<style>
img.x
{
	position:absolute;
	left:0;
	top:0;
	z-index:-1;
}
img.z
{
	position:absolute;
	left:-10;
	top:-10;
	z-index:-100;
}
</style>
<script>
var sw;
var sh;
sw = screen.width-20;
sh  = screen.height-140;
sw = screen.width;
sh  = screen.height-80;
document.write("<img class=x src=images/map.jpg width=" + sw + " height=" + sh + ">");
</script>
<?
//Модуль функций
include "functions.php";

//Если не указано имя пользователя, то выкинуть юзера нафиг
$lg = trim($HTTP_COOKIE_VARS["nativeland"]);
$pw = trim($HTTP_COOKIE_VARS["password"]);
ban();

//Залогигнен?
if (finduser($lg, $pw) != 1)
	moveto('index.php');

//Если игрок в битве, то перенаправляем его туда
FromBattle($lg);

//Если игрок в битве армиями, то перенаправляем его туда
(int)$Battle = getdata($lg, 'battles', 'battle');
if ($Battle != 0)
  moveto("fight.php");

//Получение картинки из базы
function GetPicture($Lgn)
{
  $Picture = getfrom('name', $Lgn, 'monsters', 'art');

  //Возврат картинки
  return $Picture;
}

//Вывод миникарты (таблица 20x20 с отмеченными городами, столицами и персонажем)
function minimap($Login)
{
  //Зануляем карту
  for ($i = 1; $i <= 20; $i++)
    for ($j = 1; $j <= 20; $j++)
      $MInfo[$i][$j] = 0;

  //Помечаем все наши города
  $ath = mysql_query("select * from mapbuild where login='$Login';");
  if ($ath)	//Всех абсолютно
	  while ($rw = mysql_fetch_row($ath))	//Совпадает регион?
		  $MInfo[$rw[4]][$rw[3]] = 3;

  //Получаем наши координаты и координаты столицы
  $Rx = getdata($Login, 'coords', 'rx');
  $Ry = getdata($Login, 'coords', 'ry');

  //Помещаем себя
  $MInfo[$Ry][$Rx] = 1;

  //Координаты столицы
  $Rx = getdata($Login, 'capital', 'rx');
  $Ry = getdata($Login, 'capital', 'ry');

  //Помещаем столицу
  $MInfo[$Ry][$Rx] = 2;

  //Выводим карту
  ?>
  <style>
    .xl24 {mso-style-parent:style0; border:.5pt solid windowtext;}
  </style>
  <table border=0 cellpadding=0 cellspacing=0 style='border-collapse: collapse; width:100pt'>
  <?
    for ($i = 1; $i <= 20; $i++)
    {
      echo ("<tr height=5 style='mso-height-source:userset; height:5.1pt'>");
      for ($j = 1; $j <= 20; $j++)
      {
        //Подсказка
        //$Help = getregion($i, $j, 0);

        //Выбираем тип города
        switch($MInfo[$i][$j])
        {
        case 0:
          echo("<td class=xl24 width=5 style='border-left:none;width:5pt' bgcolor=#339900><span style='display:none'>.</span></td>");
          break;
        case 1:
          echo("<td title='$Help' class=xl24 width=5 style='border-left:none;width:5pt' bgcolor=#3366FF><span style='display:none'>.</span></td>");
          break;
        case 2:
          echo("<td title='$Help' class=xl24 width=5 style='border-left:none;width:5pt' bgcolor=#FF0000><span style='display:none'>.</span></td>");
          break;
        case 3:
          echo("<td title='$Help' class=xl24 width=5 style='border-left:none;width:5pt' bgcolor=#FFFF00><span style='display:none'>.</span></td>");
          break;
        } //Switch
      } //For
      echo ("</tr>");
    }
  ?>
  </table>
  <?
}

