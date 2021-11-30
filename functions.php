<?

//Отключение уровня ошибок
Error_Reporting(E_ALL & ~E_NOTICE);
include "bases.php";

//Соединение с базой
function baselink()
{
$file = fopen("config.ini.php", "r");
$temp = trim(fgets($file, 255));
$temp = trim(fgets($file, 255));
$host = trim(fgets($file, 255));
$base = trim(fgets($file, 255));
$name = trim(fgets($file, 255));
$pass = trim(fgets($file, 255));
fclose($file);
$ret = @mysql_connect($host, $name, $pass);
$slc = mysql_select_db($base);
}

//Включение битвы
function BattleOn($Login, $Opp)
{
  //Включаем битву
  change($Login, 'battle', 'battle', '1');
  change($Opp, 'battle', 'battle', '1');

	//Добавляем номер нового ЛОГа
  $file = fopen("data/count.dat", "r");
  $num = fgets($file, 255);
	fclose ($file);
	$num++;
	$file = fopen("data/count.dat", "w");
	fputs ($file, $num);
	fclose ($file);

  //Количество проведённых боёв увеличиваем на "1"
  $adm = getadmin();
  $btls = getfrom('admin', $adm, 'settings', 'f4');
  $btls++;
  setto('admin', $adm, 'settings', 'f4', $btls);

  //Создаём ЛОГ файл
	$file = fopen("data/logs/".$num.".log", "w");
	fclose ($file);

  //Количество боёв на один больше
  change($Login, 'time', 'combats', getdata($Login, 'time', 'combats')+1);
  change($Opp, 'time', 'combats', getdata($Opp, 'time', 'combats')+1);

  //Сбрасываем все данные на 0
  change($Login, 'battle', 'health', '0');
  change($Opp, 'battle', 'health', '0');
  change($Login, 'battle', 'opponent', $Opp);
  change($Opp, 'battle', 'opponent', $Login);
  change($Login, 'battle', 'turn', $Login);
  change($Opp, 'battle', 'turn', $Login);
  change($Login, 'battle', 'attack', '0');
  change($Opp, 'battle', 'attack', '0');
  change($Login, 'battle', 'data', '');
  change($Opp, 'battle', 'data', '');
  change($Login, 'battle', 'value', '6');
  change($Opp, 'battle', 'value', '6');
  change($Login, 'battle', 'info', $num);
  change($Opp, 'battle', 'info', $num);
  $tm = time();
  change($Login, 'battle', 'timeout', $tm);
  change($Opp, 'battle', 'timeout', $tm);
}

//Нападаем на монстра
function MonsterBattle($Login, $Opp, $x, $y, $rx, $ry)
{
  //Генерация имени временного персонажа
  $Monster = $Opp;
  $Opp = $Login."_clon";
  $val = 0;
  $lv  = getdata($Login, 'hero', 'level') + rand(1, 4);
  $r   = rand(1, 12);

  //Получение собственных характеристик
  $power       = getdata($Login, 'abilities', 'power');
  $protect     = getdata($Login, 'abilities', 'protect');
  $magicpower  = getdata($Login, 'abilities', 'magicpower');
  $know        = getdata($Login, 'abilities', 'cnowledge');
  $charism     = getdata($Login, 'abilities', 'charism');
  $dexterity   = getdata($Login, 'abilities', 'dexterity');
  $intel       = getdata($Login, 'abilities', 'intellegence');
  $naturemagic = getdata($Login, 'abilities', 'naturemagic');
  $combatmagic = getdata($Login, 'abilities', 'combatmagic');
  $mindmagic   = getdata($Login, 'abilities', 'mindmagic');
  $power       = ($power + $protect + $magicpower + $naturemagic)*1.5;

  //Получаем вещи в руках
  $name  = getfrom('monster', $Monster, 'random', 'hand');
  $hand  = getfrom('name', $name, 'allitems', 'num');
  $name  = getfrom('monster', $Monster, 'random', 'armor');
  $armor = getfrom('name', $name, 'allitems', 'num');
  $Hlt   = $lv * 100;

  //Создание нового персонажа
	mysql_query ("insert into battles values('$Opp', 0, '0', 0);");
  mysql_query("insert into city values ('$Opp', '$login', '$val', '$val', '$val', '$val', '$val', '$val', '$val', '$val', '$val', '$val', '$val', '$val', '$val', '$val', '$val', '$val', '$val', '$val', '$val', '$val');");
	mysql_query("insert into users values ('$Opp', '$Login', '$x', '$y', '$rx', '$ry', '$Opp', '$Opp');");
	mysql_query("insert into hero values ('$Opp', '$Monster', '$val', '$lv', '5', '$Opp', '$Opp', '$Hlt', '$Opp');");
  mysql_query ("insert into battle values('$Opp', '0', '0', '0', '0', '0', '', '0', '0', '0');");
	mysql_query("insert into magic values ('$Opp', ".$r.", 0, 0, 0, 0, 0);");
  mysql_query("insert into abilities values ('$Opp', '$power', '$protect', '$magicpower', '$know', '$charism', '$dexterity', '$intel', '$naturemagic', '$combatmagic', '$mindmagic');");
	mysql_query("insert into items values ('$Opp', '$val', '$val', '$val', '$val', '$val', '$armor', '$hand', '$val', '$val', '$val', '$val');");
	mysql_query("insert into bottles values ('$Opp', '1', '1', '1', '1', '1', '1', '0', '0', '0', '0', '0', '0', '0', '0', '0');");

  //Пометка о создании для удаления персонажа после битвы
  change($Opp, 'city', 'build14', '1');

  //Установки битвы
  BattleOn($Login, $Opp);
}

//Изменить вес
function ChangeWeight($Login, $Weight)
{
  $Wg = getdata($Login, 'status', 'timeout');
  $Wg = $Wg + $Weight;
  if ($Wg < 0)
    $Wg = 0;
  change($Login, 'status', 'timeout', $Wg);
}

//Защита от битвы
function FromBattle($Login)
{
  //Битва героями
  $btl = getdata($Login, 'battle', 'battle');
  if ($btl != 0)
    moveto('battle.php');

  //Битва армиями
  $btl = getdata($Login, 'battles', 'battle');
  if ($btl != 0)
    moveto('fight.php');
}

//Кнопка HELP
function HelpMe($index, $align)
{
  //Скрипт помощи
  echo("\n\n<script language=JavaScript>\n");
  echo("function helpme(s)\n");
  echo("{\n");
	echo('window.open("help/help.php?index=" + s, 16,"toolbar=no, location=no, menubar=no,scrollbars=yes,width=400,height=300");'."\n");
  echo("}\n");
  echo("</script>\n");
  echo("<form action=javascript:helpme('".$index."')>\n");
  if ($align == 1)
    echo("<center>\n");
  ?>
  <input type='submit' value='Помощь' style='background:#000099; font-WEIGHT: BOLD; COLOR: white; cursor: hand; list-style: lower-alpha; filter: Alpha(Opacity=20, FinishOpacity=100, Style=3)'>
  <?
  if ($align == 1)
    echo("</center>\n");
  echo("</form>\n\n\n");
}

//Аналог
function PBar($percent, $color)
{
  $percent = round($percent);
  $wd = $percent;
  if ($wd < 1)
    $wd = 1;
  echo("<table border=1 cellpadding=0 cellspacing=0 width=150>");
  echo("<tr bgcolor=#C0C0C0><td width=$wd% align=center bgcolor='$color'><font size='1' color='white'>$percent%</font></td><td></td></tr></table>");
} 

//Отображаем ProgressBar
function progress($percent)
{
  //#264CB7
  echo("<table align='center' width='100%' bgcolor='#000000' cellspacing='1' cellpadding='0'>");
  echo("<tr bgcolor='#cccccc'><td><table cellspacing='0' cellpadding='0' width='$percent%'>");
  echo("<tr><td align='center' bgcolor='#009900'><font size='1' color='white'>$percent%</font></td></tr></table></td></tr></table>");
} 

//Соединение с базой...
baselink();

//Удаление базы данных
function drop($name)
{
   //mysql_query("drop database ".$name.";");
}

//Окно
function wnd()
{
	?>
	<script>
	function wnd(s)
	{
		window.open(s, null,'toolbar=no, location=no, menubar=no,scrollbars=yes,width=656,height=480');
	}
	</script>
	<?
}

//Пишем сообщение
function sms($to, $login, $txt)
{
//Добавить
$file = fopen ("data/mail/".$to."/rec.".time(), "a");

//От кого
$from = getdata($login, 'hero', 'name');

//Кому
change ($to, 'status', 'f2', '1');

//Отправляем...
if ((empty($from))||($from == "")) {$from = $login;}
if ($from != $login)
{
	fputs ($file, "<font color=green><b>".$from."<br>(".$login.")</b></font><br>\n");
}
else
{
	fputs ($file, "<font color=green><b>".$from."</b></font><br>\n");
}
fputs ($file, $txt."<br>");
fclose ($file);
sleep (1);
}

//Слишком много пользователей
function toomuch($name)
{
//	link();
	$ath = mysql_query("select * from users;");
	if ($ath)
	{
		$how = 0;
		while ($rw = mysql_fetch_row($ath))
		{
			if (getdata($rw[0], 'status', 'online') == 1)
			{
				$how++;
			}
		}
	}
	return $how;
}

//Проверка имени пользователя и пароля
function finduser($username, $pass)
{
//link();
$usr = mysql_query("select * from users;");
$find = 0;
if ($usr)
   {
   while ($user = mysql_fetch_array($usr))
      {
      if (($user['login'] == $username)&&($user['pwd'] == $pass))
         {
         $find = 1;
		 }
	  }
   }
   else
	{
	   $find = 2;
	}
return $find;
}

//Все пользователи
function allusers($name)
{
	echo("<script>\n");
	echo("function clans(p1)\n");
	echo("{\n");
	echo("var s;\n");
	echo("s = 'claninfo.php?' + p1;\n");
	echo("window.open(s, null,'toolbar=no, location=no, menubar=no,scrollbars=yes,width=656,height=480');\n");
	echo("}\n");
	echo("</script>\n");

	echo("<script>\n");
	echo("function dd(p1)\n");
	echo("{\n");
	echo("var s;\n");
	echo("s = 'info.php?name=' + p1;\n");
	echo("window.open(s, null,'toolbar=no, location=no, menubar=no,scrollbars=yes,width=656,height=480');\n");
	echo("}\n");
	echo("</script>\n");

	$ath = mysql_query("select * from users;");
	if ($ath)
	{
		while ($rw = mysql_fetch_row($ath))
		{
			//Инфа о пользователях
			$st = getdata($rw[0], 'status', 'online');
			$ts = getdata($rw[0], 'inf', 'fld7');

			if ($st == 1) 
				{
				echo("<center><table border=1 width=100% CELLSPACING=0 CELLPADDING=0>");
				echo("<tr>");

				//Получаем данные
				$clan = getdata($rw[0], 'inclan', 'clan');
				$temp = getfrom('name', $clan, 'clans', 'logo');
				$adm =  getfrom('name', $clan, 'clans', 'login');

				//Если пусто, проверить м.б. это админ клана?
				if (empty($temp))
				{
					$temp = getfrom('login', $rw[0], 'clans', 'logo');
					$adm = $rw[0];
				}

				//Если не пусто
				if (!empty($temp))
				{
					echo("<td width=1% align=center>");
					$test = "'admin=".$adm."&login=".$name."'";
					echo ("<a href=javascript:clans(".$test.");><img src='".$temp."' width=32 height=32 border=0></a>");
					echo("</td>");
				} else
				{
					echo("<td width=1% align=center>");
					echo ("<img src='images\clans\empty.gif' width=32 height=32 border=0>");
					echo("</td>");
				}

				//Вывод имени персонажа
				echo("<td width=40% align=center><b>");
				if ($ts  == '1')
					{
					echo ("<font color=blue>");
					} else
						{
						echo ("<font color=green>");
						}
				echo (getdata($rw[0], 'hero', 'name')." <a href=javascript:dd('".$rw[0]."')>[<i><font color=black>".getdata($rw[0], 'hero', 'level')."</font></i>]</a></b></td><td width=40% align=center>");

				if ($ts == '1')
					{
					echo ("<font color=blue>");
					} else
						{
						echo ("<font color=green>");
						}
				echo ("<table border=0 width=100%><tr><td width=60% align=center>(".$rw[0].")</td>");
				if ($rw[0] == $name)
					{
          if (getdata($name, 'inf', 'fld7') == '0')
            echo ("<td align=center><a href='game.php?action=60'><b><font color=blue>Подать заявку</font></b></a>");
            else
            echo("<td align=center><a href='game.php?action=61'><b><font color=blue>Отозвать заявку</font></b></a><br></td>");
					}
				echo ("</tr></table></tr></table></center></font>");
				}
		}
	}
}

//Все пользователи
function offline($name)
{
//	link();
	$ath = mysql_query("select * from users;");
	if ($ath)
	{
		while ($rw = mysql_fetch_row($ath))
		{
			$delta = time()-getdata($rw[0], 'inf', 'fld3');
			if ($delta > 299) 
				{
					change($rw[0], 'status', 'online', '0');
				}
		}
	}
}

//Проверка IP адреса
function findip($ip)
{
//link();
$usr = mysql_query("select * from ip;");
$find = 0;
if ($usr)
   {
   while ($user = mysql_fetch_array($usr))
      {
      if ($user['ip'] == $ip)
         {
         $find = 1;
		 }
	  }
   }
   else
	{
	   $find = 2;
	}
return $find;
}

//Обычный поиск пользователя
function hasuser($username)
{
//link();
$usr = mysql_query("select * from users;");
$find = 0;
if ($usr)
   {
   while ($user = mysql_fetch_array($usr))
      {
      if (($user['login'] == $username))
         {
         $find = 1;
		 }
	  }
   }
   else
	{
	   $find = 2;
	}
return $find;
}

//Получить рисунок
function getimg($num)
{
//link();
$usr = mysql_query("select * from allitems;");
$find = "";
if ($usr)
   {
   while ($user = mysql_fetch_array($usr))
      {
      if (($user['num'] == $num))
         {
         $find = $user['img'];
		 }
	  }
   }
   else
	{
	   $find = "<font color=red>Ошибка подключения</font>";
	}
	$test = "images/weapons/".$find;
	$find = $test;
	return $find;
}

//Получить рисунок
function getcimg($num)
{
//link();
$usr = mysql_query("select * from allitems;");
$find = "";
if ($usr)
   {
   while ($user = mysql_fetch_array($usr))
      {
      if (($user['num'] == $num))
         {
         $find = $user['img'];
		 }
	  }
   }
   else
	{
	   $find = "<font color=red>Ошибка подключения</font>";
	}
	$test = "images/cast/".$find;
	$find = $test;
	return $find;
}

//В союзе мы или нет
function hasunion($n1, $n2)
{
	$yes = 0;
	if (getdata($n1, 'unions', 'login2') == $n2) {$yes = 1;}
	if (getdata($n1, 'unions', 'login3') == $n2) {$yes = 1;}
	if (getdata($n1, 'unions', 'login4') == $n2) {$yes = 1;}
	if (getdata($n1, 'unions', 'login5') == $n2) {$yes = 1;}
	return $yes;
}

//Подсказка
function getinfo($num)
{
//link();
$usr = mysql_query("select * from allitems;");
$find = "";
if ($usr)
   {
   while ($user = mysql_fetch_array($usr))
      {
      if (($user['num'] == $num))
         {
         $act = $user['action'];
		 $hw = $user['effect'];
		 }
	  }
   }
   else
	{
	   $find = "<font color=red>Ошибка подключения</font>";
	}

	//Что делает оружие
	if ($act == 1) {$act = "Увеличивает урон на ";}
	if ($act == 2) {$act = "Добавляет к защите ";}
	if ($act == 3) {$act = "Добавляет к выносливости ";}
	if ($act == 4) {$act = "Увеличивает доход на ";}
	if ($act == 5) {$act = "Повышает магические способности на ";}

	//Результат
	$find = $act.$hw."%"; 

  $cst = CastName($num);
  $find = $find."<br>".$cst;
  //Выводим результат
	return $find;
}

