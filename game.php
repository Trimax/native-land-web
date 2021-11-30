<?

//Номер последнего действия: $action = 80
//Нажми CTRL+F и ищи "дописать" - слово из комментария, где незакончена разработка
//Режим отладки? (1 - да; 0 - нет)
$dev=1;

//Константа для отсылки
$msg_to_all = "";

//Модуль функций...
include "count.php";

//Если не указано имя пользователя, то выкинуть юзера нафиг
$lg = trim($HTTP_COOKIE_VARS["nativeland"]);
$pw = trim($HTTP_COOKIE_VARS["password"]);

//Временное ограничение до 100 уровня
if (getdata($lg, 'hero', 'expa') > 11250000) 
	change($lg, 'hero', 'expa', 11250000);

//Стабилизация базы (РАБОТАЕТ УЖАСНО - УДАЛЯЕТ АДМИНА)
if (($action == 68)&&(isadmin($lg) == 1))
{
	stabilization();
  echo("Стабилизация выполнена успешно<br>\n");
	exit();
}

//Добавление 1000 монстров на карту
if (($action == 67)&&(isadmin($lg) == 1))
{
  echo("\nAdding 1000 monsters to base<br>\n");
  for ($i = 0; $i < 1000; $i++)
    randommonster();
  echo("\n\n\n\n\n\n\n\n\nDone<br>\n\n\n\n\n\n\n\n\n");
	exit();
}

//Если игрок в битве армиями, то перенаправляем его туда
(int)$Battle = getdata($lg, 'battles', 'battle');
if ($Battle != 0)
  moveto("fight.php");

//Если игрок в битве, перенаправляем его туда
(int)$Battle = getdata($lg, 'battle', 'battle');
if ($Battle != 0)
  moveto("battle.php");

//Новая битва
if ($newbattle == 1)
{
  //Проверяем, а не в битве ли игрок?
  $OpBattle = getdata($beet, 'battle', 'battle');
  if (($Battle == 0)&&($OpBattle == 0))
  {
    //Была ли подана заявка
    $Yes = getdata($beet, 'inf', 'fld7');  

    //Если да, то всё ок
    if ($Yes == '1')
    {
      if ($lg != $beet)
      {
        BattleOn($lg, $beet);
        moveto("battle.php");
      } else
        messagebox("Вы же не можете вызвать самого себя на поединок, верно?", "game.php?action=5");
    } else
      messagebox("Этот игрок не подавал заявку", "game.php?action=5");
  } else // Оба игрока не в битве
    messagebox("Этот игрок сейчас занят в битве. Подождите её завершения...", "game.php?action=5");
} // Новая битва

//Чистка!
if ((isadmin($lg) == 1)&&($action == 66))
{
	isempty();
	exit();
}

//Рассылка сообщений
if ((isadmin($lg) == 1)&&($action == 79))
{
	$ath = mysql_query("select * from users;");
	if ($ath)
		while ($rw = mysql_fetch_row($ath))
    {
      //Создаём почту
			mkdir("data/mail/".$rw[0], 0700);
    }
  moveto('game.php');
}

//Перегруппировка игроков
if ((isadmin($lg) == 1)&&($action == 78))
{
	moveto("clear.php");
	exit();
}

//Удаление пользователей, которые не прокачались до 3-го уровня в т.ч. месяца
if ((isadmin($lg) == 1)&&($action == 65))
{
  $kicked = 0;
	$ath = mysql_query("select * from users;");
	if ($ath)
	{
		while ($rw = mysql_fetch_row($ath))
		{
			//Инфа о пользователях
			$lv = getdata($rw[0], 'hero', 'level');
      $tm = getdata($rw[0], 'inf', 'showmyinfo');
      $delta = time() - $tm;
      $delta = $delta / 3600;
      $delta = round($delta / 24);

			//Удаляём.
			if (($lv <= 3)&&($rw[0] != $adm)&&($delta > 30))
			{
				//Отсылаем пользователю письмо о том, что его персонажа удалили!
				$mail_to = getdata($rw[0], 'users', 'email');
				$mail_subject = "Native Land Information";
				$mail_msg = "Здравствуйте. Это письмо было отправлено автоматически с игрового сервера http://nativeland.spb.ru Отвечать на него не нужно. Сообщаем Вам о том, что Ваш персонаж был удалён из игры в соответствии с пунктом 1.а пятого параграфа законов игры. (http://nld.spb.ru/help.php) Спасибо за то, что Вы играли в Native Land. С Уважением, администрация.";
				mail($mail_to, $mail_subject, $mail_msg,     "To: $mail_to <$mail_to>\n" .     "From: Native_Land_Automatic_Cleaner <Native_Land_Automatic_Cleaner>\n" .$ccText.$bccText.   "X-Mailer: PHP 4.x");

				//Удаляем персонажа
        kickuser($rw[0]);
        $kicked++;

        //Кто удалён
        echo("Удалён: ".$rw[0]."<br>\n");
			}
		}
	}

  //Количество удалённых +1
  $adm = getadmin();
  $btls = getfrom('admin', $adm, 'settings', 'f3');
  $btls = $btls + $kicked;
  setto('admin', $adm, 'settings', 'f3', $btls);
}

//Античит
if (getdata($lg, 'hero', 'health') > getdata($lg, 'hero', 'level')*100)
{
	change($lg, 'hero', 'health', getdata($lg, 'hero', 'level')*100);
}

//Автоотруб
change ($lg, 'inf', 'fld3', time());
change ($lg, 'status', 'online', '1');

//Тест на оффлайн
offline($lg);

//Выйти
if ($action == 16)
{
		$new = time();
		change($lg, 'time', 'lastexit', $new);
		change($lg, 'status', 'online', '0');
		change($lg, 'inf', 'fld7', '0');
		setcookie("nativeland");
		setcookie("password");
		echo ("<script>window.location.href('index.php');</script>");
}

//Проверка на здоровье
$h = getdata($lg, 'hero', 'health');
if ($h < 0)
{
  change($lg, 'hero', 'health', '0');
}
$m = getdata($lg, 'abilities', 'intellegence');
if ($m < 0)
{
  change($lg, 'abilities', 'intellegence', '0');
}

//Подача заявки
if ($action == 60)
{
	change($lg, 'inf', 'fld7', '1');
	moveto('game.php?action=5');
}

//Отозвать
if (($action == 61)&&(getdata($lg, 'battle', 'battle') == 0))
{
	change($lg, 'inf', 'fld7', '0');
	moveto('game.php?action=5');
}

