<?

include "functions.php";

//������� ������
Error_Reporting(E_ALL & ~E_NOTICE);

//��������� ������
function addzone($rx, $ry, $name, $zone)
{
	mysql_query ("insert into map values ('$rx', '$ry', '$name', '$zone');");
}

//��������� �����
function addwarrior($name, $level, $race, $power, $protect, $archery, $arrows, $health, $img, $addon)
{
	mysql_query ("insert into warriors values ('$name', '$health', '$power', '$protect', '$archery', '$arrows', '$img', '$race', '$level', '$addon');");
}

//��������� �������
function addevent($num, $name, $effect, $how)
{
	mysql_query ("insert into events values ('$num', '$name', '$effect', '$how');");
}

if (!empty($final))
{

//�������� ����� ����� � ������������ e-mail ������ � �������� �������� ���� �����
$ohno = 0;
$err = 0;
$ml = 0;
if ((strlen($login) < 2)||(strlen($passwd) < 2)||(strlen($surname) < 2)||(strlen($mname) < 2)||(strlen($country) < 2)||(strlen($city) < 2)||(strlen($email) < 2)||(strlen($url) < 2)||(strlen($heroname) < 2)||(strlen($race) < 2)||(strlen($type) < 2)||(strlen($moneyname) < 2)||(strlen($curse) < 1)||(strlen($gcountry) < 2)||(strlen($gcapital) < 2)||(strlen($res) < 2))
   {
   $ohno = 1;
   }

//��������� e-mail �� ������������
if (!empty($email))
	{
	if (!preg_match("/[0-9a-z]+@[0-9a-z_^\.]+\.[a-z]{2,3}/i", $email))
		{
		$ml = 1;
		}
	}

//��������� ����
if (!empty($email))
	{
	if (!preg_match("/[0-9]/i", $curse))
		{
		$err = 1;
		}
	}

//���� ������?
if (($ohno != 0)||($err != 0)||($ml != 0))
	{
    echo ("<br>");
    echo ("<script language=JavaScript>");
    echo ("function rt()");
    echo ("{");
    echo ("window.location.href('install.php?start=09');");
    echo ("}");
    echo ("</script>");
	echo ("<body background='images\back.jpe'>");
	echo ("<font color=green>������. �� ��� ���� ��������� ��� ����� ������ �� ����� ������ ���� ��������. ��������� ��� ����.<br><br>");
    if ($ml != 0)
		{
		echo ("����� ��������� ������������ ���������� e-mail ������.");
		}
    if ($err != 0)
		{
		echo ("� ���� '����' ����� ���� ������ �����!");
		}
	?>
    <form action="javascript:rt();">
    <input type='submit' name='stop' value='  �����  ' style='background:#000099; font-WEIGHT: BOLD; COLOR: white; cursor: hand; list-style: lower-alpha; filter: Alpha(Opacity=20, FinishOpacity=100, Style=3)'>      
	</form>
    <?
	exit();
	}

$final = "";
$file = fopen("config.ini.php", "r");
$temp = fgets($file, 255);
$temp = fgets($file, 255);
$host = fgets($file, 255);
$db = fgets($file, 255);
$log = fgets($file, 255);
$pwd = fgets($file, 255);
fclose($file);

$host = trim($host);
$log = trim($log);
$pwd = trim($pwd);
$db = trim($db);

$db_connect = @mysql_connect($host, $log, $pwd);
mysql_select_db($db, $db_connect);

$usr = mysql_query("select * from users;");
if ($usr)
   {
   while ($user = mysql_fetch_array($usr))
	   {
       if ($user['login'] == $login)
		   {
		   $final = "";
		   $start = 46;
  		   echo ("<br>");
           echo ("<script language=JavaScript>");
	       echo ("function rt()");
	       echo ("{");
	       echo ("window.location.href('install.php?start=46');");
	       echo ("}");
	       echo ("</script>");
		   echo ("<body background='images\back.jpe'>");
	       echo ("<font color=green>��������! ������������ � ����� ������ ��� ���������������. �������� '��� ������������'</font><br><br>");
           ?>
	       <form action="javascript:rt();">
           <input type='submit' name='back' value='  �����  ' style='background:#000099; font-WEIGHT: BOLD; COLOR: white; cursor: hand; list-style: lower-alpha; filter: Alpha(Opacity=20, FinishOpacity=100, Style=3)'>      
	       </form>
           <?
		   exit();
		   }
	   }
   }
   else
	{
	   $final = "";
	   $start = 348;
 	   echo ("<br>");
       echo ("<script language=JavaScript>");
	   echo ("function rt()");
	   echo ("{");
	   echo ("window.location.href('install.php?start=348');");
	   echo ("}");
	   echo ("</script>");
	   echo ("<body background='images\back.jpe'>");
	   echo ("������. ���������� ������������ � ���� ������. ���������� ��� ���.<br><br>");
       ?>
	   <form action="javascript:rt();">
       <input type='submit' name='back' value='  �����  ' style='background:#000099; font-WEIGHT: BOLD; COLOR: white; cursor: hand; list-style: lower-alpha; filter: Alpha(Opacity=20, FinishOpacity=100, Style=3)'>       
	   </form>
       <?
	   exit();
	}

//����������� �������� ����.
$val = 0;
$curse = (int)$curse;

if ($curse < 1)
	{
	$curse = 1;
	}
(int)$r1 = $curse*20;
$r2 = 20;
$r3 = 20;
$r4 = 20;
$p = 25;
$n = 5;
$hl = 100;
$lv = 1;

//���������� � ������ ������
mysql_query ("insert into battles values('$login', 0, '0', 0);");

//���������� ������
mysql_query ("insert into inf values('$login', '0', '0', '0', '0', '0', '0', '0', '0', '0', '0', '0', '0', '0');");

//���������� �� IP �������
mysql_query("insert into ip values ('$login', '$REMOTE_ADDR');");

//�����������
mysql_query ("insert into lostpass values('$login', '$cquest', '$cansw');");

//��������� ���������� �� ������
mysql_query("insert into users values ('$login', '$passwd', '$surname', '$mname', '$city', '$country', '$email', '$url');", $db_connect);

//��������� ���������� �� ��������� ������
mysql_query("insert into hero values ('$login', '$heroname', '$val', '$lv', '$val', '$race', '$type', '$hl', '$login');", $db_connect);

//��������� ���������� �� ��������� �����������
mysql_query("insert into economic values ('$login', '$r1', '$r2', '$r3', '$r4', '$curse', '$moneyname', '$p', '$n');", $db_connect);

//��������� ���������� � ����������� ������
mysql_query("insert into magic values ('$login', 0, 0, 0, 0, 0, 0);", $db_connect);

//��������� ���������� � ����� ������
mysql_query("insert into unions values ('$login', '$login', '$login', '$login', '$login');", $db_connect);

//��������� ���������� � ����� �� ��������� (���������)
mysql_query("insert into items values ('$login', '$val', '$val', '$val', '$val', '$val', '$val', '$val', '$val', '$val', '$val', '$val');", $db_connect);

//��������� ���������� � �����������
mysql_query("insert into info values ('$login', '$gcountry', '$gcapital', '$res');", $db_connect);

//��������� ���������� � ���������� � �����
mysql_query("insert into city values ('$login', '$login', '$val', '$val', '$val', '$val', '$val', '$val', '$val', '$val', '$val', '$val', '$val', '$val', '$val', '$val', '$val', '$val', '$val', '$val', '$val', '$val');", $db_connect);

//��������� ���������� � ��������� ����������
mysql_query("insert into temp values ('$login', '', '');", $db_connect);

//��������� ���������� � �������
mysql_query("insert into status values ('$login', '0', '0', '0', '0', '0');", $db_connect);

//��������� ���������� � �������
$t = time();
mysql_query("insert into time values ('$login', '$t', '10', '10');", $db_connect);

//���������� � ����������� �������
mysql_query ("insert into capital values('$login', 0, 0, 0, 0);");

//��������� ����� ��� �������
randomplace($login);

//������ �����...
$file = fopen("data/trade/".$login, "w");
fclose($file);
$file = fopen("data/mail/".$login, "w");
fclose($file);
$file = fopen("data/logs/".$login.".log", "w");
fclose($file);

//����������� ����������� ������
$power = 1;
$protect = 1;
$magicpower = 1;
$know = 1;
$charism = 1;
$dexterity = 1;
$intel = 1;
$naturemagic = 1;
$combatmagic = 1;
$mindmagic = 1;

switch ($race)
	{
	case "people":
		$power++;
     	$protect++;
		$dexterity++;
		$charism++;
		$intel++;
		break;
	case "elf":
		$power++;
	    $dexterity++;
		$dexterity++;
		$charism++;
		$intel++;
		break;
	case "hnom":
		$power++;
    	$protect++;
		$protect++;
		$dexterity++;
		$intel++;
		break;
	case "druid":
		$protect++;
	    $know++;
	    $know++;
	    $know++;
		$intel++;
		break;
	case "necro":
		$power++;
		$power++;
    	$know++;
		$know++;
		$intel++;
		$intel++;
		$charism--;
		break;
	case "hell":
		$power++;
		$protect++;
		$protect++;
		$know++;
		$know++;
		$intel++;
		$charism--;
		break;
	}

switch ($type)
	{
	case "knight":
		$power++;
		$power++;
		$protect++;
		$protect++;
		$dexterity++;
		break;
	case "archer":
		$power++;
		$power++;
		$protect++;
		$dexterity++;
		$dexterity++;
		break;
	case "mag":
		$combatmagic++;
		$combatmagic++;
		$naturemagic++;
		$naturemagic++;
		$mindmagic++;
		break;
	case "lekar":
		$combatmagic++;
		$naturemagic++;
		$naturemagic++;
		$naturemagic++;
		$mindmagic++;
		break;
	case "barbarian":
		$power++;
		$power++;
		$power++;
		$protect++;
		$protect++;
		break;
	case "wizard":
		$combatmagic++;
		$mindmagic++;
		$mindmagic++;
		$naturemagic++;
		$mindmagic++;
		break;
	}

//��������� ���������� � �����
mysql_query("insert into abilities values ('$login', '$power', '$protect', '$magicpower', '$know', '$charism', '$dexterity', '$intel', '$naturemagic', '$combatmagic', '$mindmagic');", $db_connect);

//������� �����
$tm = time();

//������� �������� ������ � ������� ������ 
mysql_query ("insert into settings values ('$login', '$login', '1', '0', '0', '1');");
mysql_query ("insert into settings values ('Settings', '$tm', '1', '1', '0', '0');");

//���� �� ���������
echo ("<br>");
echo ("<script language=JavaScript>");
echo ("function rt()");
echo ("{");
echo ("window.location.href('index.php');");
echo ("}");
echo ("</script>");
echo ("<body background='images\back.jpe'>");
echo ("<h2><font color=#3399FF>�����������!</font></h2>");
echo ("<font color=green>��������� ������� ���������. ������� ������ '��������� ��������� ��� �������� � ������ ����.'</font><br><br>");
echo ("���� �� ������ ������ ������� ������ ���������. ������ ���� � �����������<br>");
?>

<table border=1 width=90% CELLSPACING=0 CELLPADDING=0>
<tr><td align=center><font color=green>��������</font></td><td align=center><font color=green>��������</font></td></tr>
<?

echo ("<tr><td align=center><font color=green>���</font></td><td align=center><font color=green>$heroname</font></td></tr>");
echo ("<tr><td align=center><font color=green>����</font></td><td align=center><font color=green>$race</font></td></tr>");
echo ("<tr><td align=center><font color=green>���</font></td><td align=center><font color=green>$type</font></td></tr>");
echo ("<tr><td align=center><font color=green>����</font></td><td align=center><font color=green>$power</font></td></tr>");
echo ("<tr><td align=center><font color=green>������</font></td><td align=center><font color=green>$protect</font></td></tr>");
echo ("<tr><td align=center><font color=green>��������</font></td><td align=center><font color=green>$dexterity</font></td></tr>");
echo ("<tr><td align=center><font color=green>������</font></td><td align=center><font color=green>$know</font></td></tr>");
echo ("<tr><td align=center><font color=green>�����</font></td><td align=center><font color=green>$charism</font></td></tr>");
echo ("<tr><td align=center><font color=green>���������</font></td><td align=center><font color=green>$intel</font></td></tr>");
echo ("<tr><td align=center><font color=green>������ �����</font></td><td align=center><font color=green>$combatmagic</font></td></tr>");
echo ("<tr><td align=center><font color=green>����� �������</font></td><td align=center><font color=green>$naturemagic</font></td></tr>");
echo ("<tr><td align=center><font color=green>����� ������</font></td><td align=center><font color=green>$mindmagic</font></td></tr>");
?>
</table>
<br>
<form action="javascript:rt();">
<center><input type='submit' name='finish' value='  ��������� ���������  ' style='background:#000099; font-WEIGHT: BOLD; COLOR: white; cursor: hand; list-style: lower-alpha; filter: Alpha(Opacity=20, FinishOpacity=100, Style=3)'></center>
</form>
<?
exit();
}


