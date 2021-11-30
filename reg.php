<?
include "functions.php";

if (empty($pfinal))
	{
  
  //Временно отключаем блокировку IP
	if ((findip($REMOTE_ADDR) == 1)&&(1 == 0))
		{
    echo ("<link rel='stylesheet' type='text/css' href='style.css'/>");
		echo ("<body background='images\back.jpe'>");
		echo ("<font color=green><b>Внимание!</b> С этого IP адреса кто-то уже зарегистрирован! Регистрация невозможна. Свяжитесь с администратором для уточнения деталей.<br></font>");
		echo ("<a href='index.php'>Назад</a>");
		exit();
		}
	?>
        <script>
        function help()
        {
        window.open("help.php");
        }
        </script>
	<html>
	<title>Регистрация в игре Native Land</title>
	<body background="images\back.jpe">
	<center><h2><font color=yellow>Страница регистрации</font></h2></center>
	<center><h4><a href="javascript:help();"><font color=white>Помощь</font></a></h4></center>
  <center>Поля, помеченные символом "*" (звёздочка) обязательны для заполнения</center>
    <form action="reg.php" method=post>
	<input type="hidden" name='pfinal' value=123>
	<center>
	<table border=1 width=95% cols=3 CELLSPACING=0 CELLPADDING=0>
    <tr width=24%><td align=center><font color=#006600><b>Параметр:</b></td><td align=center><font color=#006600><b>Значение:</b></td><td align=center><font color=#006600><b>Комментарий:</b></td></tr>
	<tr><td><font color=white>Имя пользователя:<font color=red>*</font></td><td align = center><input type='text' name='login' style='background:#000099; font-WEIGHT: BOLD; COLOR: white; cursor: hand; list-style: lower-alpha; filter: Alpha(Opacity=20, FinishOpacity=100, Style=3)'></td><td>Под этим именем Вы будете входить в систему. Оно должно состоять из английских букв или (и) цифр. В нём не может быть пробелов.</td></tr>
    <tr><td><font color=white>Пароль:<font color=red>*</font></td><td align = center><input type='password' name='passwd' style='background:#000099; font-WEIGHT: BOLD; COLOR: white; cursor: hand; list-style: lower-alpha; filter: Alpha(Opacity=20, FinishOpacity=100, Style=3)'></td><td>Также, Вам будет необходимо ввести этот пароль. Это необходимо для безопастности</td></tr>
	<tr><td><font color=white>Фамилия:<font color=red>*</font></td><td align = center><input type='text' name='surname' style='background:#000099; font-WEIGHT: BOLD; COLOR: white; cursor: hand; list-style: lower-alpha; filter: Alpha(Opacity=20, FinishOpacity=100, Style=3)'></td><td>Ваша фамилия необходима для заполнения базы.</td></tr>
	<tr><td><font color=white>Имя:<font color=red>*</font></td><td align = center><input type='text' name='mname' style='background:#000099; font-WEIGHT: BOLD; COLOR: white; cursor: hand; list-style: lower-alpha; filter: Alpha(Opacity=20, FinishOpacity=100, Style=3)'></td><td>Ваше имя необходимо для общения с Вами.</td></tr>
	<tr><td><font color=white>Страна проживания:<font color=red>*</font></td><td align = center><input type='text' style='background:#000099; font-WEIGHT: BOLD; COLOR: white; cursor: hand; list-style: lower-alpha; filter: Alpha(Opacity=20, FinishOpacity=100, Style=3)' name='country'></td><td>Необходима для статистики сведений игроков. (Конфиденциально)</td></tr>
	<tr><td><font color=white>Город:<font color=red>*</font></td><td align = center><input type='text' name='city' style='background:#000099; font-WEIGHT: BOLD; COLOR: white; cursor: hand; list-style: lower-alpha; filter: Alpha(Opacity=20, FinishOpacity=100, Style=3)'></td><td>Необходим для статистики сведений игроков. (Конфиденциально)</td></tr>
	<tr><td><font color=white>E-Mail:<font color=red>*</font></td><td align = center><input type='text' name='email' style='background:#000099; font-WEIGHT: BOLD; COLOR: white; cursor: hand; list-style: lower-alpha; filter: Alpha(Opacity=20, FinishOpacity=100, Style=3)'></td><td>Необходим для связи с Вами.</td></tr>
	<tr><td><font color=white>URL:</td><td align = center><input type='text' name='url' style='background:#000099; font-WEIGHT: BOLD; COLOR: white; cursor: hand; list-style: lower-alpha; filter: Alpha(Opacity=20, FinishOpacity=100, Style=3)'></td><td>Если у Вас есть ваш собственный сайт, будем Вам признательны.</td></tr>
	<tr><td><font color=white>ICQ:</td><td align = center><input type='text' name='icq' style='background:#000099; font-WEIGHT: BOLD; COLOR: white; cursor: hand; list-style: lower-alpha; filter: Alpha(Opacity=20, FinishOpacity=100, Style=3)'></td><td>Если у Вас есть ICQ, то для того, чтобы другие игроки могли связаться с Вами, укажите его здесь.</td></tr>
	<tr><td><font color=white>О себе:</td><td align = center colspan = 2><textarea cols=70 rows=10 name='osebe' maxlength=255 style='background:#000099; font-WEIGHT: BOLD; COLOR: white; cursor: hand; list-style: lower-alpha; filter: Alpha(Opacity=20, FinishOpacity=100, Style=3)'></textarea></td></tr>
	<tr><td><font color=white>Контрольный вопрос:<font color=red>*</font></td><td align = center><input type='text' name='cquest' style='background:#000099; font-WEIGHT: BOLD; COLOR: white; cursor: hand; list-style: lower-alpha; filter: Alpha(Opacity=20, FinishOpacity=100, Style=3)'></td><td>Если Вы случайно потеряете Ваш пароль, то, чтобы восстановить его, сервер задаст Вам вопрос.</td></tr>
	<tr><td><font color=white>Контрольный ответ:<font color=red>*</font></td><td align = center><input type='text' style='background:#000099; font-WEIGHT: BOLD; COLOR: white; cursor: hand; list-style: lower-alpha; filter: Alpha(Opacity=20, FinishOpacity=100, Style=3)' name='cansw'></td><td>И если Вы введёте этот ответ, то Вам вернут утерянный пароль.</td></tr>
	<tr><td><font color=blue>Имя героя<font color=red>*</font></td><td align = center><input type='text' name='heroname' style='background:#000099; font-WEIGHT: BOLD; COLOR: white; cursor: hand; list-style: lower-alpha; filter: Alpha(Opacity=20, FinishOpacity=100, Style=3)'></td><td>Под этим именем Вы будете известны в игре, как управляющий Вашим государством.</td></tr>
    <tr><td><font color=blue>Раса героя:</td><td align = center><select name='race' style='background:#000099; font-WEIGHT: BOLD; COLOR: white; cursor: hand; list-style: lower-alpha; filter: Alpha(Opacity=20, FinishOpacity=100, Style=3)'>
	<option value='people'>Человек</option>
	<option value='elf'>Эльф</option>
	<option value='hnom'>Гном</option>
	<option value='druid'>Друид</option>
	<option value='necro'>Некромант</option>
	<option value='hell'>Еретик</option>
	</select></td><td align=center>
	<table border = 1 CELLSPACING=0 CELLPADDING=0>
	<tr><td align=center>Раса:</td><td>Сила:</td><td>Защита:</td><td>Защита от магии:</td><td>Выносливость:</td><td>Колдовская сила:</td><td>Знания:</td></tr>
	<tr><td align=center>Человек</td><td align=center>+1</td><td align=center>+2</td><td align=center>0</td><td align=center>+2</td><td align=center>0</td><td align=center>+1</td></tr>
    <tr><td align=center>Эльф</td><td align=center>+2</td><td align=center>0</td><td align=center>+2</td><td align=center>0</td><td align=center>+1</td><td align=center>+1</td></tr>
    <tr><td align=center>Гном</td><td align=center>+2</td><td align=center>+2</td><td align=center>+2</td><td align=center>0</td><td align=center>0</td><td align=center>0</td></tr>
    <tr><td align=center>Друид</td><td align=center>0</td><td align=center>+2</td><td align=center>0</td><td align=center>0</td><td align=center>+1</td><td align=center>+3</td></tr>
    <tr><td align=center>Некромант</td><td align=center>+2</td><td align=center>0</td><td align=center>+1</td><td align=center>0</td><td align=center>+1</td><td align=center>+2</td></tr>
    <tr><td align=center>Еретик</td><td align=center>+1</td><td align=center>+2</td><td align=center>0</td><td align=center>+1</td><td align=center>+1</td><td align=center>+1</td></tr>
	</table>
	</td></tr>
	<tr><td><font color=blue>Тип героя:</td><td align = center><select name='type' style='background:#000099; font-WEIGHT: BOLD; COLOR: white; cursor: hand; list-style: lower-alpha; filter: Alpha(Opacity=20, FinishOpacity=100, Style=3)'>
	<option value='knight'>Рыцарь</option>
	<option value='archer'>Стрелок</option>
	<option value='mag'>Маг</option>
	<option value='lekar'>Целитель</option>
	<option value='barbarian'>Варвар</option>
	<option value='wizard'>Волшебник</option>
	</select></td><td align=center>
	<table border = 1 CELLSPACING=0 CELLPADDING=0>
	<tr><td align=center>Тип:</td><td>Сила:</td><td>Защита</td><td>Защита от магии:</td><td>Выносливость:</td><td>Колдовская сила:</td><td>Знания:</td></tr>
	<tr><td align=center>Рыцарь</td><td align=center>+2</td><td align=center>+2</td><td align=center>0</td><td align=center>+1</td><td align=center>0</td><td align=center>0</td></tr>
    <tr><td align=center>Стрелок</td><td align=center>+1</td><td align=center>+2</td><td align=center>0</td><td align=center>+2</td><td align=center>0</td><td align=center>0</td></tr>
    <tr><td align=center>Маг</td><td align=center>0</td><td align=center>0</td><td align=center>0</td><td align=center>0</td><td align=center>+3</td><td align=center>+2</td></tr>
    <tr><td align=center>Целитель</td><td align=center>0</td><td align=center>0</td><td align=center>0</td><td align=center>0</td><td align=center>+2</td><td align=center>+3</td></tr>
    <tr><td align=center>Волшебник</td><td align=center>0</td><td align=center>0</td><td align=center>0</td><td align=center>0</td><td align=center>+4</td><td align=center>+1</td></tr>
    <tr><td align=center>Варвар</td><td align=center>+3</td><td align=center>+1</td><td align=center>0</td><td align=center>+1</td><td align=center>0</td><td align=center>0</td></tr>
	</table>	
	</td></tr>
    <tr><td><font color=yellow>Название денег:<font color=red>*</font></td><td align = center><input type='text' name='moneyname' style='background:#000099; font-WEIGHT: BOLD; COLOR: white; cursor: hand; list-style: lower-alpha; filter: Alpha(Opacity=20, FinishOpacity=100, Style=3)'></td><td>Как будут называться деньги в Вашей стране (мн. ч. например, [рубли])</td></tr> 
	<tr><td><font color=yellow>Курс денег к металлу:<font color=red>*</font></td><td align = center><input type='text' name='curse' style='background:#000099; font-WEIGHT: BOLD; COLOR: white; cursor: hand; list-style: lower-alpha; filter: Alpha(Opacity=20, FinishOpacity=100, Style=3)' maxlength=3></td><td>Целое число, большее нуля. Т.е. за 1 единицу метала, Вы заплатите n единиц золота в Вашей валюте.</td></tr>
    <tr><td><font color=yellow>Название страны:<font color=red>*</font></td><td align = center><input type='text' name='gcountry' style='background:#000099; font-WEIGHT: BOLD; COLOR: white; cursor: hand; list-style: lower-alpha; filter: Alpha(Opacity=20, FinishOpacity=100, Style=3)'></td><td>Именно как управляющим этим государством Вы будете известны в игре.</td></tr>
    <tr><td><font color=yellow>Название столицы:<font color=red>*</font></td><td align = center><input type='text' name='gcapital' style='background:#000099; font-WEIGHT: BOLD; COLOR: white; cursor: hand; list-style: lower-alpha; filter: Alpha(Opacity=20, FinishOpacity=100, Style=3)'></td><td>Как будет называться столица Вашего государства</td></tr>
    <tr><td><font color=yellow>Богатства страны:<font color=red>*</font></td><td align = center><select name='res' style='background:#000099; font-WEIGHT: BOLD; COLOR: white; cursor: hand; list-style: lower-alpha; filter: Alpha(Opacity=20, FinishOpacity=100, Style=3)'>
	<option value='random'>Любой</option>
	<option value='metal'>Металл</option>
	<option value='rock'>Камень</option>
	<option value='wood'>Дерево</option>
	</select></td><td>Какой из трёх ресурсов будет добываться в Вашей стране? Остальные два Вам придётся закупать у своих союзников или импортировать из других стран.</td></tr>
	</table>
	<br><input type='submit' name='prefinish' value=' Зарегистрироваться ' style='background:#000099; font-WEIGHT: BOLD; COLOR: white; cursor: hand; list-style: lower-alpha; filter: Alpha(Opacity=20, FinishOpacity=100, Style=3)'>
	</center>
	</form>
	<?
	}
	else
	{

	//Проверям длину полей и корректность e-mail адреса и числовое значение поля курса
	$login = trim($login);
	$ohno = 0;
	$err = 0;
	$ml = 0;
	if ((strlen($login) < 2)||(strlen($passwd) < 2)||(strlen($surname) < 2)||(strlen($mname) < 2)||(strlen($country) < 2)||(strlen($city) < 2)||(strlen($email) < 2)||(strlen($heroname) < 2)||(strlen($race) < 2)||(strlen($type) < 2)||(strlen($moneyname) < 2)||(strlen($curse) < 1)||(strlen($gcountry) < 2)||(strlen($gcapital) < 2)||(strlen($res) < 2))
	{
		$ohno = 1;
	}

	//Проверяем ник на корректность
	if (!empty($login))
	{
    $login = trim($login);
		if (!preg_match("/[0-9a-z]/i", $login))
		{
			$lge = 1;
		}
    if ((strtolower($login) == 'settings')||(strtolower($login) == 'admin'))
    {
      $lge = 1;
    }
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
	if (!empty($curse))
	{
		if (!preg_match("/[0-9]/i", $curse))
		{
			$err = 1;
		}
	}

	//Есть ошибки?
	if (($ohno != 0)||($err != 0)||($ml != 0)||($lge != 0))
	{
		echo ("<br>");
	    echo ("<script language=JavaScript>");
		echo ("function rt()");
	    echo ("{");
	    echo ("window.history.go(-1);");
		echo ("}");
	    echo ("</script>");
		echo ("<body background='images\back.jpe'>");
		if ($ohno != 0)
		{
			echo ("<font color=green>Ошибка. Не все поля заполнены или длина одного из полей меньше двух символов. Заполните все поля.<br><br>");
		}
	    if ($lge != 0)
		{
			echo ("Имя пользователя может состоять только из английских букв и(или) цифр. Также, для регистрации запрещены имена Admin и Settings<br><br>");
		}
	    if ($ml != 0)
		{
			echo ("Также проверьте правильность заполнения e-mail адреса.<br><br>");
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

	//А может уже зареган
	if (hasuser($login) == 1)
		{
		echo ("<br>");
	    echo ("<script language=JavaScript>");
		echo ("function rt()");
	    echo ("{");
	    echo ("window.history.go(-1);");
		echo ("}");
	    echo ("</script>");	
		echo ("<body background='images\back.jpe'>");
		echo ("<font color=green>Внимание! Пользователь с таким 'именем пользователя' уже зарегистрирован. Смените имя пользователя!</font>");
		?>
		<form action="javascript:rt();">
	    <input type='submit' name='stop' value='  Назад  ' style='background:#000099; font-WEIGHT: BOLD; COLOR: white; cursor: hand; list-style: lower-alpha; filter: Alpha(Opacity=20, FinishOpacity=100, Style=3)'>      
		</form>
	    <?
		exit();		
		}
		else
		{
			//Всё нормально. Продолжаем работу.

			//Залогиневаем юзера :)
			setcookie("nativeland", $login, time()+3600*24);
			setcookie("password", $passwd, time()+3600*24);

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
			
			//Соединяемся с базой
			baselink();

			//Информация о битвах игрока
			mysql_query ("insert into battles values('$login', 0, '0', 0);");

      //Информация об армии игрока
			mysql_query ("insert into army values('$login', 0, 0, 0, 0);");

			//Информация о битве игрока
			mysql_query ("insert into battle values('$login', '0', '0', '0', '0', '0', '', '0', '0', '0');");

			//Заполняем информацию о статусе
			mysql_query("insert into status values ('$login', '0', '0', '0', '0', '0');");

			//Заполняем остальную информацию
      $tm = time();
			mysql_query("insert into inf values ('$login', '$icq', '$osebe', '0', '$tm', '0', '0', '0', '0', '0', '0', '0', '0', '0');");

			//Контрольные
			mysql_query ("insert into lostpass values ('$login', '$cquest', '$cansw');");

			//Заполняем информацию об игроке
			mysql_query("insert into users values ('$login', '$passwd', '$surname', '$mname', '$city', '$country', '$email', '$url');");

			//Заполняем	информацию об персонаже игрока
			mysql_query("insert into hero values ('$login', '$heroname', '$val', '$lv', '5', '$race', '$type', '$hl', '$login');");

			//Заполняем информацию об экономике королевства
			mysql_query("insert into economic values ('$login', '$r1', '$r2', '$r3', '$r4', '$curse', '$moneyname', '$p', '$n');");

			//Заполняем информацию о заклинаниях игрока
			$r = rand(1, 3);
			if ($r == 2) 
			{
				$r = 6;
			}
			if ($r == 3) 
			{
				$r = 12;
			}
			mysql_query("insert into magic values ('$login', ".$r.", 0, 0, 0, 0, 0);");

      //Информация о бутылках
			mysql_query("insert into bottles values ('$login', '0', '0', '0', '0', '0', '0', '0', '0', '0', '0', '0', '0', '0', '0', '0');");

      //Заполняем информацию о временных переменных
			mysql_query("insert into temp values ('$login', '', '');");

			//Заполняем информацию о вещах на персонаже (артефакты)
			mysql_query("insert into items values ('$login', '$val', '$val', '$val', '$val', '$val', '$val', '$val', '$val', '$val', '$val', '$val');");
			
			//Заполняем таблицу о кланах
//			mysql_query("insert into inclan values ('$login', '0', '0', '0');");

			//Случайный ресурс
			if ($res == "random")
				{
				$i = rand(2, 0);
				if ($i == 0)
				   {
				   $res = "metal";
				   }
				if ($i == 1)
				   {
				   $res = "rock";
				   }
				if ($i == 2)
				   {
				   $res = "wood";
				   }
				}
			//Заполняем информацию о королевстве
			mysql_query("insert into info values ('$login', '$gcountry', '$gcapital', '$res');");

			//Заполняем информацию о постройках в замке
			mysql_query("insert into city values ('$login', '$login', '$val', '$val', '$val', '$val', '$val', '$val', '$val', '$val', '$val', '$val', '$val', '$val', '$val', '$val', '$val', '$val', '$val', '$val', '$val', '$val');");

			//Заполняем информацию о времени
			$t = time();
			mysql_query("insert into time values ('$login', '$t', '10', '0');");

			//Заполняем информацию о союзе игрока
			mysql_query("insert into unions values ('$login', '$login', '$login', '$login', '$login');");

			//Информация об IP адресах
			mysql_query("insert into ip values ('$login', '$REMOTE_ADDR');");

			//Информация о доп. городах
			mysql_query("insert into help values ('$login', '0', '0', '0', '0');");

			//Расчитываем способности игрока
			$power = 1;
			$protect = 1;
			$magicpower = 0;
			$know = 1;
			$charism = 1;
			$dexterity = 1;
			$intel = 1;
			$naturemagic = 1;
			$combatmagic = 0;
			$mindmagic = 0;
      $ab = "0";

			switch ($race)
			{
			case "people":
				$power++;
      	$protect++;
	     	$protect++;
				$charism++;
        $charism++;
				$know++;
				break;
			case "elf":
				$power++;
				$power++;
			    $dexterity++;
				$dexterity++;
				$charism++;
				$know++;
				break;
			case "hnom":
				$power++;
				$power++;
	    	$protect++;
				$protect++;
				$dexterity++;
				$dexterity++;
				break;
			case "druid":
				$protect++;
			    $know++;
			    $know++;
			    $know++;
				$naturemagic++;
				$naturemagic++;
				break;
			case "necro":
				$power++;
				$power++;
				$know++;
				$know++;
				$naturemagic++;
        $charism++;
				break;
			case "hell":
				$power++;
				$protect++;
				$protect++;
				$know++;
				$naturemagic++;
				$charism++;
				break;
			}

			switch ($type)
			{
			case "knight":
				$power++;
				$power++;
				$protect++;
				$protect++;
				$charism++;
        $ab = "N3";
				break;
			case "archer":
				$power++;
				$protect++;
				$protect++;
        $charism++;
        $charism++;
        $ab = "N25";
				break;
			case "mag":
				$naturemagic++;
        $naturemagic++;
				$naturemagic++;
        $know++;
        $know++;
        $ab = "N16";
				break;
			case "lekar":
				$naturemagic++;
				$naturemagic++;
        $know++;
        $know++;
        $know++;
        $ab = "N17";
				break;
			case "barbarian":
				$power++;
				$power++;
				$power++;
				$protect++;
        $charism++;
        $ab = "N4";
				break;
			case "wizard":
				$naturemagic++;
				$naturemagic++;
				$naturemagic++;
				$naturemagic++;
        $know++;
        $ab = "N18";
				break;
			}

			//Заполняем информацию о постройках в замке
			mysql_query("insert into abilities values ('$login', '$power', '$protect', '$magicpower', '$know', '$charism', '$dexterity', '$intel', '$naturemagic', '$combatmagic', '$mindmagic');");

			//Информация о доп. возможностях
			mysql_query("insert into newchar values ('$login', '$ab', '0', '0', '0', '0', '0', '0', '0', '0', '0', '0', '0', '0', '0', '0', '0');");

      //Инвентарь
			mysql_query("insert into inventory values ('$login', '$ab', '0', '0', '0', '0', '0', '0', '0', '0', '0', '0', '0', '0', '0', '0', '0');");

			//Размещаем замок
			randomplace($login);

			//Создаём файлы...
			$file = fopen("data/logs/".$login.".log", "w");
			fclose($file);
			mkdir("data/mail/".$login, 0700);
			$file = fopen("data/trade/".$login, "w");
			fclose($file);

			//Выводим информацию для пользователя и ссылку на страницу авторизации
			echo ("<br>");
			echo ("<script language=JavaScript>");
			echo ("function rt()");
			echo ("{");
		    echo("window.location.href('game.php?action=1');");		
			echo ("}");
			echo ("</script>");
			echo ("<body background='images\back.jpe'>");
			echo ("<h2><font color=yellow>Поздравляем!</font></h2>");
			echo ("<font color=green>Вы успешно зарегистрированы в системе. Нажмите кнопку 'Вход' для перехода на страницу авторизации</font><br><br>");
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
			echo ("<tr><td align=center><font color=green>Защита от магии</font></td><td align=center><font color=green>$dexterity</font></td></tr>");
			echo ("<tr><td align=center><font color=green>Знания</font></td><td align=center><font color=green>$know</font></td></tr>");
			echo ("<tr><td align=center><font color=green>Выносливость</font></td><td align=center><font color=green>$charism</font></td></tr>");
			echo ("<tr><td align=center><font color=green>Колдовская сила</font></td><td align=center><font color=green>$naturemagic</font></td></tr>");
		
      //Количество зарегистрировавшихся +1
      $adm = getadmin();
      $btls = getfrom('admin', $adm, 'settings', 'f2');
      $btls++;
      setto('admin', $adm, 'settings', 'f2', $btls);
      
			//Завершение работы скрипта
			?>
			</table>
			<center>
			Войдя в игру, посетите раздел "Извещения". Туда будет послан краткий обзор игры
			</center>

			<br>
			<form action="javascript:rt();">
			<center><input type='submit' name='finish' value='  Вход  ' style='background:#000099; font-WEIGHT: BOLD; COLOR: white; cursor: hand; list-style: lower-alpha; filter: Alpha(Opacity=20, FinishOpacity=100, Style=3)'></center>
			</form>
			<?

			//Посылаем обзор
			sms($login, "Команда разработчиков Native Land", "Добро пожаловать в игру Native Land (Родная земля). В этом Вам будет предоставлена краткая справка по информационным ресурсам игры. Если Вы являетесь клиентом оптоволоконной сети matrix в Санкт-Петербурге, то зайдя в локальный канал #nl в mIRC Вы можете задать свои вопросы более опытным игрокам, поделиться своими впечатлениями и знаниями. Остальные игроки могут это сделать с помощью чата, вызвать который можно в меню слева. Также можно договориться о заключении союза или просто заняться торговлей. Круглосуточно Вам доступен и форум игры, зайти в который можно тоже из меню слева. Сама игра находится по адресу: <a href='http://nld.spb.ru'>http://nld.spb.ru</a> или <a href='http://nld.spb.ru'>http://nativeland.spb.ru</a> Для того, чтобы быстро научиться играть в Native Land мы советуем Вам ознакомиться с документацией по игре. Вызвать её можно в меню слева или по адресу: <a href='http://nld.spb.ru/help.php'>http://nld.spb.ru/help.php</a> Контактный e-mail администратора: admin@nld.spb.ru IRC: Trimax; Local ICQ: 1358; Желаем Вам приятно провести время в захватывающем, фэнтезийном мире Native Land. Группа разработчиков: Trimax и Teider.");
			?>
			<script>
				window.open("http://nld.spb.ru/forums/");
			</script>
			<?
		}
	}
?>


