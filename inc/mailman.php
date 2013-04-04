<?php

require_once 'vendor/phpmailer/class.phpmailer.php';

class Mailman
{
	private $notifications = null;
	
	private $validations = null;
	private $validation_messages = null;

	private $success_message = '';

	private $delivery_method = 'sendmail';

	private $templates = null;
	private $multipart = false;
	private $mailer = null;

	private $from_fields = null;

	private $data = null;

	public function __construct($options = array())
	{
		$this->mailer = new PHPMailer();

		if (isset($options['to'])) {
			$to = $options['to'];
			if (is_array($to)) {
				$this->mailer->AddAddress($to[0], $to[1]);
			} else {
				$this->mailer->AddAddress($to);
			}
		}

		if (isset($options['from'])) {
			$from  = $options['from'];
			if (is_array($from)) {
				$this->mailer->SetFrom($from[0], $from[1]);
			} else {
				$this->mailer->SetFrom($from);
			}
		}

		if (isset($options['from_fields'])) {
			$this->from_fields = $options['from_fields'];
		}

		if (isset($options['reply_to'])) {
			$reply_to  = $options['reply_to'];
			if (is_array($reply_to)) {
				$this->mailer->AddReplyTo($reply_to[0], $reply_to[1]);
			} else {
				$this->mailer->AddReplyTo($reply_to);
			}
		}

		if (isset($options['subject'])) {
			$this->mailer->Subject = $options['subject'];
		}

		if (isset($options['delivery_method'])) {
			$this->delivery_method = $options['delivery_method'];
		}

		if ($this->delivery_method == 'smtp') {
			$smtp = isset($options['smtp']) ? $options['smtp'] : null;
			if (!$smtp || !isset($smtp['host']) || !isset($smtp['port'])) {
				throw new Exception('SMTP hostname and port required for SMTP delivery method.');
			}

			$this->mailer->Host = $smtp['host'];
			$this->mailer->Port = $smtp['port'];
			if (isset($smtp['auth']) && $smtp['auth']) {
				$this->mailer->Username = $smtp['username'];
				$this->mailer->Password = $smtp['password'];
			}
		}

		if (isset($options['validates'])) {
			$this->validations = $options['validates'];
		}

		if (isset($options['validation_messages'])) {
			$this->validation_messages = $options['validation_messages'];
		}		

		if (isset($options['templates'])) {
			$this->set_templates($options['templates']);
		}

		$this->success_message = isset($options['success_message']) ?
			 $options['success_message'] :
			 'Your mail has been successfully sent!';
	}

	public function notifications($type = false)
	{
		if (!$this->notifications) {
			$this->clear_notifications();
		}

		if ($type) {
			return isset($notifications[$type]) ? $notifications[$type] : null;
		}

		return $this->notifications;
	}

	public function data($name = null)
	{
		if (!$name) {
			return $this->data;
		}

		if (isset($this->data[$name])) {
			return $this->data[$name];
		}

		return null;
	}

	public function has_notifications()
	{
		$notifications =& $this->notifications();
		return count($notifications['error'])   > 0 ||
					 count($notifications['info'])    > 0 ||
					 count($notifications['success']) > 0;
	}

	public function action_path()
	{
		// TODO: Using wordpress
		return add_query_arg('use_mailman', '1');
	}

	public function set_templates($templates)
	{
		if (!is_array($templates)) {
			// TODO: Use file extension instead of hardcoding
			$templates = array('text' => $templates);
		}

		$this->templates = $templates;
		$this->multipart = is_array($templates) && count($templates) > 1;

		return $this;
	}

	public function with($data)
	{
		if (!$this->data) {
			$this->data = array();
		}
		$this->data = array_merge($this->data, $data);
		return $this;		
	}

	public function get_mailer()
	{
		return $mailer;
	}

	public function add_validation($field, $callback)
	{
		if (isset($this->validations[$field])) {
			$this->validations[$field][] = $callback;
		} else {
			$this->validations[$field] = array($callback);
		}

		return $this;
	}

	public function render_notifications($options = array())
	{
		if (!$this->has_notifications()) {
			return '';
		}
		
		$result  = '';
		foreach ($this->notifications() as $type => $messages) {
			$class = sprintf(
				isset($options['class']) ? $options['class'] : "alert alert-%s",
				$type);

			if (count($messages)) {
				$result .= "<div class='{$class}'>";
				foreach ($messages as $message) {
					$result .= $message.'<br>';
				}
				$result .= '</div>';
			}
		}

		return $result;
	}

