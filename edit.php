<?
//Модуль функций
include "functions.php";

//Если не указано имя пользователя, то выкинуть юзера нафиг
$lg = trim($HTTP_COOKIE_VARS["nativeland"]);
$pw = trim($HTTP_COOKIE_VARS["password"]);
ban();

//Залогигнен?
if (finduser($lg, $pw) != 1)
{
	moveto('index.php');
	exit();
}

//Добавление
if ((!empty($add))&&(($lg == 'Anarki')||($lg == 'TbMA')||($lg == 'Admin')||($lg == 'Dpako')||($lg == 'LLIyTnick')||($lg == 'Dictator')))
{
	echo ('<font color=white>Клетка изменена ('.$rx.'x'.$ry.'='.$ax.'x'.$ay.'). Тип: '.$ter.'; Ресурс: '.$res.'; Игрок: '.$users.'</font>');
	
	//Читаем карту...
	$file = fopen("maps/".$rx."x".$ry.".map", "r");
	for ($x = 1; $x < 11; $x++)
	{
		for ($y = 1; $y < 11; $y++)
		{
			$map[$x][$y] = fgets($file, 255);
		}
	}
	fclose($file);

	//Заносим данные
	$map[$ax][$ay] = $ter."*".$res."=".$users."\n";

	//Только не в моём регионе
	if (($rx < 7)&&($ry < 5)&&($lg != 'Admin'))
	{
		//Нельзя!
	}
	else
	{
		//Пишем карту
		$file = fopen("maps/".$rx."x".$ry.".map", "w");
		for ($x = 1; $x < 11; $x++)
		{
			for ($y = 1; $y < 11; $y++)
			{
				fputs($file, $map[$x][$y]);
			}
		}
		fclose($file);
	}
}

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
echo ("<link rel='stylesheet' type='text/css' href='style.css'/><body background='images/terrain/grass.gif'>");

//Таблица для выравнивания
$name = getregion($rx, $ry, 0);
echo ("<center><h2><font color=white>Регион: $rx x $ry ($lg) [$name]</font></h2></center>");
echo ("<center><table border=0 width=45%><tr><td colspan=3 align=center><a href=edit.php?rx=".$rx."&ry=".$ury."><img src='images/arrows/up.gif' border=0></a></td></tr>");
echo ("<tr><td align=center width=1%><a href=edit.php?rx=".$urx."&ry=".$ry."><img src='images/arrows/left.gif' border=0></a></td><td width=60%>");
echo("<center><TABLE ALIGN=CENTER WIDTH='100%' BORDER=1 CELLSPACING=0 CELLPADDING=0>");
//echo ("<center><table border=1 width=100% height=100% CELLSPACING=0 CELLPADDING=0>");

$file = fopen("maps/".$rx."x".$ry.".map", "r");

