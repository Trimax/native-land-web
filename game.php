<?

//����� ���������� ��������: $action = 80
//����� CTRL+F � ��� "��������" - ����� �� �����������, ��� ����������� ����������
//����� �������? (1 - ��; 0 - ���)
$dev=1;

//��������� ��� �������
$msg_to_all = "";

//������ �������...
include "count.php";

//���� �� ������� ��� ������������, �� �������� ����� �����
$lg = trim($HTTP_COOKIE_VARS["nativeland"]);
$pw = trim($HTTP_COOKIE_VARS["password"]);

//��������� ����������� �� 100 ������
if (getdata($lg, 'hero', 'expa') > 11250000) 
	change($lg, 'hero', 'expa', 11250000);

//������������ ���� (�������� ������ - ������� ������)
if (($action == 68)&&(isadmin($lg) == 1))
{
	stabilization();
  echo("������������ ��������� �������<br>\n");
	exit();
}

//���������� 1000 �������� �� �����
if (($action == 67)&&(isadmin($lg) == 1))
{
  echo("\nAdding 1000 monsters to base<br>\n");
  for ($i = 0; $i < 1000; $i++)
    randommonster();
  echo("\n\n\n\n\n\n\n\n\nDone<br>\n\n\n\n\n\n\n\n\n");
	exit();
}

//���� ����� � ����� �������, �� �������������� ��� ����
(int)$Battle = getdata($lg, 'battles', 'battle');
if ($Battle != 0)
  moveto("fight.php");

//���� ����� � �����, �������������� ��� ����
(int)$Battle = getdata($lg, 'battle', 'battle');
if ($Battle != 0)
  moveto("battle.php");

//����� �����
if ($newbattle == 1)
{
  //���������, � �� � ����� �� �����?
  $OpBattle = getdata($beet, 'battle', 'battle');
  if (($Battle == 0)&&($OpBattle == 0))
  {
    //���� �� ������ ������
    $Yes = getdata($beet, 'inf', 'fld7');  

    //���� ��, �� �� ��
    if ($Yes == '1')
    {
      if ($lg != $beet)
      {
        BattleOn($lg, $beet);
        moveto("battle.php");
      } else
        messagebox("�� �� �� ������ ������� ������ ���� �� ��������, �����?", "game.php?action=5");
    } else
      messagebox("���� ����� �� ������� ������", "game.php?action=5");
  } else // ��� ������ �� � �����
    messagebox("���� ����� ������ ����� � �����. ��������� � ����������...", "game.php?action=5");
} // ����� �����

//������!
if ((isadmin($lg) == 1)&&($action == 66))
{
	isempty();
	exit();
}

//�������� ���������
if ((isadmin($lg) == 1)&&($action == 79))
{
	$ath = mysql_query("select * from users;");
	if ($ath)
		while ($rw = mysql_fetch_row($ath))
    {
      //������ �����
			mkdir("data/mail/".$rw[0], 0700);
    }
  moveto('game.php');
}

//��������������� �������
if ((isadmin($lg) == 1)&&($action == 78))
{
	moveto("clear.php");
	exit();
}

//�������� �������������, ������� �� ����������� �� 3-�� ������ � �.�. ������
if ((isadmin($lg) == 1)&&($action == 65))
{
  $kicked = 0;
	$ath = mysql_query("select * from users;");
	if ($ath)
	{
		while ($rw = mysql_fetch_row($ath))
		{
			//���� � �������������
			$lv = getdata($rw[0], 'hero', 'level');
      $tm = getdata($rw[0], 'inf', 'showmyinfo');
      $delta = time() - $tm;
      $delta = $delta / 3600;
      $delta = round($delta / 24);

			//�������.
			if (($lv <= 3)&&($rw[0] != $adm)&&($delta > 30))
			{
				//�������� ������������ ������ � ���, ��� ��� ��������� �������!
				$mail_to = getdata($rw[0], 'users', 'email');
				$mail_subject = "Native Land Information";
				$mail_msg = "������������. ��� ������ ���� ���������� ������������� � �������� ������� http://nativeland.spb.ru �������� �� ���� �� �����. �������� ��� � ���, ��� ��� �������� ��� ����� �� ���� � ������������ � ������� 1.� ������ ��������� ������� ����. (http://nld.spb.ru/help.php) ������� �� ��, ��� �� ������ � Native Land. � ���������, �������������.";
				mail($mail_to, $mail_subject, $mail_msg,     "To: $mail_to <$mail_to>\n" .     "From: Native_Land_Automatic_Cleaner <Native_Land_Automatic_Cleaner>\n" .$ccText.$bccText.   "X-Mailer: PHP 4.x");

				//������� ���������
        kickuser($rw[0]);
        $kicked++;

        //��� �����
        echo("�����: ".$rw[0]."<br>\n");
			}
		}
	}

  //���������� �������� +1
  $adm = getadmin();
  $btls = getfrom('admin', $adm, 'settings', 'f3');
  $btls = $btls + $kicked;
  setto('admin', $adm, 'settings', 'f3', $btls);
}

//�������
if (getdata($lg, 'hero', 'health') > getdata($lg, 'hero', 'level')*100)
{
	change($lg, 'hero', 'health', getdata($lg, 'hero', 'level')*100);
}

//���������
change ($lg, 'inf', 'fld3', time());
change ($lg, 'status', 'online', '1');

//���� �� �������
offline($lg);

//�����
if ($action == 16)
{
		$new = time();
		change($lg, 'time', 'lastexit', $new);
		change($lg, 'status', 'online', '0');
		change($lg, 'inf', 'fld7', '0');
		setcookie("nativeland");
		setcookie("password");
		echo ("<script>window.location.href('index.php');</script>");
}

//�������� �� ��������
$h = getdata($lg, 'hero', 'health');
if ($h < 0)
{
  change($lg, 'hero', 'health', '0');
}
$m = getdata($lg, 'abilities', 'intellegence');
if ($m < 0)
{
  change($lg, 'abilities', 'intellegence', '0');
}

//������ ������
if ($action == 60)
{
	change($lg, 'inf', 'fld7', '1');
	moveto('game.php?action=5');
}

//��������
if (($action == 61)&&(getdata($lg, 'battle', 'battle') == 0))
{
	change($lg, 'inf', 'fld7', '0');
	moveto('game.php?action=5');
}

//���������� ���������� �� ������
if ($action == 41)
{
	echo ("<script>window.open('info.php?name=$data', null,'toolbar=no, location=no, menubar=no,scrollbars=yes,width=656,height=480');</script>");
	moveto('game.php?action=1');
}

