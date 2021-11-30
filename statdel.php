<?
  include "functions.php";
  if (mysql_query("delete from stat_links where id_links=$id_links;"))
    echo "<HTML><HEAD><META HTTP-EQUIV='Refresh' CONTENT='0; URL=statistic.php'></HEAD></HTML>";
  else
    echo "Ошибка удаления реферера";
?>