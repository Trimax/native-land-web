<body background="images/back.jpe">
<link rel='stylesheet' type='text/css' href='style.css'/>
<title>Филиал ассоциации по контролю кланов</title>

<?
include "functions.php";
ban();

//Безопастность
$lg = trim($HTTP_COOKIE_VARS["nativeland"]);
if ($lg != $login) 
  exit();

FromBattle($lg);

//Получаем данные о кланах
$ath = mysql_query("select * from clans;");
$count = 0;
if ($ath)
{
	//Ищем всех
	while ($rw = mysql_fetch_row($ath))
	{
		//Инфа о пользователях
		$name[$count] = $rw[0];
		$admin[$count] = $rw[1];
		$desc[$count] = $rw[2];
		$link[$count] = $rw[3];
		$logo[$count] = $rw[4];
		$gerb[$count] = $rw[5];
		$nalog[$count] = $rw[6];
		$bill[$count] = $rw[7];
		$super1[$count] = $rw[8];
		$super2[$count] = $rw[9];
		$super3[$count] = $rw[10];
		$count++;
	}
}

//Выводим таблицу кланов
echo("<center><h1>Филиал администрации кланов</h1><a href=city.php?login=".$login.">Вернуться назад в город</a><br><table align=center border=1 cellspacing=0 cellpading=0 width=90%>\n");
echo("<tr><td align=center>Клан</td><td align=center>Администратор</td><td align=center>Действие</td></tr>");

//Вывод
for ($i = 0; $i < $count; $i++)
{
	echo("<tr><td align=center>".$name[$i]."</td><td align=center>".$admin[$i]."</td><td align=center><br><form action='claninfo.php' method=post><input type='hidden' name='login' value='".$login."'><input type='hidden' name='admin' value='".$admin[$i]."'><input type='submit' value='Смотреть' style='background:#000099; font-WEIGHT: BOLD; COLOR: white; cursor: hand; list-style: lower-alpha; filter: Alpha(Opacity=20, FinishOpacity=100, Style=3)'></form></td></tr>");
}
echo("</table>");
HelpMe(12, 0);
echo("</center>");

?>