//������� �� ������
if (($action == 46)&&($lg != $export))
	{
	$rt = getdata($export, 'info', 'resource');
	
	if ($rt != 'metal')
		{
		$ex = getdata($export, 'inf', 'fld5');
		$cr = getdata($export, 'inf', 'fld4');
		$sum = $ex*$cr;
		$wh = getdata($lg, 'economic', 'metal');
		if ($wh > ($sum-1))
			{
			change ($lg, 'economic', 'metal', $wh-$sum);
			$wh = getdata($export, 'economic', 'metal');
			change ($export, 'economic', 'metal', $wh+$sum);
			change ($lg, 'economic', $rt, getdata($lg, 'economic', $rt)+$ex);
			change ($export, 'inf', 'fld5', 0);
		   if ($rt == 'rock') {$w = '�����';}
		   if ($rt == 'wood') {$w = '������';}
		   if ($rt == 'metal') {$w = '�������';}
		   $txt = getdata($lg, 'hero', 'name')." ����� � ��� ".$ex." ������ ".$w." �� ".$sum." ������ �������.";
		   intolog($export, 'trade', $txt);
			}
		moveto('trademark.php?login='.$lg);
		}
	}

//������� �� ������
if (($action == 47)&&($lg != $export))
	{
	$rt = getdata($export, 'info', 'resource');

	if ($rt != 'rock')
		{
		$ex = getdata($export, 'inf', 'fld5');
		$cr = getdata($export, 'inf', 'fld4');
		$sum = $ex*$cr;
		$wh = getdata($lg, 'economic', 'rock');
		if ($wh > ($sum-1))
			{
			change ($lg, 'economic', 'rock', $wh-$sum);
			$wh = getdata($export, 'economic', 'rock');
			change ($export, 'economic', 'rock', $wh+$sum);
			change ($lg, 'economic', $rt, getdata($lg, 'economic', $rt)+$ex);
			change ($export, 'inf', 'fld5', 0);
		   if ($rt == 'rock') {$w = '�����';}
		   if ($rt == 'wood') {$w = '������';}
		   if ($rt == 'metal') {$w = '�������';}
		   $txt = getdata($lg, 'hero', 'name')." ����� � ��� ".$ex." ������ ".$w." �� ".$sum." ������ �����.";
		   intolog($export, 'trade', $txt);
			}
		moveto('trademark.php?login='.$lg);
		}
	}

//������� �� ������
if (($action == 48)&&($lg != $export))
	{
	$rt = getdata($export, 'info', 'resource');

	if ($rt != 'wood')
		{
		$ex = getdata($export, 'inf', 'fld5');
		$cr = getdata($export, 'inf', 'fld4');
		$sum = $ex*$cr;
		$wh = getdata($lg, 'economic', 'wood');
		if ($wh > ($sum-1))
			{
			change ($lg, 'economic', 'wood', $wh-$sum);
			$wh = getdata($export, 'economic', 'wood');
			change ($export, 'economic', 'wood', $wh+$sum);
			change ($lg, 'economic', $rt, getdata($lg, 'economic', $rt)+$ex);
			change ($export, 'inf', 'fld5', 0);
		   if ($rt == 'rock') {$w = '�����';}
		   if ($rt == 'wood') {$w = '������';}
		   if ($rt == 'metal') {$w = '�������';}
		   $txt = getdata($lg, 'hero', 'name')." ����� � ��� ".$ex." ������ ".$w." �� ".$sum." ������ ������.";
		   intolog($export, 'trade', $txt);
			}
		moveto('trademark.php?login='.$lg);
		}
	}

//���������� ���������� �� ������
if ($action == 42)
	{
	$rt = getdata($lg, 'info', 'resource');
	$rc = getdata($lg, 'economic', $rt);
	if ($rc > 4)
		{
		$rc = $rc - 5;
		change ($lg, 'economic', $rt, $rc);
		change ($lg, 'inf', 'fld5', getdata($lg, 'inf', 'fld5')+5);
		}
		moveto('trademark.php?login='.$lg);
	}

//���������� ���������� �� ������
if ($action == 45)
	{
	$rt = getdata($lg, 'info', 'resource');
	$rc = getdata($lg, 'economic', $rt);
	if (getdata($lg, 'inf', 'fld5')  > 0)
		{
		$rc = $rc + 5;
		change ($lg, 'economic', $rt, $rc);
		change ($lg, 'inf', 'fld5', getdata($lg, 'inf', 'fld5')-5);
		}
		moveto('trademark.php?login='.$lg);
	}

//���������� ���������� �� ������
if (($action == 44)&&(getdata($lg, 'inf', 'fld4') > 0))
	{
	change ($lg, 'inf', 'fld4', getdata($lg, 'inf', 'fld4')-0.2);
		moveto('trademark.php?login='.$lg);
	}

//���������� ���������� �� ������
if ($action == 43)
	{
	change ($lg, 'inf', 'fld4', getdata($lg, 'inf', 'fld4')+0.2);
		moveto('trademark.php?login='.$lg);
	}

/* =========================================================================== */
/* ����� ��� �������� ���������� ����� ���� (����) */

/* ����� ��� �������� ���������� ����� ���� (����) */
/* =========================================================================== */

//���� �������������� �� ������������, �� �������������� �� �������������
$ch1 = getdata($lg, 'abilities', 'combatmagic');
$ch2 = getdata($lg, 'abilities', 'mindmagic');

//� ����� �� �������� levelup
$free = 0;
for ($i = 1; $i <= 16; $i++)
{
  $a = getdata($lg, 'newchar', 'achar'.$i);
  if ($a[0] != 'E')
    $free++;
}

//������ �������
if (($ch1 != 0)&&($ch2 != 0)&&($free != 0))
  moveto("levelup.php");

//��������� �� LevelUp
$level = getdata($lg, 'hero', 'level');
$how = round(60*pow($level, 1.4));
if ((getdata($lg, 'hero', 'expa') > $how)||(getdata($lg, 'hero', 'expa') == $how)&&($level != 0))
{
  //��������� ����
  change($lg, 'hero', 'upgrade', getdata($lg, 'hero', 'upgrade')+5);

  //���� ���� ��������� �����
  if ($free != 0)
  {
    //��������� ��� ��������� ��������������
    $first = newchar($lg);
    change($lg, 'abilities', 'combatmagic', $first);
    if ($free == 1)
      $second = $first;
    else
    {
      $ok = 0;
      while ($ok == 0)
      {
        $second = newchar($lg);
        if ($second != $first)
          $ok = 1;
      }
    }

    //������
    change($lg, 'abilities', 'mindmagic', $second);	
  } //free

  //��������� �������
	change ($lg, 'hero', 'level', getdata($lg, 'hero', 'level')+1);
}