//Карта
for ($x = 1; $x < 11; $x++)
{
	echo("<tr>");
	//Новая ячейка таблицы

	for ($y = 1; $y < 11; $y++)
	{
	echo ("<td align=center>");
	if (($lg == 'Admin')||($lg == 'Anarki')||($lg == 'TbMA')||($lg == 'Dictator')||($lg == 'Dpako')||($lg == 'LLIyTnick'))
      {
		echo ("<a href=mapedit.php?rx=".$rx."&ry=".$ry."&edit=1&ax=".$x."&ay=".$y." alt='fuck'>");
	  } else
	  {
		if ($lg == 'Admin')
		  {
			echo ("<a href=edit.php?rx=".$rx."&ry=".$ry."&atack=1&ax=".$x."&ay=".$y." alt='fuck'>");
		  }
	  }

		//Получаем данные из ячейки
		$fld = fgets($file, 255);

		//Фон
		if (($fld[0] == '0')&&($fld[2] == 0)&&($fld[4] == '0'))
		{
			echo ("<img src='images/terrain/grass.gif' width=32 height=32 BORDER=0 alt=Трава>");
		}
		if (($fld[0] == '1')&&($fld[2] == 0)&&($fld[4] == '0'))
		{
			echo ("<img src='images/terrain/snow.gif' width=32 height=32 BORDER=0 alt=Снег>");
		}
		if (($fld[0] == '2')&&($fld[2] == 0)&&($fld[4] == '0'))
		{
			echo ("<img src='images/terrain/sand.bmp' width=32 height=32 BORDER=0 alt=Песок>");
		}
		if (($fld[0] == '3')&&($fld[2] == 0)&&($fld[4] == '0'))
		{
			echo ("<img src='images/terrain/Fire.jpg' width=32 height=32 BORDER=0 alt=Лава>");
		}
		if (($fld[0] == '4')&&($fld[2] == 0)&&($fld[4] == '0'))
		{
			echo ("<img src='images/terrain/water.gif' width=32 height=32 BORDER=0 alt=Вода>");
		}
		//Металл
		if (($fld[2] == '1')&&($fld[4] == '0')&&($fld[0] == '1'))
		{
			echo ("<img src='images/terrain/snmetal.bmp' width=32 height=32 BORDER=0 alt='Залежи металла'>");
		}
		if (($fld[2] == '1')&&($fld[4] == '0')&&($fld[0] == '2'))
		{
			echo ("<img src='images/terrain/smetal.bmp' width=32 height=32 BORDER=0 alt='Залежи металла'>");
		}
		if (($fld[2] == '1')&&($fld[4] == '0')&&($fld[0] == '3'))
		{
			echo ("<img src='images/terrain/lmetal.bmp' width=32 height=32 BORDER=0 alt='Залежи металла'>");
		}
		if (($fld[2] == '1')&&($fld[4] == '0')&&($fld[0] == '0'))
		{
			echo ("<img src='images/terrain/metal.bmp' width=32 height=32 BORDER=0 alt='Залежи металла'>");
		}
		//Камень
		if (($fld[2] == '2')&&($fld[4] == '0')&&($fld[0] == '3'))
		{
			echo ("<img src='images/terrain/lrock.bmp' width=32 height=32 BORDER=0 alt='Залежи камня'>");
		}
		if (($fld[2] == '2')&&($fld[4] == '0')&&($fld[0] == '2'))
		{
			echo ("<img src='images/terrain/srock.bmp' width=32 height=32 BORDER=0 alt='Залежи камня'>");
		}
		if (($fld[2] == '2')&&($fld[4] == '0')&&($fld[0] == '1'))
		{
			echo ("<img src='images/terrain/snrock.bmp' width=32 height=32 BORDER=0 alt='Залежи камня'>");
		}
		if (($fld[2] == '2')&&($fld[4] == '0')&&($fld[0] == '0'))
		{
			echo ("<img src='images/terrain/rock.bmp' width=32 height=32 BORDER=0 alt='Залежи камня'>");
		}
		//Дерево
		if (($fld[2] == '3')&&($fld[0] == '0')&&($fld[4] == '0'))
		{
			echo ("<img src='images/terrain/tree".rand(1,3).".gif' width=32 height=32 BORDER=0 alt=Деревья>");
		}
		if (($fld[2] == '3')&&($fld[0] == '1')&&($fld[4] == '0'))
		{
			echo ("<img src='images/terrain/sntree1.bmp' width=32 height=32 BORDER=0 alt=Деревья>");
		}
		if (($fld[2] == '3')&&($fld[0] == '2')&&($fld[4] == '0'))
		{
			echo ("<img src='images/terrain/stree".rand(1,2).".bmp' width=32 height=32 BORDER=0 alt=Деревья>");
		}
		if (($fld[2] == '3')&&($fld[0] == '3')&&($fld[4] == '0'))
		{
			echo ("<img src='images/terrain/ltree.bmp' width=32 height=32 BORDER=0 alt=Деревья>");
		}
		if (($fld[2] == '4'))
		{
			echo ("<img src='images/objects/wall.gif' width=32 height=32 BORDER=0 alt=Стена>");
		}
		//Игрок
		 if ($fld[4] != '0')
		{
			echo ("<img src='images/terrain/castle.gif' width=32 height=32 BORDER=0 alt='Замок. Территорией обладает ".getdata(trim(substr($fld, 4)), 'hero', 'name')."'>");
		}
		echo ("</a></td>");
	}
	echo ("</tr>");
}

fclose($file);

//Конец таблицы
echo ("</table></center>");
echo ("</td><td width=1% align=center><a href=edit.php?rx=".$drx."&ry=".$ry."><img src='images/arrows/right.gif' border=0></a></td></tr>");
echo ("<tr><td colspan=3 align=center><a href=edit.php?rx=".$rx."&ry=".$dry."><img src='images/arrows/down.gif' border=0></a></td></tr></table>");
?>

