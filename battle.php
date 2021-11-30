<?
//��� � ����� � ������
include "functions.php";

//����� �� ��� (� ��������)
$TimeOut = 60;

//���� �� ������� ��� ������������, �� �������� ����� �����
$lg = trim($HTTP_COOKIE_VARS["nativeland"]);
$pw = trim($HTTP_COOKIE_VARS["password"]);

//� ���� �� ����� ������������
if (finduser($lg, $pw) == 0)
{
  setcookie("nativeland");
  setcookie("password");
	moveto("index.php");
}

ban();

//���� ���������� �������
function Price($lg, $Opp)
{
  //�������� ����������
  $Msg = "";

  //������ �� ���
  $Mnstr = getdata($Opp, 'city', 'build14');

  //���� ����� ���� � ��������
  if ($Mnstr == 1)
  {
    //��������� �������
    $Res = rand(1, 3);
    $Mn  = rand(10, 100);
    $Tp  = rand(1, 3);
      
    //�������� �����
    $Valute = getdata($lg, 'economic', 'moneyname');

    //���� �����
    $Curse = getdata($lg, 'economic', 'curse');
    $Mn = $Mn * $Curse;

    //��������� ���������
    $Msg = "�� �������� ".$Res;

    //�������� ������
    if ($Tp == 1)
    {
      change($lg, 'economic', 'metal', getdata($lg, 'economic', 'metal') + $Res);
      $Msg = $Msg." ������� � ".$Mn;
    }

    //�������� �����
    if ($Tp == 2)
    {
      change($lg, 'economic', 'rock', getdata($lg, 'economic', 'rock') + $Res);
      $Msg = $Msg." ����� � ".$Mn;
    }

    //�������� ������
    if ($Tp == 3)
    {
      change($lg, 'economic', 'wood', getdata($lg, 'economic', 'wood') + $Res);
      $Msg = $Msg." ������ � ".$Mn;
    }

    //��������� �������� �����
    $Msg = $Msg.$Valute;

    //�������� �������, ������� ���� � �������
    $H[0] = getdata($Opp, 'bottles', 'hmaxi');
    $H[1] = getdata($Opp, 'bottles', 'hmedi');
    $H[2] = getdata($Opp, 'bottles', 'hmini');
    $M[0] = getdata($Opp, 'bottles', 'mmaxi');
    $M[1] = getdata($Opp, 'bottles', 'mmedi');
    $M[2] = getdata($Opp, 'bottles', 'mmini');

    //����� ��� ������� ������
    $Items = "<br>";
    if ($H[0] != 0)
      $Items = $Items."<img src='images\bottles\big_h.jpg'>";
    if ($H[1] != 0)
      $Items = $Items."<img src='images\bottles\med_h.jpg'>";
    if ($H[2] != 0)
      $Items = $Items."<img src='images\bottles\sma_h.jpg'>";
    if ($M[0] != 0)
      $Items = $Items."<img src='images\bottles\big_m.jpg'>";
    if ($M[1] != 0)
      $Items = $Items."<img src='images\bottles\med_m.jpg'>";
    if ($M[2] != 0)
      $Items = $Items."<img src='images\bottles\sma_m.jpg'>";

    //������������ � ����
    $H[0] = $H[0] + getdata($lg, 'bottles', 'hmaxi');
    $H[1] = $H[1] + getdata($lg, 'bottles', 'hmedi');
    $H[2] = $H[2] + getdata($lg, 'bottles', 'hmini');
    $M[0] = $M[0] + getdata($lg, 'bottles', 'mmaxi');
    $M[1] = $M[1] + getdata($lg, 'bottles', 'mmedi');
    $M[2] = $M[2] + getdata($lg, 'bottles', 'mmini');
    change($lg, 'bottles', 'hmaxi', $H[0]);
    change($lg, 'bottles', 'hmedi', $H[1]);
    change($lg, 'bottles', 'hmini', $H[2]);
    change($lg, 'bottles', 'mmaxi', $M[1]);
    change($lg, 'bottles', 'mmedi', $M[2]);
    change($lg, 'bottles', 'mmini', $M[3]);

    //������������ ����
    $Items = $Items."<br>";

    //�������� ������ �����
    $Num1 = getdata($Opp, 'items', 'rightruka');
    $Num2 = getdata($Opp, 'items', 'leftruka');

    //��������� ���� � ���������
    PushItem($lg, $Num1);
    PushItem($lg, $Num2);

    //�������� �������� �����
    $Img1 = getfrom('num', $Num1, 'allitems', 'img');
    $Img2 = getfrom('num', $Num2, 'allitems', 'img');

    //��������� ���������
    $Items = $Items."<img src='images/weapons/$Img1'><img src='images/weapons/$Img2'>";

    //��������� ���������
    $Msg = $Msg.$Items;
  } //����� ���� � ��������

  //������� ������
  return $Msg;
}

//���������
echo ("<html>\n<head>\n<link rel='stylesheet' type='text/css' href='style.css'/>\n<title>Native Land</title>\n</head>\n<body background='images\back.jpe'>\n");
echo ("<META HTTP-EQUIV='REFRESH' CONTENT=6>\n");

//���������
change ($lg, 'status', 'online', '1');
change ($lg, 'inf', 'fld3', time());

//������� ���������� ���������, � � ����� �� �����?
$battle = getdata($lg, 'battle', 'battle');
if ($battle == 0)
{
  $Winner = getdata($lg, 'hero', 'health');
  if ($Winner > 0)
    $Winner = 1;
  if ($Winner == 1)
    messagebox("� ���� �������� �� ��������.<br>".$Finish, "game.php?action=1");
  else
    messagebox("� ���� �������� �� ���������.", "game.php?action=1");
}

//�������� ������� � ��� ����
function ToLog($Num, $String)
{
  //������ ����
  $file = fopen("data/logs/".$Num.".log", "r");
  for ($i = 0; $i < 10; $i++)
    $Str[$i+1] = trim(fgets($file, 255));
  fclose($file);
  
  //������� �����
  $tm = "<font color=black><b>(".date("<b>H:i:s</b>", time()).")</b></font>";

  //��������� � ������ �������
  $Str[0] = $String." ".$tm."<br>";

  //��������� ����
  $file = fopen("data/logs/".$Num.".log", "w");
  for ($i = 0; $i < 10; $i++)
    fputs($file, $Str[$i]."\n");
  fclose($file);
}

//����� ���������� ����� ��������
function MaxiCast($Login)
{
  $max = 0;
  $num = getdata($Login, 'magic', 'cast1');
  $eff = getfrom('num', $num, 'allcasts', 'effect');
  for ($i = 2; $i <= 6; $i++)
  {
    $num = getdata($Login, 'magic', 'cast'.$i);
    $tmp = getfrom('num', $num, 'allcasts', 'effect');
    if ($tmp > $eff)
    {
      $eff = $tmp;
      $max = $num;
    }
  }

  //���������� ����� ����������! (��� ��������� ������)
  return $max;
}

//������ �����
function BladeStrike($Login)
{
  //������� ���� - ���� ������
  $Damage = getdata($Login, 'abilities', 'power');

  //����� ������ � ����
  (int)$Num = getdata($Login, 'items', 'rightruka');

  //�������������� ���� (�� ������ � ����)
  $Procent = getfrom('num', $Num, 'allitems', 'effect');

  //������ ��������������� �����
  $AddOn = ($Damage / 100) * $Procent;

  //�������������� ����, ���� ������ �������
  $Bottle = getdata($Login, 'items', 'vrukah');
  switch($Bottle)
  {
    case 1:
      $Damage = 2*$Damage;
      change($Login, 'items', 'vrukah', '0');
      break;
    case 2:
      $Damage = 1.5*$Damage;
      change($Login, 'items', 'vrukah', '0');
      break;
    case 3:
      $Damage = 1.25*$Damage;
      change($Login, 'items', 'vrukah', '0');
      break;
  }

  //����������� ���� �� ���. ������������
    //���
    $Battle = $Damage*Level(4, $Login)/100;

  //������������� �������� �����������
  $Damage = round($Damage + $AddOn + $Battle);

  //���������� �������� �����
  return $Damage;
}