//��� � �����
echo ("<html>\n<head>\n<link rel='stylesheet' type='text/css' href='style.css'/>\n<title>Native Land</title>\n</head>\n<body background='images\back.jpe'>\n");

//������
ban();

//�������...
$day = date('l');

//�������� ���� ��������
$epid = getdata($lg, 'inf', 'fld9');

//������������ ��� � ���� ������
if ($epid == 1) {$epid = 'Monday';}
if ($epid == 2) {$epid = 'Tuesday';}
if ($epid == 3) {$epid = 'Wednesday';}
if ($epid == 5) {$epid = 'Friday';}
if ($epid == 6) {$epid = 'Thursday';}
if ($epid == 6) {$epid = 'Saturday';}
if ($epid == 7) {$epid = 'Sunday';}

//���� �����������, �� ������
if ($epid == 'Monday')
{
	$epid = "You are lucky!";
}

//�������? �� �� ��! ��������!
if (($day == $epid)&&(getdata($lg, 'inf', 'fld8') != 1))
{
	//������ �� ��, ��� ��� ������
	change ($lg, 'inf', 'fld8', '1');

	//�������� ��������� �������� ��� ������...
	srand(10);
	$number = rand(1, 10);

	//�������� ������
	$eff = getfrom('num', $number, 'events', 'effect');
	$how = getfrom('num', $number, 'events', 'how');
	$nam = getfrom('num', $number, 'events', 'name');

	//�� ��!
	sms($lg, '������������ ������������ ��������', "��������! ������� ������������ ���������. � ����������� ".$nam);
	sleep(1);

	//�� � ������� ���� ����:
	if ($eff == '1')
	{
		//�������
		$k = $how/100;
		change ($lg, 'economic', 'peoples', round(getdata($lg, 'economic', 'peoples')*$k));
	}

	//��������� ��������
	if ($eff == '2')
	{
		$i = 0;
		for ($i = 0; $i < $how; $i++)
		{
			//������ how ������
			$n = rand(1,12);
			if (getdata($lg, 'city', 'build'.$n) == '1') 
				{
				sms($lg, '������������ ������������ ��������', "��������. ��������� �����. ������ ".getfrom('race', getdata($lg, 'hero', 'race'), 'buildings', 'build'.$n)." ����������");
				sleep(1);
				change ($lg, 'city', 'build'.$n, 0);
				}
		}
	}
}

//���� �����������, �� ���������� ���� ��������
if (($day == 'Monday'))
{

	//��������� ���� ��������
	$next = rand(1, 7);
	change ($lg, 'inf', 'fld9', $next);

	//�� ���� ������ ��� ��� �� ����
	change ($lg, 'inf', 'fld8', '0');
}

//����������?
if (finduser($lg, $pw) != 1)
{
	moveto('index.php');
	exit();
}

if (empty($action)) 
{
	$action = 1;
}

//�������� ��������� ��� ������������
$name = getdata($lg, 'users', 'name');


/* ����������� �������� */
$lasttime = getdata($lg, 'inf', 'fld2');
$lasttime = time() - $lasttime;
$lasttime = round($lasttime/3600);
if ($lasttime > 24)
{
  $days = round($days / 24);
  if ($days == 0)
    $days = 1;

  //����
  $Curse = getdata($lg, 'economic', 'curse');

  //������� ����� �� �����
  $level1 = getdata($lg, 'army', 'level1');
  $level2 = getdata($lg, 'army', 'level2');
  $level3 = getdata($lg, 'army', 'level3');
  $level4 = getdata($lg, 'army', 'level4');
  $Nalog = $level1 + $level2*2 + $level3*3 + $level4*4;
  $Nalog = $Nalog*$Curse*$days;
  $Economy = Level(28, $lg)*0.01;
  $Nalog = round($Nalog - $Nalog*$Economy);
  if ($Nalog < 0)
    $Nalog = 0;
  $Money = getdata($lg, 'economic', 'money');
  $Money = $Money - $Nalog;

  //����� � ��������
  $Peoples = getdata($lg, 'economic', 'peoples');
  $Peoples = round($Peoples + Level(29, $lg)*0.01*$Peoples);
  $Money = $Money + $Peoples*$days;

  //������ ������
  if ($Money < 0)
    $Money = 0;
  change($lg, 'economic', 'money', $Money);
  change($lg, 'inf', 'fld2', time());
}

/* ����������� �������� c ������� */
$lasttime = getfrom('admin', 'Settings', 'settings', 'f1');
$lasttime = time() - $lasttime;
$lasttime = round($lasttime/3600);
if ($lasttime > 24)
{
  setto('admin', 'Settings', 'settings', 'f1', time());

  //������� ����� �� ���� ������
	$ath = mysql_query("select * from clans;");
	if ($ath)
		while ($rw = mysql_fetch_row($ath))
    { 
      $bill = getfrom('name', $rw[0], 'clans', 'bill');
      $bill = $bill - 1000;
      if ($bill < 0)
        $bill = 0;
      setto('name', $rw[0], 'clans', 'bill', $bill);
    }
}

/* ==================== */

//��������� HP � ��������������� �������� � ���������� �������
$last = getdata($lg, 'time', 'lastexit');
$now = time();
$delta = $now - $last;
$result = $delta / 3600;
$num = round($result);
if ($num < 0)
{
	$num = 0;
}