//Отобразить информацию об игроке
if ($action == 41)
{
	echo ("<script>window.open('info.php?name=$data', null,'toolbar=no, location=no, menubar=no,scrollbars=yes,width=656,height=480');</script>");
	moveto('game.php?action=1');
}

//Покупка за металл
if (($action == 46)&&($lg != $export))
	{
	$rt = getdata($export, 'info', 'resource');
	
	if ($rt != 'metal')
		{
		$ex = getdata($export, 'inf', 'fld5');
		$cr = getdata($export, 'inf', 'fld4');
		$sum = $ex*$cr;
		$wh = getdata($lg, 'economic', 'metal');
		if ($wh > ($sum-1))
			{
			change ($lg, 'economic', 'metal', $wh-$sum);
			$wh = getdata($export, 'economic', 'metal');
			change ($export, 'economic', 'metal', $wh+$sum);
			change ($lg, 'economic', $rt, getdata($lg, 'economic', $rt)+$ex);
			change ($export, 'inf', 'fld5', 0);
		   if ($rt == 'rock') {$w = 'камня';}
		   if ($rt == 'wood') {$w = 'дерева';}
		   if ($rt == 'metal') {$w = 'металла';}
		   $txt = getdata($lg, 'hero', 'name')." купил у Вас ".$ex." единиц ".$w." за ".$sum." единиц металла.";
		   intolog($export, 'trade', $txt);
			}
		moveto('trademark.php?login='.$lg);
		}
	}

//Покупка за камень
if (($action == 47)&&($lg != $export))
	{
	$rt = getdata($export, 'info', 'resource');

	if ($rt != 'rock')
		{
		$ex = getdata($export, 'inf', 'fld5');
		$cr = getdata($export, 'inf', 'fld4');
		$sum = $ex*$cr;
		$wh = getdata($lg, 'economic', 'rock');
		if ($wh > ($sum-1))
			{
			change ($lg, 'economic', 'rock', $wh-$sum);
			$wh = getdata($export, 'economic', 'rock');
			change ($export, 'economic', 'rock', $wh+$sum);
			change ($lg, 'economic', $rt, getdata($lg, 'economic', $rt)+$ex);
			change ($export, 'inf', 'fld5', 0);
		   if ($rt == 'rock') {$w = 'камня';}
		   if ($rt == 'wood') {$w = 'дерева';}
		   if ($rt == 'metal') {$w = 'металла';}
		   $txt = getdata($lg, 'hero', 'name')." купил у Вас ".$ex." единиц ".$w." за ".$sum." единиц камня.";
		   intolog($export, 'trade', $txt);
			}
		moveto('trademark.php?login='.$lg);
		}
	}

//Покупка за дерево
if (($action == 48)&&($lg != $export))
	{
	$rt = getdata($export, 'info', 'resource');

	if ($rt != 'wood')
		{
		$ex = getdata($export, 'inf', 'fld5');
		$cr = getdata($export, 'inf', 'fld4');
		$sum = $ex*$cr;
		$wh = getdata($lg, 'economic', 'wood');
		if ($wh > ($sum-1))
			{
			change ($lg, 'economic', 'wood', $wh-$sum);
			$wh = getdata($export, 'economic', 'wood');
			change ($export, 'economic', 'wood', $wh+$sum);
			change ($lg, 'economic', $rt, getdata($lg, 'economic', $rt)+$ex);
			change ($export, 'inf', 'fld5', 0);
		   if ($rt == 'rock') {$w = 'камня';}
		   if ($rt == 'wood') {$w = 'дерева';}
		   if ($rt == 'metal') {$w = 'металла';}
		   $txt = getdata($lg, 'hero', 'name')." купил у Вас ".$ex." единиц ".$w." за ".$sum." единиц дерева.";
		   intolog($export, 'trade', $txt);
			}
		moveto('trademark.php?login='.$lg);
		}
	}

//Отобразить информацию об игроке
if ($action == 42)
	{
	$rt = getdata($lg, 'info', 'resource');
	$rc = getdata($lg, 'economic', $rt);
	if ($rc > 4)
		{
		$rc = $rc - 5;
		change ($lg, 'economic', $rt, $rc);
		change ($lg, 'inf', 'fld5', getdata($lg, 'inf', 'fld5')+5);
		}
		moveto('trademark.php?login='.$lg);
	}

//Отобразить информацию об игроке
if ($action == 45)
	{
	$rt = getdata($lg, 'info', 'resource');
	$rc = getdata($lg, 'economic', $rt);
	if (getdata($lg, 'inf', 'fld5')  > 0)
		{
		$rc = $rc + 5;
		change ($lg, 'economic', $rt, $rc);
		change ($lg, 'inf', 'fld5', getdata($lg, 'inf', 'fld5')-5);
		}
		moveto('trademark.php?login='.$lg);
	}

//Отобразить информацию об игроке
if (($action == 44)&&(getdata($lg, 'inf', 'fld4') > 0))
	{
	change ($lg, 'inf', 'fld4', getdata($lg, 'inf', 'fld4')-0.2);
		moveto('trademark.php?login='.$lg);
	}

//Отобразить информацию об игроке
if ($action == 43)
	{
	change ($lg, 'inf', 'fld4', getdata($lg, 'inf', 'fld4')+0.2);
		moveto('trademark.php?login='.$lg);
	}

/* =========================================================================== */
/* ЗДЕСЬ БЫЛ ВСТАВЛЕН ВЫРЕЗАННЫЙ КУСОК КОДА (НИЖЕ) */

/* ЗДЕСЬ БЫЛ ВСТАВЛЕН ВЫРЕЗАННЫЙ КУСОК КОДА (ВЫШЕ) */
/* =========================================================================== */

//Если характеристики не распределены, то перенаправляем на распределение
$ch1 = getdata($lg, 'abilities', 'combatmagic');
$ch2 = getdata($lg, 'abilities', 'mindmagic');

//А можно ли добавить levelup
$free = 0;
for ($i = 1; $i <= 16; $i++)
{
  $a = getdata($lg, 'newchar', 'achar'.$i);
  if ($a[0] != 'E')
    $free++;
}

//Делаем левелап
if (($ch1 != 0)&&($ch2 != 0)&&($free != 0))
  moveto("levelup.php");

