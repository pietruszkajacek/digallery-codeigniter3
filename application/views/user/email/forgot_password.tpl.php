<html>
<body>
	<h1>Resetowanie hasła dla <?php echo $identity;?></h1>
	<p>Proszę kliknij w link <?php echo anchor('user/reset_password/'. $forgotten_password_code, 'by zresetować hasło');?>.</p>
</body>
</html>