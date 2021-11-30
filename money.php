<?

include "functions.php";

//Фон и стиль
echo ("<html>\n<head>\n<link rel='stylesheet' type='text/css' href='style.css'/>\n<title>Native Land</title>\n</head>\n<body background='images\back.jpe'>");

//Все пользователи
function printall()
{
	echo("<center><table border=1 width=90% CELLSPACING=0 CELLPADDING=0>");
	echo("<tr><td align=center>Логин</td><td align=center width=40%>Помощь игре (рубли)</td></tr>");
	$ath = mysql_query("select * from money;");
	if ($ath)
	{
		while ($rw = mysql_fetch_row($ath))
		{
			echo("<tr><td align=center>".$rw[0]."</td><td align=center>".$rw[1]."</td></tr>");
		}
	}

	//Для админа
	if (isadmin($lg) == 1)
	{
		echo("<tr><td colspan=2 align=center>");
		echo("<form action='money.php' method=post>");
		indexuserlist('login');
		echo("</form>");
		echo("</td></tr>");
	}

	//Конец таблицы
	echo("</table></center>");
}

//Главная часть кода
echo("<center><font size=16 color=darkblue>Доска почёта</font><br>Здесь вы можете на добровольной основе внести свой вклад в оплату хостинга игры<br></center>");
printall();


?>