//������ ����� �������
function PosohStrike($Login)
{
  //������� ���� - ���� ������
  $Damage = getdata($Login, 'abilities', 'naturemagic');

  //����� ������ � ����
  (int)$Num = getdata($Login, 'items', 'rightruka');

  //�������������� ���� (�� ������ � ����)
  $Procent = getfrom('num', $Num, 'allitems', 'effect');

  //������ ��������������� �����
  $AddOn = ($Damage / 100) * $Procent;

  //������������� �������� �����������
  $Damage = round($Damage + $AddOn + $Battle);

  //���������� �������� �����
  return $Damage;
}

//������ ������
function ShieldProtection($Login)
{
  //������� ���� - ���� ������
  $Damage = getdata($Login, 'abilities', 'protect');

  //������ �� ����
    //����� ������ � ����� ����
    (int)$Num = getdata($Login, 'items', 'leftruka');

    //���� ������� �������� � ������
    $Verb = getfrom('num', $Num, 'allitems', 'action');
    $AddOn = 0;
    if ($Verb == 2)
    {
      //�������������� ���� (�� ������ � ����)
      $Procent = getfrom('num', $Num, 'allitems', 'effect');

      //������ ��������������� �����
      $AddOn = ($Damage / 100) * $Procent;
    }

  //������ �� �����
    //����� ������ �� ����
    (int)$Num = getdata($Login, 'items', 'telo');

    //���� ������� �������� � ������
    $Verb = getfrom('num', $Num, 'allitems', 'action');
    $Armor = 0;
    if ($Verb == 2)
    {
      //�������������� ���� (�� ������ � ����)
      $Procent = getfrom('num', $Num, 'allitems', 'effect');

      //������ ��������������� �����
      $Armor = ($Damage / 100) * $Procent;
    }

  //������ �� �����
    //����� ������ �� ������
    (int)$Num = getdata($Login, 'items', 'golova');

    //���� ������� �������� � ������
    $Verb = getfrom('num', $Num, 'allitems', 'action');
    $Head = 0;
    if ($Verb == 2)
    {
      //�������������� ���� (�� ������ � ����)
      $Procent = getfrom('num', $Num, 'allitems', 'effect');

      //������ ��������������� �����
      $Head = ($Damage / 100) * $Procent;
    }

  //������ �� �����
    //����� ������ �� ����
    (int)$Num = getdata($Login, 'items', 'tors');

    //���� ������� �������� � ������
    $Verb = getfrom('num', $Num, 'allitems', 'action');
    $Tors = 0;
    if ($Verb == 2)
    {
      //�������������� ���� (�� ������ � ����)
      $Procent = getfrom('num', $Num, 'allitems', 'effect');

      //������ ��������������� �����
      $Tors = ($Damage / 100) * $Procent;
    }

  //������ �� ������ �� �������
    //����� ������ �� �������
    (int)$Num = getdata($Login, 'items', 'koleni');

    //���� ������� �������� � ������
    $Verb = getfrom('num', $Num, 'allitems', 'action');
    $Koleni = 0;
    if ($Verb == 2)
    {
      //�������������� ���� (�� ������ � ����)
      $Procent = getfrom('num', $Num, 'allitems', 'effect');

      //������ ��������������� �����
      $Koleni = ($Damage / 100) * $Procent;
    }

  //������ �� ���
    //����� ������ �� �����
    (int)$Num = getdata($Login, 'items', 'nogi');

    //���� ������� �������� � ������
    $Verb = getfrom('num', $Num, 'allitems', 'action');
    $Nogi = 0;
    if ($Verb == 2)
    {
      //�������������� ���� (�� ������ � ����)
      $Procent = getfrom('num', $Num, 'allitems', 'effect');

      //������ ��������������� �����
      $Nogi = ($Damage / 100) * $Procent;
    }

  //������ �� �����
    //����� ������ �� ����
    (int)$Num = getdata($Login, 'items', 'plash');

    //���� ������� �������� � ������
    $Verb = getfrom('num', $Num, 'allitems', 'action');
    $Plash = 0;
    if ($Verb == 2)
    {
      //�������������� ���� (�� ������ � ����)
      $Procent = getfrom('num', $Num, 'allitems', 'effect');

      //������ ��������������� �����
      $Plash = ($Damage / 100) * $Procent;
    }

  //������������� �������� �����������
  $Damage = $Damage + $Armor + $Tors + $Head + $Koleni + $Nogi + $Plash;

  //���� ������ ������ ����������
  $Shield = getdata($Login, 'battle', 'attack');
  if ($Shield == 1)
  {
    change($Login, 'battle', 'attack', '0');
    $Damage = $Damage + $AddOn;
  }

  //���� ������ ������
  if ($Shield < 0)
  {
    change($Login, 'battle', 'attack', '0');
    $Damage = $Damage + $Shield;
    if ($Damage < 0)
      $Damage = 0;
  }

  //���������� �������� �����
  return $Damage;
}

//���������� �����
function ItExpa($Login)
{
  //�������� ����������
  $Combo = getdata($Login, 'battle', 'data');
  $Code = "";

  //������������ ���������� � SHORT ���
  $pos = 0;
  $Code = $Combo[0];
  for ($i = 1; $i < strlen($Combo); $i++)
  {
    //���� ��� ����� ������ �� �����������...
    if ($Code[$pos] != $Combo[$i])
    {
      $pos++;
      $Code[$pos] = $Combo[$i];
    }
  }

  //��������� ����� �� �����
  $TurnExpa = 0;
  for ($i = 0; $i < strlen($Code)+1; $i++)
  {
    //��� �� ����?
    switch($Code[$i])
    {
      //����
      case 'A':
        $TurnExpa++;
        $TurnExpa++;
        break;

      //������
      case 'D':
        $TurnExpa++;
        break;

      //�����
      case 'M':
        $TurnExpa++;
        $TurnExpa++;
        break;

      //�������� ����
      case 'C':
        $TurnExpa++;
        $TurnExpa++;
        $TurnExpa++;
        break;
    } // ����� ������
  } // ����� �����

  //��������� �����
  $Exp = getdata($Login, 'battle', 'health');
  change($Login, 'battle', 'health', $TurnExpa + $Exp);

  //�������� ���
  change($Login, 'battle', 'data', '');
}

//�������� ����
function TurnStep($Login, $Opp)
{
  //��������� ����� ��������
  $hp = getdata($Login, 'battle', 'value');
  $Dex = 6 + Level(3, $Login);
  change($Login, 'battle', 'value', $hp+$Dex);

  //���������� ������ �������� ����
  change($Login, 'battle', 'timeout', time());
  change($Opp, 'battle', 'timeout', time());

  //������� ������ ������ �������
  change($Login, 'battle', 'turn', $Opp);
  change($Opp, 'battle', 'turn', $Opp);

  //����� ����� ������
  $me = getdata($Login, 'hero', 'name');
  $he = getdata($Opp, 'hero', 'name');

  //����� ����� ���
  $Num = getdata($Login, 'battle', 'info');

  //�������������� ��������
  $How = Level(5, $Login);
  if ($How != 0)
  {
    $Health = getdata($Login, 'hero', 'health');
    $Level = getdata($Login, 'hero', 'level');
    $AddHealth = $Level*$How;
    $Health = $Health + $AddHealth;
    if ($Health > $Level*100)
      $Health = $Level*100;

    //��������� ������� ��� ��������
    ToLog($Num, "<font color=yellow><b>".$me."</b></font> ��������������� ���� <font color=green>".$AddHealth."</font> ����� ��������</font>");

    //������� �������� �������
    change($Login, 'abilities', 'magicpower', '0');
    change($Opp, 'abilities', 'magicpower', '0');

    //������ ��������
    change($Login, 'hero', 'health', $Health);

    //��������� �����
    moveto("battle.php");
  }

  //�������������� ����
  $How = Level(7, $Login);
  if ($How != 0)
  {
    $Health = getdata($Login, 'abilities', 'intellegence');
    $Level = getdata($Login, 'abilities', 'cnowledge');
    $AddHealth = round($Level*$How/10);
    $Health = $Health + $AddHealth;
    if ($Health > $Level*10)
      $Health = $Level*10;

    //��������� ������� ��� ����
    ToLog($Num, "<font color=yellow><b>".$me."</b></font> ��������������� ���� <font color=green>".$AddHealth."</font> ����� ����</font>");

    //������ ��������
    change($Login, 'abilities', 'intellegence', $Health);
  }

  //���������� � ���
  ToLog($Num, "<font color=yellow><b>".$me."</b></font> ������� ��� <font color=yellow><b>".$he."</b></font>");

  //���������� ���� �� ��������� ���
  ItExpa($Login);

  //��������� �����
  moveto("battle.php");
}

