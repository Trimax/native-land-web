<body background="images/back.jpe">
<link rel='stylesheet' type='text/css' href='style.css'/>
<title>Игровые новости</title>

<?

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

	//Выводим тело
	echo ("<center><table border=1 width=95% CELLSPACING=0 CELLPADDING=0>");
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

	//Конец текущей ячейки
	echo("</td></tr>");
	fclose ($data);
	}

	//Конец таблицы
	echo ("</table></center>");
?>