<?

//���������� ������ ������
Error_Reporting(E_ALL & ~E_NOTICE);
include "bases.php";

//���������� � �����
function baselink()
{
$file = fopen("config.ini.php", "r");
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

//��������� �����
function BattleOn($Login, $Opp)
{
  //�������� �����
  change($Login, 'battle', 'battle', '1');
  change($Opp, 'battle', 'battle', '1');

	//��������� ����� ������ ����
  $file = fopen("data/count.dat", "r");
  $num = fgets($file, 255);
	fclose ($file);
	$num++;
	$file = fopen("data/count.dat", "w");
	fputs ($file, $num);
	fclose ($file);

  //���������� ���������� ��� ����������� �� "1"
  $adm = getadmin();
  $btls = getfrom('admin', $adm, 'settings', 'f4');
  $btls++;
  setto('admin', $adm, 'settings', 'f4', $btls);

  //������ ��� ����
	$file = fopen("data/logs/".$num.".log", "w");
	fclose ($file);

  //���������� ��� �� ���� ������
  change($Login, 'time', 'combats', getdata($Login, 'time', 'combats')+1);
  change($Opp, 'time', 'combats', getdata($Opp, 'time', 'combats')+1);

  //���������� ��� ������ �� 0
  change($Login, 'battle', 'health', '0');
  change($Opp, 'battle', 'health', '0');
  change($Login, 'battle', 'opponent', $Opp);
  change($Opp, 'battle', 'opponent', $Login);
  change($Login, 'battle', 'turn', $Login);
  change($Opp, 'battle', 'turn', $Login);
  change($Login, 'battle', 'attack', '0');
  change($Opp, 'battle', 'attack', '0');
  change($Login, 'battle', 'data', '');
  change($Opp, 'battle', 'data', '');
  change($Login, 'battle', 'value', '6');
  change($Opp, 'battle', 'value', '6');
  change($Login, 'battle', 'info', $num);
  change($Opp, 'battle', 'info', $num);
  $tm = time();
  change($Login, 'battle', 'timeout', $tm);
  change($Opp, 'battle', 'timeout', $tm);
}

//�������� �� �������
function MonsterBattle($Login, $Opp, $x, $y, $rx, $ry)
{
  //��������� ����� ���������� ���������
  $Monster = $Opp;
  $Opp = $Login."_clon";
  $val = 0;
  $lv  = getdata($Login, 'hero', 'level') + rand(1, 4);
  $r   = rand(1, 12);

  //��������� ����������� �������������
  $power       = getdata($Login, 'abilities', 'power');
  $protect     = getdata($Login, 'abilities', 'protect');
  $magicpower  = getdata($Login, 'abilities', 'magicpower');
  $know        = getdata($Login, 'abilities', 'cnowledge');
  $charism     = getdata($Login, 'abilities', 'charism');
  $dexterity   = getdata($Login, 'abilities', 'dexterity');
  $intel       = getdata($Login, 'abilities', 'intellegence');
  $naturemagic = getdata($Login, 'abilities', 'naturemagic');
  $combatmagic = getdata($Login, 'abilities', 'combatmagic');
  $mindmagic   = getdata($Login, 'abilities', 'mindmagic');
  $power       = ($power + $protect + $magicpower + $naturemagic)*1.5;

  //�������� ���� � �����
  $name  = getfrom('monster', $Monster, 'random', 'hand');
  $hand  = getfrom('name', $name, 'allitems', 'num');
  $name  = getfrom('monster', $Monster, 'random', 'armor');
  $armor = getfrom('name', $name, 'allitems', 'num');
  $Hlt   = $lv * 100;

  //�������� ������ ���������
	mysql_query ("insert into battles values('$Opp', 0, '0', 0);");
  mysql_query("insert into city values ('$Opp', '$login', '$val', '$val', '$val', '$val', '$val', '$val', '$val', '$val', '$val', '$val', '$val', '$val', '$val', '$val', '$val', '$val', '$val', '$val', '$val', '$val');");
	mysql_query("insert into users values ('$Opp', '$Login', '$x', '$y', '$rx', '$ry', '$Opp', '$Opp');");
	mysql_query("insert into hero values ('$Opp', '$Monster', '$val', '$lv', '5', '$Opp', '$Opp', '$Hlt', '$Opp');");
  mysql_query ("insert into battle values('$Opp', '0', '0', '0', '0', '0', '', '0', '0', '0');");
	mysql_query("insert into magic values ('$Opp', ".$r.", 0, 0, 0, 0, 0);");
  mysql_query("insert into abilities values ('$Opp', '$power', '$protect', '$magicpower', '$know', '$charism', '$dexterity', '$intel', '$naturemagic', '$combatmagic', '$mindmagic');");
	mysql_query("insert into items values ('$Opp', '$val', '$val', '$val', '$val', '$val', '$armor', '$hand', '$val', '$val', '$val', '$val');");
	mysql_query("insert into bottles values ('$Opp', '1', '1', '1', '1', '1', '1', '0', '0', '0', '0', '0', '0', '0', '0', '0');");

  //������� � �������� ��� �������� ��������� ����� �����
  change($Opp, 'city', 'build14', '1');

  //��������� �����
  BattleOn($Login, $Opp);
}

//�������� ���
function ChangeWeight($Login, $Weight)
{
  $Wg = getdata($Login, 'status', 'timeout');
  $Wg = $Wg + $Weight;
  if ($Wg < 0)
    $Wg = 0;
  change($Login, 'status', 'timeout', $Wg);
}

//������ �� �����
function FromBattle($Login)
{
  //����� �������
  $btl = getdata($Login, 'battle', 'battle');
  if ($btl != 0)
    moveto('battle.php');

  //����� �������
  $btl = getdata($Login, 'battles', 'battle');
  if ($btl != 0)
    moveto('fight.php');
}

//������ HELP
function HelpMe($index, $align)
{
  //������ ������
  echo("\n\n<script language=JavaScript>\n");
  echo("function helpme(s)\n");
  echo("{\n");
	echo('window.open("help/help.php?index=" + s, 16,"toolbar=no, location=no, menubar=no,scrollbars=yes,width=400,height=300");'."\n");
  echo("}\n");
  echo("</script>\n");
  echo("<form action=javascript:helpme('".$index."')>\n");
  if ($align == 1)
    echo("<center>\n");
  ?>
  <input type='submit' value='������' style='background:#000099; font-WEIGHT: BOLD; COLOR: white; cursor: hand; list-style: lower-alpha; filter: Alpha(Opacity=20, FinishOpacity=100, Style=3)'>
  <?
  if ($align == 1)
    echo("</center>\n");
  echo("</form>\n\n\n");
}

//������
function PBar($percent, $color)
{
  $percent = round($percent);
  $wd = $percent;
  if ($wd < 1)
    $wd = 1;
  echo("<table border=1 cellpadding=0 cellspacing=0 width=150>");
  echo("<tr bgcolor=#C0C0C0><td width=$wd% align=center bgcolor='$color'><font size='1' color='white'>$percent%</font></td><td></td></tr></table>");
} 

//���������� ProgressBar
function progress($percent)
{
  //#264CB7
  echo("<table align='center' width='100%' bgcolor='#000000' cellspacing='1' cellpadding='0'>");
  echo("<tr bgcolor='#cccccc'><td><table cellspacing='0' cellpadding='0' width='$percent%'>");
  echo("<tr><td align='center' bgcolor='#009900'><font size='1' color='white'>$percent%</font></td></tr></table></td></tr></table>");
} 

//���������� � �����...
baselink();

//�������� ���� ������
function drop($name)
{
   //mysql_query("drop database ".$name.";");
}

//����
function wnd()
{
	?>
	<script>
	function wnd(s)
	{
		window.open(s, null,'toolbar=no, location=no, menubar=no,scrollbars=yes,width=656,height=480');
	}
	</script>
	<?
}

//����� ���������
function sms($to, $login, $txt)
{
//��������
$file = fopen ("data/mail/".$to."/rec.".time(), "a");

//�� ����
$from = getdata($login, 'hero', 'name');

//����
change ($to, 'status', 'f2', '1');

//����������...
if ((empty($from))||($from == "")) {$from = $login;}
if ($from != $login)
{
	fputs ($file, "<font color=green><b>".$from."<br>(".$login.")</b></font><br>\n");
}
else
{
	fputs ($file, "<font color=green><b>".$from."</b></font><br>\n");
}
fputs ($file, $txt."<br>");
fclose ($file);
sleep (1);
}

//������� ����� �������������
function toomuch($name)
{
//	link();
	$ath = mysql_query("select * from users;");
	if ($ath)
	{
		$how = 0;
		while ($rw = mysql_fetch_row($ath))
		{
			if (getdata($rw[0], 'status', 'online') == 1)
			{
				$how++;
			}
		}
	}
	return $how;
}

//�������� ����� ������������ � ������
function finduser($username, $pass)
{
//link();
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

//��� ������������
function allusers($name)
{
	echo("<script>\n");
	echo("function clans(p1)\n");
	echo("{\n");
	echo("var s;\n");
	echo("s = 'claninfo.php?' + p1;\n");
	echo("window.open(s, null,'toolbar=no, location=no, menubar=no,scrollbars=yes,width=656,height=480');\n");
	echo("}\n");
	echo("</script>\n");

	echo("<script>\n");
	echo("function dd(p1)\n");
	echo("{\n");
	echo("var s;\n");
	echo("s = 'info.php?name=' + p1;\n");
	echo("window.open(s, null,'toolbar=no, location=no, menubar=no,scrollbars=yes,width=656,height=480');\n");
	echo("}\n");
	echo("</script>\n");

	$ath = mysql_query("select * from users;");
	if ($ath)
	{
		while ($rw = mysql_fetch_row($ath))
		{
			//���� � �������������
			$st = getdata($rw[0], 'status', 'online');
			$ts = getdata($rw[0], 'inf', 'fld7');

			if ($st == 1) 
				{
				echo("<center><table border=1 width=100% CELLSPACING=0 CELLPADDING=0>");
				echo("<tr>");

				//�������� ������
				$clan = getdata($rw[0], 'inclan', 'clan');
				$temp = getfrom('name', $clan, 'clans', 'logo');
				$adm =  getfrom('name', $clan, 'clans', 'login');

				//���� �����, ��������� �.�. ��� ����� �����?
				if (empty($temp))
				{
					$temp = getfrom('login', $rw[0], 'clans', 'logo');
					$adm = $rw[0];
				}

				//���� �� �����
				if (!empty($temp))
				{
					echo("<td width=1% align=center>");
					$test = "'admin=".$adm."&login=".$name."'";
					echo ("<a href=javascript:clans(".$test.");><img src='".$temp."' width=32 height=32 border=0></a>");
					echo("</td>");
				} else
				{
					echo("<td width=1% align=center>");
					echo ("<img src='images\clans\empty.gif' width=32 height=32 border=0>");
					echo("</td>");
				}

				//����� ����� ���������
				echo("<td width=40% align=center><b>");
				if ($ts  == '1')
					{
					echo ("<font color=blue>");
					} else
						{
						echo ("<font color=green>");
						}
				echo (getdata($rw[0], 'hero', 'name')." <a href=javascript:dd('".$rw[0]."')>[<i><font color=black>".getdata($rw[0], 'hero', 'level')."</font></i>]</a></b></td><td width=40% align=center>");

				if ($ts == '1')
					{
					echo ("<font color=blue>");
					} else
						{
						echo ("<font color=green>");
						}
				echo ("<table border=0 width=100%><tr><td width=60% align=center>(".$rw[0].")</td>");
				if ($rw[0] == $name)
					{
          if (getdata($name, 'inf', 'fld7') == '0')
            echo ("<td align=center><a href='game.php?action=60'><b><font color=blue>������ ������</font></b></a>");
            else
            echo("<td align=center><a href='game.php?action=61'><b><font color=blue>�������� ������</font></b></a><br></td>");
					}
				echo ("</tr></table></tr></table></center></font>");
				}
		}
	}
}

//��� ������������
function offline($name)
{
//	link();
	$ath = mysql_query("select * from users;");
	if ($ath)
	{
		while ($rw = mysql_fetch_row($ath))
		{
			$delta = time()-getdata($rw[0], 'inf', 'fld3');
			if ($delta > 299) 
				{
					change($rw[0], 'status', 'online', '0');
				}
		}
	}
}

//�������� IP ������
function findip($ip)
{
//link();
$usr = mysql_query("select * from ip;");
$find = 0;
if ($usr)
   {
   while ($user = mysql_fetch_array($usr))
      {
      if ($user['ip'] == $ip)
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

//������� ����� ������������
function hasuser($username)
{
//link();
$usr = mysql_query("select * from users;");
$find = 0;
if ($usr)
   {
   while ($user = mysql_fetch_array($usr))
      {
      if (($user['login'] == $username))
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

//�������� �������
function getimg($num)
{
//link();
$usr = mysql_query("select * from allitems;");
$find = "";
if ($usr)
   {
   while ($user = mysql_fetch_array($usr))
      {
      if (($user['num'] == $num))
         {
         $find = $user['img'];
		 }
	  }
   }
   else
	{
	   $find = "<font color=red>������ �����������</font>";
	}
	$test = "images/weapons/".$find;
	$find = $test;
	return $find;
}

//�������� �������
function getcimg($num)
{
//link();
$usr = mysql_query("select * from allitems;");
$find = "";
if ($usr)
   {
   while ($user = mysql_fetch_array($usr))
      {
      if (($user['num'] == $num))
         {
         $find = $user['img'];
		 }
	  }
   }
   else
	{
	   $find = "<font color=red>������ �����������</font>";
	}
	$test = "images/cast/".$find;
	$find = $test;
	return $find;
}

//� ����� �� ��� ���
function hasunion($n1, $n2)
{
	$yes = 0;
	if (getdata($n1, 'unions', 'login2') == $n2) {$yes = 1;}
	if (getdata($n1, 'unions', 'login3') == $n2) {$yes = 1;}
	if (getdata($n1, 'unions', 'login4') == $n2) {$yes = 1;}
	if (getdata($n1, 'unions', 'login5') == $n2) {$yes = 1;}
	return $yes;
}

//���������
function getinfo($num)
{
//link();
$usr = mysql_query("select * from allitems;");
$find = "";
if ($usr)
   {
   while ($user = mysql_fetch_array($usr))
      {
      if (($user['num'] == $num))
         {
         $act = $user['action'];
		 $hw = $user['effect'];
		 }
	  }
   }
   else
	{
	   $find = "<font color=red>������ �����������</font>";
	}

	//��� ������ ������
	if ($act == 1) {$act = "����������� ���� �� ";}
	if ($act == 2) {$act = "��������� � ������ ";}
	if ($act == 3) {$act = "��������� � ������������ ";}
	if ($act == 4) {$act = "����������� ����� �� ";}
	if ($act == 5) {$act = "�������� ���������� ����������� �� ";}

	//���������
	$find = $act.$hw."%"; 

  $cst = CastName($num);
  $find = $find."<br>".$cst;
  //������� ���������
	return $find;
}

//��������� � �����
function getcinfo($num)
{
//link();
$usr = mysql_query("select * from allcasts;");
$find = "";
if ($usr)
   {
   while ($user = mysql_fetch_array($usr))
      {
      if (($user['num'] == $num))
         {
         $act = $user['action'];
		 $hw = $user['effect'];
		 }
	  }
   }
   else
	{
	   $find = "<font color=red>������ �����������</font>";
	}

	//��� ������ ������
	if ($act == 1) {$act = "����������� ���� �� ";}
	if ($act == 2) {$act = "��������������� ";}
	if (($act == 3)||($act == 8)) {$act = "���������� ";}
	if ($act == 4) {$act = "������� ���� � ��������� � ����. ���� ";}
	if (($act == 5)||($act == 6)) {$act = "������� ���������� ��������� ����. ��� �����, ���������� ����� �������� ���������� ������ �� ";}
	if ($act == 7) {$act = "��������� ��������� �� ���������, ��� ����� ������ ��� ��������� ����. ������ ��� ��������� ������ �� ";}

	if ($hw == 0) {$hw = 15;}
	//���������
	$find = $act.$hw."%";
	return $find;
}

//��� �����
function forbattle($num, $wh)
{
//link();
$usr = mysql_query("select * from allitems;");
$find = 0;
if ($usr)
   {
   while ($user = mysql_fetch_array($usr))
      {
      if (($user['num'] == $num))
		 {
		 $act = $user['action'];
		 $hw = $user['effect'];
		 }
	  }
   }
   else
	{
	   $find = "<font color=red>������ �����������</font>";
	}

	//���������
	$find = 0;

	//��� ������ ������
	if ($act == $wh)
		{
		$find = $hw/100;
		}
	return $find;
}

//��� �����
function spell($num)
{
//link();
$usr = mysql_query("select * from allitems;");
$find = 0;
if ($usr)
   {
   while ($user = mysql_fetch_array($usr))
      {
      if ($user['num'] == $num)
		 {
		  return $user['effect'];
		 }
	  }
   }
   return 0;
}

//��������� ������ �� ������� �� ����� ����, ����� ������������ � ����� �������
function getdata($username, $table, $field)
{
//link();
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
	   $find = "<font color=red>������ �����������</font>";
	}
	return $find;
}

//������� ������� �� ���������
function kickitem($login, $item)
{
	//������� ���� ������� � ���������
	$golova = getfrom('num', getdata($login, 'items', 'golova'), 'allitems', 'name');
	$shea = getfrom('num', getdata($login, 'items', 'shea'), 'allitems', 'name');
	$telo = getfrom('num', getdata($login, 'items', 'telo'), 'allitems', 'name');
	$leftruka = getfrom('num', getdata($login, 'items', 'leftruka'), 'allitems', 'name');
	$rightruka = getfrom('num', getdata($login, 'items', 'rightruka'), 'allitems', 'name');
	$palec = getfrom('num', getdata($login, 'items', 'palec'), 'allitems', 'name');
	$plash = getfrom('num', getdata($login, 'items', 'plash'), 'allitems', 'name');
	$tors = getfrom('num', getdata($login, 'items', 'tors'), 'allitems', 'name');
	$koleni = getfrom('num', getdata($login, 'items', 'koleni'), 'allitems', 'name');
	$nogi = getfrom('num', getdata($login, 'items', 'nogi'), 'allitems', 'name');

	//�� �� ���
	if ($item == $golova)
	{
		change($login, 'items', 'golova', 0);
	}
	if ($item == $shea)
	{
		change($login, 'items', 'shea', 0);
	}
	if ($item == $telo)
	{
		change($login, 'items', 'telo', 0);
	}
	if ($item == $leftruka)
	{
		change($login, 'items', 'leftruka', 0);
	}
	if ($item == $rightruka)
	{
		change($login, 'items', 'rightruka', 0);
	}
	if ($item == $palec)
	{
		change($login, 'items', 'palec', 0);
	}
	if ($item == $plash)
	{
		change($login, 'items', 'plash', 0);
	}
	if ($item == $tors)
	{
		change($login, 'items', 'tors', 0);
	}
	if ($item == $koleni)
	{
		change($login, 'items', 'koleni', 0);
	}
	if ($item == $nogi)
	{
		change($login, 'items', 'nogi', 0);
	}

  //�������� ����� ��������
  $item = getfrom('name', $item, 'allitems', 'num');

  //��� �������� � ���������
  for ($i = 1; $i <= 16; $i++)
  {
    $Itm = getdata($login, 'inventory', 'inv'.$i);
    if ($item == $Itm)
    {
      PopItem($login, $item);
      return;
    }
  }
}

//������� ���������� �� �����
function kickcitem($login, $item)
{
	//������� ���� ������� � ���������
	$cast1 = getfrom('num', getdata($login, 'magic', 'cast1'), 'allcasts', 'name');
	$cast2 = getfrom('num', getdata($login, 'magic', 'cast2'), 'allcasts', 'name');
	$cast3 = getfrom('num', getdata($login, 'magic', 'cast3'), 'allcasts', 'name');
	$cast4 = getfrom('num', getdata($login, 'magic', 'cast4'), 'allcasts', 'name');
	$cast5 = getfrom('num', getdata($login, 'magic', 'cast5'), 'allcasts', 'name');
	$cast6 = getfrom('num', getdata($login, 'magic', 'cast6'), 'allcasts', 'name');

	//�� �� ���
	$kicked = 0;
	if (($item == $cast1)&&($kicked == 0))
	{
		change($login, 'magic', 'cast1', 0);
		$kicked = 1;
	}
	if (($item == $cast2)&&($kicked == 0))
	{
		change($login, 'magic', 'cast2', 0);
		$kicked = 1;
	}
	if (($item == $cast3)&&($kicked == 0))
	{
		change($login, 'magic', 'cast3', 0);
		$kicked = 1;
	}
	if (($item == $cast4)&&($kicked == 0))
	{
		change($login, 'magic', 'cast4', 0);
		$kicked = 1;
	}
	if (($item == $cast5)&&($kicked == 0))
	{
		change($login, 'magic', 'cast5', 0);
		$kicked = 1;
	}
	if (($item == $cast6)&&($kicked == 0))
	{
		change($login, 'magic', 'cast6', 0);
		$kicked = 1;
	}
}

//��������� �������� ������� �� ���� � �����������
function getregion($rx, $ry, $zone)
{
//link();
$usr = mysql_query("select * from map;");

$find = "";
if ($usr)
   {
   while ($user = mysql_fetch_array($usr))
      {
      if (($user['rx'] == $rx)&&($user['ry'] == $ry)&&($user['zone'] == '0'))
         {
         $find = $user['name'];
		 }
	  }
   }
   else
	{
	   $find = "<font color=red>������ �����������</font>";
	}
	return $find;
}

//��������� ������ � ����
function setto($fld, $value, $table, $field, $new)
{
	mysql_query("update ".$table." set ".$field." = '".$new."' where ".$fld." = '".$value."';");
}

//��������� ������ �� ������� �� ����� ����, ����� ������������ � ����� �������
function getfrom($fld, $value, $table, $field)
{
//link();
$usr = mysql_query("select * from ".$table.";");

$find = "";
if ($usr)
   {
   while ($user = mysql_fetch_array($usr))
      {
      if (($user[$fld] == $value))
         {
         $find = $user[$field];
		 }
	  }
   }
   else
	{
	   $find = "<font color=red>������ �����������</font>";
	}
	return $find;
}

//��������� ������ �� �����
function getfield($row, $col, $x, $y)
{
//link();
$usr = mysql_query("select * from map;");
$find = "";
if ($usr)
   {
   while ($user = mysql_fetch_array($usr))
      {
      if (($user['row'] == $row)&&($user['col'] == $col))
         {
		  $field = "f".$x."x".$y;
          $find = $user[$field];
		 }
	  }
   }
   else
	{
	   $find = "<font color=red>������ �����������</font>";
	}
	return $find;
}

//�������� �����
function addl($username, $place)
{
	if (getdata($username, 'items', $place) != 0)
		{
		echo ("<option value='".getfrom('num', getdata($username, 'items', $place), 'allitems', 'name')."'>".getfrom('num', getdata($username, 'items', $place), 'allitems', 'name')."</option>");
		}
}

//�������� �����
function addi($username, $Number)
{
	if (getdata($username, 'inventory', 'inv'.$Number) != 0)
		{
		echo ("<option value='".getfrom('num', getdata($username, 'inventory', 'inv'.$Number), 'allitems', 'name')."'>".getfrom('num', getdata($username, 'inventory', 'inv'.$Number), 'allitems', 'name')."</option>");
		}
}

//��� ���� ���������
function allmyitems($username, $name)
{
//link();
$usr = mysql_query("select * from users;");
if ($usr)
   {
   while ($user = mysql_fetch_array($usr))
      {
      if (($user['login'] == $username))
         {
         $find = $user['login'];
		 }
	  }
   }

if (!empty($find))
	{
	echo ("<select name=$name style='background:#000099; font-WEIGHT: BOLD; COLOR: white; cursor: hand; list-style: lower-alpha; filter: Alpha(Opacity=20, FinishOpacity=100, Style=3)'>");
	//������
	addl($username, 'golova');
	//���
	addl($username, 'shea');
	//����
	addl($username, 'telo');
	//����
	addl($username, 'tors');
	//������
	addl($username, 'palec');
	//����� ����
	addl($username, 'leftruka');
	//������ ����
	addl($username, 'rightruka');
	//����
	addl($username, 'nogi');
	//������
	addl($username, 'koleni');
	//����
	addl($username, 'plash');
  //� ��� 16 ����� �� ���������
  for ($i = 1; $i <= 16; $i++)
    addi($username, $i);
	echo ("</select>");
	}
}

//�������� �����
function addc($username, $place)
{
	if (getdata($username, 'magic', $place) != 0)
		{
		echo ("<option value='".getfrom('num', getdata($username, 'magic', $place), 'allcasts', 'name')."'>".getfrom('num', getdata($username, 'magic', $place), 'allcasts', 'name')."</option>");
		}
}

//��� ���� ���������
function allmycasts($username, $name)
{
//link();
$usr = mysql_query("select * from users;");
if ($usr)
   {
   while ($user = mysql_fetch_array($usr))
      {
      if (($user['login'] == $username))
         {
         $find = $user['login'];
		 }
	  }
   }

if (!empty($find))
	{
	echo ("<select name=$name style='background:#000099; font-WEIGHT: BOLD; COLOR: white; cursor: hand; list-style: lower-alpha; filter: Alpha(Opacity=20, FinishOpacity=100, Style=3)'>");
	//���������� 1
	addc($username, 'cast1');
	//���������� 2
	addc($username, 'cast2');
	//���������� 3
	addc($username, 'cast3');
	//���������� 4
	addc($username, 'cast4');
	//���������� 5
	addc($username, 'cast5');
	//���������� 6
	addc($username, 'cast6');
	echo ("</select>");
	}
}

//�������� �� ������������ ����������
function issubadmin($username)
{
	$r = 0;
	$usr = mysql_query("select * from settings");
	$find = 0;
	if ($usr)
   {
	   while ($user = mysql_fetch_array($usr))
		{
	      if ($user['f1'] == $username)
		 {
			 $find = 1;
		 }
	  }
   }
   else
	{
	   $find = 0;
	}
	if (empty($username))
	{

	}
	return $find;	
}

//����� �� ������������
function isadmin($username)
{
//link();
$usr = mysql_query("select * from settings");
$find = 0;
if ($usr)
   {
   while ($user = mysql_fetch_array($usr))
      {
      if (($user['admin'] == $username))
         {
         $find = 1;
		 }
	  }
   }
   else
	{
	   $find = 0;
	}
	return $find;
}

//����� �� ������������
function getadmin()
{
$usr = mysql_query("select * from settings");
$find = "Admin";
if ($usr)
   {
   while ($user = mysql_fetch_array($usr))
      {
      if (($user['admin'] == $username))
         {
         $find = $username;
		 }
	  }
   }
	return $find;
}

//�������� ������������ �� ���� ������
function kickuser($username)
{
  //���� ��� ������, �������� � ����� ������� �� ����
  $Monster = getdata($username, 'city', 'build14');

	//������� �� ����
	mysql_query("delete from abilities where login = '".$username."';");
	mysql_query("delete from lostpass where login = '".$username."';");
	mysql_query("delete from unions where login = '".$username."';");
	mysql_query("delete from battles where login = '".$username."';");
	mysql_query("delete from economic where login = '".$username."';");
	mysql_query("delete from hero where login = '".$username."';");
	mysql_query("delete from help where login = '".$username."';");
	mysql_query("delete from info where login = '".$username."';");
	mysql_query("delete from items where login = '".$username."';");
	mysql_query("delete from time where login = '".$username."';");
	mysql_query("delete from users where login = '".$username."';");
	mysql_query("delete from city where login = '".$username."';");
	mysql_query("delete from ip where login = '".$username."';");
	mysql_query("delete from magic where login = '".$username."';");
	mysql_query("delete from status where login = '".$username."';");
	mysql_query("delete from inf where login = '".$username."';");
	mysql_query("delete from coords where login = '".$username."';");
	mysql_query("delete from inclan where login = '".$username."';");
	mysql_query("delete from temp where login = '".$username."';");
	mysql_query("delete from battle where login = '".$username."';");
	mysql_query("delete from hosting where login = '".$username."';");
	mysql_query("delete from bottles where login = '".$username."';");
	mysql_query("delete from newchar where login = '".$username."';");
	mysql_query("delete from army where login = '".$username."';");
	mysql_query("delete from capital where login = '".$username."';");
	mysql_query("delete from inventory where login = '".$username."';");

  //������?
  if ($Monster != 1)
  {
    //������� ������� ��� ������ � �����
    $dir = "data/mail/".$username; 
    while ($file = readdir($dir))
      unlink($dir."/".$file);
    closedir($dir);

	  //������� �����
  	rmdir($dir);

    //���������� �������� +1
    $adm = getadmin();
    $btls = getfrom('admin', $adm, 'settings', 'f3');
    $btls++;
    setto('admin', $adm, 'settings', 'f3', $btls);

    //������� ��� ����, �������� ���� � �����
	  unlink("data/logs/".$username.".log");
  	unlink("data/trade/".$username);
	  unlink("images/photos/".$username.".jpg");
  	unlink("images/photos/".$username.".gif");
  }
}

//��������� ������ � ����
function change($username, $table, $field, $value)
{
	mysql_query("update ".$table." set ".$field." = '".$value."' where login = '".$username."';");
}

//��������� �����������
function addability($num, $name, $effect, $level1, $level2, $level3, $img, $desc1, $desc2, $desc3)
{
	mysql_query ("insert into additional values ('$num', '$name', '$effect', '$level1', '$level2', '$level3', '$img', '$desc1', '$desc2', '$desc3');");
}

//��������� �����
function addcastle($race, $build)
{
//	link();
	mysql_query ("insert into buildings values ('$race', '$build[0]', '$build[1]', '$build[2]', '$build[3]', '$build[4]', '$build[5]', '$build[6]', '$build[7]', '$build[8]', '$build[9]', '$build[10]', '$build[11]', '$build[12]', '$build[13]', '$build[14]', '$build[15]', '$build[16]', '$build[17]', '$build[18]', '$build[19]');");
}

//��������� ����
function additem($num, $name, $action, $effect, $img, $cena, $type)
{
//	link();
	mysql_query ("insert into allitems values ('$num', '$name', '$action' , '$effect', '$img', '$cena', '$type');");
}

//��������� ���������� (type - combat, nature, mind)
function addcast($num, $name, $type, $action, $effect, $img, $cena)
{
//	link();
	mysql_query ("insert into allcasts values ('$num', '$name', '$type', '$action' , '$effect', '$img', '$cena');");
}

//��������� �������
function addmonstr($name, $race, $art, $level, $health, $power, $protect)
{
//	link();
	mysql_query ("insert into monsters values ('$name', '$race', '$art', '$level' , '$health', '$power', '$protect');");
}

//����� ������ ���� �������������
function makelist($login, $pass)
{
//	link();
	$ath = mysql_query('select * from users;');
	if ($ath)
	{
		echo("<form name='slct' action='game.php' method=post><select name='userlogin' style='background:#000099; font-WEIGHT: BOLD; COLOR: white; cursor: hand; list-style: lower-alpha; filter: Alpha(Opacity=20, FinishOpacity=100, Style=3)'>");
		while ($row = mysql_fetch_row($ath))
		{
			echo("<option name='".$row[0]."'>".$row[0]."</option>");
		}
		echo("</select><br><br><input type='submit' value='��������' style='background:#000099; font-WEIGHT: BOLD; COLOR: white; cursor: hand; list-style: lower-alpha; filter: Alpha(Opacity=20, FinishOpacity=100, Style=3)'>");
		echo("<input type='hidden' name='action' value=17>");
		echo("<input type='hidden' name='al' value='$login'>");
		echo("<input type='hidden' name='do' value=1>");
		echo("<input type='hidden' name='ap' value='$pass'></form>");
	}

	$ath = mysql_query('select * from users;');
	if ($ath)
	{
		echo("<form name='act' action='game.php' method=post><select name='userlogin' style='background:#000099; font-WEIGHT: BOLD; COLOR: white; cursor: hand; list-style: lower-alpha; filter: Alpha(Opacity=20, FinishOpacity=100, Style=3)'>");
		while ($row = mysql_fetch_row($ath))
		{
			echo("<option name='".$row[0]."'>".$row[0]."</option>");
		}
		echo("</select><br><br><input type='submit' value='�������' style='background:#000099; font-WEIGHT: BOLD; COLOR: white; cursor: hand; list-style: lower-alpha; filter: Alpha(Opacity=20, FinishOpacity=100, Style=3)'>");
		echo("<input type='hidden' name='action' value=17>");
		echo("<input type='hidden' name='al' value='$login'>");
		echo("<input type='hidden' name='do' value=2>");
		echo("<input type='hidden' name='ap' value='$pass'></form>");
	}
}

//������������ ������ (���, �������, ���)
function generate($name, $table, $row)
{
	$ath = mysql_query("select * from ".$table.";");
	if ($ath)
	{
		echo ("<select name='$name' style='background:#000099; font-WEIGHT: BOLD; COLOR: white; cursor: hand; list-style: lower-alpha; filter: Alpha(Opacity=20, FinishOpacity=100, Style=3)'>");
		while ($rw = mysql_fetch_row($ath))
      if ($rw[$row] != 'Bot')
  			echo("<option name='".$rw[$row]."'>".$rw[$row]."</option>");
		echo("</select>");
	}
}

//������������ ������ (���, �������, ���)
function sortgenerate($name, $table, $row)
{
	$count=0;
	$ath = mysql_query("select * from ".$table.";");
	if ($ath)
	{
		while ($rw = mysql_fetch_row($ath))
		{
			$info[$count] = $rw[$row];
			$count++;
		}
	}

	//����������
	@sort($info);
	echo ("<select name='$name' style='background:#000099; font-WEIGHT: BOLD; COLOR: white; cursor: hand; list-style: lower-alpha; filter: Alpha(Opacity=20, FinishOpacity=100, Style=3)'>");
	echo("<option name='Admin'>".getadmin()."</option>");
	for ($i = 0; $i < $count; $i++)
    if ($info[$i] != 'Bot')
  		echo("<option name='".$info[$i]."'>".$info[$i]."</option>");
	echo("</select>");

	//������ ������ ������!
}

//������������ ������ (���, �������, ���)
function gen2($name, $table, $row)
{
//	link();
	$ath = mysql_query("select * from ".$table.";");
	if ($ath)
	{
		echo ("<select name='$name' style='background:#000099; font-WEIGHT: BOLD; COLOR: white; cursor: hand; list-style: lower-alpha; filter: Alpha(Opacity=20, FinishOpacity=100, Style=3)'>");
		echo ("<option value='0'>�����</option>");
		while ($rw = mysql_fetch_row($ath))
		{
			echo("<option name='".$rw[$row]."'>".$rw[$row]."</option>");
		}
		echo("</select>");
	}
}

//������������ ������ (���, �������, ���)
function activeusers($name)
{
//	link();
	$ath = mysql_query("select * from users;");
	if ($ath)
	{
		echo ("<select name='$name' size=22 style='background:#000099; font-WEIGHT: BOLD; COLOR: white; cursor: hand; list-style: lower-alpha; filter: Alpha(Opacity=20, FinishOpacity=100, Style=3)'>");
		while ($rw = mysql_fetch_row($ath))
		{
			if (getdata($rw[0], 'status', 'online') == 1)
			{
				echo("<option name='".$rw[0]."'>".getdata($rw[0],'hero', 'name')." [<font color=black>".getdata($rw[0], 'hero', 'level')."</font>]</option>");
			}
		}
		echo("</select>");
	}
}

//������ �������
function table($num, $login)
{
//	link();
	$ath = mysql_query("select * from allitems;");
	if ($ath)
	{
		while ($rw = mysql_fetch_row($ath))
		{
			if ($rw[6] == $num)
			{
				echo("<tr><td width = 25% align=center>".$rw[1]."<form action='armory.php' method=post><input type='hidden' name='action' value=3><input type='hidden' name='item' value='".$rw[1]."'><input type='hidden' name='login' value='".$login."'><input type='submit' value='������'  style='background:#000099; font-WEIGHT: BOLD; COLOR: white; cursor: hand; list-style: lower-alpha; filter: Alpha(Opacity=20, FinishOpacity=100, Style=3)'></form></td><td width=15%><img src='".getimg($rw[0])."'></td><td>".getinfo($rw[0])."</td><td align=center>".(200*getfrom('num', $rw[0], 'allitems', 'cena'))*getdata($login, 'economic', 'curse')."</td></tr>");
			}
		}
	}
}

//������ �������
function ctable($num, $login)
{
//	link();
	$ath = mysql_query("select * from allcasts;");
	if ($ath)
	{
		while ($rw = mysql_fetch_row($ath))
		{
			if ($rw[2] == $num)
			{
			  $mana = getfrom('num', $rw[0], 'allcasts', 'cena'); 
				echo("<tr><td width = 25% align=center>".$rw[1]."<form action='guild.php' method=post><input type='hidden' name='action' value=3><input type='hidden' name='item' value='".$rw[1]."'><input type='hidden' name='login' value='".$login."'><input type='submit' value='������'  style='background:#000099; font-WEIGHT: BOLD; COLOR: white; cursor: hand; list-style: lower-alpha; filter: Alpha(Opacity=20, FinishOpacity=100, Style=3)'></form></td><td width=15%><img src='images/cast/".$rw[5]."'></td><td>".getcinfo($rw[0])."<br>���������� $mana ����</td><td align=center>".getfrom('num', $rw[0], 'allcasts', 'cena')*15*6*getdata($login, 'economic', 'curse')."</td></tr>");
			}
		}
	}
}

//������ ���� ������
function blist($name, $race, $login)
{
	echo ("<select name='$name' style='background:#000099; font-WEIGHT: BOLD; COLOR: white; cursor: hand; list-style: lower-alpha; filter: Alpha(Opacity=20, FinishOpacity=100, Style=3)'>");
	if (getdata($login, 'city', 'build1') == 0)
	{
	echo ("<option value='1'>".getfrom('race', $race, 'buildings', 'build1')."</option>");
	}
	if (getdata($login, 'city', 'build2') == 0)
	{
	echo ("<option value='2'>".getfrom('race', $race, 'buildings', 'build2')."</option>");
	}
	if (getdata($login, 'city', 'build3') == 0)
	{
	echo ("<option value='3'>".getfrom('race', $race, 'buildings', 'build3')."</option>");
	}
	if (getdata($login, 'city', 'build4') == 0)
	{
	echo ("<option value='4'>".getfrom('race', $race, 'buildings', 'build4')."</option>");
	}
	if (getdata($login, 'city', 'build5') == 0)
	{
	echo ("<option value='5'>".getfrom('race', $race, 'buildings', 'build5')."</option>");
	}
	if (getdata($login, 'city', 'build6') == 0)
	{
	echo ("<option value='6'>".getfrom('race', $race, 'buildings', 'build6')."</option>");
	}
	if (getdata($login, 'city', 'build7') == 0)
	{
	echo ("<option value='7'>".getfrom('race', $race, 'buildings', 'build7')."</option>");
	}
	if (getdata($login, 'city', 'build8') == 0)
	{
	echo ("<option value='8'>".getfrom('race', $race, 'buildings', 'build8')."</option>");
	}
	if (getdata($login, 'city', 'build9') == 0)
	{
	echo ("<option value='9'>".getfrom('race', $race, 'buildings', 'build9')."</option>");
	}
	if (getdata($login, 'city', 'build10') == 0)
	{
	echo ("<option value='10'>".getfrom('race', $race, 'buildings', 'build10')."</option>");
	}
	if (getdata($login, 'city', 'build11') == 0)
	{
	echo ("<option value='11'>".getfrom('race', $race, 'buildings', 'build11')."</option>");
	}
	if (getdata($login, 'city', 'build12') == 0)
	{
	echo ("<option value='12'>".getfrom('race', $race, 'buildings', 'build12')."</option>");
	}
	if (getdata($login, 'city', 'build13') == 0)
	{
	echo ("<option value='13'>".getfrom('race', $race, 'buildings', 'build13')."</option>");
	}
	echo ("</select>");
}


//����� ������ ���� �������������
function userlist($name)
{
	generate($name, 'users', 0);
}

//����� ������ ���� �������������
function indexuserlist($name)
{
	sortgenerate($name, 'users', 0);
}

//����� ������ ���� �������������
function user2($name)
{
	gen2($name, 'users', 0);
}

//������
function showstatus($login)
{
  $sec = time()-1093941325;
  $days = $sec / 24;
  $days = round($days / 3600);
  $data = "���� ������: ".$days;

  //���������� ������� ���...
  $year = 1;
  while ($days > 336)
  {
    $days = $days - 336;
    $year++;
  }

  //���������� �����
  $mounth = 0;
  while ($days > 28)
  {
    $days = $days - 28;
    $mounth++;
  }

  //������ �������� �������� ������
  switch($mounth)
  {
    case 0:
      $mnt = "���������";
      break;
    case 1:
      $mnt = "�������";
      break;
    case 2:
      $mnt = "��������";
      break;
    case 3:
      $mnt = "����������";
      break;
    case 4:
      $mnt = "��������";
      break;
    case 5:
      $mnt = "��������";
      break;
    case 6:
      $mnt = "��������";
      break;
    case 7:
      $mnt = "�������";
      break;
    case 8:
      $mnt = "�������";
      break;
    case 9:
      $mnt = "�������";
      break;
    case 10:
      $mnt = "���������";
      break;
    case 11:
      $mnt = "���������";
      break; 
  }
  $data = $days." ".$mnt." ".$year." ����";

	echo ("<center><font color=blue>");
	echo ("<TABLE BORDER=0 CELLSPACING=0 CELLPADDING=0><tr>");
	echo ("<td valign=top><img src=images/menu/gold.gif alt='".getdata($login, 'economic', 'moneyname')."'></td><td valign=center>".getdata($login, 'economic', 'money')."</td><td valign=top><img src=images/menu/metal.gif alt='������'></td><td valign=center>".getdata($login, 'economic', 'metal')."</td><td valign=top><img src=images/menu/rock.gif alt='������'></td><td valign=center>".getdata($login, 'economic', 'rock')."</td><td valign=top><img src=images/menu/wood.gif alt='������'></td><td valign=center>".getdata($login, 'economic', 'wood')."</td>");
	echo ("</tr>");
  echo("<tr><td colspan=8 align=center>$data</td></tr>");
  echo("</TABLE>");
  echo ("</font></center>");
  return $days;
}

//���������������
function conv($in)
{
	switch($in)
	{
		case "people":
			$out = "�������";
			break;
		case "elf":
			$out = "����";
			break;
		case "hnom":
			$out = "����";
			break;
		case "druid":
			$out = "�����";
			break;
		case "necro":
			$out = "���������";
			break;
		case "hell":
			$out = "������";
			break;
		case "knight":
			$out = "������";
			break;
		case "archer":
			$out = "�������";
			break;
		case "mag":
			$out = "���";
			break;
		case "lekar":
			$out = "������";
			break;
		case "barbarian":
			$out = "������";
			break;
		case "wizard":
			$out = "���������";
			break;
	}
	return $out;
}

//��������� ������� ������������� ������������
function infoof($login)
{
	$level = getdata($login, 'hero', 'level');
  $health = getdata($login, 'hero', 'health');
  $how = 300*($level)*($level);
  $hprc = $health / $level;
	if ($health < getdata($login, 'hero', 'level')*10)
		{
		$col = 'red';
		}
		else
		{
			$col = 'blue';
		}
	echo ("<center><table border=1 width=60% align=center CELLSPACING=0 CELLPADDING=0>");
	echo ("<tr><td align=center colspan=2><font color=blue><b>���������� � ���������</b></font></td></tr>");
	echo ("<tr><td align=center width=60%><font color=blue>��������</font></td><td align=center><font color=blue>��������</font></td></tr>");
	echo ("<tr><td align=center><font color=blue>���</font></td><td align=center><font color=blue>".getdata($login, 'hero', 'name')."</font></td></tr>");
	echo ("<tr><td align=center><font color=blue>����</font></td><td align=center><font color=blue>".conv(getdata($login, 'hero', 'race'))."</font></td></tr>");
	echo ("<tr><td align=center><font color=blue>���</font></td><td align=center><font color=blue>".conv(getdata($login, 'hero', 'type'))."</font></td></tr>");
	echo ("<tr><td align=center><font color=".$col.">��������</font></td><td align=center><font color=".$col.">".$health."</font></td></tr>");
	echo ("<tr><td align=center><font color=blue>�����</font></td><td align=center><font color=blue>".getdata($login, 'abilities', 'power')."</font></td></tr>");
	echo ("<tr><td align=center><font color=blue>������</font></td><td align=center><font color=blue>".getdata($login, 'abilities', 'protect')."</font></td></tr>");
	echo ("<tr><td align=center><font color=blue>������ �� �����</font></td><td align=center><font color=blue>".getdata($login, 'abilities', 'dexterity')."</font></td></tr>");
	echo ("<tr><td align=center><font color=blue>������</font></td><td align=center><font color=blue>".getdata($login, 'abilities', 'cnowledge')."</font></td></tr>");
	echo ("<tr><td align=center><font color=blue>������������</font></td><td align=center><font color=blue>".getdata($login, 'abilities', 'charism')."</font></td></tr>");
	echo ("<tr><td align=center><font color=blue>����</font></td><td align=center><font color=blue>".getdata($login, 'abilities', 'intellegence')."</font></td></tr>");
	echo ("<tr><td align=center><font color=blue>���������� ����</font></td><td align=center><font color=blue>".getdata($login, 'abilities', 'naturemagic')."</font></td></tr>");
	echo ("<tr><td align=center><font color=blue>�������</font></td><td align=center><font color=blue>".getdata($login, 'hero', 'level')."</font></td></tr>");
	echo ("<tr><td align=center><font color=blue>���������� ���</font></td><td align=center><font color=blue>".getdata($login, 'time', 'combats')."</font></td></tr>");
	echo ("</table></center>");

	echo ("<center><table border=1 width=60% align=center CELLSPACING=0 CELLPADDING=0>");
	echo ("<tr><td align=center colspan=2><font color=blue><b>���������� �� ������</b></font></td></tr>");
	echo ("<tr><td align=center><font color=blue>�������</font></td><td align=center><font color=blue>".getdata($login, 'users', 'surname')."</font></td></tr>");
	echo ("<tr><td align=center><font color=blue>���</font></td><td align=center><font color=blue>".getdata($login, 'users', 'name')."</font></td></tr>");
	echo ("<tr><td align=center><font color=blue>������</font></td><td align=center><font color=blue>".getdata($login, 'users', 'country')."</font></td></tr>");
	echo ("<tr><td align=center><font color=blue>�����</font></td><td align=center><font color=blue>".getdata($login, 'users', 'city')."</font></td></tr>");
	echo ("<tr><td align=center><font color=blue>�����</font></td><td align=center><font color=blue>".getdata($login, 'users', 'email')."</font></td></tr>");
	echo ("<tr><td align=center><font color=blue>ICQ</font></td><td align=center><font color=blue>".getdata($login, 'inf', 'icq')."</font></td></tr>");
	echo ("<tr><td align=center><font color=blue>URL</font></td><td align=center><font color=blue>".getdata($login, 'users', 'url')."</font></td></tr>");
	echo ("<tr><td align=center><font color=blue>� ����</font></td><td align=center><font color=blue>".getdata($login, 'inf', 'about')."</font></td></tr>");
	echo ("</table></center>");
}

//������ �����
function Strike($Login)
{
  //������� ���� - ���� ������
  $Damage = getdata($Login, 'abilities', 'power');

  //����� ������ � ����
  (int)$Num = getdata($Login, 'items', 'rightruka');

  //�������������� ���� (�� ������ � ����)
  $Procent = getfrom('num', $Num, 'allitems', 'effect');

  //������ ��������������� �����
  $AddOn = ($Damage / 100) * $Procent;

  //������������� �������� �����������
  $Damage = $Damage + $AddOn;

  //���������� �������� �����
  return $Damage;
}

//������ ������
function Protection($Login)
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
  $Damage = $Damage + $Armor + $Tors + $Head + $Koleni + $Nogi + $Plash + +$AddOn;

  //���������� �������� �����
  return $Damage;
}

//��������� ������� ������������� ������������
function heroinfo($login)
{
	wnd();
	$level = getdata($login, 'hero', 'level');
	$how = round(60*pow($level, 1.4));
	$mx = $level*100;
	$md = $mx / 10;
  $expa = getdata($login, 'hero', 'expa');

	//������ �����
	$full = getdata($login, 'abilities', 'power')+rand(1, 0)+1;
	(int)$num = getdata($login, 'items', 'rightruka');
	$weapon = forbattle($num, 1)*getdata($login, 'abilities', 'power');
	$full = $full + $weapon;
  $full = Strike($login);
  $prot = Protection($login);

  //������� ��������� �� ���� ������
  $pre = round(60*pow(($level-1), 1.4));
  if ($pre < 0)
    $pre = 0;

  //��������� � ������
  $temp = $expa - $pre;
  $next = $how  - $pre;

  //� ������� ������ � ��� �����
  $howmany = round($temp*100/$next);
  if ($howmany < 0)
    $howmany = 0;
  if ($howmany > 100)
    $howmany = 100;

  //���� ����� 0, ��...
  if ($expa == 0)
    $howmany = 0;

	//��� ��������	
	if (getdata($login, 'hero', 'health') < getdata($login, 'hero', 'level')*10)
		{
		$col = 'red';
		}
		else
		{
			$col = 'blue';
		}

	$ph = getdata($login, 'inf', 'fld1');
	if ($ph == '0')
	{
		$ph = $ph.".jpg";
	}
  $health = getdata($login, 'hero', 'health');
  $hprc = round($health / $level);

  $mana = getdata($login, 'abilities', 'intellegence');
  $cnow = getdata($login, 'abilities', 'cnowledge');
  if ($cnow != 0)
    $mprc = round(10*$mana / $cnow);
  else
    $mprc = 0;
  
  //��� � ��������
  $weight = getdata($login, 'status', 'timeout');
  $max = getdata($login, 'abilities', 'charism')*2;
  if ($max == 0)
	  $max = 1;
  $vprc = round(100*$weight/$max);
  if ($vprc > 100)
    $vprc = 100;

  //Java �������
  echo("<script>");
  echo("function newchar()");
	echo("{");
	echo("	window.open('newchar.php?login=".$login."', null,'toolbar=no, location=no, menubar=no,scrollbars=yes,width=270,height=270');");
	echo("}");
	echo("</script>");

  //���������� ���������
	if (getdata($login, 'hero', 'upgrade') != 0)
	{
	echo ("<center><table border=1 width=60% align=center CELLSPACING=0 CELLPADDING=0>");
	echo ("<tr><td align=center colspan=3><font color=blue><b>���������� � ����� ���������</b></font> <a href=javascript:wnd('up.php?login=".$login."')>(����)</a></td></tr>");
	echo ("<tr><td align=center width=60%><font color=blue>��������</font></td><td align=center><font color=blue>��������</font></td><td align=center><font color=blue>��������</font></td></tr>");
	echo ("<tr><td align=center><font color=blue>���</font></td><td align=center colspan=2><font color=blue>".getdata($login, 'hero', 'name')."</font></td></tr>");
	echo ("<tr><td align=center><font color=blue>����</font></td><td align=center colspan=2><font color=blue>".conv(getdata($login, 'hero', 'race'))."</font></td></tr>");
	echo ("<tr><td align=center><font color=blue>���</font></td><td align=center colspan=2><font color=blue>".conv(getdata($login, 'hero', 'type'))."</font></td></tr>");
	echo ("<tr><td align=center><font color=".$col.">��������</font></td><td align=center colspan=2><font color=".$col.">".$health." �� ".$mx."</font>");
  PBar($hprc, 'red');
  echo("</td></tr>");
	echo ("<tr><td align=center><font color=blue>����</font></td><td align=center colspan=2><font color=blue>".$mana."</font>");
  PBar($mprc, 'blue');
  echo("</td></td></tr>");
  echo ("<tr><td align=center><font color=blue>�����</font></td><td align=center><font color=blue>".getdata($login, 'abilities', 'power')." (".$full.")</font></td><td align=center><a href='game.php?action=20'>+</a> (<a href='game.php?action=69'>*</a>)</td></tr>");
	echo ("<tr><td align=center><font color=blue>������</font></td><td align=center><font color=blue>".getdata($login, 'abilities', 'protect')." (".$prot.")</font></td><td align=center><a href='game.php?action=21'>+</a> (<a href='game.php?action=70'>*</a>)</td></tr>");
	echo ("<tr><td align=center><font color=blue>������ �� �����</font></td><td align=center><font color=blue>".getdata($login, 'abilities', 'dexterity')."</font></td><td align=center><a href='game.php?action=22'>+</a> (<a href='game.php?action=71'>*</a>)</td></tr>");
	echo ("<tr><td align=center><font color=blue>������</font></td><td align=center><font color=blue>".$cnow."</font></td><td align=center><a href='game.php?action=23'>+</a> (<a href='game.php?action=72'>*</a>)</td></tr>");
	echo ("<tr><td align=center><font color=blue>������������</font></td><td align=center><font color=blue>".getdata($login, 'abilities', 'charism')."</font></td>");
  echo("</td><td align=center><a href='game.php?action=24'>+</a> (<a href='game.php?action=73'>*</a>)</td></tr>");
	echo ("<tr><td align=center><font color=blue>�������� ���������</font></td><td align=center colspan=2>");
  PBar($vprc, 'lightblue');
  echo("</td></tr>");
  echo ("<tr><td align=center><font color=blue>���������� ����</font></td><td align=center><font color=blue>".getdata($login, 'abilities', 'naturemagic')."</font></td><td align=center><a href='game.php?action=27'>+</a> (<a href='game.php?action=76'>*</a>)</td></tr>");
	echo ("<tr><td align=center><font color=blue>�������</font></td><td align=center colspan=2><font color=blue>".getdata($login, 'hero', 'level')."</font></td></tr>");
	echo ("<tr><td align=center><font color=blue>��������� ����������</font></td><td align=center colspan=2><font color=blue>".getdata($login, 'hero', 'upgrade')."</font></td></tr>");
	echo ("<tr><td align=center><font color=blue>����</font></td><td align=center colspan=2><font color=blue>".$expa."</font></td></tr>");
  echo ("<tr><td align=center><font color=blue>��������� �������</font></td><td align=center colspan=2><font color=blue>".$how."</font></td></tr>");
  echo ("<tr><td align=center><font color=blue>����� ��������</font></td><td align=center colspan=2><font color=blue>".getdata($login, 'inf', 'def')."</font></td></tr>");
	} else
	{
	echo ("<center><table border=0 width=70% CELLSPACING=0 CELLPADDING=0><tr><td align=center><table border=1 width=100% align=center CELLSPACING=0 CELLPADDING=0>");
	echo ("<tr><td align=center colspan=2><font color=blue><b>���������� � ����� ���������</b></font> <a href=javascript:wnd('up.php?login=".$login."')>(����)</a></td></tr>");
	echo ("<tr><td align=center width=60%><font color=blue>��������</font></td><td align=center><font color=blue>��������</font></td></tr>");
	echo ("<tr><td align=center><font color=blue>���</font></td><td align=center><font color=blue>".getdata($login, 'hero', 'name')."</font></td></tr>");
	echo ("<tr><td align=center><font color=blue>����</font></td><td align=center><font color=blue>".conv(getdata($login, 'hero', 'race'))."</font></td></tr>");
	echo ("<tr><td align=center><font color=blue>���</font></td><td align=center><font color=blue>".conv(getdata($login, 'hero', 'type'))."</font></td></tr>");
	echo ("<tr><td align=center><font color=".$col.">��������</font></td><td align=center><font color=".$col.">".$health." �� ".$mx."</font>");
  PBar($hprc, 'red');
  echo("</td></tr>");
	echo ("<tr><td align=center><font color=blue>����</font></td><td align=center><font color=blue>".$mana."</font>");
  PBar($mprc, 'blue');
  echo("</td></tr>");
	echo ("<tr><td align=center><font color=blue>�����</font></td><td align=center><font color=blue>".getdata($login, 'abilities', 'power')." (".$full.")</font></td></tr>");
	echo ("<tr><td align=center><font color=blue>������</font></td><td align=center><font color=blue>".getdata($login, 'abilities', 'protect')." (".$prot.")</font></td></tr>");
	echo ("<tr><td align=center><font color=blue>������ �� �����</font></td><td align=center><font color=blue>".getdata($login, 'abilities', 'dexterity')."</font></td></tr>");
	echo ("<tr><td align=center><font color=blue>������</font></td><td align=center><font color=blue>".$cnow."</font></td></tr>");
	echo ("<tr><td align=center><font color=blue>������������</font></td><td align=center><font color=blue>".getdata($login, 'abilities', 'charism')."</font></td></tr>");
	echo ("<tr><td align=center><font color=blue>�������� ���������</font></td><td align=center>");
  PBar($vprc, 'lightblue');
  echo("</td></tr>");
	echo ("<tr><td align=center><font color=blue>���������� ����</font></td><td align=center><font color=blue>".getdata($login, 'abilities', 'naturemagic')."</font></td></tr>");
	echo ("<tr><td align=center><font color=blue>�������</font></td><td align=center><font color=blue>".getdata($login, 'hero', 'level')."</font></td></tr>");
	echo ("<tr><td align=center><font color=blue>��������� ����������</font></td><td align=center><font color=blue>".getdata($login, 'hero', 'upgrade')."</font></td></tr>");
	echo ("<tr><td align=center><font color=blue>����</font></td><td align=center><font color=blue>".$expa."</font></td></tr>");
	echo ("<tr><td align=center><font color=blue>��������� �������</font></td><td align=center><font color=blue>".$how."</font></td></tr>");
  echo ("<tr><td align=center><font color=blue>����� ��������</font></td><td align=center><font color=blue>".getdata($login, 'inf', 'def')."</font></td></tr>");
	echo ("</table></td><td align=center><img width=150 height=200 src='images/photos/".$ph."'>");
  HelpMe(1, 1);
  echo("</td></tr>");
	}

  //�������� �� ������ ������ ������
  function tempcut($s)
  {
    $ns = "";
    for ($i = 1; $i < strlen($s); $i++)
      $ns = $ns.$s[$i];
    return $ns;
  }

  //����������� ���. �����������
  function Ablt($Login, $Number)
  {
    //��������� ������
    $Txt = "<td align=center width=25%>";

    //�������� ���. ����������� �� ������ ������
    $num = getdata($Login, 'newchar', 'achar'.$Number);
    $lvl = $num[0];
    $num = tempcut($num);

    //���� �� �����������?
    if ($lvl != '0')
    {
      //�� ����� ��� �����
      switch ($lvl)
      {
        case 'N':
          $alevel = 1;
          $tlevel = "������� ";
          break;
        case 'A':
          $alevel = 2;
          $tlevel = "����������� ";
          break;
        case 'E':
          $alevel = 3;
          $tlevel = "������� ";
          break;
      }

      //������ ��������
      $img = getfrom('num', $num, 'additional', 'img');
      $img = "images/newchar/".$img."/".$alevel.".jpg";

      //������ ��������
      $desc = getfrom('num', $num, 'additional', 'desc'.$alevel);
      $name = getfrom('num', $num, 'additional', 'name');

      //��������� ���������
      $tlevel = $tlevel.$name.". ".$desc;

      //��������� ������
      $Txt = $Txt."<img src='$img' alt='$tlevel'>";
    }
      else
    {
        $Txt = $Txt."<img src='images/empty.jpg'>";
    }

    //������� ��������
    $Txt = $Txt."</td>";
    return $Txt;
  }

  //������� ���. ��������������
  echo("<tr><td align=center>");
  echo("<center><table border=1 width=10% CELLSPACING=0 CELLPADDING=0>");
  echo("<tr><td colspan=4 align=center>�������������� �����������</td></tr>");
  echo("<tr>");
  for ($i = 1; $i <= 4; $i++)
    echo(Ablt($login, $i));
  echo("</tr>");
  echo("<tr>");
  for ($i = 5; $i <= 8; $i++)
    echo(Ablt($login, $i));
  echo("</tr>");
  echo("<tr>");
  for ($i = 9; $i <= 12; $i++)
    echo(Ablt($login, $i));
  echo("</tr>");
  echo("<tr>");
  for ($i = 13; $i <= 16; $i++)
    echo(Ablt($login, $i));
  echo("</tr>");
  echo("<tr><td align=center colspan=4>��������� �������:");
  progress($howmany);
  echo("</td></tr>");
  echo("</table>");
  echo("</td><td colspan=2>&nbsp;</td></tr></table></center>");
}

//�������� �� ������ �����
function isempty()
{
	//������ �����
	$map[15][15] = 0;

	//���� ����������
	$rw = 0;

	//������ ��� ����� � ������
	for ($i = 1; $i < 21; $i++)
	{
		for ($j = 1; $j < 21; $j++)
		{
			//���������� ��� �����
			$rx = $i;
			$ry = $j;
			$file = fopen("maps/".$rx."x".$ry.".map", "r");
			$rw = 0;

			//�����
			for ($x = 1; $x < 11; $x++)
			{
				//����� ������ �������
				for ($y = 1; $y < 11; $y++)
				{
					//�������� ������ �� ������
					$fld = fgets($file, 255);
					$map[$x][$y] = $fld;

					//��� ������?
					$t = trim(substr($fld, 4));
	
					//����� ��� ���?
					if (!empty($t))
					{
						//���� ���-�� ����
						if ($t != '0')
						{
							//���� ������������ �� ����������, �� ������� �����
							if (hasuser($t) == 0)
							{
								$s = $fld[0].$fld[1].$fld[2].$fld[3]."0\n";
								$map[$x][$y] = $s;
								$rw = 1;
								echo("��������: ".$i."x".$j." | ".$x."x".$y."<br>");
							}
						}
					}

				//��������� $y ����
				}
			//��������� $x ����
			}

			//��������� ����
			fclose($file);

			if ($rw == 1)
			{
				//������������ �����
				$file = fopen("maps/".$rx."x".$ry.".map", "w");
				for ($x = 1; $x < 11; $x++)
				{
					//����� ������ �������
					for ($y = 1; $y < 11; $y++)
					{
						fputs($file, $map[$x][$y]);
					}
				}
				fclose($file);
			}
		}
	}
}

//��������� ������ �� �������
function getconf($type)
{
	$file = fopen("config.ini.php");
	$temp = fgets($file, 255);	
	$temp = fgets($file, 255);	
	$host = fgets($file, 255);	
	$base = fgets($file, 255);	
	$name = fgets($file, 255);	
	$pass = fgets($file, 255);	
	if (strtolower($type) == 'host')
		return $host;
	if (strtolower($type) == 'base')
		return $base;
	if (strtolower($type) == 'name')
		return $name;
	if (strtolower($type) == 'pass')
		return $pass;
	fclose($file);
}

//������������ ��
function stabilization()
{

	//��������� ���������� ����������
	$tcount = 21;
	$tables[1] = 'abilities';
  $tables[2] = 'army';
  $tables[3] = 'battles';
	$tables[4] = 'city';
  $tables[5] = 'capital';
  $tables[6] = 'coords';
	$tables[7] = 'economic';
	$tables[8] = 'hero';
	$tables[9] = 'inf';
	$tables[10] = 'info';
	$tables[11] = 'ip';
	$tables[12] = 'items';
	$tables[13] = 'lostpass';
	$tables[14] = 'magic';
	$tables[15] = 'status';
	$tables[16] = 'temp';
	$tables[17] = 'time';
	$tables[18] = 'unions';
  $tables[19] = 'battle';
  $tables[20] = 'bottles';
  $tables[21] = 'newchar';

	//�������� ������ �������������
	$usr = mysql_query("select * from users;");
  $count = 0;
  $find = "";
	if ($usr)
	{
    //������� ������ ���� �������������
    while ($user = mysql_fetch_array($usr))
		{
			$logins[$count] = $user['login'];
      echo ($count+1).") ".$logins[$count]."<br>\n";
			$count++;
		} //$user
	} //$usr

	//�������� ��� ���� �� �������
	for ($i = 1; $i <= $tcount; $i++)
	{
    //������� ������� � ����
    $ts = mysql_query("select count(*) from ".$tables[$i].";");
    $cn = mysql_fetch_array($ts);
    $nm = $cn[0];

		//�������� ������ �������������
		echo("������: <b>".$tables[$i]."</b><br>\n");
    echo("�������������: ".$nm."<br>\n");

    //����� �� ������
    if ($nm > $count)
    {
      echo("���������� ������<br>\n");
  
      //�������� ���� ������������� � �������
      $sr = mysql_query("select * from ".$tables[$i].";");
	  	if ($sr)
  		{
        //��� ������� �� ���
	  		while ($ser = mysql_fetch_array($sr))
		  	{
			  	//�������� �������
	  			$f = 0;

		  		//���� ������������ � ������ ������������������ � ���� users
			  	for ($k = 0; $k < $count; $k++) 
  				{
	  				//������?
		  			if ($ser['login'] == $logins[$k])
			  			$f = 1;
  				}

	  			//���� �� ������, �� ������� �����
		  		if ($f == 0)
			  	{
					  $username = $ser['login'];
  					mysql_query("delete from ".$tables[$i]." where login = '".$username."';");
            echo("<dd>������� ������������ ".$username."<br>\n");
	  				$n++;
		  		} //$f
			  } //$ser
  		} //$sr

      //�������� ��������� �� ������� �������
  		echo("<b><font color=blue>������ ������� ���������</font></b><br>\n");
    } //����� ������
	} //for
} //Stabilization

//�����������������
function admin($login, $pass, $who)
{
	echo("<center><h2>�����������������</h2>");
	echo("<table border=1 width=90% CELLSPACING=0 CELLPADDING=0>");
	echo("<tr><td align=center width=25%>������������</td><td align=center>�������������� ����������</td><tr>");
	echo("<tr><td align=center>");
	makelist($login, $pass);
	echo("<br><a href='javascript:upload();'>�������� ������</a>");	
	echo("<br><a href='fullmap.php'>����� ��������</a>");	
	echo("<br><a href='game.php?action=67'>�������� 1000 ��������</a>");	
	echo("<br><a href='game.php?action=65'>������ ����</a>");
	echo("<br><a href='game.php?action=66'>�������� ��������� ������</a>");
	echo("<br><a href='game.php?action=68'>������������ ����</a>");
	echo("<br><a href='game.php?action=78'>��������������� ������</a>");
	echo("<br><a href='game.php?action=79'>��������� ���� ���������</a>");
  echo("<br><a href='statistic.php'>������� ����������</a>");
	echo("</td><td>");
	heroinfo($who);
	echo ("</td></tr></table>");
}

//��������� ������ �� cookies
function getlogin()
{
	return trim($HTTP_COOKIE_VARS["nativeland"]);
}

//����� � ���� ���� �����?
function hasmail($login)
{
	$has = 0;
	if ($file = fopen("data/mail/".$login, "r"))
	{
		$rd = fgets($file, 255);
		if (!empty($rd))
		{
			$has = 1;
		}
		fclose ($file);
	}
	return $has;
}


//���� �����
function showmenu($login, $name)
{
	?>
	<script language=JavaScript>
    function help()
    {
			window.open("help.php",2, "toolbar=no, location=no, menubar=no, scrollbars=yes, width=" + (screen.width-10) + ", height=" + (screen.height-20) + ", resizable=yes, left=0, top=0");
    }
		function forum()
		{
			window.open("forums/",2, "toolbar=no, location=no, menubar=no, scrollbars=yes, width=" + (screen.width-10) + ", height=" + (screen.height-20) + ", resizable=yes, left=0, top=0");
		}
		function money()
		{
			window.open("money.php",10,"toolbar=no, location=no, menubar=no,scrollbars=yes,width=540, height=300");
		}
		function news(log)
		{
			window.open("news.php?login=" + log,4,"toolbar=no, location=no, menubar=no,scrollbars=yes,width=540, height=300");
		}
		function mail(log)
		{
			window.open("mail.php?login=" + log,9,"toolbar=no, location=no, menubar=no,scrollbars=yes,width=540, height=300");
		}
		function chat()
		{
			window.open("chat.php",1,"toolbar=no, location=no, menubar=no,scrollbars=no,width=540, height=300");
		}
		function upload()
		{
			window.open("fileup.php",3,"toolbar=yes, location=yes, menubar=yes,scrollbars=yes");
		}
		function cheats()
		{
			window.open("cheats.php",5,"toolbar=no, location=no, menubar=no,scrollbars=yes,width=540, height=300");
		}
		function music()
		{
			window.open("player.php?track=0",6,"toolbar=no, location=no, menubar=no,scrollbars=no,width=40, height=40");
		}
		function pltop()
		{
			window.open("top.php",7,"toolbar=no, location=no, menubar=no,scrollbars=yes,width=600, height=400");
		}
		function map()
		{
			window.open("viewmap.php",8,"toolbar=no, location=no, menubar=no,scrollbars=no,width=440, height=440");
		}
	</script>
	<?
	$rx = getdata ($login, 'coords', 'rx');
	$ry = getdata ($login, 'coords', 'ry');
  $status = getdata ($login, 'city', 'build20');
	echo ("<tr><td width=20% valign=top><font color=blue size=4><center><b>���� ($name)</b></center></font><br>");
	echo("<font color=white><b>����������</b></font>");
	echo("<a href='game.php?action=1'><dd>��������</a><br>");
	echo("<a href='game.php?action=80'><dd>���������</a><br>");
  echo("<a href='game.php?action=8'><dd>���������</a><br>");
  echo("<a href='game.php?action=2'><dd>����������</a><br>");
  echo("<a href='map.php'><dd>�����������</a><br>");	
  if ($status != 0)
    echo("<a href='game.php?action=3'><dd>���� �� ������</a><br>");
	echo("<a href='javascript:map();'><dd>����� �����������</a><br>");	
  echo("<a href='game.php?action=5'><dd>������� �� ��������</a><br>");
	echo("<font color=white><b>�������</b></font>");
  if ($status != 0)
    echo("<a href='game.php?action=7'><dd>�������������</a><br>");
  echo("<a href='game.php?action=12'><dd>������� �����</a><br>");
	echo("<font color=white><b>�������</b></font>");
	echo("<a href='kickme.php'><dd>������� �������</a><br>");
	echo("<a href='javascript:help();'><dd>������������</a><br>");
	echo("<a href=javascript:money();><dd>����� ������</a><br>");
	echo("<a href=javascript:pltop();><dd>TOP �������</a><br>");
	echo("<a href='game.php?action=19'><dd>");
	if (getdata($login, 'status', 'f2') == 1)
			{
			echo ("<font color='yellow'>");
			echo ("<bgsound src='music/notify.wav' loop=1><b>");
			}
	echo ("���������</b></font></a><br>");
	echo("<a href=javascript:news('".$login."');><dd>�������</a><br>");
	echo("<a href=javascript:music();><dd>������</a><br>");
	echo("<a href='javascript:forum();'><dd>�����</a><br>");
	echo("<a href=javascript:mail('".$login."');><dd>�����</a><br>");
	echo("<a href='javascript:chat();'><dd>���</a><br>");
	echo("<a href='irc://irc.local/nl'><dd>IRC</a><br>");
	echo("<a href='game.php?action=16'><dd>�����</a><br>");
	if (isadmin($login) == 1)
	{
		echo("<a href='game.php?action=17'><dd>�����������������</a><br>");
		echo("<a href='javascript:cheats();'><dd>�������� ������</a><br>");
	}
	if (issubadmin($login) == 1)
	{
		echo("<a href='javascript:cheats();'><dd>�������� ������</a><br>");
	}

  //������ ������
  HelpMe(0, 1);
	echo("</td>");
}

//������������ �� ����
function documentation()
{
  ?>
    <script>
      window.open("help.php");
    </script>
  <?
/*
$file_array = fopen("help.php", "r");
while (!feof($file_array))
	{
	$s = fgets($file_array, 255);
	echo($s);
	}
fclose ($file_array);
*/
}

//����
function item($login, $where)
{
	if ($where == 1)
	{
		$t = 'golova';
	}
	if ($where == 2)
	{
		$t = 'shea';
	}
	if ($where == 3)
	{
		$t = 'rightruka';
	}
	if ($where == 4)
	{
		$t = 'palec';
	}
	if ($where == 5)
	{
		$t = 'telo';
	}
	if ($where == 6)
	{
		$t = 'leftruka';
	}
	if ($where == 7)
	{
		$t = 'plash';
	}
	if ($where == 8)
	{
		$t = 'tors';
	}
	if ($where == 9)
	{
		$t = 'koleni';
	}
	if ($where == 10)
	{
		$t = 'nogi';
	}
	(int)$num = getdata($login, 'items', $t);

	if (!empty($num))
	{
		$s = "<img src='".getimg($num)."' alt='".getinfo($num)."' width=60 height=60>";
	}
	else
	{
		switch ($where)
		{
			case 1:
				$s = '<img src="images/weapons/null/head.jpg" width=60 height=60>';
				break;
			case 2:
				$s = '<img src="images/weapons/null/shea.jpg" width=60 height=60>';
				break;
			case 3:
				$s = '<img src="images/weapons/null/weapon.jpg" width=60 height=60>';
				break;
			case 4:
				$s = '<img src="images/weapons/null/ring.jpg" width=60 height=60>';
				break;
			case 5:
				$s = '<img src="images/weapons/null/armor.jpg" width=60 height=60>';
				break;
			case 6:
				$s = '<img src="images/weapons/null/shit.jpg" width=60 height=60>';
				break;
			case 7:
				$s = '<img src="images/weapons/null/plash.jpg" width=60 height=60>';
				break;
			case 8:
				$s = '<img src="images/weapons/null/tors.jpg" width=60 height=60>';
				break;
			case 9:
				$s = '<img src="images/weapons/null/shitki.jpg" width=60 height=60>';
				break;
			case 10:
				$s = '<img src="images/weapons/null/nogi.jpg" width=60 height=60>';
				break;
		}
	}
	return $s;
}

//��������
function check($login)
{
//������� ��������, � ����� �� ���� ���� ������ � ������?
if (($login != $HTTP_COOKIE_VARS["nativeland"])||(empty($login))||($login == ""))
{
	echo ("<script>window.location.href('index.php');</script>");
	exit();
}
}


//����������
function equipment($login)
{
	echo("<center><h2>����������</h2>");
	echo("<table border=0 width=30% CELLSPACING=0 CELLPADDING=0>");
	echo("<tr><td colspan=3 align=center>".item($login, 1)."</td></tr>");
	echo("<tr><td colspan=3 align=center>".item($login, 2)."</td></tr>");
	echo("<tr><td align=center width=30%>".item($login, 3)."</td><td rowspan=2 align=center>".item($login, 5)."</td><td align=center width=30%>".item($login, 6)."</td></tr>");
	echo("<tr><td align=center>".item($login, 4)."</td><td align=center>".item($login, 7)."</td></tr>");
	echo("<tr><td colspan=3 align=center>".item($login, 8)."</td></tr>");
	echo("<tr><td colspan=3 align=center>".item($login, 9)."</td></tr>");
	echo("<tr><td colspan=3 align=center>".item($login, 10)."</td></tr>");
	echo("</table>");

  //���������
  $lg = $login;
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
  
  //�������� ��� ���������
  echo("<table border=1 CELLSPACING=0 CELLPADDING=0 width=10%><tr>");
  //��������� �� ���������
  if ($HMax > 0)
    echo("<td align=center><img src='images/bottles/big_h.jpg' alt='��������������� 100% ��������' width=64 height=64 border=0><br>$HMax</td>");
  if ($HMed > 0)
    echo("<td align=center><img src='images/bottles/med_h.jpg' alt='��������������� 50% ��������' width=64 height=64 border=0><br>$HMed</td>");
  if ($HMin > 0)
    echo("<td align=center><img src='images/bottles/sma_h.jpg' alt='��������������� 25% ��������' width=64 height=64 border=0><br>$HMin</td>");

  //��������� � �����
  if ($MMax > 0)
    echo("<td align=center><img src='images/bottles/big_m.jpg' alt='��������������� 100% ����' width=64 height=64 border=0><br>$MMax</td>");
  if ($MMed > 0)
    echo("<td align=center><img src='images/bottles/med_m.jpg' alt='��������������� 50% ����' width=64 height=64 border=0><br>$MMed</td>");
  if ($MMin > 0)
    echo("<td align=center><img src='images/bottles/sma_m.jpg' alt='��������������� 25% ����' width=64 height=64 border=0><br>$MMin</td>");

  //��������� � �����
  if ($PMax > 0)
    echo("<td align=center><img src='images/bottles/big_p.jpg' alt='����������� ���� �� 100%' width=64 height=64 border=0><br>$PMax</td>");
  if ($PMed > 0)
    echo("<td align=center><img src='images/bottles/med_p.jpg' alt='����������� ���� �� 50%' width=64 height=64 border=0><br>$PMed</td>");
  if ($PMin > 0)
    echo("<td align=center><img src='images/bottles/sma_p.jpg' alt='����������� ���� �� 25%' width=64 height=64 border=0><br>$PMin</td>");

  //��������� � ���������� �����
  if ($SMax > 0)
    echo("<td align=center><img src='images/bottles/big_i.jpg' alt='����������� ���������� ���� �� 100%' width=64 height=64 border=0><br>$SMax</td>");
  if ($SMed > 0)
    echo("<td align=center><img src='images/bottles/med_i.jpg' alt='����������� ���������� ���� �� 50%' width=64 height=64 border=0><br>$SMed</td>");
  if ($SMin > 0)
    echo("<td align=center><img src='images/bottles/sma_i.jpg' alt='����������� ���������� ���� �� 25%' width=64 height=64 border=0><br>$SMin</td>");

  //����� ������� ��� ���������
  echo("</tr></table>");  
  HelpMe(3, 0);
	echo("</center>");
}

//�������� ���������� �������
function getmonstr($level)
{
//	link();
	$count = 0;
	$usr = mysql_query("select * from monsters;");
	if ($usr)
		{
			while ($user = mysql_fetch_array($usr))
			{
				if (($user['level'] == $level)||($user['level'] == ($level-1))||($user['level'] == ($level-2)))
				{
					$name[$count] = $user['name'];
					$count++;
				}
			}
	    }
return $name[rand($count-1, 0)];
}

//�������� � ��� ���� �������
function addtolog($login, $info)
{

	//������ ���� ���
	$file = fopen("data/logs/".$login.".log", "r");
	for ($i = 0; $i < 13; $i++)
	{
		$st[$i+1] = fgets($file, 255);
	}
	fclose ($file);
	$tm = "(".date("<b>H:i:s</b>", time()).")";
	$st[0] = $tm." ".$info."<br>";
	$file = fopen("data/logs/".$login.".log", "w");
	for ($i = 0; $i < 12; $i++)
	{
		fputs($file, $st[$i]);
	}
	fclose($file);
}

//�������� � ��� ���� �������
function intolog($login, $log, $info)
{

	//������ ���� ���
	$file = fopen("data/".$log."/".$login, "r");
	for ($i = 0; $i < 13; $i++)
	{
		$st[$i+1] = fgets($file, 255);
	}
	fclose ($file);
	$st[0] = $info."<br>";
	$file = fopen("data/".$log."/".$login, "w");
	for ($i = 0; $i < 12; $i++)
	{
		fputs($file, $st[$i]."\n");
	}
	fclose($file);
}

//������� �����
function sendmail($login, $type)
{
	echo ("<center><table border=1 width=90% CELLSPACING=0 CELLPADDING=0>\n");
	echo ("<tr><td colspan=2 align=center><h2>������� ���������</h2>");
  HelpMe(9, 0);
  echo ("</td></tr>");
	?>
	<form name='sendmail' action='send.php' method='post'>
	<tr><td width=20%>����</td><td align=center>
	<?
	indexuserlist('to');
	echo ("<input type='hidden' name='login' value=$login>");
	echo ("<input type='hidden' name='where' value=$type>");
	?>
	</td></tr>
	<tr><td colspan=2 align=center>�����<br>
	<textarea name="txt" cols=70 rows=15 maxlength=255 style='background:#000099; font-WEIGHT: BOLD; COLOR: white; cursor: hand; list-style: lower-alpha; filter: Alpha(Opacity=20, FinishOpacity=100, Style=3)'></textarea>
	</td></tr>
	<tr><td colspan=2 align=center><input type="submit" name="send" value="���������" style='background:#000099; font-WEIGHT: BOLD; COLOR: white; cursor: hand; list-style: lower-alpha; filter: Alpha(Opacity=20, FinishOpacity=100, Style=3)'></td></tr>
	</form>
	<?
	echo ("</table></center>");
}

//������ ���������
function msg($login)
{

//������ �� ���������
change ($login, 'status', 'f2', '0');

//�������� ������ ���� ���������	
$dir_rec= dir("data/mail/".$login);
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
@rsort($names);
	

//������ ������� �� �������
echo ("<center><table width=90% border=1 CELLSPACING=0 CELLPADDING=0>");
echo ("<tr><td align=center width=20%>�����������</td><td align=center>���������</td><td align=center width=20%>�������</td></tr>");
for ($i = 0; $i < $count; $i++)
   {
   $entry = $names[$i];
//   $data = file("data/mail/".$login."/rec.".$entry);

  //������ ���� �� �����
  $data = fopen("data/mail/".$login."/rec.".$entry, "r");
  $who = fgets($data, 255);
  echo ("<tr><td align=center>");
  echo ($who."</td><td>");
  while (!feof($data))
	   {
	   echo (fgets($data, 255)."<br>");
	   }
  echo ("</td><td align=center><form action='del.php' method=post><input type='hidden' name='num' value=$entry><input type='hidden' name='login' value='$login'><br><input type='submit' value='�������' style='background:#000099; font-WEIGHT: BOLD; COLOR: white; cursor: hand; list-style: lower-alpha; filter: Alpha(Opacity=20, FinishOpacity=100, Style=3)'></form></td></tr>");
  fclose ($data);
  }
echo ("<tr><td colspan=3 align=center><form action='del.php' method=post><input type='hidden' name='num' value=0><input type='hidden' name='login' value='$login'><br><input type='submit' value='������� ���' style='background:#000099; font-WEIGHT: BOLD; COLOR: white; cursor: hand; list-style: lower-alpha; filter: Alpha(Opacity=20, FinishOpacity=100, Style=3)'></form></td></tr>");
echo ("</table></center>");	
}

//���������� �� ���������
function economic($login)
{
  function query($level, $race, $field)
  {
    $usr = mysql_query("select * from warriors;");
    $find = "";
    if ($usr)
      while ($user = mysql_fetch_array($usr))
        if (($user['level'] == $level)&&($user['race'] == $race))
          $find = $user[$field];
  	return $find;
  }

	?>
	<center>
	<table border=1 width=60% CELLSPACING=0 CELLPADDING=0>
	<tr><td colspan=2 align=center><font color=blue><b>��������� �����������</b></font></td></tr>
	<tr><td width=70% align=center><font color=blue>��������</font></td><td align=center><font color=blue>��������</font></td></tr>

	<?
	$txt = getdata($login, 'info', 'country');
	echo ("<tr><td width=20% align=center><font color=blue>�����������</font></td><td align=center><font color=blue></font>$txt</td></tr>");
	$txt = getdata($login, 'info', 'capital');
	echo ("<tr><td width=20% align=center><font color=blue>�������</font></td><td align=center><font color=blue></font>$txt</td></tr>");
	if (getdata($login, 'info', 'resource') == 'metal')
	{
		$txt = '������';
	}
	if (getdata($login, 'info', 'resource') == 'rock')
	{
		$txt = '������';
	}
	if (getdata($login, 'info', 'resource') == 'wood')
	{
		$txt = '������';
	}
	echo ("<tr><td width=20% align=center><font color=blue>�������� ������</font></td><td align=center><font color=blue></font>$txt</td></tr>");
	$txt = getdata($login, 'economic', 'moneyname');
	echo ("<tr><td width=20% align=center><font color=blue>������</font></td><td align=center><font color=blue></font>$txt</td></tr>");
	$txt = getdata($login, 'economic', 'curse');
	echo ("<tr><td width=20% align=center><font color=blue>���� ����� � �������</font></td><td align=center><font color=blue></font>$txt</td></tr>");
	$txt = getdata($login, 'economic', 'peoples');
	echo ("<tr><td width=20% align=center><font color=blue>��������� �����������</font></td><td align=center><font color=blue></font>$txt</td></tr>");
	$k = getdata($login, 'economic', 'curse')*(1 + getdata($login, 'city', 'build1') + 5*getdata($login, 'city', 'build2') + 30*getdata($login, 'city', 'build3'));
	$k = $k + getdata($login, 'city', 'build19');
	$txt = $k*24;
	echo ("<tr><td width=20% align=center>���������� ����� �����<font color=blue</font></td><td align=center><font color=blue></font>$txt</td></tr>");
	$txt = getdata($login, 'economic', 'curse')*12;
	if (getdata($login, 'city', 'build13') == 1) {$txt = 0;}
	echo ("<tr><td width=20% align=center>���������� ����� ��������� �����<font color=blue</font></td><td align=center><font color=blue></font>$txt</td></tr>");
	$txt = 24*(1 + 2*getdata($login, 'city', 'build4'));
	echo ("<tr><td width=20% align=center>���������� ����� ��������� �������<font color=blue</font></td><td align=center><font color=blue></font>$txt</td></tr>");
  $race = getdata($login, 'hero', 'race');
  $n1 = query(1, $race, 'addon');
  $n2 = query(2, $race, 'addon');
  $n3 = query(3, $race, 'addon');
  $n4 = query(4, $race, 'addon');
  $c1 = getdata($login, 'army', 'level1');
  $c2 = getdata($login, 'army', 'level2');
  $c3 = getdata($login, 'army', 'level3');
  $c4 = getdata($login, 'army', 'level4');
  echo("<tr><td width=20% align=center>���������� $n1</td><td align=center>$c1</td></tr>");
  echo("<tr><td width=20% align=center>���������� $n2</td><td align=center>$c2</td></tr>");
  echo("<tr><td width=20% align=center>���������� $n3</td><td align=center>$c3</td></tr>");
  echo("<tr><td width=20% align=center>���������� $n4</td><td align=center>$c4</td></tr>");

  //����
  $Curse = getdata($login, 'economic', 'curse');

  //������� ����� �� �����
  $Nalog = $c1 + $c2*2 + $c3*3 + $c4*4;
  $Nalog = $Nalog*$Curse;
  $Economy = Level(28, $login)*0.01;
  $Nalog = round($Nalog - $Nalog*$Economy);
  if ($Nalog < 0)
    $Nalog = 0;
  echo("<tr><td width=20% align=center>���������� ������� �� �����</td><td align=center>$Nalog</td></tr>");
  $Peoples = getdata($login, 'economic', 'peoples');
  $Peoples = round($Peoples + Level(29, $login)*0.01*$Peoples);
  echo("<tr><td width=20% align=center>���������� ������ ��������</td><td align=center>$Peoples</td></tr>");
  ?>
	</table>
	<?
  HelpMe(2, 1);
}


//�������
function nwe($login)
{

  //��������� ������ �� ������� �� ����� ����, ����� ������������ � ����� �������
  function query($level, $race, $field)
  {
    $usr = mysql_query("select * from warriors;");
    $find = "";
    if ($usr)
      while ($user = mysql_fetch_array($usr))
        if (($user['level'] == $level)&&($user['race'] == $race))
          $find = $user[$field];
  	return $find;
  }

  $race = getdata($login, 'hero', 'race');

	$t = "<center><table border=1 width=90% CELLSPACING=0 CELLPADDING=0><tr><td colspan=2 align=center><font color=blue><b>���������� ��������</b></font></td></tr><tr><td width=70% align=center><font color=blue>��������</font></td><td align=center><font color=blue>��������</font></td></tr>";

	$txt = getdata($login, 'info', 'country');
	$t = $t."<tr><td width=20% align=center><font color=blue>�����������</font></td><td align=center><font color=blue></font>".$txt."</td></tr>";
	$txt = getdata($login, 'info', 'capital');
	$t = $t."<tr><td width=20% align=center><font color=blue>�������</font></td><td align=center><font color=blue></font>".$txt."</td></tr>";
	if (getdata($login, 'info', 'resource') == 'metal')
	{
		$txt = '������';
	}
	if (getdata($login, 'info', 'resource') == 'rock')
	{
		$txt = '������';
	}
	if (getdata($login, 'info', 'resource') == 'wood')
	{
		$txt = '������';
	}
	$t = $t."<tr><td width=20% align=center><font color=blue>�������� ������</font></td><td align=center><font color=blue></font>".$txt."</td></tr>";
	$txt = getdata($login, 'economic', 'moneyname');
	$t = $t."<tr><td width=20% align=center><font color=blue>������</font></td><td align=center><font color=blue></font>".$txt."</td></tr>";
	$txt = getdata($login, 'economic', 'curse');
	$t = $t."<tr><td width=20% align=center><font color=blue>���� ����� � �������</font></td><td align=center><font color=blue></font>".$txt."</td></tr>";
	$k = rand(100, 50)/100;
	$txt = round(getdata($login, 'economic', 'peoples')*$k);
	$t = $t."<tr><td width=20% align=center><font color=blue>��������� ��������� �����������</font></td><td align=center><font color=blue></font>".$txt."</td></tr>";
  $n1 = query(1, $race, 'addon');
  $n2 = query(2, $race, 'addon');
  $n3 = query(3, $race, 'addon');
  $n4 = query(4, $race, 'addon');
  $c1 = getdata($login, 'army', 'level1');
  $c2 = getdata($login, 'army', 'level2');
  $c3 = getdata($login, 'army', 'level3');
  $c4 = getdata($login, 'army', 'level4');
  $c1 = round($c1 + $c1*rand(20, 0)/100);
  $c2 = round($c2 + $c2*rand(20, 0)/100);
  $c3 = round($c3 + $c3*rand(20, 0)/100);
  $c4 = round($c4 + $c4*rand(20, 0)/100);
	$t = $t."<tr><td width=20% align=center><font color=blue>��������� ���������� $n1</font></td><td align=center><font color=blue></font>".$c1."</td></tr>";
	$t = $t."<tr><td width=20% align=center><font color=blue>��������� ���������� $n2</font></td><td align=center><font color=blue></font>".$c2."</td></tr>";
	$t = $t."<tr><td width=20% align=center><font color=blue>��������� ���������� $n3</font></td><td align=center><font color=blue></font>".$c3."</td></tr>";
	$t = $t."<tr><td width=20% align=center><font color=blue>��������� ���������� $n4</font></td><td align=center><font color=blue></font>".$c4."</td></tr>";
  $t = $t."</table>";
  return $t;
	}

//�������������
function build($name)
{
	echo ("<center>");
	echo ("<h2>�������������</h2>");
  HelpMe(8, 0);
	echo ("<table border=1 width=98% CELLSPACING=0 CELLPADDING=0>");
	echo ("<tr><td colspan=4 align=center>�������� ������ ��� ���������</td></tr>");

	//������� ���
	for ($i = 1; $i < 20; $i ++)
	{
	$build = $i;
	$metal[$i] = round(sqrt(($build)*($build)*($build)));
	$rock[$i] = ($build)*($build);
	$wood[$i] = ($build)*5;
	$cena[$i] = $metal[$i]*getdata($name, 'economic', 'curse');
	}

	$metal[5] = 15;
	$rock[5] = 14;
	$wood[5] = 13;
	$cena[5] = $metal[5]*getdata($name, 'economic', 'curse');


	echo ("<tr><td align=center width=22%>������</td><td align=center width=50%>����������</td><td align=center width=1%>�����������</td><td align=center>����</td></tr>");
	echo ("<tr><td align=center width=20%>".getfrom('race', getdata($name, 'hero', 'race'), 'buildings', 'build1'));
	if (getdata($name, 'city', 'build1') == 0)
	{
	echo ("<form action='build.php' method='post'><input type='hidden' name='build' value=1><input type='submit' value='���������' style='background:#000099; font-WEIGHT: BOLD; COLOR: white; cursor: hand; list-style: lower-alpha; filter: Alpha(Opacity=20, FinishOpacity=100, Style=3)'><input type='hidden' name='login' value='".$name."'></form>");
	}
	echo ("</td><td align=center>����������� ����� ������ �� 5%. ����� � ���� ������ ��������� ��������, � ������� �������� �� ������� ��������� ������������ � ������ ���� ������.</td><td><img src='images/castles/".getdata($name, 'hero', 'race')."/build1.JPG' width=108 height=101></td><td align=center>������: $metal[1]<br>������: $rock[1]<br>������: $wood[1]<br>".getdata($name, 'economic', 'moneyname').": $cena[1]<br></td></tr><tr><td align=center width=20%>".getfrom('race', getdata($name, 'hero', 'race'), 'buildings', 'build2'));
	if (getdata($name, 'city', 'build2') == 0)
	{
	echo ("<form action='build.php' method='post'><input type='hidden' name='build' value=2><input type='submit' value='���������' style='background:#000099; font-WEIGHT: BOLD; COLOR: white; cursor: hand; list-style: lower-alpha; filter: Alpha(Opacity=20, FinishOpacity=100, Style=3)'><input type='hidden' name='login' value='".$name."'></form>");
	}
	echo ("</td><td align=center>����������� ����� ������ �� 10%. �����, �������� ��� ������ � ��� � ������ �������� ������ ������� ���������� �� ������� �� �������. �� ������� �������� � �����-���� ����</td><td><img src='images/castles/".getdata($name, 'hero', 'race')."/build2.JPG' width=108 height=101></td><td align=center>������: $metal[2]<br>������: $rock[2]<br>������: $wood[2]<br>".getdata($name, 'economic', 'moneyname').": $cena[2]<br></td></tr><tr><td align=center width=20%>".getfrom('race', getdata($name, 'hero', 'race'), 'buildings', 'build3'));
	if (getdata($name, 'city', 'build3') == 0)
	{
	echo ("<form action='build.php' method=post><input type='hidden' name='build' value=3><input type='submit' value='���������' style='background:#000099; font-WEIGHT: BOLD; COLOR: white; cursor: hand; list-style: lower-alpha; filter: Alpha(Opacity=20, FinishOpacity=100, Style=3)'><input type='hidden' name='login' value='".$name."'></form>");
	}
	echo ("</td><td align=center>����������� ����� ������ �� 15%</td><td><img src='images/castles/".getdata($name, 'hero', 'race')."/build3.JPG' width=108 height=101></td><td align=center>������: $metal[3]<br>������: $rock[3]<br>������: $wood[3]<br>".getdata($name, 'economic', 'moneyname').": $cena[3]<br></td></tr><tr><td align=center width=20%>".getfrom('race', getdata($name, 'hero', 'race'), 'buildings', 'build4'));
	if (getdata($name, 'city', 'build4') == 0)
	{
	echo ("<form action='build.php' method=post><input type='hidden' name='build' value=4><input type='submit' value='���������' style='background:#000099; font-WEIGHT: BOLD; COLOR: white; cursor: hand; list-style: lower-alpha; filter: Alpha(Opacity=20, FinishOpacity=100, Style=3)'><input type='hidden' name='login' value='".$name."'></form>");
	}
	echo("</td><td align=center>����������� ����� ������� �� 2</td><td><img src='images/castles/".getdata($name, 'hero', 'race')."/build4.JPG' width=108 height=101></td><td align=center>������: $metal[4]<br>������: $rock[4]<br>������: $wood[4]<br>".getdata($name, 'economic', 'moneyname').": $cena[4]<br></td></tr><tr><td align=center width=20%>".getfrom('race', getdata($name, 'hero', 'race'), 'buildings', 'build5'));
	if (getdata($name, 'city', 'build5') == 0)
	{
	echo ("<form action='build.php' method=post><input type='hidden' name='build' value=5><input type='submit' value='���������' style='background:#000099; font-WEIGHT: BOLD; COLOR: white; cursor: hand; list-style: lower-alpha; filter: Alpha(Opacity=20, FinishOpacity=100, Style=3)'><input type='hidden' name='login' value='".$name."'></form>");
	}
	echo ("</td><td align=center>��������� ��� ��������� ��������� � ������� ��������</td><td><img src='images/castles/".getdata($name, 'hero', 'race')."/build5.JPG' width=108 height=101></td><td align=center>������: $metal[5]<br>������: $rock[5]<br>������: $wood[5]<br>".getdata($name, 'economic', 'moneyname').": $cena[5]<br></td></tr><tr><td align=center width=20%>".getfrom('race', getdata($name, 'hero', 'race'), 'buildings', 'build6'));
	if (getdata($name, 'city', 'build6') == 0)
	{
	echo ("<form action='build.php' method=post><input type='hidden' name='build' value=6><input type='submit' value='���������' style='background:#000099; font-WEIGHT: BOLD; COLOR: white; cursor: hand; list-style: lower-alpha; filter: Alpha(Opacity=20, FinishOpacity=100, Style=3)'><input type='hidden' name='login' value='".$name."'></form>");
	}
	echo ("</td><td align=center>����� �� ������� �������� ��������� ��� ������ ���������</td><td><img src='images/castles/".getdata($name, 'hero', 'race')."/build6.JPG' width=108 height=101></td><td align=center>������: $metal[6]<br>������: $rock[6]<br>������: $wood[6]<br>".getdata($name, 'economic', 'moneyname').": $cena[6]<br></td></tr><tr><td align=center width=20%>".getfrom('race', getdata($name, 'hero', 'race'), 'buildings', 'build7'));
	if (getdata($name, 'city', 'build7') == 0)
	{
	echo ("<form action='build.php' method=post><input type='hidden' name='build' value=7><input type='submit' value='���������' style='background:#000099; font-WEIGHT: BOLD; COLOR: white; cursor: hand; list-style: lower-alpha; filter: Alpha(Opacity=20, FinishOpacity=100, Style=3)'><input type='hidden' name='login' value='".$name."'></form>");
	}
	echo ("</td><td align=center>����� �� ������� ������ ������ ���������</td><td><img src='images/castles/".getdata($name, 'hero', 'race')."/build7.JPG' width=108 height=101></td><td align=center>������: $metal[7]<br>������: $rock[7]<br>������: $wood[7]<br>".getdata($name, 'economic', 'moneyname').": $cena[7]<br></td></tr><tr><td align=center width=20%>".getfrom('race', getdata($name, 'hero', 'race'), 'buildings', 'build8'));
	if (getdata($name, 'city', 'build8') == 0)
	{
	echo ("<form action='build.php' method=post><input type='hidden' name='build' value=8><input type='submit' value='���������' style='background:#000099; font-WEIGHT: BOLD; COLOR: white; cursor: hand; list-style: lower-alpha; filter: Alpha(Opacity=20, FinishOpacity=100, Style=3)'><input type='hidden' name='login' value='".$name."'></form>");
	}
	echo ("</td><td align=center>����� �� ������� �������� ���������� ������� ������</td><td><img src='images/castles/".getdata($name, 'hero', 'race')."/build8.JPG' width=108 height=101></td><td align=center>������: $metal[8]<br>������: $rock[8]<br>������: $wood[8]<br>".getdata($name, 'economic', 'moneyname').": $cena[8]<br></td></tr><tr><td align=center width=20%>".getfrom('race', getdata($name, 'hero', 'race'), 'buildings', 'build9'));
	if (getdata($name, 'city', 'build9') == 0)
	{
	echo ("<form action='build.php' method=post><input type='hidden' name='build' value=9><input type='submit' value='���������' style='background:#000099; font-WEIGHT: BOLD; COLOR: white; cursor: hand; list-style: lower-alpha; filter: Alpha(Opacity=20, FinishOpacity=100, Style=3)'><input type='hidden' name='login' value='".$name."'></form>");
	}
	echo ("</td><td align=center>����� �� ������� �������� ���������� ������� ������</td><td><img src='images/castles/".getdata($name, 'hero', 'race')."/build9.JPG' width=108 height=101></td><td align=center>������: $metal[9]<br>������: $rock[9]<br>������: $wood[9]<br>".getdata($name, 'economic', 'moneyname').": $cena[9]<br></td></tr><tr><td align=center width=20%>".getfrom('race', getdata($name, 'hero', 'race'), 'buildings', 'build10'));
	if (getdata($name, 'city', 'build10') == 0)
	{
	echo ("<form action='build.php' method=post><input type='hidden' name='build' value=10><input type='submit' value='���������' style='background:#000099; font-WEIGHT: BOLD; COLOR: white; cursor: hand; list-style: lower-alpha; filter: Alpha(Opacity=20, FinishOpacity=100, Style=3)'><input type='hidden' name='login' value='".$name."'></form>");
	}
	echo ("</td><td align=center>��� ������ ���������� ��� ��� �������� ����� ������ �������</td><td><img src='images/castles/".getdata($name, 'hero', 'race')."/build10.JPG' width=108 height=101></td><td align=center>������: $metal[10]<br>������: $rock[10]<br>������: $wood[10]<br>".getdata($name, 'economic', 'moneyname').": $cena[10]<br></td></tr><tr><td align=center width=20%>".getfrom('race', getdata($name, 'hero', 'race'), 'buildings', 'build11'));
	if (getdata($name, 'city', 'build11') == 0)
	{
	echo ("<form action='build.php' method=post><input type='hidden' name='build' value=11><input type='submit' value='���������' style='background:#000099; font-WEIGHT: BOLD; COLOR: white; cursor: hand; list-style: lower-alpha; filter: Alpha(Opacity=20, FinishOpacity=100, Style=3)'><input type='hidden' name='login' value='".$name."'></form>");
	}
	echo ("</td><td align=center>�������� ��� ������, �� ������� �������� ������� � ������ �������</td><td><img src='images/castles/".getdata($name, 'hero', 'race')."/build11.JPG' width=108 height=101></td><td align=center>������: $metal[11]<br>������: $rock[11]<br>������: $wood[11]<br>".getdata($name, 'economic', 'moneyname').": $cena[11]<br></td></tr><tr><td align=center width=20%>".getfrom('race', getdata($name, 'hero', 'race'), 'buildings', 'build12'));
	if (getdata($name, 'city', 'build12') == 0)
	{
	echo ("<form action='build.php' method=post><input type='hidden' name='build' value=12><input type='submit' value='���������' style='background:#000099; font-WEIGHT: BOLD; COLOR: white; cursor: hand; list-style: lower-alpha; filter: Alpha(Opacity=20, FinishOpacity=100, Style=3)'><input type='hidden' name='login' value='".$name."'></form>");
	}
	echo ("</td><td align=center>����� �� ������� ������ �������� � ������� ��������</td><td><img src='images/castles/".getdata($name, 'hero', 'race')."/build12.JPG' width=108 height=101></td><td align=center>������: $metal[12]<br>������: $rock[12]<br>������: $wood[12]<br>".getdata($name, 'economic', 'moneyname').": $cena[12]<br></td></tr><tr><td align=center width=20%>".getfrom('race', getdata($name, 'hero', 'race'), 'buildings', 'build13'));
	if (getdata($name, 'city', 'build13') == 0)
	{
	echo ("<form action='build.php' method=post><input type='hidden' name='build' value=13><input type='submit' value='���������' style='background:#000099; font-WEIGHT: BOLD; COLOR: white; cursor: hand; list-style: lower-alpha; filter: Alpha(Opacity=20, FinishOpacity=100, Style=3)'><input type='hidden' name='login' value='".$name."'></form>");
	}
	echo ("</td><td align=center>�������� ��� ������, �� ������� ��������� ��������� ���� �����</td><td><img src='images/castles/".getdata($name, 'hero', 'race')."/build13.JPG' width=108 height=101></td><td align=center>������: $metal[13]<br>������: $rock[13]<br>������: $wood[13]<br>".getdata($name, 'economic', 'moneyname').": $cena[13]<br></td></tr>");	
	echo ("</table>");
}

//�����
function unions($login)
{
	echo ("<center><h2>�����</h2>");
	echo ("<table border=1 width=50% CELLSPACING=0 CELLPADDING=0>");
	echo ("<form action=game.php method=post>");
	echo ("<tr><td align=center width=40%>����� ��������</td><td align=center>��� �� ��</td><td align=center>�������</td></tr>");
	echo ("<tr><td align=center width=40%>1</td>");
	echo("<td align=center>");
	indexuserlist('u1');
	echo("</td><td align=center>".getdata($login, 'unions', 'login2')."</td></tr>");
	echo ("<tr><td align=center width=40%>2</td><td align=center>");
	indexuserlist('u2');
	echo("</td><td align=center>".getdata($login, 'unions', 'login3')."</td></tr>");
	echo ("<tr><td align=center width=40%>3</td><td align=center>");
	indexuserlist('u3');
	echo("</td><td align=center>".getdata($login, 'unions', 'login4')."</td></tr>");
	echo ("<tr><td align=center width=40%>4</td><td align=center>");
	indexuserlist('u4');
	echo("</td><td align=center>".getdata($login, 'unions', 'login5')."</td></tr>");
	echo ("<tr><td colspan=3 align=center><br><input type='submit' value='  �������  ' style='background:#000099; font-WEIGHT: BOLD; COLOR: white; cursor: hand; list-style: lower-alpha; filter: Alpha(Opacity=20, FinishOpacity=100, Style=3)'><br><br></td></tr>");
//	echo ("<input type='hidden' name='action' value=30>");
	echo ("</form></table></center>");
}

//��� �����
function army($login)
{
	readfile('army.php');
	echo ("<center>���� �� 1 �����: ".(getdata($login, 'economic', 'curse')*5)." ".getdata($login, 'economic', 'moneyname')."</center>");
}

//���������
function attack($login)
{
	echo ("<center><h2>������� ����</h2><table border=1 width=40% CELLSPACING=0 CELLPADDING=0>");
	echo ("<tr><td align=center>�� ���� �������</td><td align=center>");
	echo ("<form action='game.php' method=post><input type='hidden' name='action' value=34>");
	indexuserlist("users");
	echo ("</td></tr><tr><td colspan=2 align=center><input type='submit' value = ' ������� ����� ' style='background:#000099; font-WEIGHT: BOLD; COLOR: white; cursor: hand; list-style: lower-alpha; filter: Alpha(Opacity=20, FinishOpacity=100, Style=3)'></td></tr>");
	echo ("</form></table></center>");
}

function moveto($page)
{
   echo ("<script>window.location.href('".$page."');</script>");
}

//������ �� ���������� ������
function lostpwd($username, $surname, $email, $country, $hero)
{
//link();
$usr = mysql_query("select * from users;");
$find = 0;
if ($usr)
   {
   while ($user = mysql_fetch_array($usr))
      {
      if (($user['login'] == $username)&&($user['surname'] == $surname)&&($user['email'] == $email)&&($user['country'] == $country))
         {
         $find = 1;
		 }
	  }
   }
   else
	{
	   $find = 2;
	}

//�������� ��� �����
if (getdata($username, 'hero', 'name') != $hero)
	{
		$find = 2;
	}

//�� ���������? - ������� ������
echo ("<title>Native Land</title>");
echo ("<link rel='stylesheet' type='text/css' href='style.css'/>");
echo ("<center><h2>������� �������������� ������</h2>");
//echo ("��� ������ ������ �� ��� email �����, ������� �� ��������� ��� �����������<br>");
//echo ("���� �� �����-���� ������� �� �� ������ �������� ���, �� ��������� � ���������������<br>");
//echo ("�� ������� ������ ������ :)<br>");

//���������� ���������
$msg = "��� ������������: <b>".$username."</b><br>������: <b>".getdata($username, 'users', 'pwd')."</b><br>�� ������� ������ ������ :)";

//��������� �������
echo ($msg);
echo ("<br><a href='index.php'>�����</a><br>");
echo ("</center>");

exit();
}

//������ ���:
function fromlog($name)
{
	$txt = "";
	$file = fopen($name, "r");
	while (!feof($file))
	{
		$txt = $txt.fgets($file, 255);
	}
	fclose ($file);
	return $txt;
}

//��� ���
function step($name)
{
	$file = fopen("data/logs/".$name.".log", "r");
	$who = fgets($file, 255);
	fclose ($file);
	return $who;
}

//��������� �����
function randomplace($lgn)
{
	//������� �������� ��������� ������
	$ok = 0;
	while ($ok == 0)
	{
		//������� ��������� ����������
		$rx = getfrom('admin', 'Settings', 'settings', 'f2');
		$ry = getfrom('admin', 'Settings', 'settings', 'f3');
    $rx++;
    if ($rx == 21)
    {
      $rx = 1;
      $ry++;
    }
    if ($ry == 21)
    {
      $rx = 1;
      $ry = 1;
    }
  
    //���������� ����� ����������
		setto("admin", "Settings", "settings", "f2", $rx);
		setto("admin", "Settings", "settings", "f3", $ry);

  	//������ �����...
		$fp = 0;
		$file = fopen("maps/".$rx."x".$ry.".map", "r");
		for ($x = 1; $x < 11; $x++)
			for ($y = 1; $y < 11; $y++)
			{

				//������ ������
				$map[$x][$y] = "0*0=0";
				$map[$x][$y] = fgets($file, 255);
				$fld = $map[$x][$y];

				//���� ������ ��������
				if (($fld[0] != '4')&&($fld[2] == '0')&&($fld[4] == '0'))
					$fp++;
			}
		fclose($file);

		//���� ���������� ������ ����
		if ($fp != 0)
		{
			//���������� ��������� ����� �� ������ ��������
			$pk = 0;
			while ($pk == 0)
			{
				//��������� �����
				$cx = rand(10, 1);
				$cy = rand(10, 1);
				$fld = $map[$cx][$cy];

				//���� ��� ��������, �������������� ���.
				if (($fld[0] == '0')&&($fld[2] == '0')&&($fld[4] == '0'))
				{
					//��������� ������ ����
					$map[$cx][$cy] = $fld[0]."*".$fld[2]."=".$lgn."\n";

					//������� � ���� ���������� � �������
					mysql_query("insert into coords values ('".$lgn."', '".$rx."', '".$ry."', '".$cx."', '".$cy."');");

					//������� � ���� ���������� � �������
					mysql_query("insert into capital values ('".$lgn."', '".$rx."', '".$ry."', '".$cx."', '".$cy."');");

  				//��������� ���������
					$pk = 1;
					$ok = 1;
				}
			}
		}
	}
}

//������� ������� �� �����
function placeplayers($login)
{
	//������ ������������ �����
	if ($login == 'Admin')
	{
		//�������� ���� �������������
		$ath = mysql_query("select * from users;");

		//��� ������� ������������ ���� ���������� ����� �� �����
		if ($ath)
		{
			//��� �������
			while ($rw = mysql_fetch_row($ath))
			{
				//��� ������������
				$lgn = $rw[0];
				randomplace($lgn);
			}
		}
	}
}

//�������� ���������� �������
function randommonster()
{
  //����� �������� � ����
  $num = mysql_query("select count(*) from monsters;");
  $total = mysql_fetch_array($num);

  //�������� ����������
  $num = rand($total[0], 1);

  //������� ��� � ����
  $fnd = mysql_query("select * from monsters;");
  for ($i = 1; $i <= $num; $i++)
    $monster = mysql_fetch_array($fnd);

  //�������� ����������
  $num = rand(32, 1);

  //������� ��� � ����
  $fnd = mysql_query("select * from allitems;");
  for ($i = 1; $i <= $num; $i++)
    $sword = mysql_fetch_array($fnd);

  //���
  $num = rand(43, 34);

  //������� ��� � ����
  $fnd = mysql_query("select * from allitems;");
  for ($i = 1; $i <= $num; $i++)
    $shield = mysql_fetch_array($fnd);

  //���������� ����������
  $name   = $monster[0];
  $id     = $monster[2];
  $level  = $monster[3];
  $weapon = $sword[1];
  $armor  = $shield[1];

  //��������� ������� ����������
  $rx = rand(20, 1);
  $ry = rand(20, 1);
  $x  = rand(10, 1);
  $y  = rand(10, 1);

  //��������� �� ������� �� �����
  $AddToMap = 1;

  //���������, �� ���� �� �����
	$file = fopen("maps/".$rx."x".$ry.".map", "r");

  //�����
	for ($i = 1; $i < 11; $i++)
	{
		//����� ������ �������
		for ($j = 1; $j < 11; $j++)
		{
			//�������� ������ �� ������
			$fld = fgets($file, 255);
      if (($i == $x)&&($j == $y))
        if ($fld[0] == 4)
          $AddToMap = 0;
    }
  }
  fclose($file);

  //������� ������� � ����
  if ($AddToMap != 0)
    mysql_query("insert into random values('$name', '$level', '$id', '$x', '$y', '$rx', '$ry', '$weapon', '$armor');");

  //�����������
  //echo($name." (".$weapon." � ".$armor."). �� ���������: $rx x $ry ($x x $y)<br>");
}

//���������� ������ ������� ����� ��� ������
function uarmy($name)
{
	//���������� ������� ����� ����� ����
	$army = 0;

	//���������� ���������
	$n[0] = getdata($name, 'unions', 'login2');
	$n[1] = getdata($name, 'unions', 'login3');
	$n[2] = getdata($name, 'unions', 'login4');
	$n[3] = getdata($name, 'unions', 'login5');

	//��������� �� �������
	for ($i = 0; $i < 4; $i++)
		for ($j = 0; $j < 4; $j++)
		{
			if (($n[$i] == $n[$j])&&($i != $j))
			{
				$n[$i] = $name;
			}
		}

	//������� ������� �����
	for ($i = 0; $i < 4; $i++)
	{
		//���� ����� �������� ����� �����, �� ������
		if ($n[$i] != $name)
		{
			//� ��������� �� �� �� ������������������ ����� � ���� �������
			for ($k = 0; $k < 4; $k++)
			{
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
					$uar[$i][$j] = $n[$i];
				}
			}
		}
	}

	//������� ���������� �� ������������ ������
	for ($i = 0; $i < 4; $i++)
	{
		for ($j = 0; $j < 4; $j++)
		{
			//���� ����� � ����� � ���� ����� �� ��� ��
			if (($uar[$i][$j] == $name)&&($n[$i] != $name))
			{
				$army = $army + getdata($n[$i], 'economic', 'peoples')*getdata($n[$i], 'hero', 'level');
			}
		}
	}
	return $army;
}

//���������
//����� �������
function messagebox($txt, $back)
{
	echo ("<title>���������</title>");
	echo ("<link rel='stylesheet' type='text/css' href='style.css'/><body background='images/back.jpe'>");
	echo ("<center>$txt</center>");
  echo ("<center><form action='$back' method=post>");
  ?>
    <input type='submit' value='������' style='background:#000099; font-WEIGHT: BOLD; COLOR: white; cursor: hand; list-style: lower-alpha; filter: Alpha(Opacity=20, FinishOpacity=100, Style=3)'>
  <?
  echo ("</form></center>");
	exit();
}

//������������� ������
function ban()
{
	echo ("<img class=z src=http://active.mns.ru/banner/show.php width=0 height=0 left=-100 top=-10>\n");
}

//������� �������� �������� �� ��������� �� ������ � ������ (�������: 1 - �����; 0 - �������)
function PopItem($Login, $Number)
{
  //���� ���� � ���������
  for($i = 16; $i >= 1; $i--)
    if (getdata($Login, 'inventory', 'inv'.$i) == $Number)
    {
      change($Login, 'inventory', 'inv'.$i, '0');
      return 1;
    }

  return 0;
}

//������� ���������� �������� � ��������� �� ������ � ������ (�������: 1 - �����; 0 - �������)
function PushItem($Login, $Number)
{
  //������� ����������, ���� �� ��������� ����� � ���������
  $Count = 0;
  $Place = 0;
  for($i = 16; $i >= 1; $i--)
    if (getdata($Login, 'inventory', 'inv'.$i) == 0)
    {
      $Place = $i;
      $Count++;
    }

  //���� ����� ����, �� ��������� ������� ����
  if ($Count != 0)
  {
    //���������� �������
    change($Login, 'inventory', 'inv'.$Place, $Number);
  }
  else
    return 0;
  return 1;
}

//������ ����
function showblock($num, $name, $pass, $who, $location, $temptxt)
{
	switch ($num)
	{
		//�������� ������������
		case -1:
			documentation();
			break;
		
		//���������� � ���������
		case 1:
			heroinfo($name);
			break;

		//����������� ���������
		case 2:
			equipment($name);
			break;

		//� �����
		case 3:
			echo("<script>");
			echo("window.open('city.php?login=$name', null,'toolbar=no, location=no, menubar=no,scrollbars=yes,width=656,height=480');");
			echo("window.location.href('game.php?action=8');");
			echo("</script>");
			break;

		//������� ������� �� ��������
		case 4:
				battle($name);
			break;

		//������� �������� �� ��������
		case 5:
				//� ����?
				echo ("<center>");
				echo ("<table border=1 width=60% CELLSPACING=0 CELLPADDING=0>");
				echo ("<form action='game.php' method=post><tr><td  align=center>����� �� ��������</td></tr>");
				echo ("<tr><td align=center><br>");
				indexuserlist('beet');
				echo ("<br><input type='hidden' name='newbattle' value='1'><input type='submit' value=' ������� ' style='background:#000099; font-WEIGHT: BOLD; COLOR: white; cursor: hand; list-style: lower-alpha; filter: Alpha(Opacity=20, FinishOpacity=100, Style=3)'></form></td><tr><td>");
				allusers($name);
				echo ("</td></tr></table>");
        HelpMe(7, 0);
        echo("</center>");
			break;

		//�������������
		case 7:
      //��� ����� �� �������?
      $ltime = getdata($name, 'temp', 'param');
      $delta = time()-getdata($name, 'temp', 'param');
      $delta = round($delta / 3600);
      $hour = 24 - $delta;
      if ($hour < 0)
        $hour = 0;
      if ($delta > 24)
      {
			  //� � ���� �� �� ������?
  			if ($name == $location)
    			build($name);
  			else
     			echo ("<center>�� ���������� � ����� ������. ����� ������ ������ �������.</center>");
      } else //����� �� ������ ���
    		echo ("<center>����� ��������� ����������. �� �� ����� ���������� ��� $hour �����</center>");
			break;

		//���������� �� ���������
		case 8:
			economic($name);
			break;

    //������� ���������
		case 12:
			sendmail($name, 0);
			break;

		//��������
		case 14:
			if (getdata($name, 'city', 'build11') != 0)
				{
				spy($name);
				} else
					{
					echo("<center>� ��� �� ��������� ������ �������� ������� ���������� ��� ���������!</center>");
					}
			break;

		//���������
		case 15:
			if (getdata($name, 'city', 'build13') != 0)
				{
				attack($name);
				} else
					{
					echo("<center>� ��� �� ���������(�) ".getfrom('race', getdata($name, 'hero', 'race'), 'buildings', 'build13').". ������� ���������� ��������� ������!</center>");
					}
			break;

		//�����������������
		case 17:
			admin($name, $pass, $who);
			break;

		//��������� ������ ����� �����
		case 18:
			echo ("<center>��� �� �� ������ ������� �����������. ��������� ���� ���� �������� �������������� �� �����������. ��� ������ ����� � ���� ��� ���� ����� �������� ��� �������</center>");
			break;

		//�������� ���������
		case 19:
      echo("<script>\n");
      echo("function hinfo(n)\n");
      echo(" {\n");
      echo(" window.open('info.php?name=' + n);\n");
      echo(" }\n");
      echo("</script>\n");

			msg($name);
			break;

		//�������� ������
		case 29:
			echo ("<center>� ���� '�������' �� ������ ������ ������ �����, ������ ���������������!</center>");
			break;

		//���������
		case 31:
			echo ($temptxt);
			break;

    //���������
		case 80:
      include "inventory.php";
			break;
	}
}

?>