//Подсказка к магии
function getcinfo($num)
{
//link();
$usr = mysql_query("select * from allcasts;");
$find = "";
if ($usr)
   {
   while ($user = mysql_fetch_array($usr))
      {
      if (($user['num'] == $num))
         {
         $act = $user['action'];
		 $hw = $user['effect'];
		 }
	  }
   }
   else
	{
	   $find = "<font color=red>Ошибка подключения</font>";
	}

	//Что делает оружие
	if ($act == 1) {$act = "Увеличивает урон на ";}
	if ($act == 2) {$act = "Восстанавливает ";}
	if (($act == 3)||($act == 8)) {$act = "Высасывает ";}
	if ($act == 4) {$act = "Наносит вред и оппоненту и себе. Урон ";}
	if (($act == 5)||($act == 6)) {$act = "Наносит противнику моральный вред. Тем самым, количество очков действия противника падает на ";}
	if ($act == 7) {$act = "Напускает проклятье на оппонента, тем самым нанося ему моральный вред. Боевой дух оппонента падает на ";}

	if ($hw == 0) {$hw = 15;}
	//Результат
	$find = $act.$hw."%";
	return $find;
}

//Для битвы
function forbattle($num, $wh)
{
//link();
$usr = mysql_query("select * from allitems;");
$find = 0;
if ($usr)
   {
   while ($user = mysql_fetch_array($usr))
      {
      if (($user['num'] == $num))
		 {
		 $act = $user['action'];
		 $hw = $user['effect'];
		 }
	  }
   }
   else
	{
	   $find = "<font color=red>Ошибка подключения</font>";
	}

	//Результат
	$find = 0;

	//Что делает оружие
	if ($act == $wh)
		{
		$find = $hw/100;
		}
	return $find;
}

//Для битвы
function spell($num)
{
//link();
$usr = mysql_query("select * from allitems;");
$find = 0;
if ($usr)
   {
   while ($user = mysql_fetch_array($usr))
      {
      if ($user['num'] == $num)
		 {
		  return $user['effect'];
		 }
	  }
   }
   return 0;
}

//Получение данных из таблицы по имени поля, имени пользователя и имени таблицы
function getdata($username, $table, $field)
{
//link();
$usr = mysql_query("select * from ".$table.";");
$find = "";
if ($usr)
   {
   while ($user = mysql_fetch_array($usr))
      {
      if (($user['login'] == $username))
         {
         $find = $user[$field];
		 }
	  }
   }
   else
	{
	   $find = "<font color=red>Ошибка подключения</font>";
	}
	return $find;
}

//Убираем предмет из инвенторя
function kickitem($login, $item)
{
	//Находим этот предмет в инвенторе
	$golova = getfrom('num', getdata($login, 'items', 'golova'), 'allitems', 'name');
	$shea = getfrom('num', getdata($login, 'items', 'shea'), 'allitems', 'name');
	$telo = getfrom('num', getdata($login, 'items', 'telo'), 'allitems', 'name');
	$leftruka = getfrom('num', getdata($login, 'items', 'leftruka'), 'allitems', 'name');
	$rightruka = getfrom('num', getdata($login, 'items', 'rightruka'), 'allitems', 'name');
	$palec = getfrom('num', getdata($login, 'items', 'palec'), 'allitems', 'name');
	$plash = getfrom('num', getdata($login, 'items', 'plash'), 'allitems', 'name');
	$tors = getfrom('num', getdata($login, 'items', 'tors'), 'allitems', 'name');
	$koleni = getfrom('num', getdata($login, 'items', 'koleni'), 'allitems', 'name');
	$nogi = getfrom('num', getdata($login, 'items', 'nogi'), 'allitems', 'name');

	//Он ли это
	if ($item == $golova)
	{
		change($login, 'items', 'golova', 0);
	}
	if ($item == $shea)
	{
		change($login, 'items', 'shea', 0);
	}
	if ($item == $telo)
	{
		change($login, 'items', 'telo', 0);
	}
	if ($item == $leftruka)
	{
		change($login, 'items', 'leftruka', 0);
	}
	if ($item == $rightruka)
	{
		change($login, 'items', 'rightruka', 0);
	}
	if ($item == $palec)
	{
		change($login, 'items', 'palec', 0);
	}
	if ($item == $plash)
	{
		change($login, 'items', 'plash', 0);
	}
	if ($item == $tors)
	{
		change($login, 'items', 'tors', 0);
	}
	if ($item == $koleni)
	{
		change($login, 'items', 'koleni', 0);
	}
	if ($item == $nogi)
	{
		change($login, 'items', 'nogi', 0);
	}

  //Получаем номер предмета
  $item = getfrom('name', $item, 'allitems', 'num');

  //Ещё проверим в инвентаре
  for ($i = 1; $i <= 16; $i++)
  {
    $Itm = getdata($login, 'inventory', 'inv'.$i);
    if ($item == $Itm)
    {
      PopItem($login, $item);
      return;
    }
  }
}

//Убираем заклинание из книги
function kickcitem($login, $item)
{
	//Находим этот предмет в инвенторе
	$cast1 = getfrom('num', getdata($login, 'magic', 'cast1'), 'allcasts', 'name');
	$cast2 = getfrom('num', getdata($login, 'magic', 'cast2'), 'allcasts', 'name');
	$cast3 = getfrom('num', getdata($login, 'magic', 'cast3'), 'allcasts', 'name');
	$cast4 = getfrom('num', getdata($login, 'magic', 'cast4'), 'allcasts', 'name');
	$cast5 = getfrom('num', getdata($login, 'magic', 'cast5'), 'allcasts', 'name');
	$cast6 = getfrom('num', getdata($login, 'magic', 'cast6'), 'allcasts', 'name');

	//Он ли это
	$kicked = 0;
	if (($item == $cast1)&&($kicked == 0))
	{
		change($login, 'magic', 'cast1', 0);
		$kicked = 1;
	}
	if (($item == $cast2)&&($kicked == 0))
	{
		change($login, 'magic', 'cast2', 0);
		$kicked = 1;
	}
	if (($item == $cast3)&&($kicked == 0))
	{
		change($login, 'magic', 'cast3', 0);
		$kicked = 1;
	}
	if (($item == $cast4)&&($kicked == 0))
	{
		change($login, 'magic', 'cast4', 0);
		$kicked = 1;
	}
	if (($item == $cast5)&&($kicked == 0))
	{
		change($login, 'magic', 'cast5', 0);
		$kicked = 1;
	}
	if (($item == $cast6)&&($kicked == 0))
	{
		change($login, 'magic', 'cast6', 0);
		$kicked = 1;
	}
}

//Получение названия региона по зоне и координатам
function getregion($rx, $ry, $zone)
{
//link();
$usr = mysql_query("select * from map;");

$find = "";
if ($usr)
   {
   while ($user = mysql_fetch_array($usr))
      {
      if (($user['rx'] == $rx)&&($user['ry'] == $ry)&&($user['zone'] == '0'))
         {
         $find = $user['name'];
		 }
	  }
   }
   else
	{
	   $find = "<font color=red>Ошибка подключения</font>";
	}
	return $find;
}

//Изменение данных в базе
function setto($fld, $value, $table, $field, $new)
{
	mysql_query("update ".$table." set ".$field." = '".$new."' where ".$fld." = '".$value."';");
}

//Получение данных из таблицы по имени поля, имени пользователя и имени таблицы
function getfrom($fld, $value, $table, $field)
{
//link();
$usr = mysql_query("select * from ".$table.";");

$find = "";
if ($usr)
   {
   while ($user = mysql_fetch_array($usr))
      {
      if (($user[$fld] == $value))
         {
         $find = $user[$field];
		 }
	  }
   }
   else
	{
	   $find = "<font color=red>Ошибка подключения</font>";
	}
	return $find;
}

//Получение данных по карте
function getfield($row, $col, $x, $y)
{
//link();
$usr = mysql_query("select * from map;");
$find = "";
if ($usr)
   {
   while ($user = mysql_fetch_array($usr))
      {
      if (($user['row'] == $row)&&($user['col'] == $col))
         {
		  $field = "f".$x."x".$y;
          $find = $user[$field];
		 }
	  }
   }
   else
	{
	   $find = "<font color=red>Ошибка подключения</font>";
	}
	return $find;
}

//Добавить пункт
function addl($username, $place)
{
	if (getdata($username, 'items', $place) != 0)
		{
		echo ("<option value='".getfrom('num', getdata($username, 'items', $place), 'allitems', 'name')."'>".getfrom('num', getdata($username, 'items', $place), 'allitems', 'name')."</option>");
		}
}

//Добавить пункт
function addi($username, $Number)
{
	if (getdata($username, 'inventory', 'inv'.$Number) != 0)
		{
		echo ("<option value='".getfrom('num', getdata($username, 'inventory', 'inv'.$Number), 'allitems', 'name')."'>".getfrom('num', getdata($username, 'inventory', 'inv'.$Number), 'allitems', 'name')."</option>");
		}
}

//Все вещи персонажа
function allmyitems($username, $name)
{
//link();
$usr = mysql_query("select * from users;");
if ($usr)
   {
   while ($user = mysql_fetch_array($usr))
      {
      if (($user['login'] == $username))
         {
         $find = $user['login'];
		 }
	  }
   }

if (!empty($find))
	{
	echo ("<select name=$name style='background:#000099; font-WEIGHT: BOLD; COLOR: white; cursor: hand; list-style: lower-alpha; filter: Alpha(Opacity=20, FinishOpacity=100, Style=3)'>");
	//Голова
	addl($username, 'golova');
	//Шея
	addl($username, 'shea');
	//Тело
	addl($username, 'telo');
	//Торс
	addl($username, 'tors');
	//Кольца
	addl($username, 'palec');
	//Левая рука
	addl($username, 'leftruka');
	//Правая рука
	addl($username, 'rightruka');
	//Ноги
	addl($username, 'nogi');
	//Колени
	addl($username, 'koleni');
	//Плащ
	addl($username, 'plash');
  //И ещё 16 вещёй из инвенторя
  for ($i = 1; $i <= 16; $i++)
    addi($username, $i);
	echo ("</select>");
	}
}

//Добавить пункт
function addc($username, $place)
{
	if (getdata($username, 'magic', $place) != 0)
		{
		echo ("<option value='".getfrom('num', getdata($username, 'magic', $place), 'allcasts', 'name')."'>".getfrom('num', getdata($username, 'magic', $place), 'allcasts', 'name')."</option>");
		}
}

//Все вещи персонажа
function allmycasts($username, $name)
{
//link();
$usr = mysql_query("select * from users;");
if ($usr)
   {
   while ($user = mysql_fetch_array($usr))
      {
      if (($user['login'] == $username))
         {
         $find = $user['login'];
		 }
	  }
   }

if (!empty($find))
	{
	echo ("<select name=$name style='background:#000099; font-WEIGHT: BOLD; COLOR: white; cursor: hand; list-style: lower-alpha; filter: Alpha(Opacity=20, FinishOpacity=100, Style=3)'>");
	//Заклинание 1
	addc($username, 'cast1');
	//Заклинание 2
	addc($username, 'cast2');
	//Заклинание 3
	addc($username, 'cast3');
	//Заклинание 4
	addc($username, 'cast4');
	//Заклинание 5
	addc($username, 'cast5');
	//Заклинание 6
	addc($username, 'cast6');
	echo ("</select>");
	}
}

//Является ли пользователь субадмином
function issubadmin($username)
{
	$r = 0;
	$usr = mysql_query("select * from settings");
	$find = 0;
	if ($usr)
   {
	   while ($user = mysql_fetch_array($usr))
		{
	      if ($user['f1'] == $username)
		 {
			 $find = 1;
		 }
	  }
   }
   else
	{
	   $find = 0;
	}
	if (empty($username))
	{

	}
	return $find;	
}

//Админ ли пользователь
function isadmin($username)
{
//link();
$usr = mysql_query("select * from settings");
$find = 0;
if ($usr)
   {
   while ($user = mysql_fetch_array($usr))
      {
      if (($user['admin'] == $username))
         {
         $find = 1;
		 }
	  }
   }
   else
	{
	   $find = 0;
	}
	return $find;
}

//Админ ли пользователь
function getadmin()
{
$usr = mysql_query("select * from settings");
$find = "Admin";
if ($usr)
   {
   while ($user = mysql_fetch_array($usr))
      {
      if (($user['admin'] == $username))
         {
         $find = $username;
		 }
	  }
   }
	return $find;
}

//Удаление пользователя из всех таблиц
function kickuser($username)
{
  //Если это монстр, каталоги и файлы удалять не надо
  $Monster = getdata($username, 'city', 'build14');

	//Удаляем из базы
	mysql_query("delete from abilities where login = '".$username."';");
	mysql_query("delete from lostpass where login = '".$username."';");
	mysql_query("delete from unions where login = '".$username."';");
	mysql_query("delete from battles where login = '".$username."';");
	mysql_query("delete from economic where login = '".$username."';");
	mysql_query("delete from hero where login = '".$username."';");
	mysql_query("delete from help where login = '".$username."';");
	mysql_query("delete from info where login = '".$username."';");
	mysql_query("delete from items where login = '".$username."';");
	mysql_query("delete from time where login = '".$username."';");
	mysql_query("delete from users where login = '".$username."';");
	mysql_query("delete from city where login = '".$username."';");
	mysql_query("delete from ip where login = '".$username."';");
	mysql_query("delete from magic where login = '".$username."';");
	mysql_query("delete from status where login = '".$username."';");
	mysql_query("delete from inf where login = '".$username."';");
	mysql_query("delete from coords where login = '".$username."';");
	mysql_query("delete from inclan where login = '".$username."';");
	mysql_query("delete from temp where login = '".$username."';");
	mysql_query("delete from battle where login = '".$username."';");
	mysql_query("delete from hosting where login = '".$username."';");
	mysql_query("delete from bottles where login = '".$username."';");
	mysql_query("delete from newchar where login = '".$username."';");
	mysql_query("delete from army where login = '".$username."';");
	mysql_query("delete from capital where login = '".$username."';");
	mysql_query("delete from inventory where login = '".$username."';");

  //Монстр?
  if ($Monster != 1)
  {
    //Сначала стираем все письма в ящике
    $dir = "data/mail/".$username; 
    while ($file = readdir($dir))
      unlink($dir."/".$file);
    closedir($dir);

	  //Стираем почту
  	rmdir($dir);

    //Количество удалённых +1
    $adm = getadmin();
    $btls = getfrom('admin', $adm, 'settings', 'f3');
    $btls++;
    setto('admin', $adm, 'settings', 'f3', $btls);

    //Стираем ЛОГ файл, ТОРГОВЫЙ файл и почту
	  unlink("data/logs/".$username.".log");
  	unlink("data/trade/".$username);
	  unlink("images/photos/".$username.".jpg");
  	unlink("images/photos/".$username.".gif");
  }
}

//Изменение данных в базе
function change($username, $table, $field, $value)
{
	mysql_query("update ".$table." set ".$field." = '".$value."' where login = '".$username."';");
}

//Добавляем способность
function addability($num, $name, $effect, $level1, $level2, $level3, $img, $desc1, $desc2, $desc3)
{
	mysql_query ("insert into additional values ('$num', '$name', '$effect', '$level1', '$level2', '$level3', '$img', '$desc1', '$desc2', '$desc3');");
}

//Добавляем замок
function addcastle($race, $build)
{
//	link();
	mysql_query ("insert into buildings values ('$race', '$build[0]', '$build[1]', '$build[2]', '$build[3]', '$build[4]', '$build[5]', '$build[6]', '$build[7]', '$build[8]', '$build[9]', '$build[10]', '$build[11]', '$build[12]', '$build[13]', '$build[14]', '$build[15]', '$build[16]', '$build[17]', '$build[18]', '$build[19]');");
}

//Добавляем вещь
function additem($num, $name, $action, $effect, $img, $cena, $type)
{
//	link();
	mysql_query ("insert into allitems values ('$num', '$name', '$action' , '$effect', '$img', '$cena', '$type');");
}

//Добавляем заклинание (type - combat, nature, mind)
function addcast($num, $name, $type, $action, $effect, $img, $cena)
{
//	link();
	mysql_query ("insert into allcasts values ('$num', '$name', '$type', '$action' , '$effect', '$img', '$cena');");
}