//���������� ����������
function DoCast($Login, $Number, $Type)
{
  //����� ��� �����
  $Damage = getdata($Login, 'abilities', 'naturemagic');

  //����� �� ����������
  $Cast = getfrom('num', $Number, 'allcasts', 'effect'); 
  $AddOn = ($Damage / 100) * $Cast;

  //����������
  $AddOn = $AddOn*1.7;

  //�������������� ����, ���� ������ �������
  $Bottle = getdata($Login, 'abilities', 'magicpower');
  switch($Bottle)
  {
    case 1:
      $Damage = 2*$Damage;
      break;
    case 2:
      $Damage = 1.5*$Damage;
      break;
    case 3:
      $Damage = 1.25*$Damage;
      break;
  }

  //���. �����������
  switch($Type)
  {
    case 1:
      $Damage = $Damage + Level(17, $Login)*$Damage/100;
      break;
    case 2:
      $Damage = $Damage + Level(16, $Login)*$Damage/100;
      break;
    case 3:
      $Damage = $Damage + Level(18, $Login)*$Damage/100;
      break;
  }

  //������ �����
  $Damage = round($Damage + $AddOn);

  //���������� ����
  return $Damage;
}

//������������� �����
function UseCast($Login, $Opp, $Damage, $Verb)
{
  //���������
  $Interval = rand(100, 1);
  $Dxt = Level(13, $Opp);
  if (($Interval < $Dxt)&&($Verb != 2))
    $Verb = 0;

  //��� ������ ����������?
  switch($Verb)
  {
    //���������
    case 0:
      $What = "���������";
      break;
    //������� �����
    case 1:
      //������ ��� ��� �����
      $What = "������� ���� ".$Damage;
      
      //�������� ���������
      $NewHealth = getdata($Opp, 'hero', 'health');
      $NewHealth = $NewHealth - $Damage;
      if ($NewHealth < 0)
        $NewHealth = 0;

      //������ ���
      change($Opp, 'hero', 'health', $NewHealth);
      break;

    //�������
    case 2:
      //������ ��� ��� �����
      $What = "��������������� ���� ".$Damage." ��������";
      
      //�������� ���������
      $NewHealth = getdata($Login, 'hero', 'health');
      $NewHealth = $NewHealth + $Damage;

      //������ ���
      change($Login, 'hero', 'health', $NewHealth);
      break;

    //�������� �����
    case 3:
      //������ ��� ��� �����
      $Damage = $Damage / 2;
      $What = "���������� ".$Damage." ����� �� ���������";
      
      //�������� ���������
      $NewHealth = getdata($Opp, 'hero', 'health');
      $NewHealth = $NewHealth - $Damage;
      if ($NewHealth < 0)
        $NewHealth = 0;

      //�� ��������
      $MyHealth = getdata($Login, 'hero', 'health');
      if ($NewHealth > 0)
        $MyNewHealth = $MyHealth + $Damage;
      else
        $MyNewHealth = $MyHealth;

      //�� �� �����, ��� �����
      $Lvl = getdata($Login, 'hero', 'level');
      if ($MyNewHealth > $Lvl*100)
        $MyNewHealth = $Lvl*100;

      //������ ���
      change($Opp, 'hero', 'health', $NewHealth);
      change($Login, 'hero', 'health', $MyNewHealth);
      break;

    //����������� �����
    case 4:
      //������ ��� ��� �����
      $What = "������� ���� ���� ".$Damage;
      
      //�������� ���������
      $NewHealth = getdata($Opp, 'hero', 'health');
      $NewHealth = $NewHealth - $Damage;
      if ($NewHealth < 0)
        $NewHealth = 0;

      //������ ���
      change($Opp, 'hero', 'health', $NewHealth);

      //�� ��������
      $NewHealth = getdata($Login, 'hero', 'health');
      $NewHealth = $NewHealth - $Damage;
      if ($NewHealth < 0)
        $NewHealth = 0;

      //������ ���
      change($Login, 'hero', 'health', $NewHealth);
      break;

    //�������� �� �� 1
    case 5:
      //������ ��� ��� �����
      $Damage = 1;
      $What = "������� ���� �������� �� ".$Damage;
      
      //�������� ���������
      $NewHealth = getdata($Opp, 'battle', 'value');
      $NewHealth = $NewHealth - $Damage;
      if ($NewHealth < 0)
        $NewHealth = 0;

      //������ ���
      change($Opp, 'battle', 'value', $NewHealth);
      break;

    //�������� �� �� 2
    case 6:
      //������ ��� ��� �����
      $Damage = 2;
      $What = "������� ���� �������� �� ".$Damage;
      
      //�������� ���������
      $NewHealth = getdata($Opp, 'battle', 'value');
      $NewHealth = $NewHealth - $Damage;
      if ($NewHealth < 0)
        $NewHealth = 0;

      //������ ���
      change($Opp, 'battle', 'value', $NewHealth);
      break;

    //���������
    case 7:
      //������ ��� ��� �����
      $What = "������� ������ ��������� �� ".$Damage;
      
      //������ ���
      change($Opp, 'battle', 'attack', (-1)*$Damage);
      break;

    //����������� ���� �����
    case 8:
      //������ ��� ��� �����
      $Damage = $Damage / 2;
      $What = "���������� ".$Damage." ���� ���������";
      
      //���� ���������
      $NewHealth = getdata($Opp, 'abilities', 'intellegence');
      $NewHealth = $NewHealth - $Damage;
      if ($NewHealth < 0)
        $NewHealth = 0;

      //��� ����
      $MyHealth = getdata($Login, 'abilities', 'intellegence');
      if ($NewHealth > 0)
        $MyNewHealth = $MyHealth + $Damage;
      else
        $MyNewHealth = $MyHealth;

      //������ ���
      change($Opp, 'abilities', 'intellegence', $NewHealth);
      change($Login, 'abilities', 'intellegence', $MyNewHealth);
      break;

    //���������� ���� ���������
    case 9:
      //������ ��� ��� �����
      $What = "��������� ���������� ���� �� ".$Damage;
      
      //�������� ���������
      $NewHealth = getdata($Opp, 'abilities', 'intellegence');
      $NewHealth = $NewHealth - $Damage;
      if ($NewHealth < 0)
        $NewHealth = 0;

      //������ ���
      change($Opp, 'abilities', 'intellegence', $NewHealth);
      break;
  }

  //���������� ���������
  return $What;
}

//�������� �� ������ / ���������
function OffBattle($Login, $Opp)
{
  //��������� �����
  change($Login, 'battle', 'battle', '0');
  change($Opp, 'battle', 'battle', '0');

  //� � ��� �� �������
  $Monster = getdata($Opp, 'city', 'build14');

  //���������� ��� ������ �� 0
  change($Login, 'battle', 'health', '0');
  change($Opp, 'battle', 'health', '0');
  change($Login, 'battle', 'opponent', '0');
  change($Opp, 'battle', 'opponent', '0');
  change($Login, 'battle', 'turn', '0');
  change($Opp, 'battle', 'turn', '0');
  change($Login, 'battle', 'attack', '0');
  change($Opp, 'battle', 'attack', '0');
  change($Login, 'battle', 'data', '');
  change($Opp, 'battle', 'data', '');
  change($Login, 'battle', 'value', '0');
  change($Opp, 'battle', 'value', '0');
  change($Login, 'battle', 'info', '0');
  change($Opp, 'battle', 'info', '0');
  change($Login, 'battle', 'timeout', '0');
  change($Opp, 'battle', 'timeout', '0');

  //��������� ������������ � ���� ����� (� ��� ��������?)
  $mh = getdata($Login, 'hero', 'health');
  if ($mh <= 0)
  {
    $Loser = $Login;
    $Winner = $Opp;
  }
  $op = getdata($Opp, 'hero', 'health');
  if ($op <= 0)
  {
    $Loser = $Opp;
    $Winner = $Login;
  }

  //����� ������������
    //���������� ���������� �������
    $rx = getdata($Loser, 'capital', 'rx');
    $ry = getdata($Loser, 'capital', 'ry');
    $cx = getdata($Loser, 'capital', 'x');
    $cy = getdata($Loser, 'capital', 'y');

    //���������� ���� ������
    change($Loser, 'coords', 'rx', $rx);
    change($Loser, 'coords', 'ry', $ry);
    change($Loser, 'coords', 'x',  $cx);
    change($Loser, 'coords', 'y',  $cy);

    //�������� �� ���� ������������ (���� ��� �� ������ �������)
    $HName = getdata($Winner, 'hero', 'name');
    if (($Loser == $Opp)&&($Monster == 1))
    {
      //� ���� ������ �������� ������� �� ����!
    }
    else
      sms($Loser, "���������", "����� �������� � ".$HName." �� ��������� ����� ��������� � ������������. ���������, �� ���������� ���� � ���� ������ ������");

  //���� �� ��������, ������� ������� � �����
  if ($Winner == $Login)
  {
    //�������� ������
    $Mx  = getdata($Opp, 'users', 'surname');
    $My  = getdata($Opp, 'users', 'name');
    $MRx = getdata($Opp, 'users', 'city');
    $MRy = getdata($Opp, 'users', 'country');

    //������� �������
  	mysql_query("delete from random where x = '".$Mx."' and y = '".$My."' and rx = '".$MRx."' and ry = '".$MRy."';");
  }
  
  //������� ������� �� ����
  $Pr = "";
  if ($Monster == 1)
  {
    //���� ����� �������
    if ($op <= 0)
      $Pr = Price($Login, $Opp);

    //������� ������� �� ����
    kickuser($Opp);
  }

  //������� ���������
  return $Pr;
}

