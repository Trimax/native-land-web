<body background="images/back.jpe">
<title>Храм</title>
<link rel='stylesheet' type='text/css' href='style.css'/>
<?

//Подключаем модуль и определяем имя игрока
include "functions.php";
$lg = trim($HTTP_COOKIE_VARS["nativeland"]);

//Не заходим, если чужой храм
if ($lg != $login)
{
	exit();
}

FromBattle($lg);

//Может покупка бутылки
if (($action >= 5)&&($action <= 16))
{
  //Сколько надо денег
  $need = getdata($login, 'economic', 'curse')*100;
  switch($action)
  {
    case 5:
      $need=$need*100;
      break;
    case 6:
      $need=$need*50;
      break;
    case 7:
      $need=$need*25;
      break;
    case 8:
      $need=$need*20;
      break;
    case 9:
      $need=$need*10;
      break;
    case 10:
      $need=$need*5;
      break;
    case 11:
      $need=$need*100;
      break;
    case 12:
      $need=$need*50;
      break;
    case 13:
      $need=$need*25;
      break;
    case 14:
      $need=$need*100;
      break;
    case 15:
      $need=$need*50;
      break;
    case 16:
      $need=$need*25;
      break;
  }

  //Определяем все бутылки
  $weight=0;
  if (($action == 5)||($action == 8)||($action == 11)||($action == 14))
    $weight = 3;
  if (($action == 6)||($action == 9)||($action == 12)||($action == 15))
    $weight = 2;
  if (($action == 7)||($action == 10)||($action == 13)||($action == 16))
    $weight = 1;

  //Сколько уже истрачено
  $total = getdata($lg, 'status', 'timeout');
  $max = getdata($lg, 'abilities', 'charism');

  //Если нагрузка персонажа вместе с новой бутылкой более, чем можно, то нельзя
  if (($weight+$total) > $max*2)
  {
    messagebox("Ваша выносливость не позволяет брать Вам на себя больше, чем Вы уже взяли", "church.php?login=".$login);
  }

  //А денег хватает?
  $mn = getdata($lg, 'economic', 'money');
  if ($need <= $mn)
  {
    //Убираем деньги
    change($lg, 'economic', 'money', $mn-$need);

    //Добавляем вес
    change($lg, 'items', 'vrukah', $total+$weight);

    //Добавляем бутылку
    switch($action)
    {
      case 5:
        change($lg, 'bottles', 'hmaxi', getdata($lg, 'bottles', 'hmaxi')+1);
        break;
      case 6:
        change($lg, 'bottles', 'hmedi', getdata($lg, 'bottles', 'hmedi')+1);
        break;
      case 7:
        change($lg, 'bottles', 'hmini', getdata($lg, 'bottles', 'hmini')+1);
        break;
      case 8:
        change($lg, 'bottles', 'mmaxi', getdata($lg, 'bottles', 'mmaxi')+1);
        break;
      case 9:
        change($lg, 'bottles', 'mmedi', getdata($lg, 'bottles', 'mmedi')+1);
        break;
      case 10:
        change($lg, 'bottles', 'mmini', getdata($lg, 'bottles', 'mmini')+1);
        break;
      case 11:
        change($lg, 'bottles', 'pmaxi', getdata($lg, 'bottles', 'pmaxi')+1);
        break;
      case 12:
        change($lg, 'bottles', 'pmedi', getdata($lg, 'bottles', 'pmedi')+1);
        break;
      case 13:
        change($lg, 'bottles', 'pmini', getdata($lg, 'bottles', 'pmini')+1);
        break;
      case 14:
        change($lg, 'bottles', 'smaxi', getdata($lg, 'bottles', 'smaxi')+1);
        break;
     case 15:
        change($lg, 'bottles', 'smedi', getdata($lg, 'bottles', 'smedi')+1);
        break;
      case 16:
        change($lg, 'bottles', 'smini', getdata($lg, 'bottles', 'smini')+1);
        break;
    } //switch
  } else //нет денег
  {
    messagebox("У Вас недостаточно денег для покупки", "church.php?login=$login");
    exit();
  }

  moveto("church.php?login=$login");
}

//Удельная стоимость процента здоровья в металле
$us = getdata($login, 'hero', 'level');
$us2 = getdata($login, 'abilities', 'cnowledge');

//Счётчик
ban();

//Расчёт правильный!!!
$prc = getdata($login, 'hero', 'health') / $us;
$crs = getdata($login, 'economic', 'curse');
$money = getdata($login, 'economic', 'money');
$how = 100 - $prc;
$how = round($how*$crs);
$tp = 10*$crs;

//Если лечиться не надо
if (($prc*$us) == $us*100)
{
	$tp = 0;
}

//Для маны
if ($us2 != 0)
  $prc2 = 10*getdata($login, 'abilities', 'intellegence') / $us2;
  else
  $prc2 = 0;
