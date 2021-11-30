<?

//Если не указано имя пользователя, то выкинуть юзера нафиг
$lg = trim($HTTP_COOKIE_VARS["nativeland"]);
$pw = trim($HTTP_COOKIE_VARS["password"]);

//Нет подтверждения
if ($yes == 0)
{
	?>
	<html>
	<head>
	<link rel='stylesheet' type='text/css' href='style.css'/>
	<title>Native Land</title>
	</head>
	<body background='images\back.jpe'>
	<br><br><br><br><br>
	<center><table border=1 width=40% CELLSPACING=0 CELLPADDING=0><tr><td align=center colspan=2><b><font color=blue>Вы уверены, что хотите удалить Ваш аккаунт из игры Native Land?</b></font></td></tr><tr><td width=50% align=center><form action='kickme.php' method=post><input type='hidden' name='yes' value=1><br><input type='submit' value='  Да  ' style='background:#000099; font-WEIGHT: BOLD; COLOR: white; cursor: hand; list-style: lower-alpha; filter: Alpha(Opacity=20, FinishOpacity=100, Style=3)'></form></td><td align=center><form action='game.php' method=post><br><input type='submit' value='  Нет  ' style='background:#000099; font-WEIGHT: BOLD; COLOR: white; cursor: hand; list-style: lower-alpha; filter: Alpha(Opacity=20, FinishOpacity=100, Style=3)'></form></tr></table></center>
	<?
} else
{
		//Файл с функциями
		include "functions.php";

		//Делаем официальный выход из игры
		$new = time();
		change($lg, 'time', 'lastexit', $new);
		change($lg, 'status', 'online', '0');
		change($lg, 'inf', 'fld7', '0');
		setcookie("nativeland");
		setcookie("password");

		//Удаляем из базы пользователя
		kickuser($lg);

		//Перенаправляем на главную страницу
		moveto ('index.php');
}
?>
</body>
</html>