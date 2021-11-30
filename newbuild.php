<?
  include "functions.php";

  //Если не указано имя пользователя, то выкинуть юзера нафиг
  $lg = trim($HTTP_COOKIE_VARS["nativeland"]);
  $pw = trim($HTTP_COOKIE_VARS["password"]);

  //Залогигнен?
  if (finduser($lg, $pw) != 1)
  	moveto('index.php');

  //Если игрок в битве, то перенаправляем его туда
  FromBattle($lg);
  ban();

  //Запоминаем текущие координаты
  $cx = $x;
  $cy = $y;

  //Проверяем, в этом ли мы месте карты находимся?
  $mx = getdata($lg, 'coords', 'rx');
  $my = getdata($lg, 'coords', 'ry');
  if (($mx == $rx)&&($my == $ry))
  {
  }
  else
    moveto("map.php");

  //Читаем все столицы
  $ath = mysql_query("select * from capital;");
  $castle_count=0;
  if ($ath)
  	while ($rw = mysql_fetch_row($ath))
    	if (($rx == $rw[1])&&($ry == $rw[2]))
		  {
			  //Запоминаем координаты замка
        $cstl[$castle_count] = $rw[0];
        $cstx[$castle_count] = $rw[3];
        $csty[$castle_count] = $rw[4];
        $castle_count++;
  		}

  //Читаем все здания
  $ath = mysql_query("select * from mapbuild;");
  $object_count=0;
  if ($ath)	//Всех абсолютно
	  while ($rw = mysql_fetch_row($ath))	//Совпадает регион?
  		if (($rx == $rw[3])&&($ry == $rw[4]))
	  	{
		  	//Запоминаем координаты замка
        $objl[$object_count] = $rw[1]; //Чья постройка
        $objt[$object_count] = $rw[2]; //Тип постройки
        $obji[$object_count] = $rw[7]; //Информация о постройке
        $objx[$object_count] = $rw[5]; //Координата постройки X
        $objy[$object_count] = $rw[6]; //Координата постройки Y
        $object_count++;
  		} //if

  //Читаем карту...
	$file = fopen("maps/".$rx."x".$ry.".map", "r");
  for ($x = 1; $x < 11; $x++)
  	for ($y = 1; $y < 11; $y++)
    {
  		//Запоминаем что тут
      $map[$x][$y] = fgets($file, 255);
      $fld = trim($map[$x][$y]);

      //Если тут столица
      for ($i = 0; $i < $castle_count; $i++)
        if (($x == $cstx[$i])&&($y == $csty[$i]))
          $map[$x][$y] = $fld[0].$fld[1].$fld[2].$fld[3].$cstl[$i];

      //А нет ли тут у нас другого объекта
      for ($i = 0; $i < $object_count; $i++) //Совпадают координаты?
        if (($objx[$i] == $x)&&($objy[$i] == $y))
        {
          $map[$x][$y] = $fld[0].$fld[1].$fld[2].$fld[3].$objl[$i];
          $CastleName = $obji[$i];
        }
    }
	fclose($file);

  //Проверяем, что находится в этой клетке
  (int)$x = (int)$cx;
  (int)$y = (int)$cy;
  $fld = $map[$x][$y];

    //Тип территории
    $type = $fld[0];
    //Тип объекта
    $object = $fld[2];
    //Чей он
    $hoster = trim(substr($fld, 4));

  //Шапка
  echo("<title>Строительство</title>");
  echo ("<link rel='stylesheet' type='text/css' href='style.css'/><body background='images\back.jpe'>");

  //Информация о моих ресурсах
  $login = $lg;
  echo ("<center><h2>Что вы намерены делать?</h2><TABLE BORDER=0 CELLSPACING=0 CELLPADDING=0><tr>");
	echo ("<td valign=top><img src=images/menu/gold.gif alt='".getdata($login, 'economic', 'moneyname')."'></td><td valign=center>".getdata($login, 'economic', 'money')."</td><td valign=top><img src=images/menu/metal.gif alt='Металл'></td><td valign=center>".getdata($login, 'economic', 'metal')."</td><td valign=top><img src=images/menu/rock.gif alt='Камень'></td><td valign=center>".getdata($login, 'economic', 'rock')."</td><td valign=top><img src=images/menu/wood.gif alt='Дерево'></td><td valign=center>".getdata($login, 'economic', 'wood')."</td>");
	echo ("</tr>");
  echo("<tr><td colspan=8 align=center>$data</td></tr>");
  echo("</TABLE></center>");

  //Если клетка никем не занята, то здесь можно что-нибудь строить
  if ($hoster == '0')
  {
    //Если это вода, то ничего сделать нельзя
    if ($type == 4)
      messagebox("Вы не можете ничего построить на воде", "map.php");

    //Определяем тип объекта (чтобы узнать, что тут строить)
    switch($object)
    {
      case 0:
        $name = "город";
        break;
      case 1:
        $name = "шахту";
        break;
      case 2:
        $name = "каменеломню";
        break;
      case 3:
        $name = "лесопилку";
        break;
    }
    ?>
      <center>
      <form action='doit.php' method=post>
      <table border=1 cellspacing=0 cellpadding=0 width=50%>
      <?
        //Расчитываем цену на здание
        $curse     = getdata($lg, 'economic', 'curse');
        $moneyname = getdata($lg, 'economic', 'moneyname'); 
        switch($object)
        {
          case 0:
            $money = 10000*$curse;
            $metal = 1000;
            $rock  = 2000;
            $wood  = 2000;
            break;
          case 1:
            $money = 7000*$curse;
            $metal = 4000;
            $rock  = 4000;
            $wood  = 500;
            break;
          case 2:
            $money = 7000*$curse;
            $metal = 6000;
            $rock  = 2500;
            $wood  = 1500;
            break;
          case 3:
            $money = 5000*$curse;
            $metal = 2000;
            $rock  = 500;
            $wood  = 3500;
            break;
        }

        //Выводим информацию
        echo("<tr><td colspan=2 align=center>Построить ".$name."</td></tr>");
        echo("<tr><td width=20% align=center>Ресурс</td><td align=center>Количество</td></tr>");
        echo("<tr><td width=20%>".$moneyname."</td><td align=center>".$money."</td></tr>");
        echo("<tr><td width=20%>Металл</td><td align=center>".$metal."</td></tr>");
        echo("<tr><td width=20%>Камень</td><td align=center>".$rock."</td></tr>");
        echo("<tr><td width=20%>Дерево</td><td align=center>".$wood."</td></tr>");

        //Невидимые кнопки формы
        echo("<input type='hidden' name='rx' value='$rx'>");
        echo("<input type='hidden' name='ry' value='$ry'>");
        echo("<input type='hidden' name='cx' value='$x'>");
        echo("<input type='hidden' name='cy' value='$y'>");
        echo("<input type='hidden' name='tp' value='$type'>");
        echo("<input type='hidden' name='bj' value='$object'>");
      ?>
       <tr>
        <td colspan=2 align=center>
        <br>
        <input type='text' name='bname' value='Город' style='background:#000099; font-WEIGHT: BOLD; COLOR: white; cursor: hand; list-style: lower-alpha; filter: Alpha(Opacity=20, FinishOpacity=100, Style=3)'>
        <br>
        <input type='submit' value='Построить' style='background:#000099; font-WEIGHT: BOLD; COLOR: white; cursor: hand; list-style: lower-alpha; filter: Alpha(Opacity=20, FinishOpacity=100, Style=3)'>
        </form>
        </td>
       </tr>
       <tr>
        <td colspan=2 align=center>
          <br>
          <form action='map.php' method=post>
          <input type='submit' value='Вернуться' style='background:#000099; font-WEIGHT: BOLD; COLOR: white; cursor: hand; list-style: lower-alpha; filter: Alpha(Opacity=20, FinishOpacity=100, Style=3)'>
          </form>
        <?
            HelpMe(5, 0);
        ?>
        </td>
       </tr>
       </table>
      </center>
    <?
    exit();
  }
  else
  {
    $nick = getdata($hoster, 'hero', 'name');
    ?>
      <center>
      <table border=1 cellpadding=0 cellspacing=0 width=70%>
      <tr>
        <td align=center>
        <br>
        <form action='startbattle.php' method='post'>
        <?
          echo("<h2>".$CastleName."</h2>");
          echo("<b>Вы уверены, что хотите осадировать замок ".$nick."?</b>"); 
          echo("<input type='hidden' name='login' value='".$lg."'>");     //Логин нападающего            
          echo("<input type='hidden' name='oppon' value='".$hoster."'>"); //Логин обороняющегося              
          echo("<input type='hidden' name='type'  value='1'>");           //Тип поединка - осада   
          echo("<input type='hidden' name='terr'  value='".$terr."'>");   //Территория для фона
        ?>
        <br>
        <br>
        <input type='submit' value='Начать осаду' style='background:#000099; font-WEIGHT: BOLD; COLOR: white; cursor: hand; list-style: lower-alpha; filter: Alpha(Opacity=20, FinishOpacity=100, Style=3)'>
        </form>
        <?
          HelpMe(6, 0);
        ?>
        </td>
      </tr>
      </table>
        <br>
        <form action='map.php' method='post'>
        <input type='submit' value='Вернуться' style='background:#000099; font-WEIGHT: BOLD; COLOR: white; cursor: hand; list-style: lower-alpha; filter: Alpha(Opacity=20, FinishOpacity=100, Style=3)'>
        </form>
      </center>
    <?
  }
?>