if (!empty($start))
{
	?>
    <h2><font color=#3399FF>��� 3. ��������� ����</font></h2>
    <font color=blue>��������! �������, �������� ��� ���� ������ �������� ���������������<br></font>
	<body background='images\back.jpe'>
	<form action="install.php" method=post>
    <input type="hidden" name='final' value='1234'>
	<center>
	<table border=1 width=95% cols=3 CELLSPACING=0 CELLPADDING=0>
    <tr width=20%><td align=center><font color=#3399FF>��������:</td><td align=center><font color=#3399FF>��������:</td><td align=center><font color=#3399FF>�����������:</td></tr>
	<tr><td><font color=green>��� ������������:</td><td align = center><input type='text' name='login' style='background:#000099; font-WEIGHT: BOLD; COLOR: white; cursor: hand; list-style: lower-alpha; filter: Alpha(Opacity=20, FinishOpacity=100, Style=3)'></td><td>��� ���� ������ �� ������ ������� � �������</td></tr>
    <tr><td><font color=green>������:</td><td align = center><input type='password' name='passwd' style='background:#000099; font-WEIGHT: BOLD; COLOR: white; cursor: hand; list-style: lower-alpha; filter: Alpha(Opacity=20, FinishOpacity=100, Style=3)'></td><td>�����, ��� ����� ���������� ������ ���� ������. ��� ���������� ��� �������������</td></tr>
	<tr><td><font color=green>�������:</td><td align = center><input type='text' name='surname' style='background:#000099; font-WEIGHT: BOLD; COLOR: white; cursor: hand; list-style: lower-alpha; filter: Alpha(Opacity=20, FinishOpacity=100, Style=3)'></td><td>���� ������� ���������� ��� ���������� ����.</td></tr>
	<tr><td><font color=green>���:</td><td align = center><input type='text' name='mname' style='background:#000099; font-WEIGHT: BOLD; COLOR: white; cursor: hand; list-style: lower-alpha; filter: Alpha(Opacity=20, FinishOpacity=100, Style=3)'></td><td>���� ��� ���������� ��� ������� � ����.</td></tr>
	<tr><td><font color=green>������ ����������:</td><td align = center><input type='text' style='background:#000099; font-WEIGHT: BOLD; COLOR: white; cursor: hand; list-style: lower-alpha; filter: Alpha(Opacity=20, FinishOpacity=100, Style=3)' name='country'></td><td>���������� ��� ���������� �������� �������. (���������������)</td></tr>
	<tr><td><font color=green>�����:</td><td align = center><input type='text' name='city' style='background:#000099; font-WEIGHT: BOLD; COLOR: white; cursor: hand; list-style: lower-alpha; filter: Alpha(Opacity=20, FinishOpacity=100, Style=3)'></td><td>��������� ��� ���������� �������� �������. (���������������)</td></tr>
	<tr><td><font color=green>E-Mail:</td><td align = center><input type='text' name='email' style='background:#000099; font-WEIGHT: BOLD; COLOR: white; cursor: hand; list-style: lower-alpha; filter: Alpha(Opacity=20, FinishOpacity=100, Style=3)'></td><td>��������� ��� ����� � ����.</td></tr>
	<tr><td><font color=green>URL:</td><td align = center><input type='text' name='url' style='background:#000099; font-WEIGHT: BOLD; COLOR: white; cursor: hand; list-style: lower-alpha; filter: Alpha(Opacity=20, FinishOpacity=100, Style=3)'></td><td>���� � ��� ���� ��� ����������� ����, ����� ��� ������������.</td></tr>
	<tr><td><font color=green>����������� ������:</td><td align = center><input type='text' name='�quest' style='background:#000099; font-WEIGHT: BOLD; COLOR: white; cursor: hand; list-style: lower-alpha; filter: Alpha(Opacity=20, FinishOpacity=100, Style=3)'></td><td>���� �� �������� ��������� ��� ������, ��, ����� ������������ ���, ������ ������ ��� ������.</td></tr>
	<tr><td><font color=green>����������� �����:</td><td align = center><input type='text' name='cansw' style='background:#000099; font-WEIGHT: BOLD; COLOR: white; cursor: hand; list-style: lower-alpha; filter: Alpha(Opacity=20, FinishOpacity=100, Style=3)'></td><td>� ���� �� ������ ���� �����, �� ��� ������ ��������� ������.</td></tr>
	<tr><td><font color=#3366FF>��� �����</td><td align = center><input type='text' name='heroname' style='background:#000099; font-WEIGHT: BOLD; COLOR: white; cursor: hand; list-style: lower-alpha; filter: Alpha(Opacity=20, FinishOpacity=100, Style=3)'></td><td>��� ���� ������ �� ������ �������� � ����, ��� ����������� ����� ������������.</td></tr>
    <tr><td><font color=#3366FF>���� �����:</td><td align = center><select name='race' style='background:#000099; font-WEIGHT: BOLD; COLOR: white; cursor: hand; list-style: lower-alpha; filter: Alpha(Opacity=20, FinishOpacity=100, Style=3)'>
	<option value='people'>�������</option>
	<option value='elf'>����</option>
	<option value='hnom'>����</option>
	<option value='druid'>�����</option>
	<option value='necro'>���������</option>
	<option value='hell'>������</option>
	</select></td><td align=center>
	<table border = 1 CELLSPACING=0 CELLPADDING=0>
	<tr><td align=center>����:</td><td>����:</td><td>������:</td><td>��������:</td><td>������:</td><td>�����:</td><td>���������:</td></tr>
	<tr><td align=center>�������</td><td align=center>+1</td><td align=center>+1</td><td align=center>+1</td><td align=center>0</td><td align=center>+1</td><td align=center>+1</td></tr>
    <tr><td align=center>����</td><td align=center>+1</td><td align=center>0</td><td align=center>+2</td><td align=center>0</td><td align=center>+1</td><td align=center>+1</td></tr>
    <tr><td align=center>����</td><td align=center>+1</td><td align=center>+2</td><td align=center>+1</td><td align=center>0</td><td align=center>0</td><td align=center>+1</td></tr>
    <tr><td align=center>�����</td><td align=center>0</td><td align=center>+1</td><td align=center>0</td><td align=center>+3</td><td align=center>0</td><td align=center>+1</td></tr>
    <tr><td align=center>���������</td><td align=center>+2</td><td align=center>0</td><td align=center>0</td><td align=center>+2</td><td align=center>-1</td><td align=center>+2</td></tr>
    <tr><td align=center>������</td><td align=center>+1</td><td align=center>+2</td><td align=center>0</td><td align=center>+2</td><td align=center>-1</td><td align=center>+1</td></tr>
	</table>
	</td></tr>
	<tr><td><font color=#3366FF>��� �����:</td><td align = center><select name='type' style='background:#000099; font-WEIGHT: BOLD; COLOR: white; cursor: hand; list-style: lower-alpha; filter: Alpha(Opacity=20, FinishOpacity=100, Style=3)'>
	<option value='knight'>������</option>
	<option value='archer'>�������</option>
	<option value='mag'>���</option>
	<option value='lekar'>��������</option>
	<option value='barbarian'>������</option>
	<option value='wizard'>���������</option>
	</select></td><td align=center>
	<table border = 1 CELLSPACING=0 CELLPADDING=0>
	<tr><td align=center>���:</td><td>����:</td><td>������</td><td>��������:</td><td>������ �����:</td><td>����� �������:</td><td>����� ������:</td></tr>
	<tr><td align=center>������</td><td align=center>+2</td><td align=center>+2</td><td align=center>+1</td><td align=center>0</td><td align=center>0</td><td align=center>0</td></tr>
    <tr><td align=center>�������</td><td align=center>+2</td><td align=center>+1</td><td align=center>+2</td><td align=center>0</td><td align=center>0</td><td align=center>0</td></tr>
    <tr><td align=center>���</td><td align=center>0</td><td align=center>0</td><td align=center>0</td><td align=center>+2</td><td align=center>+2</td><td align=center>+1</td></tr>
    <tr><td align=center>��������</td><td align=center>0</td><td align=center>0</td><td align=center>0</td><td align=center>+1</td><td align=center>+3</td><td align=center>+1</td></tr>
    <tr><td align=center>������</td><td align=center>+3</td><td align=center>+2</td><td align=center>0</td><td align=center>0</td><td align=center>0</td><td align=center>0</td></tr>
    <tr><td align=center>���������</td><td align=center>0</td><td align=center>0</td><td align=center>0</td><td align=center>+1</td><td align=center>+1</td><td align=center>+3</td></tr>
	</table>	
	</td></tr>
    <tr><td><font color=#FF0033>�������� �����:</td><td align = center><input type='text' name='moneyname' style='background:#000099; font-WEIGHT: BOLD; COLOR: white; cursor: hand; list-style: lower-alpha; filter: Alpha(Opacity=20, FinishOpacity=100, Style=3)'></td><td>��� ����� ���������� ������ � ����� ������ (��. �. ��������, [�����])</td></tr> 
	<tr><td><font color=#FF0033>���� ����� � �������:</td><td align = center><input type='text' style='background:#000099; font-WEIGHT: BOLD; COLOR: white; cursor: hand; list-style: lower-alpha; filter: Alpha(Opacity=20, FinishOpacity=100, Style=3)' name='curse'></td><td>����� �����, ������� ����. �.�. �� 1 ������� ������, �� ��������� n ������ ������ � ����� ������.</td></tr>
    <tr><td><font color=#FF0033>�������� ������:</td><td align = center><input type='text' style='background:#000099; font-WEIGHT: BOLD; COLOR: white; cursor: hand; list-style: lower-alpha; filter: Alpha(Opacity=20, FinishOpacity=100, Style=3)' name='gcountry'></td><td>������ ��� ����������� ���� ������������ �� ������ �������� � ����.</td></tr>
    <tr><td><font color=#FF0033>�������� �������:</td><td align = center><input type='text' name='gcapital' style='background:#000099; font-WEIGHT: BOLD; COLOR: white; cursor: hand; list-style: lower-alpha; filter: Alpha(Opacity=20, FinishOpacity=100, Style=3)'></td><td>��� ����� ���������� ������� ������ �����������</td></tr>
    <tr><td><font color=#FF0033>��������� ������:</td><td align = center><select name='res' style='background:#000099; font-WEIGHT: BOLD; COLOR: white; cursor: hand; list-style: lower-alpha; filter: Alpha(Opacity=20, FinishOpacity=100, Style=3)'>
	<option value='metal'>������</option>
	<option value='rock'>������</option>
	<option value='wood'>������</option>
	</select></td><td>����� �� ��� �������� ����� ���������� � ����� ������? ��������� ��� ��� ������� �������� � ����� ��������� ��� ������������� �� ������ �����.</td></tr>
	</table>
	<br><input type='submit' name='prefinish' value='  �����  ' style='background:#000099; font-WEIGHT: BOLD; COLOR: white; cursor: hand; list-style: lower-alpha; filter: Alpha(Opacity=20, FinishOpacity=100, Style=3)'>
	</center>
	</form>

	<?
	exit();
}

