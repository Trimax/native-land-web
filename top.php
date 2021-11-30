<body background="images/back.jpe">
<link rel='stylesheet' type='text/css' href='style.css'/>
<title>Рейтинг игроков</title>
<script>
function playersinfo(name)
{
	var s;
	s = 'info.php?name=' + name;
	window.open(s, null,'toolbar=no, location=no, menubar=no,scrollbars=yes,width=656,height=480');
}
</script>

<?
	include "functions.php";
	ban();
	
	$ath = mysql_query("select * from hero;");
	$count = 0;
	if ($ath)
	{
		//Ищем всех
		while ($rw = mysql_fetch_row($ath))
		{
			//Инфа о пользователях
			$users[$count] = $rw[0];
			$names[$count] = $rw[1];
			$expas[$count] = $rw[2];
			$levels[$count] = $rw[3];
			$count++;
		}
	}

	//Сортируем всех пользователей по опыту
	for($i = 0; $i < $count; $i++)
	{
		for($j = $i+1; $j < $count; $j++)
		{
			if ($expas[$j] > $expas[$i])
			{
				//Reverse expa
				$y = $expas[$i];
				$expas[$i] = $expas[$j];
			    $expas[$j] = $y;
	
				//Reverse users
				$y = $users[$i];
				$users[$i] = $users[$j];
			    $users[$j] = $y;

				//Reverse names
				$y = $names[$i];
				$names[$i] = $names[$j];
				$names[$j] = $y;

				//Reverse levels
				$y = $levels[$i];
				$levels[$i] = $levels[$j];
			    $levels[$j] = $y;
			} //End of condition
		} //End of "j" cycle
	} // End of "i" cycle

	//Заголовок страницы
	echo("<center><h1>Рейтинг игроков</h1></center>");
	?>
	<center>
	<a href="#rul">	<b>Правила участия в еженедельном рейтинге</b><br>
	</center>
	<?

	//Выводим результат в таблицу
	echo("<center><table border=1 CELLPADDING=0 CELLSPACING=0 width=90%>");
	echo("<tr><td align=center>Место</td><td align=center>Логин</td><td align=center>Имя героя</td><td align=center>Уровень</td><td align=center>Опыт</td></tr>");
	for ($i = 0; $i < $count; $i++)
	{
		//Призовые места выделяем жёлтым
		if ($i < 10)
		{
			//Пятёрка лучших
			if ($i < 5)
			{
				//Тройка лидеров
				if ($i < 3)
				{
					echo("<tr><td align=center><a href=javascript:playersinfo('".$users[$i]."')><b><font color=#006600>".($i+1)."</font></b></a></td><td align=center><a href=javascript:playersinfo('".$users[$i]."')><b><font color=#006600>".$users[$i]."</font></b></a></td><td align=center><a href=javascript:playersinfo('".$users[$i]."')><b><font color=#006600>".$names[$i]."</font></b></a></td><td align=center><a href=javascript:playersinfo('".$users[$i]."')><b><font color=#006600>".$levels[$i]."</font></b></a></td><td align=center><a href=javascript:playersinfo('".$users[$i]."')><b><font color=#006600>".$expas[$i]."</font></b></a></td></tr>");
				} else
				{
					echo("<tr><td align=center><a href=javascript:playersinfo('".$users[$i]."')><b><font color=yellow>".($i+1)."</font></b></a></td><td align=center><a href=javascript:playersinfo('".$users[$i]."')><b><font color=yellow>".$users[$i]."</font></b></a></td><td align=center><a href=javascript:playersinfo('".$users[$i]."')><b><font color=yellow>".$names[$i]."</font></b></a></td><td align=center><a href=javascript:playersinfo('".$users[$i]."')><b><font color=yellow>".$levels[$i]."</font></b></a></td><td align=center><a href=javascript:playersinfo('".$users[$i]."')><b><font color=yellow>".$expas[$i]."</font></b></a></td></tr>");
				}
			} else
			{
				echo("<tr><td align=center><a href=javascript:playersinfo('".$users[$i]."')><b><font color=#CC0033>".($i+1)."</font></b></a></td><td align=center><a href=javascript:playersinfo('".$users[$i]."')><b><font color=#CC0033>".$users[$i]."</font></b></a></td><td align=center><a href=javascript:playersinfo('".$users[$i]."')><b><font color=#CC0033>".$names[$i]."</font></b></a></td><td align=center><a href=javascript:playersinfo('".$users[$i]."')><b><font color=#CC0033>".$levels[$i]."</font></b></a></td><td align=center><a href=javascript:playersinfo('".$users[$i]."')><b><font color=#CC0033>".$expas[$i]."</font></b></a></td></tr>");
			}
		} else
		{
			if ($expas[$i] > 10)
			{
				echo("<tr><td align=center>".($i+1)."</td><td align=center><a href=javascript:playersinfo('".$users[$i]."')><font color=blue><b>".$users[$i]."</b></font></a></td><td align=center><b>".$names[$i]."</b></td><td align=center><b>".$levels[$i]."</b></td><td align=center><b>".$expas[$i]."</b></td></tr>");
			} else
			{
				echo("<tr><td align=center><b><font color=black>".($i+1)."</font></b></td><td align=center><a href=javascript:playersinfo('".$users[$i]."')><b><font color=black>".$users[$i]."</font></b></a></td><td align=center><b><font color=black>".$names[$i]."</font></b></td><td align=center><b><font color=black>".$levels[$i]."</font></b></td><td align=center><b><font color=black>".$expas[$i]."</font></b></td></tr>");
			}
		}
	}
	echo("</table></center>");
?>

<br><A NAME="RUL"><b>Правила участия в еженедельном рейтинге</b></A><br>
1) К участию в рейтинге не допускаются администраторы и разработчкики игры, т.е. Admin и Teider.<br>
2) Если игрок, занявший призовое место уличён в обмане (каким-либо образом), он тутже выбывает из рейтинга и его опыт становится равным нулю (при этом, уровень и навыки сохраняются)<br>
<br><font color=darkblue><b>Призовые места</b></font><br>
Призовыми местами считаются первые десять мест, причём:<br>
<font color=#006600>1)</font> Занявшим с 10 по 6 начисляется дополнительные очки действия (100 ОД)<br>
<font color=yellow>2)</font> Занявшим 5 и 4 место начисляются дополнительные ресурсы (по 50 каждого)<br>
<font color=#CC0033>3)</font> Занявшим с 3 по 1 место на счёт в игровом банке переводятся деньги (10000 по курсу 1 к 1)<br>

<font color=black>0)</font> Игроки, отмеченные чёрным цветом будут удалены 1 числа следующего месяца<br>
<br>P.S. Призы ещё не определены однозначно. На сколько я понимаю, тем, кто занимает первые места не нужны ни деньги, ни ресурсы, ни ОД. Подсказывайте. :)<br>