//Добавляем монстра
function addmonstr($name, $race, $art, $level, $health, $power, $protect)
{
//	link();
	mysql_query ("insert into monsters values ('$name', '$race', '$art', '$level' , '$health', '$power', '$protect');");
}

//Вывод списка всех пользователей
function makelist($login, $pass)
{
//	link();
	$ath = mysql_query('select * from users;');
	if ($ath)
	{
		echo("<form name='slct' action='game.php' method=post><select name='userlogin' style='background:#000099; font-WEIGHT: BOLD; COLOR: white; cursor: hand; list-style: lower-alpha; filter: Alpha(Opacity=20, FinishOpacity=100, Style=3)'>");
		while ($row = mysql_fetch_row($ath))
		{
			echo("<option name='".$row[0]."'>".$row[0]."</option>");
		}
		echo("</select><br><br><input type='submit' value='Смотреть' style='background:#000099; font-WEIGHT: BOLD; COLOR: white; cursor: hand; list-style: lower-alpha; filter: Alpha(Opacity=20, FinishOpacity=100, Style=3)'>");
		echo("<input type='hidden' name='action' value=17>");
		echo("<input type='hidden' name='al' value='$login'>");
		echo("<input type='hidden' name='do' value=1>");
		echo("<input type='hidden' name='ap' value='$pass'></form>");
	}

	$ath = mysql_query('select * from users;');
	if ($ath)
	{
		echo("<form name='act' action='game.php' method=post><select name='userlogin' style='background:#000099; font-WEIGHT: BOLD; COLOR: white; cursor: hand; list-style: lower-alpha; filter: Alpha(Opacity=20, FinishOpacity=100, Style=3)'>");
		while ($row = mysql_fetch_row($ath))
		{
			echo("<option name='".$row[0]."'>".$row[0]."</option>");
		}
		echo("</select><br><br><input type='submit' value='Удалить' style='background:#000099; font-WEIGHT: BOLD; COLOR: white; cursor: hand; list-style: lower-alpha; filter: Alpha(Opacity=20, FinishOpacity=100, Style=3)'>");
		echo("<input type='hidden' name='action' value=17>");
		echo("<input type='hidden' name='al' value='$login'>");
		echo("<input type='hidden' name='do' value=2>");
		echo("<input type='hidden' name='ap' value='$pass'></form>");
	}
}

//Генерировать список (имя, таблица, ряд)
function generate($name, $table, $row)
{
	$ath = mysql_query("select * from ".$table.";");
	if ($ath)
	{
		echo ("<select name='$name' style='background:#000099; font-WEIGHT: BOLD; COLOR: white; cursor: hand; list-style: lower-alpha; filter: Alpha(Opacity=20, FinishOpacity=100, Style=3)'>");
		while ($rw = mysql_fetch_row($ath))
      if ($rw[$row] != 'Bot')
  			echo("<option name='".$rw[$row]."'>".$rw[$row]."</option>");
		echo("</select>");
	}
}

//Генерировать список (имя, таблица, ряд)
function sortgenerate($name, $table, $row)
{
	$count=0;
	$ath = mysql_query("select * from ".$table.";");
	if ($ath)
	{
		while ($rw = mysql_fetch_row($ath))
		{
			$info[$count] = $rw[$row];
			$count++;
		}
	}

	//Сортировка
	@sort($info);
	echo ("<select name='$name' style='background:#000099; font-WEIGHT: BOLD; COLOR: white; cursor: hand; list-style: lower-alpha; filter: Alpha(Opacity=20, FinishOpacity=100, Style=3)'>");
	echo("<option name='Admin'>".getadmin()."</option>");
	for ($i = 0; $i < $count; $i++)
    if ($info[$i] != 'Bot')
  		echo("<option name='".$info[$i]."'>".$info[$i]."</option>");
	echo("</select>");

	//Ставим админа первым!
}

//Генерировать список (имя, таблица, ряд)
function gen2($name, $table, $row)
{
//	link();
	$ath = mysql_query("select * from ".$table.";");
	if ($ath)
	{
		echo ("<select name='$name' style='background:#000099; font-WEIGHT: BOLD; COLOR: white; cursor: hand; list-style: lower-alpha; filter: Alpha(Opacity=20, FinishOpacity=100, Style=3)'>");
		echo ("<option value='0'>Ничья</option>");
		while ($rw = mysql_fetch_row($ath))
		{
			echo("<option name='".$rw[$row]."'>".$rw[$row]."</option>");
		}
		echo("</select>");
	}
}

//Генерировать список (имя, таблица, ряд)
function activeusers($name)
{
//	link();
	$ath = mysql_query("select * from users;");
	if ($ath)
	{
		echo ("<select name='$name' size=22 style='background:#000099; font-WEIGHT: BOLD; COLOR: white; cursor: hand; list-style: lower-alpha; filter: Alpha(Opacity=20, FinishOpacity=100, Style=3)'>");
		while ($rw = mysql_fetch_row($ath))
		{
			if (getdata($rw[0], 'status', 'online') == 1)
			{
				echo("<option name='".$rw[0]."'>".getdata($rw[0],'hero', 'name')." [<font color=black>".getdata($rw[0], 'hero', 'level')."</font>]</option>");
			}
		}
		echo("</select>");
	}
}

//Создаём таблицу
function table($num, $login)
{
//	link();
	$ath = mysql_query("select * from allitems;");
	if ($ath)
	{
		while ($rw = mysql_fetch_row($ath))
		{
			if ($rw[6] == $num)
			{
				echo("<tr><td width = 25% align=center>".$rw[1]."<form action='armory.php' method=post><input type='hidden' name='action' value=3><input type='hidden' name='item' value='".$rw[1]."'><input type='hidden' name='login' value='".$login."'><input type='submit' value='Купить'  style='background:#000099; font-WEIGHT: BOLD; COLOR: white; cursor: hand; list-style: lower-alpha; filter: Alpha(Opacity=20, FinishOpacity=100, Style=3)'></form></td><td width=15%><img src='".getimg($rw[0])."'></td><td>".getinfo($rw[0])."</td><td align=center>".(200*getfrom('num', $rw[0], 'allitems', 'cena'))*getdata($login, 'economic', 'curse')."</td></tr>");
			}
		}
	}
}

//Создаём таблицу
function ctable($num, $login)
{
//	link();
	$ath = mysql_query("select * from allcasts;");
	if ($ath)
	{
		while ($rw = mysql_fetch_row($ath))
		{
			if ($rw[2] == $num)
			{
			  $mana = getfrom('num', $rw[0], 'allcasts', 'cena'); 
				echo("<tr><td width = 25% align=center>".$rw[1]."<form action='guild.php' method=post><input type='hidden' name='action' value=3><input type='hidden' name='item' value='".$rw[1]."'><input type='hidden' name='login' value='".$login."'><input type='submit' value='Купить'  style='background:#000099; font-WEIGHT: BOLD; COLOR: white; cursor: hand; list-style: lower-alpha; filter: Alpha(Opacity=20, FinishOpacity=100, Style=3)'></form></td><td width=15%><img src='images/cast/".$rw[5]."'></td><td>".getcinfo($rw[0])."<br>Необходимо $mana маны</td><td align=center>".getfrom('num', $rw[0], 'allcasts', 'cena')*15*6*getdata($login, 'economic', 'curse')."</td></tr>");
			}
		}
	}
}

//Список всех зданий
function blist($name, $race, $login)
{
	echo ("<select name='$name' style='background:#000099; font-WEIGHT: BOLD; COLOR: white; cursor: hand; list-style: lower-alpha; filter: Alpha(Opacity=20, FinishOpacity=100, Style=3)'>");
	if (getdata($login, 'city', 'build1') == 0)
	{
	echo ("<option value='1'>".getfrom('race', $race, 'buildings', 'build1')."</option>");
	}
	if (getdata($login, 'city', 'build2') == 0)
	{
	echo ("<option value='2'>".getfrom('race', $race, 'buildings', 'build2')."</option>");
	}
	if (getdata($login, 'city', 'build3') == 0)
	{
	echo ("<option value='3'>".getfrom('race', $race, 'buildings', 'build3')."</option>");
	}
	if (getdata($login, 'city', 'build4') == 0)
	{
	echo ("<option value='4'>".getfrom('race', $race, 'buildings', 'build4')."</option>");
	}
	if (getdata($login, 'city', 'build5') == 0)
	{
	echo ("<option value='5'>".getfrom('race', $race, 'buildings', 'build5')."</option>");
	}
	if (getdata($login, 'city', 'build6') == 0)
	{
	echo ("<option value='6'>".getfrom('race', $race, 'buildings', 'build6')."</option>");
	}
	if (getdata($login, 'city', 'build7') == 0)
	{
	echo ("<option value='7'>".getfrom('race', $race, 'buildings', 'build7')."</option>");
	}
	if (getdata($login, 'city', 'build8') == 0)
	{
	echo ("<option value='8'>".getfrom('race', $race, 'buildings', 'build8')."</option>");
	}
	if (getdata($login, 'city', 'build9') == 0)
	{
	echo ("<option value='9'>".getfrom('race', $race, 'buildings', 'build9')."</option>");
	}
	if (getdata($login, 'city', 'build10') == 0)
	{
	echo ("<option value='10'>".getfrom('race', $race, 'buildings', 'build10')."</option>");
	}
	if (getdata($login, 'city', 'build11') == 0)
	{
	echo ("<option value='11'>".getfrom('race', $race, 'buildings', 'build11')."</option>");
	}
	if (getdata($login, 'city', 'build12') == 0)
	{
	echo ("<option value='12'>".getfrom('race', $race, 'buildings', 'build12')."</option>");
	}
	if (getdata($login, 'city', 'build13') == 0)
	{
	echo ("<option value='13'>".getfrom('race', $race, 'buildings', 'build13')."</option>");
	}
	echo ("</select>");
}


//Вывод списка всех пользователей
function userlist($name)
{
	generate($name, 'users', 0);
}

//Вывод списка всех пользователей
function indexuserlist($name)
{
	sortgenerate($name, 'users', 0);
}

//Вывод списка всех пользователей
function user2($name)
{
	gen2($name, 'users', 0);
}

//Статус
function showstatus($login)
{
  $sec = time()-1093941325;
  $days = $sec / 24;
  $days = round($days / 3600);
  $data = "Дней прошло: ".$days;

  //Определяем сколько лет...
  $year = 1;
  while ($days > 336)
  {
    $days = $days - 336;
    $year++;
  }

  //Определяем месяц
  $mounth = 0;
  while ($days > 28)
  {
    $days = $days - 28;
    $mounth++;
  }

  //Теперь получаем название месяца
  switch($mounth)
  {
    case 0:
      $mnt = "монопиона";
      break;
    case 1:
      $mnt = "дипиона";
      break;
    case 2:
      $mnt = "трипиона";
      break;
    case 3:
      $mnt = "тетрапиона";
      break;
    case 4:
      $mnt = "пентабря";
      break;
    case 5:
      $mnt = "гексабря";
      break;
    case 6:
      $mnt = "гектабря";
      break;
    case 7:
      $mnt = "октобря";
      break;
    case 8:
      $mnt = "нонабря";
      break;
    case 9:
      $mnt = "декабря";
      break;
    case 10:
      $mnt = "ундекабря";
      break;
    case 11:
      $mnt = "додекабря";
      break; 
  }
  $data = $days." ".$mnt." ".$year." года";

	echo ("<center><font color=blue>");
	echo ("<TABLE BORDER=0 CELLSPACING=0 CELLPADDING=0><tr>");
	echo ("<td valign=top><img src=images/menu/gold.gif alt='".getdata($login, 'economic', 'moneyname')."'></td><td valign=center>".getdata($login, 'economic', 'money')."</td><td valign=top><img src=images/menu/metal.gif alt='Металл'></td><td valign=center>".getdata($login, 'economic', 'metal')."</td><td valign=top><img src=images/menu/rock.gif alt='Камень'></td><td valign=center>".getdata($login, 'economic', 'rock')."</td><td valign=top><img src=images/menu/wood.gif alt='Дерево'></td><td valign=center>".getdata($login, 'economic', 'wood')."</td>");
	echo ("</tr>");
  echo("<tr><td colspan=8 align=center>$data</td></tr>");
  echo("</TABLE>");
  echo ("</font></center>");
  return $days;
}

//Конвертирование
function conv($in)
{
	switch($in)
	{
		case "people":
			$out = "Человек";
			break;
		case "elf":
			$out = "Эльф";
			break;
		case "hnom":
			$out = "Гном";
			break;
		case "druid":
			$out = "Друид";
			break;
		case "necro":
			$out = "Некромант";
			break;
		case "hell":
			$out = "Еретик";
			break;
		case "knight":
			$out = "Рыцарь";
			break;
		case "archer":
			$out = "Стрелок";
			break;
		case "mag":
			$out = "Маг";
			break;
		case "lekar":
			$out = "Лекарь";
			break;
		case "barbarian":
			$out = "Варвар";
			break;
		case "wizard":
			$out = "Волшебник";
			break;
	}
	return $out;
}

//Получение таблицы характеристик пользователя
function infoof($login)
{
	$level = getdata($login, 'hero', 'level');
  $health = getdata($login, 'hero', 'health');
  $how = 300*($level)*($level);
  $hprc = $health / $level;
	if ($health < getdata($login, 'hero', 'level')*10)
		{
		$col = 'red';
		}
		else
		{
			$col = 'blue';
		}
	echo ("<center><table border=1 width=60% align=center CELLSPACING=0 CELLPADDING=0>");
	echo ("<tr><td align=center colspan=2><font color=blue><b>Информация о персонаже</b></font></td></tr>");
	echo ("<tr><td align=center width=60%><font color=blue>Параметр</font></td><td align=center><font color=blue>Значение</font></td></tr>");
	echo ("<tr><td align=center><font color=blue>Имя</font></td><td align=center><font color=blue>".getdata($login, 'hero', 'name')."</font></td></tr>");
	echo ("<tr><td align=center><font color=blue>Раса</font></td><td align=center><font color=blue>".conv(getdata($login, 'hero', 'race'))."</font></td></tr>");
	echo ("<tr><td align=center><font color=blue>Тип</font></td><td align=center><font color=blue>".conv(getdata($login, 'hero', 'type'))."</font></td></tr>");
	echo ("<tr><td align=center><font color=".$col.">Здоровье</font></td><td align=center><font color=".$col.">".$health."</font></td></tr>");
	echo ("<tr><td align=center><font color=blue>Атака</font></td><td align=center><font color=blue>".getdata($login, 'abilities', 'power')."</font></td></tr>");
	echo ("<tr><td align=center><font color=blue>Защита</font></td><td align=center><font color=blue>".getdata($login, 'abilities', 'protect')."</font></td></tr>");
	echo ("<tr><td align=center><font color=blue>Защита от магии</font></td><td align=center><font color=blue>".getdata($login, 'abilities', 'dexterity')."</font></td></tr>");
	echo ("<tr><td align=center><font color=blue>Знания</font></td><td align=center><font color=blue>".getdata($login, 'abilities', 'cnowledge')."</font></td></tr>");
	echo ("<tr><td align=center><font color=blue>Выносливость</font></td><td align=center><font color=blue>".getdata($login, 'abilities', 'charism')."</font></td></tr>");
	echo ("<tr><td align=center><font color=blue>Мана</font></td><td align=center><font color=blue>".getdata($login, 'abilities', 'intellegence')."</font></td></tr>");
	echo ("<tr><td align=center><font color=blue>Колдовская сила</font></td><td align=center><font color=blue>".getdata($login, 'abilities', 'naturemagic')."</font></td></tr>");
	echo ("<tr><td align=center><font color=blue>Уровень</font></td><td align=center><font color=blue>".getdata($login, 'hero', 'level')."</font></td></tr>");
	echo ("<tr><td align=center><font color=blue>Количество боёв</font></td><td align=center><font color=blue>".getdata($login, 'time', 'combats')."</font></td></tr>");
	echo ("</table></center>");

	echo ("<center><table border=1 width=60% align=center CELLSPACING=0 CELLPADDING=0>");
	echo ("<tr><td align=center colspan=2><font color=blue><b>Информация об игроке</b></font></td></tr>");
	echo ("<tr><td align=center><font color=blue>Фамилия</font></td><td align=center><font color=blue>".getdata($login, 'users', 'surname')."</font></td></tr>");
	echo ("<tr><td align=center><font color=blue>Имя</font></td><td align=center><font color=blue>".getdata($login, 'users', 'name')."</font></td></tr>");
	echo ("<tr><td align=center><font color=blue>Страна</font></td><td align=center><font color=blue>".getdata($login, 'users', 'country')."</font></td></tr>");
	echo ("<tr><td align=center><font color=blue>Город</font></td><td align=center><font color=blue>".getdata($login, 'users', 'city')."</font></td></tr>");
	echo ("<tr><td align=center><font color=blue>Почта</font></td><td align=center><font color=blue>".getdata($login, 'users', 'email')."</font></td></tr>");
	echo ("<tr><td align=center><font color=blue>ICQ</font></td><td align=center><font color=blue>".getdata($login, 'inf', 'icq')."</font></td></tr>");
	echo ("<tr><td align=center><font color=blue>URL</font></td><td align=center><font color=blue>".getdata($login, 'users', 'url')."</font></td></tr>");
	echo ("<tr><td align=center><font color=blue>О себе</font></td><td align=center><font color=blue>".getdata($login, 'inf', 'about')."</font></td></tr>");
	echo ("</table></center>");
}

