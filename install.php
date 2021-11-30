<?

include "functions.php";

//Уровень ошибок
Error_Reporting(E_ALL & ~E_NOTICE);

//Добавляем регион
function addzone($rx, $ry, $name, $zone)
{
	mysql_query ("insert into map values ('$rx', '$ry', '$name', '$zone');");
}

//Добавляем воина
function addwarrior($name, $level, $race, $power, $protect, $archery, $arrows, $health, $img, $addon)
{
	mysql_query ("insert into warriors values ('$name', '$health', '$power', '$protect', '$archery', '$arrows', '$img', '$race', '$level', '$addon');");
}

//Добавляем событие
function addevent($num, $name, $effect, $how)
{
	mysql_query ("insert into events values ('$num', '$name', '$effect', '$how');");
}

if (!empty($final))
{

//Проверям длину полей и корректность e-mail адреса и числовое значение поля курса
$ohno = 0;
$err = 0;
$ml = 0;
if ((strlen($login) < 2)||(strlen($passwd) < 2)||(strlen($surname) < 2)||(strlen($mname) < 2)||(strlen($country) < 2)||(strlen($city) < 2)||(strlen($email) < 2)||(strlen($url) < 2)||(strlen($heroname) < 2)||(strlen($race) < 2)||(strlen($type) < 2)||(strlen($moneyname) < 2)||(strlen($curse) < 1)||(strlen($gcountry) < 2)||(strlen($gcapital) < 2)||(strlen($res) < 2))
   {
   $ohno = 1;
   }

//Проверяем e-mail на корректность
if (!empty($email))
	{
	if (!preg_match("/[0-9a-z]+@[0-9a-z_^\.]+\.[a-z]{2,3}/i", $email))
		{
		$ml = 1;
		}
	}

//Проверяем курс
if (!empty($email))
	{
	if (!preg_match("/[0-9]/i", $curse))
		{
		$err = 1;
		}
	}

//Есть ошибки?
if (($ohno != 0)||($err != 0)||($ml != 0))
	{
    echo ("<br>");
    echo ("<script language=JavaScript>");
    echo ("function rt()");
    echo ("{");
    echo ("window.location.href('install.php?start=09');");
    echo ("}");
    echo ("</script>");
	echo ("<body background='images\back.jpe'>");
	echo ("<font color=green>Ошибка. Не все поля заполнены или длина одного из полей меньше двух символов. Заполните все поля.<br><br>");
    if ($ml != 0)
		{
		echo ("Также проверьте правильность заполнения e-mail адреса.");
		}
    if ($err != 0)
		{
		echo ("В поле 'курс' может быть только число!");
		}
	?>
    <form action="javascript:rt();">
    <input type='submit' name='stop' value='  Назад  ' style='background:#000099; font-WEIGHT: BOLD; COLOR: white; cursor: hand; list-style: lower-alpha; filter: Alpha(Opacity=20, FinishOpacity=100, Style=3)'>      
	</form>
    <?
	exit();
	}

$final = "";
$file = fopen("config.ini.php", "r");
$temp = fgets($file, 255);
$temp = fgets($file, 255);
$host = fgets($file, 255);
$db = fgets($file, 255);
$log = fgets($file, 255);
$pwd = fgets($file, 255);
fclose($file);

$host = trim($host);
$log = trim($log);
$pwd = trim($pwd);
$db = trim($db);

$db_connect = @mysql_connect($host, $log, $pwd);
mysql_select_db($db, $db_connect);

$usr = mysql_query("select * from users;");
if ($usr)
   {
   while ($user = mysql_fetch_array($usr))
	   {
       if ($user['login'] == $login)
		   {
		   $final = "";
		   $start = 46;
  		   echo ("<br>");
           echo ("<script language=JavaScript>");
	       echo ("function rt()");
	       echo ("{");
	       echo ("window.location.href('install.php?start=46');");
	       echo ("}");
	       echo ("</script>");
		   echo ("<body background='images\back.jpe'>");
	       echo ("<font color=green>Внимание! Пользователь с таким именем уже зарегистрирован. Измените 'Имя пользователя'</font><br><br>");
           ?>
	       <form action="javascript:rt();">
           <input type='submit' name='back' value='  Назад  ' style='background:#000099; font-WEIGHT: BOLD; COLOR: white; cursor: hand; list-style: lower-alpha; filter: Alpha(Opacity=20, FinishOpacity=100, Style=3)'>      
	       </form>
           <?
		   exit();
		   }
	   }
   }
   else
	{
	   $final = "";
	   $start = 348;
 	   echo ("<br>");
       echo ("<script language=JavaScript>");
	   echo ("function rt()");
	   echo ("{");
	   echo ("window.location.href('install.php?start=348');");
	   echo ("}");
	   echo ("</script>");
	   echo ("<body background='images\back.jpe'>");
	   echo ("Ошибка. Невозможно подключиться к базе данных. Попробуйте ещё раз.<br><br>");
       ?>
	   <form action="javascript:rt();">
       <input type='submit' name='back' value='  Назад  ' style='background:#000099; font-WEIGHT: BOLD; COLOR: white; cursor: hand; list-style: lower-alpha; filter: Alpha(Opacity=20, FinishOpacity=100, Style=3)'>       
	   </form>
       <?
	   exit();
	}

//Стандартное значение нуля.
$val = 0;
$curse = (int)$curse;

if ($curse < 1)
	{
	$curse = 1;
	}
(int)$r1 = $curse*20;
$r2 = 20;
$r3 = 20;
$r4 = 20;
$p = 25;
$n = 5;
$hl = 100;
$lv = 1;

//Информация о битвах игрока
mysql_query ("insert into battles values('$login', 0, '0', 0);");

//Информация разная
mysql_query ("insert into inf values('$login', '0', '0', '0', '0', '0', '0', '0', '0', '0', '0', '0', '0', '0');");

//Информация об IP адресах
mysql_query("insert into ip values ('$login', '$REMOTE_ADDR');");

//Контрольные
mysql_query ("insert into lostpass values('$login', '$cquest', '$cansw');");

//Заполняем информацию об игроке
mysql_query("insert into users values ('$login', '$passwd', '$surname', '$mname', '$city', '$country', '$email', '$url');", $db_connect);

//Заполняем информацию об персонаже игрока
mysql_query("insert into hero values ('$login', '$heroname', '$val', '$lv', '$val', '$race', '$type', '$hl', '$login');", $db_connect);

//Заполняем информацию об экономике королевства
mysql_query("insert into economic values ('$login', '$r1', '$r2', '$r3', '$r4', '$curse', '$moneyname', '$p', '$n');", $db_connect);

//Заполняем информацию о заклинаниях игрока
mysql_query("insert into magic values ('$login', 0, 0, 0, 0, 0, 0);", $db_connect);

//Заполняем информацию о союзе игрока
mysql_query("insert into unions values ('$login', '$login', '$login', '$login', '$login');", $db_connect);

//Заполняем информацию о вещах на персонаже (артефакты)
mysql_query("insert into items values ('$login', '$val', '$val', '$val', '$val', '$val', '$val', '$val', '$val', '$val', '$val', '$val');", $db_connect);

//Заполняем информацию о королевстве
mysql_query("insert into info values ('$login', '$gcountry', '$gcapital', '$res');", $db_connect);

//Заполняем информацию о постройках в замке
mysql_query("insert into city values ('$login', '$login', '$val', '$val', '$val', '$val', '$val', '$val', '$val', '$val', '$val', '$val', '$val', '$val', '$val', '$val', '$val', '$val', '$val', '$val', '$val', '$val');", $db_connect);

//Заполняем информацию о временных переменных
mysql_query("insert into temp values ('$login', '', '');", $db_connect);

//Заполняем информацию о статусе
mysql_query("insert into status values ('$login', '0', '0', '0', '0', '0');", $db_connect);

//Заполняем информацию о времени
$t = time();
mysql_query("insert into time values ('$login', '$t', '10', '10');", $db_connect);

//Информация о координатах столицы
mysql_query ("insert into capital values('$login', 0, 0, 0, 0);");

//Случайное место для столицы
randomplace($login);

//Создаём файлы...
$file = fopen("data/trade/".$login, "w");
fclose($file);
$file = fopen("data/mail/".$login, "w");
fclose($file);
$file = fopen("data/logs/".$login.".log", "w");
fclose($file);

//Расчитываем способности игрока
$power = 1;
$protect = 1;
$magicpower = 1;
$know = 1;
$charism = 1;
$dexterity = 1;
$intel = 1;
$naturemagic = 1;
$combatmagic = 1;
$mindmagic = 1;

switch ($race)
	{
	case "people":
		$power++;
     	$protect++;
		$dexterity++;
		$charism++;
		$intel++;
		break;
	case "elf":
		$power++;
	    $dexterity++;
		$dexterity++;
		$charism++;
		$intel++;
		break;
	case "hnom":
		$power++;
    	$protect++;
		$protect++;
		$dexterity++;
		$intel++;
		break;
	case "druid":
		$protect++;
	    $know++;
	    $know++;
	    $know++;
		$intel++;
		break;
	case "necro":
		$power++;
		$power++;
    	$know++;
		$know++;
		$intel++;
		$intel++;
		$charism--;
		break;
	case "hell":
		$power++;
		$protect++;
		$protect++;
		$know++;
		$know++;
		$intel++;
		$charism--;
		break;
	}

switch ($type)
	{
	case "knight":
		$power++;
		$power++;
		$protect++;
		$protect++;
		$dexterity++;
		break;
	case "archer":
		$power++;
		$power++;
		$protect++;
		$dexterity++;
		$dexterity++;
		break;
	case "mag":
		$combatmagic++;
		$combatmagic++;
		$naturemagic++;
		$naturemagic++;
		$mindmagic++;
		break;
	case "lekar":
		$combatmagic++;
		$naturemagic++;
		$naturemagic++;
		$naturemagic++;
		$mindmagic++;
		break;
	case "barbarian":
		$power++;
		$power++;
		$power++;
		$protect++;
		$protect++;
		break;
	case "wizard":
		$combatmagic++;
		$mindmagic++;
		$mindmagic++;
		$naturemagic++;
		$mindmagic++;
		break;
	}

//Заполняем информацию о герое
mysql_query("insert into abilities values ('$login', '$power', '$protect', '$magicpower', '$know', '$charism', '$dexterity', '$intel', '$naturemagic', '$combatmagic', '$mindmagic');", $db_connect);

//Текущее время
$tm = time();

//Заносим основные данные в таблицы данные 
mysql_query ("insert into settings values ('$login', '$login', '1', '0', '0', '1');");
mysql_query ("insert into settings values ('Settings', '$tm', '1', '1', '0', '0');");

//Если всё нормально
echo ("<br>");
echo ("<script language=JavaScript>");
echo ("function rt()");
echo ("{");
echo ("window.location.href('index.php');");
echo ("}");
echo ("</script>");
echo ("<body background='images\back.jpe'>");
echo ("<h2><font color=#3399FF>Поздравляем!</font></h2>");
echo ("<font color=green>Установка успешно завершена. Нажмите кнопку 'Завершить установку для перехода к началу игры.'</font><br><br>");
echo ("Ниже Вы можете видеть таблицу Вашего персонажа. Можете себе её распечатать<br>");
?>

<table border=1 width=90% CELLSPACING=0 CELLPADDING=0>
<tr><td align=center><font color=green>Параметр</font></td><td align=center><font color=green>Значение</font></td></tr>
<?

echo ("<tr><td align=center><font color=green>Имя</font></td><td align=center><font color=green>$heroname</font></td></tr>");
echo ("<tr><td align=center><font color=green>Раса</font></td><td align=center><font color=green>$race</font></td></tr>");
echo ("<tr><td align=center><font color=green>Тип</font></td><td align=center><font color=green>$type</font></td></tr>");
echo ("<tr><td align=center><font color=green>Сила</font></td><td align=center><font color=green>$power</font></td></tr>");
echo ("<tr><td align=center><font color=green>Защита</font></td><td align=center><font color=green>$protect</font></td></tr>");
echo ("<tr><td align=center><font color=green>Ловкость</font></td><td align=center><font color=green>$dexterity</font></td></tr>");
echo ("<tr><td align=center><font color=green>Знания</font></td><td align=center><font color=green>$know</font></td></tr>");
echo ("<tr><td align=center><font color=green>Удача</font></td><td align=center><font color=green>$charism</font></td></tr>");
echo ("<tr><td align=center><font color=green>Интеллект</font></td><td align=center><font color=green>$intel</font></td></tr>");
echo ("<tr><td align=center><font color=green>Боевая магия</font></td><td align=center><font color=green>$combatmagic</font></td></tr>");
echo ("<tr><td align=center><font color=green>Магия природы</font></td><td align=center><font color=green>$naturemagic</font></td></tr>");
echo ("<tr><td align=center><font color=green>Магия разума</font></td><td align=center><font color=green>$mindmagic</font></td></tr>");
?>
</table>
<br>
<form action="javascript:rt();">
<center><input type='submit' name='finish' value='  Завершить установку  ' style='background:#000099; font-WEIGHT: BOLD; COLOR: white; cursor: hand; list-style: lower-alpha; filter: Alpha(Opacity=20, FinishOpacity=100, Style=3)'></center>
</form>
<?
exit();
}