//�����������
function Capitulation($Login, $Opp)
{
  //��������� ���� ������ � ������ ��������
  $Expa = getdata($Opp, 'hero', 'expa');
  $Exp = getdata($Opp, 'battle', 'health');
  change($Opp, 'hero', 'expa', $Expa + $Exp);

  //���������� �����
  $Finish = OffBattle($Login, $Opp);
}

//����� ���������
  //������� ���� �� ���� ����� (� ������� �� ���� �������) (max = ��������)
  $blade_need = 3; 
  //������� ���� �� ����� (�� ����������) (max = ���������)
  $magic_need = 3;
  //������� ���� �� ������ (�� ����) (max = ��������)
  $protect_need = 2;
  //������� ���� �� ������� ��������
  $hbottle_need = 12;
  //������� ���� �� �������
  $bottle_need = 2;

//��������� ������ �� ��
  //����� ���������
  $opp = getdata($lg, 'battle', 'opponent');
  //��� ����� �����
  $me = getdata($lg, 'hero', 'name');
  //��� ����� ���������
  $opponent = getdata($opp, 'hero', 'name');
  //�� ����
  $myphoto = getdata($lg, 'inf', 'fld1');
  //���� ���������
  $opphoto = getdata($opp, 'inf', 'fld1');
  //��� ���� ��������. ������?
  $opwho   = getdata($opp, 'city', 'build14');
  //������ ��� �����
  $info = "��� ����� ������ ����";
  //�� ��������
  $myhealth = getdata($lg, 'hero', 'health');
  //�������� ���������
  $ophealth = getdata($opp, 'hero', 'health');
  //������� ������ ��������
  $sec = getdata($lg, 'battle', 'timeout');
  //�������� ����
  $timeout = "�� �������� ���� �������� ".$sec." ������";
  //����� ��������
  $log = getdata($lg, 'battle', 'info');
  //��� ���� ��������
  $logfile = "data/logs/".$log.".log";
  //��� ���
  $turn = getdata($lg, 'battle', 'turn');
  //���������� � ���� ��������
  $plturn = "������ �����: <font color=darkblue><b>".getdata($turn, 'hero', 'name')."</font></b>";
  //���� ��������
  $hp = getdata($lg, 'battle', 'value');
  //���� �������� ���������
  $ophp = getdata($opp, 'battle', 'value');
  //��� �������
  $mylevel = getdata($lg, 'hero', 'level');
  //������� ���������
  $oplevel = getdata($opp, 'hero', 'level');
  //��� ������ �� �����
  $MagicProt = getdata($lg, 'abilities', 'dexterity');
  //������ �� ����� ���������
  $opMagicProt = getdata($opp, 'abilities', 'dexterity');
  //����� �������
  $LastTime = getdata($lg, 'battle', 'timeout');
  //��� ����
  $mana = getdata($lg, 'abilities', 'intellegence');
  //���� ���������
  $opmana = getdata($opp, 'abilities', 'intellegence');
  //��� ���
  $info = getdata($lg, 'battle', 'data');
  //������
  $cnow = getdata($lg, 'abilities', 'cnowledge');
  //������ ���������
  $opcnow = getdata($opp, 'abilities', 'cnowledge');
  //������������ ����
  $mynewexpa = getdata($lg, 'battle', 'health');
  //������������ ���� ����������
  $opnewexpa = getdata($opp, 'battle', 'health');
  //���������
    //��������
    (int)$HMax = getdata($lg, 'bottles', 'hmaxi');
    (int)$HMed = getdata($lg, 'bottles', 'hmedi');
    (int)$HMin = getdata($lg, 'bottles', 'hmini');
    //����
    (int)$MMax = getdata($lg, 'bottles', 'mmaxi');
    (int)$MMed = getdata($lg, 'bottles', 'mmedi');
    (int)$MMin = getdata($lg, 'bottles', 'mmini');
    //����
    (int)$PMax = getdata($lg, 'bottles', 'pmaxi');
    (int)$PMed = getdata($lg, 'bottles', 'pmedi');
    (int)$PMin = getdata($lg, 'bottles', 'pmini');
    //����������
    (int)$SMax = getdata($lg, 'bottles', 'smaxi');
    (int)$SMed = getdata($lg, 'bottles', 'smedi');
    (int)$SMin = getdata($lg, 'bottles', 'smini');
  //On-Line �� ��� ��������
  $online = getdata($opp, 'status', 'online');

//���� �������� - ������, �� ���������� ������
if ($opwho == 1)
{
  $mnstr    = getdata($opp, 'hero', 'name');
  $pht      = getfrom('name', $mnstr, 'monsters', 'art');
  $opphoto = "monsters/".$pht;
}

//���� ��� ��������, ��������� .JPG
if ($myphoto == '0')
  $myphoto = $myphoto.".jpg";
if ($opphoto == '0')
  $opphoto = $opphoto.".jpg";

//���� �� ���������� ����
if ($mana > ($cnow*10))
{
  $mana = $cnow*10;
  change($lg, 'abilities', 'intellegence', $mana);
}

//���� �� ��������� ��� ��������
if (($myhealth <= 0)||($ophealth <= 0))
{
  //���������� ��?
  $Winner = 0;

  //��������� ���� ������ � ������ ��������
  $Expa = getdata($lg, 'hero', 'expa');
  $Exp = getdata($lg, 'battle', 'health');
  if ($myhealth > 0)
  {
    //��������� ����
    change($lg, 'hero', 'expa', $Expa + $Exp);

    //����������
    $Winner = 1;

    //���� ���� ����������� ���, �� ����� ����� �����
    $Rob = Level(11, $lg);
    $Rnd = rand(100, 0);

    //���� ������� ������ �������...
    if ($Rnd < $Rob)
    {
      //���������� �����
      $Money = getdata($opp, 'economic', 'money');
      $Money = $Money - $Rnd;
      if ($Money < 0)
        $Money = 0;

      //������� ��� ������ � ���������
      change($opp, 'economic', 'money', $Money);

      $Monster = getdata($opp, 'city', 'build14');

      //�������� ��� �� ����
      if ($Monster != 1)
        sms($opp, $lg, "����� ����� ��� �������� ��� �������. �� ����� � ��� ".$Rnd." �����");

      //���������� ��� ������ ���
      $Curse = getdata($opp, 'economic', 'curse');
      if ($Curse == 0)
        $Curse = 1;
      $Rnd = $Rnd / $Curse;
      $Curse = getdata($lg, 'economic', 'curse');
      $Money = round($Rnd*$Curse);

      //���������� ��� �����
      $Our = getdata($lg, 'economic', 'money');
      change($lg, 'economic', 'money', $Our + $Money);
    }
  }
  else
  {
    $Expa = getdata($opp, 'hero', 'expa');
    $Exp = getdata($opp, 'battle', 'health');
    change($opp, 'hero', 'expa', $Expa + $Exp);
  }

  //�������� �����
  $Finish = OffBattle($lg, $opp);

  //��������� ��������
  if ($Winner == 1)
    messagebox("� ���� �������� �� ��������.<br>".$Finish, "game.php?action=1");
  else
    messagebox("� ���� �������� �� ���������.", "game.php?action=1");
}