//Выводим кнопку
function Button($Opp, $Go)
{
  $hn = getdata($Opp, 'hero', 'name');
  if (empty($hn))
    $hn = $Opp;
  if ($hn == '0')
    $hn = 'Некто';
  echo("<br>");
  echo("<form action='map.php' method='post'>");
  echo("<input type='hidden' name='go' value='".$Go."'>");
  echo("<input type='hidden' name='attack' value='1'>");
  echo("<input type='submit' value='".$hn."' style='background:#000099; font-WEIGHT: BOLD; COLOR: white; cursor: hand; list-style: lower-alpha; filter: Alpha(Opacity=20, FinishOpacity=100, Style=3)'>");
  echo("</form>");
}

//Обновление
echo ("<META HTTP-EQUIV='REFRESH' CONTENT=15>");

//Для админа только:
$rx = getdata ($lg, 'coords', 'rx');
$ry = getdata ($lg, 'coords', 'ry');
$hx = getdata ($lg, 'coords', 'x');
$hy = getdata ($lg, 'coords', 'y');

//Ошибки
if (($ry < 1)||(empty($ry))) {$ry = 1;}
if (($rx < 1)||(empty($rx))) {$rx = 1;}
if (($ry > 20)||(empty($ry))) {$ry = 20;}
if (($rx > 20)||(empty($rx))) {$rx = 20;}

//Соседи
$ury = $ry-1;
$urx = $rx-1;
$dry = $ry+1;
$drx = $rx+1;

//Ошибки
if ($ury < 1) {$ury = 1;}
if ($urx < 1) {$urx = 1;}
if ($ury > 20) {$ury = 20;}
if ($ury > 20) {$ury = 20;}

//Шапка таблицы
echo ("<title>Карта материка</title>");
if ($build != 1) 
  echo ("<link rel='stylesheet' type='text/css' href='style.css'/>");

//Таблица для выравнивания
$name = getregion($rx, $ry, 0);
if ($build != 1) 
  echo ("<center><h2><font color=white>$name (".getdata($lg, 'hero', 'name').")</font></h2></center>");

echo ("<center><table border=0 width=80%><tr><td>");
echo ("<center><table border=0 width=30%>");
echo ("<tr>");
echo ("<td width=60%>");
echo("<center><TABLE ALIGN=CENTER WIDTH='100%' BORDER=1 CELLSPACING=0 CELLPADDING=0>");

//Читаем всех пользователей
for ($i = 1; $i < 11; $i++)
	for ($j = 1; $j < 11; $j++)
		$hmap[$i][$j] = "0*0=0";

//Читаем всех героев в массив
$ath = mysql_query("select * from coords;");
if ($ath) //Всех абсолютно
	while ($rw = mysql_fetch_row($ath))	//Совпадает регион?
		if (($rx == $rw[1])&&($ry == $rw[2]))	//Ставим уродца
			$hmap[$rw[3]][$rw[4]] = "1*1=".$rw[0];

//Я сам
$hmap[$hx][$hy] = "1*1=".$lg;

//Монстры
$ath = mysql_query("select * from random where rx='".$rx."' and ry='".$ry."';");
if ($ath) //Всех абсолютно
	while ($rw = mysql_fetch_row($ath))	//Совпадает регион?
			$hmap[$rw[3]][$rw[4]] = "1*1=".$rw[0];

//Читаем карту...
$file = fopen("maps/".$rx."x".$ry.".map", "r");
for ($x = 1; $x < 11; $x++)
	for ($y = 1; $y < 11; $y++)
		$map[$x][$y] = fgets($file, 255);
fclose($file);

//Читаем все столицы
$ath = mysql_query("select * from capital;");
$castle_count=0;
if ($ath)	//Всех абсолютно
	while ($rw = mysql_fetch_row($ath))	//Совпадает регион?
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
      $obji[$object_count] = $rw[7]; //Информация о постройке (название города)
      $objx[$object_count] = $rw[5]; //Координата постройки X
      $objy[$object_count] = $rw[6]; //Координата постройки Y
      $object_count++;
		} //if

$hp = getdata($lg, 'inf', 'def');
if (($hp < 1)&&($go != 0))
{
  $go = 0;
  moveto('map.php?message=<b><font color=white>У Вас мало ОД</font></b>');
}