//Расчёт атаки
function Strike($Login)
{
  //Базовый урон - сила игрока
  $Damage = getdata($Login, 'abilities', 'power');

  //Номер оружия в руке
  (int)$Num = getdata($Login, 'items', 'rightruka');

  //Дополнительный урон (от оружия в руке)
  $Procent = getfrom('num', $Num, 'allitems', 'effect');

  //Расчёт дополнительного урона
  $AddOn = ($Damage / 100) * $Procent;

  //Пересчитываем конечные повреждения
  $Damage = $Damage + $AddOn;

  //Возвращаем значение урона
  return $Damage;
}

//Расчёт защиты
function Protection($Login)
{
  //Базовый урон - сила игрока
  $Damage = getdata($Login, 'abilities', 'protect');

  //Защита от щита
    //Номер оружия в левой руке
    (int)$Num = getdata($Login, 'items', 'leftruka');

    //Если конечно прибавка к защите
    $Verb = getfrom('num', $Num, 'allitems', 'action');
    $AddOn = 0;
    if ($Verb == 2)
    {
      //Дополнительный урон (от оружия в руке)
      $Procent = getfrom('num', $Num, 'allitems', 'effect');

      //Расчёт дополнительного урона
      $AddOn = ($Damage / 100) * $Procent;
    }

  //Защита от брони
    //Номер оружия на теле
    (int)$Num = getdata($Login, 'items', 'telo');

    //Если конечно прибавка к защите
    $Verb = getfrom('num', $Num, 'allitems', 'action');
    $Armor = 0;
    if ($Verb == 2)
    {
      //Дополнительный урон (от оружия в руке)
      $Procent = getfrom('num', $Num, 'allitems', 'effect');

      //Расчёт дополнительного урона
      $Armor = ($Damage / 100) * $Procent;
    }

  //Защита от шлема
    //Номер оружия на голове
    (int)$Num = getdata($Login, 'items', 'golova');

    //Если конечно прибавка к защите
    $Verb = getfrom('num', $Num, 'allitems', 'action');
    $Head = 0;
    if ($Verb == 2)
    {
      //Дополнительный урон (от оружия в руке)
      $Procent = getfrom('num', $Num, 'allitems', 'effect');

      //Расчёт дополнительного урона
      $Head = ($Damage / 100) * $Procent;
    }

  //Защита от пояса
    //Номер оружия на теле
    (int)$Num = getdata($Login, 'items', 'tors');

    //Если конечно прибавка к защите
    $Verb = getfrom('num', $Num, 'allitems', 'action');
    $Tors = 0;
    if ($Verb == 2)
    {
      //Дополнительный урон (от оружия в руке)
      $Procent = getfrom('num', $Num, 'allitems', 'effect');

      //Расчёт дополнительного урона
      $Tors = ($Damage / 100) * $Procent;
    }

  //Защита от щитков на коленях
    //Номер оружия на коленях
    (int)$Num = getdata($Login, 'items', 'koleni');

    //Если конечно прибавка к защите
    $Verb = getfrom('num', $Num, 'allitems', 'action');
    $Koleni = 0;
    if ($Verb == 2)
    {
      //Дополнительный урон (от оружия в руке)
      $Procent = getfrom('num', $Num, 'allitems', 'effect');

      //Расчёт дополнительного урона
      $Koleni = ($Damage / 100) * $Procent;
    }

  //Защита от ног
    //Номер оружия на ногах
    (int)$Num = getdata($Login, 'items', 'nogi');

    //Если конечно прибавка к защите
    $Verb = getfrom('num', $Num, 'allitems', 'action');
    $Nogi = 0;
    if ($Verb == 2)
    {
      //Дополнительный урон (от оружия в руке)
      $Procent = getfrom('num', $Num, 'allitems', 'effect');

      //Расчёт дополнительного урона
      $Nogi = ($Damage / 100) * $Procent;
    }

  //Защита от плаща
    //Номер оружия на теле
    (int)$Num = getdata($Login, 'items', 'plash');

    //Если конечно прибавка к защите
    $Verb = getfrom('num', $Num, 'allitems', 'action');
    $Plash = 0;
    if ($Verb == 2)
    {
      //Дополнительный урон (от оружия в руке)
      $Procent = getfrom('num', $Num, 'allitems', 'effect');

      //Расчёт дополнительного урона
      $Plash = ($Damage / 100) * $Procent;
    }

  //Пересчитываем конечные повреждения
  $Damage = $Damage + $Armor + $Tors + $Head + $Koleni + $Nogi + $Plash + +$AddOn;

  //Возвращаем значение урона
  return $Damage;
}

//Получение таблицы характеристик пользователя
function heroinfo($login)
{
	wnd();
	$level = getdata($login, 'hero', 'level');
	$how = round(60*pow($level, 1.4));
	$mx = $level*100;
	$md = $mx / 10;
  $expa = getdata($login, 'hero', 'expa');

	//Полная атака
	$full = getdata($login, 'abilities', 'power')+rand(1, 0)+1;
	(int)$num = getdata($login, 'items', 'rightruka');
	$weapon = forbattle($num, 1)*getdata($login, 'abilities', 'power');
	$full = $full + $weapon;
  $full = Strike($login);
  $prot = Protection($login);

  //Сколько процентов до пред уровня
  $pre = round(60*pow(($level-1), 1.4));
  if ($pre < 0)
    $pre = 0;

  //Переводим к началу
  $temp = $expa - $pre;
  $next = $how  - $pre;

  //А сколько сейчас у нас экспы
  $howmany = round($temp*100/$next);
  if ($howmany < 0)
    $howmany = 0;
  if ($howmany > 100)
    $howmany = 100;

  //Если экспы 0, то...
  if ($expa == 0)
    $howmany = 0;

	//Как здоровье	
	if (getdata($login, 'hero', 'health') < getdata($login, 'hero', 'level')*10)
		{
		$col = 'red';
		}
		else
		{
			$col = 'blue';
		}

	$ph = getdata($login, 'inf', 'fld1');
	if ($ph == '0')
	{
		$ph = $ph.".jpg";
	}
  $health = getdata($login, 'hero', 'health');
  $hprc = round($health / $level);

  $mana = getdata($login, 'abilities', 'intellegence');
  $cnow = getdata($login, 'abilities', 'cnowledge');
  if ($cnow != 0)
    $mprc = round(10*$mana / $cnow);
  else
    $mprc = 0;
  
  //Вес и загрузка
  $weight = getdata($login, 'status', 'timeout');
  $max = getdata($login, 'abilities', 'charism')*2;
  if ($max == 0)
	  $max = 1;
  $vprc = round(100*$weight/$max);
  if ($vprc > 100)
    $vprc = 100;

  //Java функция
  echo("<script>");
  echo("function newchar()");
	echo("{");
	echo("	window.open('newchar.php?login=".$login."', null,'toolbar=no, location=no, menubar=no,scrollbars=yes,width=270,height=270');");
	echo("}");
	echo("</script>");

  //Показываем остальное
	if (getdata($login, 'hero', 'upgrade') != 0)
	{
	echo ("<center><table border=1 width=60% align=center CELLSPACING=0 CELLPADDING=0>");
	echo ("<tr><td align=center colspan=3><font color=blue><b>Информация о Вашем персонаже</b></font> <a href=javascript:wnd('up.php?login=".$login."')>(ФОТО)</a></td></tr>");
	echo ("<tr><td align=center width=60%><font color=blue>Параметр</font></td><td align=center><font color=blue>Значение</font></td><td align=center><font color=blue>Повысить</font></td></tr>");
	echo ("<tr><td align=center><font color=blue>Имя</font></td><td align=center colspan=2><font color=blue>".getdata($login, 'hero', 'name')."</font></td></tr>");
	echo ("<tr><td align=center><font color=blue>Раса</font></td><td align=center colspan=2><font color=blue>".conv(getdata($login, 'hero', 'race'))."</font></td></tr>");
	echo ("<tr><td align=center><font color=blue>Тип</font></td><td align=center colspan=2><font color=blue>".conv(getdata($login, 'hero', 'type'))."</font></td></tr>");
	echo ("<tr><td align=center><font color=".$col.">Здоровье</font></td><td align=center colspan=2><font color=".$col.">".$health." из ".$mx."</font>");
  PBar($hprc, 'red');
  echo("</td></tr>");
	echo ("<tr><td align=center><font color=blue>Мана</font></td><td align=center colspan=2><font color=blue>".$mana."</font>");
  PBar($mprc, 'blue');
  echo("</td></td></tr>");
  echo ("<tr><td align=center><font color=blue>Атака</font></td><td align=center><font color=blue>".getdata($login, 'abilities', 'power')." (".$full.")</font></td><td align=center><a href='game.php?action=20'>+</a> (<a href='game.php?action=69'>*</a>)</td></tr>");
	echo ("<tr><td align=center><font color=blue>Защита</font></td><td align=center><font color=blue>".getdata($login, 'abilities', 'protect')." (".$prot.")</font></td><td align=center><a href='game.php?action=21'>+</a> (<a href='game.php?action=70'>*</a>)</td></tr>");
	echo ("<tr><td align=center><font color=blue>Защита от магии</font></td><td align=center><font color=blue>".getdata($login, 'abilities', 'dexterity')."</font></td><td align=center><a href='game.php?action=22'>+</a> (<a href='game.php?action=71'>*</a>)</td></tr>");
	echo ("<tr><td align=center><font color=blue>Знания</font></td><td align=center><font color=blue>".$cnow."</font></td><td align=center><a href='game.php?action=23'>+</a> (<a href='game.php?action=72'>*</a>)</td></tr>");
	echo ("<tr><td align=center><font color=blue>Выносливость</font></td><td align=center><font color=blue>".getdata($login, 'abilities', 'charism')."</font></td>");
  echo("</td><td align=center><a href='game.php?action=24'>+</a> (<a href='game.php?action=73'>*</a>)</td></tr>");
	echo ("<tr><td align=center><font color=blue>Загрузка персонажа</font></td><td align=center colspan=2>");
  PBar($vprc, 'lightblue');
  echo("</td></tr>");
  echo ("<tr><td align=center><font color=blue>Колдовская сила</font></td><td align=center><font color=blue>".getdata($login, 'abilities', 'naturemagic')."</font></td><td align=center><a href='game.php?action=27'>+</a> (<a href='game.php?action=76'>*</a>)</td></tr>");
	echo ("<tr><td align=center><font color=blue>Уровень</font></td><td align=center colspan=2><font color=blue>".getdata($login, 'hero', 'level')."</font></td></tr>");
	echo ("<tr><td align=center><font color=blue>Возможных увеличений</font></td><td align=center colspan=2><font color=blue>".getdata($login, 'hero', 'upgrade')."</font></td></tr>");
	echo ("<tr><td align=center><font color=blue>Опыт</font></td><td align=center colspan=2><font color=blue>".$expa."</font></td></tr>");
  echo ("<tr><td align=center><font color=blue>Следующий уровень</font></td><td align=center colspan=2><font color=blue>".$how."</font></td></tr>");
  echo ("<tr><td align=center><font color=blue>Очков действия</font></td><td align=center colspan=2><font color=blue>".getdata($login, 'inf', 'def')."</font></td></tr>");
	} else
	{
	echo ("<center><table border=0 width=70% CELLSPACING=0 CELLPADDING=0><tr><td align=center><table border=1 width=100% align=center CELLSPACING=0 CELLPADDING=0>");
	echo ("<tr><td align=center colspan=2><font color=blue><b>Информация о Вашем персонаже</b></font> <a href=javascript:wnd('up.php?login=".$login."')>(ФОТО)</a></td></tr>");
	echo ("<tr><td align=center width=60%><font color=blue>Параметр</font></td><td align=center><font color=blue>Значение</font></td></tr>");
	echo ("<tr><td align=center><font color=blue>Имя</font></td><td align=center><font color=blue>".getdata($login, 'hero', 'name')."</font></td></tr>");
	echo ("<tr><td align=center><font color=blue>Раса</font></td><td align=center><font color=blue>".conv(getdata($login, 'hero', 'race'))."</font></td></tr>");
	echo ("<tr><td align=center><font color=blue>Тип</font></td><td align=center><font color=blue>".conv(getdata($login, 'hero', 'type'))."</font></td></tr>");
	echo ("<tr><td align=center><font color=".$col.">Здоровье</font></td><td align=center><font color=".$col.">".$health." из ".$mx."</font>");
  PBar($hprc, 'red');
  echo("</td></tr>");
	echo ("<tr><td align=center><font color=blue>Мана</font></td><td align=center><font color=blue>".$mana."</font>");
  PBar($mprc, 'blue');
  echo("</td></tr>");
	echo ("<tr><td align=center><font color=blue>Атака</font></td><td align=center><font color=blue>".getdata($login, 'abilities', 'power')." (".$full.")</font></td></tr>");
	echo ("<tr><td align=center><font color=blue>Защита</font></td><td align=center><font color=blue>".getdata($login, 'abilities', 'protect')." (".$prot.")</font></td></tr>");
	echo ("<tr><td align=center><font color=blue>Защита от магии</font></td><td align=center><font color=blue>".getdata($login, 'abilities', 'dexterity')."</font></td></tr>");
	echo ("<tr><td align=center><font color=blue>Знания</font></td><td align=center><font color=blue>".$cnow."</font></td></tr>");
	echo ("<tr><td align=center><font color=blue>Выносливость</font></td><td align=center><font color=blue>".getdata($login, 'abilities', 'charism')."</font></td></tr>");
	echo ("<tr><td align=center><font color=blue>Загрузка персонажа</font></td><td align=center>");
  PBar($vprc, 'lightblue');
  echo("</td></tr>");
	echo ("<tr><td align=center><font color=blue>Колдовская сила</font></td><td align=center><font color=blue>".getdata($login, 'abilities', 'naturemagic')."</font></td></tr>");
	echo ("<tr><td align=center><font color=blue>Уровень</font></td><td align=center><font color=blue>".getdata($login, 'hero', 'level')."</font></td></tr>");
	echo ("<tr><td align=center><font color=blue>Возможных увеличений</font></td><td align=center><font color=blue>".getdata($login, 'hero', 'upgrade')."</font></td></tr>");
	echo ("<tr><td align=center><font color=blue>Опыт</font></td><td align=center><font color=blue>".$expa."</font></td></tr>");
	echo ("<tr><td align=center><font color=blue>Следующий уровень</font></td><td align=center><font color=blue>".$how."</font></td></tr>");
  echo ("<tr><td align=center><font color=blue>Очков действия</font></td><td align=center><font color=blue>".getdata($login, 'inf', 'def')."</font></td></tr>");
	echo ("</table></td><td align=center><img width=150 height=200 src='images/photos/".$ph."'>");
  HelpMe(1, 1);
  echo("</td></tr>");
	}

  //Вырезает из строки первый символ
  function tempcut($s)
  {
    $ns = "";
    for ($i = 1; $i < strlen($s); $i++)
      $ns = $ns.$s[$i];
    return $ns;
  }

  //Отображение доп. способности
  function Ablt($Login, $Number)
  {
    //Очередная ячейка
    $Txt = "<td align=center width=25%>";

    //Получаем доп. способность из данной клетки
    $num = getdata($Login, 'newchar', 'achar'.$Number);
    $lvl = $num[0];
    $num = tempcut($num);

    //Есть ли способность?
    if ($lvl != '0')
    {
      //На каком она уроне
      switch ($lvl)
      {
        case 'N':
          $alevel = 1;
          $tlevel = "Новичок ";
          break;
        case 'A':
          $alevel = 2;
          $tlevel = "Продвинутый ";
          break;
        case 'E':
          $alevel = 3;
          $tlevel = "Эксперт ";
          break;
      }

      //Достаём картинку
      $img = getfrom('num', $num, 'additional', 'img');
      $img = "images/newchar/".$img."/".$alevel.".jpg";

      //Достаём описание
      $desc = getfrom('num', $num, 'additional', 'desc'.$alevel);
      $name = getfrom('num', $num, 'additional', 'name');

      //Компануем подсказку
      $tlevel = $tlevel.$name.". ".$desc;

      //Компануем ячейку
      $Txt = $Txt."<img src='$img' alt='$tlevel'>";
    }
      else
    {
        $Txt = $Txt."<img src='images/empty.jpg'>";
    }

    //Возврат значения
    $Txt = $Txt."</td>";
    return $Txt;
  }

  //Выводим доп. характеристики
  echo("<tr><td align=center>");
  echo("<center><table border=1 width=10% CELLSPACING=0 CELLPADDING=0>");
  echo("<tr><td colspan=4 align=center>Дополнительные способности</td></tr>");
  echo("<tr>");
  for ($i = 1; $i <= 4; $i++)
    echo(Ablt($login, $i));
  echo("</tr>");
  echo("<tr>");
  for ($i = 5; $i <= 8; $i++)
    echo(Ablt($login, $i));
  echo("</tr>");
  echo("<tr>");
  for ($i = 9; $i <= 12; $i++)
    echo(Ablt($login, $i));
  echo("</tr>");
  echo("<tr>");
  for ($i = 13; $i <= 16; $i++)
    echo(Ablt($login, $i));
  echo("</tr>");
  echo("<tr><td align=center colspan=4>Следующий уровень:");
  progress($howmany);
  echo("</td></tr>");
  echo("</table>");
  echo("</td><td colspan=2>&nbsp;</td></tr></table></center>");
}

