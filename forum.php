<body background="images/back.jpe">
<link rel='stylesheet' type='text/css' href='style.css'/>
<title>Форум</title>

<?
include "connect.php";

//Получаем данные из cookies
$lg = trim($HTTP_COOKIE_VARS["nativeland"]);
$pw = trim($HTTP_COOKIE_VARS["password"]);

//Единственная функция, возвращающая информацию о количестве новых сообщений в форуме
function messages($category, $forum)
{
	//0) Флаг для результата (0 - нет;  < 0 - все прочитаны; > 0 есть новые)
	$flag = 0;
	$one = 0; //Хотябы одно новое
	$count = 0; //Количество новых

	//1) Граница (за последние 2 часа)
	$lasttime = time() - 2*3600;

	//2) Получаем список всех тем из текущего форума и из текущей категории
	$ath = mysql_query("select * from forum_subjects;");
	if ($ath)
	{
		//Для каждой темы
		while ($rw = mysql_fetch_row($ath))
		{
			//А это сообщение откуда надо?
			if (($rw[3] == $category)&&($rw[2] == $forum))
			{
				//Получаем папку сообщения
				$path = "forum/".getfrom('category', $category, 'forum_categories', 'folder')."/".getfrom('forum', $forum, 'forum_forums', 'folder')."/".$rw[1];

				//Получаем список всех ответов на сообщение, считаем их количество и времена последнего захода
				$dir_rec= dir($path);
				while ($entry = $dir_rec->read())
				{
				   if (substr($entry,0,3)=="rec")
				  {
					   //Получаем время последнего сообщения
					   $names[$i]=trim(substr($entry,4));
					   (int)$msgtime = $names[$i];
		
						//Если оно больше, чем два последние часа, то ставим флаг что есть новые
						if ($msgtime > $lasttime)
						{
							$one = 1;
						}
					   $count++;
			      } //rec
				} //While $entry
				$dir_rec->close();
			}
		} //While $rw
	}
	
	//Возвращаем количество
	$flag = $count;

	//Если новых нет, то умножаем флаг на -1
	if ($one == 0)
	{
		$flag = $flag * (-1);
	}

	//Возвращаем результат
	return $flag;
}

//Послать сообщение
if ($message == 1)
{
	?>
		<script>
			window.open('sendmail.php', null,'toolbar=no, location=no, menubar=no,scrollbars=no,width=656,height=480');
		</script>
	<?
}

//Создание новой категории (только администратор)
if (($newpart == 1)&&(isadmin($lg) == 1))
{
	echo("<form action='forum.php' method=post>");
	echo("<center><table border=1 width=80% cellpadding=0 cellspacing=0>");	
	echo("<tr><td colspan=2 align=center>Создание новой категории</td></tr>");
	echo("<tr><td width=40%>Название категории</td><td><input type='text' name='cname' style='background:#000099; font-WEIGHT: BOLD; COLOR: white; cursor: hand; list-style: lower-alpha; filter: Alpha(Opacity=20, FinishOpacity=100, Style=3)'></td></tr>");
	echo("<tr><td width=40%>Папка категории</td><td><input type='text' name='fname' style='background:#000099; font-WEIGHT: BOLD; COLOR: white; cursor: hand; list-style: lower-alpha; filter: Alpha(Opacity=20, FinishOpacity=100, Style=3)'></td></tr>");
	echo("<tr><td width=40%>Модератор категории</td><td>");
	sortgenerate('moder', 'users', 0);
	echo("</td></tr>");
	echo("<tr><td colspan=2 align=center><input type='submit' value='Создать категорию' style='background:#000099; font-WEIGHT: BOLD; COLOR: white; cursor: hand; list-style: lower-alpha; filter: Alpha(Opacity=20, FinishOpacity=100, Style=3)'></td></tr>");
	echo("</table></center>");
	echo("<input type='hidden' name='newpart_step2' value=1>");
	echo("</form>");
	exit();
}

//Подтверждение создания
if (($newpart_step2 == 1)&&(isadmin($lg) == 1))
{
	//Добавляем категорию в БД
	mysql_query("insert into forum_categories values('".$cname."', '".$fname."', '".$moder."');");

	//Создаём каталог категории
	mkdir("forum/".$fname,0700);
	moveto('forum.php');
}