//Запоминаем старые координаты
$orx = $rx;
$ory = $ry;
$ox = $hx;
$oy = $hy;
$no = 0;

//Входим в здание
if ($enter == 1)
  moveto("newbuild.php?login=".$lg."&rx=".$rx."&ry=".$ry."&x=".$ax."&y=".$ay);

//Может быть кого-нибудь атакуем?
if ($attack == 1)
{
	//Получаем имя оппонента
	$dx = 0;
	$dy = 0;
	if ($go == 1) {$dx=-1;}
	if ($go == 2) {$dy=-1;}
	if ($go == 3) {$dy=1;}
	if ($go == 4) {$dx=1;}
	$beet = trim(substr($hmap[$hx+$dx][$hy+$dy], 4));

  //Если это монстр, то генерируем параметры для поединка с монстром
  $hname = getdata($beet, 'hero', 'name');

  //Если это монстр
  if (empty($hname))
  {
    MonsterBattle($lg, $beet, $hx+$dx, $hy+$dy, $rx, $ry);
    moveto("battle.php");
  } //атака монстра
  else
  {
    //Если это осада замка
	  if (trim(substr($map[$hx+$dx][$hy+$dy], 4)) != '0')	//А построены ли казармы?
		  if (getdata($lg, 'city', 'build13') != 0)
			  {
				  $bt = trim(substr($map[$hx+$dx][$hy+$dy], 4));
  				//moveto("game.php?action=34&users=".$bt);
           moveto("map.php?message=временно недоступно");
		  	} else //нет казармы
           moveto("map.php?message=У Вас не построена(ы) ".getfrom('race', getdata($lg, 'hero', 'race'), 'buildings', 'build13').". Сначала необходимо построить здание!");

    //Проверяем, а не в битве ли игрок?
    $OpBattle = getdata($beet, 'battle', 'battle');
    if (($Battle == 0)&&($OpBattle == 0))
    {
      if ($lg != $beet)
      {
        BattleOn($lg, $beet);
        moveto("battle.php");
      } else //Если нападаем на кого-то
        moveto("map.php?message=Вы же не можете вызвать самого себя на поединок");
    } else // Оба игрока не в битве
      moveto("map.php?message=Этот игрок сейчас занят в битве. Подождите её завершения...");
  } //Атака не монстра
} //атака кого-то

//Влево
if ($go == 2)
{
  $hy = $hy - 1;

	//Переход
	if ($hy == 0)
	{
  	//Край региона
		$hy = 10;
		$rx = $rx - 1;

    //Предел карты
		if ($rx == 0)
		{
			$rx = 1;
			$hy = 1;
		}
  }
}

//Вправо
if ($go == 3)
{
	$hy = $hy + 1;

  //Переход
	if ($hy == 11)
	{
  	//Край региона
	  $hy = 1;
  	$rx = $rx + 1;

    //Предел карты
		if ($rx == 21)
		{
			$rx = 20;
			$hy = 10;
		}
	}
}

//Вверх
if ($go == 1)
{
	$hx = $hx - 1;

  //Переход
	if ($hx == 0)
	{
    //Край региона
		$hx = 10;
		$ry = $ry - 1;

		//Предел карты
		if ($ry == 0)
		{
			$ry = 1;
			$hx = 1;
		}
	}
}

//Вниз
if ($go == 4)
{
	$hx = $hx + 1;

  //Переход
	if ($hx == 11)
	{
		//Край региона
		$hx = 1;
		$ry = $ry + 1;

    //Предел карты
		if ($ry == 21)
		{
			$ry = 20;
			$hx = 10;
		}
	}
}