//���� ��������� ���� ��������, �� ������� ��� ���������
if ($hp <= 0)
  TurnStep($lg, $opp);

//������� ������ ������� � 
$Delta = time() - $LastTime;

//������� �������� �� �������� ����
$CountDown = $TimeOut - $Delta;

//���� ����� ����� ����, �� ������� ��� ���������
if ($Delta >= $TimeOut)
  if ($lg == $turn)
    TurnStep($lg, $opp);
    else
    TurnStep($opp, $lg);

//�������, � ������, ���� �������� �� on-line
if (($turn == $opp)&&($online == 0))
{
  //���������� ���� ��������� � ��� �����
  $omp = getdata($opp, 'abilities', 'naturemagic');
  $obl = getdata($opp, 'abilities', 'power');

  //������� ���������� ��� ���� (����� ��� ���)
  $type = 1; //���������� ����� ���
  if ($obl > $omp)
    $type = 1; //���� �����
  else
    $type = 2; //����� �����

  //���� ��� ����������, �� ������ �� ����� ������
  $MCast = MaxiCast($opp);
  if ($MCast == 0)
    $type = 1;

  //�������, ���� �������� ����� 30% � ���� �������
    //��������� �������� ��������� � ��������
    $hprc = $ophealth/$oplevel;
  //�������� ����� 30%
  if ($hprc < 30)
  {
    //� ���� �� �������
    (int)$OHMax = getdata($opp, 'bottles', 'hmaxi');
    (int)$OHMed = getdata($opp, 'bottles', 'hmedi');
    (int)$OHMin = getdata($opp, 'bottles', 'hmini');

    //���� �� �������
    $btl = 0;
    if (($OHMax != 0)||($OHMed != 0)||($OHMin != 0))
      $btl = 1;

    //���� ���� ���� ���� �������
    if ($btl != 0)
    {
      //���� ������� ��
      if ($ophp >= 12)
      {
        //������� ��
        $ophp = $ophp - 12;
        change($opp, 'battle', 'value', $ophp);

        //�������
        $ready = 0;
        if ($OHMax != 0)
        {
          change($opp, 'hero', 'health', ($oplevel*100));
          ChangeWeight($opp, -3);
          ToLog($log, "<font color=yellow><b>".$opp."</b></font> ���������� ������� ����� �������");
          $ready = 1;
        } //������� �����
        if (($OHMed != 0)&&($ready == 0))
        {
          $ophealth = $ophealth + $oplevel*50;
          if ($ophealth > ($oplevel*100))
            $ophealth = ($oplevel*100);
          change($opp, 'hero', 'health', $ophealth);
          ChangeWeight($opp, -2);
          ToLog($log, "<font color=yellow><b>".$opp."</b></font> ���������� ������� ����� �������");
          $ready = 1;
        } //������� �����
        if (($OHMin != 0)&&($ready == 0))
        {
          $ophealth = $ophealth + $oplevel*25;
          if ($ophealth > ($oplevel*100))
            $ophealth = ($oplevel*100);
          change($opp, 'hero', 'health', $ophealth);
          ChangeWeight($opp, -1);
          ToLog($log, "<font color=yellow><b>".$opp."</b></font> ���������� ����� ����� �������");
          $ready = 1;
        } //��������� �����
      } //������� �� ��
    } //���� �� ���� ���� �������
  } // ���� ����� 30%

  //�.�. ����� ���������� ���
  $skip = 0;
  if (($btl != 0)&&($hprc < 20))
    $skip = 1;

  //���� �� �������� ��
  while (($ophp > 3)&&($skip == 0))
  {
    //������ ���� �����
    switch($type)
    {
      //����� �����
      case 1:
        $ophp = $ophp - 3;
        change($opp, 'battle', 'value', $ophp);

        //����������� �����������
        $Damage = BladeStrike($opp);

        //�������� ������ ���������
        $Protect = ShieldProtection($lg);

        //���� ������� ��������
        $Result = $Damage - $Protect;
        if ($Result < 0)
          $Result = 0;

        //��������� �������
        $Result = $Result + rand(0, 2);

        //�������� ������������� ����
        change($opp, 'battle', 'data', $info.'A');

        //��������� � ���
        if ($Result != 0)
        {
          ToLog($log, "<font color=yellow><b>".$opponent."</b></font> ���� <font color=yellow><b>".$me."</b></font> � ������� ��� ���� <font color=white><b>".$Result."</b></font>");
          $myhealth = $myhealth - $Result;
          if ($Health < 0)
            $Health = 0;
          change($lg, 'hero', 'health', $myhealth);
        }
        else
          ToLog($log, "<font color=yellow><b>".$opponent."</b></font> ������� ����� ���� <font color=yellow><b>".$me."</b></font>");
        break;
      //����� ������
      case 2:
        $ophp = $ophp - 3;
        change($opp, 'battle', 'value', $ophp);

        //������� ��� ����������
        $type = getfrom('num', $MCast, 'allcasts', 'type');

        //��������� ����� �����������
        $Damage = DoCast($opp, $MCast, $type);

        //������������� ���. ������������
        $Damage = $Damage + Level(12, $opp)*$Damage/100;

        //���������� ������ �� �����
        $Protect = getdata($lg, 'abilities', 'dexterity');

        //�������� �������� ����������
        $act = getfrom('num', $MCast, 'allcasts', 'action'); 

        //�������� ����
        $Damage = $Damage - $Protect;
        If ($Damage < 0)
          $Damage = 0;

        //���������� ���������� � �������� ��������� �������, ��� ���������
        $What = UseCast($opp, $lg, $Damage, $act);

        //�������� ������������� ����
        change($opp, 'battle', 'data', $info.'M');

        //� ������� � ��� ����
        $String = "<font color=yellow><b>".$me."</b></font> ���������� ���������� <font color=white><b>".$CastName."</b></font> � ".$What;
        ToLog($log, $String);
        break;
    } //����� ����
  } //����� ������� ���

  //�������� ����
  TurnStep($opp, $lg);

  //��������� �����
  moveto("battle.php");
}