//Проверяем на LevelUp
$level = getdata($lg, 'hero', 'level');
$how = round(60*pow($level, 1.4));
if ((getdata($lg, 'hero', 'expa') > $how)||(getdata($lg, 'hero', 'expa') == $how)&&($level != 0))
{
  //Добавляем очки
  change($lg, 'hero', 'upgrade', getdata($lg, 'hero', 'upgrade')+5);

  //Если есть свободные слоты
  if ($free != 0)
  {
    //Добавляем две случайные характеристики
    $first = newchar($lg);
    change($lg, 'abilities', 'combatmagic', $first);
    if ($free == 1)
      $second = $first;
    else
    {
      $ok = 0;
      while ($ok == 0)
      {
        $second = newchar($lg);
        if ($second != $first)
          $ok = 1;
      }
    }

    //Вторая
    change($lg, 'abilities', 'mindmagic', $second);	
  } //free

  //Добавляем уровень
	change ($lg, 'hero', 'level', getdata($lg, 'hero', 'level')+1);
}

//Фон и стиль
echo ("<html>\n<head>\n<link rel='stylesheet' type='text/css' href='style.css'/>\n<title>Native Land</title>\n</head>\n<body background='images\back.jpe'>\n");

//Баннер
ban();

//События...
$day = date('l');

//Получаем день бедствия
$epid = getdata($lg, 'inf', 'fld9');

//Конвертируем его в день недели
if ($epid == 1) {$epid = 'Monday';}
if ($epid == 2) {$epid = 'Tuesday';}
if ($epid == 3) {$epid = 'Wednesday';}
if ($epid == 5) {$epid = 'Friday';}
if ($epid == 6) {$epid = 'Thursday';}
if ($epid == 6) {$epid = 'Saturday';}
if ($epid == 7) {$epid = 'Sunday';}

//Если понедельник, то нельзя
if ($epid == 'Monday')
{
	$epid = "You are lucky!";
}

//Совпало? Ха ха ха! Эпидемия!
if (($day == $epid)&&(getdata($lg, 'inf', 'fld8') != 1))
{
	//Меняем на то, что уже готово
	change ($lg, 'inf', 'fld8', '1');

	//Получаем стихийное бедствие для страны...
	srand(10);
	$number = rand(1, 10);

	//Получаем эффект
	$eff = getfrom('num', $number, 'events', 'effect');
	$how = getfrom('num', $number, 'events', 'how');
	$nam = getfrom('num', $number, 'events', 'name');

	//Ха ха!
	sms($lg, 'Министерство чрезвычайных ситуаций', "Внимание! Введено чрезвычайное положение. В королевстве ".$nam);
	sleep(1);

	//Ну и наконец сама беда:
	if ($eff == '1')
	{
		//Болезнь
		$k = $how/100;
		change ($lg, 'economic', 'peoples', round(getdata($lg, 'economic', 'peoples')*$k));
	}

	//Стихийное бедствие
	if ($eff == '2')
	{
		$i = 0;
		for ($i = 0; $i < $how; $i++)
		{
			//Сносим how зданий
			$n = rand(1,12);
			if (getdata($lg, 'city', 'build'.$n) == '1') 
				{
				sms($lg, 'Министерство чрезвычайных ситуаций', "Внимание. Пострадал замок. Здание ".getfrom('race', getdata($lg, 'hero', 'race'), 'buildings', 'build'.$n)." уничтожено");
				sleep(1);
				change ($lg, 'city', 'build'.$n, 0);
				}
		}
	}
}

//Если понедельник, то генерируем день бедствия
if (($day == 'Monday'))
{

	//Случайный день бедствия
	$next = rand(1, 7);
	change ($lg, 'inf', 'fld9', $next);

	//На этой недели его ещё не было
	change ($lg, 'inf', 'fld8', '0');
}

//Залогигнен?
if (finduser($lg, $pw) != 1)
{
	moveto('index.php');
	exit();
}

if (empty($action)) 
{
	$action = 1;
}

//Получаем настоящее имя пользователя
$name = getdata($lg, 'users', 'name');


/* Ежесуточные операции */
$lasttime = getdata($lg, 'inf', 'fld2');
$lasttime = time() - $lasttime;
$lasttime = round($lasttime/3600);
if ($lasttime > 24)
{
  $days = round($days / 24);
  if ($days == 0)
    $days = 1;

  //Курс
  $Curse = getdata($lg, 'economic', 'curse');

  //Снимаем налог за армию
  $level1 = getdata($lg, 'army', 'level1');
  $level2 = getdata($lg, 'army', 'level2');
  $level3 = getdata($lg, 'army', 'level3');
  $level4 = getdata($lg, 'army', 'level4');
  $Nalog = $level1 + $level2*2 + $level3*3 + $level4*4;
  $Nalog = $Nalog*$Curse*$days;
  $Economy = Level(28, $lg)*0.01;
  $Nalog = round($Nalog - $Nalog*$Economy);
  if ($Nalog < 0)
    $Nalog = 0;
  $Money = getdata($lg, 'economic', 'money');
  $Money = $Money - $Nalog;

  //Налог с крестьян
  $Peoples = getdata($lg, 'economic', 'peoples');
  $Peoples = round($Peoples + Level(29, $lg)*0.01*$Peoples);
  $Money = $Money + $Peoples*$days;

  //Меняем деньги
  if ($Money < 0)
    $Money = 0;
  change($lg, 'economic', 'money', $Money);
  change($lg, 'inf', 'fld2', time());
}

/* Ежесуточные операции c кланами */
$lasttime = getfrom('admin', 'Settings', 'settings', 'f1');
$lasttime = time() - $lasttime;
$lasttime = round($lasttime/3600);
if ($lasttime > 24)
{
  setto('admin', 'Settings', 'settings', 'f1', time());

  //Снимаем налог со всех кланов
	$ath = mysql_query("select * from clans;");
	if ($ath)
		while ($rw = mysql_fetch_row($ath))
    { 
      $bill = getfrom('name', $rw[0], 'clans', 'bill');
      $bill = $bill - 1000;
      if ($bill < 0)
        $bill = 0;
      setto('name', $rw[0], 'clans', 'bill', $bill);
    }
}

/* ==================== */

//Начисляем HP и восстанавливаем здоровье и прибавляем ресурсы
$last = getdata($lg, 'time', 'lastexit');
$now = time();
$delta = $now - $last;
$result = $delta / 3600;
$num = round($result);
if ($num < 0)
{
	$num = 0;
}