//Если можно
if ($no == 0)
{
	//Обновляем позиции
	$hmap[$ox][$oy] = "0*0=0\n";

  //Проверяем на смену карты
	if (($orx != $rx)||($ory != $ry))
	{
		//Читаем карту...
		$file = fopen("maps/".$rx."x".$ry.".map", "r");
		for ($x = 1; $x < 11; $x++)
			for ($y = 1; $y < 11; $y++)
				$map[$x][$y] = fgets($file, 255);
		fclose($file);
	}

  //Мега проверка
	if (($map[$hx][$hy][2] != '0')||($hmap[$hx][$hy][0] != '0'))
	{
		$hx = $ox;
		$hy = $oy;
		$rx = $orx;
		$ry = $ory;
    $go = 0;
	}

	//Ставим героя в новую клетку
	$hmap[$hx][$hy] = "1*1=".$lg."\n";

  //Изменяем данные в базе
	change ($lg, 'coords', 'rx', $rx);
	change ($lg, 'coords', 'ry', $ry);
	change ($lg, 'coords', 'x', $hx);
	change ($lg, 'coords', 'y', $hy);

 	//Если герой куда-то пошёл, то проверяем ОД
  if ($go != 0)
 		change($lg, 'inf', 'def', $hp-1);

	//Проверка
	if ($go != 0)
	{
    //Проверка статуса игрока. Где он находится: в городе или нет
    change($lg, 'city', 'build20', 0);

    //Получаем данные о столице
    $cx  = getdata($lg, 'capital', 'rx');
    $cy  = getdata($lg, 'capital', 'ry');
    $cpx = getdata($lg, 'capital', 'x');
    $cpy = getdata($lg, 'capital', 'y');

    //Если мы в столице, ставим 1
    if (($rx == $cx)&&($ry == $cy)&&($hx == $cpx)&&($hy == $cpy))
      change($lg, 'city', 'build20', 1);

    //Если мы в другом городе, ставим 2
    $ath = mysql_query("select * from mapbuild where login = '$lg';");
    if ($ath)	//Всех абсолютно
    	while ($rw = mysql_fetch_row($ath))	//Совпадает регион?
      {
        echo("<b><font color=white>".$rx." = ".$rw[3]."; ".$ry." = ".$rw[4]."; ".$x." = ".$rw[5]."; ".$y." = ".$rw[6]."</font></b><br>");
        if (($rx == $rw[3])&&($ry == $rw[4])&&($hx == $rw[5])&&($hy == $rw[6]))
          change($lg, 'city', 'build20', 2);
      }

    //Обновление экрана
		$go = 0;
		?>
		<script>
		window.location.href('map.php');
		</script>
		<?
	}
}

//Читаем карту объектов
$file = fopen("maps/".$rx."x".$ry.".map", "r");