//Создание нового раздела
if (($newvol == 1)&&(($lg == $moder)||(isadmin($lg) == 1)))
{
	echo("<form action='forum.php' method=post>");
	echo("<center><table border=1 width=80% cellpadding=0 cellspacing=0>");	
	echo("<tr><td colspan=2 align=center>Создание нового раздела</td></tr>");
	echo("<tr><td width=40%>Название раздела</td><td><input type='text' name='cname' style='background:#000099; font-WEIGHT: BOLD; COLOR: white; cursor: hand; list-style: lower-alpha; filter: Alpha(Opacity=20, FinishOpacity=100, Style=3)'></td></tr>");
	echo("<tr><td width=40%>Папка раздела</td><td><input type='text' name='fname' style='background:#000099; font-WEIGHT: BOLD; COLOR: white; cursor: hand; list-style: lower-alpha; filter: Alpha(Opacity=20, FinishOpacity=100, Style=3)'></td></tr>");
	echo("<tr><td colspan=2 align=center><input type='submit' value='Создать категорию' style='background:#000099; font-WEIGHT: BOLD; COLOR: white; cursor: hand; list-style: lower-alpha; filter: Alpha(Opacity=20, FinishOpacity=100, Style=3)'></td></tr>");
	echo("</table></center>");
	//Передаём информацию о следующем шаге
	echo("<input type='hidden' name='newvol_step2' value=1>");
	//Указываем модератора категории
	echo("<input type='hidden' name='moder' value='".$moder."'>");
	//Передаём название категории
	echo("<input type='hidden' name='directory' value='".$volinpart."'>");
	echo("</form>");
	exit();
}

//Обработка событий, вызванных из меню сверху
if (($newvol_step2 == 1)&&(isadmin($lg) == 1))
{
	//Добавляем категорию в БД
	mysql_query("insert into forum_forums values('".$cname."', '".$fname."', '".$directory."');");

	//Создаём каталог категории
	mkdir("forum/".$directory."/".$fname,0700);
	moveto('forum.php');
}

//Подтверждение создания новой темы
if (($newsubject == 1)&&(finduser($lg, $pw) == 1)&&(!empty($cname)))
{
	//Добавляем категорию в БД
	mysql_query("insert into forum_subjects values('".$cname."', '".$cname."', '".$forum."', '".$category."', '0', '".$lg."', '1');");

	//Создаём каталог темы
	$path = "forum/".getfrom('category', $category, 'forum_categories', 'folder')."/".getfrom('forum', $forum, 'forum_forums', 'folder')."/".$cname;
	mkdir($path,0700);
	moveto('forum.php?category='.$category."&forum=".$forum."&subject=".$cname);
}

//Закрытие темы модератором
if (($closesubject == 1)&&(($lg == getfrom('categories', $category, 'forum_categories', 'moderator'))||(isadmin($lg) == 1)))
{
	setto('subject', $subject, 'forum_subjects', 'closed', '1');
	moveto("forum.php?category=".$category."&forum=".$forum);
}

//Открытие темы модератором
if (($opensubject == 1)&&(($lg == getfrom('categories', $category, 'forum_categories', 'moderator'))||(isadmin($lg) == 1)))
{
	setto('subject', $subject, 'forum_subjects', 'closed', '0');
	moveto("forum.php?category=".$category."&forum=".$forum);
}

//Удаление темы модератором
if (($erasesubject == 1)&&(($lg == getfrom('categories', $category, 'forum_categories', 'moderator'))||(isadmin($lg) == 1)))
{
	$path = "forum/".getfrom('category', $category, 'forum_categories', 'folder')."/".getfrom('forum', $forum, 'forum_forums', 'folder')."/".$subject;

	//Получаем список всех ответов на сообщения и удаляем все
	$dir_rec= dir($path);
	$i = 0;
	while ($entry = $dir_rec->read())
	{
	   if (substr($entry,0,3)=="rec")
	  {
	      $names[$i]=trim(substr($entry,4));
		  $name = $path."/rec.".$names[$i];
		  unlink ($name);
	      $i++;
      }
	}
	$dir_rec->close();
	$count = $i;
	
	//Удаляем саму директорию
	rmdir($path);

	//Убираем данные из БД
	delfrom('subject', $subject, 'forum_subjects');

	//Возвращаемся в форум
	moveto("forum.php?category=".$category."&forum=".$forum);
}