//��������� ������� (������ ��� ������, ������� �����)
if ($turn == $lg) 
{

  //�������� �������� ������������ (���-�� ������������...)
  if ($action == 22)
  {
    Capitulation($lg, $opp);
    moveto("game.php?action=5");
  }

  //���������
  if (($action >= 10)&&($action <= 21))
  {
    //��� �� �������
    switch($action)
    {
      //�������� ��������
      case 10:
        if (($HMax > 0)&&($hp >= $hbottle_need))
        {
          //������ �������
          $HMax--;
          change($lg, 'bottles', 'hmaxi', $HMax);
          ChangeWeight($lg, -3);

          //�������� ��������
          change($lg, 'hero', 'health', $mylevel*100);          

          //������ ���� ��������
          $hp = $hp - $hbottle_need;
          change($lg, 'battle', 'value', $hp);

          //� ���
          ToLog($log, "<font color=yellow><b>".$me."</b></font> ���������� ������� ����� �������");
        }
        break;
      //�������� �������
      case 11:
        if (($HMed > 0)&&($hp >= $hbottle_need))
        {
          //������ �������
          $HMed--;
          change($lg, 'bottles', 'hmedi', $HMed);
          ChangeWeight($lg, -2);

          //�������� ��������
          $myhealth = $myhealth + $mylevel*50;
          if ($myhealth > $mylevel*100)
            $myhealth = $mylevel*100;
          change($lg, 'hero', 'health', $myhealth);          

          //������ ���� ��������
          $hp = $hp - $hbottle_need;
          change($lg, 'battle', 'value', $hp);

          //� ���
          ToLog($log, "<font color=yellow><b>".$me."</b></font> ���������� ������� ����� �������");
        }
        break;
      //�������� �������
      case 12:
        if (($HMin > 0)&&($hp >= $hbottle_need))
        {
          //������ �������
          $HMin--;
          change($lg, 'bottles', 'hmini', $HMin);
          ChangeWeight($lg, -1);

          //�������� ��������
          $myhealth = $myhealth + $mylevel*25;
          if ($myhealth > $mylevel*100)
            $myhealth = $mylevel*100;
          change($lg, 'hero', 'health', $myhealth);          

          //������ ���� ��������
          $hp = $hp - $hbottle_need;
          change($lg, 'battle', 'value', $hp);

          //� ���
          ToLog($log, "<font color=yellow><b>".$me."</b></font> ���������� ����� ����� �������");
        }
        break;
      //���� ��������
      case 13:
        if (($MMax > 0)&&($hp >= $bottle_need))
        {
          //������ �������
          $MMax--;
          change($lg, 'bottles', 'mmaxi', $MMax);
          ChangeWeight($lg, -3);

          //�������� ��������
          change($lg, 'abilities', 'intellegence', $cnow*10);          

          //������ ���� ��������
          $hp = $hp - $bottle_need;
          change($lg, 'battle', 'value', $hp);

          //� ���
          ToLog($log, "<font color=yellow><b>".$me."</b></font> ���������� ������� ����� ����");
        }
        break;
      //���� �������
      case 14:
        if (($MMed > 0)&&($hp >= $bottle_need))
        {
          //������ �������
          $MMed--;
          change($lg, 'bottles', 'mmedi', $MMed);
          ChangeWeight($lg, -2);

          //�������� ����
          $mana = $mana + $cnow*5;
          if ($mana > ($cnow*10))
            $mana = $cnow*10;
          change($lg, 'abilities', 'intellegence', $mana);          

          //������ ���� ��������
          $hp = $hp - $bottle_need;
          change($lg, 'battle', 'value', $hp);

          //� ���
          ToLog($log, "<font color=yellow><b>".$me."</b></font> ���������� ������� ����� ����");
        }
        break;
      //���� �������
      case 15:
        if (($MMin > 0)&&($hp >= $bottle_need))
        {
          //������ �������
          $MMin--;
          change($lg, 'bottles', 'mmini', $MMin);
          ChangeWeight($lg, -1);

          //�������� ����
          $mana = $mana + round($cnow*2.5);
          if ($mana > ($cnow*10))
            $mana = $cnow*10;
          change($lg, 'abilities', 'intellegence', $mana);          

          //������ ���� ��������
          $hp = $hp - $bottle_need;
          change($lg, 'battle', 'value', $hp);

          //� ���
          ToLog($log, "<font color=yellow><b>".$me."</b></font> ���������� ����� ����� ����");
        }
        break;
      //���� �������
      case 16:
        if (($PMax > 0)&&($hp >= $bottle_need))
        {
          //������ �������
          $PMax--;
          change($lg, 'bottles', 'pmaxi', $PMax);
          ChangeWeight($lg, -3);

          //�����, ��� ������ ������� ���������
          change($lg, 'items', 'vrukah', '1');          

          //������ ���� ��������
          $hp = $hp - $bottle_need;
          change($lg, 'battle', 'value', $hp);

          //� ���
          ToLog($log, "<font color=yellow><b>".$me."</b></font> ���������� ������� ����� ���� � ����������� ���� ����� �� 100%");
        }
        break;
      //���� �������
      case 17:
        if (($PMed > 0)&&($hp >= $bottle_need))
        {
          //������ �������
          $PMed--;
          change($lg, 'bottles', 'pmedi', $PMed);
          ChangeWeight($lg, -2);

          //�����, ��� ������ ������� ���������
          change($lg, 'items', 'vrukah', '2');          

          //������ ���� ��������
          $hp = $hp - $bottle_need;
          change($lg, 'battle', 'value', $hp);

          //� ���
          ToLog($log, "<font color=yellow><b>".$me."</b></font> ���������� ������� ����� ���� � ����������� ���� ����� �� 50%");
        }
        break;
      //���� �������
      case 18:
        if (($PMin > 0)&&($hp >= $bottle_need))
        {
          //������ �������
          $PMin--;
          change($lg, 'bottles', 'pmini', $PMin);
          ChangeWeight($lg, -1);

          //�����, ��� ������ ������� ���������
          change($lg, 'items', 'vrukah', '3');          

          //������ ���� ��������
          $hp = $hp - $bottle_need;
          change($lg, 'battle', 'value', $hp);

          //� ���
          ToLog($log, "<font color=yellow><b>".$me."</b></font> ���������� ����� ����� ���� � ����������� ���� ����� �� 25%");
        }
        break;
      //����� �������
      case 19:
        if (($SMax > 0)&&($hp >= $bottle_need))
        {
          //������ �������
          $SMax--;
          change($lg, 'bottles', 'smaxi', $SMax);
          ChangeWeight($lg, -3);

          //�����, ��� ������ ������� ���������
          change($lg, 'abilities', 'magicpower', '1');          

          //������ ���� ��������
          $hp = $hp - $bottle_need;
          change($lg, 'battle', 'value', $hp);

          //� ���
          ToLog($log, "<font color=yellow><b>".$me."</b></font> ���������� ������� ����� ���������� ���� � ����������� ���� ���������� ����� �� 100%");
        }
        break;
      //����� �������
      case 20:
        if (($SMed > 0)&&($hp >= $bottle_need))
        {
          //������ �������
          $SMed--;
          change($lg, 'bottles', 'smedi', $SMed);
          ChangeWeight($lg, -2);

          //�����, ��� ������ ������� ���������
          change($lg, 'abilities', 'magicpower', '2');          

          //������ ���� ��������
          $hp = $hp - $bottle_need;
          change($lg, 'battle', 'value', $hp);

          //� ���
          ToLog($log, "<font color=yellow><b>".$me."</b></font> ���������� ������� ����� ���������� ���� � ����������� ���� ���������� ����� �� 50%");
        }
        break;
      //����� �������
      case 21:
        if (($SMin > 0)&&($hp >= $bottle_need))
        {
          //������ �������
          $SMin--;
          change($lg, 'bottles', 'smini', $SMin);
          ChangeWeight($lg, -1);

          //�����, ��� ������ ������� ���������
          change($lg, 'abilities', 'magicpower', '3');          

          //������ ���� ��������
          $hp = $hp - $bottle_need;
          change($lg, 'battle', 'value', $hp);

          //� ���
          ToLog($log, "<font color=yellow><b>".$me."</b></font> ���������� ����� ����� ���������� ���� � ����������� ���� ���������� ����� �� 25%");
        }
        break;
    }

    //��������� �����
    moveto("battle.php");
  }

	//�������� �������� ������������ (���� ��� ������������� �����)
  if (($action >= 1)&&($action <= 6)&&($hp >= $magic_need))
  {
    //������� ����������, ��� ��� �� ���������� (��� �����)
    $num = getdata($lg, 'magic', 'cast' + $action);

    //� ���� �� � ��� ����� ����������
    if ($num != 0)
    {
      //�������� ���� ����������
      $cena = getfrom('num', $num, 'allcasts', 'cena'); 

      //� ���� �������
      if ($mana >= $cena)
      {
        //�������� ��� ����������
        $type = getfrom('num', $num, 'allcasts', 'type'); 

        //�������� �������� ����������
        $act = getfrom('num', $num, 'allcasts', 'action'); 

        //�������� �������� ����������
        $CastName = getfrom('num', $num, 'allcasts', 'name'); 

        //��������� ����������
        $Damage = DoCast($lg, $num, $type);

        //������������� ���. ������������
        $Damage = $Damage + Level(12, $lg)*$Damage/100;

        //���������� ������ �� �����
        $Protect = getdata($opp, 'abilities', 'dexterity');

        //�������� ����
        $Damage = $Damage - $Protect;
        If ($Damage < 0)
          $Damage = 0;

        //����������� ���� �� ������ ��� ������...
/*        (int)$Num = getdata($lg, 'items', 'plash');

        //�������������� ����
        $Procent = getfrom('num', $Num, 'allitems', 'effect');
        $Type    = getfrom('num', $Num, 'allitems', 'action');
        if ($Type != 5)
          $Procent = 0;

        //����������� ���� �� ������ ��� ������...
        (int)$Num = getdata($lg, 'items', 'shea');

        //�������������� ����
        $Procent2 = getfrom('num', $Num, 'allitems', 'effect');
        $Type    = getfrom('num', $Num, 'allitems', 'action');
        if ($Type != 5)
          $Procent2 = 0;
   
        //�������� � ������
        $Damage = $Damage + round($Damage*$Procent/100) + round($Damage*$Procent2/100); */
      
        //���������� ���������� � �������� ��������� �������, ��� ���������
        $What = UseCast($lg, $opp, $Damage, $act);

        //���� ��������� ���������
        if ($What == "���������")
        {
          ToLog($log, "<b><font color=yellow>".$opp."</b></font> �������� ���������� � ������������� �� ���������� <b><font color=yellow>".$me."</font></b>");
        }
        else
        {
          //� ���
          $String = "<font color=yellow><b>".$me."</b></font> ���������� ���������� <font color=white><b>".$CastName."</b></font> � ".$What;
          ToLog($log, $String);
        }

        //�������� ����
        change($lg, 'abilities', 'intellegence', $mana-$cena);

        //�������� ��
        $NewHP = $hp - $magic_need;
        if ($NewHP < 0)
          $NewHP = 0;
        change($lg, 'battle', 'value', $NewHP);
        
        //�������� ������������� ����������
        change($lg, 'battle', 'data', $info.'M');

        //��������� ��������
        moveto("battle.php");
      } // ������� �� ����
      else
      {
        ToLog($log, "� <font color=yellow><b>".$me."</b></font> �� ������� ���������� �������");
        moveto("battle.php");
      } // �� �������
    } // ���� �� ����
  } // ����

  //�������� ��� ���� (���� 1, �� ��� �����)
  $item = 0;
  (int)$Num = getdata($lg, 'items', 'rightruka');
  $itype = getfrom('num', $Num, 'allitems', 'type');
  if ($itype == 11)
    $item = 1;

	//�������� �������� ������������ (���� ��� ����� �������)
  if (($action == 7)&&($hp >= $blade_need)&&($item == 1))
  {
    //�������� ��������� �����������
    $Damage = PosohStrike($lg);

    //�������� ������ ���������
    $Protect = getdata($opp, 'abilities', 'dexterity');

    //���� ������� ��������
    $Result = $Damage - $Protect;
    if ($Result < 0)
      $Result = 0;

    //��������� �������
    $Result = $Result + rand(0, 2);

    //�������� �������� ���������� �� ������ ������
    $cast = CastName($Num);

    //������� � ���
    if ($Result != 0)
       ToLog($log, "<font color=yellow><b>".$me."</b></font> ���������� ����� ������ <font color=yellow><b>".$opponent."</b></font> � ��� ����� �������� ���������� ".$cast.", ������� ������� ��������� ���� <font color=white><b>".$Result."</b></font>");
    else
       ToLog($log, "<font color=yellow><b>".$me."</b></font> ������� ����� ���� <font color=yellow><b>".$opponent."</b></font>");

    //�������� ��������
    if ($Result > 0)
    {
      $NewHealth = $ophealth - $Result;
      if ($NewHealth < 0)
        $NewHealth = 0;
      change($opp, 'hero', 'health', $NewHealth);
    }

    //�������� ��
    $NewHP = $hp - $blade_need;
    if ($NewHP < 0)
      $NewHP = 0;
    change($lg, 'battle', 'value', $NewHP);

    //�������� ������������� ����
    change($lg, 'battle', 'data', $info.'A');

    //��������� �����
    moveto("battle.php");
  }

	//�������� �������� ������������ (���� ��� ����� �����)
  if (($action == 7)&&($hp >= $blade_need)&&($item != 1))
  {
    //�������� ��������� �����������
    $Damage = BladeStrike($lg);

    //�������� ������ ���������
    $Protect = ShieldProtection($opp);

    //���� ������� ��������
    $Result = $Damage - $Protect;
    if ($Result < 0)
      $Result = 0;

    //��������� �������
    $Result = $Result + rand(0, 2);

    //��������
    $Interval = rand(100, 1);
    $Dxt = Level(2, $opp);
    if ($Interval < $Dxt)
    {
      $Result = 0;
      ToLog($log, "<font color=yellow><b>".$opponent."</b></font> ������������� �� ����� <font color=yellow><b>".$me."</b></font>");
    }
    else //� ��� ����
    {
      if ($Result != 0)
        ToLog($log, "<font color=yellow><b>".$me."</b></font> ���� <font color=yellow><b>".$opponent."</b></font> � ������� ��� ���� <font color=white><b>".$Result."</b></font>");
      else
        ToLog($log, "<font color=yellow><b>".$me."</b></font> ������� ����� ���� <font color=yellow><b>".$opponent."</b></font>");
    }

    //�������������� �����������
      //���������� ������
      $MagicWeapon = getdata($lg, 'abilities', 'naturemagic')*Level(14, $lg)/100;
      //�������������� ������
      $ManaWeapon = getdata($lg, 'abilities', 'naturemagic')*Level(15, $lg)/100;
  
    //���������� ������
    if (($MagicWeapon != 0)&&($Result != 0))
    {
      $MagicWeapon = round($MagicWeapon);
      ToLog($log, "<font color=yellow><b>".$me."</b></font> �������� ������������ ���������� ������ � ������ �������� ����� <font color=yellow><b>".$opponent."</b></font> �������� �������� ��� � ������� ".$MagicWeapon." �����������");
      $Result = $Result + $MagicWeapon;
    }   

    //���������� ������
    if ($ManaWeapon != 0)
    {
      ToLog($log, "<font color=yellow><b>".$me."</b></font> �������� ������������ �������������� ������ � ������ �������� ����� <font color=yellow><b>".$opponent."</b></font> ������ ".$ManaWeapon." ����� ����");

      //�������� ����
      $opmana = $opmana - $ManaWeapon;
      if ($opmana < 0)
        $opmana = 0;
      change($opp, 'abilities', 'intellegence', $opmana);
    }   

    //�������� ��������
    if ($Result > 0)
    {
      $NewHealth = $ophealth - $Result;
      if ($NewHealth < 0)
        $NewHealth = 0;
      change($opp, 'hero', 'health', $NewHealth);
    }

    //�������� ��
    $NewHP = $hp - $blade_need;
    if ($NewHP < 0)
      $NewHP = 0;
    change($lg, 'battle', 'value', $NewHP);

    //�������� ������������� ����
    change($lg, 'battle', 'data', $info.'A');

    //��������� �����
    moveto("battle.php");
  }

	//�������� �������� ������������ (���� ��� ������)
  if (($action == 8)&&($hp >= $protect_need))
  {
    //�������� ��
    $NewHP = $hp - $protect_need;
    if ($NewHP < 0)
      $NewHP = 0;
    change($lg, 'battle', 'value', $NewHP);

    //��������� ������
    change($lg, 'battle', 'attack', '1');

    //� ���
    ToLog($log, "<font color=yellow><b>".$me."</b></font> ��������� ���� ������");
  
    //�������� ������������� ����
    change($lg, 'battle', 'data', $info.'D');

    //��������� ��������
    moveto("battle.php");
  }

	//�������� �������� ������������ (���� ��� �������� ����)
  if ($action == 9)
    TurnStep($lg, $opp);

} //���� ����� ��������� � ������� �������� ������

