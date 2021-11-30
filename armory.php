<?
include "functions.php";

$lg = trim($HTTP_COOKIE_VARS["nativeland"]);
if ($lg != $login) 
  exit();

FromBattle($lg);

//Продажа
if ($action == 2)
{

	//Выбран ли предмет
	if (!empty($myitems))
	{
		//Получаем цену предмета
		$cena = round(getfrom('name', $myitems, 'allitems', 'cena')*getdata($login, 'economic', 'curse')*0.5);

		//Добавляем эти деньги
		change ($login, 'economic', 'money', getdata($login, 'economic', 'money')+$cena);

    //Снимаем вес
    ChangeWeight($login, -4);

		//Убираем предмет
		kickitem($login, $myitems);
	}
}

//Покупка
if ($action == 3)
{

	//Выбран ли предмет
	if (!empty($item))
	{
		//Получаем цену предмета
		$cena = 200*round(getfrom('name', $item, 'allitems', 'cena')*getdata($login, 'economic', 'curse'));

		//Получаем номер предмета
		$number = getfrom('name', $item, 'allitems', 'num');

		//Получаем тип предмета
		$type = getfrom('name', $item, 'allitems', 'num');

		//Может это место у нас занято...
		$place = getfrom('name', $item, 'allitems', 'type');
		
		//Конвертируем номер в название поля
		if ($place == 1) {$field = 'golova';}
		if ($place == 2) {$field = 'shea';}
		if ($place == 3) {$field = 'telo';}
		if ($place == 4) {$field = 'tors';}
		if ($place == 5) {$field = 'palec';}
		if ($place == 6) {$field = 'leftruka';}
		if ($place == 7) {$field = 'rightruka';}
		if ($place == 8) {$field = 'nogi';}
		if ($place == 9) {$field = 'koleni';}
		if ($place == 10) {$field = 'plash';}
		if ($place == 11) {$field = 'rightruka';}

		//Какой здесь предмет
		$here = getdata($login, 'items', $field);

		if ($here == 0)
			{

      //А сможем ли унести?
      $total = getdata($login, 'status', 'timeout');
      $max = getdata($login, 'abilities', 'charism');
      if (($total+4) > $max*2)
      {
        messagebox("Ваша выносливость не позволяет брать Вам на себя больше, чем Вы уже взяли", "armory.php?login=".$login);
      }

			//А есть ли у нас столько денег
			if ($cena < getdata($login, 'economic', 'money'))
				{
				//Забираем деньги
				change ($login, 'economic', 'money', getdata($login, 'economic', 'money')-$cena);

        //Увеличиваем вес
        ChangeWeight($login, 4);

				//Ставим предмет
				change ($login, 'items', $field, $number);

				//Сообщение
				$msg = "<br>Вы успешно купили ".getfrom('num', $number, 'allitems', 'name');
				}
				else
				{
					$msg = "<br>У Вас недостаточно денег для покупки предмета. Предмет стоит ".$cena." ".getdata($login, 'economic', 'moneyname');
				}
			}
			else
				{
				$what = getfrom('num', getdata($login, 'items', $field), 'allitems', name);
				$msg = "<br>Сначала продайте ".$what;
				}
	}
}

//Продолжаем...
?>
<title>Кузница</title>
<link rel='stylesheet' type='text/css' href='style.css'/>
<body background='images/back.jpe'>
<?

//Приветствие
echo ("<center><h2> Кузница </h2>(<a href='city.php?login=$login'>В город</a>)</center><br>");
HelpMe(15, 1);
echo("<center>Добро пожаловать в кузницу, ".getdata($login, 'hero', 'name')."<br><br>");

echo("<table border=1 width=95% CELLSPACING=0 CELLPADDING=0>");
echo("<tr><td align=center width=50%>Товары в кузнице</td><td align=center>Ваши вещи</td></tr>");
echo("<tr><td align=center>");
echo("<form action='armory.php' method=post>");
echo("<input type='hidden' name='login' value=$login>");
echo("<input type='hidden' name='action' value=1>");
echo("<br><select name='part' style='background:#000099; font-WEIGHT: BOLD; COLOR: white; cursor: hand; list-style: lower-alpha; filter: Alpha(Opacity=20, FinishOpacity=100, Style=3)'>");
echo("<option value=7>Оружие</option>");
echo("<option value=6>Щиты</option>");
echo("<option value=3>Броня</option>");
echo("<option value=1>Шлемы</option>");
echo("<option value=2>Амулеты</option>");
echo("<option value=4>Пояса</option>");
echo("<option value=9>Щитки</option>");
echo("<option value=8>Сапоги</option>");
echo("<option value=5>Кольца</option>");
echo("<option value=10>Плащи</option>");
echo("<option value=11>Посохи</option>");
echo("</select>");
?>
	<br><br>
	<input type=submit value=' Выбрать ' style='background:#000099; font-WEIGHT: BOLD; COLOR: white; cursor: hand; list-style: lower-alpha; filter: Alpha(Opacity=20, FinishOpacity=100, Style=3)'>
	</form></td>
<?
echo("<td align=center>");
echo("<form action='armory.php'  method=post>");
echo("<input type='hidden' name='login' value=$login>");
echo("<input type='hidden' name='action' value=2>");
?>
	<br>
	<?
	allmyitems($login, 'myitems');
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
  $money = getdata($login, 'economic', 'money');
  echo("<tr><td colspan=4 align=center><b>Ваша наличность: $money <img src='images/menu/gold.gif'></b></td></tr>");
	echo ("<tr><td align=center>Название</td><td align=center>Изображение</td><td align=center>Описание</td><td align=center>Цена</td></tr>");
	table($part, $login);
	echo ("</table></center>");
}

echo("</td></tr></table>".$msg."</center>");
ban();

?>