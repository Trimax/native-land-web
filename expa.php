<title>Таблица опыта</title>
<body background="images/back.jpe">
<link rel='stylesheet' type='text/css' href='style.css'/>
<center>
<table border=1 CELLSPACING=0 CELLPADDING=0 width=80%>
<tr><td align=center width=20%>Уровень</td><td align=center>Опыт для следующего</td></tr>
<?

//Рисуем таблицу
for ($i = 2; $i < $limit; $i++)
{
  $expa = round(60*pow(($i-1), 1.4));
	echo("<tr><td align=center width=20%>".$i."</td><td align=center>".$expa."</td></tr>");
}
?>
</table>
</center>
