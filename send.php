<body background="images\back.jpe">
<?

include "functions.php";

//Пишем сообщение
$file = fopen ("data/mail/".$to."/rec.".time(), "w");
fputs ($file, "<font color=yellow><b>".getdata($login, 'hero', 'name')."</b></font><br><font color=green><b>(".$login.")</b></font><br>\n");
fputs ($file, $txt."<br>");
fclose ($file);

change ($to, 'status', 'f2', '1');
ban();

if ($where == 0)
{
	?>
	<script>
	window.location.href("game.php?action=1");
	</script>
	<?
}
	else
	{
	?>
		<script>
		window.close();
		</script>
	<?
	}