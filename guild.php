<?
include "functions.php";

$lg = trim($HTTP_COOKIE_VARS["nativeland"]);
if ($lg != $login)
  exit();
ban();

FromBattle($lg);

//Продажа
if ($action == 2)
{

	//Выбран ли предмет
	if (!empty($myitems))
	{
		//Получаем цену предмета
		$cena = round(getfrom('name', $myitems, 'allcasts', 'cena')*getdata($login, 'economic', 'curse')*0.5);

		//Добавляем эти деньги
		change ($login, 'economic', 'money', getdata($login, 'economic', 'money')+$cena);

		//Убираем предмет
		kickcitem($login, $myitems);
	}
}

//Покупка
if ($action == 3)
{

	//Выбран ли предмет
	if (!empty($item))
	{
		//Получаем цену предмета
		$cena = 6*round(getfrom('name', $item, 'allcasts', 'cena')*15*getdata($login, 'economic', 'curse'));

		//Получаем номер предмета
		$number = getfrom('name', $item, 'allcasts', 'num');

		//Ищем свободное место
		$free = 0;
		$lg = $login;
		if (getdata($lg, 'magic', 'cast6') == 0) {$free = 6;}
		if (getdata($lg, 'magic', 'cast5') == 0) {$free = 5;}
		if (getdata($lg, 'magic', 'cast4') == 0) {$free = 4;}
		if (getdata($lg, 'magic', 'cast3') == 0) {$free = 3;}
		if (getdata($lg, 'magic', 'cast2') == 0) {$free = 2;}
		if (getdata($lg, 'magic', 'cast1') == 0) {$free = 1;}		

		//Свободно ли место
		if ($free != 0)
			{
			//А есть ли у нас столько денег
			if ($cena < getdata($login, 'economic', 'money'))
				{
				//Забираем деньги
				change ($login, 'economic', 'money', getdata($login, 'economic', 'money')-$cena);

				//Ставим предмет
				change ($login, 'magic', 'cast'.$free, $number);

				//Сообщение
				$msg = "<br>Вы успешно купили руну ".getfrom('num', $number, 'allcasts', 'name');
				}
				else
				{
					$msg = "<br>У Вас недостаточно денег для покупки предмета. Предмет стоит ".$cena." ".getdata($login, 'economic', 'moneyname');
				}
			}
			else
				{
				$msg = "Все 6 позиций для магических рун заняты";
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
echo ("<center><h2> Гильдия магов </h2>(<a href='city.php?login=$login'>В город</a>)</center><br>");
HelpMe(18, 1);
echo("<center>Добро пожаловать в гильдию магов, ".getdata($login, 'hero', 'name')."<br><br>");

echo("<table border=1 width=95% CELLSPACING=0 CELLPADDING=0>");
echo("<tr><td align=center width=50%>Заклинания в гильдии</td><td align=center>Ваши заклинания</td></tr>");
echo("<tr><td align=center>");
echo("<form action='guild.php' method=post>");
echo("<input type='hidden' name='login' value=$login>");
echo("<input type='hidden' name='action' value=1>");
echo("<br><select name='part' style='background:#000099; font-WEIGHT: BOLD; COLOR: white; cursor: hand; list-style: lower-alpha; filter: Alpha(Opacity=20, FinishOpacity=100, Style=3)'>");
echo("<option value=1>Магия природы</option>");
echo("<option value=2>Боевая магия</option>");
echo("<option value=3>Магия разума</option>");
echo("</select>");
?>
	<br><br>
	<input type=submit value=' Выбрать ' style='background:#000099; font-WEIGHT: BOLD; COLOR: white; cursor: hand; list-style: lower-alpha; filter: Alpha(Opacity=20, FinishOpacity=100, Style=3)'>
	</form></td>
<?
echo("<td align=center>");
echo("<form action='guild.php'  method=post>");
echo("<input type='hidden' name='login' value=$login>");
echo("<input type='hidden' name='action' value=2>");
?>
	<br>
	<?
	allmycasts($login, 'myitems');
	?>
	<br><br>
	<input type=submit value=' Продать ' style='background:#000099; font-WEIGHT: BOLD; COLOR: white; cursor: hand; list-style: lower-alpha; filter: Alpha(Opacity=20, FinishOpacity=100, Style=3)'>
	</form></td>
	<tr><td colspan=2 align=center>
<?

//Списки
if ($action == 1)
{
	//Выводим таблицу с вещами
	echo ("<center><table border=1 width=90% CELLSPACING=0 CELLPADDING=0>");
	echo ("<tr><td align=center>Название</td><td align=center>Изображение</td><td align=center>Описание</td><td align=center>Цена</td></tr>");
	ctable($part, $login);
	echo ("</table></center>");
}

echo("</td></tr></table>".$msg."</center>");

?>