	public function validate()
	{
		if (!$this->validations) { return true; }

		$result = true;

		foreach ($this->data as $field => $value) {
			if (isset($this->validations[$field])) {
				$field_name = $field;
				foreach ($this->validations[$field] as $cb => $message) {
					// Friendly name for field
					if (is_string($message) && $message[0] == '@') {
						$field_name = substr($message, 1);		
						continue;
					}

					// No message specified
					if (is_int($cb)) {
						$cb = $message;
						$message = $this->get_validation_message($cb);
					}


					# Validate
					if (!call_user_func_array($cb, array($value))) {
						$result = false;
						$this->notifications['error'][] = sprintf($message, $field_name);
						break;
					}
				}
			}
		}

		return $result;
	}


	public function deliver($data = array())
	{
		$this->clear_notifications()->with($data);

		if (!$this->validate()) { return; }

		$mailer = &$this->mailer;

		// Prepare mail
		$subject = null;
		$this->set_mailer_delivery_method();

		$this->set_from();

		$is_html = isset($this->templates['html']);
		$mailer->IsHTML($is_html);
		foreach ($this->templates as $type => $path) {
			list($subj, $body) = $this->parse_template($path);

			if ($subj) {
				$subject = $subj;
			}

			switch ($type) {
				case 'html': {
					$mailer->Body = $body;
				}
				break;
				case 'text': {
					if ($is_html) {
						$mailer->AltBody = $body;
					} else {
						$mailer->Body = $body;
					}
				}
				break;
				default: throw new Exception('Unsupported mail message type');
			}
		}

		if ($subject) {
			$mailer->Subject = $subject;
		}

		if (!$mailer->Send()) {
			$this->notifications['error'][] = $mailer->ErrorInfo;
			return $this;
		}

		$this->notifications['success'][] = $this->success_message;
		$this->data = array();
		return $this;
	}

	/**
	 * Initializes global variable $mailman and hooks up Wordpress
	 */
	public static function wp_bootstrap($options)
	{
		if (!function_exists('get_bloginfo')) {
			die('Cannot bootstrap mailman - Wordpress is not installed.');
		}

		global $mailman;
		$class = __CLASS__;
		$GLOBALS['mailman'] = $mailman = new $class($options);

		function mailman_parse_request(&$request) {
			$query_vars = &$request->query_vars;

			if (isset($query_vars['use_mailman']) && $_POST) {
				global $mailman;
				$mailman->deliver($_POST);
			}
		} add_action('parse_request', 'mailman_parse_request');

		function mailman_query_vars($vars) {
			array_push($vars, 'use_mailman');
			return $vars;
		} add_filter('query_vars', 'mailman_query_vars');

		return $mailman;
	}

	protected function set_mailer_delivery_method() 
	{
		switch ($this->delivery_method) {
			case 'sendmail': 
				$this->mailer->IsSendmail();
			break;
			case 'smtp': 
				$this->mailer->IsSMTP();
			break;
			default: throw new Exception("Unsupported delivery method '{$this->delivery_method}.");
		}
	}

	protected function parse_template($_path)
	{
		extract($this->data, EXTR_SKIP);

		ob_start();
		include $_path;

		$body = ob_get_contents();
		ob_end_clean();

		$parts = preg_split('#%body%#', $body, 2);
		if (count($parts) == 1) {
			$body = $parts[0];			
			$subject = null;
		} else {
			list($subject, $body) = $parts;
		}

		return array(trim($subject), $body);
	}

	protected function set_from() {
		if ($ff = $this->from_fields) {
			if (!isset($this->data[$ff[0]])) {
				throw new Exception("No field named '{$ff[0]}' to set as sender email address.");
			}

			if (count($ff) > 1 && !isset($this->data[$ff[1]])) {
				throw new Exception("No field named '{$ff[1]}' to set as sender name.");
			}

			if (count($ff) > 1) {
				$this->mailer->SetFrom($this->data[$ff[0]], $this->data[$ff[1]]);
			} else {
				$this->mailer->SetFrom($this->data[$ff[0]]);
			}			
		}
	}

	protected function clear_notifications()
	{
		$this->notifications = array(
			'error'  => array(),
			'success' => array(),
			'info'    => array()
		);

		return $this;
	}

	protected function get_validation_message($callback)
	{
		return $this->validation_messages && isset($this->validation_messages[$callback]) ?
			$this->validation_messages[$callback] :
			"Validation failed for {$callback}.";
	}
}