//Карта
for ($x = 1; $x < 11; $x++)
{
	echo("<tr>");
	//Новая ячейка таблицы

	for ($y = 1; $y < 11; $y++)
	{
  	//Получаем данные из ячейки
	  $fld = fgets($file, 255);
		$hld = $hmap[$x][$y];

    //Отображаем ячейку
    echo ("<td align=center>");
    echo ("<a href=newbuild.php?login=".$lg."&enter=1&rx=".$rx."&ry=".$ry."&x=".$x."&y=".$y."&terr=".$fld[0].">");	

    //А нет ли тут у нас замка?
    for ($i = 0; $i < $castle_count; $i++) //Совпадают координаты?
      if (($cstx[$i] == $x)&&($csty[$i] == $y))
        $fld = $fld[0].$fld[1].$fld[2].$fld[3].$cstl[$i];

    //А нет ли тут у нас другого объекта
    for ($i = 0; $i < $object_count; $i++) //Совпадают координаты?
      if (($objx[$i] == $x)&&($objy[$i] == $y))
        $fld = $fld[0].$fld[1].$fld[2].$fld[3].$objl[$i];

		/* -=== Фон ===- */

    //Трава
		if (($fld[0] == '0')&&($fld[2] == 0)&&($fld[4] == '0'))
			if ($hld[0] == 0)
				echo ("<img src='images/terrain/grass.gif' width=32 height=32 BORDER=0 alt=Трава>");
			else
			{
				$tp = "m";
				if ($hld[2] == '1') 
          $tp = "h";
        $mnm = trim(substr($hld, 4));
        $hnm = getdata($mnm, 'hero', 'name');
        $direct = 'heroes';
        if (empty($hnm))
        {
          $hnm = $mnm;
          $tp = getpicture($mnm);
          $direct = 'monsters/grass'.$tp;
  				echo ("<img src='images/".$direct."' width=32 height=32 BORDER=0 alt='".$hnm."'>");
        }
        else
  				echo ("<img src='images/".$direct."/".$tp."grass.bmp' width=32 height=32 BORDER=0 alt='".$hnm."'>");
			}

    //Снег
		if (($fld[0] == '1')&&($fld[2] == 0)&&($fld[4] == '0'))
			if ($hld[0] == 0)
				echo ("<img src='images/terrain/snow.gif' width=32 height=32 BORDER=0 alt=Снег>");
			else
			{
				$tp = "m";
				if ($hld[2] == '1') 
          $tp = "h";
        $mnm = trim(substr($hld, 4));
        $hnm = getdata($mnm, 'hero', 'name');
        $direct = 'heroes';
        if (empty($hnm))
        {
          $tp = getpicture($mnm);
          $hnm = $mnm;
          $direct = 'monsters/snow'.$tp;
  				echo ("<img src='images/".$direct."' width=32 height=32 BORDER=0 alt='".$hnm."'>");
        }
        else
  				echo ("<img src='images/".$direct."/".$tp."snow.bmp' width=32 height=32 BORDER=0 alt='".$hnm."'>");
			}

    //Песок
		if (($fld[0] == '2')&&($fld[2] == 0)&&($fld[4] == '0'))
			if ($hld[0] == '0')
				echo ("<img src='images/terrain/sand.bmp' width=32 height=32 BORDER=0 alt=Песок>");
			else
			{
				$tp = "m";
				if ($hld[2] == '1') 
          $tp = "h";
        $mnm = trim(substr($hld, 4));
        $hnm = getdata($mnm, 'hero', 'name');
        $direct = 'heroes';
        if (empty($hnm))
        {
          $hnm = $mnm;
          $tp = getpicture($mnm);
          $direct = 'monsters/sand'.$tp;
  				echo ("<img src='images/".$direct."' width=32 height=32 BORDER=0 alt='".$hnm."'>");
        }
        else
  				echo ("<img src='images/".$direct."/".$tp."sand.bmp' width=32 height=32 BORDER=0 alt='".$hnm."'>");
			}

    //Лава
		if (($fld[0] == '3')&&($fld[2] == 0)&&($fld[4] == '0'))
			if ($hld[0] == '0')
				echo ("<img src='images/terrain/Fire.jpg' width=32 height=32 BORDER=0 alt=Лава>");
			else
			{
				$tp = "m";
				if ($hld[2] == '1') 
          $tp = "h";
        $mnm = trim(substr($hld, 4));
        $hnm = getdata($mnm, 'hero', 'name');
        $direct = 'heroes';     
        if (empty($hnm))
        {
          $hnm = $mnm;
          $tp = getpicture($mnm);
          $direct = 'monsters/fire'.$tp;
  				echo ("<img src='images/".$direct."' width=32 height=32 BORDER=0 alt='".$hnm."'>");
        }
        else
  				echo ("<img src='images/".$direct."/".$tp."fire.bmp' width=32 height=32 BORDER=0 alt='".$hnm."'>");
			}

    //Вода
    if (($fld[0] == '4')&&($fld[2] == 0)&&($fld[4] == '0'))
			if ($hld[0] == '0')
  			echo ("<img src='images/terrain/water.gif' width=32 height=32 BORDER=0 alt=Вода>");
			else
			{
				$tp = "m";
				if ($hld[2] == '1') 
          $tp = "h";
        $mnm = trim(substr($hld, 4));
        $hnm = getdata($mnm, 'hero', 'name');
        if (empty($hnm))
        {
          $hnm = $mnm;
          $tp = getpicture($mnm);
          $direct = 'monsters/water'.$tp;
  				echo ("<img src='images/".$direct."' width=32 height=32 BORDER=0 alt='".$hnm."'>");
        }
        else
  				echo ("<img src='images/heroes/".$tp."water.bmp' width=32 height=32 BORDER=0 alt='".$hnm."'>");
			}

    /* -=== Ресурс ===- */

    //Металл на траве
		if (($fld[2] == '1')&&($fld[4] == '0')&&($fld[0] == '0'))
			echo ("<img src='images/terrain/metal.bmp' width=32 height=32 BORDER=0 alt='Залежи металла'>");
    //Металл на снегу
		if (($fld[2] == '1')&&($fld[4] == '0')&&($fld[0] == '1'))
			echo ("<img src='images/terrain/snmetal.bmp' width=32 height=32 BORDER=0 alt='Залежи металла'>");
    //Металл на песке
		if (($fld[2] == '1')&&($fld[4] == '0')&&($fld[0] == '2'))
			echo ("<img src='images/terrain/smetal.bmp' width=32 height=32 BORDER=0 alt='Залежи металла'>");
    //Металл на лаве
		if (($fld[2] == '1')&&($fld[4] == '0')&&($fld[0] == '3'))
			echo ("<img src='images/terrain/lmetal.bmp' width=32 height=32 BORDER=0 alt='Залежи металла'>");
    //Камень на траве
    if (($fld[2] == '2')&&($fld[4] == '0')&&($fld[0] == '0'))
			echo ("<img src='images/terrain/rock.bmp' width=32 height=32 BORDER=0 alt='Залежи камня'>");
    //Камень на снегу
    if (($fld[2] == '2')&&($fld[4] == '0')&&($fld[0] == '1'))
			echo ("<img src='images/terrain/snrock.bmp' width=32 height=32 BORDER=0 alt='Залежи камня'>");
    //Камень на песке
    if (($fld[2] == '2')&&($fld[4] == '0')&&($fld[0] == '2'))
			echo ("<img src='images/terrain/srock.bmp' width=32 height=32 BORDER=0 alt='Залежи камня'>");
    //Камень на лаве
		if (($fld[2] == '2')&&($fld[4] == '0')&&($fld[0] == '3'))
			echo ("<img src='images/terrain/lrock.bmp' width=32 height=32 BORDER=0 alt='Залежи камня'>");
    //Дерево на траве
    if (($fld[2] == '3')&&($fld[4] == '0')&&($fld[0] == '0'))
			echo ("<img src='images/terrain/tree".rand(1,3).".gif' width=32 height=32 BORDER=0 alt=Деревья>");
    //Дерево на снегу
		if (($fld[2] == '3')&&($fld[4] == '0')&&($fld[0] == '1'))
			echo ("<img src='images/terrain/sntree".rand(1,3).".bmp' width=32 height=32 BORDER=0 alt=Деревья>");
    //Дерево на песке
		if (($fld[2] == '3')&&($fld[4] == '0')&&($fld[0] == '2'))
			echo ("<img src='images/terrain/stree".rand(1,2).".bmp' width=32 height=32 BORDER=0 alt=Деревья>");
    //Дерево на лаве
		if (($fld[2] == '3')&&($fld[4] == '0')&&($fld[0] == '3'))
			echo ("<img src='images/terrain/ltree.bmp' width=32 height=32 BORDER=0 alt=Деревья>");
    

		/* -=== Объекты, не зависящие от территории ===- */

    //Стена
		if (($fld[2] == '4'))
			echo ("<img src='images/objects/wall.gif' width=32 height=32 BORDER=0 alt=Стена>");

   	/* -=== Замки на разной территории ===- */
 	  if (($fld[4] != '0')&&($fld[2] == '0'))
	 	{
      //Определяем владельца
			$nm = getdata(trim(substr($fld, 4)), 'hero', 'name');
			if ($nm != '')
			{
        //Замок на траве
				if ($fld[0] == 0)
					echo ("<img src='images/terrain/castle.gif' width=32 height=32 BORDER=0 alt='Замок. Территорией обладает ".$nm."'>");
        //Замок на снегу
				if ($fld[0] == 1)
					echo ("<img src='images/terrain/castlesnow.gif' width=32 height=32 BORDER=0 alt='Замок. Территорией обладает ".$nm."'>");
        //Замок на песке
				if ($fld[0] == 2)
					echo ("<img src='images/terrain/castlesand.gif' width=32 height=32 BORDER=0 alt='Замок. Территорией обладает ".$nm."'>");
				//Замок на лаве
				if ($fld[0] == 3)
					echo ("<img src='images/terrain/castlefire.gif' width=32 height=32 BORDER=0 alt='Замок. Территорией обладает ".$nm."'>");
			}
			else
				echo ("<img src='images/terrain/castle.gif' width=32 height=32 BORDER=0 alt='Заброшенный замок'>");
		}

    /* -=== Шахты на разной территории ===- */

    //Шахта на траве
 	  if (($fld[4] != '0')&&($fld[0] == '0')&&($fld[2] == '1'))
			echo ("<img src='images/buildings/gold_grass.bmp' width=32 height=32 BORDER=0 alt='Шахта. Территорией обладает ".getdata(trim(substr($fld, 4)), 'hero', 'name')."'>");
    //Шахта на снегу
	  if (($fld[4] != '0')&&($fld[0] == '1')&&($fld[2] == '1'))
			echo ("<img src='images/buildings/gold_snow.bmp' width=32 height=32 BORDER=0 alt='Шахта. Территорией обладает ".getdata(trim(substr($fld, 4)), 'hero', 'name')."'>");
    //Шахта на песке
		if (($fld[4] != '0')&&($fld[0] == '2')&&($fld[2] == '1'))

			echo ("<img src='images/buildings/gold_sand.bmp' width=32 height=32 BORDER=0 alt='Шахта. Территорией обладает ".getdata(trim(substr($fld, 4)), 'hero', 'name')."'>");
		//Шахта на лаве
		if (($fld[4] != '0')&&($fld[0] == '3')&&($fld[2] == '1'))
			echo ("<img src='images/buildings/gold_fire.bmp' width=32 height=32 BORDER=0 alt='Шахта. Территорией обладает ".getdata(trim(substr($fld, 4)), 'hero', 'name')."'>");

    /* -=== Карьеры на разной территории ===- */

		//Карьер на траве
		if (($fld[4] != '0')&&($fld[0] == '0')&&($fld[2] == '2'))
			echo ("<img src='images/buildings/kam_grass.bmp' width=32 height=32 BORDER=0 alt='Карьер. Территорией обладает ".getdata(trim(substr($fld, 4)), 'hero', 'name')."'>");
		//Карьер на снегу
    if (($fld[4] != '0')&&($fld[0] == '1')&&($fld[2] == '2'))
			echo ("<img src='images/buildings/kam_snow.bmp' width=32 height=32 BORDER=0 alt='Карьер. Территорией обладает ".getdata(trim(substr($fld, 4)), 'hero', 'name')."'>");
		//Карьер на песке
    if (($fld[4] != '0')&&($fld[0] == '2')&&($fld[2] == '2'))
			echo ("<img src='images/buildings/kam_sand.bmp' width=32 height=32 BORDER=0 alt='Карьер. Территорией обладает ".getdata(trim(substr($fld, 4)), 'hero', 'name')."'>");
		//Карьер на лаве
		if (($fld[4] != '0')&&($fld[0] == '3')&&($fld[2] == '2'))
			echo ("<img src='images/buildings/kam_fire.bmp' width=32 height=32 BORDER=0 alt='Карьер. Территорией обладает ".getdata(trim(substr($fld, 4)), 'hero', 'name')."'>");

    /* -=== Карьеры на разной территории ===- */

		//Лесопилка на траве
    if (($fld[4] != '0')&&($fld[0] == '0')&&($fld[2] == '3'))
			echo ("<img src='images/buildings/les_grass.bmp' width=32 height=32 BORDER=0 alt='Лесопилка. Территорией обладает ".getdata(trim(substr($fld, 4)), 'hero', 'name')."'>");

		//Лесопилка на снегу
    if (($fld[4] != '0')&&($fld[0] == '1')&&($fld[2] == '3'))
			echo ("<img src='images/buildings/les_snow.bmp' width=32 height=32 BORDER=0 alt='Лесопилка. Территорией обладает ".getdata(trim(substr($fld, 4)), 'hero', 'name')."'>");
    //Лесопилка на песке
		if (($fld[4] != '0')&&($fld[0] == '2')&&($fld[2] == '3'))
			echo ("<img src='images/buildings/les_sand.bmp' width=32 height=32 BORDER=0 alt='Лесопилка. Территорией обладает ".getdata(trim(substr($fld, 4)), 'hero', 'name')."'>");
		//Лесопилка на лаве
		if (($fld[4] != '0')&&($fld[0] == '3')&&($fld[2] == '3'))
			echo ("<img src='images/buildings/les_fire.bmp' width=32 height=32 BORDER=0 alt='Лесопилка. Территорией обладает ".getdata(trim(substr($fld, 4)), 'hero', 'name')."'>");
		echo ("</a></td>");
	}
	echo ("</tr>");
}
fclose($file);

