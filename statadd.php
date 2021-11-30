<title>Статистика</title>
<link rel='stylesheet' type='text/css' href='style.css'/>
<body background='images/back.jpe'>
<?
  include "functions.php";

  //Корректные ли данные
  if (trim($reff) == "")
  {
    echo "<p>Недопустимое имя реферера...</p>";
    echo "<a href=statistic.php>Вернуться</a>";
    exit();
  }

  $query_reff = "insert into stat_links values (0, '$reff');";
  if (mysql_query($query_reff))
    echo "<HTML><HEAD><META HTTP-EQUIV='Refresh' CONTENT='0; URL=statistic.php'></HEAD></HTTP>";
  else
    echo "Ошибка добавления реферера...";


?>