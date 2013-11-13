<html>
<body>
	<h1>Aktywacja konta dla <?php echo $identity;?></h1>
	<p>Proszę kliknij w link by <?php echo anchor('user/activate/'. $id .'/'. $activation, 'aktywować Twoje konto');?>.</p>
</body>
</html>