//Прошла как минимум одна часовая доля.
if ($num > 0)
{
	//Меняем время последнего выхода
	change($lg, 'time', 'lastexit', time());

	//Прибавляем ОД
	$temp = getdata($lg, 'time', 'hp');
	$lev = getdata($lg, 'hero', 'level');
	$temp = $temp + $lev*$num;
	if ($temp > $lev*10)
	{
		$temp = $lev*10;
	}
	//Увеличиваем количество очков действия
	change($lg, 'time', 'hp', $temp);

	//Увеличиваем количество того ресурса, который добывают в стране.
	change($lg, 'economic', getdata($lg, 'info', 'resource'), getdata($lg, 'economic', getdata($lg, 'info', 'resource'))+$num);

  //Доход от доп. способностей
  $mt = Level(8, $lg);
  $rc = Level(9, $lg);
  $wd = Level(10, $lg);

	//Доход от шахт, лесопилок и прочего
	change($lg, 'economic', 'metal', $mt+getdata($lg, 'economic', 'metal')+getdata($lg, 'city', 'build18')*$num);
	change($lg, 'economic', 'rock', $rc+getdata($lg, 'economic', 'rock')+getdata($lg, 'city', 'build17')*$num);
	change($lg, 'economic', 'wood', $wd+getdata($lg, 'economic', 'wood')+getdata($lg, 'city', 'build16')*$num);

	//Увеличиваем количество того ресурса, который добывают в стране.
	if (getdata($lg, 'city', 'build4') != 0)
	{
		change($lg, 'economic', getdata($lg, 'info', 'resource'), getdata($lg, 'economic', getdata($lg, 'info', 'resource'))+2*$num);
	}

	//И деньги добавляем...
	$k = $num*getdata($lg, 'economic', 'curse');
	$full = $k*(1 + getdata($lg, 'city', 'build1')+5*getdata($lg, 'city', 'build2')+30*getdata($lg, 'city', 'build3'));
	$rs =  getdata($lg, 'economic', 'money')+$full;

	//От шахт доход
	$rs = $rs + getdata($lg, 'city', 'build19')*$k;

	//Проверяем на бабло
	$curse = getdata($lg, 'economic', 'curse');
	(int)$pnum = getdata($lg, 'items', 'palec');
	$add = forbattle($pnum, 4);
	$dop = $k*$add*$full;
	$rs = $rs + round($dop);
	(int)$pnum = getdata($lg, 'items', 'shea');
	$add = forbattle($pnum, 4);
	$dop = $k*$add*$full;
	$rs = $rs + round($dop);

  //А также, с доп. способности
  $rs = $rs + round(Level(6, $lg)*$full/100);

  //Изменяем
  change($lg, 'economic', 'money', $rs);

	//Сколько за крышу платим?
	$sum = round($k*0.5);
  if ($sum < 0)
  {
    $sum = 0;
  }

	//Если нет казармы, то платим
	if (getdata($lg, 'city', 'build13') != 1)
	{

		//Снимаем налог
		change($lg, 'economic', 'money', getdata($lg, 'economic', 'money')-$sum);
		if (getdata($lg, 'economic', 'money') < 0)
		{
			change($lg, 'economic', 'money', 0);
		}

		//Платим налог, если нет казармы
		sms($lg, 'Великий северный союз', "Очередной членский взнос взят с Вас! Итого: ".$sum);
	} else //в противоположном случае прирост к армии
  {
    $Add = Level(23, $lg);
    $Lev1 = 4*$num;
    $Lev2 = 3*$num;
    $Lev3 = 2*$num;
    $Lev4 = 1*$num;
    $Add = $Add / 100;
    $Add = $Add + 1;
    $Lev1 = round($Lev1*$Add);
    $Lev2 = round($Lev2*$Add);
    $Lev3 = round($Lev3*$Add);
    $Lev4 = round($Lev4*$Add);
    
    //Сколько было
    (int)$Mon1 = getdata($lg, 'unions', 'login2');
    (int)$Mon2 = getdata($lg, 'unions', 'login3');
    (int)$Mon3 = getdata($lg, 'unions', 'login4');
    (int)$Mon4 = getdata($lg, 'unions', 'login5');

    //Добавляем
    change($lg, 'unions', 'login2', $Lev1+$Mon1);
    change($lg, 'unions', 'login3', $Lev2+$Mon2);
    change($lg, 'unions', 'login4', $Lev3+$Mon3);
    change($lg, 'unions', 'login5', $Lev4+$Mon4);
  }

	//Дополнительные расчёты для клана
	//1) Отчисление на счёт клана
	$clan = getdata($lg, 'inclan', 'clan');
	if (!empty($clan))
	{
		//Получаем налог, который надо заплатить.
    $curse = getdata($lg, 'economic', 'curse');
    $nalog = getfrom('name', $clan, 'clans', 'nalog');
		$mynalog = $nalog * $curse;
		$money = getdata($lg, 'economic', 'money');
		if ($mynalog > $money)
		{
			$mynalog = $money;
		}
		$money = $money - $mynalog;
    $nalog = round($mynalog / $curse);
		change($lg, 'economic', 'money', $money);
		$adm = getfrom('name', $clan, 'clans', 'login');
		$bill = getdata($adm, 'clans', 'bill');
	  change($adm, 'clans', 'bill', $nalog+$bill);
	}

  //Восстановление ОД
  $OD = getdata($lg, 'inf', 'def');
  $OD = $OD + $num;
  $max = 30 + Level(1, $lg);
  if ($OD > $max)
    $OD = $max;
  change($lg, 'inf', 'def', $OD);

	//2) Отчисление клана в ВАНК

	//И здоровье...
  $lv = getdata($lg, 'hero', 'level');
  $add = $num*$lv*20;
  
  //Восстанавливаем здоровье из-за доп. хар.
  $add = $add + Level(5, $lg)*$lv;

  //Доп. способность
	change($lg, 'hero', 'health', getdata($lg, 'hero', 'health')+$add);

  //И ману...
  $add = $num*10;

  //Восстанавливаем ману из-за доп. хар.
  $add = $add + Level(7, $lg)*$lv;

  change($lg, 'abilities', 'intellegence', getdata($lg, 'abilities', 'intellegence')+$add);

  //Но не больше, чем можно!
	if (getdata($lg, 'abilities', 'intellegence') > getdata($lg, 'abilities', 'cnowledge')*10)
	{
	change($lg, 'abilities', 'intellegence', getdata($lg, 'abilities', 'cnowledge')*10);
	}

	//Но не больше, чем можно!
	if (getdata($lg, 'hero', 'health') > getdata($lg, 'hero', 'level')*100)
	{
	change($lg, 'hero', 'health', getdata($lg, 'hero', 'level')*100);
	}
}