//Конец таблицы
echo ("</table></center></td>");
echo ("</table></td>");
echo ("<td valign=top><center><font color=yellow><h2>Действия</h2></font>");
echo ("<a href=game.php?action=8><b><font color=white>Режим города</font></b></a><br><br>");
echo ("<font color=white>У Вас: ".getdata($lg, 'inf', 'def')." очков действия</font><br><br>");

//Меню действий
	//Кнопки
	echo ("<table border=0><tr><td colspan=3 align=center>");
		echo ("<a href='map.php?go=1'><img src='images/arrows/up.gif' alt='Идти на север' width=40 height=40 border=0></a></td></tr>");
			echo ("<tr><td align=center><a href='map.php?go=2'><img src='images/arrows/left.gif' alt='Идти на запад' width=40 height=40 border=0></a></td>");
			echo ("<td align=center><img src='images/terrain/empty.gif' width=40 height=40></a></td>");
			echo ("<td align=center><a href='map.php?go=3'><img src='images/arrows/right.gif' alt='Идти на восток' width=40 height=40 border=0></a></td></tr>");
		echo ("<tr><td align=center colspan=3><a href='map.php?go=4'><img src='images/arrows/down.gif' alt='Идти на юг' width=40 height=40 border=0></a></td></tr>");
    echo("<tr><td align=center colspan=3>");

    //Выводим кнопки для атаки тех, кто находится вокруг
    echo("<font color=white><b>Напасть на</b></font><br>");
      //Кто-нибудь стоит сверху?
      $smb = trim(substr($hmap[$hx-1][$hy], 4));
      if (empty($smb))
        $smb = '0';
      if ($smb != '0')
        Button($smb, 1);
      //Кто-нибудь стоит снизу?
      $smb = trim(substr($hmap[$hx+1][$hy], 4));
      if (empty($smb))
        $smb = '0';
      if ($smb != '0')
        Button($smb, 4);
      //Кто-нибудь стоит слева?
      $smb = trim(substr($hmap[$hx][$hy-1], 4));
      if (empty($smb))
        $smb = '0';
      if ($smb != '0')
        Button($smb, 2);
      //Кто-нибудь стоит справа?
      $smb = trim(substr($hmap[$hx][$hy+1], 4));
      if (empty($smb))
        $smb = '0';
      if ($smb != '0')
        Button($smb, 3);
    //Кнопка помощи
    HelpMe(4, 1);
    echo("</td></tr>");
	echo ("</table>");
echo ("</center></td></tr></table><font color=white><b>".$message."</b><font>");
MiniMap($lg);
echo ("</center>");
?>