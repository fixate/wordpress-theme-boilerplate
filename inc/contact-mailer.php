<?php

require_once(FIX8_THEME_BASEPATH.'/inc/mail/class.mailer.php');

function required(&$errors, $name, $str)
{
	if (!isset($str) || empty($str)) {
		$key = key($name);
		$cname = ucfirst(htmlspecialchars($name[$key]));
		$errors[strtolower($key)] = $cname;
		return false;
	}
	return true;
}

function email(&$errors, $name, $email)
{
	$msg = "Email invalid";
	if (!ereg("^[^@]{1,64}@[^@]{1,255}$", $email)) {
		// Email invalid because wrong number of characters 
		// in one section or wrong number of @ symbols.
		$errors[$name] = $msg;
		return false;
	}
	// Split it into sections to make life easier
	$email_array = explode("@", $email);
	$local_array = explode(".", $email_array[0]);
	for ($i = 0; $i < sizeof($local_array); $i++) {
		if (!ereg("^(([A-Za-z0-9!#$%&'*+/=?^_`{|}~-][A-Za-z0-9!#$%&'*+/=?^_`{|}~\.-]{0,63})|(\"[^(\\|\")]{0,62}\"))$",//"
							$local_array[$i])) {
			$errors[$name] = $msg;
			return false;
		}
	}
	// Check if domain is IP. If not, 
	// it should be valid domain name
	if (!ereg("^\[?[0-9\.]+\]?$", $email_array[1])) {
		$domain_array = explode(".", $email_array[1]);
		if (sizeof($domain_array) < 2) {
			$errors[$name] = $msg;
			return false; // Not enough parts to domain
		}
		for ($i = 0; $i < sizeof($domain_array); $i++) {
			if (!ereg("^(([A-Za-z0-9][A-Za-z0-9-]{0,61}[A-Za-z0-9])|([A-Za-z0-9]+))$",
								$domain_array[$i])) {
				$errors[$name] = $msg;
				return false;
			}
		}
	}
	return true;
}

$_name    = $_POST['client_name'];
$_email   = $_POST['client_email'];
$_comment = $_POST['client_comment'];

// Validations
$errors = array();
required($errors, array('client_name' => 'Name'), $_name);
if (required($errors, array('client_email' => 'Email Address'), $_email))
	email($errors, 'client_email', $_email);
required($errors, array('client_comment' => 'Comment'), $_comment);

if (count($errors) > 0) {
	$error = 'Please enter your '.implode(', ', $errors).'.';
	return;
}

require_once(FIX8_THEME_BASEPATH.'/inc/mail/class.mailer.php');

//TODO: Change to proper email
$recipient = new FIX8_Recipient('[site]', 'larrywp@themousepotatowebsite.co.za');
$template = FIX8_Template::load('contact');

$mailer = new FIX8_Mailer($recipient, $template);

$mailer->variables = array(
		'sender_name' => $_name,
		'sender_email' => $_email,
		'sender_comment' => $_comment
);

$result = $mailer->send();

if ($result === true) {
	$notify = 'Thank you for contacting us, we will be in touch!';
	unset($_name);
	unset($_email);
	unset($_comment);
} else {
	$error = (is_wp_error($result)) ?
		$result->get_error_message() :
		'Something has gone wrong and we are unable to send your comments at this time. Please try again later.';
}