//Посылаем ответ на тему
if ($postreply == 1)
{
	//А не закрыта ли тема?
	if ((getfrom('subject', $subject, 'forum_subjects', 'closed') == 0)&&(getfrom('subject', $subject, 'forum_subjects', 'category') == $category)&&(getfrom('subject', $subject, 'forum_subjects', 'forum') == $forum))
	{
		//Объявляем функцию вставки смайла
		?>
		<script language=JavaScript>
		function DoSmile(Code) 
		{
			document.forms[0].message.value = document.forms[0].message.value + Code;
			document.forms[0].message.focus();
			return;
		}
		</script>
		<?

		//Создаём новый пост
		echo("<form name=data action='forum.php' method=post>");
		echo("<center><table border=1 width=80% cellpadding=0 cellspacing=0>");	
		echo("<tr><td align=center>Ответ на сообщение ".$subject."</td></tr>");
		echo("<tr><td align=center>Ваше сообщение:<br><textarea name='message' cols=70 rows=15 style='background:#000099; font-WEIGHT: BOLD; COLOR: white; cursor: hand; list-style: lower-alpha; filter: Alpha(Opacity=20, FinishOpacity=100, Style=3)'></textarea></td></td></tr>");
		echo("<tr><td align=center>");
		?>

		<!--  Добавляем смайлы -->
		<a href="javascript:DoSmile(':)');"><img src=images/smiles/icon_biggrin.gif border=0></a>
		<a href="javascript:DoSmile(':(');"><img src=images/smiles/icon_cry.gif border=0></a>
		<a href="javascript:DoSmile(':)');"><img src=images/smiles/icon_eek.gif border=0></a>
		<a href="javascript:DoSmile('8)');"><img src=images/smiles/icon_wink.gif border=0></a>
		<a href="javascript:DoSmile(';0');"><img src=images/smiles/icon_surprised.gif border=0></a>
		<a href="javascript:DoSmile(':/');"><img src=images/smiles/icon_confused.gif border=0></a>
		<a href="javascript:DoSmile(':0');"><img src=images\smiles\icon_cool.gif border=0>
		<a href="javascript:DoSmile('>:');"><img src=images\smiles\icon_evil.gif border=0>
		<a href="javascript:DoSmile('8^');"><img src=images\smiles\icon_lol.gif border=0>
		<a href="javascript:DoSmile('/:');"><img src=images\smiles\icon_mad.gif border=0>
		<a href="javascript:DoSmile(':]');"><img src=images\smiles\icon_mrgreen.gif border=0>
		<a href="javascript:DoSmile(':|');"><img src=images\smiles\icon_neutral.gif border=0>
		<a href="javascript:DoSmile(':>');"><img src=images\smiles\icon_razz.gif border=0>
		<a href="javascript:DoSmile('<:');"><img src=images\smiles\icon_redface.gif border=0>
		<a href="javascript:DoSmile(':?');"><img src=images\smiles\icon_rolleyes.gif border=0>
		<a href="javascript:DoSmile(':!');"><img src=images\smiles\icon_twisted.gif border=0>
		<?

		//Конец строки и кнопка "Послать"
		echo("</td></tr>");
		echo("<tr><td colspan=2 align=center><input type='submit' value='Послать ответ' style='background:#000099; font-WEIGHT: BOLD; COLOR: white; cursor: hand; list-style: lower-alpha; filter: Alpha(Opacity=20, FinishOpacity=100, Style=3)'></td></tr>");
		echo("</table></center>");

		//Передаём информацию о следующем шаге
		echo("<input type='hidden' name='postreply_step2' value=1>");

		//Указываем название категории
		echo("<input type='hidden' name='category' value='".$category."'>");

		//Передаём название раздела
		echo("<input type='hidden' name='forum' value='".$forum."'>");

		//Передаем название темы
		echo("<input type='hidden' name='subject' value='".$subject."'>");
		echo("</form>");
		exit();
	}
}