//������ ��� ������� ���� ������� ����.
if ($num > 0)
{
	//������ ����� ���������� ������
	change($lg, 'time', 'lastexit', time());

	//���������� ��
	$temp = getdata($lg, 'time', 'hp');
	$lev = getdata($lg, 'hero', 'level');
	$temp = $temp + $lev*$num;
	if ($temp > $lev*10)
	{
		$temp = $lev*10;
	}
	//����������� ���������� ����� ��������
	change($lg, 'time', 'hp', $temp);

	//����������� ���������� ���� �������, ������� �������� � ������.
	change($lg, 'economic', getdata($lg, 'info', 'resource'), getdata($lg, 'economic', getdata($lg, 'info', 'resource'))+$num);

  //����� �� ���. ������������
  $mt = Level(8, $lg);
  $rc = Level(9, $lg);
  $wd = Level(10, $lg);

	//����� �� ����, ��������� � �������
	change($lg, 'economic', 'metal', $mt+getdata($lg, 'economic', 'metal')+getdata($lg, 'city', 'build18')*$num);
	change($lg, 'economic', 'rock', $rc+getdata($lg, 'economic', 'rock')+getdata($lg, 'city', 'build17')*$num);
	change($lg, 'economic', 'wood', $wd+getdata($lg, 'economic', 'wood')+getdata($lg, 'city', 'build16')*$num);

	//����������� ���������� ���� �������, ������� �������� � ������.
	if (getdata($lg, 'city', 'build4') != 0)
	{
		change($lg, 'economic', getdata($lg, 'info', 'resource'), getdata($lg, 'economic', getdata($lg, 'info', 'resource'))+2*$num);
	}

	//� ������ ���������...
	$k = $num*getdata($lg, 'economic', 'curse');
	$full = $k*(1 + getdata($lg, 'city', 'build1')+5*getdata($lg, 'city', 'build2')+30*getdata($lg, 'city', 'build3'));
	$rs =  getdata($lg, 'economic', 'money')+$full;

	//�� ���� �����
	$rs = $rs + getdata($lg, 'city', 'build19')*$k;

	//��������� �� �����
	$curse = getdata($lg, 'economic', 'curse');
	(int)$pnum = getdata($lg, 'items', 'palec');
	$add = forbattle($pnum, 4);
	$dop = $k*$add*$full;
	$rs = $rs + round($dop);
	(int)$pnum = getdata($lg, 'items', 'shea');
	$add = forbattle($pnum, 4);
	$dop = $k*$add*$full;
	$rs = $rs + round($dop);

  //� �����, � ���. �����������
  $rs = $rs + round(Level(6, $lg)*$full/100);

  //��������
  change($lg, 'economic', 'money', $rs);

	//������� �� ����� ������?
	$sum = round($k*0.5);
  if ($sum < 0)
  {
    $sum = 0;
  }

	//���� ��� �������, �� ������
	if (getdata($lg, 'city', 'build13') != 1)
	{

		//������� �����
		change($lg, 'economic', 'money', getdata($lg, 'economic', 'money')-$sum);
		if (getdata($lg, 'economic', 'money') < 0)
		{
			change($lg, 'economic', 'money', 0);
		}

		//������ �����, ���� ��� �������
		sms($lg, '������� �������� ����', "��������� �������� ����� ���� � ���! �����: ".$sum);
	} else //� ��������������� ������ ������� � �����
  {
    $Add = Level(23, $lg);
    $Lev1 = 4*$num;
    $Lev2 = 3*$num;
    $Lev3 = 2*$num;
    $Lev4 = 1*$num;
    $Add = $Add / 100;
    $Add = $Add + 1;
    $Lev1 = round($Lev1*$Add);
    $Lev2 = round($Lev2*$Add);
    $Lev3 = round($Lev3*$Add);
    $Lev4 = round($Lev4*$Add);
    
    //������� ����
    (int)$Mon1 = getdata($lg, 'unions', 'login2');
    (int)$Mon2 = getdata($lg, 'unions', 'login3');
    (int)$Mon3 = getdata($lg, 'unions', 'login4');
    (int)$Mon4 = getdata($lg, 'unions', 'login5');

    //���������
    change($lg, 'unions', 'login2', $Lev1+$Mon1);
    change($lg, 'unions', 'login3', $Lev2+$Mon2);
    change($lg, 'unions', 'login4', $Lev3+$Mon3);
    change($lg, 'unions', 'login5', $Lev4+$Mon4);
  }

	//�������������� ������� ��� �����
	//1) ���������� �� ���� �����
	$clan = getdata($lg, 'inclan', 'clan');
	if (!empty($clan))
	{
		//�������� �����, ������� ���� ���������.
    $curse = getdata($lg, 'economic', 'curse');
    $nalog = getfrom('name', $clan, 'clans', 'nalog');
		$mynalog = $nalog * $curse;
		$money = getdata($lg, 'economic', 'money');
		if ($mynalog > $money)
		{
			$mynalog = $money;
		}
		$money = $money - $mynalog;
    $nalog = round($mynalog / $curse);
		change($lg, 'economic', 'money', $money);
		$adm = getfrom('name', $clan, 'clans', 'login');
		$bill = getdata($adm, 'clans', 'bill');
	  change($adm, 'clans', 'bill', $nalog+$bill);
	}

  //�������������� ��
  $OD = getdata($lg, 'inf', 'def');
  $OD = $OD + $num;
  $max = 30 + Level(1, $lg);
  if ($OD > $max)
    $OD = $max;
  change($lg, 'inf', 'def', $OD);

	//2) ���������� ����� � ����

	//� ��������...
  $lv = getdata($lg, 'hero', 'level');
  $add = $num*$lv*20;
  
  //��������������� �������� ��-�� ���. ���.
  $add = $add + Level(5, $lg)*$lv;

  //���. �����������
	change($lg, 'hero', 'health', getdata($lg, 'hero', 'health')+$add);

  //� ����...
  $add = $num*10;

  //��������������� ���� ��-�� ���. ���.
  $add = $add + Level(7, $lg)*$lv;

  change($lg, 'abilities', 'intellegence', getdata($lg, 'abilities', 'intellegence')+$add);

  //�� �� ������, ��� �����!
	if (getdata($lg, 'abilities', 'intellegence') > getdata($lg, 'abilities', 'cnowledge')*10)
	{
	change($lg, 'abilities', 'intellegence', getdata($lg, 'abilities', 'cnowledge')*10);
	}

	//�� �� ������, ��� �����!
	if (getdata($lg, 'hero', 'health') > getdata($lg, 'hero', 'level')*100)
	{
	change($lg, 'hero', 'health', getdata($lg, 'hero', 'level')*100);
	}
}

