<?
  //��������� ������
  include "functions.php";

  //���� �� ������� ��� ������������, �� �������� ����� �����
  $lg = trim($HTTP_COOKIE_VARS["nativeland"]);
  $pw = trim($HTTP_COOKIE_VARS["password"]);

  //����������?
  if (finduser($lg, $pw) != 1)
  	moveto('index.php');

  //���� ����� � �����, �� �������������� ��� ����
  FromBattle($lg);
  ban();

  //��������� ������
  function NewBuild($Number, $Login, $Type, $RegionX, $RegionY, $CoordX, $CoordY, $Info)
  {
    mysql_query("insert into mapbuild values('$Number', '$Login', '$Type', '$RegionX', '$RegionY', '$CoordX', '$CoordY', '$Info');");
    //����������� ���������
    if ($Type == 1)
    {
      $Peoples = getdata($Login, 'economic', 'peoples');
      $Peoples = $Peoples + 20;
      change($Login, 'economic', 'peoples', $Peoples);
    }
  }

  //���������, � ���� �� �� ����� ����� ���������?
  $mx = getdata($lg, 'coords', 'rx');
  $my = getdata($lg, 'coords', 'ry');
  if (($mx == $rx)&&($my == $ry))
  {
  }
  else
    moveto("map.php");  

  //���� ��� ����, �� ������������ �����
  if ($tp == 4)
    moveto("map.php");

  //���� ��������� ��� �� ������
  $lastbuild = getdata($lg, 'temp', 'param');
  $delta = time()-$lastbuild;
  $delta = round($delta/3600);
  $hours = 24 - $delta;
  if ($hours < 0)
    $hours = 0;
  if ($delta < 24)
    messagebox("����� ���������� ��������� ��� ".$hours." �����", "map.php");

  //����������� ���� �� ������
  $curse     = getdata($lg, 'economic', 'curse');
  $moneyname = getdata($lg, 'economic', 'moneyname'); 
  switch($object)
  {
    case 0: //�����
      $money = 10000*$curse;
      $metal = 1000;
      $rock  = 2000;
      $wood  = 2000;
      $type = 1;
      break;
    case 1: //������
      $money = 7000*$curse;
      $metal = 4000;
      $rock  = 4000;
      $wood  = 500;
      $type = 2;
      break;
    case 2: //������
      $money = 7000*$curse;
      $metal = 6000;
      $rock  = 2500;
      $wood  = 1500;
      $type = 3;
      break;
    case 3: //������
      $money = 5000*$curse;
      $metal = 2000;
      $rock  = 500;
      $wood  = 3500;
      $type = 4;
      break;
  }
  
  //� ����� �� ����� �������?
  $mymoney = getdata($lg, 'economic', 'money');
  $mymetal = getdata($lg, 'economic', 'metal');
  $myrock  = getdata($lg, 'economic', 'rock');
  $mywood  = getdata($lg, 'economic', 'wood');

  //���������� ��
  if ($mymoney < $money)
    messagebox("� ��� ������������ ".$moneyname." ��� �������������", "map.php");
  if ($mymetal < $metal)
    messagebox("� ��� ������������ ������� ��� �������������", "map.php");
  if ($myrock  < $rock)
    messagebox("� ��� ������������ ����� ��� �������������", "map.php");
  if ($mywood  < $wood)
    messagebox("� ��� ������������ ������ ��� �������������", "map.php");

  //�������� �������
  change($lg, 'economic', 'money', $mymoney - $money);
  change($lg, 'economic', 'metal', $mymetal - $metal);
  change($lg, 'economic', 'rock',  $myrock  - $rock);
  change($lg, 'economic', 'wood',  $mywood  - $wood);

  //�������� ������ ������������� � ����������� ��� �� "1"
  $index = getfrom('admin', getadmin(), 'settings', 'f5');
  $index++;
  setto('admin', getadmin(), 'settings', 'f5', $index);

  //������ ������
  newbuild($index, $lg, $type, $rx, $ry, $cx, $cy, $bname);

  //���������� ���������� ��������
  change($lg, 'temp', 'param', time());
  
  //�������������� �� �����
  moveto('map.php');
?>