//Непосредственное добавление поста
if ($postreply_step2 == 1)
{
	//Определяем путь к теме
	$path = "forum/".getfrom('category', $category, 'forum_categories', 'folder')."/".getfrom('forum', $forum, 'forum_forums', 'folder')."/".$subject;

	//Переделываем сообщение в смайлы
	$msg[0] = $message;
	for ($i = 0; $i < strlen($msg[0]); $i++)
	{

		//Смайлик?
		$yes = 0;

		//Улыбка
		if (substr($msg[0], $i, 2) == ":)")
		{
			$txt = $txt."<img src=images/smiles/icon_biggrin.gif>";
			$yes = 1;
			$i++;
		}

		//Досада
		if (substr($msg[0], $i, 2) == ":(")
		{
			$txt = $txt."<img src=images/smiles/icon_cry.gif>";
			$yes = 1;
			$i++;
		}

		//Очки
		if (substr($msg[0], $i, 2) == "8)")
		{
			$txt = $txt."<img src=images/smiles/icon_eek.gif>";
			$yes = 1;
			$i++;
		}

		//Подмигивает
		if (substr($msg[0], $i, 2) == ";)")
		{
			$txt = $txt."<img src=images/smiles/icon_wink.gif>";
			$yes = 1;
			$i++;
		}

		//Рот открыт + мигает
		if (substr($msg[0], $i, 2) == ";0")
		{
			$txt = $txt."<img src=images/smiles/icon_surprised.gif>";
			$yes = 1;
			$i++;
		}

		//Смущённый
		if (substr($msg[0], $i, 2) == ":/")
		{
			$txt = $txt."<img src=images/smiles/icon_confused.gif>";
			$yes = 1;
			$i++;
		}

		//Круто
		if (substr($msg[0], $i, 2) == ":0")
		{
			$txt = $txt."<img src=images/smiles/icon_cool.gif>";
			$yes = 1;
			$i++;
		}

		//Озадачен
		if (substr($msg[0], $i, 2) == ">:")
		{
			$txt = $txt."<img src=images/smiles/icon_evil.gif>";
			$yes = 1;
			$i++;
		}

		//ЛОЛ
		if (substr($msg[0], $i, 2) == "8^")
		{
			$txt = $txt."<img src=images/smiles/icon_lol.gif>";
			$yes = 1;
			$i++;
		}

		//Сумасшедший
		if (substr($msg[0], $i, 2) == "/:")
		{
			$txt = $txt."<img src=images/smiles/icon_mad.gif>";
			$yes = 1;
			$i++;
		}

		//Зелёная рожица
		if (substr($msg[0], $i, 2) == ":]")
		{
			$txt = $txt."<img src=images/smiles/icon_mrgreen.gif>";
			$yes = 1;
			$i++;
		}

		//Безразличие
		if (substr($msg[0], $i, 2) == ":|")
		{
			$txt = $txt."<img src=images/smiles/icon_neutral.gif>";
			$yes = 1;
			$i++;
		}

		//Не помню
		if (substr($msg[0], $i, 2) == ":>")
		{
			$txt = $txt."<img src=images/smiles/icon_razz.gif>";
			$yes = 1;
			$i++;
		}

		//Красное лицо (смущение, недоумение)
		if (substr($msg[0], $i, 2) == "<:")
		{
			$txt = $txt."<img src=images/smiles/icon_redface.gif>";
			$yes = 1;
			$i++;
		}

		//Удивление
		if (substr($msg[0], $i, 2) == ":?")
		{
			$txt = $txt."<img src=images/smiles/icon_rolleyes.gif>";
			$yes = 1;
			$i++;
		}

		//Сладкий
		if (substr($msg[0], $i, 2) == ":!")
		{
			$txt = $txt."<img src=images/smiles/icon_twisted.gif>";
			$yes = 1;
			$i++;
		}

		//Ничего, просто добавляем символ
		if ($yes == 0)
		{
			$txt = $txt.$msg[0][$i];
		}
	}

	//Запись сообщения непосредственно в файл и непременный возврат в тему
	$file = fopen($path."/rec.".time(), "w+");
	fputs($file, $lg."\n");
	fputs($file, $txt);
	fclose ($file);

	//Возвращаемся в форум
	moveto("forum.php?category=".$category."&forum=".$forum."&subject=".$subject);
}

//Удаляем пост
if (($erasepost == 1)&&(($lg == getfrom('categories', $category, 'forum_categories', 'moderator'))||(isadmin($lg) == 1)))
{
	$file = "forum/".getfrom('category', $category, 'forum_categories', 'folder')."/".getfrom('forum', $forum, 'forum_forums', 'folder')."/".$subject."/rec.".$num;

	//Удаляем 
	unlink($file);
	moveto("forum.php?category=".$category."&forum=".$forum."&subject=".$subject);
}

