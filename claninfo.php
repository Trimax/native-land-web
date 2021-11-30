<body background="images/back.jpe">
<link rel='stylesheet' type='text/css' href='style.css'/>
<title>Информация о клане</title>

<script>
function wnd(name)
{
  wd = screen.width - 10;
  hg = screen.height - 120;
	window.open(name,11,"toolbar=yes, location=yes, menubar=yes,scrollbars=yes, resizable=yes, width=" + wd + ", height=" + hg + ", left=0, top=0");
}
</script>

<?
include "functions.php";
ban();

//Добавляем в клан себя
function addusertoclan($login, $clan)
{
	mysql_query ("insert into inclan values ('$login', '$clan', '0' , '0');");
}

//Уходим из клана
function kickuserfromclan($login)
{
	mysql_query("delete from inclan where login = '".$login."';");
}

//Обычный поиск пользователя
function userinclan($username, $table)
{
//link();
$usr = mysql_query("select * from ".$table.";");
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

//Безопастность
$lg = trim($HTTP_COOKIE_VARS["nativeland"]);
if ($lg != $login) 
  exit();

FromBattle($lg);

//Какой клан?
if (!empty($admin))
{

	//Обрабатываем запросы
	if ($login != $admin)
	{
		//Может мы хотим вступить?
		if ($enter == 1)
		{
			//Проверяем, а не в клане ли мы уже? А может мы админ другого клана?
			if ((userinclan($login, 'inclan') == 0)&&(userinclan($login, 'clans') == 0))
			{
        $send = "<center>Заявка на вступление в клан. <form action='comehere.php' method=post><input type='hidden' name='member' value='".$login."'><input type='hidden' name='admin' value='".$admin."'><br><input type='submit' value='Принять' style='background:#000099; font-WEIGHT: BOLD; COLOR: white; cursor: hand; list-style: lower-alpha; filter: Alpha(Opacity=20, FinishOpacity=100, Style=3)'></form><br><a href=javascript:hinfo('".$login."');>Информация о персонаже</a><br>В случае отказа, напишите игроку об этом</center>";
        sms($admin, $login, $send);
				messagebox("Ваша заявка отправлена в администрацию клана. Она будет рассмотрена и Вы получите результат через извещения. Ожидайте.", "bank.php?login=".$login);
			}
		}

		//Может мы хотим выйти?
		if ($out == 1)
		{
			//Проверяем, а не в клане ли мы уже? А может мы админ другого клана?
			if ((userinclan($login, 'inclan') == 1)&&(userinclan($login, 'clans') == 0))
			{
				kickuserfromclan($login);
				messagebox("Вы успешно вышли из клана ".$rw[0], "bank.php?login=".$login);
				exit();
			}
		}
	}

	//Создаём таблицу
	echo("<center><h1>Информация о клане</h1><a href='bank.php?login=".$login."'>Назад в филиал кланов</a><br><table border=1  cellspacing=0 cellpading=0 width=90%>");
	if ($login == $admin)
	{
		echo("<tr><td align=center width=30%>Параметр</td><td align=center>Значение</td></tr>");
	} else
	{
		echo("<tr><td align=center width=30%>Параметр</td><td align=center>Значение</td></tr>");
	}

	//Получаем данные о клане
	$ath = mysql_query("select * from clans;");
	$count = 0;
	if ($ath)
	{
		//Ищем всех
		while ($rw = mysql_fetch_row($ath))
		{
			//Наш клан? Тогда выводим информацию!
			if ($rw[1] == $admin)
			{
				if ($login != $admin)
				{
					//Выводим таблицу
					echo("<tr><td align=center>Название клана</td><td align=center>".$rw[0]."</td></tr>");
					echo("<tr><td align=center>Описание клана</td><td align=center>".$rw[2]."</td></tr>");
					echo("<tr><td align=center>Администратор</td><td align=center>".$rw[1]."</td></tr>");
					echo("<tr><td align=center>Налог</td><td align=center>".$rw[6]."</td></tr>");
					echo("<tr><td align=center>Счёт клана</td><td align=center>".$rw[7]."</td></tr>");
					echo("<tr><td align=center>Особенности клана</td><td align=center>".$rw[8].$rw[9].$rw[10]."</td></tr>");
					echo("<tr><td align=center>Ссылка в форуме</td><td align=center><a href=javascript:wnd('".$rw[3]."');>Нажмите</a></td></tr>");
					echo("<tr><td align=center>Логотип</td><td align=center><img src='".$rw[4]."' width=32 height=32></td></tr>");
					echo("<tr><td align=center>Герб</td><td align=center><img src='".$rw[5]."' width=320 height=240></td></tr><tr><td align=center colspan=2>");
          if (userinclan($login, 'inclan') == 0)
          {
            echo("<form action='claninfo.php' method=post><input type='hidden' name='enter' value=1><input type='hidden' name='login' value='".$login."'><input type='hidden' name='admin' value='".$admin."'><input type='hidden' name='inclan' value='".$rw[0]."'><input type='submit' value='Вступить в клан' style='background:#000099; font-WEIGHT: BOLD; COLOR: white; cursor: hand; list-style: lower-alpha; filter: Alpha(Opacity=20, FinishOpacity=100, Style=3)'></form>");
          }
          if (userinclan($login, 'inclan') == 1)
					{
						echo("<form action='claninfo.php' method=post><input type='hidden' name='out' value=1><input type='hidden' name='login' value='".$login."'><input type='hidden' name='admin' value='".$admin."'><input type='submit' value='Выйти из клана' style='background:#000099; font-WEIGHT: BOLD; COLOR: white; cursor: hand; list-style: lower-alpha; filter: Alpha(Opacity=20, FinishOpacity=100, Style=3)'></form>");
					}
					echo("</td></tr>");
				} else //Меню администратора
				{
					//Теперь обрабатываем команды:
					//1) Сохранить изменения о клане
					if ($save == 1)
					{
						change($admin, 'clans', 'name', $name);
						$rw[0] = $name;
						change($admin, 'clans', 'link', $link);
						$rw[3] = $link;
						change($admin, 'clans', 'description', $desc);
						$rw[2] = $desc;
						change($admin, 'clans', 'logo', $logo);
						$rw[4] = $logo;
						change($admin, 'clans', 'gerb', $gerb);
						$rw[5] = $gerb;
					}

					//Выводим таблицу
					echo("<form action='claninfo.php' method=post><input type='hidden' name='save' value=1><input type='hidden' name='login' value='".$login."'><input type='hidden' name='admin' value='".$admin."'>");
					echo("<tr><td align=center>Название клана</td><td align=center><input type='text' style='background:#000099; font-WEIGHT: BOLD; COLOR: white; cursor: hand; list-style: lower-alpha; filter: Alpha(Opacity=20, FinishOpacity=100, Style=3)' name='name' value='".$rw[0]."'></td></tr>");
					echo("<tr><td align=center>Описание клана</td><td align=center><textarea name='desc' cols=45 rows=6 style='background:#000099; font-WEIGHT: BOLD; COLOR: white; cursor: hand; list-style: lower-alpha; filter: Alpha(Opacity=20, FinishOpacity=100, Style=3)'>".$rw[2]."</textarea></td></tr>");
					echo("<tr><td align=center>Администратор</td><td align=center>".$rw[1]."</td></tr>");
					echo("<tr><td align=center>Налог</td><td align=center><input type='text' name='nalog' value='".$rw[6]."' style='background:#000099; font-WEIGHT: BOLD; COLOR: white; cursor: hand; list-style: lower-alpha; filter: Alpha(Opacity=20, FinishOpacity=100, Style=3)'></td></tr>");
					echo("<tr><td align=center>Счёт клана</td><td align=center>".$rw[7]."</td></tr>");					
					echo("<tr><td align=center>Особенности клана</td><td align=center>".$rw[8].$rw[9].$rw[10]."</td></tr>");
					echo("<tr><td align=center>Ссылка в форуме</td><td align=center><input type='text' name='link' value='".$rw[3]."' style='background:#000099; font-WEIGHT: BOLD; COLOR: white; cursor: hand; list-style: lower-alpha; filter: Alpha(Opacity=20, FinishOpacity=100, Style=3)'></td></tr>");
					echo("<tr><td align=center>Логотип</td><td align=center><input type='text' name='logo' value='".$rw[4]."' style='background:#000099; font-WEIGHT: BOLD; COLOR: white; cursor: hand; list-style: lower-alpha; filter: Alpha(Opacity=20, FinishOpacity=100, Style=3)'></td></tr>");
					echo("<tr><td align=center>Герб</td><td align=center><input type='text' name='gerb' value='".$rw[5]."' style='background:#000099; font-WEIGHT: BOLD; COLOR: white; cursor: hand; list-style: lower-alpha; filter: Alpha(Opacity=20, FinishOpacity=100, Style=3)'></td></tr>");
					echo("<tr><td align=center colspan=2><input type='submit' value='Сохранить изменения' style='background:#000099; font-WEIGHT: BOLD; COLOR: white; cursor: hand; list-style: lower-alpha; filter: Alpha(Opacity=20, FinishOpacity=100, Style=3)'></td></tr>");
					echo("</form>");
				}//$login==$admin
			}//$admin
		}//$rw
	} //$ath

	//Завершаем
	echo("</table></center>");
} //$admin (empty)

?>