//Действия:
if (!empty($action))
{
	switch($action)
	{
  /* =========================================================================== */
  /* ЗДЕСЬ БЫЛ ВСТАВЛЕН ВЫРЕЗАННЫЙ КУСОК КОДА (НИЖЕ) */

  /* ЗДЕСЬ БЫЛ ВСТАВЛЕН ВЫРЕЗАННЫЙ КУСОК КОДА (ВЫШЕ) */
  /* =========================================================================== */
		//Администрирование
		case 17:
			if (isadmin($lg) != 1)
			{
				$action = 0;
				window.location.href("game.php");
			}
			break;

		//Принять союзы
		case 30:
			if (getdata($lg, 'unions', 'login2') != $lg) {sms(getdata($lg, 'unions', 'login2'), $lg, 'Я объявляю Вам войну!');}
			if (getdata($lg, 'unions', 'login3') != $lg) {sms(getdata($lg, 'unions', 'login3'), $lg, 'Я объявляю Вам войну!');}
			if (getdata($lg, 'unions', 'login4') != $lg) {sms(getdata($lg, 'unions', 'login4'), $lg, 'Я объявляю Вам войну!');}
			if (getdata($lg, 'unions', 'login5') != $lg) {sms(getdata($lg, 'unions', 'login5'), $lg, 'Я объявляю Вам войну!');}
			change ($lg, 'unions', 'login2', $u1);
			change ($lg, 'unions', 'login3', $u2);
			change ($lg, 'unions', 'login4', $u3);
			change ($lg, 'unions', 'login5', $u4);
			if (getdata($lg, 'unions', 'login2') != $lg) {sms(getdata($lg, 'unions', 'login2'), $lg, 'Я предлагаю Вам мир!');}
			if (getdata($lg, 'unions', 'login3') != $lg) {sms(getdata($lg, 'unions', 'login3'), $lg, 'Я предлагаю Вам мир!');}
			if (getdata($lg, 'unions', 'login4') != $lg) {sms(getdata($lg, 'unions', 'login4'), $lg, 'Я предлагаю Вам мир!');}
			if (getdata($lg, 'unions', 'login5') != $lg) {sms(getdata($lg, 'unions', 'login5'), $lg, 'Я предлагаю Вам мир!');}
			break;	

		//Шпионаж
		case 33:
			if (getdata($lg, 'economic', 'money') > getdata($lg, 'economic', 'curse')*getdata($lg, 'hero', 'level')*25)
				{
					//Снимаем деньги
					change($lg, 'economic', 'money', getdata($lg, 'economic', 'money')-getdata($lg, 'economic', 'curse')*getdata($lg, 'hero', 'level')*25);

					//Выводим инфу
					$temptxt = nwe($users);
          messagebox($temptxt, "spy.php?login=".$lg);
				}
				else
					{
            messagebox("У Вас недостаточно денег для шпионажа! Для того, чтобы послать шпиона Вам необходимо ".getdata($lg, 'economic', 'curse')*20*getdata($lg, 'hero', 'level')." ".getdata($lg, 'economic', 'moneyname')."", "spy.php?login=".$lg);
					}
			break;
		
		//Банковский перевод денег
		case 35:
			//Должно быть введено число, большее нуля!
			if (!preg_match("/[0-9]/i", $count))
				{
          messagebox("Введите неотрицательное числовое значение!", "bankomat.php?login=".$lg);
				}
				else
					{
					if ($count < 0)
						{
              messagebox("Введите неотрицательное числовое значение!", "bankomat.php?login=".$lg);
						}
						else
						{
						//Если денег больше, чем есть
						if ($count > getdata($lg, 'economic', 'money'))
							{
							$count = getdata($lg, 'economic', 'money');
							}

						//Снимаем со счёта деньги
						change ($lg, 'economic', 'money', getdata($lg, 'economic', 'money')-$count);

						//Переводим всё в металл
						$count = round($count / getdata($lg, 'economic', 'curse'));

						//Переводим в деньги по другому курсу
						$count = $count * getdata($users, 'economic', 'curse');

						//Снимаем 5 процентов за перевод
						$count = round($count*0.95);

						//Добавляем на счёт деньги
						change ($users, 'economic', 'money', getdata($users, 'economic', 'money')+$count);

						//Отправляем уведомления
						sms($users, 'Банк города '.getdata($lg, 'info', 'capital'), 'На Ваш счёт переведено '.$count.' '.getdata($users, 'economic', 'moneyname').'. Спасибо за Ваше доверие нам. За перевод было снято 5%');
						sms($lg, 'Банк города '.getdata($lg, 'info', 'capital'), 'Вы успешно перевели '.$count.' '.getdata($users, 'economic', 'moneyname').'. Спасибо за Ваше доверие нам. За перевод было снято 5%');

						//Всё ок
            messagebox("Перевод осуществлён. Уведомления разосланы.", "bankomat.php?login=".$lg);
						}
					}
			break;

		//Атака на город
		case 34:
      messagebox("Эта возможность временно недоступна т.к. в данный момент идёт обновление игры. Приносим свои извинения", "game.php?action=1");

			//Проверка на количество посылаемой армии
			if (getdata($lg, 'economic', 'peoples') == 0)
			{
				messagebox("<center>У Вас нет армии, чтобы её посылать</center>", "game.php?action=15");
			}

			//0) Может мы нападали недавно
			$dlt = time() - getdata($lg, 'economic', 'nalog');
			$dlt = round($dlt/3600);

			if ($dlt < 24)
				{
					$action = 31;
					$temptxt = "<center>Ваша армия устала. Ей необходимо отдохнуть. Подождите ещё примерно ".(24 - $dlt)." часов.</center>";
				} else
		{

			//0.5) А если у оппонента даже казармы нет?
			if (getdata($users, 'city', 'build13') == 0)
			{
					$action = 31;
					$temptxt = "<center>Это государство входит в 'Великий северный союз'. Его суверинитет охраняет великая северная организация 'защиты суверенитета государства' ЗСГ. Нападать на него было бы глупо.</center>";
			} else
			{
			//1) Проверяем на союзы
			if (hasunion($lg, $users) != 1)
				{
				//2) Сообщаем о нападении
				sms($users, $lg, "Мне надоело Ваше присутствие здесь. Защищайтесь!");

				//3) Расчитываем, кто выиграл
				$delta = getdata($lg, 'economic', 'peoples')*getdata($lg, 'hero', 'level') - getdata($users, 'economic', 'peoples')*getdata($users, 'hero', 'level');

				//3.5) Подсчитываем все союзные войска
				$useunion = 0;
				//Суммируем все союзные войска, считаем разность. 
				//Если разность больше нуля, то отнимаем от каждого союзника из армии
				//Равные части, по 1/n от армии противника. n - кол-во союзников
				//Если разность меньше нуля, то все армии равны нулю и из армии нападющего вычитаем
				//Сумму всех n+1 армий. 1 - собственное. n = {1;2;3;4}
				if ($delta > 0)
				{
					$ua = uarmy($users);
					$delta = $delta - $ua;

					//Использовали ли мы армию союзников?
					if ($ua != 0)
					{
						$useunion = 1;
					
						//Определяем союзников
						$n[0] = getdata($users, 'unions', 'login2');
						$n[1] = getdata($users, 'unions', 'login3');
						$n[2] = getdata($users, 'unions', 'login4');
						$n[3] = getdata($users, 'unions', 'login5');

						//Проверяем на повторы
						for ($i = 0; $i < 4; $i++)
							for ($j = 0; $j < 4; $j++)
							{
								if (($n[$i] == $n[$j])&&($i != $j))
								{
									$n[$i] = $users;
								}
							}

						//Получаем информацию об армиях всех союзников
						for ($i = 0; $i < 4; $i++)
						{
							$aru[$i] = getdata($n[$i], 'economic', 'peoples');							
						}
			
						//Считаем у каких игроков мы находимся в союзе тоже.
						for ($i = 0; $i < 4; $i++)
						{
							//Если логин является самим собой, то нельзя
							if ($n[$i] != $users)
							{
								//А находимся ли мы во взаимнооднозначном союзе с этим игроком
								for ($k = 0; $k < 4; $k++)
								{
									//Добавляем его наконец-таки к списку
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
										//Если повтор, то забиваем в клетку имя союзника
										$uar[$i][$j] = $n[$i];
									} //галочка
								} //$k
							} //$j
						} //$i

					} //Конец $ua == 0
				} //Конец $delta == 0

				//4) Ну и...
				$result = 0; //(1 - победа, 2 - поражение) для нападающего
				if ($delta > 0)
					{
						$result = 1;
						change ($lg, 'economic', 'peoples', round($delta/getdata($lg, 'hero', 'level')));
						change ($users, 'economic', 'peoples', 0);

						//Грабим ресурсы
						change ($lg, 'economic', 'metal', getdata($lg, 'economic', 'metal') + round(0.4*getdata($users, 'economic', 'metal')));
						change ($lg, 'economic', 'rock', getdata($lg, 'economic', 'rock') + round(0.4*getdata($users, 'economic', 'rock')));
						change ($lg, 'economic', 'wood', getdata($lg, 'economic', 'wood') + round(0.4*getdata($users, 'economic', 'wood')));
						$temptxt = "<center><h2>Результаты осады замка ".getdata($users, 'info', 'capital')."</h2>";
						$temptxt = $temptxt."<br>";
						$temptxt = $temptxt."<table border=1 width=40% CELLSPACING=0 CELLPADDING=0>";
						$temptxt = $temptxt."<tr><td align=center width=40%>Награбленное</td><td align=center>Сколько</td></tr>";
						$temptxt = $temptxt."<tr><td align=center>Металл</td><td align=center>".round(0.6*getdata($users, 'economic', 'metal'))."</td>";
						$temptxt = $temptxt."<tr><td align=center>Камень</td><td align=center>".round(0.6*getdata($users, 'economic', 'rock'))."</td>";
						$temptxt = $temptxt."<tr><td align=center>Дерево</td><td align=center>".round(0.6*getdata($users, 'economic', 'wood'))."</td>";
						$temptxt = $temptxt."</table>";
						$temptxt = $temptxt."</center>";
						change ($users, 'economic', 'metal', round(0.6*getdata($users, 'economic', 'metal')));
						change ($users, 'economic', 'rock', round(0.6*getdata($users, 'economic', 'rock')));
						change ($users, 'economic', 'wood', round(0.6*getdata($users, 'economic', 'wood')));
						sms($users, $lg, 'Я же сказал, что Вы мне надоели. Ха ха ха! Я забираю все Ваши ресурсы!');

						//Если использовали армию союзников, то и её сбрасываем на 0
						if ($useunion == 1)
						{
							$tmp = getdata($users, 'hero', 'name');
							$castle = getdata($users, 'info', 'capital');
							for ($k = 0; $k < 4; $k++)
							{
								//Если мы стоим у союзника в союзе
								for ($l = 0; $l < 4; $l++)
								{
									if ($uar[$k][$l] == $users)
									{
										change($n[$k], 'economic', 'peoples', 0);
										sms($n[$k], 'Военный штаб игрока '.$tmp, 'По союзному договору Ваша армия учавствовала в обороне замка '.$castle.'. Оборона закончилась неудачей. Все союзные армии потерпели поражение.');
									}
								} //$l
							} //$k
						} //$userunion
					} 
					else //Проигрыш при осаде (выиграл оборонявшийся)
					{
						//Если использовали армию союзников, то вычитаем из каждой поровну
						if ($useunion == 1)
						{
							//Выбираем всех союзников, у которых армия не ноль. Сортируем их в случайном порядке и
							//по очереди вычитаем армию
							//Сначала, естественно, у самого обороняющегося

							//Считаем на сколько частей делить
							$count = 0;
							for($i = 0; $i < 4; $i++)
							{
								$flag = 0;

								//Если у союзника есть армия, тогда можно проверить
								if ($aru[$i] != 0)
								{
									//Проверяем все 4 слота
									for ($j = 0; $j < 4; $j++)
									{
										//Если мы хотябы в одном слоте
										if (($uar[$i][$j] == $users)&&($n[$i] != $users))
										{
											$flag = 1;
										}//$uar
									} //$j

									//Если мы хотябы 1 раз включены
									if ($flag == 1)
									{
										$count++;
									} //$flag
								} //$aru
							} //$i

							//Делаем дельту положительной
							$delta = abs($delta);

							//Определяем долю на союзников
							$part = $delta / $count;

							//Важные данные
							$tmp = getdata($users, 'hero', 'name');
							$castle = getdata($users, 'info', 'capital');

							//Теперь от каждого союзника с двухсторонней связью отнимаем армию, если она у него не ноль
							for ($i = 0; $i < 4; $i++)
							{
								//Если армия не ноль
								if ($aru[$i] != 0)
								{
									//Определяем, есть ли галочка
									$flag = 0;
									for ($j = 0; $j < 4; $j++)
									{
										//Так есть или нет
										if ($uar[$i][$j] == $users)
										{
											$flag = 1;
										} //$uar
									} //$j
									
									//Если всёже есть (отнимаем грубо. М.б. отнимается меншее кол-во. Ну и пусть пофигу)
									$aru[$i] = round($part / getdata($n[$i], 'hero', 'level'));

									//Если армия меньше нуля, то ставим ноль
									//Вот в этом-то и заключается преимущество союзов. Армии меньше дохнет.
									if ($aru[$i] < 0)
									{
										$aru[$i] = 0;
									} //$aru

									//Имеем ли мы право лапать чужую армию
									$can = 0;
									for ($j = 0; $j < 4; $j++)
									{
										if (($uar[$i][$j] == $users)&&($can == 0))
										{
											$can = 1;
										}
									}

									//Рассылаем мессаги и устанавливаем новое значение армии союзника
									if (($n[$i] != $lg)&&($n[$i] != $users)&&($can == 1))
									{
										//Устанавливаем новое значение армии союзника
										if ($aru[$i] < getdata($n[$i], 'economic', 'peoples'))
										{
											change($n[$i], 'economic', 'peoples', $aru[$i]);
										}

										sms($n[$i], 'Военный штаб игрока '.$tmp, 'По союзному договору Ваша армия учавствовала в обороне замка '.$castle.'. Оборона закончилась успешно. Ваша армия помогла удержать замок. Я, '.$tmp.', выражаю Вам огромную благодарность.');
									} //$lg
								} //$aru
							} //$i

							//Собственные потери - всегда всё!
							change ($users, 'economic', 'peoples', 0);

							//Потери нападавшего тоже
							change ($lg, 'economic', 'peoples', 0);

							//Деньги добавляем (по 1/5 каждому союзнику) //дописать
							$vl = getdata ($lg, 'economic', 'money') / getdata ($lg, 'economic', 'curse');
							change ($lg, 'economic', 'money', 0);
							$vl = $vl * getdata ($users, 'economic', 'curse');
							change ($users, 'economic', 'money', getdata($users, 'economic', 'money') + $vl);

							//Приносим извинения							
							sms($users, $lg, 'О нет! Я ошибся. Приношу свои извинения за такое вторжение. Надеюсь, что Вы не будете пока нападать на меня, ведь вся моя армия пала под стенами Вашего замка. В знак того, что мои слова правда я передаю все деньги моего королевства Вам. Перевожу их по курсу в Вашу валюту. Итого: '.$vl.' '.getdata($users, "economic", "moneyname").'. С Уважением, '.getdata($lg, 'hero', 'name'));
						}
						else //Армия союзников не использована
						{
							change ($users, 'economic', 'peoples', round(abs($delta/getdata($users, 'hero', 'level'))));
							change ($lg, 'economic', 'peoples', 0);
							$vl = getdata ($lg, 'economic', 'money') / getdata ($lg, 'economic', 'curse');
							change ($lg, 'economic', 'money', 0);
							$vl = $vl * getdata ($users, 'economic', 'curse');
							change ($users, 'economic', 'money', getdata($users, 'economic', 'money') + $vl);
							sms($users, $lg, 'О нет! Я ошибся. Приношу свои извинения за такое вторжение. Надеюсь, что Вы не будете пока нападать на меня, ведь вся моя армия пала под стенами Вашего замка. В знак того, что мои слова правда я передаю все деньги моего королевства Вам. Перевожу их по курсу в Вашу валюту. Итого: '.$vl.' '.getdata($users, "economic", "moneyname").'. С Уважением, '.getdata($lg, 'hero', 'name'));
						}
					}

				//5) Подводим итоги
				$action = 31;
				change($lg, 'economic', 'nalog', time());
				}
				else
					{
					$action = 31;
					$temptxt = "<center>Вы не можете напасть на своего союзника</center>";
					}
			}
		}
			break;

		//Набор армии
		case 36:

			//Проверяем курс
			$err = 0;
			if (!empty($hyperhow))
				{

				if (!preg_match("/[0-9]/i", $hyperhow))
					{
					$err = 1;
					} else
						{
						if (($hyperhow < 0)||($hyperhow == 0))
							{
							$err = 1;
							}
						}
				}

			//Всё нормально?
			if ($err != 0)
				{
					$action = 29;
				} else
					{
						//Сколько стоит
						$sum = $hyperhow*getdata($lg, 'economic', 'curse')*5;

						//Много или нормально?
						if ($sum > getdata($lg, 'economic', 'money'))
							{
							$action = 31;
							$temptxt = "<center>У Вас недостаточно денег для найма армии. Вам необходимо: ".$sum." ".getdata($lg, 'economic', 'moneyname'."</center>");
							} else
								{
									//Забираем деньги
									change($lg, 'economic', 'money', getdata($lg, 'economic', 'money')-$sum);

									//Добавляем армию
									change($lg, 'economic', 'peoples', getdata($lg, 'economic', 'peoples')+$hyperhow);
			
									//Выводим результат
									$action = 31;
									$temptxt = "<center>Вы успешно наняли ".$hyperhow." бойцов в Вашу армию за ".$sum. " ".getdata($lg, 'economic', 'moneyname')."</center>";
								}
					}
			break;
	}
}