//===============
//Вывод тела форума
//===============

//Шапка таблицы
echo("<table border=1 width=100% cellpadding=0 cellspacing=0>");
echo("<tr><td colspan=5 align=center><b>Официальный форум игры Native Land</b></td></tr>");
echo("<tr><td align=center><br><form action='forum.php' method=post><input type='submit' value='Новая категория' style='background:#000099; font-WEIGHT: BOLD; COLOR: white; cursor: hand; list-style: lower-alpha; filter: Alpha(Opacity=20, FinishOpacity=100, Style=3)'><input type='hidden' name='newpart' value=1></form></td><td align=center><br><form action='forum.php' method=post><input type='submit' value='Послать персональное сообщение' style='background:#000099; font-WEIGHT: BOLD; COLOR: white; cursor: hand; list-style: lower-alpha; filter: Alpha(Opacity=20, FinishOpacity=100, Style=3)'><input type='hidden' name='message' value=1></form></td><td align=center><br><form action='forum.php' method=post><input type='submit' value='В начало' style='background:#000099; font-WEIGHT: BOLD; COLOR: white; cursor: hand; list-style: lower-alpha; filter: Alpha(Opacity=20, FinishOpacity=100, Style=3)'></form></td><td align=center><br><form action='game.php?action=1' method=post><input type='submit' value='В игру' style='background:#000099; font-WEIGHT: BOLD; COLOR: white; cursor: hand; list-style: lower-alpha; filter: Alpha(Opacity=20, FinishOpacity=100, Style=3)'></form></td></tr>");
echo("<tr><td colspan=5 align=center>");

