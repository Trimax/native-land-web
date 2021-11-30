<?
  //Соединение с базой
  function baselink()
  {
    $file = fopen("config.php", "r");
    $temp = trim(fgets($file, 255));
    $temp = trim(fgets($file, 255));
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

  //Получение данных из таблицы по имени поля, имени пользователя и имени таблицы
  function getdata($username, $table, $field)
  {
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

  //Проверка имени пользователя и пароля
  function finduser($username, $pass)
  {
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

  //Перемещение
  function moveto($addr)
  {
    echo("<script>");
    echo("window.location.href('".$addr."');");
    echo("</script>");
  }

  //Сообщение
  function ShowMessage($msg)
  {
    echo("<script>");
    echo("alert('".$msg."');");
    echo("</script>");
  }

  //Соединение с базой...
  baselink();

  //Выход
  if ($quit == 1)
  {
    $quit = 0;
	  setcookie("nativeland");
		setcookie("password");
    moveto("hosting.php");
  }

  //Авторизация...
  if ($auth == 1)
  {
    $auth = 0;
    setcookie("nativeland", $login, time()+3600*24);
		setcookie("password", $password, time()+3600*24);
    moveto("hosting.php");
  }

  //Авторизация на хостинге
  $lg = trim($HTTP_COOKIE_VARS["nativeland"]);
  $pw = trim($HTTP_COOKIE_VARS["password"]);
  $subdir = trim($HTTP_COOKIE_VARS["subdir"]);

  //Найден или нет пользователь
  if (finduser($lg, $pw) != 1)
  {
    //Фон и стиль
    echo ("<html>\n<head>\n<link rel='stylesheet' type='text/css' href='http://nld.spb.ru/style.css'/>\n<title>Native Land</title>\n</head>\n");
    ?>
      <center>
      <h2>Авторизация на странице хостинга</h2>
      <form action='hosting.php' method=post>
      <input type='hidden' name='auth' value='1'>
      <table border=0 cellpadding=0 cellspacing=0 width=40%>
      <tr><td align=center><input type="text" name="login" value="Логин" style='background:#000099; font-WEIGHT: BOLD; COLOR: white; cursor: hand; list-style: lower-alpha; filter: Alpha(Opacity=20, FinishOpacity=100, Style=3)'></td><td align=center><input type="password" name="password" value="" style='background:#000099; font-WEIGHT: BOLD; COLOR: white; cursor: hand; list-style: lower-alpha; filter: Alpha(Opacity=20, FinishOpacity=100, Style=3)'></td></tr>
      <tr><td colspan=2 align=center><input type="submit" style='background:#000099; font-WEIGHT: BOLD; COLOR: white; cursor: hand; list-style: lower-alpha; filter: Alpha(Opacity=20, FinishOpacity=100, Style=3)' value="Авторизироваться"></td></tr>
      </table>
      </form>
      Если с первого раза не вышло, попробуйте ещё раз :)
      </center>
    <?
    exit();
  }

  $bd = getdata($lg, 'hosting', 'dir');

  //Если 4-я строчка в конфигурационном файле равна той, что записана в БД, то всё ок.
  $file = fopen("config.php", "r");
  $temp = trim(fgets($file, 255));
  $temp = trim(fgets($file, 255));
  $temp = trim(fgets($file, 255));
  $fldr = trim(fgets($file, 255));
  fclose($file);
  if ($fldr != $bd)
  {
 	  setcookie("nativeland");
		setcookie("password");
    moveto("hosting.php");
  }
  
  //ДЕЙСТВИЯ С ФАЙЛАМИ
    //Загрузка файлов на сайт 
    if ($upload == 1)
    {
      $upload = 0;
      $AllOk = 1;
      if ($HTTP_POST_FILES["filename"]["size"] > 1024*100)
      {
        $AllOk = 0;
        ShowMessage("Размер файла превышает 100КБ");
      }
      $s = strtolower(substr($HTTP_POST_FILES["filename"]["name"], strlen($HTTP_POST_FILES["filename"]["name"])-3));
      if (($s == 'exe')||($s == 'com')||($s == 'bat'))
      {
        $AllOk = 0;
        ShowMessage("Exe, Com и Bat файлы загружать на сервер запрещается");
      }
      $fname = "";
      $flname = $HTTP_POST_FILES["filename"]["name"];
      for ($i = 0; $i < strlen($flname)-4; $i++)
        $fname = $fname.$flname[$i];
      $fname = strtolower($fname);
      if (($fname == 'config')||($fname == 'hosting'))
      {
        $AllOk = 0;
        ShowMessage("Запрещается использовать имена config и hosting для Ваших файлов");
      }
      if ($AllOk == 1)
      {
        echo("All Ok");
        $cpy = copy($HTTP_POST_FILES["filename"]["tmp_name"], $subdir.$flname);
        if ($cpy)
        {
          $upload = 0;
          moveto("hosting.php");
        } //Copy
      } //AllOk
    } //UpLoad

  //Удаление файлов с сайта
  if ($delete == 1)
  {
    $delete = 0;
    $crt = strtolower($filename);
    if (($crt != "config.php")&&($crt != "hosting.php"))
    {
      if (is_dir($filename))
      {
        if (!rmdir($subdir.$filename))
          ShowMessage("Каталог не пуст! Сначала удалите всё содержимое");
      }
      else
        unlink($subdir.$filename);
    }
    moveto("hosting.php");
  }

  //Создание каталога
  if ($newdir == 1)
  {
    $newdir = 0;
    mkdir($folder);
    moveto("hosting.php");
  }

  //Выход в корень
  if ($root == 1)
  {
    $root = 0;
    $subdir = "";
    setcookie("subdir");
    moveto("hosting.php");
  }

  //Вход в каталог
  if ($enter == 1)
  {
    $enter = 0;
    $subdir = $subdir.$catalog."/";
    setcookie("subdir", $subdir, time()+3600*24);
    moveto("hosting.php");
  }

  //Редактор файлов
  if ($edit == 1)
  {
    $edit = 0;
    echo ("<html>\n<head>\n<link rel='stylesheet' type='text/css' href='http://nld.spb.ru/style.css'/>\n<title>Native Land</title>\n</head>\n");
    ?>
      <center>
      <form action="hosting.php" method=post>
      <input type="hidden" name="save" value="1">
      <?
        echo("<input type='hidden' name='editfile' value='".$editfile."'>");
      ?>
      <table border=1 cellpadding=0 cellspacing=0 width=90%>
      <tr><td align=center><h3>Редактирование файла</h3></td></tr>
      <tr><td>
      <?
        echo("<textarea name='data' cols=70 rows=15 maxlength=255 style='background:#000099; font-WEIGHT: BOLD; COLOR: white; cursor: hand; list-style: lower-alpha; filter: Alpha(Opacity=20, FinishOpacity=100, Style=3)'>".file_get_contents($editfile)."</textarea>");
      ?>
      </td></tr>
      <tr><td align=center><input type="submit" style='background:#000099; font-WEIGHT: BOLD; COLOR: white; cursor: hand; list-style: lower-alpha; filter: Alpha(Opacity=20, FinishOpacity=100, Style=3)' value="Сохранить"></td></tr>
      </table>
      </form>
      </center>
    <?
    exit();
  }

  //Сохранение отредактированного файла
  if ($save == 1)
  {
    $save = 0;
    if ((strtolower($editfile) != "config.php")&&(strtolower($editfile) != "hosting.php"))
    {
      $fl = fopen($editfile, "w");
      //fputs($fl, htmlspecialchars($data));
      fputs($fl, $data);
      fclose($fl);
    }
    moveto("hosting.php");
  }

  //Выводим меню (слева кнопки, справа таблица с файлами)
  echo ("<html>\n<head>\n<link rel='stylesheet' type='text/css' href='http://nld.spb.ru/style.css'/>\n<title>Native Land</title>\n</head>\n");
  echo("<center><table border=2 cellpadding=0 cellspacing=0 width=90%><tr>");
  echo("<td width=20% align=center valign=top>");
  echo("<font size=6><b>Меню</b></font><br>");

  //Кнопка выхода в корневой каталог
  ?>
    <form action="hosting.php" method=post>
    <input type="hidden" name="root" value="1">
    <input type="submit" style='background:#000099; font-WEIGHT: BOLD; COLOR: white; cursor: hand; list-style: lower-alpha; filter: Alpha(Opacity=20, FinishOpacity=100, Style=3)' value="В начало">
    </form>
  <?  

  //Кнопка выхода с хостинга
  ?>
    <form action="hosting.php" method=post>
    <input type="hidden" name="quit" value="1">
    <input type="submit" style='background:#000099; font-WEIGHT: BOLD; COLOR: white; cursor: hand; list-style: lower-alpha; filter: Alpha(Opacity=20, FinishOpacity=100, Style=3)' value="Выход">
    </form>
  <?

  //Загрузка файлов на сайт
  echo("<font size=5><b>Загрузка</b></font><br>");
  ?>
    Файл не должен превышать 100 КБ. Если файл превышает этот размер, свяжитесь с <a href='mailto:admin@nld.spb.ru'>администратором</a> и договоритесь с ним о загрузке файла. Exe, Com и Bat файлы запрещены к загрузке
    <form action="hosting.php" method=post enctype="multipart/form-data">
    <input type="hidden" name="upload" value="1">
    <input type="file" name="filename" style='background:#000099; font-WEIGHT: BOLD; COLOR: white; cursor: hand; list-style: lower-alpha; filter: Alpha(Opacity=20, FinishOpacity=100, Style=3)'>
    <input type="submit" style='background:#000099; font-WEIGHT: BOLD; COLOR: white; cursor: hand; list-style: lower-alpha; filter: Alpha(Opacity=20, FinishOpacity=100, Style=3)' value="Загрузить">
    </form>
  <?

  //Создание каталога
  echo("<font size=5><b>Создание каталога</b></font><br>");
  ?>
    Разрешено создание только подкаталогов первого уровня
    <form action="hosting.php" method=post>
    <input type="hidden" name="newdir" value="1">
    <input type="text" name="folder" style='background:#000099; font-WEIGHT: BOLD; COLOR: white; cursor: hand; list-style: lower-alpha; filter: Alpha(Opacity=20, FinishOpacity=100, Style=3)'>
    <input type="submit" style='background:#000099; font-WEIGHT: BOLD; COLOR: white; cursor: hand; list-style: lower-alpha; filter: Alpha(Opacity=20, FinishOpacity=100, Style=3)' value="Создать">
    </form>
  <?

  //Выводим путь
  $path = "http://nativeland.spb.ru/".$bd."/".$subdir;

  //Теперь таблица с файлами и действия над ними
  echo("</td><td align=center>");
  echo("<table border=1 cellpadding=0 cellspacing=0 width=90%>");
  echo("<tr><td colspan=4 align=center>Путь: <b>".$path."</b></td></tr>");
  echo("<tr><td align=center>Имя файла</td><td align=center width=10%>Редактировать</td><td align=center width=10%>Войти</td><td align=center width=10%>Удалить</td></tr>");

  //Выводим таблицу с файлами
  $dir = opendir("."."/".$subdir);
  while ($file = readdir($dir))
  {
    if (($file != ".")&&($file != "..")&&($file != "hosting.php")&&($file != "config.php"))
    {
      echo("<tr><td><a href='".$file."'>".$file."</a></td>");

      //Для файла кнопка "Редактор"
      if (is_file($file))
      {
        echo("<td align=center colspan=2>");
        ?>
          <br>
          <form action="hosting.php" method=post>
          <input type="hidden" name="edit" value="1">
          <?
            echo("<input type='hidden' name='editfile' value='".$file."'>");
          ?>
          <input type="submit" style='background:#000099; font-WEIGHT: BOLD; COLOR: white; cursor: hand; list-style: lower-alpha; filter: Alpha(Opacity=20, FinishOpacity=100, Style=3)' value="Редактор">
          </form>
        <?
        echo("</td>");
      }

      //Для директории кнопка "Вход"
      if (is_dir($file))
      {
        echo("<td align=center colspan=2>");
        ?>
          <br>
          <form action="hosting.php" method=post>
          <input type="hidden" name="enter" value="1">
          <?
            echo("<input type='hidden' name='catalog' value='".$file."'>");
          ?>
          <input type="submit" style='background:#000099; font-WEIGHT: BOLD; COLOR: white; cursor: hand; list-style: lower-alpha; filter: Alpha(Opacity=20, FinishOpacity=100, Style=3)' value="Вход">
          </form>
        <?
        echo("</td><td align=center>");
      }
      else
      echo("<td align=center>");
      ?>
        <br>
        <form action="hosting.php" method=post>
         <input type="hidden" name="delete" value="1">
         <input type="submit" style='background:#000099; font-WEIGHT: BOLD; COLOR: white; cursor: hand; list-style: lower-alpha; filter: Alpha(Opacity=20, FinishOpacity=100, Style=3)' value="Удалить">
      <?
      echo("<input type='hidden' name='filename' value='".$file."'>");
      echo("</form></td>");
    }
  }
  closedir($dir);

  //Конец таблицы файлов
  echo("</table>");

  //Конец рабочей таблицы
  echo("</td></tr></table></center>");  
?>