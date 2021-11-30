<body background="images/back.jpe">
<link rel='stylesheet' type='text/css' href='style.css'/>
<title>Строительство замка</title>
<?

include "functions.php";
ban();

FromBattle($login);

//Расчёт цены
$metal = round(sqrt(($build)*($build)*($build)));
$rock = ($build)*($build);
$wood = ($build)*5;
$cena = $metal*getdata($login, 'economic', 'curse');

//Мои ресурсы
$mymetal = getdata($login, 'economic', 'metal');
$myrock = getdata($login, 'economic', 'rock');
$mywood = getdata($login, 'economic', 'wood');
$mymoney = getdata($login, 'economic', 'money');

//Рынок дешевлее
if ($build == 5)
{
	$metal = 15;
	$rock = 14;
	$wood = 13;
	$cena = $metal*getdata($login, 'economic', 'curse');
}

if (!empty($allok))
{
	if (($metal <= $mymetal)&&($rock <= $myrock)&&($wood <= $mywood)&&($cena <= $mymoney))
	{
		change($login, 'economic', 'money', $mymoney-$cena);
		change($login, 'economic', 'metal', $mymetal-$metal);
		change($login, 'economic', 'rock', $myrock-$rock);
		change($login, 'economic', 'wood', $mywood-$wood);
		$test = "build".$build;
		change($login, 'city', $test, 1);
    change($login, 'temp', 'param', time());
		moveto("game.php?action=8");
	}
}
else
{
//Шапка таблицы
echo("<center>");
echo("<table border=1 width=40% CELLSPACING=0 CELLPADDING=0>");
echo("<tr><td colspan=3 align=center>Цена на здание</td></tr>");
echo("<tr><td align=center width=50%>Ресурс</td><td align=center width=25%>Количество</td><td align=center>У Вас</td></tr>");
echo("<tr><td align=center>Металл</td><td align=center>".$metal."</td><td align=center>".getdata($login, 'economic', 'metal')."</td></tr>");
echo("<tr><td align=center>Камень</td><td align=center>".$rock."</td><td align=center>".getdata($login, 'economic', 'rock')."</td></tr>");
echo("<tr><td align=center>Дерево</td><td align=center>".$wood."</td><td align=center>".getdata($login, 'economic', 'wood')."</td></tr>");
echo("<tr><td align=center>".getdata($login, 'economic', 'moneyname')."</td><td align=center>".$cena."</td><td align=center>".getdata($login, 'economic', 'money')."</td></tr>");
echo("<tr><td colspan=3 align=center><br>");

//Достаточно ли ресурсов?
if (($metal <= $mymetal)&&($rock <= $myrock)&&($wood <= $mywood)&&($cena <= $mymoney))
   {
   ?>
   <form action="build.php"  method=post>
   <input type="hidden" name='allok' value=1>
   <?
   echo ("<input type='hidden' name='login' value='$login'>");
   echo ("<input type='hidden' name='build' value='$build'>");
   ?>
   <input type="submit" value = " Построить " style='background:#000099; font-WEIGHT: BOLD; COLOR: white; cursor: hand; list-style: lower-alpha; filter: Alpha(Opacity=20, FinishOpacity=100, Style=3)'>
   </form>
   <?
   }
   else
      {
	  ?>
      <form action="game.php"  method=post>
	  <input type="hidden" name="action" value="3">
	  <input type="submit" value = " Назад " style='background:#000099; font-WEIGHT: BOLD; COLOR: white; cursor: hand; list-style: lower-alpha; filter: Alpha(Opacity=20, FinishOpacity=100, Style=3)'>
      </form>
      <?
	  }
echo("</td></tr></table></center>");
}
?>

