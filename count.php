<?
  include "functions.php";

  //������� ������
  Error_Reporting(E_ALL & ~E_NOTICE);

  //���� ��������
  $page    = $PHP_SELF;
  $ip      = $REMOTE_ADDR;
  $forward = getenv(HTTP_X_FORWARDED_FOR);

  //������ IP
  if (($forward != NULL)&&($forward != $REMOTE_ADDR))
    $ip = $ip."/".$forward;
  
  //�������
  $reff = urldecode(getenv('HTTP_REFERER'));

  //�������� id_page ������ ��������
  $pgs = mysql_query("SELECT * FROM stat_pages WHERE name='$page';");
  if ($pgs)
  {
    $pag = mysql_fetch_array($pgs);
    $nm = $pag['id_page'];
  }

  //���� ���� �������� � ������� ���, �� ��������� � ����
  if ($pag['name'] == NULL)
  {
    $query = "insert into stat_pages values(0, '$page', 0)";
    mysql_query($query);
  }

  //������������� ��� ��������
  $br = 0;
  //Internet explorer
  if (strpos($HTTP_USER_AGENT, "MSIE")  !== false)
    if (strpos($HTTP_USER_AGENT, "Opera") == NULL)
      $br = 1;
  //Opera
  if (strpos($HTTP_USER_AGENT, "Opera") !== false)
    $br = 2;
  //Netscape navigator
  if (strpos($HTTP_USER_AGENT, "Netscape") !== false)
    $br = 3;
  
  //������������� ��� ��
  $os = 0;
  //Windows
  if (strpos($HTTP_USER_AGENT, "Win") !== false)
    $os = 1;
  //*nix
  if (strpos($HTTP_USER_AGENT, "Linux") !== false || strpos($HTTP_USER_AGENT, "Unix") !== false)
    $os = 2;
  //MacOS
  if (strpos($HTTP_USER_AGENT, "Macintosh") !== false)
    $os = 3;

  //�������������� � �������
  if (substr($HTTP_USER_AGENT, 0, 12) == "StackRambler")
    $os = 4;
  if (substr($HTTP_USER_AGENT, 0, 9) == "GoogleBot")
    $os = 5;
  if (substr($HTTP_USER_AGENT, 0, 6) == "Yandex")
    $os = 6;
  if (substr($HTTP_USER_AGENT, 0, 5) == "Aport")
    $os = 7;

  //���������
  $srch = 0;
  if (strpos($reff, "yandex"))
    $srch = 1;
  if (strpos($reff, "rambler"))
    $srch = 2;
  if (strpos($reff, "google"))
    $srch = 3;
  if (strpos($reff, "aport"))
    $srch = 4;

  //������� ��� ���������� � ������� IP
  $query_main = "insert into stat_ip values ('$HTTP_USER_AGENT', '$ip', now(), $nm, $br, $os, $srch);";
  mysql_query($query_main);

  //���� ������� �� ����, ������� ��� � ����
  if ($reff != "")
  {
    $query_reff = "insert into refferer values (0, '$reff', now(), '$ip', $nm);";
    mysql_query($query_reff);
  }
?>