if (empty($host))
{
	//���� ��� �����������, �� ������� ������ ����
	if ($ok != 1)
	{
		$file = fopen("config.ini.php", "r");
		if ($file)
		{
			$ttemp = trim(fgets($file, 255));
			$ttemp = trim(fgets($file, 255));
			$thost = trim(fgets($file, 255));
			$tbase = trim(fgets($file, 255));
			$tname = trim(fgets($file, 255));
			$tpass = trim(fgets($file, 255));
			fclose($file);
			$test = @mysql_connect($thost,$tname,$tpass);
			if ($test)
			{
//				$q = mysql_query("drop database ".$tbase, $test);
			}
			else
			{
				unlink("config.ini.php");
				echo("<script>window.location.href('install.php');</script>");
			}

		}
		else
		{
			echo("<script>window.location.href('install.php?ok=1');</script>");
		}
	}

	?>
  	<html>
    <center><h2><font color=#3399FF>��������� Native Land</font></h2></center>
    <H4><font color=#00CC99>����� ���������� � ��������� ��������� PHP �������� - Native Land. ��� ��������� ������� ��� ��������� ��������� ����������������� ��������. �������� �����������, ������� ����� ��� ���������� � ���� ���������...</H4>

    <h2><font color=#3399FF>��� 1. ��������� ����� � ����� ������ MySql</font></h2>
    <center>
    <form action="install.php" method="post">
    <table border=1 width=40% cols=2 CELLSPACING=0 CELLPADDING=0>
    <tr><td align=center>��������</td><td align = center>��������</tr></tr>
    <tr><td align=left>���� ���� ������:</td><td align=center><input type="text" name="host" value="localhost" style='background:#000099; font-WEIGHT: BOLD; COLOR: white; cursor: hand; list-style: lower-alpha; filter: Alpha(Opacity=20, FinishOpacity=100, Style=3)'></td></tr>
    <tr><td align=left>��� ���� ������:</td><td align=center><input type="text" name="name" value="venta" style='background:#000099; font-WEIGHT: BOLD; COLOR: white; cursor: hand; list-style: lower-alpha; filter: Alpha(Opacity=20, FinishOpacity=100, Style=3)'></td></tr>
    <tr><td align=left>��� ������������:</td><td align=center><input type="text" name="log" value="root" style='background:#000099; font-WEIGHT: BOLD; COLOR: white; cursor: hand; list-style: lower-alpha; filter: Alpha(Opacity=20, FinishOpacity=100, Style=3)'></td></tr>
    <tr><td align=left>������ �������:</td><td align=center><input type="password" name="dpwd" value="" style='background:#000099; font-WEIGHT: BOLD; COLOR: white; cursor: hand; list-style: lower-alpha; filter: Alpha(Opacity=20, FinishOpacity=100, Style=3)'></td></tr>
    </table><br>
    <center><input type="submit" value="  �����  " style='background:#000099; font-WEIGHT: BOLD; COLOR: white; cursor: hand; list-style: lower-alpha; filter: Alpha(Opacity=20, FinishOpacity=100, Style=3)'></center>
    </form>
    </center>
	<?
    if (!empty($error))
	{
		echo ("<font color='green'>��������! �� ����� ��������� ������������ ���������� ��������� ������: ".$error."</font>");
	}
}
else
{
   $db_connect = @mysql_connect($host, $log, $dpwd);
   if (!$db_connect)
   {
      $error = "<font color=green>���������� ������������ � ������� MySql";
      echo $error;
	  echo ("<br>");
      echo ("<script language=JavaScript>");
	  echo ("function rt()");
	  echo ("{");
	  echo ("window.location.href('install.php');");
	  echo ("}");
	  echo ("</script>");
  	  echo ("<body background='images\back.jpe'>");
	  echo ("���������� ������������ ������ ���������.<br><br>");
	  ?>
      <form action="javascript:rt();">
      <input type='submit' name='back' value='  �����  ' style='background:#000099; font-WEIGHT: BOLD; COLOR: white; cursor: hand; list-style: lower-alpha; filter: Alpha(Opacity=20, FinishOpacity=100, Style=3)'>      
	  </form>
	  <?
	  exit();
   }

$slct = mysql_select_db($dbname, $db_connect);

if (!$slct)
   {
   mysql_query('create database '.$name,$db_connect);
   echo ("<font color=green>������� ���� ������ $name</font><br>");
   }
   else
	{
	   echo("<font color=green>���� ������ $name ������� ��� �������������</font><br>");
	}

$file = fopen("config.ini.php", "w");
fputs($file, "<?\n");
fputs($file, "/*\n");
fputs($file, "$host\n");
fputs($file, "$name\n");
fputs($file, "$log\n");
fputs($file, "$pwd");
fputs($file, "*/\n");
fputs($file, "?>\n");
fclose ($file);
echo ("<body background='images\back.jpe'>");
echo ("<font color=#3399FF><h2>��� 2. ������� �������� ������...</font></h2>");
mysql_query ('use '.$name, $db_connect);

mysql_query ('create table users (login text, pwd text, surname text, name text, city text, country text, email text, url text) TYPE=MyISAM;');
echo("<font color=green>������� ������� 'users'</font><br>");

mysql_query ('create table war (login text, lx1 text, ly1 text, lx2 text, ly2 text, lx3 text, ly3 text, lx4 text, ly4 text, step1 text, step2 text, step3 text, step4 text, health1 text, health2 text, health3 text, health4 text, arrow1 text, arrow2 text, arrow3 text, arrow4 text) TYPE=MyISAM;');
echo("<font color=green>������� ������� 'war'</font><br>");

mysql_query ('create table mapbuild (number text, login text, type text, rx text, ry text, cx text, cy text, info text) TYPE=MyISAM;');
echo("<font color=green>������� ������� 'mapbuild'</font><br>");

mysql_query ('create table hosting (login text, dir text) TYPE=MyISAM;');
echo("<font color=green>������� ������� 'hosting'</font><br>");

mysql_query ('create table inf (login text, icq text, about text, def text, showmyinfo text, fld1 text, fld2 text, fld3 text, fld4 text, fld5 text, fld6 text, fld7 text, fld8 text, fld9 text) TYPE=MyISAM;');
echo("<font color=green>������� ������� 'inf'</font><br>");

mysql_query ('create table status (login text, online text, timeout text, f1 text, f2 text, f3 text) TYPE=MyISAM;');
echo("<font color=green>������� ������� 'status'</font><br>");

mysql_query ('create table hero (login text, name text, expa int(10) default NULL, level int(10) default NULL, upgrade int(10) default NULL, race text, type text, health int (10) default NULL, location text) TYPE=MyISAM;');
echo("<font color=green>������� ������� 'hero'</font><br>");

mysql_query ('create table abilities (login text, power int(10) default NULL, protect int(10) default NULL, magicpower int(10) default NULL, cnowledge int(10) default NULL, charism int(10) default NULL, dexterity int(10) default NULL, intellegence int(10) default NULL, naturemagic int(10) default NULL, combatmagic int(10) default NULL, mindmagic int(10) default NULL) TYPE=MyISAM;');
echo("<font color=green>������� ������� 'abilities'</font><br>");

mysql_query ('create table magic (login text, cast1 int(10) default NULL, cast2 int(10) default NULL, cast3 int(10) default NULL, cast4 int(10) default NULL, cast5 int(10) default NULL, cast6 int(10) default NULL) TYPE=MyISAM;');
echo("<font color=green>������� ������� 'magic'</font><br>");

mysql_query ('create table items (login text, golova int(10) default NULL, shea int(10) default NULL, telo int(10) default NULL, tors int(10) default NULL, palec int(10) default NULL, leftruka int(10) default NULL, rightruka int(10) default NULL, vrukah int(10) default NULL, nogi int(10) default NULL, koleni int(10) default NULL, plash int(10) default NULL) TYPE=MyISAM;');
echo("<font color=green>������� ������� 'items'</font><br>");

mysql_query ('create table economic (login text, money int(10) default NULL, metal int(10) default NULL, rock int(10) default NULL, wood int(10) default NULL, curse int(10) default NULL, moneyname text, peoples int(10) default NULL, nalog int(10) default NULL) TYPE=MyISAM;');
echo("<font color=green>������� ������� 'economic'</font><br>");

//���� ����� � �������
mysql_query ('create table info (login text, country text, capital text, resource text) TYPE=MyISAM;');
echo("<font color=green>������� ������� 'info'</font><br>");
//resource - ��� ������ ������

mysql_query ('create table city (login text, name text, build1 int(10) default NULL, build2 int(10) default NULL, build3 int(10) default NULL, build4 int(10) default NULL, build5 int(10) default NULL, build6 int(10) default NULL, build7 int(10) default NULL, build8 int(10) default NULL, build9 int(10) default NULL, build10 int(10) default NULL, build11 int(10) default NULL, build12 int(10) default NULL, build13 int(10) default NULL, build14 int(10) default NULL, build15 int(10) default NULL, build16 int(10) default NULL, build17 int(10) default NULL, build18 int(10) default NULL, build19 int(10) default NULL, build20 int(10) default NULL) TYPE=MyISAM;');
echo("<font color=green>������� ������� 'city'</font><br>");

mysql_query ('create table monsters (name text, race text, art text, level int(10) default NULL, health int(10) default NULL, power int(10) default NULL, protect int(10) default NULL) TYPE=MyISAM;');
echo("<font color=green>������� ������� 'monsters'</font><br>");

mysql_query ('create table time (login text, lastexit int(10) default NULL, hp int(10) default NULL, combats int(10) default NULL) TYPE=MyISAM;');
echo("<font color=green>������� ������� 'time'</font><br>");

mysql_query ('create table buildings (race text, build1 text, build2 text, build3 text, build4 text, build5 text, build6 text, build7 text, build8 text, build9 text, build10 text, build11 text, build12 text, build13 text, build14 text, build15 text, build16 text, build17 text, build18 text, build19 text, build20 text) TYPE=MyISAM;');
echo("<font color=green>������� ������� 'buildings'</font><br>");

mysql_query ('create table army (login text, level1 text, level2 text, level3 text, level4 text) TYPE=MyISAM;');
echo("<font color=green>������� ������� 'army'</font><br>");

mysql_query ('create table allitems (num int(10) default NULL, name text, action int(10) default NULL, effect int(10) default NULL, img text, cena int(10) default NULL, type int(10) default NULL) TYPE=MyISAM;');
echo("<font color=green>������� ������� 'allitems'</font><br>");

mysql_query ('create table allcasts (num int(10) default NULL, name text, type int(10) default NULL, action int(10) default NULL, effect int(10) default NULL, img text, cena int(10) default NULL) TYPE=MyISAM;');
echo("<font color=green>������� ������� 'allcasts'</font><br>");

mysql_query ('create table settings (admin text, f1 text, f2 text, f3 text, f4 text, f5 text) TYPE=MyISAM;');
echo("<font color=green>������� ������� 'settings'</font><br>");

mysql_query ('create table ip (login text, ip text) TYPE=MyISAM;');
echo("<font color=green>������� ������� 'ip'</font><br>");

mysql_query ('create table battles (login text, opponent text, health int(10) default NULL, battle int(10) default NULL) TYPE=MyISAM;');
echo("<font color=green>������� ������� 'battles'</font><br>");

mysql_query ('create table help (login text, gold text, metal text, rock text, wood text) TYPE=MyISAM;');
echo("<font color=green>������� ������� 'help'</font><br>");

mysql_query ('create table temp (login text, param text, value text) TYPE=MyISAM;');
echo("<font color=green>������� ������� 'temp'</font><br>");

mysql_query ('create table lostpass (login text, question text, answer text) TYPE=MyISAM;');
echo("<font color=green>������� ������� 'lostpass'</font><br>");

mysql_query ('create table unions (login text, login2 text, login3 text, login4 text, login5 text) TYPE=MyISAM;');
echo("<font color=green>������� ������� 'unions'</font><br>");

mysql_query ('create table additional (num text, name text, effect text, level1 text, level2 text, level3 text, img text, desc1 text, desc2 text, desc3 text) TYPE=MyISAM;');
echo("<font color=green>������� ������� 'additional'</font><br>");

mysql_query ('create table events (num text, name text, effect text, how text) TYPE=MyISAM;');
echo("<font color=green>������� ������� 'events'</font><br>");

mysql_query ('create table coords (login text, rx text, ry text, x text, y text) TYPE=MyISAM;');
echo("<font color=green>������� ������� 'coords'</font><br>");

mysql_query ('create table capital (login text, rx text, ry text, x text, y text) TYPE=MyISAM;');
echo("<font color=green>������� ������� 'capital'</font><br>");

mysql_query ('create table clans (name text, login text, description text, link text, logo text, gerb text, nalog text, bill text, super1 text, super2 text, super3 text) TYPE=MyISAM;');
echo("<font color=green>������� ������� 'clans'</font><br>");

mysql_query ('create table inclan (login text, clan text, bill text, status text) TYPE=MyISAM;');
echo("<font color=green>������� ������� 'inclan'</font><br>");

mysql_query ('create table forum_categories(category text, folder text, moderator text)TYPE=MyIsam;');
echo("<font color=green>������� ������� 'forum_categories'</font><br>");

mysql_query ('create table forum_forums(forum text, folder text, category text)TYPE=MyIsam;');
echo("<font color=green>������� ������� 'forum_forums'</font><br>");

mysql_query ('create table forum_subjects(subject text, folder text, forum text, category text, closed text, author text, hasnew text)TYPE=MyIsam;');
echo("<font color=green>������� ������� 'forum_subjects'</font><br>");

mysql_query ('create table map (rx text, ry text, name text, zone text) TYPE=MyISAM;');
echo("<font color=green>������� ������� 'map'</font><br>");

mysql_query ('create table money (login text, money text) TYPE=MyISAM;');
echo("<font color=green>������� ������� 'money'</font><br>");

mysql_query ('create table battle (login text, battle text, health text, opponent text, turn text, attack text, data text, value text, info text, timeout text) TYPE=MyISAM;');
echo("<font color=green>������� ������� 'battle'</font><br>");

mysql_query ('create table bottles (login text, hmini text, hmedi text, hmaxi text, mmini text, mmedi text, mmaxi text, pmini text, pmedi text, pmaxi text, smini text, smedi text, smaxi text, amini text, amedi text, amaxi text) TYPE=MyISAM;');
echo("<font color=green>������� ������� 'bottles'</font><br>");

mysql_query ('create table newchar (login text, achar1 text, achar2 text, achar3 text, achar4 text, achar5 text, achar6 text, achar7 text, achar8 text, achar9 text, achar10 text, achar11 text, achar12 text, achar13 text, achar14 text, achar15 text, achar16 text) TYPE=MyISAM;');
echo("<font color=green>������� ������� 'newchar'</font><br>");

//����������
mysql_query ('create table stat_ip (id_ip int(32) NOT NULL auto_increment, ip text, putdate datetime default NULL, id_page int(10) default NULL, browser int(4) default NULL, system int(4) default NULL, search int(4) default NULL, PRIMARY KEY (id_ip)) TYPE=MyISAM;');
echo("<font color=green>������� ������� 'stat_ip'</font><br>");

mysql_query ('create table stat_refferer (id_refferer int(16) NOT NULL auto_increment, name text, putdate datetime default NULL, ip text, id_page int(8) default NULL, PRIMARY KEY (id_refferer)) TYPE=MyISAM;');
echo("<font color=green>������� ������� 'stat_refferer'</font><br>");

mysql_query ('create table stat_pages (id_page int(10) NOT NULL auto_increment, name text, id_site int(4) default NULL, PRIMARY KEY  (id_page)) TYPE=MyISAM;');
echo("<font color=green>������� ������� 'stat_pages'</font><br>");

mysql_query ('create table stat_links (id_links int(8) NOT NULL auto_increment, name text, PRIMARY KEY (id_links)) TYPE=MyISAM;');
echo("<font color=green>������� ������� 'stat_links'</font><br>");

mysql_query ('create table warriors (name text, health text, power text, protect text, archery text, arrows text, img text, race text, level text, addon text) TYPE=MyISAM;');
echo("<font color=green>������� ������� 'warriors'</font><br>");

mysql_query ('create table random (monster text, level text, id text, x text, y text, rx text, ry text, hand text, armor text) TYPE=MyISAM;');
echo("<font color=green>������� ������� 'random'</font><br>");

mysql_query ('create table inventory (login text, inv1 text, inv2 text, inv3 text, inv4 text, inv5 text, inv6 text, inv7 text, inv8 text, inv9 text, inv10 text, inv11 text, inv12 text, inv13 text, inv14 text, inv15 text, inv16 text) TYPE=MyISAM;');
echo("<font color=green>������� ������� 'inventory'</font><br>");

mysql_query ('create table quests (num text, name text, author text, time text, man text, complete text, description text, price text, data text) TYPE=MyISAM;');
echo("<font color=green>������� ������� 'quests'</font><br>");

//������
echo("<font color='green'>������� �������� ������ ��������</font><br>");

//������� ���� � ������
//         ��������         ���� ����      ���� ������ �������� ������ ���� ������ ������
//����
addwarrior('�������',      1,   'people', 2,   1,     0,       0,     5,   'p_1', '��������');      //Full = 3
addwarrior('�������',       2,   'people', 1,   2,     3,       10,    10,  'p_2', '��������');       //Full = 6
addwarrior('������',        3,   'people', 1,   2,     6,       20,    25,  'p_3', '�������');        //Full = 9
addwarrior('������',        4,   'people', 8,   4,     0,       0,     33,  'p_4', '�������');        //Full = 12
//�����
addwarrior('���',           1,   'elf',    1,   2,     0,       0,     4,   'e_1', '���');            //Full = 3
addwarrior('�������',       2,   'elf',    1,   1,     5,       14,    9,   'e_2', '��������');       //Full = 6
addwarrior('��������',      3,   'elf',    1,   1,     7,       20,    26,  'e_3', '���������');      //Full = 9
addwarrior('������� �����', 4,   'elf',    1,   1,     10,      25,    28,  'e_4', '������� ������'); //Full = 12
//�����
addwarrior('������',        1,   'hnom',   1,   2,     0,       0,     6,   'h_1', '�������');        //Full = 3
addwarrior('������',        2,   'hnom',   2,   1,     3,       12,    13,  'h_2', '�����');          //Full = 6
addwarrior('���������',     3,   'hnom',   1,   1,     0,       0,     27,  'h_3', '����������');     //Full = 9
addwarrior('������� �����', 4,   'hnom',   9,   3,     0,       0,     32,  'h_4', '������ ������');  //Full = 12
//������
addwarrior('������� ������',1,   'druid',  2,   2,     0,       0,     3,   'd_1', '������� �������');//Full = 3
addwarrior('����',          2,   'druid',  1,   1,     4,       12,    12,  'd_2', '�����');          //Full = 6
addwarrior('�����',         3,   'druid',  6,   3,     0,       0,     28,  'd_3', '������');         //Full = 9
addwarrior('������',        4,   'druid',  1,   2,     9,       25,    31,  'd_4', '�������');        //Full = 12
//������
addwarrior('�������',       1,   'necro',  1,   2,     0,       0,     6,   'n_1', '��������');       //Full = 3
addwarrior('��������',      2,   'necro',  4,   2,     0,       0,     11,  'n_2', '���������');      //Full = 6
addwarrior('�������',       3,   'necro',  7,   3,     0,       0,     25,  'n_3', '��������');       //Full = 9
addwarrior('׸���� ������', 4,   'necro',  10,  2,     0,       0,     35,  'n_4', '������ �������'); //Full = 12
//������ ��������
addwarrior('����',          1,   'hell',   1,   2,     0,       0,     5,   'a_1', '�����');          //Full = 3
addwarrior('����',          2,   'hell',   1,   1,     3,       25,    13,  'a_2', '�����');          //Full = 6
addwarrior('�����',         3,   'hell',   8,   1,     0,       0,     26,  'a_3', '������');         //Full = 9
addwarrior('�������',       4,   'hell',   8,   5,     0,       0,     30,  'a_4', '��������');       //Full = 12


//������� ���� � ��������� �������� �����
//����������; ��������; ��� (0 - �����)
addzone(1,  1, "�������� �����", 0);
addzone(2,  1, "�������� �����", 0);
addzone(3,  1, "�������� �����", 0);
addzone(4,  1, "����� ��������", 0);
addzone(5,  1, "�������� �����", 0);
addzone(6,  1, "�������� �����", 0);
addzone(7,  1, "�������� �����", 0);
addzone(8,  1, "�������� �����", 0);
addzone(9,  1, "�������� �����", 0);
addzone(10, 1, "�������� ���������", 0);
addzone(11, 1, "�������� ���������", 0);
addzone(12, 1, "�������� ���������", 0);
addzone(13, 1, "�������� ������", 0);
addzone(14, 1, "�������� ���������", 0);
addzone(15, 1, "�������� �����", 0);
addzone(16, 1, "�������� �����", 0);
addzone(17, 1, "�������� �����", 0);
addzone(18, 1, "�������� �����", 0);
addzone(19, 1, "�������� �����", 0);
addzone(20, 1, "�������� �����", 0);

addzone(1,  2, "�������� ����", 0);
addzone(2,  2, "�������� ����", 0);
addzone(3,  2, "�������� �����", 0);
addzone(4,  2, "�������� �����", 0);
addzone(5,  2, "�������� �����", 0);
addzone(6,  2, "�������� �����", 0);
addzone(7,  2, "�������� �����", 0);
addzone(8,  2, "�������� �����", 0);
addzone(9,  2, "�������� ����", 0);
addzone(10, 2, "�������� ����", 0);
addzone(11, 2, "�������� ������", 0);
addzone(12, 2, "�������� ������", 0);
addzone(13, 2, "�������� ���������", 0);
addzone(14, 2, "������-��������� ����", 0);
addzone(15, 2, "�������� �����", 0);
addzone(16, 2, "���� �������", 0);
addzone(17, 2, "�������� �����", 0);
addzone(18, 2, "�������� �����", 0);
addzone(19, 2, "�������� �����", 0);
addzone(20, 2, "�������� �����", 0);

addzone(1,  3, "�������� ����", 0);
addzone(2,  3, "�������� �����", 0);
addzone(3,  3, "�������� �����", 0);
addzone(4,  3, "�������� �����", 0);
addzone(5,  3, "�������� �����", 0);
addzone(6,  3, "�������� ������", 0);
addzone(7,  3, "�������� ������", 0);
addzone(8,  3, "�������� ������", 0);
addzone(9,  3, "�������� ������", 0);
addzone(10, 3, "�������� ������", 0);
addzone(11, 3, "�������� ������", 0);
addzone(12, 3, "������ ����", 0);
addzone(13, 3, "���� �������", 0);
addzone(14, 3, "���� �������", 0);
addzone(15, 3, "���� �������", 0);
addzone(16, 3, "���� �������", 0);
addzone(17, 3, "�������� �����", 0);
addzone(18, 3, "�������� �����", 0);
addzone(19, 3, "������� �����", 0);
addzone(20, 3, "�������� �����", 0);


addzone(1,  4, "���� ������-������", 0);
addzone(2,  4, "���� ������-������", 0);
addzone(3,  4, "�������� �����", 0);
addzone(4,  4, "�������� �����", 0);
addzone(5,  4, "�������� �����", 0);
addzone(6,  4, "�������� ������", 0);
addzone(7,  4, "����������", 0);
addzone(8,  4, "����������", 0);
addzone(9,  4, "����������", 0);
addzone(10, 4, "�������� ������", 0);
addzone(11, 4, "���� �������", 0);
addzone(12, 4, "���� �������", 0);
addzone(13, 4, "���� �������", 0);
addzone(14, 4, "������-��������� �������", 0);
addzone(15, 4, "������-��������� �������", 0);
addzone(16, 4, "�������� �����", 0);
addzone(17, 4, "�������� �����", 0);
addzone(18, 4, "�������� �����", 0);
addzone(19, 4, "��������� �����", 0);
addzone(20, 4, "��������� ������", 0);

addzone(1,  5, "�������� ��������", 0);
addzone(2,  5, "�������� ��������", 0);
addzone(3,  5, "�������� �������", 0);
addzone(4,  5, "�������� �������", 0);
addzone(5,  5, "�������� �����", 0);
addzone(6,  5, "���� ������-������", 0);
addzone(7,  5, "���� ������-������", 0);
addzone(8,  5, "���� ������-������", 0);
addzone(9,  5, "���� ������-������", 0);
addzone(10, 5, "���� �������", 0);
addzone(11, 5, "�������� ������", 0);
addzone(12, 5, "��������� ����", 0);
addzone(13, 5, "�������� �����", 0);
addzone(14, 5, "��������� ����", 0);
addzone(15, 5, "��������� ����", 0);
addzone(16, 5, "��������� ����", 0);
addzone(17, 5, "��������� ����", 0);
addzone(18, 5, "��������������� ����", 0);
addzone(19, 5, "��������� �����", 0);
addzone(20, 5, "��������� ������", 0);

addzone(1,  6, "�������� ��������", 0);
addzone(2,  6, "�������� ��������", 0);
addzone(3,  6, "�������� �������", 0);
addzone(4,  6, "�������� �������", 0);
addzone(5,  6, "����������� �����������", 0);
addzone(6,  6, "����������� �����������", 0);
addzone(7,  6, "����������� ����", 0);
addzone(8,  6, "����������� �����������", 0);
addzone(9,  6, "����������� �����������", 0);
addzone(10, 6, "���� �������", 0);
addzone(11, 6, "��������� ����", 0);
addzone(12, 6, "��������� ����", 0);
addzone(13, 6, "��������� ����", 0);
addzone(14, 6, "��������� ����", 0);
addzone(15, 6, "��������� ����", 0);
addzone(16, 6, "��������� ����", 0);
addzone(17, 6, "��������� ����", 0);
addzone(18, 6, "����� �������", 0);
addzone(19, 6, "��������������� ����", 0);
addzone(20, 6, "��������� �����", 0);

addzone(1,  7, "�������� ��������", 0);
addzone(2,  7, "���� ��������", 0);
addzone(3,  7, "���� ��������", 0);
addzone(4,  7, "����� ��������", 0);
addzone(5,  7, "����������� ����", 0);
addzone(6,  7, "����������� ����", 0);
addzone(7,  7, "����������� ����", 0);
addzone(8,  7, "����������� ����", 0);
addzone(9,  7, "���� �������", 0);
addzone(10, 7, "����������� �����������", 0);
addzone(11, 7, "��������� ����", 0);
addzone(12, 7, "��������� ����", 0);
addzone(13, 7, "��������� ����", 0);
addzone(14, 7, "��������� ����", 0);
addzone(15, 7, "��������� ����", 0);
addzone(16, 7, "��������� ����", 0);
addzone(17, 7, "��������� ����", 0);
addzone(18, 7, "��������������� ����", 0);
addzone(19, 7, "��������������� ����", 0);
addzone(20, 7, "��������������� ����", 0);

addzone(1,  8, "�������� �����", 0);
addzone(2,  8, "�������� �����", 0);
addzone(3,  8, "���� ��������", 0);
addzone(4,  8, "���� �������", 0);
addzone(5,  8, "���� �������", 0);
addzone(6,  8, "����������� ����", 0);
addzone(7,  8, "����������� ����", 0);
addzone(8,  8, "����������� ����", 0);
addzone(9,  8, "������ ��������", 0);
addzone(10, 8, "������ ��������", 0);
addzone(11, 8, "�������� �����", 0);
addzone(12, 8, "�������� �����", 0);
addzone(13, 8, "���������� ����", 0);
addzone(14, 8, "���������� ����", 0);
addzone(15, 8, "��������� ����", 0);
addzone(16, 8, "��������� ����", 0);
addzone(17, 8, "������ �����", 0);
addzone(18, 8, "������ �����", 0);
addzone(19, 8, "��������������� ����", 0);
addzone(20, 8, "��������������� ����", 0);

addzone(1,  9, "�������� ���������", 0);
addzone(2,  9, "�������� �����", 0);
addzone(3,  9, "������� �������� �����", 0);
addzone(4,  9, "������� �������� �����", 0);
addzone(5,  9, "���� �������", 0);
addzone(6,  9, "���� �������", 0);
addzone(7,  9, "���� �������", 0);
addzone(8,  9, "���� �������", 0);
addzone(9,  9, "������ ��������", 0);
addzone(10, 9, "������ ��������", 0);
addzone(11, 9, "�������� �����", 0);
addzone(12, 9, "�������� �����", 0);
addzone(13, 9, "���������� ����", 0);
addzone(14, 9, "���������� ����", 0);
addzone(15, 9, "���� �����", 0);
addzone(16, 9, "��������� ����", 0);
addzone(17, 9, "��������� ����", 0);
addzone(18, 9, "��������������� ����", 0);
addzone(19, 9, "������� ������ �����", 0);
addzone(20, 9, "������� ������ �����", 0);

addzone(1,  10, "�������� ������", 0);
addzone(2,  10, "�������� ������", 0);
addzone(3,  10, "������� �������", 0);
addzone(4,  10, "������� �������", 0);
addzone(5,  10, "������� �������", 0);
addzone(6,  10, "����������� �����������", 0);
addzone(7,  10, "����������� �����������", 0);
addzone(8,  10, "�������� �����", 0);
addzone(9,  10, "�������� �����", 0);
addzone(10, 10, "���������� ����", 0);
addzone(11, 10, "���������� ����", 0);
addzone(12, 10, "���������� ����", 0);
addzone(13, 10, "���������� ����", 0);
addzone(14, 10, "���� �����", 0);
addzone(15, 10, "��������� ����", 0);
addzone(16, 10, "��������� ����", 0);
addzone(17, 10, "��������� ����", 0);
addzone(18, 10, "��������������� ����", 0);
addzone(19, 10, "������� ������ �����", 0);
addzone(20, 10, "������ ������", 0);

addzone(1,  11, "�������� ������", 0);
addzone(2,  11, "�������� ������", 0);
addzone(3,  11, "�������� ������", 0);
addzone(4,  11, "������� �������", 0);
addzone(5,  11, "������� �������", 0);
addzone(6,  11, "����������� �����������", 0);
addzone(7,  11, "����� �����", 0);
addzone(8,  11, "����� �����", 0);
addzone(9,  11, "�������� �����", 0);
addzone(10, 11, "���������� ����", 0);
addzone(11, 11, "���������� ����", 0);
addzone(12, 11, "���� �����", 0);
addzone(13, 11, "���� �����", 0);
addzone(14, 11, "��������� ����", 0);
addzone(15, 11, "��������� ����", 0);
addzone(16, 11, "��������� ����", 0);
addzone(17, 11, "��������� ����", 0);
addzone(18, 11, "��������������� ����", 0);
addzone(19, 11, "������� ������ �����", 0);
addzone(20, 11, "������� ������ �����", 0);

addzone(1,  12, "���� ����", 0);
addzone(2,  12, "�������� ������", 0);
addzone(3,  12, "�������� �����������", 0);
addzone(4,  12, "�������� �����������", 0);
addzone(5,  12, "�������� �����������", 0);
addzone(6,  12, "����� �����", 0);
addzone(7,  12, "����� �����", 0);
addzone(8,  12, "����� �����", 0);
addzone(9,  12, "����������� ������", 0);
addzone(10, 12, "���������� ����", 0);
addzone(11, 12, "���� �����", 0);
addzone(12, 12, "���� �����", 0);
addzone(13, 12, "��������� ����", 0);
addzone(14, 12, "��������� ����", 0);
addzone(15, 12, "��������� ����", 0);
addzone(16, 12, "��������� ����", 0);
addzone(17, 12, "��������� ����", 0);
addzone(18, 12, "��������������� ����", 0);
addzone(19, 12, "��������������� ����", 0);
addzone(20, 12, "��������������� ����", 0);

addzone(1,  13, "���� ����", 0);
addzone(2,  13, "���� ����", 0);
addzone(3,  13, "���� ����", 0);
addzone(4,  13, "���� ����", 0);
addzone(5,  13, "����� �����", 0);
addzone(6,  13, "����� �����", 0);
addzone(7,  13, "���� �����", 0);
addzone(8,  13, "���� �����", 0);
addzone(9,  13, "����������� ������", 0);
addzone(10, 13, "����������� ������", 0);
addzone(11, 13, "���� �����", 0);
addzone(12, 13, "��������� ����", 0);
addzone(13, 13, "��������� ����", 0);
addzone(14, 13, "��������� ����", 0);
addzone(15, 13, "��������� ����", 0);
addzone(16, 13, "��������� ����", 0);
addzone(17, 13, "��������� ����", 0);
addzone(18, 13, "��������������� ����", 0);
addzone(19, 13, "��������������� ����", 0);
addzone(20, 13, "��������������� ����", 0);

addzone(1,  14, "�������� �����", 0);
addzone(2,  14, "�������� �������", 0);
addzone(3,  14, "�������� �������", 0);
addzone(4,  14, "���� ����", 0);
addzone(5,  14, "���� ����", 0);
addzone(6,  14, "���� �����", 0);
addzone(7,  14, "���-�������� ���������", 0);
addzone(8,  14, "�������", 0);
addzone(9,  14, "���������� ����", 0);
addzone(10, 14, "���� �����", 0);
addzone(11, 14, "���� �����", 0);
addzone(12, 14, "��������� ����", 0);
addzone(13, 14, "��������� ����", 0);
addzone(14, 14, "����� �����", 0);
addzone(15, 14, "����� �����", 0);
addzone(16, 14, "��������� ����", 0);
addzone(17, 14, "��������� ����", 0);
addzone(18, 14, "Ҹ���� ����", 0);
addzone(19, 14, "��������������� ����", 0);
addzone(20, 14, "��������������� ����", 0);

addzone(1,  15, "�������� �����", 0);
addzone(2,  15, "�������� �����", 0);
addzone(3,  15, "�������� �������", 0);
addzone(4,  15, "���� ����", 0);
addzone(5,  15, "�������� �������", 0);
addzone(6,  15, "�������� �����", 0);
addzone(7,  15, "����� �������� �����", 0);
addzone(8,  15, "�������", 0);
addzone(9,  15, "�������", 0);
addzone(10, 15, "���� �����", 0);
addzone(11, 15, "��������� ����", 0);
addzone(12, 15, "��������� ����", 0);
addzone(13, 15, "��������� ����", 0);
addzone(14, 15, "����� �����", 0);
addzone(15, 15, "����� �����", 0);
addzone(16, 15, "����� ����������", 0);
addzone(17, 15, "��������� ����", 0);
addzone(18, 15, "Ҹ���� ����", 0);
addzone(19, 15, "��������������� ����", 0);
addzone(20, 15, "��������� ������� �������", 0);

addzone(1,  16, "����� �����", 0);
addzone(2,  16, "����� �����", 0);
addzone(3,  16, "���� ����", 0);
addzone(4,  16, "���� ����", 0);
addzone(5,  16, "���-�������� �������", 0);
addzone(6,  16, "���-�������� �������", 0);
addzone(7,  16, "����� �������", 0);
addzone(8,  16, "����� �������", 0);
addzone(9,  16, "�������", 0);
addzone(10, 16, "����� ��������", 0);
addzone(11, 16, "���-��������� ����", 0);
addzone(12, 16, "���-��������� ����", 0);
addzone(13, 16, "��������� ����", 0);
addzone(14, 16, "��������� ����", 0);
addzone(15, 16, "��������� ����", 0);
addzone(16, 16, "��������� ����", 0);
addzone(17, 16, "��������� ����", 0);
addzone(18, 16, "���������", 0);
addzone(19, 16, "������� �������", 0);
addzone(20, 16, "��������� ������� �������", 0);

addzone(1,  17, "���� ����", 0);
addzone(2,  17, "���� ����", 0);
addzone(3,  17, "���� ����", 0);
addzone(4,  17, "������ ����", 0);
addzone(5,  17, "������ ����", 0);
addzone(6,  17, "����� �������", 0);
addzone(7,  17, "����� �������", 0);
addzone(8,  17, "����� �������", 0);
addzone(9,  17, "����� �������", 0);
addzone(10, 17, "���� �����", 0);
addzone(11, 17, "���� �����", 0);
addzone(12, 17, "���-��������� ����", 0);
addzone(13, 17, "������� ������", 0);
addzone(14, 17, "������� ������", 0);
addzone(15, 17, "��������� ����", 0);
addzone(16, 17, "��������� ����", 0);
addzone(17, 17, "��������� ����", 0);
addzone(18, 17, "Ҹ���� ����", 0);
addzone(19, 17, "������� �������", 0);
addzone(20, 17, "���-��������� �����", 0);

addzone(1,  18, "���� ����", 0);
addzone(2,  18, "����� �������������", 0);
addzone(3,  18, "������� ������", 0);
addzone(4,  18, "������� ������", 0);
addzone(5,  18, "������� ������", 0);
addzone(6,  18, "������� ������", 0);
addzone(7,  18, "������� ������", 0);
addzone(8,  18, "������� ������", 0);
addzone(9,  18, "������� ������", 0);
addzone(10, 18, "������� ������", 0);
addzone(11, 18, "���� �����", 0);
addzone(12, 18, "����� �����", 0);
addzone(13, 18, "����� �����", 0);
addzone(14, 18, "����� �����", 0);
addzone(15, 18, "������� ������", 0);
addzone(16, 18, "��������� ����", 0);
addzone(17, 18, "��������� ����", 0);
addzone(18, 18, "Ҹ���� ����", 0);
addzone(19, 18, "Ҹ���� ����", 0);
addzone(20, 18, "����� �����", 0);

addzone(1,  19, "���� ����", 0);
addzone(2,  19, "����� �������������", 0);
addzone(3,  19, "������� ������", 0);
addzone(4,  19, "��������� ����", 0);
addzone(5,  19, "��������� �����", 0);
addzone(6,  19, "��������� ����", 0);
addzone(7,  19, "��������� ����", 0);
addzone(8,  19, "��������� ����", 0);
addzone(9,  19, "������� �����", 0);
addzone(10, 19, "��������� ����", 0);
addzone(11, 19, "���� �����", 0);
addzone(12, 19, "����� �����", 0);
addzone(13, 19, "����� �����", 0);
addzone(14, 19, "��������� ����", 0);
addzone(15, 19, "��������� ����", 0);
addzone(16, 19, "��������� ����", 0);
addzone(17, 19, "��������� ����", 0);
addzone(18, 19, "Ҹ���� �����", 0);
addzone(19, 19, "Ҹ���� �����", 0);
addzone(20, 19, "������ �����", 0);

addzone(1,  20, "���-�������� ����", 0);
addzone(2,  20, "���-�������� ����", 0);
addzone(3,  20, "������� ������", 0);
addzone(4,  20, "��������� ����", 0);
addzone(5,  20, "��������� ����", 0);
addzone(6,  20, "��������� ����", 0);
addzone(7,  20, "����� ���������� �����", 0);
addzone(8,  20, "��������� ����", 0);
addzone(9,  20, "��������� ����", 0);
addzone(10, 20, "����� �����", 0);
addzone(11, 20, "����� �����", 0);
addzone(12, 20, "��������� ����", 0);
addzone(13, 20, "��������� ����", 0);
addzone(14, 20, "����� ������", 0);
addzone(15, 20, "������ ����� ���", 0);
addzone(16, 20, "��������� ����", 0);
addzone(17, 20, "��������� ����", 0);
addzone(18, 20, "Ҹ���� �����", 0);
addzone(19, 20, "Ҹ���� �����", 0);
addzone(20, 20, "����� ����", 0);


// ��������� ��������
//          ��������          ����       ����              �� ��  �� ��
addmonstr('���',            '���������', 'sprite.jpg',     1, 65, 1, 1);
addmonstr('����',           '���������', '0',              1, 70, 2, 1);
addmonstr('������',         '���������', 'goblin.jpg',     1, 75, 2, 2);
addmonstr('����� ��������', '���������', '0',              1, 80, 3, 2);
addmonstr('�������',        '���������', '0',              1, 85, 3, 3);
addmonstr('������',         '���������', 'skelet.jpg',     1, 90, 4, 3);
addmonstr('������',         '���������', 'bandit.jpg',     1, 95, 4, 4);
addmonstr('�����',          '���������', '0',              1, 100, 4, 4);
addmonstr('������',         '���������', '0',              2, 160, 5, 4);
addmonstr('��������',       '���������', '0',              2, 165, 5, 5);
addmonstr('���',            '���������', '0',              2, 170, 6, 5);
addmonstr('������ ��������','���������', '0',              2, 175, 6, 6);
addmonstr('���',            '���������', 'rob.jpg',        2, 180, 7, 6);
addmonstr('���',            '���������', 'gog.jpg',        2, 195, 7, 7);
addmonstr('�������',        '���������', 'dead.jpg',       2, 190, 7, 8);
addmonstr('�������� �����', '���������', 'stoneholem.jpg', 3, 270, 8, 8);
addmonstr('���������� ����','���������', '0',              3, 280, 9, 8);
addmonstr('������� �����',  '���������', 'daemon.jpg',     3, 280, 10, 9);
addmonstr('����',           '���������', '0',              3, 270, 11, 9);
addmonstr('������� �������','���������', '0',              3, 290, 11, 10);
addmonstr('���',            '���������', '0',              3, 290, 12, 9);
addmonstr('����',           '���������', 'master.jpg',     3, 300, 12, 10);
addmonstr('������',         '���������', '0',              3, 300, 12, 11);
addmonstr('�������',        '���������', 'mag.jpg',        4, 350, 12, 12);
addmonstr('����� �����',    '���������', '0',              4, 355, 12, 12);
addmonstr('��������',       '���������', '0',              4, 360, 12, 12);
addmonstr('������� ����',   '���������', '0',              4, 365, 12, 12);
addmonstr('������ ��������','���������', '0',              4, 370, 12, 12);
addmonstr('������ ����',    '���������', 'wishmaster.jpg', 4, 380, 12, 12);
addmonstr('������',         '���������', '0',              5, 390, 12, 13);
addmonstr('����',           '���������', '0',              5, 395, 13, 13);
addmonstr('��������',       '���������', '0',              5, 400, 13, 12);
addmonstr('����� �����',    '���������', '0',              5, 420, 13, 13);
addmonstr('�����',          '���������', 'efretei.jpg',    5, 440, 13, 13);
addmonstr('���',            '���������', '0',              5, 460, 13, 13);
addmonstr('������� �����',  '���������', '0',              6, 550, 14, 13);
addmonstr('�������',        '���������', '0',              6, 570, 15, 13);
addmonstr('������',         '���������', '0',              6, 590, 16, 13);
addmonstr('������ ���',     '���������', '0',              6, 560, 16, 14);
addmonstr('�����������',    '���������', '0',              6, 580, 16, 15);
addmonstr('�����',          '���������', 'titan.jpg',      6, 600, 16, 16);
addmonstr('�������� �����', '���������', '0',              7, 650, 17, 17);
addmonstr('�������',        '���������', '0',              7, 650, 16, 17);
addmonstr('������',         '���������', '0',              7, 660, 18, 18);
addmonstr('������� ����',   '���������', 'waterelem.jpg',  7, 670, 19, 19);
addmonstr('������� ������', '���������', '0',              7, 680, 20, 18);
addmonstr('������� ������', '���������', '0',              7, 690, 20, 19);
addmonstr('������ ������', '���������', '0',              8, 755, 21, 21);
addmonstr('������',         '���������', '0',              8, 760, 22, 21);
addmonstr('������� ������', '���������', '0',              8, 770, 23, 21);
addmonstr('������ �����',   '���������', '0',              8, 780, 23, 22);
addmonstr('������ ������',  '���������', 'deadknife.jpg',  8, 790, 23, 23);
addmonstr('��������',       '���������', '0',              8, 800, 25, 25);
addmonstr('��� �������',    '���������', '0',              9, 850, 30, 30);
addmonstr('���� - ������',  '���������', 'hnom.jpg',       9, 860, 32, 30);
addmonstr('�����������',    '���������', 'krest.jpg',      9, 870, 34, 32);
addmonstr('����� - ������', '���������', 'troll.jpg',      9, 880, 36, 34);
addmonstr('׸���� ������',  '���������', '0',              9, 890, 38, 36);
addmonstr('�����',          '���������', '0',              9, 900, 40, 40);
addmonstr('������� ������', '���������', '0',             10, 920, 45, 45);
addmonstr('����������',     '���������', 'devil.jpg',     10, 940, 48, 48);
addmonstr('���� - �������', '���������', 'greatelf.jpg',  10, 960, 52, 52);
addmonstr('���� ��������',  '���������', 'vampire.jpg',   10, 980, 54, 54);
addmonstr('���������',      '���������', '0',             10, 1000, 60, 60);
echo("<font color='green'>������ � ������� monsters ��������</font><br>");

//��������� �������������� ����������� (������ � ������� NewChar: N4 ��� E8)
//         �����   ��������          ������   ����� ����� ����  ��������
//��������
addability('1',    '��������',       '1',     '3',  '7',  '11', 'axelerate', '����������� ����������������� ����������� �� 3 �������', '����������� ����������������� ����������� �� 7 �������', '����������� ����������������� ����������� �� 11 ������');
//��������
addability('2',    '��������',       '2',     '15', '25', '40', 'dexterity', '��� 15% ���� ���������� �� �����', '��� 25% ���� ���������� �� �����', '��� 40% ���� ���������� �� �����'); 
//�������
addability('3',    '�������',        '3',     '1',  '2',  '3',  'tactic', '����������� ���������� ����� �������� � ��� �� 1', '����������� ���������� ����� �������� � ��� �� 2', '����������� ���������� ����� �������� � ��� �� 3');
//���
addability('4',    '���',            '4',     '10', '15', '20', 'battle', '����������� ��������� ����������� �� 10%', '����������� ��������� ����������� �� 15%', '����������� ��������� ����������� �� 20%'); 
//����������
addability('5',    '����������',     '5',     '1', '3', '5', 'metabalism', '�������� �������������� �������� ���������. �������� ����������������� �� 1% ������ ��� � ���', '�������� �������������� �������� ���������. �������� ����������������� �� 3% ������ ��� � ���', '�������� �������������� �������� ���������. �������� ����������������� �� 5% ������ ��� � ���'); 
//���������
addability('6',    '���������',      '6',     '5',  '10', '15', 'economist', '����������� ���������� � ����� ����� �� 5%', '����������� ���������� � ����� ����� �� 10%', '����������� ���������� � ����� ����� �� 15%'); 
//�������������� ����
addability('7',    '��������������', '7',     '1', '3', '5', 'metamagic', '�������� �������������� ���� ���������. ���� ����������������� �� 1% ������ ��� � ���', '�������� �������������� ���� ���������. ���� ����������������� �� 3% ������ ��� � ���', '�������� �������������� ���� ���������. ���� ����������������� �� 5% ������ ��� � ���'); 
//������ �������
addability('8',    '������ �������', '8',     '1',  '2',  '3',  'metal', '�������� ������������� ������� �������', '�������� ��� ������������� ������� �������', '�������� ��� ������������� ������� �������'); 
//������ �����
addability('9',    '������ �����',   '9',     '1',  '2',  '3',  'rock', '�������� ������������� ������� �����', '�������� ��� ������������� ������� �����', '�������� ��� ������������� ������� �����'); 
//������ ������
addability('10',   '������ ������', '10',    '1',  '2',  '3',  'wood', '�������� ������������� ������� ������', '�������� ��� ������������� ������� ������', '�������� ��� ������������� ������� ������'); 
//���
addability('11',   '���',            '11',    '20', '40', '60',  'rob', '��������� ����� �������� � ����� ������� � ���������� ��� �������� ������ � ������������ 20%', '��������� ����� �������� � ����� ������� � ���������� ��� �������� ������ � ������������ 40%', '��������� ����� �������� � ����� ������� � ���������� ��� �������� ������ � ������������ 60%'); 
//�������
addability('12',   '�������',        '12',    '10', '15', '20', 'wizardy', '����������� ��������� ������ ����������� �� 10%', '����������� ��������� ������ ����������� �� 15%', '����������� ��������� ������ ����������� �� 20%'); 
//���������
addability('13',   '���������',      '13',    '15', '20', '40', 'antimagic', '��� 15% ���� �������� ���������� �����', '��� 20% ���� �������� ���������� �����', '��� 40% ���� �������� ���������� �����'); 
//���������� ������
addability('14',   '���������� ������', '14', '5',  '10', '15', 'magicweapon', '������ ���� ������ ���������� � ������ ������� �����, ���� ������ ����� ��������� ���������, � ��������� � 5%', '������ ���� ������ ���������� � ������ ������� �����, ���� ������ ����� ��������� ���������, � ��������� � 10%', '������ ���� ������ ���������� � ������ ������� �����, ���� ������ ����� ��������� ���������, � ��������� � 15%'); 
//�������������� ������
addability('15',   '�������������� ������', '15', '5', '7', '9',  'powerweapon', '������ ���� ������ ���������� � ������ ������� �����, ���� ������ ����� ��������� ���� ��������� �� 5%', '������ ���� ������ ���������� � ������ ������� �����, ���� ������ ����� ��������� ���� ��������� �� 7%', '������ ���� ������ ���������� � ������ ������� �����, ���� ������ ����� ��������� ���� ��������� �� 9%'); 
//������ �����
addability('16',   '������ �����',   '16',  '10', '15', '20', 'combatmagic', '����������� �����������, ��������� ������ ������ �� 10%', '����������� �����������, ��������� ������ ������ �� 15%', '����������� �����������, ��������� ������ ������ �� 20%'); 
//����� �������
addability('17',   '����� �������',  '17',  '10', '15', '20', 'naturemagic', '����������� �����������, ��������� ������ ������� �� 10%', '����������� �����������, ��������� ������ ������� �� 15%', '����������� �����������, ��������� ������ ������� �� 20%'); 
//����� ������
addability('18',   '����� ������',  '18',  '10', '15', '20', 'mindmagic', '����������� �����������, ��������� ������ ������ �� 10%', '����������� �����������, ��������� ������ ������ �� 15%', '����������� �����������, ��������� ������ ������ �� 20%'); 
//�������������� �����������
//���������
addability('19',   '�������',  '19',  '5', '8', '12', 'strategy', '����������� �����������, ��������� ���� ����� ������ �� 5%', '����������� �����������, ��������� ���� ����� ������ �� 8%', '����������� �����������, ��������� ���� ����� ������ �� 12%'); 
//�������
addability('20',   '�������',  '20',  '5', '8', '12', 'defeat', '��������� �����������, ��������� ���� ����� ����� �� 5%', '��������� �����������, ��������� ���� ����� ����� �� 8%', '��������� �����������, ��������� ���� ����� ����� �� 12%'); 
//����������
addability('21',   '����������',  '21',  '1', '2', '3', 'balistic', '����� ������ ����� �� �������������� ������������� ��� �� ������� ������ � � ������ ����� ���������� ���� �������������� ������ ��� ����� �����', '����� ������ ����� �� �������������� ������������� ��� �� ������� ������ � � ������ ����� ���������� ��� �������������� ������� ��� ����� �����', '����� ������ ����� �� �������������� ������������� ��� �� ������� ������ � � ������ ����� ���������� ��� �������������� ������� ��� ����� �����'); 
//�����������
addability('22',   '�����������',  '21',  '10', '20', '30', 'necromancy', '��������� ����� ����� ���������� ��� 10% ���� ����� ������ ������� ������', '��������� ����� ����� ���������� ��� 20% ���� ����� ������ ������� ������', '��������� ����� ����� ���������� ��� 30% ���� ����� ������ ������� ������'); 
//������� �������
addability('23',   '������� �������',  '23',  '25', '50', '100', 'morecreat', '����������� ���������� ������� ������� � ����� �� 25%', '����������� ���������� ������� ������� � ����� �� 50%', '����������� ���������� ������� ������� � ����� �� 100%'); 
//������� ���
addability('24',   '������� ���',  '24',  '10', '15', '20', 'fight', '����������� � ���� ����� ����� �������� ��� �� 10%', '����������� � ���� ����� ����� �������� ��� �� 15%', '����������� � ���� ����� ����� �������� ��� �� 20%'); 
//��������
addability('25',   '��������',  '25',  '10', '15', '20', 'arrow', '����������� � ���� ����� ����� �������� �� 10%', '����������� � ���� ����� ����� �������� �� 15%', '����������� � ���� ����� ����� �������� �� 20%'); 
//�����
addability('26',   '�����',  '26',  '5', '10', '15', 'luck', '��� 5% ���� ��������� ����� �������� �� ����� �����', '��� 10% ���� ��������� ����� �������� �� ����� �����', '��� 15% ���� ��������� ����� �������� �� ����� �����'); 
//��������
addability('27',   '��������',  '27',  '20', '50', '100', 'medicine', '��������������� �������� ���� ������� �� 20% ����� ������� ���� � �����', '��������������� �������� ���� ������� �� 50% ����� ������� ���� � �����', '��������������� �������� ���� ������� �� 100% ����� ������� ���� � �����'); 
//��������
addability('28',   '��������',  '28',  '5', '10', '15', 'economy', '��������� ������� �� ����� �� 5%', '��������� ������� �� ����� �� 10%', '��������� ������� �� ����� �� 15%'); 
//���������������
addability('29',   '���������������',  '29',  '5', '10', '15', 'nalog', '����������� ������ ��� ��������� ������ �� 5%', '����������� ������ ��� ��������� ������ �� 10%', '����������� ������ ��� ��������� ������ �� 15%'); 
//����������
addability('30',   '����������',  '30',  '10', '15', '20', 'castle', '����������� �����������, ��������� ��������������� �������, �� ����� ����� ������ �����, �� 10%', '����������� �����������, ��������� ��������������� �������, �� ����� ����� ������ �����, �� 15%', '����������� �����������, ��������� ��������������� �������, �� ����� ����� ������ �����, �� 20%'); 
echo("<font color='green'>������ � ������� additional ��������</font><br>");



//��������� ������ �� �����
//         ����    ������
$castle = array('�����������', '�������������', '���������', '�����', '�����', '�������', '����', '������� ����� I', '������� ����� II', '����', '��������', '�������', '�������', '', '', '', '', '', '', '');
addcastle ('people', $castle);
$castle = array('��� �������', '��������� ��� �������', '���������', '������', '�����', '�������', '������� ����', '������� ����� I', '������� ����� II', '������������', '��������', '�������', '������ ����������', '', '', '', '', '', '', '');
addcastle ('elf', $castle);
$castle = array('������������ I ������', '������������ II ������', '������������ III ������', '�����', '�����', '�������', '���������', '������� ����� I', '������� ����� II', '����', '��������', '�������', '��������', '', '', '', '', '', '', '');
addcastle ('hnom', $castle);
$castle = array('������ �������', '����� �������', '������ �����', '�����', '�����', '�������', '����������', '������� ����� I', '������� ����� II', '����������', '��������', '�������', '����� �����', '', '', '', '', '', '', '');
addcastle ('druid', $castle);
$castle = array('�����������', '�������������', '���������', '����������� �����', '�����', '�������', '��� ������', '������� ����� I', '������� ����� II', '����', '��������', '�������', '�������� ��������', '', '', '', '', '', '', '');
addcastle ('necro', $castle);
$castle = array('�����������', '�������������', '���������', '������������� �����', '�����', '�������', '��� ������', '������� ����� I', '������� ����� II', '����', '��������', '�������', '����� ���', '', '', '', '', '', '', '');
addcastle ('hell', $castle);
echo("<font color='green'>������ � ������� buildings ��������</font><br>");

//��� ���� � �������� ������!!!!!!!!!!!!!!
//����:
//1 - ������; 2 - ���; 3 - �����; 4 - ����; 5 - �����; 6 - ����� ����; 7 - ������ ����; 8 - �����; 9 - ����; 10 - ������; 11 - ����
//������� ����
//      �  ��������       ��� ������    �������    �������          ����   ��� (������, ���...))
additem(1,  "���",              1,         4,      "sknife.jpg",    5,     7);
additem(2,  "������� ���",      1,         8,      "mknife.jpg",    10,    7);
additem(3,  "�����      ",      1,         12,     "bknife.jpg",    20,    7);
additem(4,  "�������� ���",     1,         16,     "ksword.jpg",    25,    7);
additem(5,  "������ ���",       1,         20,     "msword.jpg",    30,    7);
additem(6,  "��������� ���",    1,         24,     "bsword.jpg",    35,    7);
additem(7,  "������� ���",      1,         28,     "ssword.jpg",    40,    7);
additem(8,  "������� ���",      1,         32,     "lsword.jpg",    50,    7);
additem(9,  "��������� ���",    1,         36,     "nsword.jpg",    55,    7);
additem(10, "���� �����",       1,         8,      "mmolot.jpg",    9,     7);
additem(11, "����� �����",      1,         10,     "gmolot.jpg",    15,    7);
additem(12, "������� �����",    1,         14,     "lmolot.jpg",    22,    7);
additem(13, "����� �����",      1,         18,     "kmolot.jpg",    25,    7);
additem(14, "������",           1,         2,      "mdubin.jpg",    3,     7);
additem(15, "������� ������",   1,         6,      "bdubin.jpg",    4,     7);
additem(16, "������ ���������", 1,         22,     "msekir.jpg",    32,    7);
additem(17, "������",           1,         26,     "mbulav.jpg",    37,    7);
additem(18, "���������� ������",1,         30,     "sbulav.jpg",    43,    7);
additem(19, "������� ������",   1,         34,     "bbulav.jpg",    52,    7);
additem(20, "�������",          1,         10,     "stopor.jpg",    13,    7);
additem(21, "�����",            1,         12,     "mtopor.jpg",    22,    7);
additem(22, "����� ���������",  1,         16,     "dtopor.jpg",    26,    7);
additem(23, "����� ����",       1,         20,     "otopor.jpg",    31,    7);
additem(24, "������� �����",    1,         24,     "btopor.jpg",    56,    7);
additem(25, "������",           1,         38,     "jataga.jpg",    60,    7);
additem(26, "������",           1,         42,     "palica.jpg",    72,    7);
additem(27, "��� ������",       1,         40,     "tsword.jpg",    70,    7);
additem(28, "��������� ���",    1,         10,     "s__luk.jpg",    16,    7);
additem(29, "������� ���",      1,         16,     "m__luk.jpg",    27,    7);
additem(30, "������� ���",      1,         22,     "b__luk.jpg",    33,    7);
additem(31, "�������",          1,         28,     "sarbal.jpg",    42,    7);
additem(32, "��� �����",        1,         34,     "elfluk.jpg",    55,    7);
additem(33, "������� �������",  1,         40,     "barbal.jpg",    69,    7);
additem(34, "��������� ���",    2,         4,      "sarmor.jpg",    10,    6);
additem(35, "�������� ���",     2,         8,      "karmor.jpg",    15,    6);
additem(36, "������ ���",       2,         12,     "marmor.jpg",    20,    6);
additem(37, "��������� ���",    2,         16,     "barmor.jpg",    25,    6);
additem(38, "������� ���",      2,         20,     "tarmor.jpg",    30,    6);
additem(39, "��� � �������",    2,         24,     "rarmor.jpg",    35,    6);
additem(40, "���������� ���",   2,         28,     "garmor.jpg",    40,    6);
additem(41, "������� ���",      2,         32,     "iarmor.jpg",    45,    6);
additem(42, "��������� ���",    2,         36,     "narmor.jpg",    50,    6);
additem(43, "��� �������� ����",2,         40,     "oarmor.jpg",    55,    6);
additem(44, "��������� ����",   2,         1,      "s_head.jpg",    4,     1);
additem(45, "���������� ����",  2,         3,      "w_head.jpg",    8,     1);
additem(46, "������ ����",      2,         5,      "m_head.jpg",    12,    1);
additem(47, "��������� ����",   2,         7,      "b_head.jpg",    16,    1);
additem(48, "��������� ����",   2,         9,      "k_head.jpg",    20,    1);
additem(49, "���� ����",        1,         2,      "a_tors.jpg",    5,     4);
additem(50, "���� ������",      2,         5,      "p_tors.jpg",    15,    4);
additem(51, "���� ��������",    3,         8,      "d_tors.jpg",    20,    4);
additem(52, "�����",            2,         1,      "s_shit.jpg",    2,     9);
additem(53, "��������� �����",  2,         3,      "k_shit.jpg",    5,     9);
additem(54, "������",           2,         2,      "ssapog.jpg",    7,     8);
additem(55, "�������� ������",  2,         3,      "osapog.jpg",    10,    8);
additem(56, "��������� ����",   2,         4,      "ksapog.jpg",    13,    8);
additem(57, "������ �����",     3,         5,      "esapog.jpg",    16,    8);
additem(58, "�������� �����",   2,         2,      "k_bron.jpg",    8,     3);
additem(59, "������ ��������",  2,         3,      "m_bron.jpg",    12,    3);
additem(60, "������ �����",     2,         4,      "e_bron.jpg",    16,    3);
additem(61, "������� �����",    2,         5,      "r_bron.jpg",    20,    3);
additem(62, "��������� �����",  2,         6,      "b_bron.jpg",    24,    3);
additem(63, "��������� �����",  2,         7,      "n_bron.jpg",    28,    3);
additem(64, "��������� �����",  2,         8,      "v_bron.jpg",    32,    3);
additem(65, "����� ������",     2,         9,      "t_bron.jpg",    46,    3);
additem(66, "������ ����",      1,         2,      "a_ring.jpg",    10,    5);
additem(67, "������ �����",     4,         20,     "m_ring.jpg",    10,    5);
additem(68, "������ ������",    2,         2,      "p_ring.jpg",    10,    5);
additem(69, "������ ��������",  3,         2,      "d_ring.jpg",    10,    5);
additem(70, "���� - �������",   2,         5,      "nplash.jpg",    10,    10);
additem(71, "���� �������",     2,         10,     "dplash.jpg",    20,    10);
additem(72, "���������� ����",  5,         50,     "mplash.jpg",    25,    10);
additem(73, "������ �����",     1,         5,      "u_amul.jpg",    20,    2);
additem(74, "������ �����",     4,         15,     "d_amul.jpg",    20,    2);
additem(75, "������ ����",      1,         10,     "a_amul.jpg",    35,    2);
additem(76, "���������� ������",5,         25,     "m_amul.jpg",    30,    2);
additem(77, "��������� �����",  2,         12,     "mebron.jpg",    50,    3);
additem(78, "�������������� �����",2,      13,     "etbron.jpg",    65,    3); 
additem(79, "����� ��������",   2,         15,     "trbron.jpg",    80,    3); 
additem(80, "����� ������ ������",2,      17,     "dkbron.jpg",    95,    3); 
additem(81, "������ �������",   2,         6,      "vsapog.jpg",    20,    8); 
additem(82, "������ ������",    2,         7,      "asapog.jpg",    25,    8); 
additem(83, "���� ������",      2,         9,      "tsapog.jpg",    30,    8); 
additem(84, "���������� �������", 1,       41,     "varbal.jpg",    75,    7); 
additem(85, "������������ �������", 1,     43,     "aarbal.jpg",    80,    7); 
additem(86, "�������������� �������", 1,   45,     "zarbal.jpg",    90,    7); 
additem(87, "���������",        1,         36,     "rbulav.jpg",    57,    7); 
additem(88, "������ ������",    1,         38,     "dbulav.jpg",    62,    7); 
additem(89, "��� �������� �����",1,        42,     "ge_luk.jpg",    77,    7); 
additem(90, "��� ��������",     1,         44,     "sn_luk.jpg",    88,    7); 
additem(91, "����� �����",      1,         27,     "gtopor.jpg",    60,    7); 
additem(92, "������� ������ ���������",1,  30,     "psekir.jpg",    80,    7); 
additem(93, "��� ��������",     1,         43,     "zsword.jpg",    85,    7); 
additem(94, "��� ������",       1,         45,     "asword.jpg",    95,    7); 
additem(95, "�������",          1,         47,     "hsword.jpg",    110,   7); 
additem(96, "��� ���������",    1,         50,     "psword.jpg",    140,   7); 
additem(97, "׸���� ������",    1,         52,     "gsword.jpg",    160,   7); 
additem(98, "���� �������",     1,         55,     "dsword.jpg",    190,   7); 
additem(99, "��������",         1,         15,     "mestar.jpg",    30,    7); 
additem(100,"���������",        1,         16,     "dastar.jpg",    32,    7); 
additem(101,"�������",          1,         17,     "igstar.jpg",    34,    7); 
additem(102,"���������",        1,         18,     "krstar.jpg",    36,    7); 
additem(103,"���������",        1,         20,     "prstar.jpg",    38,    7); 
additem(104,"�������",          1,         22,     "ststar.jpg",    40,    7); 
additem(105,"�������",          1,         26,     "spstar.jpg",    44,    7); 
additem(107,"��� ����������",   1,         72,     "tksword.jpg",   350,   7); 
additem(108,"��� �������",      1,         75,     "answord.jpg",   380,   7); 
additem(109,"����� �������",    1,         25,     "puposoh.jpg",   60,    11);
additem(110,"����� ����",       1,         30,     "poposoh.jpg",   70,    11);
additem(111,"����� ������",     1,         35,     "moposoh.jpg",   80,    11);
additem(112,"����� ��������",   1,         40,     "raposoh.jpg",   90,    11);
additem(113,"����� ����",       1,         45,     "fiposoh.jpg",   100,   11);
additem(114,"����� ��������� ����",1,      50,     "fbposoh.jpg",   110,   11);
additem(115,"����� �������",    1,         55,     "enposoh.jpg",   120,   11);
additem(116,"����� ������",     1,         60,     "leposoh.jpg",   130,   11);
additem(117,"����� ���������� ����",1,     65,     "veposoh.jpg",   140,   11);
additem(118,"����� ��������",   1,         70,     "arposoh.jpg",   150,   11);
additem(119,"������� ����",     1,         80,     "s_serp.jpg",    450,   7);
additem(120,"��� ������� �����",1,         85,     "ge_bow.jpg",    500,   7);
additem(121,"���� ���������",   1,         90,     "fk_pika.jpg",   550,   7);
additem(122,"����� ��������",   1,         95,     "tr_ace.jpg",    600,   7);
additem(123,"������� �������",  1,         100,    "dr_arbal.jpg",  650,   7);
additem(124,"�������� ���",     1,         106,    "fr_blade.jpg",  780,   7);
additem(125,"����������� ���",  1,         70,     "ar_armor.jpg",  200,   6);
additem(126,"������� ��������", 1,         75,     "bl_posoh.jpg",  200,   11);
additem(127,"����� �����",      1,         80,     "re_posoh.jpg",  250,   11);
additem(128,"����� �������",    1,         85,     "sh_posoh.jpg",  300,   11);
additem(129,"������������",     1,         90,     "el_posoh.jpg",  350,   11);
additem(130,"����� ���������",  1,         95,     "fi_posoh.jpg",  400,   11);
additem(131,"���������� �����", 1,         100,    "as_posoh.jpg",  450,   11);

echo("<font color='green'>������ � ������� allitems ��������</font><br>");

//���������� 
//      �    ��������          ���     ��� ������  c������  �������      ����
addcast(1,  "������",           1,         1,         10,   "arrow.jpg",  1);
addcast(2,  "������",           1,         1,         15,   "smerc.jpg",  2);
addcast(3,  "�������",          1,         2,         20,   "leche.jpg",  3);
addcast(4,  "������� ������",   1,         1,         20,   "ledar.jpg",  2);
addcast(5,  "�������� �����",   1,         3,         25,   "visos.jpg",  6);
addcast(6,  "��������",         2,         1,         12,   "meteo.jpg",  1);
addcast(7,  "������ �����",     2,         1,         14,   "sernd.jpg",  2);
addcast(8,  "�������",          2,         1,         20,   "vspis.jpg",  2);
addcast(9,  "�������",          2,         1,         50,   "podze.jpg",  5);
addcast(10, "����� ������",     2,         4,         60,   "volna.jpg",  7);
addcast(11, "����������� �����",2,         1,         70,   "mdozd.jpg",  9);
addcast(12, "������",           3,         5,	      11,   "hypno.jpg",  2);
addcast(13, "������������",     3,         6,	      0,    "zabiv.jpg",  3);
addcast(14, "���������",        3,         7,	      25,   "prokl.jpg",  3);
addcast(15, "������� �� �����", 3,         8,	      15,   "vlech.jpg",  2);
addcast(16, "���������",        3,         4,	      70,   "kamik.jpg",  9);
addcast(17, "�������� ���",     2,         1,	      65,   "fireball.jpg",  8);
addcast(18, "Ҹ���� �������",   1,         2,	      25,   "darklek.jpg",   4);
addcast(19, "������� �������",  1,         2,	      30,   "liglek.jpg",    5);
addcast(20, "Ҹ���� ������",    3,         5,	      5,    "darkhyp.jpg",   8);
addcast(21, "�������� ���",     3,         5,	      7,    "fireeye.jpg",   1);
addcast(22, "�������� �������", 3,         9,	      2,    "manadown.jpg",  2);
addcast(23, "���������� ����",  2,         1,	      52,   "vlfire.jpg",    5);
addcast(24, "���� ����",        1,         1,	      62,   "linepow.jpg",   8);
addcast(25, "������� �����",    3,         3,	      30,   "takelife.jpg",  8);
addcast(26, "������� �������",  3,         9,	      4,    "takeener.jpg",  8);
addcast(27, "�������������",    3,         5,	      2,    "inhyp.jpg",     5);
echo("<font color='green'>������ � ������� allcasts ��������</font><br>");

//���� �������:
//        �  ��������               ������             ��������
addevent (1, "�������� ����",        1,                 60);
addevent (2, "�������� ������",      1,                 50);
addevent (3, "�������� ������",      1,                 40);
addevent (4, "�������� ���������",   1,                 30);
addevent (5, "�������� ����������", 1,                 20);
addevent (6, "�������������",        2,                 5);
addevent (7, "����������",           2,                 4);
addevent (8, "�����",                2,                 3);
addevent (9, "������",               2,                 2);
addevent (10, "����",                 2,                 1);
echo("<font color='green'>������ � ������� events ��������</font><br>");

//�������� ������
mkdir('maps');
for ($i = 1; $i < 21; $i++)
{
   for ($j = 1; $j < 21; $j++)
   {
	   //������ ������
	   $name = "maps/".$i."x".$j.".map";
	   $file = fopen($name, "w");

	   //����� ������
	   for ($a = 1; $a < 11; $a++)
	   {
		   for ($b = 1; $b < 11; $b++)
		   {
			   fputs($file, "0*0=0\n");
		   }
	   }
	fclose ($file);
   }
}

//�������� ������
mkdir('maps/heroes');
for ($i = 1; $i < 21; $i++)
{
   for ($j = 1; $j < 21; $j++)
   {
	   //������ ������
	   $name = "maps/heroes/".$i."x".$j.".map";
	   $file = fopen($name, "w");

	   //����� ������
	   for ($a = 1; $a < 11; $a++)
	   {
		   for ($b = 1; $b < 11; $b++)
		   {
			   fputs($file, "0*0=0\n");
		   }
	   }
	fclose ($file);
   }
}

?>

<form action="install.php" method=post>
<input type='hidden' name='start' value='345'>
<input type='submit' value = '  �����  ' style='background:#000099; font-WEIGHT: BOLD; COLOR: white; cursor: hand; list-style: lower-alpha; filter: Alpha(Opacity=20, FinishOpacity=100, Style=3)'>
</form>

<?
}

?>