//Действия
if ((($action > 19)&&($action < 29))||(($action > 68)&&($action < 78)))
{
	if (getdata($lg, 'hero', 'upgrade') == 0)
	{
		$action = 1;
	}
	else
	{
		switch($action)
		{
			case 20:
				$up = getdata($lg, 'hero', 'upgrade');
				$up--;
				change($lg, 'hero', 'upgrade', $up);
				$nw = getdata($lg, 'abilities', 'power');
				$nw++;
				change($lg, 'abilities', 'power', $nw);
				$action = 1;
				break;
			case 69:
				$up = getdata($lg, 'hero', 'upgrade');
				change($lg, 'hero', 'upgrade', 0);
				$nw = getdata($lg, 'abilities', 'power');
				$nw=$nw+$up;
				change($lg, 'abilities', 'power', $nw);
				$action = 1;
				break;
			case 21:
				$up = getdata($lg, 'hero', 'upgrade');
				$up--;
				change($lg, 'hero', 'upgrade', $up);
				$nw = getdata($lg, 'abilities', 'protect');
				$nw++;
				change($lg, 'abilities', 'protect', $nw);
				$action = 1;
				break;
			case 70:
				$up = getdata($lg, 'hero', 'upgrade');
				change($lg, 'hero', 'upgrade', 0);
				$nw = getdata($lg, 'abilities', 'protect');
				$nw=$nw+$up;
				change($lg, 'abilities', 'protect', $nw);
				$action = 1;
				break;
			case 22:
				$up = getdata($lg, 'hero', 'upgrade');
				$up--;
				change($lg, 'hero', 'upgrade', $up);
				$nw = getdata($lg, 'abilities', 'dexterity');
				$nw++;
				change($lg, 'abilities', 'dexterity', $nw);
				$action = 1;
				break;
			case 71:
				$up = getdata($lg, 'hero', 'upgrade');
				change($lg, 'hero', 'upgrade', 0);
				$nw = getdata($lg, 'abilities', 'dexterity');
				$nw=$nw+$up;
				change($lg, 'abilities', 'dexterity', $nw);
				$action = 1;
				break;
			case 23:
				$up = getdata($lg, 'hero', 'upgrade');
				$up--;
				change($lg, 'hero', 'upgrade', $up);
				$nw = getdata($lg, 'abilities', 'cnowledge');
				$nw++;
				change($lg, 'abilities', 'cnowledge', $nw);
				$action = 1;
				break;
			case 72:
				$up = getdata($lg, 'hero', 'upgrade');
				change($lg, 'hero', 'upgrade', 0);
				$nw = getdata($lg, 'abilities', 'cnowledge');
				$nw=$nw+$up;
				change($lg, 'abilities', 'cnowledge', $nw);
				$action = 1;
				break;
			case 24:
				$up = getdata($lg, 'hero', 'upgrade');
				$up--;
				change($lg, 'hero', 'upgrade', $up);
				$nw = getdata($lg, 'abilities', 'charism');
				$nw++;
				change($lg, 'abilities', 'charism', $nw);
				$action = 1;
				break;
			case 73:
				$up = getdata($lg, 'hero', 'upgrade');
				change($lg, 'hero', 'upgrade', 0);
				$nw = getdata($lg, 'abilities', 'charism');
				$nw=$nw+$up;
				change($lg, 'abilities', 'charism', $nw);
				$action = 1;
				break;
			case 25:
				$up = getdata($lg, 'hero', 'upgrade');
				$up--;
				change($lg, 'hero', 'upgrade', $up);
				$nw = getdata($lg, 'abilities', 'intellegence');
				$nw++;
				change($lg, 'abilities', 'intellegence', $nw);
				$action = 1;
				break;
			case 74:
				$up = getdata($lg, 'hero', 'upgrade');
				change($lg, 'hero', 'upgrade', 0);
				$nw = getdata($lg, 'abilities', 'intellegence');
				$nw=$nw+$up;
				change($lg, 'abilities', 'intellegence', $nw);
				$action = 1;
				break;
			case 26:
				$up = getdata($lg, 'hero', 'upgrade');
				$up--;
				change($lg, 'hero', 'upgrade', $up);
				$nw = getdata($lg, 'abilities', 'combatmagic');
				$nw++;
				change($lg, 'abilities', 'combatmagic', $nw);
				$action = 1;
				break;
			case 75:
				$up = getdata($lg, 'hero', 'upgrade');
				change($lg, 'hero', 'upgrade', 0);
				$nw = getdata($lg, 'abilities', 'combatmagic');
				$nw=$nw+$up;
				change($lg, 'abilities', 'combatmagic', $nw);
				$action = 1;
				break;
			case 27:
				$up = getdata($lg, 'hero', 'upgrade');
				$up--;
				change($lg, 'hero', 'upgrade', $up);
				$nw = getdata($lg, 'abilities', 'naturemagic');
				$nw++;
				change($lg, 'abilities', 'naturemagic', $nw);
				$action = 1;
				break;
			case 76:
				$up = getdata($lg, 'hero', 'upgrade');
				change($lg, 'hero', 'upgrade', 0);
				$nw = getdata($lg, 'abilities', 'naturemagic');
				$nw=$nw+$up;
				change($lg, 'abilities', 'naturemagic', $nw);
				$action = 1;
				break;
			case 28:
				$up = getdata($lg, 'hero', 'upgrade');
				$up--;
				change($lg, 'hero', 'upgrade', $up);
				$nw = getdata($lg, 'abilities', 'mindmagic');
				$nw++;
				change($lg, 'abilities', 'mindmagic', $nw);
				$action = 1;
				break;
			case 77:
				$up = getdata($lg, 'hero', 'upgrade');
				change($lg, 'hero', 'upgrade', 0);
				$nw = getdata($lg, 'abilities', 'mindmagic');
				$nw=$nw+$up;
				change($lg, 'abilities', 'mindmagic', $nw);
				$action = 1;
				break;
		}
	$action = 1;
	echo("<script>window.location.href('game.php?action=1');</script>");
	}
}

