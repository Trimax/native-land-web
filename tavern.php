<body background="images/back.jpe">
<link rel='stylesheet' type='text/css' href='style.css'/>
<title>Таверна</title>

<?

  Error_Reporting(E_ALL & ~E_NOTICE);

  //Перемещение
	function moveto($page)
	{
	   echo ("<script>window.location.href('".$page."');</script>");
	}

	//Безопастность
	$lg = trim($HTTP_COOKIE_VARS["nativeland"]);

	//Не заходим, если чужой храм
	if ($lg != $login) 
	{
		exit();
	}

	//Может удаление?
	if ($kick == 1)
	{
		//А доступ есть?
		if (($login == 'Admin')||($login == 'PANTERKA')) 
		{
			//Стираем запись
			$name = "data/news/rec.".$num;
			unlink ($name);
			moveto('tavern.php?login='.$login);
		}
	}

	//Может добавление?
	if ($add == 1)
	{
		//А доступ есть?
		if (($login == 'Admin')||($login == 'PANTERKA')) 
		{
			//Создаём запись
			$file = fopen ("data/news/rec.".time(), "w");
			fputs ($file, $msg."\n");
			fclose ($file);
			moveto('tavern.php?login='.$login);
		}
	}

	//Выводим тело
	echo ("<center><a href=city.php?login=".$login.">Выйти из таверны</a><br><br><table border=1 width=90% CELLSPACING=0 CELLPADDING=0>");
	echo ("<tr><td align=center colspan=2><b><font size=4>Последние игровые новости</font></b></td></tr>");

	//Получаем список всех сообщений	
	$dir_rec= dir("data/news");
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
	for ($i = 0; $i < $count; $i++)
	{
		$entry = $names[$i];
		if (($login != 'Admin')&&($login != 'PANTERKA')) 
		{
			echo("<tr><td align=center colspan=2 width=80%>");
		}
		else
		{
			echo("<tr><td align=center>");
		}
		
		//Читаем файл до конца
		$data = fopen("data/news/rec.".$entry, "r");
		while (!feof($data))
		{
		   echo (fgets($data, 255)."<br>");
		}

	if (($login == 'Admin')||($login == 'PANTERKA')) 
	{
		echo("</td><td align=center>");
		echo("<form action='tavern.php' method=post><input type='hidden' name='num' value=$entry><input type='hidden' name='kick' value=1><input type='hidden' name='login' value='$login'><br><input type='submit' value='Стереть' style='background:#000099; font-WEIGHT: BOLD; COLOR: white; cursor: hand; list-style: lower-alpha; filter: Alpha(Opacity=20, FinishOpacity=100, Style=3)'></form>");
	}
	//Конец текущей ячейки
	echo("</td></tr>");
	fclose ($data);
	}

	//Возможность добавления новостей
	if (($login == 'Admin')||($login == 'PANTERKA')) 
	{
		echo("<tr><td align=center colspan=2><b>Добавить новость</b><form action='tavern.php' method=post><input type='hidden' name='num' value=$entry><input type='hidden' name='add' value=1><input type='hidden' name='login' value='$login'><textarea name='msg' cols=70 rows=15 maxlength=255 style='background:#000099; font-WEIGHT: BOLD; COLOR: white; cursor: hand; list-style: lower-alpha; filter: Alpha(Opacity=20, FinishOpacity=100, Style=3)'></textarea><br><br><input type='submit' value='Добавить новость' style='background:#000099; font-WEIGHT: BOLD; COLOR: white; cursor: hand; list-style: lower-alpha; filter: Alpha(Opacity=20, FinishOpacity=100, Style=3)'></form></td></tr>");
	}

	//Конец таблицы
	echo ("</table></center>");
?>