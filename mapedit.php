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

if (($lg != 'Anarki')&&($lg != 'Admin')&&($lg != 'TbMA')&&($lg != 'Dpako')&&($lg != 'LLIyTnick')&&($lg != 'Dictator'))
{
	moveto('edit.php');
	exit();
}

//Ошибки
if (($ry == 0)||(empty($ry))) {$ry = 1;}
if (($rx == 0)||(empty($rx))) {$rx = 1;}
if (($ry == 21)||(empty($ry))) {$ry = 20;}
if (($rx == 21)||(empty($rx))) {$rx = 20;}

//Соседи
$ury = $ry-1;
$urx = $rx-1;
$dry = $ry+1;
$drx = $rx+1;

//Ошибки
if ($ury == 0) {$ury = 1;}
if ($urx == 0) {$urx = 1;}
if ($ury == 21) {$ury = 20;}
if ($ury == 21) {$ury = 20;}

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

//Шапка таблицы
echo ("<title>Редактирование карты материка</title>");
echo ("<link rel='stylesheet' type='text/css' href='mapstyle.css'/><body background='images/terrain/grass.gif'>");

//Таблица для выравнивания
echo ("<center><h2><font color=white>Редактирование региона: $rx x $ry ($lg). Поле: $ax x $ay</font></h2></center>");

//Получаем клетку
$s = $map[$ax][$ay];

//Форма
echo ("<form action=edit.php method=post>");
echo ("<input type=hidden name=rx value=$rx>");
echo ("<input type=hidden name=ry value=$ry>");
echo ("<input type=hidden name=ax value=$ax>");
echo ("<input type=hidden name=ay value=$ay>");
echo ("<input type=hidden name=add value=1>");
echo ("<center><table border=1 CELLSPACING=0 CELLPADDING=0>");
echo ("<tr><td align=center>Параметр</td><td align=center>Значение</td></tr>");
echo ("<tr><td align=center>Территория</td><td>");
echo ("<select name=ter style='background:#000099; font-WEIGHT: BOLD; COLOR: white; cursor: hand; list-style: lower-alpha; filter: Alpha(Opacity=20, FinishOpacity=100, Style=3)'>");
echo ("<option ");
echo (" value='0'>Трава</option>");
if ($s[0] == '0') {echo (" selected");}
echo ("<option ");
if ($s[0] == '1') {echo (" selected");}
echo (" value='1'>Снег</option>");
echo ("<option ");
if ($s[0] == '2') {echo (" selected");}
echo (" value='2'>Песок</option>");
echo ("<option ");
if ($s[0] == '3') {echo (" selected");}
echo (" value='3'>Лава</option>");
echo ("<option ");
if ($s[0] == '4') {echo (" selected");}
echo (" value='4'>Вода</option>");
echo ("</select></td></tr>");
echo ("<tr><td align=center>Ресурс</td><td><select name=res style='background:#000099; font-WEIGHT: BOLD; COLOR: white; cursor: hand; list-style: lower-alpha; filter: Alpha(Opacity=20, FinishOpacity=100, Style=3)'>");
echo ("<option value='0'>Пусто</option>");
echo ("<option ");
if ($s[2] == '1') {echo (" selected ");}
echo ("value='1'>Металл</option>");
echo ("<option ");
if ($s[2] == '2') {echo (" selected ");}
echo ("value='2'>Камень</option>");
echo ("<option ");
if ($s[2] == '3') {echo (" selected ");}
echo ("value='3'>Дерево</option>");
echo ("<option ");
if ($s[2] == '4') {echo (" selected ");}
echo ("value='4'>Стена</option>");
echo ("</select></td></tr>");
echo ("<tr><td align=center>Игрок</td><td>");
//user2('users');
echo ("<select name='users' style='background:#000099; font-WEIGHT: BOLD; COLOR: white; cursor: hand; list-style: lower-alpha; filter: Alpha(Opacity=20, FinishOpacity=100, Style=3)'>");
echo ("<option value=0>Пусто</option>");
echo ("<option value=#KLAN>Адм. кланов</option>");
echo ("</select></td></tr>");
echo ("<tr><td align=center colspan=2><input type=submit value='Добавить' style='background:#000099; font-WEIGHT: BOLD; COLOR: white; cursor: hand; list-style: lower-alpha; filter: Alpha(Opacity=20, FinishOpacity=100, Style=3)'></td></tr>");
echo ("</table></form>");

echo ("<center><a href='edit.php?rx=".$rx."&ry=".$ry."'>Назад</a></center>");
?>