//Проверка на пустые замки
function isempty()
{
	//Массив карты
	$map[15][15] = 0;

	//Флаг перезаписи
	$rw = 0;

	//Читаем все файлы в массив
	for ($i = 1; $i < 21; $i++)
	{
		for ($j = 1; $j < 21; $j++)
		{
			//Определяем имя файла
			$rx = $i;
			$ry = $j;
			$file = fopen("maps/".$rx."x".$ry.".map", "r");
			$rw = 0;

			//Карта
			for ($x = 1; $x < 11; $x++)
			{
				//Новая ячейка таблицы
				for ($y = 1; $y < 11; $y++)
				{
					//Получаем данные из ячейки
					$fld = fgets($file, 255);
					$map[$x][$y] = $fld;

					//Чья клетка?
					$t = trim(substr($fld, 4));
	
					//Пусто или нет?
					if (!empty($t))
					{
						//Если кто-то есть
						if ($t != '0')
						{
							//Если пользователь не существует, то удаляем замок
							if (hasuser($t) == 0)
							{
								$s = $fld[0].$fld[1].$fld[2].$fld[3]."0\n";
								$map[$x][$y] = $s;
								$rw = 1;
								echo("Изменить: ".$i."x".$j." | ".$x."x".$y."<br>");
							}
						}
					}

				//Завершаем $y цикл
				}
			//Завершаем $x цикл
			}

			//Закрываем файл
			fclose($file);

			if ($rw == 1)
			{
				//Переписываем карту
				$file = fopen("maps/".$rx."x".$ry.".map", "w");
				for ($x = 1; $x < 11; $x++)
				{
					//Новая ячейка таблицы
					for ($y = 1; $y < 11; $y++)
					{
						fputs($file, $map[$x][$y]);
					}
				}
				fclose($file);
			}
		}
	}
}

//Получение данных из конфига
function getconf($type)
{
	$file = fopen("config.ini.php");
	$temp = fgets($file, 255);	
	$temp = fgets($file, 255);	
	$host = fgets($file, 255);	
	$base = fgets($file, 255);	
	$name = fgets($file, 255);	
	$pass = fgets($file, 255);	
	if (strtolower($type) == 'host')
		return $host;
	if (strtolower($type) == 'base')
		return $base;
	if (strtolower($type) == 'name')
		return $name;
	if (strtolower($type) == 'pass')
		return $pass;
	fclose($file);
}

//Стабилизация БД
function stabilization()
{

	//Объявляем глобальные переменные
	$tcount = 21;
	$tables[1] = 'abilities';
  $tables[2] = 'army';
  $tables[3] = 'battles';
	$tables[4] = 'city';
  $tables[5] = 'capital';
  $tables[6] = 'coords';
	$tables[7] = 'economic';
	$tables[8] = 'hero';
	$tables[9] = 'inf';
	$tables[10] = 'info';
	$tables[11] = 'ip';
	$tables[12] = 'items';
	$tables[13] = 'lostpass';
	$tables[14] = 'magic';
	$tables[15] = 'status';
	$tables[16] = 'temp';
	$tables[17] = 'time';
	$tables[18] = 'unions';
  $tables[19] = 'battle';
  $tables[20] = 'bottles';
  $tables[21] = 'newchar';

	//Получаем список пользователей
	$usr = mysql_query("select * from users;");
  $count = 0;
  $find = "";
	if ($usr)
	{
    //Выводим список всех пользователей
    while ($user = mysql_fetch_array($usr))
		{
			$logins[$count] = $user['login'];
      echo ($count+1).") ".$logins[$count]."<br>\n";
			$count++;
		} //$user
	} //$usr

	//Получаем имя базы из конфига
	for ($i = 1; $i <= $tcount; $i++)
	{
    //Сколько человек в базе
    $ts = mysql_query("select count(*) from ".$tables[$i].";");
    $cn = mysql_fetch_array($ts);
    $nm = $cn[0];

		//Получаем список пользователей
		echo("Чистка: <b>".$tables[$i]."</b><br>\n");
    echo("Пользователей: ".$nm."<br>\n");

    //Нужна ли чистка
    if ($nm > $count)
    {
      echo("Необходима чистка<br>\n");
  
      //Выбираем всех пользователей в таблице
      $sr = mysql_query("select * from ".$tables[$i].";");
	  	if ($sr)
  		{
        //Для каждого из них
	  		while ($ser = mysql_fetch_array($sr))
		  	{
			  	//Обнуляем счётчик
	  			$f = 0;

		  		//Ищём пользователя в списке зарегистрированных в базе users
			  	for ($k = 0; $k < $count; $k++) 
  				{
	  				//Найден?
		  			if ($ser['login'] == $logins[$k])
			  			$f = 1;
  				}

	  			//Если не найден, то удаляем нафиг
		  		if ($f == 0)
			  	{
					  $username = $ser['login'];
  					mysql_query("delete from ".$tables[$i]." where login = '".$username."';");
            echo("<dd>Стереть пользователя ".$username."<br>\n");
	  				$n++;
		  		} //$f
			  } //$ser
  		} //$sr

      //Сообщаем результат об очистке таблицы
  		echo("<b><font color=blue>Чистка таблицы завершена</font></b><br>\n");
    } //Нужна чистка
	} //for
} //Stabilization

//Администрирование
function admin($login, $pass, $who)
{
	echo("<center><h2>Администрирование</h2>");
	echo("<table border=1 width=90% CELLSPACING=0 CELLPADDING=0>");
	echo("<tr><td align=center width=25%>Пользователь</td><td align=center>Дополнительная информация</td><tr>");
	echo("<tr><td align=center>");
	makelist($login, $pass);
	echo("<br><a href='javascript:upload();'>Загрузка файлов</a>");	
	echo("<br><a href='fullmap.php'>Карта материка</a>");	
	echo("<br><a href='game.php?action=67'>Добавить 1000 монстров</a>");	
	echo("<br><a href='game.php?action=65'>Чистка базы</a>");
	echo("<br><a href='game.php?action=66'>Удаление свободных замков</a>");
	echo("<br><a href='game.php?action=68'>Стабилизация базы</a>");
	echo("<br><a href='game.php?action=78'>Перегруппировка замков</a>");
	echo("<br><a href='game.php?action=79'>Разослать всем сообщение</a>");
  echo("<br><a href='statistic.php'>Игровая статистика</a>");
	echo("</td><td>");
	heroinfo($who);
	echo ("</td></tr></table>");
}

//Получение логина из cookies
function getlogin()
{
	return trim($HTTP_COOKIE_VARS["nativeland"]);
}

//Может у меня есть почта?
function hasmail($login)
{
	$has = 0;
	if ($file = fopen("data/mail/".$login, "r"))
	{
		$rd = fgets($file, 255);
		if (!empty($rd))
		{
			$has = 1;
		}
		fclose ($file);
	}
	return $has;
}


//Меню слева
function showmenu($login, $name)
{
	?>
	<script language=JavaScript>
    function help()
    {
			window.open("help.php",2, "toolbar=no, location=no, menubar=no, scrollbars=yes, width=" + (screen.width-10) + ", height=" + (screen.height-20) + ", resizable=yes, left=0, top=0");
    }
		function forum()
		{
			window.open("forums/",2, "toolbar=no, location=no, menubar=no, scrollbars=yes, width=" + (screen.width-10) + ", height=" + (screen.height-20) + ", resizable=yes, left=0, top=0");
		}
		function money()
		{
			window.open("money.php",10,"toolbar=no, location=no, menubar=no,scrollbars=yes,width=540, height=300");
		}
		function news(log)
		{
			window.open("news.php?login=" + log,4,"toolbar=no, location=no, menubar=no,scrollbars=yes,width=540, height=300");
		}
		function mail(log)
		{
			window.open("mail.php?login=" + log,9,"toolbar=no, location=no, menubar=no,scrollbars=yes,width=540, height=300");
		}
		function chat()
		{
			window.open("chat.php",1,"toolbar=no, location=no, menubar=no,scrollbars=no,width=540, height=300");
		}
		function upload()
		{
			window.open("fileup.php",3,"toolbar=yes, location=yes, menubar=yes,scrollbars=yes");
		}
		function cheats()
		{
			window.open("cheats.php",5,"toolbar=no, location=no, menubar=no,scrollbars=yes,width=540, height=300");
		}
		function music()
		{
			window.open("player.php?track=0",6,"toolbar=no, location=no, menubar=no,scrollbars=no,width=40, height=40");
		}
		function pltop()
		{
			window.open("top.php",7,"toolbar=no, location=no, menubar=no,scrollbars=yes,width=600, height=400");
		}
		function map()
		{
			window.open("viewmap.php",8,"toolbar=no, location=no, menubar=no,scrollbars=no,width=440, height=440");
		}
	</script>
	<?
	$rx = getdata ($login, 'coords', 'rx');
	$ry = getdata ($login, 'coords', 'ry');
  $status = getdata ($login, 'city', 'build20');
	echo ("<tr><td width=20% valign=top><font color=blue size=4><center><b>Меню ($name)</b></center></font><br>");
	echo("<font color=white><b>Информация</b></font>");
	echo("<a href='game.php?action=1'><dd>Персонаж</a><br>");
	echo("<a href='game.php?action=80'><dd>Инвентарь</a><br>");
  echo("<a href='game.php?action=8'><dd>Экономика</a><br>");
  echo("<a href='game.php?action=2'><dd>Экипировка</a><br>");
  echo("<a href='map.php'><dd>Королевство</a><br>");	
  if ($status != 0)
    echo("<a href='game.php?action=3'><dd>Идти по городу</a><br>");
	echo("<a href='javascript:map();'><dd>Карта королевства</a><br>");	
  echo("<a href='game.php?action=5'><dd>Вызвать на поединок</a><br>");
	echo("<font color=white><b>Приказы</b></font>");
  if ($status != 0)
    echo("<a href='game.php?action=7'><dd>Строительство</a><br>");
  echo("<a href='game.php?action=12'><dd>Послать гонца</a><br>");
	echo("<font color=white><b>Система</b></font>");
	echo("<a href='kickme.php'><dd>Удалить аккаунт</a><br>");
	echo("<a href='javascript:help();'><dd>Документация</a><br>");
	echo("<a href=javascript:money();><dd>Доска почёта</a><br>");
	echo("<a href=javascript:pltop();><dd>TOP игроков</a><br>");
	echo("<a href='game.php?action=19'><dd>");
	if (getdata($login, 'status', 'f2') == 1)
			{
			echo ("<font color='yellow'>");
			echo ("<bgsound src='music/notify.wav' loop=1><b>");
			}
	echo ("Извещения</b></font></a><br>");
	echo("<a href=javascript:news('".$login."');><dd>Новости</a><br>");
	echo("<a href=javascript:music();><dd>Музыка</a><br>");
	echo("<a href='javascript:forum();'><dd>Форум</a><br>");
	echo("<a href=javascript:mail('".$login."');><dd>Почта</a><br>");
	echo("<a href='javascript:chat();'><dd>Чат</a><br>");
	echo("<a href='irc://irc.local/nl'><dd>IRC</a><br>");
	echo("<a href='game.php?action=16'><dd>Выход</a><br>");
	if (isadmin($login) == 1)
	{
		echo("<a href='game.php?action=17'><dd>Администрирование</a><br>");
		echo("<a href='javascript:cheats();'><dd>Кладовая знаний</a><br>");
	}
	if (issubadmin($login) == 1)
	{
		echo("<a href='javascript:cheats();'><dd>Кладовая знаний</a><br>");
	}

  //Кнопка помощи
  HelpMe(0, 1);
	echo("</td>");
}

//Документация по игре
function documentation()
{
  ?>
    <script>
      window.open("help.php");
    </script>
  <?
/*
$file_array = fopen("help.php", "r");
while (!feof($file_array))
	{
	$s = fgets($file_array, 255);
	echo($s);
	}
fclose ($file_array);
*/
}

//Вещь
function item($login, $where)
{
	if ($where == 1)
	{
		$t = 'golova';
	}
	if ($where == 2)
	{
		$t = 'shea';
	}
	if ($where == 3)
	{
		$t = 'rightruka';
	}
	if ($where == 4)
	{
		$t = 'palec';
	}
	if ($where == 5)
	{
		$t = 'telo';
	}
	if ($where == 6)
	{
		$t = 'leftruka';
	}
	if ($where == 7)
	{
		$t = 'plash';
	}
	if ($where == 8)
	{
		$t = 'tors';
	}
	if ($where == 9)
	{
		$t = 'koleni';
	}
	if ($where == 10)
	{
		$t = 'nogi';
	}
	(int)$num = getdata($login, 'items', $t);

	if (!empty($num))
	{
		$s = "<img src='".getimg($num)."' alt='".getinfo($num)."' width=60 height=60>";
	}
	else
	{
		switch ($where)
		{
			case 1:
				$s = '<img src="images/weapons/null/head.jpg" width=60 height=60>';
				break;
			case 2:
				$s = '<img src="images/weapons/null/shea.jpg" width=60 height=60>';
				break;
			case 3:
				$s = '<img src="images/weapons/null/weapon.jpg" width=60 height=60>';
				break;
			case 4:
				$s = '<img src="images/weapons/null/ring.jpg" width=60 height=60>';
				break;
			case 5:
				$s = '<img src="images/weapons/null/armor.jpg" width=60 height=60>';
				break;
			case 6:
				$s = '<img src="images/weapons/null/shit.jpg" width=60 height=60>';
				break;
			case 7:
				$s = '<img src="images/weapons/null/plash.jpg" width=60 height=60>';
				break;
			case 8:
				$s = '<img src="images/weapons/null/tors.jpg" width=60 height=60>';
				break;
			case 9:
				$s = '<img src="images/weapons/null/shitki.jpg" width=60 height=60>';
				break;
			case 10:
				$s = '<img src="images/weapons/null/nogi.jpg" width=60 height=60>';
				break;
		}
	}
	return $s;
}