if (!empty($start))
{
	?>
    <h2><font color=#3399FF>Шаг 3. Настройка игры</font></h2>
    <font color=blue>Внимание! Человек, зашедший под этим именем является администратором<br></font>
	<body background='images\back.jpe'>
	<form action="install.php" method=post>
    <input type="hidden" name='final' value='1234'>
	<center>
	<table border=1 width=95% cols=3 CELLSPACING=0 CELLPADDING=0>
    <tr width=20%><td align=center><font color=#3399FF>Параметр:</td><td align=center><font color=#3399FF>Значение:</td><td align=center><font color=#3399FF>Комментарий:</td></tr>
	<tr><td><font color=green>Имя пользователя:</td><td align = center><input type='text' name='login' style='background:#000099; font-WEIGHT: BOLD; COLOR: white; cursor: hand; list-style: lower-alpha; filter: Alpha(Opacity=20, FinishOpacity=100, Style=3)'></td><td>Под этим именем Вы будете входить в систему</td></tr>
    <tr><td><font color=green>Пароль:</td><td align = center><input type='password' name='passwd' style='background:#000099; font-WEIGHT: BOLD; COLOR: white; cursor: hand; list-style: lower-alpha; filter: Alpha(Opacity=20, FinishOpacity=100, Style=3)'></td><td>Также, Вам будет необходимо ввести этот пароль. Это необходимо для безопастности</td></tr>
	<tr><td><font color=green>Фамилия:</td><td align = center><input type='text' name='surname' style='background:#000099; font-WEIGHT: BOLD; COLOR: white; cursor: hand; list-style: lower-alpha; filter: Alpha(Opacity=20, FinishOpacity=100, Style=3)'></td><td>Ваша фамилия необходима для заполнения базы.</td></tr>
	<tr><td><font color=green>Имя:</td><td align = center><input type='text' name='mname' style='background:#000099; font-WEIGHT: BOLD; COLOR: white; cursor: hand; list-style: lower-alpha; filter: Alpha(Opacity=20, FinishOpacity=100, Style=3)'></td><td>Ваше имя необходимо для общения с Вами.</td></tr>
	<tr><td><font color=green>Страна проживания:</td><td align = center><input type='text' style='background:#000099; font-WEIGHT: BOLD; COLOR: white; cursor: hand; list-style: lower-alpha; filter: Alpha(Opacity=20, FinishOpacity=100, Style=3)' name='country'></td><td>Необходима для статистики сведений игроков. (Конфиденциально)</td></tr>
	<tr><td><font color=green>Город:</td><td align = center><input type='text' name='city' style='background:#000099; font-WEIGHT: BOLD; COLOR: white; cursor: hand; list-style: lower-alpha; filter: Alpha(Opacity=20, FinishOpacity=100, Style=3)'></td><td>Необходим для статистики сведений игроков. (Конфиденциально)</td></tr>
	<tr><td><font color=green>E-Mail:</td><td align = center><input type='text' name='email' style='background:#000099; font-WEIGHT: BOLD; COLOR: white; cursor: hand; list-style: lower-alpha; filter: Alpha(Opacity=20, FinishOpacity=100, Style=3)'></td><td>Необходим для связи с Вами.</td></tr>
	<tr><td><font color=green>URL:</td><td align = center><input type='text' name='url' style='background:#000099; font-WEIGHT: BOLD; COLOR: white; cursor: hand; list-style: lower-alpha; filter: Alpha(Opacity=20, FinishOpacity=100, Style=3)'></td><td>Если у Вас есть ваш собственный сайт, будем Вам признательны.</td></tr>
	<tr><td><font color=green>Контрольный вопрос:</td><td align = center><input type='text' name='сquest' style='background:#000099; font-WEIGHT: BOLD; COLOR: white; cursor: hand; list-style: lower-alpha; filter: Alpha(Opacity=20, FinishOpacity=100, Style=3)'></td><td>Если Вы случайно потеряете Ваш пароль, то, чтобы восстановить его, сервер задаст Вам вопрос.</td></tr>
	<tr><td><font color=green>Контрольный ответ:</td><td align = center><input type='text' name='cansw' style='background:#000099; font-WEIGHT: BOLD; COLOR: white; cursor: hand; list-style: lower-alpha; filter: Alpha(Opacity=20, FinishOpacity=100, Style=3)'></td><td>И если Вы введёте этот ответ, то Вам вернут утерянный пароль.</td></tr>
	<tr><td><font color=#3366FF>Имя героя</td><td align = center><input type='text' name='heroname' style='background:#000099; font-WEIGHT: BOLD; COLOR: white; cursor: hand; list-style: lower-alpha; filter: Alpha(Opacity=20, FinishOpacity=100, Style=3)'></td><td>Под этим именем Вы будете известны в игре, как управляющий Вашим государством.</td></tr>
    <tr><td><font color=#3366FF>Раса героя:</td><td align = center><select name='race' style='background:#000099; font-WEIGHT: BOLD; COLOR: white; cursor: hand; list-style: lower-alpha; filter: Alpha(Opacity=20, FinishOpacity=100, Style=3)'>
	<option value='people'>Человек</option>
	<option value='elf'>Эльф</option>
	<option value='hnom'>Гном</option>
	<option value='druid'>Друид</option>
	<option value='necro'>Некромант</option>
	<option value='hell'>Еретик</option>
	</select></td><td align=center>
	<table border = 1 CELLSPACING=0 CELLPADDING=0>
	<tr><td align=center>Раса:</td><td>Сила:</td><td>Защита:</td><td>Ловкость:</td><td>Знания:</td><td>Удача:</td><td>Интеллект:</td></tr>
	<tr><td align=center>Человек</td><td align=center>+1</td><td align=center>+1</td><td align=center>+1</td><td align=center>0</td><td align=center>+1</td><td align=center>+1</td></tr>
    <tr><td align=center>Эльф</td><td align=center>+1</td><td align=center>0</td><td align=center>+2</td><td align=center>0</td><td align=center>+1</td><td align=center>+1</td></tr>
    <tr><td align=center>Гном</td><td align=center>+1</td><td align=center>+2</td><td align=center>+1</td><td align=center>0</td><td align=center>0</td><td align=center>+1</td></tr>
    <tr><td align=center>Друид</td><td align=center>0</td><td align=center>+1</td><td align=center>0</td><td align=center>+3</td><td align=center>0</td><td align=center>+1</td></tr>
    <tr><td align=center>Некромант</td><td align=center>+2</td><td align=center>0</td><td align=center>0</td><td align=center>+2</td><td align=center>-1</td><td align=center>+2</td></tr>
    <tr><td align=center>Еретик</td><td align=center>+1</td><td align=center>+2</td><td align=center>0</td><td align=center>+2</td><td align=center>-1</td><td align=center>+1</td></tr>
	</table>
	</td></tr>
	<tr><td><font color=#3366FF>Тип героя:</td><td align = center><select name='type' style='background:#000099; font-WEIGHT: BOLD; COLOR: white; cursor: hand; list-style: lower-alpha; filter: Alpha(Opacity=20, FinishOpacity=100, Style=3)'>
	<option value='knight'>Рыцарь</option>
	<option value='archer'>Стрелок</option>
	<option value='mag'>Маг</option>
	<option value='lekar'>Целитель</option>
	<option value='barbarian'>Варвар</option>
	<option value='wizard'>Волшебник</option>
	</select></td><td align=center>
	<table border = 1 CELLSPACING=0 CELLPADDING=0>
	<tr><td align=center>Тип:</td><td>Сила:</td><td>Защита</td><td>Ловкость:</td><td>Боевая магия:</td><td>Магия природы:</td><td>Магия разума:</td></tr>
	<tr><td align=center>Рыцарь</td><td align=center>+2</td><td align=center>+2</td><td align=center>+1</td><td align=center>0</td><td align=center>0</td><td align=center>0</td></tr>
    <tr><td align=center>Стрелок</td><td align=center>+2</td><td align=center>+1</td><td align=center>+2</td><td align=center>0</td><td align=center>0</td><td align=center>0</td></tr>
    <tr><td align=center>Маг</td><td align=center>0</td><td align=center>0</td><td align=center>0</td><td align=center>+2</td><td align=center>+2</td><td align=center>+1</td></tr>
    <tr><td align=center>Целитель</td><td align=center>0</td><td align=center>0</td><td align=center>0</td><td align=center>+1</td><td align=center>+3</td><td align=center>+1</td></tr>
    <tr><td align=center>Варвар</td><td align=center>+3</td><td align=center>+2</td><td align=center>0</td><td align=center>0</td><td align=center>0</td><td align=center>0</td></tr>
    <tr><td align=center>Волшебник</td><td align=center>0</td><td align=center>0</td><td align=center>0</td><td align=center>+1</td><td align=center>+1</td><td align=center>+3</td></tr>
	</table>	
	</td></tr>
    <tr><td><font color=#FF0033>Название денег:</td><td align = center><input type='text' name='moneyname' style='background:#000099; font-WEIGHT: BOLD; COLOR: white; cursor: hand; list-style: lower-alpha; filter: Alpha(Opacity=20, FinishOpacity=100, Style=3)'></td><td>Как будут называться деньги в Вашей стране (мн. ч. например, [рубли])</td></tr> 
	<tr><td><font color=#FF0033>Курс денег к металлу:</td><td align = center><input type='text' style='background:#000099; font-WEIGHT: BOLD; COLOR: white; cursor: hand; list-style: lower-alpha; filter: Alpha(Opacity=20, FinishOpacity=100, Style=3)' name='curse'></td><td>Целое число, большее нуля. Т.е. за 1 единицу метала, Вы заплатите n единиц золота в Вашей валюте.</td></tr>
    <tr><td><font color=#FF0033>Название страны:</td><td align = center><input type='text' style='background:#000099; font-WEIGHT: BOLD; COLOR: white; cursor: hand; list-style: lower-alpha; filter: Alpha(Opacity=20, FinishOpacity=100, Style=3)' name='gcountry'></td><td>Именно как управляющим этим государством Вы будете известны в игре.</td></tr>
    <tr><td><font color=#FF0033>Название столицы:</td><td align = center><input type='text' name='gcapital' style='background:#000099; font-WEIGHT: BOLD; COLOR: white; cursor: hand; list-style: lower-alpha; filter: Alpha(Opacity=20, FinishOpacity=100, Style=3)'></td><td>Как будет называться столица Вашего государства</td></tr>
    <tr><td><font color=#FF0033>Богатства страны:</td><td align = center><select name='res' style='background:#000099; font-WEIGHT: BOLD; COLOR: white; cursor: hand; list-style: lower-alpha; filter: Alpha(Opacity=20, FinishOpacity=100, Style=3)'>
	<option value='metal'>Металл</option>
	<option value='rock'>Камень</option>
	<option value='wood'>Дерево</option>
	</select></td><td>Какой из трёх ресурсов будет добываться в Вашей стране? Остальные два Вам придётся закупать у своих союзников или импортировать из других стран.</td></tr>
	</table>
	<br><input type='submit' name='prefinish' value='  Далее  ' style='background:#000099; font-WEIGHT: BOLD; COLOR: white; cursor: hand; list-style: lower-alpha; filter: Alpha(Opacity=20, FinishOpacity=100, Style=3)'>
	</center>
	</form>

	<?
	exit();
}

