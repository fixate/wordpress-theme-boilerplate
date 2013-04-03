<?php
/**
 * The template for displaying a "No posts found" message.
 *
 * @package WordPress
 * @subpackage Theme_Name
 * @since Theme Name 1.0
 */
global $mailman;
?>
<?php if ($mailman->has_notifications()): ?>
	<?php foreach ($mailman->notifications() as $type => $messages): ?>
		<div class="alert alert-<?php echo $type ?>">
			<?php foreach ($messages as $message): ?>
				<?php echo $message ?><br>
			<?php endforeach ?>
		</div>
	<?php endforeach ?>
<?php endif ?>

<h1>Get in touch <i class="icon icon-cross fc-beta fs-kilo"></i></h1>
<form method="POST" action="<?php echo $mailman->action_path() ?>">
	<div class="field prl-milli">
		<label class="field-label visuallyhidden notvisuallyhidden-ie9" for="name">Name:</label>
		<input class="d-block" id="name" name="mail_name" type="text" placeholder="Name" value="<?php echo $mailman->data('mail_name') ?>">
	</div>
	<div class="field prl-milli">
		<label class="field-label visuallyhidden notvisuallyhidden-ie9" for="email">
			Email:
		</label>
		<input class="d-block" id="email" name="mail_email" type="text" placeholder="Email" value="<?php echo $mailman->data('mail_email') ?>">
	</div>
	<div class="field prl-milli">
		<label class="field-label visuallyhidden notvisuallyhidden-ie9" for="message">Message:</label>
		<textarea class="d-block" name="mail_message" id="message" placeholder="Message" cols="30" rows="10"><?php echo $mailman->data('mail_message') ?></textarea>
	</div>
	<div class="field ar">
		<label for="submit">
			<input class="btn" type="submit" value="submit" id="submit" name="submit">
		</label>
	</div>
</form>