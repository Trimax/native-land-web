<?
include "functions.php";

//Безопастность
$lg = trim($HTTP_COOKIE_VARS["nativeland"]);
if ($lg != $login) 
  exit();
ban();

FromBattle($lg);

//Если уже послали проклятье...
if (!empty($action))
{

	//Посылаем проклятье
	if ($action == 1)
	{

		//Уж не на себя ли?
		if ($login == $opp)
		{
			?>
			<title>Гильдия магов</title>
			<link rel='stylesheet' type='text/css' href='style.css'/>
			<body background='images/back.jpe'>
			<center>Вы не можете проклять своё собственное государство</center>
			<?
			echo("<center><h2>Гильдия магов</h2>(<a href='mguild.php?login=$login'>Назад</a>)</center><br>");
			exit();
		}	else
		{
			//Считаем цену
			$k = 1000;
			$curse = getdata($login, 'economic', 'curse');
			$rw[0] = $event;
			$rw[1] = getfrom('num', $event, 'events', 'name');
			$rw[2] = getfrom('num', $event, 'events', 'effect');
			$rw[3] = getfrom('num', $event, 'events', 'how');
			if ($rw[2] == 1) {$k = 250;}
			if ($rw[2] == 2) {$k = 5000;}
			$cena = $curse*$rw[3]*$k;

			//А денег хватает?
			if (($cena-1) > getdata($login, 'economic', 'money'))
			{
				?>
				<title>Гильдия магов</title>
				<link rel='stylesheet' type='text/css' href='style.css'/>
				<body background='images/back.jpe'>
				<center>У Вас недостаточно денег для прокляться</center>
				<?
				echo("<center><h2>Гильдия магов</h2>(<a href='mguild.php?login=$login'>Назад</a>)</center><br>");
				exit();
			} else
			{
				//Проклинаем
				$day = date('l');
				change ($opp, 'inf', 'fld8', '0');

				//Пишем сегодняшний день
				if ($day == 'Monday') {$next = 1;}
				if ($day == 'Tuesday') {$next = 2;}
				if ($day == 'Wednesday') {$next = 3;}
				if ($day == 'Thursday') {$next = 4;}
				if ($day == 'Friday') {$next = 5;}
				if ($day == 'Saturday') {$next = 6;}
				if ($day == 'Sunday') {$next = 7;}
				change ($opp, 'inf', 'fld9', $next);

				//Сообщение
				sms($opp, getdata($login, 'hero', 'name'), "Проклинаю тебя! На твоё королевство напускаю беду.");

				//Снимаем деньги и выводим результат
				change ($login, 'economic', 'money', getdata($login, 'economic', 'money')-$cena);
				?>
				<title>Гильдия магов</title>
				<link rel='stylesheet' type='text/css' href='style.css'/>
				<body background='images/back.jpe'>
				<center>Проклятье послано</center>
				<?
				echo("<center><h2>Гильдия магов</h2>(<a href='mguild.php?login=$login'>Назад</a>)</center><br>");
				exit();
			}
		}
	}
	moveto('mguild.php?login='.$login);
}

//Создаём таблицу с проклятьями
function prtable($login)
{
	$curse = getdata($login, 'economic', 'curse');
	$mn = getdata($login, 'economic', 'moneyname');
	$ath = mysql_query("select * from events;");
	if ($ath)
	{
		while ($rw = mysql_fetch_row($ath))
		{
			//Коэффициент стоимости
			$k = 1000;
			if ($rw[2] == 1) {$k = 250;}
			if ($rw[2] == 2) {$k = 5000;}
			$cena = $curse*$rw[3]*$k;
			echo("<tr><td width = 25% align=center>".$rw[1]."<form action='mguild.php' method=post><input type='hidden' name='action' value=1><input type='hidden' name='event' value='".$rw[0]."'><input type='hidden' name='login' value='".$login."'><input type='submit' value='Проклять'  style='background:#000099; font-WEIGHT: BOLD; COLOR: white; cursor: hand; list-style: lower-alpha; filter: Alpha(Opacity=20, FinishOpacity=100, Style=3)'></td><td align=center>".$cena." ".$mn."</td>");
			echo ("<td align=center>");
			userlist('opp');
			echo ("</form></td></tr>");
		}
	}
}

//Продолжаем...
?>
<title>Гильдия магов</title>
<link rel='stylesheet' type='text/css' href='style.css'/>
<body background='images/back.jpe'>
<?

//Приветствие
echo("<center><h2> Гильдия магов </h2>(<a href='city.php?login=$login'>В город</a>)</center><br>");
HelpMe(19, 1);
echo("<center>Добро пожаловать в гильдию магов, ".getdata($login, 'hero', 'name')."<br><br>");
echo("Наша гильдия занимается проклятиями врагов государств. Вы можете наслать проклятье на врага.");
?>
<table border=1 CELLSPACING=0 CELLPADDING=0>
<tr><td align=center>Проклятье</td><td align=center>Цена</td><td align=center>Жертва</td></tr>
<?

//Выводим список проклятий
prtable($login);
echo ("</table></center>");
?>