$crs2 = getdata($login, 'economic', 'curse');
$money2 = getdata($login, 'economic', 'money');
$how2 = 100 - $prc2;
$how2 = round($how2*$crs2);
$tp2 = 10*$crs2;

//Если лечиться не надо
if (($prc2*$us2) == $us2*10)
{
	$tp2 = 0;
}

//Может пользователь уже что-то ткнул?
$login = trim($login);
if (($action == 1)&&($money > 0))
{
	//А деньги есть
	if ($ten <= $money)
	{
		//А лечить-то надо?
		if (getdata($login, 'hero', 'health') < (100*$us))
		{

			//Может он хитрит и в битве?
			if (getdata($login, 'battle', 'battle') == 0)
			{
				change ($login, 'economic', 'money', getdata($login, 'economic', 'money') - getdata($login, 'hero', 'level')*getdata($login, 'economic', 'curse'));
				change ($login, 'hero', 'health', getdata($login, 'hero', 'health') + 10*getdata($login, 'hero', 'level'));
				$mx = getdata($login, 'hero', 'level')*100;
				if (getdata($login, 'hero', 'health') > $mx)
				{
					change ($login, 'hero', 'health', $mx);
				}
			}
		}
	}
}

if (($action == 2)&&($money > 0))
{
	//Определяем, сколько надо лечить
	$hw = getdata($login, 'hero', 'level')*100-getdata($login, 'hero', 'health');

	//А деньги есть
	if ($how <= $money)
	{
		//А лечить-то надо?
		if (getdata($login, 'hero', 'health') < (100*$us))
		{

			//Может он хитрит и в битве?
			if (getdata($login, 'battle', 'battle') == 0)
			{
				change ($login, 'economic', 'money', $money - $how);
				change ($login, 'hero', 'health', getdata($login, 'hero', 'level')*100);
			}
		}
	}
}

if (($action == 3)&&($money > 0))
{
	//А деньги есть
	if ($ten <= $money2)
	{
		//А лечить-то надо?
		if (getdata($login, 'abilities', 'intellegence') < (10*$us2))
		{

			//Может он хитрит и в битве?
			if (getdata($login, 'battle', 'battle') == 0)
			{
				change ($login, 'economic', 'money', $money2 - $ten);
				change ($login, 'abilities', 'intellegence', getdata($login, 'abilities', 'intellegence') + getdata($login, 'abilities', 'cnowledge'));
				$mx = getdata($login, 'abilities', 'cnowledge')*10;
				if (getdata($login, 'abilities', 'intellegence') > $mx)
					change ($login, 'abilities', 'intellegence', $mx);
			}
		}
	}
}

if (($action == 4)&&($money > 0))
{
	//Определяем, сколько надо лечить
	$hw = getdata($login, 'abilities', 'cnowledge')*10-getdata($login, 'abilities', 'intellegence');

	//А деньги есть
	if ($how <= $money2)
	{
		//А лечить-то надо?
		if (getdata($login, 'abilities', 'intellegence') < (10*$us2))
		{

			//Может он хитрит и в битве?
			if (getdata($login, 'battle', 'battle') == 0)
			{
				change ($login, 'economic', 'money', $money2 - $how2);
				change ($login, 'abilities', 'intellegence', getdata($login, 'abilities', 'cnowledge')*10);
			}
		}
	}
}

//Приветствие
echo ("<center><h2> ".getfrom('race', getdata($login, 'hero', 'race'), 'buildings', 'build7')." </h2>(<a href='city.php?login=$login'>В город</a>)</center><br>");
HelpMe(16, 1);
echo ("<center>Добро пожаловать в ".getfrom('race', getdata($login, 'hero', 'race'), 'buildings', 'build7').", ".getdata($login, 'hero', 'name')."<br><br>");
echo ("<center><table border=1 width=90% CELLSPACING=0 CELLPADDING=0>");
echo("<tr><td colspan=3 align=center><b>Ваша наличность: $money <img src='images/menu/gold.gif'></b></td></tr>");
echo ("<tr><td colspan=3 align=center>Услуги лекаря</td></tr>");
echo ("<form action='church.php'  method=post>");
echo ("<input type='hidden' name='login' value=$login>");
echo ("<input type='hidden' name='action' value=1>");
echo ("<tr><td rowspan=2 align=center>Здоровье: ".getdata($login, 'hero', 'health')." из ".(getdata($login, 'hero', 'level')*100)."</td><td align=center>Цена: ".$tp." ".getdata($login, 'economic', 'moneyname')."</td><td align=center><input type='submit' value=' Восстановить 10% ' style='background:#000099; font-WEIGHT: BOLD; COLOR: white; cursor: hand; list-style: lower-alpha; filter: Alpha(Opacity=20, FinishOpacity=100, Style=3)'></td></tr>");
echo ("</form>");
echo ("<form action='church.php'  method=post>");
echo ("<input type='hidden' name='login' value=$login>");
echo ("<input type='hidden' name='action' value=2>");
echo ("<tr><td align=center>Цена ".$how." ".getdata($login, 'economic', 'moneyname')."</td><td align=center><input type='submit' value='Восстановить 100%' style='background:#000099; font-WEIGHT: BOLD; COLOR: white; cursor: hand; list-style: lower-alpha; filter: Alpha(Opacity=20, FinishOpacity=100, Style=3)'></td></tr>");
echo ("</form>");
echo ("</table>");