//� ������� ����. ��������
if ($myhealth > ($mylevel*100))
{
  $myhealth = $mylevel*100;
  change($lg, 'hero', 'health', $myhealth);
}
if ($ophealth > ($oplevel*100))
{
  $ophealth = $oplevel*100;
  change($opp, 'hero', 'health', $opphealth);
}
if ($mana > ($cnow*10))
{
  $mana = $cnow*10;
  change($lg, 'abilities', 'intellegence', $mana);
}
if ($opmana > ($opcnow*10))
{
  $opmana = $opcnow*10;
  change($opp, 'abilities', 'intellegence', $opmana);
}

//������ � ���� �������
?>
  <center>
  <table border=1 width=95% CELLSPACING=0 CELLPADDING=0>
  <?
  //��������� �������� � ��������
  $hprc = $myhealth/$mylevel;
  if ($cnow != 0)
    $mprc = (10*$mana)/$cnow;
    else
    $mprc = 0;

	//������� ����� ������� (����� �����, ����� � ��� �������� ���)
  echo("<tr><td colspan=3 align=center>�������� ����� <b>$me</b> � <b>$opponent</b><br>�� �������� ���� ��������: $CountDown ������<br>$plturn<br></td></tr>");
  echo("<tr><td align=center width=15%>$me<br>�������: $mylevel<br><img src='images/photos/$myphoto' width=150 height=200><br>��������: $myhealth<br>");
  pbar($hprc, 'red');
  echo("�����: $hp<br>����: $mana<br>");
  pbar($mprc, 'blue');
  echo("����: ".$mynewexpa."</td><td valign=top>");
  echo("<center>");
    echo("<table border=1 width=100% height=100% CELLSPACING=0 CELLPADDING=0>");
    echo("<tr><td valign=top>");
   	readfile($logfile);
    echo("</td></tr>");
    echo("<tr><td align=center>");
    ?>
      <table>
        <tr>
        <td align=center width=33%>
        <br>
        <form action='battle.php' method=post>
        <input type="hidden" name="action" value=23>
       	<input type="submit" value="       �����      " style='background:#000099; font-WEIGHT: BOLD; COLOR: white; cursor: hand; list-style: lower-alpha; filter: Alpha(Opacity=20, FinishOpacity=100, Style=3)'>
        </form>
        </td>
        <td align=center width=33%>
        <br>
        <form action='battle.php' method=post>
        <input type="hidden" name="action" value=9>
       	<input type="submit" value="�������� ���" style='background:#000099; font-WEIGHT: BOLD; COLOR: white; cursor: hand; list-style: lower-alpha; filter: Alpha(Opacity=20, FinishOpacity=100, Style=3)'>
        </form>
        </td>
        <td align=center width=33%>
        <br>
        <form action='battle.php' method=post>
        <input type="hidden" name="action" value=22>
       	<input type="submit" value="�����������" style='background:#000099; font-WEIGHT: BOLD; COLOR: white; cursor: hand; list-style: lower-alpha; filter: Alpha(Opacity=20, FinishOpacity=100, Style=3)'>
        </form>
        </td>
        </tr>
      </table>
    <?
    echo("</td></tr>");
    echo("</table>");
  echo("</center>");

  //��� ���������
  $hprc = $ophealth/$oplevel;
  if ($opcnow != 0)
    $mprc = (10*$opmana)/$opcnow;
    else
    $mprc = 0;

  //����� �������
  echo("</td>");

  //���������, �� ������ �� ��� ��������?
  $Mns = getdata($opp, 'city', 'build14');
  if ($Mns == 1)
    $opp = $opponent;

  //������� ����������
  echo("<td align=center width=15%>$opp<br>�������: $oplevel<br><img src='images/photos/$opphoto' width=150 height=200><br>��������: $ophealth<br>");
  pbar($hprc, 'red');
  echo("�����: $ophp<br>����: $opmana");
  pbar($mprc, 'blue');

  //������� ����, ���� ��� �� ������
  if ($Mns != 1)
    echo("����: ".$opnewexpa);

  //����� ������ ������
  echo("</td></tr>");

	//������� ��� ��������� ���������� (� �����, ��������)
	echo("<tr><td align=center colspan=3>");

  //�������� ��� ��������
  echo("<table border=0 CELLSPACING=0 CELLPADDING=0 width=10%><tr>");

	//������ ���������:
	  //�������� ����� ������ � ����
	  (int)$num = getdata($lg, 'items', 'rightruka');
    if ($num != 0)
    {
      $imgfile = getimg($num);
      $alt = getinfo($num);
    }
      else
    {
      $imgfile = "images/weapons/null/hand.jpg";
      $alt = "� ��� ��� ������ � �����";
    }
    //�������� ����� �������� ������
	  $s = "<img src='".$imgfile."' alt='".$alt."' width=80 height=80 border=0>";
	echo("<td align=center>��: $blade_need<br><a href='battle.php?action=7'>".$s."</a><br>�����</td>");

	//������ ��� �����
	for ($i = 1; $i <= 6; $i++)
	{
		//����� �����
		$num = getdata($lg, 'magic', 'cast' + $i);
		//����� ��������
		$cast = getfrom('num', $num, 'allcasts', 'img');

    //���� ���� ����, ������� ���
    if ($num != 0)
    {
      $cena = getfrom('num', $num, 'allcasts', 'cena'); 
  		echo("<td align=center>��: $magic_need<br><a href='battle.php?action=$i'><img src='images/cast/$cast' width=80 height=80 alt='".getcinfo($num)."' border=0></a><br>����: $cena</td>");
    }
	}

	//������ ����������:
	  //�������� ����� ������ � ����
	  (int)$num = getdata($lg, 'items', 'leftruka');
	  //�������� ����� �������� ������
	  $s = "<img src='".getimg($num)."' alt='".getinfo($num)."' width=80 height=80 border=0>";
	if ($num != 0)
    echo("<td align=center>��: $protect_need<br><a href='battle.php?action=8'>".$s."</a><br>������</td>");
  
  //����� ������� ��������
  echo("</tr></table>");

  //������ � ���������
  echo("</td></tr>");
	echo("<tr><td colspan=3 align=center>");

  //�������� ��� ���������
  echo("<table border=0 CELLSPACING=0 CELLPADDING=0 width=10%><tr>");
  //��������� �� ���������
  if ($HMax > 0)
    echo("<td align=center>��: $hbottle_need<br><a href='battle.php?action=10'><img src='images/bottles/big_h.jpg' alt='��������������� 100% ��������' width=64 height=64 border=0><br>$HMax</a></td>");
  if ($HMed > 0)
    echo("<td align=center>��: $hbottle_need<br><a href='battle.php?action=11'><img src='images/bottles/med_h.jpg' alt='��������������� 50% ��������' width=64 height=64 border=0><br>$HMed</a></td>");
  if ($HMin > 0)
    echo("<td align=center>��: $hbottle_need<br><a href='battle.php?action=12'><img src='images/bottles/sma_h.jpg' alt='��������������� 25% ��������' width=64 height=64 border=0><br>$HMin</a></td>");

  //��������� � �����
  if ($MMax > 0)
    echo("<td align=center>��: $bottle_need<br><a href='battle.php?action=13'><img src='images/bottles/big_m.jpg' alt='��������������� 100% ����' width=64 height=64 border=0><br>$MMax</a></td>");
  if ($MMed > 0)
    echo("<td align=center>��: $bottle_need<br><a href='battle.php?action=14'><img src='images/bottles/med_m.jpg' alt='��������������� 50% ����' width=64 height=64 border=0><br>$MMed</a></td>");
  if ($MMin > 0)
    echo("<td align=center>��: $bottle_need<br><a href='battle.php?action=15'><img src='images/bottles/sma_m.jpg' alt='��������������� 25% ����' width=64 height=64 border=0><br>$MMin</a></td>");

  //��������� � �����
  if ($PMax > 0)
    echo("<td align=center>��: $bottle_need<br><a href='battle.php?action=16'><img src='images/bottles/big_p.jpg' alt='����������� ���� �� 100%' width=64 height=64 border=0><br>$PMax</a></td>");
  if ($PMed > 0)
    echo("<td align=center>��: $bottle_need<br><a href='battle.php?action=17'><img src='images/bottles/med_p.jpg' alt='����������� ���� �� 50%' width=64 height=64 border=0><br>$PMed</a></td>");
  if ($PMin > 0)
    echo("<td align=center>��: $bottle_need<br><a href='battle.php?action=18'><img src='images/bottles/sma_p.jpg' alt='����������� ���� �� 25%' width=64 height=64 border=0><br>$PMin</a></td>");

  //��������� � ���������� �����
  if ($SMax > 0)
    echo("<td align=center>��: $bottle_need<br><a href='battle.php?action=19'><img src='images/bottles/big_i.jpg' alt='����������� ���������� ���� �� 100%' width=64 height=64 border=0><br>$SMax</a></td>");
  if ($SMed > 0)
    echo("<td align=center>��: $bottle_need<br><a href='battle.php?action=20'><img src='images/bottles/med_i.jpg' alt='����������� ���������� ���� �� 50%' width=64 height=64 border=0><br>$SMed</a></td>");
  if ($SMin > 0)
    echo("<td align=center>��: $bottle_need<br><a href='battle.php?action=21'><img src='images/bottles/sma_i.jpg' alt='����������� ���������� ���� �� 25%' width=64 height=64 border=0><br>$SMin</a></td>");

  //����� ������� ��� ���������
  echo("</tr></table>");

  //��������� ������� �������
  echo("</td></tr>");
	echo("</td></tr>");
  ?>
  </table>
  </center>
<?

?>