//Если выбрана тема, категория и форум, отображаем их
if ((!empty($category))&&(!empty($forum)))
{
	//Если пользователь нажал кнопку зайти в тему, то открываем ему тему
	if (!empty($subject))
	{
		//Отображаем ветвь сообщений
		echo("<table border=1 width=100% cellpadding=0 cellspacing=0>");

		//Заголовок ветви
		echo("<tr>");
			//1) Если тема не закрыта, то отображаем кнопку "Послать ответ"
			if (getfrom('subject', $subject, 'forum_subjects', 'closed') == '0')
			{
				echo("<td align=center><br><form action='forum.php' method=post><input type='submit' value='Послать ответ' style='background:#000099; font-WEIGHT: BOLD; COLOR: white; cursor: hand; list-style: lower-alpha; filter: Alpha(Opacity=20, FinishOpacity=100, Style=3)'><input type='hidden' name='postreply' value=1><input type='hidden' name='category' value='".$category."'><input type='hidden' name='forum' value='".$forum."'><input type='hidden' name='subject' value='".$subject."'></form></td>");
			}

			//2) Кнопка вернуться назад в меню форума
			echo("<td align=center><br><form action='forum.php?category=".$category."&forum=".$forum."' method=post><input type='submit' value='Вернуться в раздел ".$forum."' style='background:#000099; font-WEIGHT: BOLD; COLOR: white; cursor: hand; list-style: lower-alpha; filter: Alpha(Opacity=20, FinishOpacity=100, Style=3)'></form></td>");

		//Конец заголовка ветви
		echo("</tr>");

		//Новая строка для всех ответов на сообщения
		echo("<tr><td align=center colspan=4>");

		//Получаем список всех сообщений
		$path = "forum/".getfrom('category', $category, 'forum_categories', 'folder')."/".getfrom('forum', $forum, 'forum_forums', 'folder')."/".$subject;
		$dir_rec= dir($path);
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

		//Выводим все сообщения. Каждое в своей таблице. Слева автор и аватар, справа сообщение
		for ($i = 0; $i < $count; $i++)
		{
			//Получаем имя файла
		   $entry = $names[$i];

		   //Создаём таблицу для поста
		   echo("<table border=1 width=100% cellpadding=0 cellspacing=0>");

		   //Создаём для модератора спецменю
		   if (($lg == getfrom('category', $category, 'forum_categories', 'moderator'))||(isadmin($lg) == 1))
			{
			   echo("<tr><td align=right colspan=2><form action='forum.php' method=post><input type='hidden' name='num' value=".$entry."><input type='hidden' name='category' value='$category'><input type='hidden' name='forum' value='$forum'><input type='hidden' name='subject' value='$subject'><input type='hidden' name='erasepost' value=1><br><input type='submit' value='Стереть' style='background:#000099; font-WEIGHT: BOLD; COLOR: white; cursor: hand; list-style: lower-alpha; filter: Alpha(Opacity=20, FinishOpacity=100, Style=3)'></form></td></tr>");
			}
		   
		   //Читаем файл с сообщением до конца
		   $data = fopen($path."/rec.".$entry, "r");
		   $who =trim(fgets($data, 255));
		   $avatar = 'images/photos/'.getdata($who, 'inf', 'fld1');
		   echo("<tr><td align=center width=35%>".$who."<br>(".getdata($who, 'hero', 'name').")<br><img src='".$avatar."' width=150 height=200></td><td align=center>");
		   while (!feof($data))
			{
			   echo (fgets($data, 255)."<br>");
			}
			fclose ($data);
			echo("</td></tr></table>");
		}

		//Конец таблицы поста
		echo("</td></tr>");
		echo("</table>");
		exit();
	}

	//Создаём шапку таблицы для тем
	echo("<table border=1 width=100% cellpadding=0 cellspacing=0>");
	echo("<tr><td align=center colspan=5><br><form action='forum.php' method=post><input type='text' name='cname' style='background:#000099; font-WEIGHT: BOLD; COLOR: white; cursor: hand; list-style: lower-alpha; filter: Alpha(Opacity=20, FinishOpacity=100, Style=3)'>&nbsp;<input type='submit' value='Новая тема' style='background:#000099; font-WEIGHT: BOLD; COLOR: white; cursor: hand; list-style: lower-alpha; filter: Alpha(Opacity=20, FinishOpacity=100, Style=3)'><input type='hidden' name='newsubject' value=1><input type='hidden' name='category' value='".$category."'><input type='hidden' name='forum' value='".$forum."'></form></td></tr>");
	echo("<tr><td align=center>Тема</td><td align=center>Автор</td><td align=center>Новое</td><td align=center>Статус</td></tr>");

	//Определяем модератора
	$moderator = getfrom('category', $category, 'forum_categories', 'moderator');

	//Определяем все темы
	$ath = mysql_query("select * from forum_subjects;");
	if ($ath)
	{
		//Для каждой темы
		while ($rw = mysql_fetch_row($ath))
		{
			//Если тема из текущего форума и категории
			if (($rw[2] == $forum)&&($rw[3] == $category))
			{
				//Строка текущей темы для модератора
				if (($lg == $moderator)||(isadmin($lg) == 1))
				{
					//Если тема открыта, то кнопка "Закрыть"
					if ($rw[4] == '0')
					{
						echo("<tr><td align=center><a href='forum.php?category=".$category."&forum=".$forum."&subject=".$rw[0]."'>".$rw[0]."</a></td><td align=center>".$rw[5]."</td><td align=center><br><form action='forum.php' method=post><input type='submit' value='Закрыть' style='background:#000099; font-WEIGHT: BOLD; COLOR: white; cursor: hand; list-style: lower-alpha; filter: Alpha(Opacity=20, FinishOpacity=100, Style=3)'><input type='hidden' name='closesubject' value=1><input type=hidden name='subject' value='".$rw[0]."'><input type=hidden name='forum' value='".$forum."'><input type=hidden name='category' value='".$category."'></form></td><td align=center><br><form action='forum.php' method=post><input type='submit' value='Удалить' style='background:#000099; font-WEIGHT: BOLD; COLOR: white; cursor: hand; list-style: lower-alpha; filter: Alpha(Opacity=20, FinishOpacity=100, Style=3)'><input type='hidden' name='erasesubject' value=1><input type=hidden name='subject' value='".$rw[0]."'><input type=hidden name='forum' value='".$forum."'><input type=hidden name='category' value='".$category."'></form></td></tr>");
					}
					else //Открытие темы
					{
						echo("<tr><td align=center><a href='forum.php?category=".$category."&forum=".$forum."&subject=".$rw[0]."'>".$rw[0]."</a></td><td align=center>".$rw[5]."</td><td align=center><br><form action='forum.php' method=post><input type='submit' value='Открыть' style='background:#000099; font-WEIGHT: BOLD; COLOR: white; cursor: hand; list-style: lower-alpha; filter: Alpha(Opacity=20, FinishOpacity=100, Style=3)'><input type='hidden' name='opensubject' value=1><input type=hidden name='subject' value='".$rw[0]."'><input type=hidden name='forum' value='".$forum."'><input type=hidden name='category' value='".$category."'></form></td><td align=center><br><form action='forum.php' method=post><input type='submit' value='Удалить' style='background:#000099; font-WEIGHT: BOLD; COLOR: white; cursor: hand; list-style: lower-alpha; filter: Alpha(Opacity=20, FinishOpacity=100, Style=3)'><input type='hidden' name='erasesubject' value=1><input type=hidden name='subject' value='".$rw[0]."'><input type=hidden name='forum' value='".$forum."'><input type=hidden name='category' value='".$category."'></form></td></tr>");
					}
				}
				else //Для всех остальных
				{
					//Тип сообщения (прочитанное или нет или вообще нет ни одного)
					$count = messages($category, $forum);

					//Нет сообщений
					if ($count == 0)
					{
						$type = "empty";
					}

					//Новые есть
					if ($count > 0)
					{
						$type = "newmessage";
					}

					//Новых нет
					if ($count < 0)
					{
						$type = "oldmessage";
						$count = $count * (-1);
					}

					//Определяем статус
					if ($rw[6] == 1)
					{
						$status = "Открыта";
					}
					else
					{
						$status = "Закрыта";
					}

					//Выводим саму тему
					echo("<tr><td align=center><a href='forum.php?category=".$category."&forum=".$forum."&subject=".$rw[0]."'>".$rw[0]."</a></td><td align=center>".$rw[5]."</td><td align=center>".$status."</td><td align=center><img src='images/icons/".$type.".ico'></td></tr>");
				} //Для всех остальных
			} //
		}
	}

	//Конец таблицы тем
	echo("</table>");
	exit();
}