//��������:
if (!empty($action))
{
	switch($action)
	{
  /* =========================================================================== */
  /* ����� ��� �������� ���������� ����� ���� (����) */

  /* ����� ��� �������� ���������� ����� ���� (����) */
  /* =========================================================================== */
		//�����������������
		case 17:
			if (isadmin($lg) != 1)
			{
				$action = 0;
				window.location.href("game.php");
			}
			break;

		//������� �����
		case 30:
			if (getdata($lg, 'unions', 'login2') != $lg) {sms(getdata($lg, 'unions', 'login2'), $lg, '� �������� ��� �����!');}
			if (getdata($lg, 'unions', 'login3') != $lg) {sms(getdata($lg, 'unions', 'login3'), $lg, '� �������� ��� �����!');}
			if (getdata($lg, 'unions', 'login4') != $lg) {sms(getdata($lg, 'unions', 'login4'), $lg, '� �������� ��� �����!');}
			if (getdata($lg, 'unions', 'login5') != $lg) {sms(getdata($lg, 'unions', 'login5'), $lg, '� �������� ��� �����!');}
			change ($lg, 'unions', 'login2', $u1);
			change ($lg, 'unions', 'login3', $u2);
			change ($lg, 'unions', 'login4', $u3);
			change ($lg, 'unions', 'login5', $u4);
			if (getdata($lg, 'unions', 'login2') != $lg) {sms(getdata($lg, 'unions', 'login2'), $lg, '� ��������� ��� ���!');}
			if (getdata($lg, 'unions', 'login3') != $lg) {sms(getdata($lg, 'unions', 'login3'), $lg, '� ��������� ��� ���!');}
			if (getdata($lg, 'unions', 'login4') != $lg) {sms(getdata($lg, 'unions', 'login4'), $lg, '� ��������� ��� ���!');}
			if (getdata($lg, 'unions', 'login5') != $lg) {sms(getdata($lg, 'unions', 'login5'), $lg, '� ��������� ��� ���!');}
			break;	

		//�������
		case 33:
			if (getdata($lg, 'economic', 'money') > getdata($lg, 'economic', 'curse')*getdata($lg, 'hero', 'level')*25)
				{
					//������� ������
					change($lg, 'economic', 'money', getdata($lg, 'economic', 'money')-getdata($lg, 'economic', 'curse')*getdata($lg, 'hero', 'level')*25);

					//������� ����
					$temptxt = nwe($users);
          messagebox($temptxt, "spy.php?login=".$lg);
				}
				else
					{
            messagebox("� ��� ������������ ����� ��� ��������! ��� ����, ����� ������� ������ ��� ���������� ".getdata($lg, 'economic', 'curse')*20*getdata($lg, 'hero', 'level')." ".getdata($lg, 'economic', 'moneyname')."", "spy.php?login=".$lg);
					}
			break;
		
		//���������� ������� �����
		case 35:
			//������ ���� ������� �����, ������� ����!
			if (!preg_match("/[0-9]/i", $count))
				{
          messagebox("������� ��������������� �������� ��������!", "bankomat.php?login=".$lg);
				}
				else
					{
					if ($count < 0)
						{
              messagebox("������� ��������������� �������� ��������!", "bankomat.php?login=".$lg);
						}
						else
						{
						//���� ����� ������, ��� ����
						if ($count > getdata($lg, 'economic', 'money'))
							{
							$count = getdata($lg, 'economic', 'money');
							}

						//������� �� ����� ������
						change ($lg, 'economic', 'money', getdata($lg, 'economic', 'money')-$count);

						//��������� �� � ������
						$count = round($count / getdata($lg, 'economic', 'curse'));

						//��������� � ������ �� ������� �����
						$count = $count * getdata($users, 'economic', 'curse');

						//������� 5 ��������� �� �������
						$count = round($count*0.95);

						//��������� �� ���� ������
						change ($users, 'economic', 'money', getdata($users, 'economic', 'money')+$count);

						//���������� �����������
						sms($users, '���� ������ '.getdata($lg, 'info', 'capital'), '�� ��� ���� ���������� '.$count.' '.getdata($users, 'economic', 'moneyname').'. ������� �� ���� ������� ���. �� ������� ���� ����� 5%');
						sms($lg, '���� ������ '.getdata($lg, 'info', 'capital'), '�� ������� �������� '.$count.' '.getdata($users, 'economic', 'moneyname').'. ������� �� ���� ������� ���. �� ������� ���� ����� 5%');

						//�� ��
            messagebox("������� ����������. ����������� ���������.", "bankomat.php?login=".$lg);
						}
					}
			break;

		//����� �� �����
		case 34:
      messagebox("��� ����������� �������� ���������� �.�. � ������ ������ ��� ���������� ����. �������� ���� ���������", "game.php?action=1");

			//�������� �� ���������� ���������� �����
			if (getdata($lg, 'economic', 'peoples') == 0)
			{
				messagebox("<center>� ��� ��� �����, ����� � ��������</center>", "game.php?action=15");
			}

			//0) ����� �� �������� �������
			$dlt = time() - getdata($lg, 'economic', 'nalog');
			$dlt = round($dlt/3600);

			if ($dlt < 24)
				{
					$action = 31;
					$temptxt = "<center>���� ����� ������. �� ���������� ���������. ��������� ��� �������� ".(24 - $dlt)." �����.</center>";
				} else
		{

			//0.5) � ���� � ��������� ���� ������� ���?
			if (getdata($users, 'city', 'build13') == 0)
			{
					$action = 31;
					$temptxt = "<center>��� ����������� ������ � '������� �������� ����'. ��� ����������� �������� ������� �������� ����������� '������ ������������ �����������' ���. �������� �� ���� ���� �� �����.</center>";
			} else
			{
			//1) ��������� �� �����
			if (hasunion($lg, $users) != 1)
				{
				//2) �������� � ���������
				sms($users, $lg, "��� ������� ���� ����������� �����. �����������!");

				//3) �����������, ��� �������
				$delta = getdata($lg, 'economic', 'peoples')*getdata($lg, 'hero', 'level') - getdata($users, 'economic', 'peoples')*getdata($users, 'hero', 'level');

				//3.5) ������������ ��� ������� ������
				$useunion = 0;
				//��������� ��� ������� ������, ������� ��������. 
				//���� �������� ������ ����, �� �������� �� ������� �������� �� �����
				//������ �����, �� 1/n �� ����� ����������. n - ���-�� ���������
				//���� �������� ������ ����, �� ��� ����� ����� ���� � �� ����� ���������� ��������
				//����� ���� n+1 �����. 1 - �����������. n = {1;2;3;4}
				if ($delta > 0)
				{
					$ua = uarmy($users);
					$delta = $delta - $ua;

					//������������ �� �� ����� ���������?
					if ($ua != 0)
					{
						$useunion = 1;
					
						//���������� ���������
						$n[0] = getdata($users, 'unions', 'login2');
						$n[1] = getdata($users, 'unions', 'login3');
						$n[2] = getdata($users, 'unions', 'login4');
						$n[3] = getdata($users, 'unions', 'login5');

						//��������� �� �������
						for ($i = 0; $i < 4; $i++)
							for ($j = 0; $j < 4; $j++)
							{
								if (($n[$i] == $n[$j])&&($i != $j))
								{
									$n[$i] = $users;
								}
							}

						//�������� ���������� �� ������ ���� ���������
						for ($i = 0; $i < 4; $i++)
						{
							$aru[$i] = getdata($n[$i], 'economic', 'peoples');							
						}
			
						//������� � ����� ������� �� ��������� � ����� ����.
						for ($i = 0; $i < 4; $i++)
						{
							//���� ����� �������� ����� �����, �� ������
							if ($n[$i] != $users)
							{
								//� ��������� �� �� �� ������������������ ����� � ���� �������
								for ($k = 0; $k < 4; $k++)
								{
									//��������� ��� �������-���� � ������
									$uar[$i][$k] = getdata($n[$i], 'unions', 'login'.($k+2));
								}
							}
						}

						//����, �� ������� � ���� ��������� ���������� ��� ���� �������, ������� � ��� � �����.
						//��������� �� ����������
						for ($i = 0; $i < 4; $i++)
						{
							for ($j = 0; $j < 4; $j++)
							{
								for ($k = 0; $k < 4; $k++)
								{
									//���� ��, �� ��������� �������
									if (($uar[$i][$j] == $uar[$i][$k])&&($j != $k))
									{
										//���� ������, �� �������� � ������ ��� ��������
										$uar[$i][$j] = $n[$i];
									} //�������
								} //$k
							} //$j
						} //$i

					} //����� $ua == 0
				} //����� $delta == 0

				//4) �� �...
				$result = 0; //(1 - ������, 2 - ���������) ��� �����������
				if ($delta > 0)
					{
						$result = 1;
						change ($lg, 'economic', 'peoples', round($delta/getdata($lg, 'hero', 'level')));
						change ($users, 'economic', 'peoples', 0);

						//������ �������
						change ($lg, 'economic', 'metal', getdata($lg, 'economic', 'metal') + round(0.4*getdata($users, 'economic', 'metal')));
						change ($lg, 'economic', 'rock', getdata($lg, 'economic', 'rock') + round(0.4*getdata($users, 'economic', 'rock')));
						change ($lg, 'economic', 'wood', getdata($lg, 'economic', 'wood') + round(0.4*getdata($users, 'economic', 'wood')));
						$temptxt = "<center><h2>���������� ����� ����� ".getdata($users, 'info', 'capital')."</h2>";
						$temptxt = $temptxt."<br>";
						$temptxt = $temptxt."<table border=1 width=40% CELLSPACING=0 CELLPADDING=0>";
						$temptxt = $temptxt."<tr><td align=center width=40%>������������</td><td align=center>�������</td></tr>";
						$temptxt = $temptxt."<tr><td align=center>������</td><td align=center>".round(0.6*getdata($users, 'economic', 'metal'))."</td>";
						$temptxt = $temptxt."<tr><td align=center>������</td><td align=center>".round(0.6*getdata($users, 'economic', 'rock'))."</td>";
						$temptxt = $temptxt."<tr><td align=center>������</td><td align=center>".round(0.6*getdata($users, 'economic', 'wood'))."</td>";
						$temptxt = $temptxt."</table>";
						$temptxt = $temptxt."</center>";
						change ($users, 'economic', 'metal', round(0.6*getdata($users, 'economic', 'metal')));
						change ($users, 'economic', 'rock', round(0.6*getdata($users, 'economic', 'rock')));
						change ($users, 'economic', 'wood', round(0.6*getdata($users, 'economic', 'wood')));
						sms($users, $lg, '� �� ������, ��� �� ��� �������. �� �� ��! � ������� ��� ���� �������!');

						//���� ������������ ����� ���������, �� � � ���������� �� 0
						if ($useunion == 1)
						{
							$tmp = getdata($users, 'hero', 'name');
							$castle = getdata($users, 'info', 'capital');
							for ($k = 0; $k < 4; $k++)
							{
								//���� �� ����� � �������� � �����
								for ($l = 0; $l < 4; $l++)
								{
									if ($uar[$k][$l] == $users)
									{
										change($n[$k], 'economic', 'peoples', 0);
										sms($n[$k], '������� ���� ������ '.$tmp, '�� �������� �������� ���� ����� ������������ � ������� ����� '.$castle.'. ������� ����������� ��������. ��� ������� ����� ��������� ���������.');
									}
								} //$l
							} //$k
						} //$userunion
					} 
					else //�������� ��� ����� (������� �������������)
					{
						//���� ������������ ����� ���������, �� �������� �� ������ �������
						if ($useunion == 1)
						{
							//�������� ���� ���������, � ������� ����� �� ����. ��������� �� � ��������� ������� �
							//�� ������� �������� �����
							//�������, �����������, � ������ ��������������

							//������� �� ������� ������ ������
							$count = 0;
							for($i = 0; $i < 4; $i++)
							{
								$flag = 0;

								//���� � �������� ���� �����, ����� ����� ���������
								if ($aru[$i] != 0)
								{
									//��������� ��� 4 �����
									for ($j = 0; $j < 4; $j++)
									{
										//���� �� ������ � ����� �����
										if (($uar[$i][$j] == $users)&&($n[$i] != $users))
										{
											$flag = 1;
										}//$uar
									} //$j

									//���� �� ������ 1 ��� ��������
									if ($flag == 1)
									{
										$count++;
									} //$flag
								} //$aru
							} //$i

							//������ ������ �������������
							$delta = abs($delta);

							//���������� ���� �� ���������
							$part = $delta / $count;

							//������ ������
							$tmp = getdata($users, 'hero', 'name');
							$castle = getdata($users, 'info', 'capital');

							//������ �� ������� �������� � ������������� ������ �������� �����, ���� ��� � ���� �� ����
							for ($i = 0; $i < 4; $i++)
							{
								//���� ����� �� ����
								if ($aru[$i] != 0)
								{
									//����������, ���� �� �������
									$flag = 0;
									for ($j = 0; $j < 4; $j++)
									{
										//��� ���� ��� ���
										if ($uar[$i][$j] == $users)
										{
											$flag = 1;
										} //$uar
									} //$j
									
									//���� ���� ���� (�������� �����. �.�. ���������� ������ ���-��. �� � ����� ������)
									$aru[$i] = round($part / getdata($n[$i], 'hero', 'level'));

									//���� ����� ������ ����, �� ������ ����
									//��� � ����-�� � ����������� ������������ ������. ����� ������ ������.
									if ($aru[$i] < 0)
									{
										$aru[$i] = 0;
									} //$aru

									//����� �� �� ����� ������ ����� �����
									$can = 0;
									for ($j = 0; $j < 4; $j++)
									{
										if (($uar[$i][$j] == $users)&&($can == 0))
										{
											$can = 1;
										}
									}

									//��������� ������� � ������������� ����� �������� ����� ��������
									if (($n[$i] != $lg)&&($n[$i] != $users)&&($can == 1))
									{
										//������������� ����� �������� ����� ��������
										if ($aru[$i] < getdata($n[$i], 'economic', 'peoples'))
										{
											change($n[$i], 'economic', 'peoples', $aru[$i]);
										}

										sms($n[$i], '������� ���� ������ '.$tmp, '�� �������� �������� ���� ����� ������������ � ������� ����� '.$castle.'. ������� ����������� �������. ���� ����� ������� �������� �����. �, '.$tmp.', ������� ��� �������� �������������.');
									} //$lg
								} //$aru
							} //$i

							//����������� ������ - ������ ��!
							change ($users, 'economic', 'peoples', 0);

							//������ ����������� ����
							change ($lg, 'economic', 'peoples', 0);

							//������ ��������� (�� 1/5 ������� ��������) //��������
							$vl = getdata ($lg, 'economic', 'money') / getdata ($lg, 'economic', 'curse');
							change ($lg, 'economic', 'money', 0);
							$vl = $vl * getdata ($users, 'economic', 'curse');
							change ($users, 'economic', 'money', getdata($users, 'economic', 'money') + $vl);

							//�������� ���������							
							sms($users, $lg, '� ���! � ������. ������� ���� ��������� �� ����� ���������. �������, ��� �� �� ������ ���� �������� �� ����, ���� ��� ��� ����� ���� ��� ������� ������ �����. � ���� ����, ��� ��� ����� ������ � ������� ��� ������ ����� ����������� ���. �������� �� �� ����� � ���� ������. �����: '.$vl.' '.getdata($users, "economic", "moneyname").'. � ���������, '.getdata($lg, 'hero', 'name'));
						}
						else //����� ��������� �� ������������
						{
							change ($users, 'economic', 'peoples', round(abs($delta/getdata($users, 'hero', 'level'))));
							change ($lg, 'economic', 'peoples', 0);
							$vl = getdata ($lg, 'economic', 'money') / getdata ($lg, 'economic', 'curse');
							change ($lg, 'economic', 'money', 0);
							$vl = $vl * getdata ($users, 'economic', 'curse');
							change ($users, 'economic', 'money', getdata($users, 'economic', 'money') + $vl);
							sms($users, $lg, '� ���! � ������. ������� ���� ��������� �� ����� ���������. �������, ��� �� �� ������ ���� �������� �� ����, ���� ��� ��� ����� ���� ��� ������� ������ �����. � ���� ����, ��� ��� ����� ������ � ������� ��� ������ ����� ����������� ���. �������� �� �� ����� � ���� ������. �����: '.$vl.' '.getdata($users, "economic", "moneyname").'. � ���������, '.getdata($lg, 'hero', 'name'));
						}
					}

				//5) �������� �����
				$action = 31;
				change($lg, 'economic', 'nalog', time());
				}
				else
					{
					$action = 31;
					$temptxt = "<center>�� �� ������ ������� �� ������ ��������</center>";
					}
			}
		}
			break;

		//����� �����
		case 36:

			//��������� ����
			$err = 0;
			if (!empty($hyperhow))
				{

				if (!preg_match("/[0-9]/i", $hyperhow))
					{
					$err = 1;
					} else
						{
						if (($hyperhow < 0)||($hyperhow == 0))
							{
							$err = 1;
							}
						}
				}

			//�� ���������?
			if ($err != 0)
				{
					$action = 29;
				} else
					{
						//������� �����
						$sum = $hyperhow*getdata($lg, 'economic', 'curse')*5;

						//����� ��� ���������?
						if ($sum > getdata($lg, 'economic', 'money'))
							{
							$action = 31;
							$temptxt = "<center>� ��� ������������ ����� ��� ����� �����. ��� ����������: ".$sum." ".getdata($lg, 'economic', 'moneyname'."</center>");
							} else
								{
									//�������� ������
									change($lg, 'economic', 'money', getdata($lg, 'economic', 'money')-$sum);

									//��������� �����
									change($lg, 'economic', 'peoples', getdata($lg, 'economic', 'peoples')+$hyperhow);
			
									//������� ���������
									$action = 31;
									$temptxt = "<center>�� ������� ������ ".$hyperhow." ������ � ���� ����� �� ".$sum. " ".getdata($lg, 'economic', 'moneyname')."</center>";
								}
					}
			break;
	}
}

