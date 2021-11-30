<?
  include "functions.php";

  //Удаляем страницу из таблиц
  if (mysql_query("delete from stat_ip where id_page=$page;") && mysql_query("delete from stat_refferer where id_page=$page;"))
  {
    if (mysql_query("delete from stat_pages where id_page=$page;"))
    {
      print("<HTML><HEAD>\n");
      print("<META HTTP-EQUIV='Refresh' CONTENT=0 URL='statistic.php'>\n");
      print("</HEAD></HTML>\n");
    }
  }
?>