//Отображаем сам форум, если не выбрана категория и форум
	//Получаем список категорий из БД
	$ath = mysql_query("select * from forum_categories;");
	if ($ath)
	{
		//Для каждой категории
		while ($rw = mysql_fetch_row($ath))
		{
			//Начало категории
			echo("<br><table border=1 width=98% cellpadding=0 cellspacing=0>");
			echo("<tr><td width=60% align=center><h2>".$rw[0]."</h2></td><td align=center><h3>Модерируется: ".$rw[2]."</h3></td>");

			//Создаём меню категории (только для модератора)
			if (($lg == $rw[2])||(isadmin($lg) == 1))
			{
				echo("<td align=center><br><form action='forum.php' method=post><input type='submit' value='Новый раздел' style='background:#000099; font-WEIGHT: BOLD; COLOR: white; cursor: hand; list-style: lower-alpha; filter: Alpha(Opacity=20, FinishOpacity=100, Style=3)'><input type='hidden' name='newvol' value=1><input type='hidden' name='moder' value='".$rw[2]."'><input type='hidden' name='volinpart' value='".$rw[1]."'></form></td>");
			}
			echo("</tr>");

				//Получаем список форумов для категории
				echo("<tr><td colspan=3>");
				$frm = mysql_query("select * from forum_forums;");
				if ($frm)
				{
					//Для каждого форума
					while ($rm = mysql_fetch_row($frm))
					{
						//Если этот форум относится к текущей категории, то создаём ссылку
						if ($rm[2] == $rw[1])
						{
							//Тип сообщения (прочитанное или нет или вообще нет ни одного)
							$count = messages($rw[0], $rm[0]);

							//Нет сообщений
							if ($count == 0)
							{
								$type = "empty";
							}

							//Новые есть
							if ($count > 0)
							{
								$type = "newmessage";
							}

							//Новых нет
							if ($count < 0)
							{
								$type = "oldmessage";
								$count = $count * (-1);
							}

							//Выводим категорию
							echo("<table border=1 width=98% cellpadding=0 cellspacing=0>");
							echo("<tr><td align=center width=><img src='images/icons/".$type.".ico' border=0></td><td width=80%><a href='forum.php?category=".$rw[0]."&forum=".$rm[0]."'><h3><dd>".$rm[0]."</h3></a></td><td align=center width=20%>Сообщений: ".$count."</td></tr>");
							echo("</table>");
						}
					}
				}
				echo("</td></tr>");
			
			//Конец категории
			echo("</table>");
		}
	}

//Конец основной таблицы
echo("</td></tr>");
echo("</table>");
?>