//��������
if ((($action > 19)&&($action < 29))||(($action > 68)&&($action < 78)))
{
	if (getdata($lg, 'hero', 'upgrade') == 0)
	{
		$action = 1;
	}
	else
	{
		switch($action)
		{
			case 20:
				$up = getdata($lg, 'hero', 'upgrade');
				$up--;
				change($lg, 'hero', 'upgrade', $up);
				$nw = getdata($lg, 'abilities', 'power');
				$nw++;
				change($lg, 'abilities', 'power', $nw);
				$action = 1;
				break;
			case 69:
				$up = getdata($lg, 'hero', 'upgrade');
				change($lg, 'hero', 'upgrade', 0);
				$nw = getdata($lg, 'abilities', 'power');
				$nw=$nw+$up;
				change($lg, 'abilities', 'power', $nw);
				$action = 1;
				break;
			case 21:
				$up = getdata($lg, 'hero', 'upgrade');
				$up--;
				change($lg, 'hero', 'upgrade', $up);
				$nw = getdata($lg, 'abilities', 'protect');
				$nw++;
				change($lg, 'abilities', 'protect', $nw);
				$action = 1;
				break;
			case 70:
				$up = getdata($lg, 'hero', 'upgrade');
				change($lg, 'hero', 'upgrade', 0);
				$nw = getdata($lg, 'abilities', 'protect');
				$nw=$nw+$up;
				change($lg, 'abilities', 'protect', $nw);
				$action = 1;
				break;
			case 22:
				$up = getdata($lg, 'hero', 'upgrade');
				$up--;
				change($lg, 'hero', 'upgrade', $up);
				$nw = getdata($lg, 'abilities', 'dexterity');
				$nw++;
				change($lg, 'abilities', 'dexterity', $nw);
				$action = 1;
				break;
			case 71:
				$up = getdata($lg, 'hero', 'upgrade');
				change($lg, 'hero', 'upgrade', 0);
				$nw = getdata($lg, 'abilities', 'dexterity');
				$nw=$nw+$up;
				change($lg, 'abilities', 'dexterity', $nw);
				$action = 1;
				break;
			case 23:
				$up = getdata($lg, 'hero', 'upgrade');
				$up--;
				change($lg, 'hero', 'upgrade', $up);
				$nw = getdata($lg, 'abilities', 'cnowledge');
				$nw++;
				change($lg, 'abilities', 'cnowledge', $nw);
				$action = 1;
				break;
			case 72:
				$up = getdata($lg, 'hero', 'upgrade');
				change($lg, 'hero', 'upgrade', 0);
				$nw = getdata($lg, 'abilities', 'cnowledge');
				$nw=$nw+$up;
				change($lg, 'abilities', 'cnowledge', $nw);
				$action = 1;
				break;
			case 24:
				$up = getdata($lg, 'hero', 'upgrade');
				$up--;
				change($lg, 'hero', 'upgrade', $up);
				$nw = getdata($lg, 'abilities', 'charism');
				$nw++;
				change($lg, 'abilities', 'charism', $nw);
				$action = 1;
				break;
			case 73:
				$up = getdata($lg, 'hero', 'upgrade');
				change($lg, 'hero', 'upgrade', 0);
				$nw = getdata($lg, 'abilities', 'charism');
				$nw=$nw+$up;
				change($lg, 'abilities', 'charism', $nw);
				$action = 1;
				break;
			case 25:
				$up = getdata($lg, 'hero', 'upgrade');
				$up--;
				change($lg, 'hero', 'upgrade', $up);
				$nw = getdata($lg, 'abilities', 'intellegence');
				$nw++;
				change($lg, 'abilities', 'intellegence', $nw);
				$action = 1;
				break;
			case 74:
				$up = getdata($lg, 'hero', 'upgrade');
				change($lg, 'hero', 'upgrade', 0);
				$nw = getdata($lg, 'abilities', 'intellegence');
				$nw=$nw+$up;
				change($lg, 'abilities', 'intellegence', $nw);
				$action = 1;
				break;
			case 26:
				$up = getdata($lg, 'hero', 'upgrade');
				$up--;
				change($lg, 'hero', 'upgrade', $up);
				$nw = getdata($lg, 'abilities', 'combatmagic');
				$nw++;
				change($lg, 'abilities', 'combatmagic', $nw);
				$action = 1;
				break;
			case 75:
				$up = getdata($lg, 'hero', 'upgrade');
				change($lg, 'hero', 'upgrade', 0);
				$nw = getdata($lg, 'abilities', 'combatmagic');
				$nw=$nw+$up;
				change($lg, 'abilities', 'combatmagic', $nw);
				$action = 1;
				break;
			case 27:
				$up = getdata($lg, 'hero', 'upgrade');
				$up--;
				change($lg, 'hero', 'upgrade', $up);
				$nw = getdata($lg, 'abilities', 'naturemagic');
				$nw++;
				change($lg, 'abilities', 'naturemagic', $nw);
				$action = 1;
				break;
			case 76:
				$up = getdata($lg, 'hero', 'upgrade');
				change($lg, 'hero', 'upgrade', 0);
				$nw = getdata($lg, 'abilities', 'naturemagic');
				$nw=$nw+$up;
				change($lg, 'abilities', 'naturemagic', $nw);
				$action = 1;
				break;
			case 28:
				$up = getdata($lg, 'hero', 'upgrade');
				$up--;
				change($lg, 'hero', 'upgrade', $up);
				$nw = getdata($lg, 'abilities', 'mindmagic');
				$nw++;
				change($lg, 'abilities', 'mindmagic', $nw);
				$action = 1;
				break;
			case 77:
				$up = getdata($lg, 'hero', 'upgrade');
				change($lg, 'hero', 'upgrade', 0);
				$nw = getdata($lg, 'abilities', 'mindmagic');
				$nw=$nw+$up;
				change($lg, 'abilities', 'mindmagic', $nw);
				$action = 1;
				break;
		}
	$action = 1;
	echo("<script>window.location.href('game.php?action=1');</script>");
	}
}