//Проверка
function check($login)
{
//Сначала проверим, а может ли этот юзер сидеть в городе?
if (($login != $HTTP_COOKIE_VARS["nativeland"])||(empty($login))||($login == ""))
{
	echo ("<script>window.location.href('index.php');</script>");
	exit();
}
}


//Экипировка
function equipment($login)
{
	echo("<center><h2>Экипировка</h2>");
	echo("<table border=0 width=30% CELLSPACING=0 CELLPADDING=0>");
	echo("<tr><td colspan=3 align=center>".item($login, 1)."</td></tr>");
	echo("<tr><td colspan=3 align=center>".item($login, 2)."</td></tr>");
	echo("<tr><td align=center width=30%>".item($login, 3)."</td><td rowspan=2 align=center>".item($login, 5)."</td><td align=center width=30%>".item($login, 6)."</td></tr>");
	echo("<tr><td align=center>".item($login, 4)."</td><td align=center>".item($login, 7)."</td></tr>");
	echo("<tr><td colspan=3 align=center>".item($login, 8)."</td></tr>");
	echo("<tr><td colspan=3 align=center>".item($login, 9)."</td></tr>");
	echo("<tr><td colspan=3 align=center>".item($login, 10)."</td></tr>");
	echo("</table>");

  //Бутылочки
  $lg = $login;
    //Здоровье
    (int)$HMax = getdata($lg, 'bottles', 'hmaxi');
    (int)$HMed = getdata($lg, 'bottles', 'hmedi');
    (int)$HMin = getdata($lg, 'bottles', 'hmini');
    //Мана
    (int)$MMax = getdata($lg, 'bottles', 'mmaxi');
    (int)$MMed = getdata($lg, 'bottles', 'mmedi');
    (int)$MMin = getdata($lg, 'bottles', 'mmini');
    //Сила
    (int)$PMax = getdata($lg, 'bottles', 'pmaxi');
    (int)$PMed = getdata($lg, 'bottles', 'pmedi');
    (int)$PMin = getdata($lg, 'bottles', 'pmini');
    //Заклинания
    (int)$SMax = getdata($lg, 'bottles', 'smaxi');
    (int)$SMed = getdata($lg, 'bottles', 'smedi');
    (int)$SMin = getdata($lg, 'bottles', 'smini');
  
  //Табличка для бутылочек
  echo("<table border=1 CELLSPACING=0 CELLPADDING=0 width=10%><tr>");
  //Бутылочки со здоровьем
  if ($HMax > 0)
    echo("<td align=center><img src='images/bottles/big_h.jpg' alt='Восстанавливает 100% здоровья' width=64 height=64 border=0><br>$HMax</td>");
  if ($HMed > 0)
    echo("<td align=center><img src='images/bottles/med_h.jpg' alt='Восстанавливает 50% здоровья' width=64 height=64 border=0><br>$HMed</td>");
  if ($HMin > 0)
    echo("<td align=center><img src='images/bottles/sma_h.jpg' alt='Восстанавливает 25% здоровья' width=64 height=64 border=0><br>$HMin</td>");

  //Бутылочки с маной
  if ($MMax > 0)
    echo("<td align=center><img src='images/bottles/big_m.jpg' alt='Восстанавливает 100% маны' width=64 height=64 border=0><br>$MMax</td>");
  if ($MMed > 0)
    echo("<td align=center><img src='images/bottles/med_m.jpg' alt='Восстанавливает 50% маны' width=64 height=64 border=0><br>$MMed</td>");
  if ($MMin > 0)
    echo("<td align=center><img src='images/bottles/sma_m.jpg' alt='Восстанавливает 25% маны' width=64 height=64 border=0><br>$MMin</td>");

  //Бутылочки с силой
  if ($PMax > 0)
    echo("<td align=center><img src='images/bottles/big_p.jpg' alt='Увеличивает силу на 100%' width=64 height=64 border=0><br>$PMax</td>");
  if ($PMed > 0)
    echo("<td align=center><img src='images/bottles/med_p.jpg' alt='Увеличивает силу на 50%' width=64 height=64 border=0><br>$PMed</td>");
  if ($PMin > 0)
    echo("<td align=center><img src='images/bottles/sma_p.jpg' alt='Увеличивает силу на 25%' width=64 height=64 border=0><br>$PMin</td>");

  //Бутылочки с колдовской силой
  if ($SMax > 0)
    echo("<td align=center><img src='images/bottles/big_i.jpg' alt='Увеличивает колдовскую силу на 100%' width=64 height=64 border=0><br>$SMax</td>");
  if ($SMed > 0)
    echo("<td align=center><img src='images/bottles/med_i.jpg' alt='Увеличивает колдовскую силу на 50%' width=64 height=64 border=0><br>$SMed</td>");
  if ($SMin > 0)
    echo("<td align=center><img src='images/bottles/sma_i.jpg' alt='Увеличивает колдовскую силу на 25%' width=64 height=64 border=0><br>$SMin</td>");

  //Конец таблицы для бутылочек
  echo("</tr></table>");  
  HelpMe(3, 0);
	echo("</center>");
}

//Получить случайного монстра
function getmonstr($level)
{
//	link();
	$count = 0;
	$usr = mysql_query("select * from monsters;");
	if ($usr)
		{
			while ($user = mysql_fetch_array($usr))
			{
				if (($user['level'] == $level)||($user['level'] == ($level-1))||($user['level'] == ($level-2)))
				{
					$name[$count] = $user['name'];
					$count++;
				}
			}
	    }
return $name[rand($count-1, 0)];
}

//Добавить в лог файл строчку
function addtolog($login, $info)
{

	//Читаем весь ЛОГ
	$file = fopen("data/logs/".$login.".log", "r");
	for ($i = 0; $i < 13; $i++)
	{
		$st[$i+1] = fgets($file, 255);
	}
	fclose ($file);
	$tm = "(".date("<b>H:i:s</b>", time()).")";
	$st[0] = $tm." ".$info."<br>";
	$file = fopen("data/logs/".$login.".log", "w");
	for ($i = 0; $i < 12; $i++)
	{
		fputs($file, $st[$i]);
	}
	fclose($file);
}

//Добавить в лог файл строчку
function intolog($login, $log, $info)
{

	//Читаем весь ЛОГ
	$file = fopen("data/".$log."/".$login, "r");
	for ($i = 0; $i < 13; $i++)
	{
		$st[$i+1] = fgets($file, 255);
	}
	fclose ($file);
	$st[0] = $info."<br>";
	$file = fopen("data/".$log."/".$login, "w");
	for ($i = 0; $i < 12; $i++)
	{
		fputs($file, $st[$i]."\n");
	}
	fclose($file);
}

//Послать гонца
function sendmail($login, $type)
{
	echo ("<center><table border=1 width=90% CELLSPACING=0 CELLPADDING=0>\n");
	echo ("<tr><td colspan=2 align=center><h2>Послать сообщение</h2>");
  HelpMe(9, 0);
  echo ("</td></tr>");
	?>
	<form name='sendmail' action='send.php' method='post'>
	<tr><td width=20%>Кому</td><td align=center>
	<?
	indexuserlist('to');
	echo ("<input type='hidden' name='login' value=$login>");
	echo ("<input type='hidden' name='where' value=$type>");
	?>
	</td></tr>
	<tr><td colspan=2 align=center>Текст<br>
	<textarea name="txt" cols=70 rows=15 maxlength=255 style='background:#000099; font-WEIGHT: BOLD; COLOR: white; cursor: hand; list-style: lower-alpha; filter: Alpha(Opacity=20, FinishOpacity=100, Style=3)'></textarea>
	</td></tr>
	<tr><td colspan=2 align=center><input type="submit" name="send" value="Отправить" style='background:#000099; font-WEIGHT: BOLD; COLOR: white; cursor: hand; list-style: lower-alpha; filter: Alpha(Opacity=20, FinishOpacity=100, Style=3)'></td></tr>
	</form>
	<?
	echo ("</table></center>");
}

//Читаем сообщения
function msg($login)
{

//Меняем на прочтённое
change ($login, 'status', 'f2', '0');

//Получаем список всех сообщений	
$dir_rec= dir("data/mail/".$login);
$i = 0;
while ($entry = $dir_rec->read())
   {
   if (substr($entry,0,3)=="rec")
      {
      $names[$i]=trim(substr($entry,4));
      $i++;
      }
   }
$dir_rec->close();
$count = $i;
@rsort($names);
	

//Создаём таблицу со списком
echo ("<center><table width=90% border=1 CELLSPACING=0 CELLPADDING=0>");
echo ("<tr><td align=center width=20%>Отправитель</td><td align=center>Сообщение</td><td align=center width=20%>Удалить</td></tr>");
for ($i = 0; $i < $count; $i++)
   {
   $entry = $names[$i];
//   $data = file("data/mail/".$login."/rec.".$entry);

  //Читаем файл до конца
  $data = fopen("data/mail/".$login."/rec.".$entry, "r");
  $who = fgets($data, 255);
  echo ("<tr><td align=center>");
  echo ($who."</td><td>");
  while (!feof($data))
	   {
	   echo (fgets($data, 255)."<br>");
	   }
  echo ("</td><td align=center><form action='del.php' method=post><input type='hidden' name='num' value=$entry><input type='hidden' name='login' value='$login'><br><input type='submit' value='Стереть' style='background:#000099; font-WEIGHT: BOLD; COLOR: white; cursor: hand; list-style: lower-alpha; filter: Alpha(Opacity=20, FinishOpacity=100, Style=3)'></form></td></tr>");
  fclose ($data);
  }
echo ("<tr><td colspan=3 align=center><form action='del.php' method=post><input type='hidden' name='num' value=0><input type='hidden' name='login' value='$login'><br><input type='submit' value='Стереть все' style='background:#000099; font-WEIGHT: BOLD; COLOR: white; cursor: hand; list-style: lower-alpha; filter: Alpha(Opacity=20, FinishOpacity=100, Style=3)'></form></td></tr>");
echo ("</table></center>");	
}

//Информация об экономике
function economic($login)
{
  function query($level, $race, $field)
  {
    $usr = mysql_query("select * from warriors;");
    $find = "";
    if ($usr)
      while ($user = mysql_fetch_array($usr))
        if (($user['level'] == $level)&&($user['race'] == $race))
          $find = $user[$field];
  	return $find;
  }

	?>
	<center>
	<table border=1 width=60% CELLSPACING=0 CELLPADDING=0>
	<tr><td colspan=2 align=center><font color=blue><b>Экономика королевства</b></font></td></tr>
	<tr><td width=70% align=center><font color=blue>Параметр</font></td><td align=center><font color=blue>Значение</font></td></tr>

	<?
	$txt = getdata($login, 'info', 'country');
	echo ("<tr><td width=20% align=center><font color=blue>Королевство</font></td><td align=center><font color=blue></font>$txt</td></tr>");
	$txt = getdata($login, 'info', 'capital');
	echo ("<tr><td width=20% align=center><font color=blue>Столица</font></td><td align=center><font color=blue></font>$txt</td></tr>");
	if (getdata($login, 'info', 'resource') == 'metal')
	{
		$txt = 'Металл';
	}
	if (getdata($login, 'info', 'resource') == 'rock')
	{
		$txt = 'Камень';
	}
	if (getdata($login, 'info', 'resource') == 'wood')
	{
		$txt = 'Дерево';
	}
	echo ("<tr><td width=20% align=center><font color=blue>Основной ресурс</font></td><td align=center><font color=blue></font>$txt</td></tr>");
	$txt = getdata($login, 'economic', 'moneyname');
	echo ("<tr><td width=20% align=center><font color=blue>Деньги</font></td><td align=center><font color=blue></font>$txt</td></tr>");
	$txt = getdata($login, 'economic', 'curse');
	echo ("<tr><td width=20% align=center><font color=blue>Курс денег к металлу</font></td><td align=center><font color=blue></font>$txt</td></tr>");
	$txt = getdata($login, 'economic', 'peoples');
	echo ("<tr><td width=20% align=center><font color=blue>Население королевства</font></td><td align=center><font color=blue></font>$txt</td></tr>");
	$k = getdata($login, 'economic', 'curse')*(1 + getdata($login, 'city', 'build1') + 5*getdata($login, 'city', 'build2') + 30*getdata($login, 'city', 'build3'));
	$k = $k + getdata($login, 'city', 'build19');
	$txt = $k*24;
	echo ("<tr><td width=20% align=center>Ежедневный доход денег<font color=blue</font></td><td align=center><font color=blue></font>$txt</td></tr>");
	$txt = getdata($login, 'economic', 'curse')*12;
	if (getdata($login, 'city', 'build13') == 1) {$txt = 0;}
	echo ("<tr><td width=20% align=center>Ежедневный налог северному союзу<font color=blue</font></td><td align=center><font color=blue></font>$txt</td></tr>");
	$txt = 24*(1 + 2*getdata($login, 'city', 'build4'));
	echo ("<tr><td width=20% align=center>Ежедневный доход основного ресурса<font color=blue</font></td><td align=center><font color=blue></font>$txt</td></tr>");
  $race = getdata($login, 'hero', 'race');
  $n1 = query(1, $race, 'addon');
  $n2 = query(2, $race, 'addon');
  $n3 = query(3, $race, 'addon');
  $n4 = query(4, $race, 'addon');
  $c1 = getdata($login, 'army', 'level1');
  $c2 = getdata($login, 'army', 'level2');
  $c3 = getdata($login, 'army', 'level3');
  $c4 = getdata($login, 'army', 'level4');
  echo("<tr><td width=20% align=center>Количество $n1</td><td align=center>$c1</td></tr>");
  echo("<tr><td width=20% align=center>Количество $n2</td><td align=center>$c2</td></tr>");
  echo("<tr><td width=20% align=center>Количество $n3</td><td align=center>$c3</td></tr>");
  echo("<tr><td width=20% align=center>Количество $n4</td><td align=center>$c4</td></tr>");

  //Курс
  $Curse = getdata($login, 'economic', 'curse');

  //Снимаем налог за армию
  $Nalog = $c1 + $c2*2 + $c3*3 + $c4*4;
  $Nalog = $Nalog*$Curse;
  $Economy = Level(28, $login)*0.01;
  $Nalog = round($Nalog - $Nalog*$Economy);
  if ($Nalog < 0)
    $Nalog = 0;
  echo("<tr><td width=20% align=center>Ежедневные затраты на армию</td><td align=center>$Nalog</td></tr>");
  $Peoples = getdata($login, 'economic', 'peoples');
  $Peoples = round($Peoples + Level(29, $login)*0.01*$Peoples);
  echo("<tr><td width=20% align=center>Ежедневные подати крестьян</td><td align=center>$Peoples</td></tr>");
  ?>
	</table>
	<?
  HelpMe(2, 1);
}


