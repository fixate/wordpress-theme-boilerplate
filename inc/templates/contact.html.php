Onine request from <?php echo "{$mail_name} <{$mail_email}>" ?> 
%body%
<html>
<body>
  <h2 style='font: 15px Tahoma, "Trebuchet MS", sans-serif;letter-spacing: 1px; margin-bottom: 21px; padding-bottom: 12px; border-bottom: 1px solid #528165;'>Contact details</h2>
  <p style='font: 12px Verdana, "Trebuchet MS", sans-serif;'>Name: <?= $mail_name ?><br/>
  Email: <a href="mailto:<?= $mail_email ?>"><?= $mail_email ?></a><br/>
  <br/></p>
  <h2 style='font: 15px Tahoma, "Trebuchet MS", sans-serif; letter-spacing: 1px; margin-bottom: 21px; padding-bottom: 12px; border-bottom: 1px solid #528165;'>Message</h2>
  <p style='font: 12px Verdana, "Trebuchet MS", sans-serif;'><?= $mail_message ?></p>
  <p>---</p>
</body>
</html>