echo ("<center><table border=1 width=90% CELLSPACING=0 CELLPADDING=0>");
echo ("<tr><td colspan=3 align=center>Услуги монаха</td></tr>");
echo ("<form action='church.php'  method=post>");
echo ("<input type='hidden' name='login' value=$login>");
echo ("<input type='hidden' name='action' value=3>");
echo ("<tr><td rowspan=2 align=center>Мана: ".getdata($login, 'abilities', 'intellegence')." из ".(getdata($login, 'abilities', 'cnowledge')*10)."</td><td align=center>Цена: ".$tp2." ".getdata($login, 'economic', 'moneyname')."</td><td align=center><input type='submit' value=' Восстановить 10% ' style='background:#000099; font-WEIGHT: BOLD; COLOR: white; cursor: hand; list-style: lower-alpha; filter: Alpha(Opacity=20, FinishOpacity=100, Style=3)'></td></tr>");
echo ("</form>");
echo ("<form action='church.php'  method=post>");
echo ("<input type='hidden' name='login' value=$login>");
echo ("<input type='hidden' name='action' value=4>");
echo ("<tr><td align=center>Цена ".$how2." ".getdata($login, 'economic', 'moneyname')."</td><td align=center><input type='submit' value='Восстановить 100%' style='background:#000099; font-WEIGHT: BOLD; COLOR: white; cursor: hand; list-style: lower-alpha; filter: Alpha(Opacity=20, FinishOpacity=100, Style=3)'></td></tr>");
echo ("</form>");
echo ("</table>");
echo ("</center>");

echo ("<center><table border=1 width=90% CELLSPACING=0 CELLPADDING=0>");
echo ("<tr><td colspan=12 align=center>Услуги целителя</td></tr>");

//Сколько надо на бутылку
$need = getdata($login, 'economic', 'curse')*100;

echo ("<tr>");
echo ("<td align=center><img src='images/bottles/big_h.jpg' alt='Большое зелье лечения. Восстанавливает 100% здоровья в бою'><br>Цена: ".($need*100)."</td>");
echo ("<td align=center><img src='images/bottles/med_h.jpg' alt='Среднее зелье лечения. Восстанавливает 50% здоровья в бою'><br>Цена: ".($need*50)."</td>");
echo ("<td align=center><img src='images/bottles/sma_h.jpg' alt='Малое зелье лечения. Восстанавливает 25% здоровья в бою'><br>Цена: ".($need*25)."</td>");
echo ("<td align=center><img src='images/bottles/big_m.jpg' alt='Большое зелье маны. Восстанавливает 100% маны в бою'><br>Цена: ".($need*20)."</td>");
echo ("<td align=center><img src='images/bottles/med_m.jpg' alt='Среднее зелье маны. Восстанавливает 50% маны в бою'><br>Цена: ".($need*10)."</td>");
echo ("<td align=center><img src='images/bottles/sma_m.jpg' alt='Малое зелье маны. Восстанавливает 25% маны в бою'><br>Цена: ".($need*5)."</td>");
echo ("</tr>");