//Шпионаж
function nwe($login)
{

  //Получение данных из таблицы по имени поля, имени пользователя и имени таблицы
  function query($level, $race, $field)
  {
    $usr = mysql_query("select * from warriors;");
    $find = "";
    if ($usr)
      while ($user = mysql_fetch_array($usr))
        if (($user['level'] == $level)&&($user['race'] == $race))
          $find = $user[$field];
  	return $find;
  }

  $race = getdata($login, 'hero', 'race');

	$t = "<center><table border=1 width=90% CELLSPACING=0 CELLPADDING=0><tr><td colspan=2 align=center><font color=blue><b>Результаты разведки</b></font></td></tr><tr><td width=70% align=center><font color=blue>Параметр</font></td><td align=center><font color=blue>Значение</font></td></tr>";

	$txt = getdata($login, 'info', 'country');
	$t = $t."<tr><td width=20% align=center><font color=blue>Королевство</font></td><td align=center><font color=blue></font>".$txt."</td></tr>";
	$txt = getdata($login, 'info', 'capital');
	$t = $t."<tr><td width=20% align=center><font color=blue>Столица</font></td><td align=center><font color=blue></font>".$txt."</td></tr>";
	if (getdata($login, 'info', 'resource') == 'metal')
	{
		$txt = 'Металл';
	}
	if (getdata($login, 'info', 'resource') == 'rock')
	{
		$txt = 'Камень';
	}
	if (getdata($login, 'info', 'resource') == 'wood')
	{
		$txt = 'Дерево';
	}
	$t = $t."<tr><td width=20% align=center><font color=blue>Основной ресурс</font></td><td align=center><font color=blue></font>".$txt."</td></tr>";
	$txt = getdata($login, 'economic', 'moneyname');
	$t = $t."<tr><td width=20% align=center><font color=blue>Деньги</font></td><td align=center><font color=blue></font>".$txt."</td></tr>";
	$txt = getdata($login, 'economic', 'curse');
	$t = $t."<tr><td width=20% align=center><font color=blue>Курс денег к металлу</font></td><td align=center><font color=blue></font>".$txt."</td></tr>";
	$k = rand(100, 50)/100;
	$txt = round(getdata($login, 'economic', 'peoples')*$k);
	$t = $t."<tr><td width=20% align=center><font color=blue>Примерное население королевства</font></td><td align=center><font color=blue></font>".$txt."</td></tr>";
  $n1 = query(1, $race, 'addon');
  $n2 = query(2, $race, 'addon');
  $n3 = query(3, $race, 'addon');
  $n4 = query(4, $race, 'addon');
  $c1 = getdata($login, 'army', 'level1');
  $c2 = getdata($login, 'army', 'level2');
  $c3 = getdata($login, 'army', 'level3');
  $c4 = getdata($login, 'army', 'level4');
  $c1 = round($c1 + $c1*rand(20, 0)/100);
  $c2 = round($c2 + $c2*rand(20, 0)/100);
  $c3 = round($c3 + $c3*rand(20, 0)/100);
  $c4 = round($c4 + $c4*rand(20, 0)/100);
	$t = $t."<tr><td width=20% align=center><font color=blue>Примерное количество $n1</font></td><td align=center><font color=blue></font>".$c1."</td></tr>";
	$t = $t."<tr><td width=20% align=center><font color=blue>Примерное количество $n2</font></td><td align=center><font color=blue></font>".$c2."</td></tr>";
	$t = $t."<tr><td width=20% align=center><font color=blue>Примерное количество $n3</font></td><td align=center><font color=blue></font>".$c3."</td></tr>";
	$t = $t."<tr><td width=20% align=center><font color=blue>Примерное количество $n4</font></td><td align=center><font color=blue></font>".$c4."</td></tr>";
  $t = $t."</table>";
  return $t;
	}

//Строительство
function build($name)
{
	echo ("<center>");
	echo ("<h2>Строительство</h2>");
  HelpMe(8, 0);
	echo ("<table border=1 width=98% CELLSPACING=0 CELLPADDING=0>");
	echo ("<tr><td colspan=4 align=center>Выберите здание для постройки</td></tr>");

	//Таблица цен
	for ($i = 1; $i < 20; $i ++)
	{
	$build = $i;
	$metal[$i] = round(sqrt(($build)*($build)*($build)));
	$rock[$i] = ($build)*($build);
	$wood[$i] = ($build)*5;
	$cena[$i] = $metal[$i]*getdata($name, 'economic', 'curse');
	}

	$metal[5] = 15;
	$rock[5] = 14;
	$wood[5] = 13;
	$cena[5] = $metal[5]*getdata($name, 'economic', 'curse');


	echo ("<tr><td align=center width=22%>Здание</td><td align=center width=50%>Назначение</td><td align=center width=1%>Изображение</td><td align=center>Цена</td></tr>");
	echo ("<tr><td align=center width=20%>".getfrom('race', getdata($name, 'hero', 'race'), 'buildings', 'build1'));
	if (getdata($name, 'city', 'build1') == 0)
	{
	echo ("<form action='build.php' method='post'><input type='hidden' name='build' value=1><input type='submit' value='Построить' style='background:#000099; font-WEIGHT: BOLD; COLOR: white; cursor: hand; list-style: lower-alpha; filter: Alpha(Opacity=20, FinishOpacity=100, Style=3)'><input type='hidden' name='login' value='".$name."'></form>");
	}
	echo ("</td><td align=center>Увеличивает доход золота на 5%. Также в этом здании находится телепорт, с помощью которого Вы сможете мгновенно перемещаться в другие Ваши города.</td><td><img src='images/castles/".getdata($name, 'hero', 'race')."/build1.JPG' width=108 height=101></td><td align=center>Металл: $metal[1]<br>Камень: $rock[1]<br>Дерево: $wood[1]<br>".getdata($name, 'economic', 'moneyname').": $cena[1]<br></td></tr><tr><td align=center width=20%>".getfrom('race', getdata($name, 'hero', 'race'), 'buildings', 'build2'));
	if (getdata($name, 'city', 'build2') == 0)
	{
	echo ("<form action='build.php' method='post'><input type='hidden' name='build' value=2><input type='submit' value='Построить' style='background:#000099; font-WEIGHT: BOLD; COLOR: white; cursor: hand; list-style: lower-alpha; filter: Alpha(Opacity=20, FinishOpacity=100, Style=3)'><input type='hidden' name='login' value='".$name."'></form>");
	}
	echo ("</td><td align=center>Увеличивает доход золота на 10%. Также, построив это здание у Вас в городе появится филиал Великой Ассоциации по Надзору за Кланами. Вы сможете вступить в какой-либо клан</td><td><img src='images/castles/".getdata($name, 'hero', 'race')."/build2.JPG' width=108 height=101></td><td align=center>Металл: $metal[2]<br>Камень: $rock[2]<br>Дерево: $wood[2]<br>".getdata($name, 'economic', 'moneyname').": $cena[2]<br></td></tr><tr><td align=center width=20%>".getfrom('race', getdata($name, 'hero', 'race'), 'buildings', 'build3'));
	if (getdata($name, 'city', 'build3') == 0)
	{
	echo ("<form action='build.php' method=post><input type='hidden' name='build' value=3><input type='submit' value='Построить' style='background:#000099; font-WEIGHT: BOLD; COLOR: white; cursor: hand; list-style: lower-alpha; filter: Alpha(Opacity=20, FinishOpacity=100, Style=3)'><input type='hidden' name='login' value='".$name."'></form>");
	}
	echo ("</td><td align=center>Увеличивает доход золота на 15%</td><td><img src='images/castles/".getdata($name, 'hero', 'race')."/build3.JPG' width=108 height=101></td><td align=center>Металл: $metal[3]<br>Камень: $rock[3]<br>Дерево: $wood[3]<br>".getdata($name, 'economic', 'moneyname').": $cena[3]<br></td></tr><tr><td align=center width=20%>".getfrom('race', getdata($name, 'hero', 'race'), 'buildings', 'build4'));
	if (getdata($name, 'city', 'build4') == 0)
	{
	echo ("<form action='build.php' method=post><input type='hidden' name='build' value=4><input type='submit' value='Построить' style='background:#000099; font-WEIGHT: BOLD; COLOR: white; cursor: hand; list-style: lower-alpha; filter: Alpha(Opacity=20, FinishOpacity=100, Style=3)'><input type='hidden' name='login' value='".$name."'></form>");
	}
	echo("</td><td align=center>Увеличивает доход ресурса на 2</td><td><img src='images/castles/".getdata($name, 'hero', 'race')."/build4.JPG' width=108 height=101></td><td align=center>Металл: $metal[4]<br>Камень: $rock[4]<br>Дерево: $wood[4]<br>".getdata($name, 'economic', 'moneyname').": $cena[4]<br></td></tr><tr><td align=center width=20%>".getfrom('race', getdata($name, 'hero', 'race'), 'buildings', 'build5'));
	if (getdata($name, 'city', 'build5') == 0)
	{
	echo ("<form action='build.php' method=post><input type='hidden' name='build' value=5><input type='submit' value='Построить' style='background:#000099; font-WEIGHT: BOLD; COLOR: white; cursor: hand; list-style: lower-alpha; filter: Alpha(Opacity=20, FinishOpacity=100, Style=3)'><input type='hidden' name='login' value='".$name."'></form>");
	}
	echo ("</td><td align=center>Позволяет Вам торговать ресурсами с другими игроками</td><td><img src='images/castles/".getdata($name, 'hero', 'race')."/build5.JPG' width=108 height=101></td><td align=center>Металл: $metal[5]<br>Камень: $rock[5]<br>Дерево: $wood[5]<br>".getdata($name, 'economic', 'moneyname').": $cena[5]<br></td></tr><tr><td align=center width=20%>".getfrom('race', getdata($name, 'hero', 'race'), 'buildings', 'build6'));
	if (getdata($name, 'city', 'build6') == 0)
	{
	echo ("<form action='build.php' method=post><input type='hidden' name='build' value=6><input type='submit' value='Построить' style='background:#000099; font-WEIGHT: BOLD; COLOR: white; cursor: hand; list-style: lower-alpha; filter: Alpha(Opacity=20, FinishOpacity=100, Style=3)'><input type='hidden' name='login' value='".$name."'></form>");
	}
	echo ("</td><td align=center>Здесь Вы сможете покупать артефакты для Вашего персонажа</td><td><img src='images/castles/".getdata($name, 'hero', 'race')."/build6.JPG' width=108 height=101></td><td align=center>Металл: $metal[6]<br>Камень: $rock[6]<br>Дерево: $wood[6]<br>".getdata($name, 'economic', 'moneyname').": $cena[6]<br></td></tr><tr><td align=center width=20%>".getfrom('race', getdata($name, 'hero', 'race'), 'buildings', 'build7'));
	if (getdata($name, 'city', 'build7') == 0)
	{
	echo ("<form action='build.php' method=post><input type='hidden' name='build' value=7><input type='submit' value='Построить' style='background:#000099; font-WEIGHT: BOLD; COLOR: white; cursor: hand; list-style: lower-alpha; filter: Alpha(Opacity=20, FinishOpacity=100, Style=3)'><input type='hidden' name='login' value='".$name."'></form>");
	}
	echo ("</td><td align=center>Здесь Вы сможете лечить своего персонажа</td><td><img src='images/castles/".getdata($name, 'hero', 'race')."/build7.JPG' width=108 height=101></td><td align=center>Металл: $metal[7]<br>Камень: $rock[7]<br>Дерево: $wood[7]<br>".getdata($name, 'economic', 'moneyname').": $cena[7]<br></td></tr><tr><td align=center width=20%>".getfrom('race', getdata($name, 'hero', 'race'), 'buildings', 'build8'));
	if (getdata($name, 'city', 'build8') == 0)
	{
	echo ("<form action='build.php' method=post><input type='hidden' name='build' value=8><input type='submit' value='Построить' style='background:#000099; font-WEIGHT: BOLD; COLOR: white; cursor: hand; list-style: lower-alpha; filter: Alpha(Opacity=20, FinishOpacity=100, Style=3)'><input type='hidden' name='login' value='".$name."'></form>");
	}
	echo ("</td><td align=center>Здесь Вы сможете покупать заклинания первого уровня</td><td><img src='images/castles/".getdata($name, 'hero', 'race')."/build8.JPG' width=108 height=101></td><td align=center>Металл: $metal[8]<br>Камень: $rock[8]<br>Дерево: $wood[8]<br>".getdata($name, 'economic', 'moneyname').": $cena[8]<br></td></tr><tr><td align=center width=20%>".getfrom('race', getdata($name, 'hero', 'race'), 'buildings', 'build9'));
	if (getdata($name, 'city', 'build9') == 0)
	{
	echo ("<form action='build.php' method=post><input type='hidden' name='build' value=9><input type='submit' value='Построить' style='background:#000099; font-WEIGHT: BOLD; COLOR: white; cursor: hand; list-style: lower-alpha; filter: Alpha(Opacity=20, FinishOpacity=100, Style=3)'><input type='hidden' name='login' value='".$name."'></form>");
	}
	echo ("</td><td align=center>Здесь Вы сможете покупать заклинания второго уровня</td><td><img src='images/castles/".getdata($name, 'hero', 'race')."/build9.JPG' width=108 height=101></td><td align=center>Металл: $metal[9]<br>Камень: $rock[9]<br>Дерево: $wood[9]<br>".getdata($name, 'economic', 'moneyname').": $cena[9]<br></td></tr><tr><td align=center width=20%>".getfrom('race', getdata($name, 'hero', 'race'), 'buildings', 'build10'));
	if (getdata($name, 'city', 'build10') == 0)
	{
	echo ("<form action='build.php' method=post><input type='hidden' name='build' value=10><input type='submit' value='Построить' style='background:#000099; font-WEIGHT: BOLD; COLOR: white; cursor: hand; list-style: lower-alpha; filter: Alpha(Opacity=20, FinishOpacity=100, Style=3)'><input type='hidden' name='login' value='".$name."'></form>");
	}
	echo ("</td><td align=center>Это здание необходимо Вам для перевода денег другим игрокам</td><td><img src='images/castles/".getdata($name, 'hero', 'race')."/build10.JPG' width=108 height=101></td><td align=center>Металл: $metal[10]<br>Камень: $rock[10]<br>Дерево: $wood[10]<br>".getdata($name, 'economic', 'moneyname').": $cena[10]<br></td></tr><tr><td align=center width=20%>".getfrom('race', getdata($name, 'hero', 'race'), 'buildings', 'build11'));
	if (getdata($name, 'city', 'build11') == 0)
	{
	echo ("<form action='build.php' method=post><input type='hidden' name='build' value=11><input type='submit' value='Построить' style='background:#000099; font-WEIGHT: BOLD; COLOR: white; cursor: hand; list-style: lower-alpha; filter: Alpha(Opacity=20, FinishOpacity=100, Style=3)'><input type='hidden' name='login' value='".$name."'></form>");
	}
	echo ("</td><td align=center>Построив это здание, Вы сможете засылать шпионов к другим игрокам</td><td><img src='images/castles/".getdata($name, 'hero', 'race')."/build11.JPG' width=108 height=101></td><td align=center>Металл: $metal[11]<br>Камень: $rock[11]<br>Дерево: $wood[11]<br>".getdata($name, 'economic', 'moneyname').": $cena[11]<br></td></tr><tr><td align=center width=20%>".getfrom('race', getdata($name, 'hero', 'race'), 'buildings', 'build12'));
	if (getdata($name, 'city', 'build12') == 0)
	{
	echo ("<form action='build.php' method=post><input type='hidden' name='build' value=12><input type='submit' value='Построить' style='background:#000099; font-WEIGHT: BOLD; COLOR: white; cursor: hand; list-style: lower-alpha; filter: Alpha(Opacity=20, FinishOpacity=100, Style=3)'><input type='hidden' name='login' value='".$name."'></form>");
	}
	echo ("</td><td align=center>Здесь Вы сможете вживую общаться с другими игроками</td><td><img src='images/castles/".getdata($name, 'hero', 'race')."/build12.JPG' width=108 height=101></td><td align=center>Металл: $metal[12]<br>Камень: $rock[12]<br>Дерево: $wood[12]<br>".getdata($name, 'economic', 'moneyname').": $cena[12]<br></td></tr><tr><td align=center width=20%>".getfrom('race', getdata($name, 'hero', 'race'), 'buildings', 'build13'));
	if (getdata($name, 'city', 'build13') == 0)
	{
	echo ("<form action='build.php' method=post><input type='hidden' name='build' value=13><input type='submit' value='Построить' style='background:#000099; font-WEIGHT: BOLD; COLOR: white; cursor: hand; list-style: lower-alpha; filter: Alpha(Opacity=20, FinishOpacity=100, Style=3)'><input type='hidden' name='login' value='".$name."'></form>");
	}
	echo ("</td><td align=center>Построив это здание, Вы сможете регулярно пополнять свою армию</td><td><img src='images/castles/".getdata($name, 'hero', 'race')."/build13.JPG' width=108 height=101></td><td align=center>Металл: $metal[13]<br>Камень: $rock[13]<br>Дерево: $wood[13]<br>".getdata($name, 'economic', 'moneyname').": $cena[13]<br></td></tr>");	
	echo ("</table>");
}