//Создаём таблицу
echo ("<table border=$dev width=100% CELLSPACING=0 CELLPADDING=0>\n");
echo ("<tr><td colspan=3 align=center><font color=yellow size=6><img src='images/logo.gif' width=640 height=60></font></td></tr>\n");

//Показываем меню пользователя
showmenu($lg, $name);
echo("<td colspan=2>");

//Показываем блок
if (!empty($userlogin))
{
	if ((finduser($al, $ap) == 1)&&(isadmin($al) == 1))
	{
		if ($do == 2)
		{
			kickuser($userlogin);
		}
	}
	else
	{
		$userlogin = "";
	}
}
if ($action == 0)
{
	documentation();
}
else
{
  if (!empty($CellNum))
    $temptxt = $CellNum;
	showblock($action, trim($lg), $pw, $userlogin, getdata($lg, 'hero', 'location'), $temptxt);
  if ($action == 0)
    moveto("game.php?action=1");
}
echo("</td></tr>");
echo ("<tr>");
echo("<td valign=top colspan=2>");

//Показываем статус
$days = showstatus($lg);
echo("</td>");
//Луна
  echo("<td align=right width=1%><table border=1 width=1% cellpadding=0 cellspacing=0><tr><td align=center><img src='images/moon/".$days.".jpg' alt='Лунная фаза'></td></tr></table></td>");
echo ("</tr>");
echo ("</table>\n");
echo("<br><center><table border=1 CELLSPACING=0 CELLPADDING=0><tr><td align=center><font color=blue><b>Программист:</b> Елисеев Дмитрий; <b>Дизайнер:</b> Титберия Олег</font></td></tr><tr><td align=center>В игре использована музыка группы Space: 'Space opera', 'Velvet rape', 'Ballad for space lovers'</td></tr></table></center></html>");

//Автообновление каждые 2 минуты
if ($bat == 0)
{
	echo ("\n<META HTTP-EQUIV='REFRESH' CONTENT=120>\n");
}

?>