if (empty($host))
{
	//Если уже установлено, то удаляем старую базу
	if ($ok != 1)
	{
		$file = fopen("config.ini.php", "r");
		if ($file)
		{
			$ttemp = trim(fgets($file, 255));
			$ttemp = trim(fgets($file, 255));
			$thost = trim(fgets($file, 255));
			$tbase = trim(fgets($file, 255));
			$tname = trim(fgets($file, 255));
			$tpass = trim(fgets($file, 255));
			fclose($file);
			$test = @mysql_connect($thost,$tname,$tpass);
			if ($test)
			{
//				$q = mysql_query("drop database ".$tbase, $test);
			}
			else
			{
				unlink("config.ini.php");
				echo("<script>window.location.href('install.php');</script>");
			}

		}
		else
		{
			echo("<script>window.location.href('install.php?ok=1');</script>");
		}
	}

	?>
  	<html>
    <center><h2><font color=#3399FF>Установка Native Land</font></h2></center>
    <H4><font color=#00CC99>Добро пожаловать в программу установки PHP продукта - Native Land. Эта программа поможет Вам корректно настроить работоспособность продукта. Следуйте инструкциям, которые будут Вам отображены в ходе установки...</H4>

    <h2><font color=#3399FF>Шаг 1. Установка связи с базой данных MySql</font></h2>
    <center>
    <form action="install.php" method="post">
    <table border=1 width=40% cols=2 CELLSPACING=0 CELLPADDING=0>
    <tr><td align=center>Параметр</td><td align = center>Значение</tr></tr>
    <tr><td align=left>Хост базы данных:</td><td align=center><input type="text" name="host" value="localhost" style='background:#000099; font-WEIGHT: BOLD; COLOR: white; cursor: hand; list-style: lower-alpha; filter: Alpha(Opacity=20, FinishOpacity=100, Style=3)'></td></tr>
    <tr><td align=left>Имя базы данных:</td><td align=center><input type="text" name="name" value="venta" style='background:#000099; font-WEIGHT: BOLD; COLOR: white; cursor: hand; list-style: lower-alpha; filter: Alpha(Opacity=20, FinishOpacity=100, Style=3)'></td></tr>
    <tr><td align=left>Имя пользователя:</td><td align=center><input type="text" name="log" value="root" style='background:#000099; font-WEIGHT: BOLD; COLOR: white; cursor: hand; list-style: lower-alpha; filter: Alpha(Opacity=20, FinishOpacity=100, Style=3)'></td></tr>
    <tr><td align=left>Пароль доступа:</td><td align=center><input type="password" name="dpwd" value="" style='background:#000099; font-WEIGHT: BOLD; COLOR: white; cursor: hand; list-style: lower-alpha; filter: Alpha(Opacity=20, FinishOpacity=100, Style=3)'></td></tr>
    </table><br>
    <center><input type="submit" value="  Далее  " style='background:#000099; font-WEIGHT: BOLD; COLOR: white; cursor: hand; list-style: lower-alpha; filter: Alpha(Opacity=20, FinishOpacity=100, Style=3)'></center>
    </form>
    </center>
	<?
    if (!empty($error))
	{
		echo ("<font color='green'>Внимание! Во время установки конфигурации обнаружена следующая ошибка: ".$error."</font>");
	}
}
else
{
   $db_connect = @mysql_connect($host, $log, $dpwd);
   if (!$db_connect)
   {
      $error = "<font color=green>Невозможно подключиться к серверу MySql";
      echo $error;
	  echo ("<br>");
      echo ("<script language=JavaScript>");
	  echo ("function rt()");
	  echo ("{");
	  echo ("window.location.href('install.php');");
	  echo ("}");
	  echo ("</script>");
  	  echo ("<body background='images\back.jpe'>");
	  echo ("Попробуйте использовать другие настройки.<br><br>");
	  ?>
      <form action="javascript:rt();">
      <input type='submit' name='back' value='  Назад  ' style='background:#000099; font-WEIGHT: BOLD; COLOR: white; cursor: hand; list-style: lower-alpha; filter: Alpha(Opacity=20, FinishOpacity=100, Style=3)'>      
	  </form>
	  <?
	  exit();
   }

$slct = mysql_select_db($dbname, $db_connect);

if (!$slct)
   {
   mysql_query('create database '.$name,$db_connect);
   echo ("<font color=green>Создана база данных $name</font><br>");
   }
   else
	{
	   echo("<font color=green>База данных $name выбрана для использования</font><br>");
	}

$file = fopen("config.ini.php", "w");
fputs($file, "<?\n");
fputs($file, "/*\n");
fputs($file, "$host\n");
fputs($file, "$name\n");
fputs($file, "$log\n");
fputs($file, "$pwd");
fputs($file, "*/\n");
fputs($file, "?>\n");
fclose ($file);
echo ("<body background='images\back.jpe'>");
echo ("<font color=#3399FF><h2>Шаг 2. Процесс создания таблиц...</font></h2>");
mysql_query ('use '.$name, $db_connect);

mysql_query ('create table users (login text, pwd text, surname text, name text, city text, country text, email text, url text) TYPE=MyISAM;');
echo("<font color=green>Создана таблица 'users'</font><br>");

mysql_query ('create table war (login text, lx1 text, ly1 text, lx2 text, ly2 text, lx3 text, ly3 text, lx4 text, ly4 text, step1 text, step2 text, step3 text, step4 text, health1 text, health2 text, health3 text, health4 text, arrow1 text, arrow2 text, arrow3 text, arrow4 text) TYPE=MyISAM;');
echo("<font color=green>Создана таблица 'war'</font><br>");

mysql_query ('create table mapbuild (number text, login text, type text, rx text, ry text, cx text, cy text, info text) TYPE=MyISAM;');
echo("<font color=green>Создана таблица 'mapbuild'</font><br>");

mysql_query ('create table hosting (login text, dir text) TYPE=MyISAM;');
echo("<font color=green>Создана таблица 'hosting'</font><br>");

mysql_query ('create table inf (login text, icq text, about text, def text, showmyinfo text, fld1 text, fld2 text, fld3 text, fld4 text, fld5 text, fld6 text, fld7 text, fld8 text, fld9 text) TYPE=MyISAM;');
echo("<font color=green>Создана таблица 'inf'</font><br>");

mysql_query ('create table status (login text, online text, timeout text, f1 text, f2 text, f3 text) TYPE=MyISAM;');
echo("<font color=green>Создана таблица 'status'</font><br>");

mysql_query ('create table hero (login text, name text, expa int(10) default NULL, level int(10) default NULL, upgrade int(10) default NULL, race text, type text, health int (10) default NULL, location text) TYPE=MyISAM;');
echo("<font color=green>Создана таблица 'hero'</font><br>");

mysql_query ('create table abilities (login text, power int(10) default NULL, protect int(10) default NULL, magicpower int(10) default NULL, cnowledge int(10) default NULL, charism int(10) default NULL, dexterity int(10) default NULL, intellegence int(10) default NULL, naturemagic int(10) default NULL, combatmagic int(10) default NULL, mindmagic int(10) default NULL) TYPE=MyISAM;');
echo("<font color=green>Создана таблица 'abilities'</font><br>");

mysql_query ('create table magic (login text, cast1 int(10) default NULL, cast2 int(10) default NULL, cast3 int(10) default NULL, cast4 int(10) default NULL, cast5 int(10) default NULL, cast6 int(10) default NULL) TYPE=MyISAM;');
echo("<font color=green>Создана таблица 'magic'</font><br>");

mysql_query ('create table items (login text, golova int(10) default NULL, shea int(10) default NULL, telo int(10) default NULL, tors int(10) default NULL, palec int(10) default NULL, leftruka int(10) default NULL, rightruka int(10) default NULL, vrukah int(10) default NULL, nogi int(10) default NULL, koleni int(10) default NULL, plash int(10) default NULL) TYPE=MyISAM;');
echo("<font color=green>Создана таблица 'items'</font><br>");

mysql_query ('create table economic (login text, money int(10) default NULL, metal int(10) default NULL, rock int(10) default NULL, wood int(10) default NULL, curse int(10) default NULL, moneyname text, peoples int(10) default NULL, nalog int(10) default NULL) TYPE=MyISAM;');
echo("<font color=green>Создана таблица 'economic'</font><br>");

//Курс денег к металлу
mysql_query ('create table info (login text, country text, capital text, resource text) TYPE=MyISAM;');
echo("<font color=green>Создана таблица 'info'</font><br>");
//resource - чем богата страна

mysql_query ('create table city (login text, name text, build1 int(10) default NULL, build2 int(10) default NULL, build3 int(10) default NULL, build4 int(10) default NULL, build5 int(10) default NULL, build6 int(10) default NULL, build7 int(10) default NULL, build8 int(10) default NULL, build9 int(10) default NULL, build10 int(10) default NULL, build11 int(10) default NULL, build12 int(10) default NULL, build13 int(10) default NULL, build14 int(10) default NULL, build15 int(10) default NULL, build16 int(10) default NULL, build17 int(10) default NULL, build18 int(10) default NULL, build19 int(10) default NULL, build20 int(10) default NULL) TYPE=MyISAM;');
echo("<font color=green>Создана таблица 'city'</font><br>");

mysql_query ('create table monsters (name text, race text, art text, level int(10) default NULL, health int(10) default NULL, power int(10) default NULL, protect int(10) default NULL) TYPE=MyISAM;');
echo("<font color=green>Создана таблица 'monsters'</font><br>");

mysql_query ('create table time (login text, lastexit int(10) default NULL, hp int(10) default NULL, combats int(10) default NULL) TYPE=MyISAM;');
echo("<font color=green>Создана таблица 'time'</font><br>");

mysql_query ('create table buildings (race text, build1 text, build2 text, build3 text, build4 text, build5 text, build6 text, build7 text, build8 text, build9 text, build10 text, build11 text, build12 text, build13 text, build14 text, build15 text, build16 text, build17 text, build18 text, build19 text, build20 text) TYPE=MyISAM;');
echo("<font color=green>Создана таблица 'buildings'</font><br>");

mysql_query ('create table army (login text, level1 text, level2 text, level3 text, level4 text) TYPE=MyISAM;');
echo("<font color=green>Создана таблица 'army'</font><br>");

mysql_query ('create table allitems (num int(10) default NULL, name text, action int(10) default NULL, effect int(10) default NULL, img text, cena int(10) default NULL, type int(10) default NULL) TYPE=MyISAM;');
echo("<font color=green>Создана таблица 'allitems'</font><br>");

mysql_query ('create table allcasts (num int(10) default NULL, name text, type int(10) default NULL, action int(10) default NULL, effect int(10) default NULL, img text, cena int(10) default NULL) TYPE=MyISAM;');
echo("<font color=green>Создана таблица 'allcasts'</font><br>");

mysql_query ('create table settings (admin text, f1 text, f2 text, f3 text, f4 text, f5 text) TYPE=MyISAM;');
echo("<font color=green>Создана таблица 'settings'</font><br>");

mysql_query ('create table ip (login text, ip text) TYPE=MyISAM;');
echo("<font color=green>Создана таблица 'ip'</font><br>");

mysql_query ('create table battles (login text, opponent text, health int(10) default NULL, battle int(10) default NULL) TYPE=MyISAM;');
echo("<font color=green>Создана таблица 'battles'</font><br>");

mysql_query ('create table help (login text, gold text, metal text, rock text, wood text) TYPE=MyISAM;');
echo("<font color=green>Создана таблица 'help'</font><br>");

mysql_query ('create table temp (login text, param text, value text) TYPE=MyISAM;');
echo("<font color=green>Создана таблица 'temp'</font><br>");

mysql_query ('create table lostpass (login text, question text, answer text) TYPE=MyISAM;');
echo("<font color=green>Создана таблица 'lostpass'</font><br>");

mysql_query ('create table unions (login text, login2 text, login3 text, login4 text, login5 text) TYPE=MyISAM;');
echo("<font color=green>Создана таблица 'unions'</font><br>");

mysql_query ('create table additional (num text, name text, effect text, level1 text, level2 text, level3 text, img text, desc1 text, desc2 text, desc3 text) TYPE=MyISAM;');
echo("<font color=green>Создана таблица 'additional'</font><br>");

mysql_query ('create table events (num text, name text, effect text, how text) TYPE=MyISAM;');
echo("<font color=green>Создана таблица 'events'</font><br>");

mysql_query ('create table coords (login text, rx text, ry text, x text, y text) TYPE=MyISAM;');
echo("<font color=green>Создана таблица 'coords'</font><br>");

mysql_query ('create table capital (login text, rx text, ry text, x text, y text) TYPE=MyISAM;');
echo("<font color=green>Создана таблица 'capital'</font><br>");

mysql_query ('create table clans (name text, login text, description text, link text, logo text, gerb text, nalog text, bill text, super1 text, super2 text, super3 text) TYPE=MyISAM;');
echo("<font color=green>Создана таблица 'clans'</font><br>");

mysql_query ('create table inclan (login text, clan text, bill text, status text) TYPE=MyISAM;');
echo("<font color=green>Создана таблица 'inclan'</font><br>");

mysql_query ('create table forum_categories(category text, folder text, moderator text)TYPE=MyIsam;');
echo("<font color=green>Создана таблица 'forum_categories'</font><br>");

mysql_query ('create table forum_forums(forum text, folder text, category text)TYPE=MyIsam;');
echo("<font color=green>Создана таблица 'forum_forums'</font><br>");

mysql_query ('create table forum_subjects(subject text, folder text, forum text, category text, closed text, author text, hasnew text)TYPE=MyIsam;');
echo("<font color=green>Создана таблица 'forum_subjects'</font><br>");

mysql_query ('create table map (rx text, ry text, name text, zone text) TYPE=MyISAM;');
echo("<font color=green>Создана таблица 'map'</font><br>");

mysql_query ('create table money (login text, money text) TYPE=MyISAM;');
echo("<font color=green>Создана таблица 'money'</font><br>");

mysql_query ('create table battle (login text, battle text, health text, opponent text, turn text, attack text, data text, value text, info text, timeout text) TYPE=MyISAM;');
echo("<font color=green>Создана таблица 'battle'</font><br>");

mysql_query ('create table bottles (login text, hmini text, hmedi text, hmaxi text, mmini text, mmedi text, mmaxi text, pmini text, pmedi text, pmaxi text, smini text, smedi text, smaxi text, amini text, amedi text, amaxi text) TYPE=MyISAM;');
echo("<font color=green>Создана таблица 'bottles'</font><br>");

mysql_query ('create table newchar (login text, achar1 text, achar2 text, achar3 text, achar4 text, achar5 text, achar6 text, achar7 text, achar8 text, achar9 text, achar10 text, achar11 text, achar12 text, achar13 text, achar14 text, achar15 text, achar16 text) TYPE=MyISAM;');
echo("<font color=green>Создана таблица 'newchar'</font><br>");

//Статистика
mysql_query ('create table stat_ip (id_ip int(32) NOT NULL auto_increment, ip text, putdate datetime default NULL, id_page int(10) default NULL, browser int(4) default NULL, system int(4) default NULL, search int(4) default NULL, PRIMARY KEY (id_ip)) TYPE=MyISAM;');
echo("<font color=green>Создана таблица 'stat_ip'</font><br>");

mysql_query ('create table stat_refferer (id_refferer int(16) NOT NULL auto_increment, name text, putdate datetime default NULL, ip text, id_page int(8) default NULL, PRIMARY KEY (id_refferer)) TYPE=MyISAM;');
echo("<font color=green>Создана таблица 'stat_refferer'</font><br>");

mysql_query ('create table stat_pages (id_page int(10) NOT NULL auto_increment, name text, id_site int(4) default NULL, PRIMARY KEY  (id_page)) TYPE=MyISAM;');
echo("<font color=green>Создана таблица 'stat_pages'</font><br>");

mysql_query ('create table stat_links (id_links int(8) NOT NULL auto_increment, name text, PRIMARY KEY (id_links)) TYPE=MyISAM;');
echo("<font color=green>Создана таблица 'stat_links'</font><br>");

mysql_query ('create table warriors (name text, health text, power text, protect text, archery text, arrows text, img text, race text, level text, addon text) TYPE=MyISAM;');
echo("<font color=green>Создана таблица 'warriors'</font><br>");

mysql_query ('create table random (monster text, level text, id text, x text, y text, rx text, ry text, hand text, armor text) TYPE=MyISAM;');
echo("<font color=green>Создана таблица 'random'</font><br>");

mysql_query ('create table inventory (login text, inv1 text, inv2 text, inv3 text, inv4 text, inv5 text, inv6 text, inv7 text, inv8 text, inv9 text, inv10 text, inv11 text, inv12 text, inv13 text, inv14 text, inv15 text, inv16 text) TYPE=MyISAM;');
echo("<font color=green>Создана таблица 'inventory'</font><br>");

mysql_query ('create table quests (num text, name text, author text, time text, man text, complete text, description text, price text, data text) TYPE=MyISAM;');
echo("<font color=green>Создана таблица 'quests'</font><br>");

//Готово
echo("<font color='green'>Процесс создания таблиц завершён</font><br>");

//Заносим инфу о воинах
//         Название         Уров Раса      Сила Защита Стрельба Стрелы Здор Картин Дополн
//Люди
addwarrior('Пикенёры',      1,   'people', 2,   1,     0,       0,     5,   'p_1', 'пикенёров');      //Full = 3
addwarrior('Лучники',       2,   'people', 1,   2,     3,       10,    10,  'p_2', 'лучников');       //Full = 6
addwarrior('Монахи',        3,   'people', 1,   2,     6,       20,    25,  'p_3', 'монахов');        //Full = 9
addwarrior('Рыцари',        4,   'people', 8,   4,     0,       0,     33,  'p_4', 'рыцарей');        //Full = 12
//Эльфы
addwarrior('Феи',           1,   'elf',    1,   2,     0,       0,     4,   'e_1', 'фей');            //Full = 3
addwarrior('Стрелки',       2,   'elf',    1,   1,     5,       14,    9,   'e_2', 'стрелков');       //Full = 6
addwarrior('Снайперы',      3,   'elf',    1,   1,     7,       20,    26,  'e_3', 'снайперов');      //Full = 9
addwarrior('Великие эльфы', 4,   'elf',    1,   1,     10,      25,    28,  'e_4', 'великих эльфов'); //Full = 12
//Гномы
addwarrior('Големы',        1,   'hnom',   1,   2,     0,       0,     6,   'h_1', 'големов');        //Full = 3
addwarrior('Медузы',        2,   'hnom',   2,   1,     3,       12,    13,  'h_2', 'медуз');          //Full = 6
addwarrior('Минотавры',     3,   'hnom',   1,   1,     0,       0,     27,  'h_3', 'минотавров');     //Full = 9
addwarrior('Мастера гномы', 4,   'hnom',   9,   3,     0,       0,     32,  'h_4', 'мастер гномов');  //Full = 12
//Друиды
addwarrior('Ледяные големы',1,   'druid',  2,   2,     0,       0,     3,   'd_1', 'ледяных големов');//Full = 3
addwarrior('Маги',          2,   'druid',  1,   1,     4,       12,    12,  'd_2', 'магов');          //Full = 6
addwarrior('Джины',         3,   'druid',  6,   3,     0,       0,     28,  'd_3', 'джинов');         //Full = 9
addwarrior('Титаны',        4,   'druid',  1,   2,     9,       25,    31,  'd_4', 'титанов');        //Full = 12
//Нежить
addwarrior('Скелеты',       1,   'necro',  1,   2,     0,       0,     6,   'n_1', 'скелетов');       //Full = 3
addwarrior('Мертвецы',      2,   'necro',  4,   2,     0,       0,     11,  'n_2', 'мертвецов');      //Full = 6
addwarrior('Вампиры',       3,   'necro',  7,   3,     0,       0,     25,  'n_3', 'вампиров');       //Full = 9
addwarrior('Чёрные рыцарм', 4,   'necro',  10,  2,     0,       0,     35,  'n_4', 'чёрных рыцарей'); //Full = 12
//Адские создания
addwarrior('Бесы',          1,   'hell',   1,   2,     0,       0,     5,   'a_1', 'бесов');          //Full = 3
addwarrior('Орки',          2,   'hell',   1,   1,     3,       25,    13,  'a_2', 'орков');          //Full = 6
addwarrior('Черти',         3,   'hell',   8,   1,     0,       0,     26,  'a_3', 'чертей');         //Full = 9
addwarrior('Дьяволы',       4,   'hell',   8,   5,     0,       0,     30,  'a_4', 'дьяволов');       //Full = 12


//Заносим инфу о названиях регионов карты
//Координаты; Название; Тип (0 - земля)
addzone(1,  1, "Северный океан", 0);
addzone(2,  1, "Северный океан", 0);
addzone(3,  1, "Северный океан", 0);
addzone(4,  1, "Озеро свежести", 0);
addzone(5,  1, "Северный океан", 0);
addzone(6,  1, "Северный океан", 0);
addzone(7,  1, "Северный океан", 0);
addzone(8,  1, "Северный океан", 0);
addzone(9,  1, "Северный океан", 0);
addzone(10, 1, "Северное перелесье", 0);
addzone(11, 1, "Северное перелесье", 0);
addzone(12, 1, "Северное перелесье", 0);
addzone(13, 1, "Северная отмель", 0);
addzone(14, 1, "Северное перелесье", 0);
addzone(15, 1, "Огненные земли", 0);
addzone(16, 1, "Огненные земли", 0);
addzone(17, 1, "Огненные земли", 0);
addzone(18, 1, "Огненные земли", 0);
addzone(19, 1, "Северный океан", 0);
addzone(20, 1, "Северный океан", 0);

addzone(1,  2, "Северные леса", 0);
addzone(2,  2, "Холодная река", 0);
addzone(3,  2, "Западное озеро", 0);
addzone(4,  2, "Западное озеро", 0);
addzone(5,  2, "Западный исток", 0);
addzone(6,  2, "Западный исток", 0);
addzone(7,  2, "Северный океан", 0);
addzone(8,  2, "Северный океан", 0);
addzone(9,  2, "Холодные луга", 0);
addzone(10, 2, "Холодные луга", 0);
addzone(11, 2, "Западный приток", 0);
addzone(12, 2, "Западный приток", 0);
addzone(13, 2, "Северное перелесье", 0);
addzone(14, 2, "Северо-восточные луга", 0);
addzone(15, 2, "Огненные земли", 0);
addzone(16, 2, "Река Рубикон", 0);
addzone(17, 2, "Огненные земли", 0);
addzone(18, 2, "Огненные земли", 0);
addzone(19, 2, "Огненные земли", 0);
addzone(20, 2, "Огненные земли", 0);

addzone(1,  3, "Северные леса", 0);
addzone(2,  3, "Западный исток", 0);
addzone(3,  3, "Западное озеро", 0);
addzone(4,  3, "Западный исток", 0);
addzone(5,  3, "Западный исток", 0);
addzone(6,  3, "Северный остров", 0);
addzone(7,  3, "Северный остров", 0);
addzone(8,  3, "Западный приток", 0);
addzone(9,  3, "Западный приток", 0);
addzone(10, 3, "Западный приток", 0);
addzone(11, 3, "Западный приток", 0);
addzone(12, 3, "Свежие луга", 0);
addzone(13, 3, "Река Рубикон", 0);
addzone(14, 3, "Река Рубикон", 0);
addzone(15, 3, "Река Рубикон", 0);
addzone(16, 3, "Река Рубикон", 0);
addzone(17, 3, "Огненные земли", 0);
addzone(18, 3, "Огненные земли", 0);
addzone(19, 3, "Кипящее озеро", 0);
addzone(20, 3, "Огненные земли", 0);


addzone(1,  4, "Леса северо-запада", 0);
addzone(2,  4, "Леса северо-запада", 0);
addzone(3,  4, "Западный исток", 0);
addzone(4,  4, "Западный исток", 0);
addzone(5,  4, "Травяные холмы", 0);
addzone(6,  4, "Каменный карьер", 0);
addzone(7,  4, "Редколесье", 0);
addzone(8,  4, "Редколесье", 0);
addzone(9,  4, "Редколесье", 0);
addzone(10, 4, "Огненный остров", 0);
addzone(11, 4, "Река Рубикон", 0);
addzone(12, 4, "Река Рубикон", 0);
addzone(13, 4, "Река Рубикон", 0);
addzone(14, 4, "Северо-восточные пустоши", 0);
addzone(15, 4, "Северо-восточные пустоши", 0);
addzone(16, 4, "Огненные земли", 0);
addzone(17, 4, "Огненные земли", 0);
addzone(18, 4, "Огненные земли", 0);
addzone(19, 4, "Восточный рукав", 0);
addzone(20, 4, "Восточный остров", 0);

addzone(1,  5, "Западная аномалия", 0);
addzone(2,  5, "Западная аномалия", 0);
addzone(3,  5, "Западная пустыня", 0);
addzone(4,  5, "Западная пустыня", 0);
addzone(5,  5, "Травяные холмы", 0);
addzone(6,  5, "Поля северо-запада", 0);
addzone(7,  5, "Поля северо-запада", 0);
addzone(8,  5, "Поля северо-запада", 0);
addzone(9,  5, "Поля северо-запада", 0);
addzone(10, 5, "Река Рубикон", 0);
addzone(11, 5, "Огненный остров", 0);
addzone(12, 5, "Восточные поля", 0);
addzone(13, 5, "Огненное озеро", 0);
addzone(14, 5, "Восточные поля", 0);
addzone(15, 5, "Восточные леса", 0);
addzone(16, 5, "Восточные леса", 0);
addzone(17, 5, "Восточные леса", 0);
addzone(18, 5, "Дальневосточные леса", 0);
addzone(19, 5, "Восточный рукав", 0);
addzone(20, 5, "Восточный остров", 0);

addzone(1,  6, "Западная аномалия", 0);
addzone(2,  6, "Западная аномалия", 0);
addzone(3,  6, "Западная пустыня", 0);
addzone(4,  6, "Западная пустыня", 0);
addzone(5,  6, "Центральные низменности", 0);
addzone(6,  6, "Центральные низменности", 0);
addzone(7,  6, "Центральные леса", 0);
addzone(8,  6, "Центральные низменности", 0);
addzone(9,  6, "Центральные низменности", 0);
addzone(10, 6, "Река Рубикон", 0);
addzone(11, 6, "Восточные поля", 0);
addzone(12, 6, "Восточные поля", 0);
addzone(13, 6, "Восточные поля", 0);
addzone(14, 6, "Восточные поля", 0);
addzone(15, 6, "Восточные леса", 0);
addzone(16, 6, "Восточные леса", 0);
addzone(17, 6, "Восточные леса", 0);
addzone(18, 6, "Озеро счастья", 0);
addzone(19, 6, "Дальневосточные леса", 0);
addzone(20, 6, "Восточный рукав", 0);

addzone(1,  7, "Западная аномалия", 0);
addzone(2,  7, "Поля свежести", 0);
addzone(3,  7, "Поля свежести", 0);
addzone(4,  7, "Исток Рубикона", 0);
addzone(5,  7, "Центральные леса", 0);
addzone(6,  7, "Центральные леса", 0);
addzone(7,  7, "Центральные леса", 0);
addzone(8,  7, "Центральные леса", 0);
addzone(9,  7, "Река Рубикон", 0);
addzone(10, 7, "Центральные низменности", 0);
addzone(11, 7, "Восточные поля", 0);
addzone(12, 7, "Восточные поля", 0);
addzone(13, 7, "Восточные поля", 0);
addzone(14, 7, "Восточные поля", 0);
addzone(15, 7, "Восточные леса", 0);
addzone(16, 7, "Восточные леса", 0);
addzone(17, 7, "Восточные леса", 0);
addzone(18, 7, "Дальневосточные леса", 0);
addzone(19, 7, "Дальневосточные леса", 0);
addzone(20, 7, "Дальневосточные леса", 0);

addzone(1,  8, "Бурлящая речка", 0);
addzone(2,  8, "Бурлящая речка", 0);
addzone(3,  8, "Поля свежести", 0);
addzone(4,  8, "Река Рубикон", 0);
addzone(5,  8, "Река Рубикон", 0);
addzone(6,  8, "Центральные леса", 0);
addzone(7,  8, "Центральные леса", 0);
addzone(8,  8, "Центральные леса", 0);
addzone(9,  8, "Заводь Рубикона", 0);
addzone(10, 8, "Заводь Рубикона", 0);
addzone(11, 8, "Глубокое озеро", 0);
addzone(12, 8, "Глубокое озеро", 0);
addzone(13, 8, "Эльфийские леса", 0);
addzone(14, 8, "Эльфийские леса", 0);
addzone(15, 8, "Восточные леса", 0);
addzone(16, 8, "Восточные леса", 0);
addzone(17, 8, "Солёное озеро", 0);
addzone(18, 8, "Солёное озеро", 0);
addzone(19, 8, "Дальневосточные леса", 0);
addzone(20, 8, "Дальневосточные леса", 0);

addzone(1,  9, "Западные лабиринты", 0);
addzone(2,  9, "Бурлящая речка", 0);
addzone(3,  9, "Большой западный рукав", 0);
addzone(4,  9, "Большой западный рукав", 0);
addzone(5,  9, "Река Рубикон", 0);
addzone(6,  9, "Река Рубикон", 0);
addzone(7,  9, "Река Рубикон", 0);
addzone(8,  9, "Река Рубикон", 0);
addzone(9,  9, "Заводь Рубикона", 0);
addzone(10, 9, "Заводь Рубикона", 0);
addzone(11, 9, "Глубокое озеро", 0);
addzone(12, 9, "Глубокое озеро", 0);
addzone(13, 9, "Эльфийские леса", 0);
addzone(14, 9, "Эльфийские леса", 0);
addzone(15, 9, "Река Вента", 0);
addzone(16, 9, "Восточные леса", 0);
addzone(17, 9, "Восточные леса", 0);
addzone(18, 9, "Дальневосточные леса", 0);
addzone(19, 9, "Большой лесной ручей", 0);
addzone(20, 9, "Большой лесной ручей", 0);

addzone(1,  10, "Западные болота", 0);
addzone(2,  10, "Западные болота", 0);
addzone(3,  10, "Снежные равнины", 0);
addzone(4,  10, "Снежные равнины", 0);
addzone(5,  10, "Снежные равнины", 0);
addzone(6,  10, "Центральные низменности", 0);
addzone(7,  10, "Центральные низменности", 0);
addzone(8,  10, "Лабиринт ужаса", 0);
addzone(9,  10, "Лабиринт ужаса", 0);
addzone(10, 10, "Эльфийские леса", 0);
addzone(11, 10, "Эльфийские леса", 0);
addzone(12, 10, "Эльфийские леса", 0);
addzone(13, 10, "Эльфийские леса", 0);
addzone(14, 10, "Река Вента", 0);
addzone(15, 10, "Восточные леса", 0);
addzone(16, 10, "Восточные леса", 0);
addzone(17, 10, "Восточные леса", 0);
addzone(18, 10, "Дальневосточные леса", 0);
addzone(19, 10, "Большой лесной ручей", 0);
addzone(20, 10, "Остров Тихона", 0);

addzone(1,  11, "Западные болота", 0);
addzone(2,  11, "Западные болота", 0);
addzone(3,  11, "Западные болота", 0);
addzone(4,  11, "Снежные равнины", 0);
addzone(5,  11, "Снежные равнины", 0);
addzone(6,  11, "Центральные низменности", 0);
addzone(7,  11, "Синее озеро", 0);
addzone(8,  11, "Синее озеро", 0);
addzone(9,  11, "Лабиринт ужаса", 0);
addzone(10, 11, "Эльфийские леса", 0);
addzone(11, 11, "Эльфийские леса", 0);
addzone(12, 11, "Река Вента", 0);
addzone(13, 11, "Река Вента", 0);
addzone(14, 11, "Восточные леса", 0);
addzone(15, 11, "Восточные леса", 0);
addzone(16, 11, "Восточные леса", 0);
addzone(17, 11, "Восточные леса", 0);
addzone(18, 11, "Дальневосточные леса", 0);
addzone(19, 11, "Большой лесной ручей", 0);
addzone(20, 11, "Большой лесной ручей", 0);

addzone(1,  12, "Река Лика", 0);
addzone(2,  12, "Западные болота", 0);
addzone(3,  12, "Западные низменности", 0);
addzone(4,  12, "Западные низменности", 0);
addzone(5,  12, "Западные низменности", 0);
addzone(6,  12, "Синее озеро", 0);
addzone(7,  12, "Синее озеро", 0);
addzone(8,  12, "Синее озеро", 0);
addzone(9,  12, "Центральные долины", 0);
addzone(10, 12, "Эльфийские леса", 0);
addzone(11, 12, "Река Вента", 0);
addzone(12, 12, "Река Вента", 0);
addzone(13, 12, "Восточные леса", 0);
addzone(14, 12, "Восточные леса", 0);
addzone(15, 12, "Восточные леса", 0);
addzone(16, 12, "Восточные леса", 0);
addzone(17, 12, "Восточные леса", 0);
addzone(18, 12, "Дальневосточные леса", 0);
addzone(19, 12, "Дальневосточные леса", 0);
addzone(20, 12, "Дальневосточные леса", 0);

addzone(1,  13, "Река Лика", 0);
addzone(2,  13, "Река Лика", 0);
addzone(3,  13, "Река Лика", 0);
addzone(4,  13, "Река Лика", 0);
addzone(5,  13, "Ручей жизни", 0);
addzone(6,  13, "Ручей жизни", 0);
addzone(7,  13, "Река жизни", 0);
addzone(8,  13, "Река жизни", 0);
addzone(9,  13, "Центральные долины", 0);
addzone(10, 13, "Центральные долины", 0);
addzone(11, 13, "Река Вента", 0);
addzone(12, 13, "Восточные леса", 0);
addzone(13, 13, "Восточные леса", 0);
addzone(14, 13, "Восточные леса", 0);
addzone(15, 13, "Восточные леса", 0);
addzone(16, 13, "Восточные леса", 0);
addzone(17, 13, "Восточные леса", 0);
addzone(18, 13, "Дальневосточные леса", 0);
addzone(19, 13, "Дальневосточные леса", 0);
addzone(20, 13, "Дальневосточные леса", 0);

addzone(1,  14, "Болотная речка", 0);
addzone(2,  14, "Западные равнины", 0);
addzone(3,  14, "Западные равнины", 0);
addzone(4,  14, "Река Лика", 0);
addzone(5,  14, "Река Лика", 0);
addzone(6,  14, "Река жизни", 0);
addzone(7,  14, "Юго-западные перелесья", 0);
addzone(8,  14, "Переток", 0);
addzone(9,  14, "Эльфийские леса", 0);
addzone(10, 14, "Река Вента", 0);
addzone(11, 14, "Река Вента", 0);
addzone(12, 14, "Восточные леса", 0);
addzone(13, 14, "Восточные леса", 0);
addzone(14, 14, "Озеро удачи", 0);
addzone(15, 14, "Озеро удачи", 0);
addzone(16, 14, "Восточные леса", 0);
addzone(17, 14, "Восточные леса", 0);
addzone(18, 14, "Тёплая река", 0);
addzone(19, 14, "Дальневосточные леса", 0);
addzone(20, 14, "Дальневосточные леса", 0);

addzone(1,  15, "Болотная речка", 0);
addzone(2,  15, "Болотная речка", 0);
addzone(3,  15, "Западные равнины", 0);
addzone(4,  15, "Река Лика", 0);
addzone(5,  15, "Западные равнины", 0);
addzone(6,  15, "Источник жизни", 0);
addzone(7,  15, "Южный песочный крест", 0);
addzone(8,  15, "Переток", 0);
addzone(9,  15, "Переток", 0);
addzone(10, 15, "Река Вента", 0);
addzone(11, 15, "Восточные леса", 0);
addzone(12, 15, "Восточные леса", 0);
addzone(13, 15, "Восточные леса", 0);
addzone(14, 15, "Озеро удачи", 0);
addzone(15, 15, "Озеро удачи", 0);
addzone(16, 15, "Место бессмертия", 0);
addzone(17, 15, "Восточные леса", 0);
addzone(18, 15, "Тёплая река", 0);
addzone(19, 15, "Дальневосточные леса", 0);
addzone(20, 15, "Восточные снежные равнины", 0);

addzone(1,  16, "Южный ручей", 0);
addzone(2,  16, "Южный ручей", 0);
addzone(3,  16, "Река Лика", 0);
addzone(4,  16, "Река Лика", 0);
addzone(5,  16, "Юго-западные равнины", 0);
addzone(6,  16, "Юго-западные равнины", 0);
addzone(7,  16, "Южные равнины", 0);
addzone(8,  16, "Южные равнины", 0);
addzone(9,  16, "Переток", 0);
addzone(10, 16, "Южный островок", 0);
addzone(11, 16, "Юго-восточные леса", 0);
addzone(12, 16, "Юго-восточные леса", 0);
addzone(13, 16, "Восточные леса", 0);
addzone(14, 16, "Восточные леса", 0);
addzone(15, 16, "Восточные леса", 0);
addzone(16, 16, "Восточные леса", 0);
addzone(17, 16, "Восточные леса", 0);
addzone(18, 16, "Переправа", 0);
addzone(19, 16, "Снежные равнины", 0);
addzone(20, 16, "Восточные снежные равнины", 0);

addzone(1,  17, "Река Лика", 0);
addzone(2,  17, "Река Лика", 0);
addzone(3,  17, "Река Лика", 0);
addzone(4,  17, "Приток Лики", 0);
addzone(5,  17, "Приток Лики", 0);
addzone(6,  17, "Южные равнины", 0);
addzone(7,  17, "Южные равнины", 0);
addzone(8,  17, "Южные равнины", 0);
addzone(9,  17, "Южные равнины", 0);
addzone(10, 17, "Река Вента", 0);
addzone(11, 17, "Река Вента", 0);
addzone(12, 17, "Юго-восточные леса", 0);
addzone(13, 17, "Граница холода", 0);
addzone(14, 17, "Граница холода", 0);
addzone(15, 17, "Восточные леса", 0);
addzone(16, 17, "Восточные леса", 0);
addzone(17, 17, "Восточные леса", 0);
addzone(18, 17, "Тёплая река", 0);
addzone(19, 17, "Снежные равнины", 0);
addzone(20, 17, "Юго-восточные снега", 0);

addzone(1,  18, "Река Лика", 0);
addzone(2,  18, "Южная возвышенность", 0);
addzone(3,  18, "Граница холода", 0);
addzone(4,  18, "Граница холода", 0);
addzone(5,  18, "Граница холода", 0);
addzone(6,  18, "Граница холода", 0);
addzone(7,  18, "Граница холода", 0);
addzone(8,  18, "Граница холода", 0);
addzone(9,  18, "Граница холода", 0);
addzone(10, 18, "Граница холода", 0);
addzone(11, 18, "Река Вента", 0);
addzone(12, 18, "Южные снега", 0);
addzone(13, 18, "Южные снега", 0);
addzone(14, 18, "Южные снега", 0);
addzone(15, 18, "Граница холода", 0);
addzone(16, 18, "Замерзшие реки", 0);
addzone(17, 18, "Восточные леса", 0);
addzone(18, 18, "Тёплая река", 0);
addzone(19, 18, "Тёплая река", 0);
addzone(20, 18, "Южные снега", 0);

addzone(1,  19, "Река Лика", 0);
addzone(2,  19, "Южная возвышенность", 0);
addzone(3,  19, "Граница холода", 0);
addzone(4,  19, "Замерзшие леса", 0);
addzone(5,  19, "Замерзшее озеро", 0);
addzone(6,  19, "Замерзшие леса", 0);
addzone(7,  19, "Замерзшие реки", 0);
addzone(8,  19, "Замерзшие реки", 0);
addzone(9,  19, "Ледяной водоём", 0);
addzone(10, 19, "Замерзшие леса", 0);
addzone(11, 19, "Река Вента", 0);
addzone(12, 19, "Южные снега", 0);
addzone(13, 19, "Южные снега", 0);
addzone(14, 19, "Замерзшие реки", 0);
addzone(15, 19, "Замерзшие реки", 0);
addzone(16, 19, "Замерзшие реки", 0);
addzone(17, 19, "Замерзшие леса", 0);
addzone(18, 19, "Тёплый залив", 0);
addzone(19, 19, "Тёплый залив", 0);
addzone(20, 19, "Пролив тепла", 0);

addzone(1,  20, "Юго-западная река", 0);
addzone(2,  20, "Юго-западная река", 0);
addzone(3,  20, "Граница холода", 0);
addzone(4,  20, "Замерзшие реки", 0);
addzone(5,  20, "Замерзшие леса", 0);
addzone(6,  20, "Замерзшие леса", 0);
addzone(7,  20, "Исток замерзшего ручья", 0);
addzone(8,  20, "Замерзшие леса", 0);
addzone(9,  20, "Замерзшие леса", 0);
addzone(10, 20, "Устье Венты", 0);
addzone(11, 20, "Устье Венты", 0);
addzone(12, 20, "Замерзшие реки", 0);
addzone(13, 20, "Замерзшие реки", 0);
addzone(14, 20, "Полюс холода", 0);
addzone(15, 20, "Дельта южных рек", 0);
addzone(16, 20, "Замерзшие леса", 0);
addzone(17, 20, "Замерзшие реки", 0);
addzone(18, 20, "Тёплый залив", 0);
addzone(19, 20, "Тёплый залив", 0);
addzone(20, 20, "Южное море", 0);


// ДОБАВЛЯЕМ МОНСТРОВ
//          Название          Раса       КАРТ              УР ЗД  АТ ЗЩ
addmonstr('Фея',            'Свободный', 'sprite.jpg',     1, 65, 1, 1);
addmonstr('Волк',           'Свободный', '0',              1, 70, 2, 1);
addmonstr('Гоблин',         'Свободный', 'goblin.jpg',     1, 75, 2, 2);
addmonstr('Вождь гоблинов', 'Свободный', '0',              1, 80, 3, 2);
addmonstr('Гремлин',        'Свободный', '0',              1, 85, 3, 3);
addmonstr('Скелет',         'Свободный', 'skelet.jpg',     1, 90, 4, 3);
addmonstr('Бандит',         'Свободный', 'bandit.jpg',     1, 95, 4, 4);
addmonstr('Гнолл',          'Свободный', '0',              1, 100, 4, 4);
addmonstr('Хоббит',         'Свободный', '0',              2, 160, 5, 4);
addmonstr('Гаргулья',       'Свободный', '0',              2, 165, 5, 5);
addmonstr('Оса',            'Свободный', '0',              2, 170, 6, 5);
addmonstr('Волчий наездник','Свободный', '0',              2, 175, 6, 6);
addmonstr('Вор',            'Свободный', 'rob.jpg',        2, 180, 7, 6);
addmonstr('Гог',            'Свободный', 'gog.jpg',        2, 195, 7, 7);
addmonstr('Мертвец',        'Свободный', 'dead.jpg',       2, 190, 7, 8);
addmonstr('Каменный голем', 'Свободный', 'stoneholem.jpg', 3, 270, 8, 8);
addmonstr('Потерянная душа','Свободный', '0',              3, 280, 9, 8);
addmonstr('Рогатый демон',  'Свободный', 'daemon.jpg',     3, 280, 10, 9);
addmonstr('Ящер',           'Свободный', '0',              3, 270, 11, 9);
addmonstr('Элемент воздуха','Свободный', '0',              3, 290, 11, 10);
addmonstr('Огр',            'Свободный', '0',              3, 290, 12, 9);
addmonstr('Джин',           'Свободный', 'master.jpg',     3, 300, 12, 10);
addmonstr('Грифон',         'Свободный', '0',              3, 300, 12, 11);
addmonstr('Архимаг',        'Свободный', 'mag.jpg',        4, 350, 12, 12);
addmonstr('Вождь огров',    'Свободный', '0',              4, 355, 12, 12);
addmonstr('Дендроид',       'Свободный', '0',              4, 360, 12, 12);
addmonstr('Элемент огня',   'Свободный', '0',              4, 365, 12, 12);
addmonstr('Мастер дендроид','Свободный', '0',              4, 370, 12, 12);
addmonstr('Мастер джин',    'Свободный', 'wishmaster.jpg', 4, 380, 12, 12);
addmonstr('Насгул',         'Свободный', '0',              5, 390, 12, 13);
addmonstr('Нага',           'Свободный', '0',              5, 395, 13, 13);
addmonstr('Василиск',       'Свободный', '0',              5, 400, 13, 12);
addmonstr('Птица грома',    'Свободный', '0',              5, 420, 13, 13);
addmonstr('Эфрит',          'Свободный', 'efretei.jpg',    5, 440, 13, 13);
addmonstr('Лич',            'Свободный', '0',              5, 460, 13, 13);
addmonstr('Элемент земли',  'Свободный', '0',              6, 550, 14, 13);
addmonstr('Горгона',        'Свободный', '0',              6, 570, 15, 13);
addmonstr('Циклоп',         'Свободный', '0',              6, 590, 16, 13);
addmonstr('Мастер лич',     'Свободный', '0',              6, 560, 16, 14);
addmonstr('Громовержец',    'Свободный', '0',              6, 580, 16, 15);
addmonstr('Титан',          'Свободный', 'titan.jpg',      6, 600, 16, 16);
addmonstr('Железный голем', 'Свободный', '0',              7, 650, 17, 17);
addmonstr('Кентавр',        'Свободный', '0',              7, 650, 16, 17);
addmonstr('Виверн',         'Свободный', '0',              7, 660, 18, 18);
addmonstr('Элемент воды',   'Свободный', 'waterelem.jpg',  7, 670, 19, 19);
addmonstr('Красный дракон', 'Свободный', '0',              7, 680, 20, 18);
addmonstr('Древнее чудище', 'Свободный', '0',              7, 690, 20, 19);
addmonstr('Зелёный дракон', 'Свободный', '0',              8, 755, 21, 21);
addmonstr('Феникс',         'Свободный', '0',              8, 760, 22, 21);
addmonstr('Золотой дракон', 'Свободный', '0',              8, 770, 23, 21);
addmonstr('Падший ангел',   'Свободный', '0',              8, 780, 23, 22);
addmonstr('Рыцарь смерти',  'Свободный', 'deadknife.jpg',  8, 790, 23, 23);
addmonstr('Единорог',       'Свободный', '0',              8, 800, 25, 25);
addmonstr('Дух дракона',    'Свободный', '0',              9, 850, 30, 30);
addmonstr('Гном - убийца',  'Свободный', 'hnom.jpg',       9, 860, 32, 30);
addmonstr('Крестоносец',    'Свободный', 'krest.jpg',      9, 870, 34, 32);
addmonstr('Троль - убийца', 'Свободный', 'troll.jpg',      9, 880, 36, 34);
addmonstr('Чёрный дракон',  'Свободный', '0',              9, 890, 38, 36);
addmonstr('Нечто',          'Свободный', '0',              9, 900, 40, 40);
addmonstr('Голубой дракон', 'Свободный', '0',             10, 920, 45, 45);
addmonstr('Архидъявол',     'Свободный', 'devil.jpg',     10, 940, 48, 48);
addmonstr('Эльф - снайпер', 'Свободный', 'greatelf.jpg',  10, 960, 52, 52);
addmonstr('Лорд вампиров',  'Свободный', 'vampire.jpg',   10, 980, 54, 54);
addmonstr('Архититан',      'Свободный', '0',             10, 1000, 60, 60);
echo("<font color='green'>Данные в таблицу monsters занесены</font><br>");

//ДОБАВЛЯЕМ ДОПОЛНИТЕЛЬНЫЕ ВОЗМОЖНОСТИ (Формат в таблице NewChar: N4 или E8)
//         Номер   Название          Действ   Нович Продв Эксп  Картинка
//Следопыт
addability('1',    'Следопыт',       '1',     '3',  '7',  '11', 'axelerate', 'Увеличивает продолжительность перемещения на 3 единицы', 'Увеличивает продолжительность перемещения на 7 единицы', 'Увеличивает продолжительность перемещения на 11 единиц');
//Ловкость
addability('2',    'Ловкость',       '2',     '15', '25', '40', 'dexterity', 'Даёт 15% шанс увернуться от атаки', 'Даёт 25% шанс увернуться от атаки', 'Даёт 40% шанс увернуться от атаки'); 
//Тактика
addability('3',    'Тактика',        '3',     '1',  '2',  '3',  'tactic', 'Увеличивает количество очков действия в бою на 1', 'Увеличивает количество очков действия в бою на 2', 'Увеличивает количество очков действия в бою на 3');
//Бой
addability('4',    'Бой',            '4',     '10', '15', '20', 'battle', 'Увеличивает наносимые повреждения на 10%', 'Увеличивает наносимые повреждения на 15%', 'Увеличивает наносимые повреждения на 20%'); 
//Метаболизм
addability('5',    'Метаболизм',     '5',     '1', '3', '5', 'metabalism', 'Ускоряет восстановление здоровья персонажа. Здоровье восстанавливается на 1% каждый ход в бою', 'Ускоряет восстановление здоровья персонажа. Здоровье восстанавливается на 3% каждый ход в бою', 'Ускоряет восстановление здоровья персонажа. Здоровье восстанавливается на 5% каждый ход в бою'); 
//Экономист
addability('6',    'Экономист',      '6',     '5',  '10', '15', 'economist', 'Увеличивает приносимый в казну доход на 5%', 'Увеличивает приносимый в казну доход на 10%', 'Увеличивает приносимый в казну доход на 15%'); 
//Восстановление маны
addability('7',    'Восстановление', '7',     '1', '3', '5', 'metamagic', 'Ускоряет восстановление маны персонажа. Мана восстанавливается на 1% каждый ход в бою', 'Ускоряет восстановление маны персонажа. Мана восстанавливается на 3% каждый ход в бою', 'Ускоряет восстановление маны персонажа. Мана восстанавливается на 5% каждый ход в бою'); 
//Добыча металла
addability('8',    'Добыча металла', '8',     '1',  '2',  '3',  'metal', 'Приносит дополнительую единицу металла', 'Приносит две дополнительые единицы металла', 'Приносит три дополнительые единицы металла'); 
//Добыча камня
addability('9',    'Добыча камня',   '9',     '1',  '2',  '3',  'rock', 'Приносит дополнительую единицу камня', 'Приносит две дополнительые единицы камня', 'Приносит три дополнительые единицы камня'); 
//Добыча дерева
addability('10',   'Добыча дерева', '10',    '1',  '2',  '3',  'wood', 'Приносит дополнительую единицу дерева', 'Приносит две дополнительые единицы дерева', 'Приносит три дополнительые единицы дерева'); 
//Вор
addability('11',   'Вор',            '11',    '20', '40', '60',  'rob', 'Позволяет после выигрыша в битве украсть у противника его наличные деньги с вероятностью 20%', 'Позволяет после выигрыша в битве украсть у противника его наличные деньги с вероятностью 40%', 'Позволяет после выигрыша в битве украсть у противника его наличные деньги с вероятностью 60%'); 
//Ворожба
addability('12',   'Ворожба',        '12',    '10', '15', '20', 'wizardy', 'Увеличивает наносимые магией повреждения на 10%', 'Увеличивает наносимые магией повреждения на 15%', 'Увеличивает наносимые магией повреждения на 20%'); 
//Антимагия
addability('13',   'Антимагия',      '13',    '15', '20', '40', 'antimagic', 'Даёт 15% шанс избежать магической атаки', 'Даёт 20% шанс избежать магической атаки', 'Даёт 40% шанс избежать магической атаки'); 
//Магическое оружие
addability('14',   'Магическое оружие', '14', '5',  '10', '15', 'magicweapon', 'Делает Ваше оружие магическим и помимо обычной атаки, Ваше оружие будет атаковать магически, с усилением в 5%', 'Делает Ваше оружие магическим и помимо обычной атаки, Ваше оружие будет атаковать магически, с усилением в 10%', 'Делает Ваше оружие магическим и помимо обычной атаки, Ваше оружие будет атаковать магически, с усилением в 15%'); 
//Обессиливающее оружие
addability('15',   'Обессиливающее оружие', '15', '5', '7', '9',  'powerweapon', 'Делает Ваше оружие магическим и помимо обычной атаки, Ваше оружие будет уменьшать ману соперника на 5%', 'Делает Ваше оружие магическим и помимо обычной атаки, Ваше оружие будет уменьшать ману соперника на 7%', 'Делает Ваше оружие магическим и помимо обычной атаки, Ваше оружие будет уменьшать ману соперника на 9%'); 
//Боевая магия
addability('16',   'Боевая магия',   '16',  '10', '15', '20', 'combatmagic', 'Увеличивает повреждения, наносимые боевой магией на 10%', 'Увеличивает повреждения, наносимые боевой магией на 15%', 'Увеличивает повреждения, наносимые боевой магией на 20%'); 
//Магия природы
addability('17',   'Магия природы',  '17',  '10', '15', '20', 'naturemagic', 'Увеличивает повреждения, наносимые магией природы на 10%', 'Увеличивает повреждения, наносимые магией природы на 15%', 'Увеличивает повреждения, наносимые магией природы на 20%'); 
//Магия разума
addability('18',   'Магия разума',  '18',  '10', '15', '20', 'mindmagic', 'Увеличивает повреждения, наносимые магией разума на 10%', 'Увеличивает повреждения, наносимые магией разума на 15%', 'Увеличивает повреждения, наносимые магией разума на 20%'); 
//СТРАТЕГИЧЕСКИЕ СПОСОБНОСТИ
//Стратегия
addability('19',   'Стратег',  '19',  '5', '8', '12', 'strategy', 'Увеличивает повреждения, наносимые всей Вашей армией на 5%', 'Увеличивает повреждения, наносимые всей Вашей армией на 8%', 'Увеличивает повреждения, наносимые всей Вашей армией на 12%'); 
//Оборона
addability('20',   'Оборона',  '20',  '5', '8', '12', 'defeat', 'Уменьшает повреждения, наносимые всей Вашей армии на 5%', 'Уменьшает повреждения, наносимые всей Вашей армии на 8%', 'Уменьшает повреждения, наносимые всей Вашей армии на 12%'); 
//Баллистика
addability('21',   'Баллистика',  '21',  '1', '2', '3', 'balistic', 'Перед осадой замка Вы предварительно обстреливаете его из осадных орудий и в стенах замка появляется один дополнительный проход для Ваших войск', 'Перед осадой замка Вы предварительно обстреливаете его из осадных орудий и в стенах замка появляется два дополнительных прохода для Ваших войск', 'Перед осадой замка Вы предварительно обстреливаете его из осадных орудий и в стенах замка появляется три дополнительных прохода для Ваших войск'); 
//Некромантия
addability('22',   'Некромантия',  '21',  '10', '20', '30', 'necromancy', 'Позволяет после битвы воскресить Вам 10% всех ваших солдат первого уровня', 'Позволяет после битвы воскресить Вам 20% всех ваших солдат первого уровня', 'Позволяет после битвы воскресить Вам 30% всех ваших солдат первого уровня'); 
//Прирост существ
addability('23',   'Прирост существ',  '23',  '25', '50', '100', 'morecreat', 'Увеличивает ежедневный прирост существ в замке на 25%', 'Увеличивает ежедневный прирост существ в замке на 50%', 'Увеличивает ежедневный прирост существ в замке на 100%'); 
//Ближний бой
addability('24',   'Ближний бой',  '24',  '10', '15', '20', 'fight', 'Увеличивает у всей армии навых ближнего боя на 10%', 'Увеличивает у всей армии навых ближнего боя на 15%', 'Увеличивает у всей армии навых ближнего боя на 20%'); 
//Стрельба
addability('25',   'Стрельба',  '25',  '10', '15', '20', 'arrow', 'Увеличивает у всей армии навых стрельбы на 10%', 'Увеличивает у всей армии навых стрельбы на 15%', 'Увеличивает у всей армии навых стрельбы на 20%'); 
//Удача
addability('26',   'Удача',  '26',  '5', '10', '15', 'luck', 'Даёт 5% шанс повторить атаку существу во время битвы', 'Даёт 10% шанс повторить атаку существу во время битвы', 'Даёт 15% шанс повторить атаку существу во время битвы'); 
//Медицина
addability('27',   'Медицина',  '27',  '20', '50', '100', 'medicine', 'Восстанавливает здоровье всех отрядов на 20% после каждого хода в битве', 'Восстанавливает здоровье всех отрядов на 50% после каждого хода в битве', 'Восстанавливает здоровье всех отрядов на 100% после каждого хода в битве'); 
//Экономия
addability('28',   'Экономия',  '28',  '5', '10', '15', 'economy', 'Уменьшает затраты на армию на 5%', 'Уменьшает затраты на армию на 10%', 'Уменьшает затраты на армию на 15%'); 
//Налогообложение
addability('29',   'Налогообложение',  '29',  '5', '10', '15', 'nalog', 'Увеличивает налоги для населения страны на 5%', 'Увеличивает налоги для населения страны на 10%', 'Увеличивает налоги для населения страны на 15%'); 
//Укрепления
addability('30',   'Укрепления',  '30',  '10', '15', '20', 'castle', 'Увеличивает повреждения, наносимые оборонительными башнями, во время осады Вашего замка, на 10%', 'Увеличивает повреждения, наносимые оборонительными башнями, во время осады Вашего замка, на 15%', 'Увеличивает повреждения, наносимые оборонительными башнями, во время осады Вашего замка, на 20%'); 
echo("<font color='green'>Данные в таблицу additional занесены</font><br>");



//ДОБАВЛЯЕМ ЗДАНИЯ ПО РАСАМ
//         Раса    Здания
$castle = array('Преффектура', 'Муниципалитет', 'Капитолий', 'Шахта', 'Рынок', 'Кузница', 'Храм', 'Гильдия магов I', 'Гильдия магов II', 'Банк', 'Разведка', 'Таверна', 'Казармы', '', '', '', '', '', '', '');
addcastle ('people', $castle);
$castle = array('Зал советов', 'Верховный зал советов', 'Капитолий', 'Карьер', 'Рынок', 'Кузница', 'Великий храм', 'Гильдия магов I', 'Гильдия магов II', 'Сокровищница', 'Разведка', 'Таверна', 'Лучная мастерская', '', '', '', '', '', '', '');
addcastle ('elf', $castle);
$castle = array('Сокровищница I уровня', 'Сокровищница II уровня', 'Сокровищница III уровня', 'Шахта', 'Рынок', 'Кузница', 'Святилище', 'Гильдия магов I', 'Гильдия магов II', 'Банк', 'Разведка', 'Таверна', 'Гномница', '', '', '', '', '', '', '');
addcastle ('hnom', $castle);
$castle = array('Община друидов', 'Совет друидов', 'Высший совет', 'Шахта', 'Рынок', 'Кузница', 'Стоунхендж', 'Гильдия магов I', 'Гильдия магов II', 'Библиотека', 'Разведка', 'Таверна', 'Башня магов', '', '', '', '', '', '', '');
addcastle ('druid', $castle);
$castle = array('Преффектура', 'Муниципалитет', 'Капитолий', 'Заброшенная шахта', 'Рынок', 'Кузница', 'Дом лекаря', 'Гильдия магов I', 'Гильдия магов II', 'Банк', 'Разведка', 'Таверна', 'Поместье вампиров', '', '', '', '', '', '', '');
addcastle ('necro', $castle);
$castle = array('Преффектура', 'Муниципалитет', 'Капитолий', 'Вулканическая шахта', 'Рынок', 'Кузница', 'Дом лекаря', 'Гильдия магов I', 'Гильдия магов II', 'Банк', 'Разведка', 'Таверна', 'Врата ада', '', '', '', '', '', '', '');
addcastle ('hell', $castle);
echo("<font color='green'>Данные в таблицу buildings занесены</font><br>");

//Все цены в единицах метала!!!!!!!!!!!!!!
//Типы:
//1 - голова; 2 - шея; 3 - броня; 4 - торс; 5 - палец; 6 - левая рука; 7 - правая рука; 8 - пусто; 9 - ноги; 10 - колени; 11 - плащ
//Заносим вещи
//      №  название       что делает    сколько    рисунок          цена   тип (голова, шея...))
additem(1,  "Нож",              1,         4,      "sknife.jpg",    5,     7);
additem(2,  "Большой нож",      1,         8,      "mknife.jpg",    10,    7);
additem(3,  "Тесак      ",      1,         12,     "bknife.jpg",    20,    7);
additem(4,  "Короткий меч",     1,         16,     "ksword.jpg",    25,    7);
additem(5,  "Медный меч",       1,         20,     "msword.jpg",    30,    7);
additem(6,  "Бронзовый меч",    1,         24,     "bsword.jpg",    35,    7);
additem(7,  "Средний меч",      1,         28,     "ssword.jpg",    40,    7);
additem(8,  "Длинный меч",      1,         32,     "lsword.jpg",    50,    7);
additem(9,  "Рыцарский меч",    1,         36,     "nsword.jpg",    55,    7);
additem(10, "Мини молот",       1,         8,      "mmolot.jpg",    9,     7);
additem(11, "Молот грома",      1,         10,     "gmolot.jpg",    15,    7);
additem(12, "Большой молот",    1,         14,     "lmolot.jpg",    22,    7);
additem(13, "Молот магии",      1,         18,     "kmolot.jpg",    25,    7);
additem(14, "Дубина",           1,         2,      "mdubin.jpg",    3,     7);
additem(15, "Большая дубина",   1,         6,      "bdubin.jpg",    4,     7);
additem(16, "Секира минотавра", 1,         22,     "msekir.jpg",    32,    7);
additem(17, "Булава",           1,         26,     "mbulav.jpg",    37,    7);
additem(18, "Шипованная булава",1,         30,     "sbulav.jpg",    43,    7);
additem(19, "Большая булава",   1,         34,     "bbulav.jpg",    52,    7);
additem(20, "Топорик",          1,         10,     "stopor.jpg",    13,    7);
additem(21, "Топор",            1,         12,     "mtopor.jpg",    22,    7);
additem(22, "Топор дровосека",  1,         16,     "dtopor.jpg",    26,    7);
additem(23, "Топор огра",       1,         20,     "otopor.jpg",    31,    7);
additem(24, "Большой топор",    1,         24,     "btopor.jpg",    56,    7);
additem(25, "Ятаган",           1,         38,     "jataga.jpg",    60,    7);
additem(26, "Палица",           1,         42,     "palica.jpg",    72,    7);
additem(27, "Меч титана",       1,         40,     "tsword.jpg",    70,    7);
additem(28, "Маленький лук",    1,         10,     "s__luk.jpg",    16,    7);
additem(29, "Средний лук",      1,         16,     "m__luk.jpg",    27,    7);
additem(30, "Большой лук",      1,         22,     "b__luk.jpg",    33,    7);
additem(31, "Арбалет",          1,         28,     "sarbal.jpg",    42,    7);
additem(32, "Лук эльфа",        1,         34,     "elfluk.jpg",    55,    7);
additem(33, "Большой арбалет",  1,         40,     "barbal.jpg",    69,    7);
additem(34, "Маленький щит",    2,         4,      "sarmor.jpg",    10,    6);
additem(35, "Кожанный щит",     2,         8,      "karmor.jpg",    15,    6);
additem(36, "Медный щит",       2,         12,     "marmor.jpg",    20,    6);
additem(37, "Бронзовый щит",    2,         16,     "barmor.jpg",    25,    6);
additem(38, "Средний щит",      2,         20,     "tarmor.jpg",    30,    6);
additem(39, "Щит с камнями",    2,         24,     "rarmor.jpg",    35,    6);
additem(40, "Магический щит",   2,         28,     "garmor.jpg",    40,    6);
additem(41, "Большой щит",      2,         32,     "iarmor.jpg",    45,    6);
additem(42, "Рыцарский щит",    2,         36,     "narmor.jpg",    50,    6);
additem(43, "Щит великого огра",2,         40,     "oarmor.jpg",    55,    6);
additem(44, "Маленький шлем",   2,         1,      "s_head.jpg",    4,     1);
additem(45, "Деревянный шлем",  2,         3,      "w_head.jpg",    8,     1);
additem(46, "Медный шлем",      2,         5,      "m_head.jpg",    12,    1);
additem(47, "Бронзовый шлем",   2,         7,      "b_head.jpg",    16,    1);
additem(48, "Рыцарский шлем",   2,         9,      "k_head.jpg",    20,    1);
additem(49, "Пояс силы",        1,         2,      "a_tors.jpg",    5,     4);
additem(50, "Пояс защиты",      2,         5,      "p_tors.jpg",    15,    4);
additem(51, "Пояс ловкости",    3,         8,      "d_tors.jpg",    20,    4);
additem(52, "Щитки",            2,         1,      "s_shit.jpg",    2,     9);
additem(53, "Рыцарские щитки",  2,         3,      "k_shit.jpg",    5,     9);
additem(54, "Сапоги",           2,         2,      "ssapog.jpg",    7,     8);
additem(55, "Кожанные сапоги",  2,         3,      "osapog.jpg",    10,    8);
additem(56, "Рыцарские боты",   2,         4,      "ksapog.jpg",    13,    8);
additem(57, "Сапоги эльфа",     3,         5,      "esapog.jpg",    16,    8);
additem(58, "Кожанная броня",   2,         2,      "k_bron.jpg",    8,     3);
additem(59, "Медная кольчуга",  2,         3,      "m_bron.jpg",    12,    3);
additem(60, "Медная броня",     2,         4,      "e_bron.jpg",    16,    3);
additem(61, "Крепкая броня",    2,         5,      "r_bron.jpg",    20,    3);
additem(62, "Бронзовая броня",  2,         6,      "b_bron.jpg",    24,    3);
additem(63, "Рыцарская броня",  2,         7,      "n_bron.jpg",    28,    3);
additem(64, "Волшебная броня",  2,         8,      "v_bron.jpg",    32,    3);
additem(65, "Броня титана",     2,         9,      "t_bron.jpg",    46,    3);
additem(66, "Кольцо силы",      1,         2,      "a_ring.jpg",    10,    5);
additem(67, "Кольцо денег",     4,         20,     "m_ring.jpg",    10,    5);
additem(68, "Кольцо защиты",    2,         2,      "p_ring.jpg",    10,    5);
additem(69, "Кольцо ловкости",  3,         2,      "d_ring.jpg",    10,    5);
additem(70, "Плащ - накидка",   2,         5,      "nplash.jpg",    10,    10);
additem(71, "Плащ Дракулы",     2,         10,     "dplash.jpg",    20,    10);
additem(72, "Магический плащ",  5,         50,     "mplash.jpg",    25,    10);
additem(73, "Амулет удачи",     1,         5,      "u_amul.jpg",    20,    2);
additem(74, "Амулет денег",     4,         15,     "d_amul.jpg",    20,    2);
additem(75, "Амулет силы",      1,         10,     "a_amul.jpg",    35,    2);
additem(76, "Магический амулет",5,         25,     "m_amul.jpg",    30,    2);
additem(77, "Метисовая броня",  2,         12,     "mebron.jpg",    50,    3);
additem(78, "Эльфотриксовая броня",2,      13,     "etbron.jpg",    65,    3); 
additem(79, "Броня трайдера",   2,         15,     "trbron.jpg",    80,    3); 
additem(80, "Броня тёмного рыцаря",2,      17,     "dkbron.jpg",    95,    3); 
additem(81, "Сапоги варвара",   2,         6,      "vsapog.jpg",    20,    8); 
additem(82, "Сапоги Аргона",    2,         7,      "asapog.jpg",    25,    8); 
additem(83, "Боты титана",      2,         9,      "tsapog.jpg",    30,    8); 
additem(84, "Скоростной арбалет", 1,       41,     "varbal.jpg",    75,    7); 
additem(85, "Самозаводной арбалет", 1,     43,     "aarbal.jpg",    80,    7); 
additem(86, "Автоматический арбалет", 1,   45,     "zarbal.jpg",    90,    7); 
additem(87, "Дробитель",        1,         36,     "rbulav.jpg",    57,    7); 
additem(88, "Булава смерти",    1,         38,     "dbulav.jpg",    62,    7); 
additem(89, "Лук великого эльфа",1,        42,     "ge_luk.jpg",    77,    7); 
additem(90, "Лук снайпера",     1,         44,     "sn_luk.jpg",    88,    7); 
additem(91, "Топор гнома",      1,         27,     "gtopor.jpg",    60,    7); 
additem(92, "Большая секира минотавра",1,  30,     "psekir.jpg",    80,    7); 
additem(93, "Меч забвения",     1,         43,     "zsword.jpg",    85,    7); 
additem(94, "Меч Аргона",       1,         45,     "asword.jpg",    95,    7); 
additem(95, "Шершень",          1,         47,     "hsword.jpg",    110,   7); 
additem(96, "Меч палладина",    1,         50,     "psword.jpg",    140,   7); 
additem(97, "Чёрная гадюка",    1,         52,     "gsword.jpg",    160,   7); 
additem(98, "Жало дракона",     1,         55,     "dsword.jpg",    190,   7); 
additem(99, "Метаблас",         1,         15,     "mestar.jpg",    30,    7); 
additem(100,"Дарзандал",        1,         16,     "dastar.jpg",    32,    7); 
additem(101,"Иглорез",          1,         17,     "igstar.jpg",    34,    7); 
additem(102,"Крафторез",        1,         18,     "krstar.jpg",    36,    7); 
additem(103,"Прострайк",        1,         20,     "prstar.jpg",    38,    7); 
additem(104,"Стардел",          1,         22,     "ststar.jpg",    40,    7); 
additem(105,"Спидран",          1,         26,     "spstar.jpg",    44,    7); 
additem(107,"Меч правосудия",   1,         72,     "tksword.jpg",   350,   7); 
additem(108,"Меч андруил",      1,         75,     "answord.jpg",   380,   7); 
additem(109,"Посох ученика",    1,         25,     "puposoh.jpg",   60,    11);
additem(110,"Посох силы",       1,         30,     "poposoh.jpg",   70,    11);
additem(111,"Посох молний",     1,         35,     "moposoh.jpg",   80,    11);
additem(112,"Посох разрядов",   1,         40,     "raposoh.jpg",   90,    11);
additem(113,"Посох огня",       1,         45,     "fiposoh.jpg",   100,   11);
additem(114,"Посох огненного шара",1,      50,     "fbposoh.jpg",   110,   11);
additem(115,"Посох энергии",    1,         55,     "enposoh.jpg",   120,   11);
additem(116,"Посох лекаря",     1,         60,     "leposoh.jpg",   130,   11);
additem(117,"Посох верховного мага",1,     65,     "veposoh.jpg",   140,   11);
additem(118,"Посох архимага",   1,         70,     "arposoh.jpg",   150,   11);
additem(119,"Простой серп",     1,         80,     "s_serp.jpg",    450,   7);
additem(120,"Лук мастера эльфа",1,         85,     "ge_bow.jpg",    500,   7);
additem(121,"Пика охранника",   1,         90,     "fk_pika.jpg",   550,   7);
additem(122,"Топор Тримакса",   1,         95,     "tr_ace.jpg",    600,   7);
additem(123,"Арбалет дракона",  1,         100,    "dr_arbal.jpg",  650,   7);
additem(124,"Огненный меч",     1,         106,    "fr_blade.jpg",  780,   7);
additem(125,"Серебрянный щит",  1,         70,     "ar_armor.jpg",  200,   6);
additem(126,"Голубое затмение", 1,         75,     "bl_posoh.jpg",  200,   11);
additem(127,"Посох духов",      1,         80,     "re_posoh.jpg",  250,   11);
additem(128,"Посох колдуна",    1,         85,     "sh_posoh.jpg",  300,   11);
additem(129,"Электропосох",     1,         90,     "el_posoh.jpg",  350,   11);
additem(130,"Посох фаталиста",  1,         95,     "fi_posoh.jpg",  400,   11);
additem(131,"Астральный посох", 1,         100,    "as_posoh.jpg",  450,   11);

echo("<font color='green'>Данные в таблицу allitems занесены</font><br>");

//Заклинания 
//      №    название          тип     что делает  cколько  рисунок      цена
addcast(1,  "Молния",           1,         1,         10,   "arrow.jpg",  1);
addcast(2,  "Смерчь",           1,         1,         15,   "smerc.jpg",  2);
addcast(3,  "Лечение",          1,         2,         20,   "leche.jpg",  3);
addcast(4,  "Ледяная стрела",   1,         1,         20,   "ledar.jpg",  2);
addcast(5,  "Высосать жизнь",   1,         3,         25,   "visos.jpg",  6);
addcast(6,  "Метеорит",         2,         1,         12,   "meteo.jpg",  1);
addcast(7,  "Серный дождь",     2,         1,         14,   "sernd.jpg",  2);
addcast(8,  "Вспышка",          2,         1,         20,   "vspis.jpg",  2);
addcast(9,  "Поджечь",          2,         1,         50,   "podze.jpg",  5);
addcast(10, "Волна смерти",     2,         4,         60,   "volna.jpg",  7);
addcast(11, "Метеоритный дождь",2,         1,         70,   "mdozd.jpg",  9);
addcast(12, "Гипноз",           3,         5,	      11,   "hypno.jpg",  2);
addcast(13, "Забывчивость",     3,         6,	      0,    "zabiv.jpg",  3);
addcast(14, "Проклятье",        3,         7,	      25,   "prokl.jpg",  3);
addcast(15, "Лечение от врага", 3,         8,	      15,   "vlech.jpg",  2);
addcast(16, "Камикадзе",        3,         4,	      70,   "kamik.jpg",  9);
addcast(17, "Огненный шар",     2,         1,	      65,   "fireball.jpg",  8);
addcast(18, "Тёмное лечение",   1,         2,	      25,   "darklek.jpg",   4);
addcast(19, "Светлое лечение",  1,         2,	      30,   "liglek.jpg",    5);
addcast(20, "Тёмный гипноз",    3,         5,	      5,    "darkhyp.jpg",   8);
addcast(21, "Огненное око",     3,         5,	      7,    "fireeye.jpg",   1);
addcast(22, "Высосать энергию", 3,         9,	      2,    "manadown.jpg",  2);
addcast(23, "Властитель огня",  2,         1,	      52,   "vlfire.jpg",    5);
addcast(24, "Сила льва",        1,         1,	      62,   "linepow.jpg",   8);
addcast(25, "Забрать жизнь",    3,         3,	      30,   "takelife.jpg",  8);
addcast(26, "Забрать энергию",  3,         9,	      4,    "takeener.jpg",  8);
addcast(27, "Безмятежность",    3,         5,	      2,    "inhyp.jpg",     5);
echo("<font color='green'>Данные в таблицу allcasts занесены</font><br>");

//Сами события:
//        №  Название               Эффект             Значение
addevent (1, "Эпидемия чумы",        1,                 60);
addevent (2, "Эпидемия холеры",      1,                 50);
addevent (3, "Эпидемия гриппа",      1,                 40);
addevent (4, "Эпидемия геппатита",   1,                 30);
addevent (5, "Эпидемия туберкулёза", 1,                 20);
addevent (6, "Землятресение",        2,                 5);
addevent (7, "Наводнение",           2,                 4);
addevent (8, "Смерч",                2,                 3);
addevent (9, "Ураган",               2,                 2);
addevent (10, "Сель",                 2,                 1);
echo("<font color='green'>Данные в таблицу events занесены</font><br>");

//Создание файлов
mkdir('maps');
for ($i = 1; $i < 21; $i++)
{
   for ($j = 1; $j < 21; $j++)
   {
	   //Создаём регион
	   $name = "maps/".$i."x".$j.".map";
	   $file = fopen($name, "w");

	   //Пишем клетки
	   for ($a = 1; $a < 11; $a++)
	   {
		   for ($b = 1; $b < 11; $b++)
		   {
			   fputs($file, "0*0=0\n");
		   }
	   }
	fclose ($file);
   }
}

//Создание файлов
mkdir('maps/heroes');
for ($i = 1; $i < 21; $i++)
{
   for ($j = 1; $j < 21; $j++)
   {
	   //Создаём регион
	   $name = "maps/heroes/".$i."x".$j.".map";
	   $file = fopen($name, "w");

	   //Пишем клетки
	   for ($a = 1; $a < 11; $a++)
	   {
		   for ($b = 1; $b < 11; $b++)
		   {
			   fputs($file, "0*0=0\n");
		   }
	   }
	fclose ($file);
   }
}

?>

<form action="install.php" method=post>
<input type='hidden' name='start' value='345'>
<input type='submit' value = '  Далее  ' style='background:#000099; font-WEIGHT: BOLD; COLOR: white; cursor: hand; list-style: lower-alpha; filter: Alpha(Opacity=20, FinishOpacity=100, Style=3)'>
</form>

<?
}

?>