echo ("<tr>");
echo ("<td align=center><br><form action='church.php'><input type='hidden' name='login' value=$login><input type='hidden' name='action' value='5'><input type='submit' value='Купить' style='background:#000099; font-WEIGHT: BOLD; COLOR: white; cursor: hand; list-style: lower-alpha; filter: Alpha(Opacity=20, FinishOpacity=100, Style=3)'></form></td>");
echo ("<td align=center><br><form action='church.php'><input type='hidden' name='login' value=$login><input type='hidden' name='action' value='6'><input type='submit' value='Купить' style='background:#000099; font-WEIGHT: BOLD; COLOR: white; cursor: hand; list-style: lower-alpha; filter: Alpha(Opacity=20, FinishOpacity=100, Style=3)'></form></td>");
echo ("<td align=center><br><form action='church.php'><input type='hidden' name='login' value=$login><input type='hidden' name='action' value='7'><input type='submit' value='Купить' style='background:#000099; font-WEIGHT: BOLD; COLOR: white; cursor: hand; list-style: lower-alpha; filter: Alpha(Opacity=20, FinishOpacity=100, Style=3)'></form></td>");
echo ("<td align=center><br><form action='church.php'><input type='hidden' name='login' value=$login><input type='hidden' name='action' value='8'><input type='submit' value='Купить' style='background:#000099; font-WEIGHT: BOLD; COLOR: white; cursor: hand; list-style: lower-alpha; filter: Alpha(Opacity=20, FinishOpacity=100, Style=3)'></form></td>");
echo ("<td align=center><br><form action='church.php'><input type='hidden' name='login' value=$login><input type='hidden' name='action' value='9'><input type='submit' value='Купить' style='background:#000099; font-WEIGHT: BOLD; COLOR: white; cursor: hand; list-style: lower-alpha; filter: Alpha(Opacity=20, FinishOpacity=100, Style=3)'></form></td>");
echo ("<td align=center><br><form action='church.php'><input type='hidden' name='login' value=$login><input type='hidden' name='action' value='10'><input type='submit' value='Купить' style='background:#000099; font-WEIGHT: BOLD; COLOR: white; cursor: hand; list-style: lower-alpha; filter: Alpha(Opacity=20, FinishOpacity=100, Style=3)'></form></td>");
echo ("</tr>");

echo ("<tr>");
echo ("<td align=center><img src='images/bottles/big_p.jpg' alt='Большое зелье силы. Увеличивает силу персонажа на 100% в бою'><br>Цена: ".($need*100)."</td>");
echo ("<td align=center><img src='images/bottles/med_p.jpg' alt='Среднее зелье силы. Увеличивает силу персонажа на 50% в бою'><br>Цена: ".($need*50)."</td>");
echo ("<td align=center><img src='images/bottles/sma_p.jpg' alt='Малое зелье силы. Увеличивает силу персонажа на 25% в бою'><br>Цена: ".($need*25)."</td>");
echo ("<td align=center><img src='images/bottles/big_i.jpg' alt='Большое зелье колдовской силы. Увеличивает колдовскую силу персонажа на 100% в бою'><br>Цена: ".($need*100)."</td>");
echo ("<td align=center><img src='images/bottles/med_i.jpg' alt='Среднее зелье колдовской силы. Увеличивает колдовскую силу персонажа на 50% в бою'><br>Цена: ".($need*50)."</td>");
echo ("<td align=center><img src='images/bottles/sma_i.jpg' alt='Малое зелье колдовской силы. Увеличивает колдовскую силу персонажа на 25% в бою'><br>Цена: ".($need*25)."</td>");
echo ("</tr>");

echo ("<tr>");
echo ("<td align=center><br><form action='church.php'><input type='hidden' name='login' value=$login><input type='hidden' name='action' value='11'><input type='submit' value='Купить' style='background:#000099; font-WEIGHT: BOLD; COLOR: white; cursor: hand; list-style: lower-alpha; filter: Alpha(Opacity=20, FinishOpacity=100, Style=3)'></form></td>");
echo ("<td align=center><br><form action='church.php'><input type='hidden' name='login' value=$login><input type='hidden' name='action' value='12'><input type='submit' value='Купить' style='background:#000099; font-WEIGHT: BOLD; COLOR: white; cursor: hand; list-style: lower-alpha; filter: Alpha(Opacity=20, FinishOpacity=100, Style=3)'></form></td>");
echo ("<td align=center><br><form action='church.php'><input type='hidden' name='login' value=$login><input type='hidden' name='action' value='13'><input type='submit' value='Купить' style='background:#000099; font-WEIGHT: BOLD; COLOR: white; cursor: hand; list-style: lower-alpha; filter: Alpha(Opacity=20, FinishOpacity=100, Style=3)'></form></td>");
echo ("<td align=center><br><form action='church.php'><input type='hidden' name='login' value=$login><input type='hidden' name='action' value='14'><input type='submit' value='Купить' style='background:#000099; font-WEIGHT: BOLD; COLOR: white; cursor: hand; list-style: lower-alpha; filter: Alpha(Opacity=20, FinishOpacity=100, Style=3)'></form></td>");
echo ("<td align=center><br><form action='church.php'><input type='hidden' name='login' value=$login><input type='hidden' name='action' value='15'><input type='submit' value='Купить' style='background:#000099; font-WEIGHT: BOLD; COLOR: white; cursor: hand; list-style: lower-alpha; filter: Alpha(Opacity=20, FinishOpacity=100, Style=3)'></form></td>");
echo ("<td align=center><br><form action='church.php'><input type='hidden' name='login' value=$login><input type='hidden' name='action' value='16'><input type='submit' value='Купить' style='background:#000099; font-WEIGHT: BOLD; COLOR: white; cursor: hand; list-style: lower-alpha; filter: Alpha(Opacity=20, FinishOpacity=100, Style=3)'></form></td>");
echo ("</tr>");

echo ("</table>");
echo ("</center>");

?>