<?
  include "functions.php";

  //��� � �����
  echo ("<html>\n<head>\n<link rel='stylesheet' type='text/css' href='style.css'/>\n<title>Native Land</title>\n</head>\n<body background='images\back.jpe'>");
  
  $lg = trim($HTTP_COOKIE_VARS["nativeland"]);
  if (hasuser($lg) == 0)
    exit();

  //���������� ������������ � ����
  function addusertoclan($login, $clan)
  {
  	mysql_query ("insert into inclan values ('$login', '$clan', '0' , '0');");
  }

  //������� ����� ������������
  function inbases($username)
  {
    //����� �� ��� � �����
    $usr = mysql_query("select * from inclan;");
    $find = 0;
    if ($usr)
      while ($user = mysql_fetch_array($usr))
      if (($user['login'] == $username))
        $find = 1;
    else
      $find = 2;

    //����� �� ��� �����
    $usr = mysql_query("select * from clans;");
    if ($usr)
      while ($user = mysql_fetch_array($usr))
      if (($user['login'] == $username))
        $find = 1;
    else
      $find = 2;
    return $find;
  }

  //���� �� ���������:
  $login = $member;

  //���� ������������ ��� � �����, ��� ��� �����, �� ������
  if (inbases($login) != 0)
    moveto("game.php?action=19");

  //��������� ���������� ����� � ����, ��� ��������
  $money = getdata($login, 'economic', 'money');
  $curse = getdata($login, 'economic', 'curse');

  //�������� ����� ��� �����������
  $nalog = getdata($admin, 'clans', 'nalog');
  $summa = $nalog*$curse;

  //�������� ������
  $money = $money - $summa;
  if ($money < 0)
    $money = 0;

  //�������� ������ � �����������
  change($login, 'economic', 'money', $money);

  //��������� ������� � �������� �������
  $money = round($money / $curse);

  //��������� ��� ������ �� ���� �����
  $bill = getdata($admin, 'clans', 'bill');
  change($admin, 'clans', 'bill', $bill + $money);

  //��� ���� �� ����?
  $clan = getdata($admin, 'clans', 'name');

  //���������� ������ � ����
  addusertoclan($login, $clan);

  //����� ��� ������
  $heroname = getdata($login, 'hero', 'name');

  //� ������� �������� �� ���� ������ ���������
  sms($login, "������������� ����� ".$clan, "��������� ".$heroname." (".$login."), �� ������� � ��� ����. ����� ����������!");
 
  //�������������� �������������� �����
  messagebox("�� ������� ������� � ���� ������ ".$heroname." (".$login.")", "game.php?action=19");
?>