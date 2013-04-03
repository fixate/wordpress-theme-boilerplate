<?php

class Mailman
{
	public $errors = array();
	public $subject = '';
	public $message = '';
	public $headers = '';

	public $data = array();

	public function __construct($data)
	{
		$this->data = $data;
	}

	public function val_email($email)
	{
		if (!ereg("^[^@]{1,64}@[^@]{1,255}$", $email)) {
			// Email invalid because wrong number of characters
			// in one section or wrong number of @ symbols.
			return false;
		}

		// Split it into sections to make life easier
		$email_array = explode("@", $email);
		$local_array = explode(".", $email_array[0]);
		for ($i = 0; $i < sizeof($local_array); $i++) {
			if (!ereg("^(([A-Za-z0-9!#$%&'*+/=?^_`{|}~-][A-Za-z0-9!#$%&'*+/=?^_`{|}~\.-]{0,63})|(\"[^(\\|\")]{0,62}\"))$",//"
								$local_array[$i])) {
				return false;
			}
		}
		// Check if domain is IP. If not,
		// it should be valid domain name
		if (!ereg("^\[?[0-9\.]+\]?$", $email_array[1])) {
			$domain_array = explode(".", $email_array[1]);
			if (sizeof($domain_array) < 2) {
				return false; // Not enough parts to domain
			}

			for ($i = 0; $i < sizeof($domain_array); $i++) {
				if (!ereg("^(([A-Za-z0-9][A-Za-z0-9-]{0,61}[A-Za-z0-9])|([A-Za-z0-9]+))$",
									$domain_array[$i])) {
					return false;
				}
			}
		}
		return true;
	}

	public function validate()
	{
		$this->errors = array();

		extract($this->data, EXTR_SKIP);
		$result = true;

		if (empty($mail_name)) {
			$result = false;
			$this->errors[] = array('mail_name', 'A name must be entered.');
		}

		if (empty($mail_email)) {
			$result = false;
			$this->errors[] = array('mail_email', 'An email address must be entered.');
		} elseif (!$this->val_email($mail_email)) {
			$result = false;
			$this->errors[] = array('mail_email', 'Email address is invalid.');
		}

		if (empty($mail_message)) {
			$result = false;
			$this->errors[] = array('mail_message', 'A message must be entered.');
		}

		return $result;
	}

	public function prepare($template)
	{
		extract($this->data, EXTR_SKIP);

		ob_start();
		include($template);

		$this->message = ob_get_contents();
		ob_end_clean();

		$this->subject = sprintf($this->subject, $mail_name);
		$this->headers  = 'MIME-Version: 1.0' . "\r\n";
		$this->headers .= 'Content-type: text/html; charset=iso-8859-1' . "\r\n";
		$this->headers .= "From: {$mail_name} <{$mail_email}>\r\n";

		return $this;
	}

	public function with($meta)
	{
		$meta_fields = array('subject');

		foreach ($meta_fields as $f) {
			if (isset($meta[$f])) {
				$this->{$f} = $meta[$f];
			}
		}

		return $this;		
	}

	public function to($email)
	{
		if (!$this->validate()) {
			return $this;
		}

		$this->prepare('templates/request.php');

		$this->errors = array();
		if (!mail($email, $this->subject, $this->message, $this->headers)) {
			$this->errors[] = 'Error sending mail';
		} else {
			$this->data = array();
		}

		return $this;
	}

	public function gives($key, $default = '')
	{
		if (isset($this->data[$key])) {
			return $this->data[$key];
		}

		return $default;
	}

	public function talks_to_you() 
	{
		if (!self::has_post()) {
			return;
		}

		if (empty($this->errors)) {
			echo '<div class="alert alert-success">';
			echo '<p>Your message has been sent! We\'ll be in contact soon.</p>';
			echo '</div>';
		} else {
			echo '<div class="alert alert-warning">';
			echo '<p>The following errors occured:</p>';
			echo '<ul>';
			foreach ($this->errors as $error) {
				echo "<li>{$error[1]}</li>";
			}
			echo '</ul>';
			echo '</div>';
		}
	}

	public static function delivers($post)
	{
		$class = __CLASS__;
		$mailman = new $class($post);

		return $mailman;
	}

	public static function no_work()
	{
		$class = __CLASS__;
		$mailman = new $class(array());

		return $mailman;
	}

	public static function has_post()
	{
		return !empty($_POST);
	}
}