//Союзы
function unions($login)
{
	echo ("<center><h2>Союзы</h2>");
	echo ("<table border=1 width=50% CELLSPACING=0 CELLPADDING=0>");
	echo ("<form action=game.php method=post>");
	echo ("<tr><td align=center width=40%>Номер союзника</td><td align=center>Кто же он</td><td align=center>Текущие</td></tr>");
	echo ("<tr><td align=center width=40%>1</td>");
	echo("<td align=center>");
	indexuserlist('u1');
	echo("</td><td align=center>".getdata($login, 'unions', 'login2')."</td></tr>");
	echo ("<tr><td align=center width=40%>2</td><td align=center>");
	indexuserlist('u2');
	echo("</td><td align=center>".getdata($login, 'unions', 'login3')."</td></tr>");
	echo ("<tr><td align=center width=40%>3</td><td align=center>");
	indexuserlist('u3');
	echo("</td><td align=center>".getdata($login, 'unions', 'login4')."</td></tr>");
	echo ("<tr><td align=center width=40%>4</td><td align=center>");
	indexuserlist('u4');
	echo("</td><td align=center>".getdata($login, 'unions', 'login5')."</td></tr>");
	echo ("<tr><td colspan=3 align=center><br><input type='submit' value='  Принять  ' style='background:#000099; font-WEIGHT: BOLD; COLOR: white; cursor: hand; list-style: lower-alpha; filter: Alpha(Opacity=20, FinishOpacity=100, Style=3)'><br><br></td></tr>");
//	echo ("<input type='hidden' name='action' value=30>");
	echo ("</form></table></center>");
}

//Наём армии
function army($login)
{
	readfile('army.php');
	echo ("<center>Цена за 1 воина: ".(getdata($login, 'economic', 'curse')*5)." ".getdata($login, 'economic', 'moneyname')."</center>");
}

//Нападение
function attack($login)
{
	echo ("<center><h2>Главный штаб</h2><table border=1 width=40% CELLSPACING=0 CELLPADDING=0>");
	echo ("<tr><td align=center>На кого нападем</td><td align=center>");
	echo ("<form action='game.php' method=post><input type='hidden' name='action' value=34>");
	indexuserlist("users");
	echo ("</td></tr><tr><td colspan=2 align=center><input type='submit' value = ' Послать армию ' style='background:#000099; font-WEIGHT: BOLD; COLOR: white; cursor: hand; list-style: lower-alpha; filter: Alpha(Opacity=20, FinishOpacity=100, Style=3)'></td></tr>");
	echo ("</form></table></center>");
}

function moveto($page)
{
   echo ("<script>window.location.href('".$page."');</script>");
}

//Запрос на потерянный пароль
function lostpwd($username, $surname, $email, $country, $hero)
{
//link();
$usr = mysql_query("select * from users;");
$find = 0;
if ($usr)
   {
   while ($user = mysql_fetch_array($usr))
      {
      if (($user['login'] == $username)&&($user['surname'] == $surname)&&($user['email'] == $email)&&($user['country'] == $country))
         {
         $find = 1;
		 }
	  }
   }
   else
	{
	   $find = 2;
	}

//Проверям имя героя
if (getdata($username, 'hero', 'name') != $hero)
	{
		$find = 2;
	}

//Всё нормально? - Выводим пароль
echo ("<title>Native Land</title>");
echo ("<link rel='stylesheet' type='text/css' href='style.css'/>");
echo ("<center><h2>Система восстановления пароля</h2>");
//echo ("Ваш пароль выслан на Ваш email адрес, который Вы указывали при регистрации<br>");
//echo ("Если по какой-либо причине Вы не можете получить его, то свяжитесь с администратором<br>");
//echo ("Не теряйте больше пароль :)<br>");

//Составляем сообщение
$msg = "Имя пользователя: <b>".$username."</b><br>Пароль: <b>".getdata($username, 'users', 'pwd')."</b><br>Не теряйте больше пароль :)";

//Результат выводим
echo ($msg);
echo ("<br><a href='index.php'>Назад</a><br>");
echo ("</center>");

exit();
}

//Читаем ЛОГ:
function fromlog($name)
{
	$txt = "";
	$file = fopen($name, "r");
	while (!feof($file))
	{
		$txt = $txt.fgets($file, 255);
	}
	fclose ($file);
	return $txt;
}

//Чей ход
function step($name)
{
	$file = fopen("data/logs/".$name.".log", "r");
	$who = fgets($file, 255);
	fclose ($file);
	return $who;
}

//Случайное место
function randomplace($lgn)
{
	//Сначала выбираем случайный регион
	$ok = 0;
	while ($ok == 0)
	{
		//Генерим случайные координаты
		$rx = getfrom('admin', 'Settings', 'settings', 'f2');
		$ry = getfrom('admin', 'Settings', 'settings', 'f3');
    $rx++;
    if ($rx == 21)
    {
      $rx = 1;
      $ry++;
    }
    if ($ry == 21)
    {
      $rx = 1;
      $ry = 1;
    }
  
    //Запоминаем новые координаты
		setto("admin", "Settings", "settings", "f2", $rx);
		setto("admin", "Settings", "settings", "f3", $ry);

  	//Читаем карту...
		$fp = 0;
		$file = fopen("maps/".$rx."x".$ry.".map", "r");
		for ($x = 1; $x < 11; $x++)
			for ($y = 1; $y < 11; $y++)
			{

				//Читаем клетку
				$map[$x][$y] = "0*0=0";
				$map[$x][$y] = fgets($file, 255);
				$fld = $map[$x][$y];

				//Если клетка свободна
				if (($fld[0] != '4')&&($fld[2] == '0')&&($fld[4] == '0'))
					$fp++;
			}
		fclose($file);

		//Если количество больше нуля
		if ($fp != 0)
		{
			//Генерируем случайное место на данной подкарте
			$pk = 0;
			while ($pk == 0)
			{
				//Случайное место
				$cx = rand(10, 1);
				$cy = rand(10, 1);
				$fld = $map[$cx][$cy];

				//Если оно свободно, обосновываемся тут.
				if (($fld[0] == '0')&&($fld[2] == '0')&&($fld[4] == '0'))
				{
					//Добавляем игрока сюда
					$map[$cx][$cy] = $fld[0]."*".$fld[2]."=".$lgn."\n";

					//Заносим в базу информацию о столице
					mysql_query("insert into coords values ('".$lgn."', '".$rx."', '".$ry."', '".$cx."', '".$cy."');");

					//Заносим в базу информацию о столице
					mysql_query("insert into capital values ('".$lgn."', '".$rx."', '".$ry."', '".$cx."', '".$cy."');");

  				//Генерация завершена
					$pk = 1;
					$ok = 1;
				}
			}
		}
	}
}

//Разброс игроков по карте
function placeplayers($login)
{
	//Только администратр может
	if ($login == 'Admin')
	{
		//Выбираем всех пользователей
		$ath = mysql_query("select * from users;");

		//Для каждого пользователя игры генерируем место на карте
		if ($ath)
		{
			//Для каждого
			while ($rw = mysql_fetch_row($ath))
			{
				//Имя пользователя
				$lgn = $rw[0];
				randomplace($lgn);
			}
		}
	}
}

//Создание рандомного монстра
function randommonster()
{
  //Всего монстров в базе
  $num = mysql_query("select count(*) from monsters;");
  $total = mysql_fetch_array($num);

  //Выбираем случайного
  $num = rand($total[0], 1);

  //Находим его в базе
  $fnd = mysql_query("select * from monsters;");
  for ($i = 1; $i <= $num; $i++)
    $monster = mysql_fetch_array($fnd);

  //Выбираем случайного
  $num = rand(32, 1);

  //Находим его в базе
  $fnd = mysql_query("select * from allitems;");
  for ($i = 1; $i <= $num; $i++)
    $sword = mysql_fetch_array($fnd);

  //Щит
  $num = rand(43, 34);

  //Находим его в базе
  $fnd = mysql_query("select * from allitems;");
  for ($i = 1; $i <= $num; $i++)
    $shield = mysql_fetch_array($fnd);

  //Запоминаем информацию
  $name   = $monster[0];
  $id     = $monster[2];
  $level  = $monster[3];
  $weapon = $sword[1];
  $armor  = $shield[1];

  //Подбираем монстру координаты
  $rx = rand(20, 1);
  $ry = rand(20, 1);
  $x  = rand(10, 1);
  $y  = rand(10, 1);

  //Добавляем ли монстра на карту
  $AddToMap = 1;

  //Проверяем, не вода ли здесь
	$file = fopen("maps/".$rx."x".$ry.".map", "r");

  //Карта
	for ($i = 1; $i < 11; $i++)
	{
		//Новая ячейка таблицы
		for ($j = 1; $j < 11; $j++)
		{
			//Получаем данные из ячейки
			$fld = fgets($file, 255);
      if (($i == $x)&&($j == $y))
        if ($fld[0] == 4)
          $AddToMap = 0;
    }
  }
  fclose($file);

  //Заносим монстра в базу
  if ($AddToMap != 0)
    mysql_query("insert into random values('$name', '$level', '$id', '$x', '$y', '$rx', '$ry', '$weapon', '$armor');");

  //Инфорамация
  //echo($name." (".$weapon." и ".$armor."). Он находится: $rx x $ry ($x x $y)<br>");
}

//Возвращает размер союзной армии для игрока
function uarmy($name)
{
	//Изначально союзная армия равна нулю
	$army = 0;

	//Определяем союзников
	$n[0] = getdata($name, 'unions', 'login2');
	$n[1] = getdata($name, 'unions', 'login3');
	$n[2] = getdata($name, 'unions', 'login4');
	$n[3] = getdata($name, 'unions', 'login5');

	//Проверяем на повторы
	for ($i = 0; $i < 4; $i++)
		for ($j = 0; $j < 4; $j++)
		{
			if (($n[$i] == $n[$j])&&($i != $j))
			{
				$n[$i] = $name;
			}
		}

	//Считаем союзную армию
	for ($i = 0; $i < 4; $i++)
	{
		//Если логин является самим собой, то нельзя
		if ($n[$i] != $name)
		{
			//А находимся ли мы во взаимнооднозначном союзе с этим игроком
			for ($k = 0; $k < 4; $k++)
			{
				$uar[$i][$k] = getdata($n[$i], 'unions', 'login'.($k+2));
			}
		}
	}

	//Итак, мы считали у всех союзников информацию обо всех игроках, стоящих у них в союзе.
	//Проверяем на совпадения
	for ($i = 0; $i < 4; $i++)
	{
		for ($j = 0; $j < 4; $j++)
		{
			for ($k = 0; $k < 4; $k++)
			{
				//Если да, то добавляем галочку
				if (($uar[$i][$j] == $uar[$i][$k])&&($j != $k))
				{
					$uar[$i][$j] = $n[$i];
				}
			}
		}
	}

	//Выводим информацию об обработанных союзах
	for ($i = 0; $i < 4; $i++)
	{
		for ($j = 0; $j < 4; $j++)
		{
			//Если стоим в союзе и этот игрок не сам он
			if (($uar[$i][$j] == $name)&&($n[$i] != $name))
			{
				$army = $army + getdata($n[$i], 'economic', 'peoples')*getdata($n[$i], 'hero', 'level');
			}
		}
	}
	return $army;
}

//Сообщение
//Шапка таблицы
function messagebox($txt, $back)
{
	echo ("<title>Сообщение</title>");
	echo ("<link rel='stylesheet' type='text/css' href='style.css'/><body background='images/back.jpe'>");
	echo ("<center>$txt</center>");
  echo ("<center><form action='$back' method=post>");
  ?>
    <input type='submit' value='Готово' style='background:#000099; font-WEIGHT: BOLD; COLOR: white; cursor: hand; list-style: lower-alpha; filter: Alpha(Opacity=20, FinishOpacity=100, Style=3)'>
  <?
  echo ("</form></center>");
	exit();
}

//Матриксовский баннер
function ban()
{
	echo ("<img class=z src=http://active.mns.ru/banner/show.php width=0 height=0 left=-100 top=-10>\n");
}

//Функция удаления предмета из инвентаря по номеру и ЛОГИНу (Возврат: 1 - успех; 0 - неудача)
function PopItem($Login, $Number)
{
  //Ищем вещь в инвенторе
  for($i = 16; $i >= 1; $i--)
    if (getdata($Login, 'inventory', 'inv'.$i) == $Number)
    {
      change($Login, 'inventory', 'inv'.$i, '0');
      return 1;
    }

  return 0;
}

//Функция добавление предмета в инвентарь по номеру и ЛОГИНу (Возврат: 1 - успех; 0 - неудача)
function PushItem($Login, $Number)
{
  //Сначала определяем, есть ли свободное место в инвентаре
  $Count = 0;
  $Place = 0;
  for($i = 16; $i >= 1; $i--)
    if (getdata($Login, 'inventory', 'inv'.$i) == 0)
    {
      $Place = $i;
      $Count++;
    }

  //Если место есть, то добавляем предмет туда
  if ($Count != 0)
  {
    //Записываем предмет
    change($Login, 'inventory', 'inv'.$Place, $Number);
  }
  else
    return 0;
  return 1;
}

//Правый блок
function showblock($num, $name, $pass, $who, $location, $temptxt)
{
	switch ($num)
	{
		//Показать документацию
		case -1:
			documentation();
			break;
		
		//Информация о персонаже
		case 1:
			heroinfo($name);
			break;

		//Эккипировка персонажа
		case 2:
			equipment($name);
			break;

		//В город
		case 3:
			echo("<script>");
			echo("window.open('city.php?login=$name', null,'toolbar=no, location=no, menubar=no,scrollbars=yes,width=656,height=480');");
			echo("window.location.href('game.php?action=8');");
			echo("</script>");
			break;

		//Вызвать монстра на поединок
		case 4:
				battle($name);
			break;

		//Вызвать человека на поединок
		case 5:
				//А кого?
				echo ("<center>");
				echo ("<table border=1 width=60% CELLSPACING=0 CELLPADDING=0>");
				echo ("<form action='game.php' method=post><tr><td  align=center>Вызов на поединок</td></tr>");
				echo ("<tr><td align=center><br>");
				indexuserlist('beet');
				echo ("<br><input type='hidden' name='newbattle' value='1'><input type='submit' value=' Вызвать ' style='background:#000099; font-WEIGHT: BOLD; COLOR: white; cursor: hand; list-style: lower-alpha; filter: Alpha(Opacity=20, FinishOpacity=100, Style=3)'></form></td><tr><td>");
				allusers($name);
				echo ("</td></tr></table>");
        HelpMe(7, 0);
        echo("</center>");
			break;

		//Строительство
		case 7:
      //Как давно мы строили?
      $ltime = getdata($name, 'temp', 'param');
      $delta = time()-getdata($name, 'temp', 'param');
      $delta = round($delta / 3600);
      $hour = 24 - $delta;
      if ($hour < 0)
        $hour = 0;
      if ($delta > 24)
      {
			  //А в своём ли мы городе?
  			if ($name == $location)
    			build($name);
  			else
     			echo ("<center>Вы находитесь в чужом городе. Здесь нельзя ничего строить.</center>");
      } else //время не прошло ещё
    		echo ("<center>Дайте отдохнуть строителям. Им на отдых необходимо ещё $hour часов</center>");
			break;

		//Информация об экономике
		case 8:
			economic($name);
			break;

    //Послать сообщение
		case 12:
			sendmail($name, 0);
			break;

		//Разведка
		case 14:
			if (getdata($name, 'city', 'build11') != 0)
				{
				spy($name);
				} else
					{
					echo("<center>У Вас не построено здание разведки Сначала необходимо его построить!</center>");
					}
			break;

		//Нападение
		case 15:
			if (getdata($name, 'city', 'build13') != 0)
				{
				attack($name);
				} else
					{
					echo("<center>У Вас не построена(ы) ".getfrom('race', getdata($name, 'hero', 'race'), 'buildings', 'build13').". Сначала необходимо построить здание!</center>");
					}
			break;

		//Администрирование
		case 17:
			admin($name, $pass, $who);
			break;

		//Сообщение только после битвы
		case 18:
			echo ("<center>Вам бы не мешало сначала подлечиться. Подождите пока ваше здоровье регенерируется до нормального. Или можете зайти в храм для того чтобы ускорить это процесс</center>");
			break;

		//Показать извещения
		case 19:
      echo("<script>\n");
      echo("function hinfo(n)\n");
      echo(" {\n");
      echo(" window.open('info.php?name=' + n);\n");
      echo(" }\n");
      echo("</script>\n");

			msg($name);
			break;

		//Показать ошибку
		case 29:
			echo ("<center>В поле 'Сколько' Вы можете ввести только число, причём неотрицательное!</center>");
			break;

		//Сообщение
		case 31:
			echo ($temptxt);
			break;

    //Инвентарь
		case 80:
      include "inventory.php";
			break;
	}
}

?>