//������ �������
echo ("<table border=$dev width=100% CELLSPACING=0 CELLPADDING=0>\n");
echo ("<tr><td colspan=3 align=center><font color=yellow size=6><img src='images/logo.gif' width=640 height=60></font></td></tr>\n");

//���������� ���� ������������
showmenu($lg, $name);
echo("<td colspan=2>");

//���������� ����
if (!empty($userlogin))
{
	if ((finduser($al, $ap) == 1)&&(isadmin($al) == 1))
	{
		if ($do == 2)
		{
			kickuser($userlogin);
		}
	}
	else
	{
		$userlogin = "";
	}
}
if ($action == 0)
{
	documentation();
}
else
{
  if (!empty($CellNum))
    $temptxt = $CellNum;
	showblock($action, trim($lg), $pw, $userlogin, getdata($lg, 'hero', 'location'), $temptxt);
  if ($action == 0)
    moveto("game.php?action=1");
}
echo("</td></tr>");
echo ("<tr>");
echo("<td valign=top colspan=2>");

//���������� ������
$days = showstatus($lg);
echo("</td>");
//����
  echo("<td align=right width=1%><table border=1 width=1% cellpadding=0 cellspacing=0><tr><td align=center><img src='images/moon/".$days.".jpg' alt='������ ����'></td></tr></table></td>");
echo ("</tr>");
echo ("</table>\n");
echo("<br><center><table border=1 CELLSPACING=0 CELLPADDING=0><tr><td align=center><font color=blue><b>�����������:</b> ������� �������; <b>��������:</b> �������� ����</font></td></tr><tr><td align=center>� ���� ������������ ������ ������ Space: 'Space opera', 'Velvet rape', 'Ballad for space lovers'</td></tr></table></center></html>");

//�������������� ������ 2 ������
if ($bat == 0)
{
	echo ("\n<META HTTP-EQUIV='REFRESH' CONTENT=120>\n");
}

?>