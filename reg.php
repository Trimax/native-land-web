<?
include "functions.php";

if (empty($pfinal))
	{
  
  //�������� ��������� ���������� IP
	if ((findip($REMOTE_ADDR) == 1)&&(1 == 0))
		{
    echo ("<link rel='stylesheet' type='text/css' href='style.css'/>");
		echo ("<body background='images\back.jpe'>");
		echo ("<font color=green><b>��������!</b> � ����� IP ������ ���-�� ��� ���������������! ����������� ����������. ��������� � ��������������� ��� ��������� �������.<br></font>");
		echo ("<a href='index.php'>�����</a>");
		exit();
		}
	?>
        <script>
        function help()
        {
        window.open("help.php");
        }
        </script>
	<html>
	<title>����������� � ���� Native Land</title>
	<body background="images\back.jpe">
	<center><h2><font color=yellow>�������� �����������</font></h2></center>
	<center><h4><a href="javascript:help();"><font color=white>������</font></a></h4></center>
  <center>����, ���������� �������� "*" (��������) ����������� ��� ����������</center>
    <form action="reg.php" method=post>
	<input type="hidden" name='pfinal' value=123>
	<center>
	<table border=1 width=95% cols=3 CELLSPACING=0 CELLPADDING=0>
    <tr width=24%><td align=center><font color=#006600><b>��������:</b></td><td align=center><font color=#006600><b>��������:</b></td><td align=center><font color=#006600><b>�����������:</b></td></tr>
	<tr><td><font color=white>��� ������������:<font color=red>*</font></td><td align = center><input type='text' name='login' style='background:#000099; font-WEIGHT: BOLD; COLOR: white; cursor: hand; list-style: lower-alpha; filter: Alpha(Opacity=20, FinishOpacity=100, Style=3)'></td><td>��� ���� ������ �� ������ ������� � �������. ��� ������ �������� �� ���������� ���� ��� (�) ����. � �� �� ����� ���� ��������.</td></tr>
    <tr><td><font color=white>������:<font color=red>*</font></td><td align = center><input type='password' name='passwd' style='background:#000099; font-WEIGHT: BOLD; COLOR: white; cursor: hand; list-style: lower-alpha; filter: Alpha(Opacity=20, FinishOpacity=100, Style=3)'></td><td>�����, ��� ����� ���������� ������ ���� ������. ��� ���������� ��� �������������</td></tr>
	<tr><td><font color=white>�������:<font color=red>*</font></td><td align = center><input type='text' name='surname' style='background:#000099; font-WEIGHT: BOLD; COLOR: white; cursor: hand; list-style: lower-alpha; filter: Alpha(Opacity=20, FinishOpacity=100, Style=3)'></td><td>���� ������� ���������� ��� ���������� ����.</td></tr>
	<tr><td><font color=white>���:<font color=red>*</font></td><td align = center><input type='text' name='mname' style='background:#000099; font-WEIGHT: BOLD; COLOR: white; cursor: hand; list-style: lower-alpha; filter: Alpha(Opacity=20, FinishOpacity=100, Style=3)'></td><td>���� ��� ���������� ��� ������� � ����.</td></tr>
	<tr><td><font color=white>������ ����������:<font color=red>*</font></td><td align = center><input type='text' style='background:#000099; font-WEIGHT: BOLD; COLOR: white; cursor: hand; list-style: lower-alpha; filter: Alpha(Opacity=20, FinishOpacity=100, Style=3)' name='country'></td><td>���������� ��� ���������� �������� �������. (���������������)</td></tr>
	<tr><td><font color=white>�����:<font color=red>*</font></td><td align = center><input type='text' name='city' style='background:#000099; font-WEIGHT: BOLD; COLOR: white; cursor: hand; list-style: lower-alpha; filter: Alpha(Opacity=20, FinishOpacity=100, Style=3)'></td><td>��������� ��� ���������� �������� �������. (���������������)</td></tr>
	<tr><td><font color=white>E-Mail:<font color=red>*</font></td><td align = center><input type='text' name='email' style='background:#000099; font-WEIGHT: BOLD; COLOR: white; cursor: hand; list-style: lower-alpha; filter: Alpha(Opacity=20, FinishOpacity=100, Style=3)'></td><td>��������� ��� ����� � ����.</td></tr>
	<tr><td><font color=white>URL:</td><td align = center><input type='text' name='url' style='background:#000099; font-WEIGHT: BOLD; COLOR: white; cursor: hand; list-style: lower-alpha; filter: Alpha(Opacity=20, FinishOpacity=100, Style=3)'></td><td>���� � ��� ���� ��� ����������� ����, ����� ��� ������������.</td></tr>
	<tr><td><font color=white>ICQ:</td><td align = center><input type='text' name='icq' style='background:#000099; font-WEIGHT: BOLD; COLOR: white; cursor: hand; list-style: lower-alpha; filter: Alpha(Opacity=20, FinishOpacity=100, Style=3)'></td><td>���� � ��� ���� ICQ, �� ��� ����, ����� ������ ������ ����� ��������� � ����, ������� ��� �����.</td></tr>
	<tr><td><font color=white>� ����:</td><td align = center colspan = 2><textarea cols=70 rows=10 name='osebe' maxlength=255 style='background:#000099; font-WEIGHT: BOLD; COLOR: white; cursor: hand; list-style: lower-alpha; filter: Alpha(Opacity=20, FinishOpacity=100, Style=3)'></textarea></td></tr>
	<tr><td><font color=white>����������� ������:<font color=red>*</font></td><td align = center><input type='text' name='cquest' style='background:#000099; font-WEIGHT: BOLD; COLOR: white; cursor: hand; list-style: lower-alpha; filter: Alpha(Opacity=20, FinishOpacity=100, Style=3)'></td><td>���� �� �������� ��������� ��� ������, ��, ����� ������������ ���, ������ ������ ��� ������.</td></tr>
	<tr><td><font color=white>����������� �����:<font color=red>*</font></td><td align = center><input type='text' style='background:#000099; font-WEIGHT: BOLD; COLOR: white; cursor: hand; list-style: lower-alpha; filter: Alpha(Opacity=20, FinishOpacity=100, Style=3)' name='cansw'></td><td>� ���� �� ������ ���� �����, �� ��� ������ ��������� ������.</td></tr>
	<tr><td><font color=blue>��� �����<font color=red>*</font></td><td align = center><input type='text' name='heroname' style='background:#000099; font-WEIGHT: BOLD; COLOR: white; cursor: hand; list-style: lower-alpha; filter: Alpha(Opacity=20, FinishOpacity=100, Style=3)'></td><td>��� ���� ������ �� ������ �������� � ����, ��� ����������� ����� ������������.</td></tr>
    <tr><td><font color=blue>���� �����:</td><td align = center><select name='race' style='background:#000099; font-WEIGHT: BOLD; COLOR: white; cursor: hand; list-style: lower-alpha; filter: Alpha(Opacity=20, FinishOpacity=100, Style=3)'>
	<option value='people'>�������</option>
	<option value='elf'>����</option>
	<option value='hnom'>����</option>
	<option value='druid'>�����</option>
	<option value='necro'>���������</option>
	<option value='hell'>������</option>
	</select></td><td align=center>
	<table border = 1 CELLSPACING=0 CELLPADDING=0>
	<tr><td align=center>����:</td><td>����:</td><td>������:</td><td>������ �� �����:</td><td>������������:</td><td>���������� ����:</td><td>������:</td></tr>
	<tr><td align=center>�������</td><td align=center>+1</td><td align=center>+2</td><td align=center>0</td><td align=center>+2</td><td align=center>0</td><td align=center>+1</td></tr>
    <tr><td align=center>����</td><td align=center>+2</td><td align=center>0</td><td align=center>+2</td><td align=center>0</td><td align=center>+1</td><td align=center>+1</td></tr>
    <tr><td align=center>����</td><td align=center>+2</td><td align=center>+2</td><td align=center>+2</td><td align=center>0</td><td align=center>0</td><td align=center>0</td></tr>
    <tr><td align=center>�����</td><td align=center>0</td><td align=center>+2</td><td align=center>0</td><td align=center>0</td><td align=center>+1</td><td align=center>+3</td></tr>
    <tr><td align=center>���������</td><td align=center>+2</td><td align=center>0</td><td align=center>+1</td><td align=center>0</td><td align=center>+1</td><td align=center>+2</td></tr>
    <tr><td align=center>������</td><td align=center>+1</td><td align=center>+2</td><td align=center>0</td><td align=center>+1</td><td align=center>+1</td><td align=center>+1</td></tr>
	</table>
	</td></tr>
	<tr><td><font color=blue>��� �����:</td><td align = center><select name='type' style='background:#000099; font-WEIGHT: BOLD; COLOR: white; cursor: hand; list-style: lower-alpha; filter: Alpha(Opacity=20, FinishOpacity=100, Style=3)'>
	<option value='knight'>������</option>
	<option value='archer'>�������</option>
	<option value='mag'>���</option>
	<option value='lekar'>��������</option>
	<option value='barbarian'>������</option>
	<option value='wizard'>���������</option>
	</select></td><td align=center>
	<table border = 1 CELLSPACING=0 CELLPADDING=0>
	<tr><td align=center>���:</td><td>����:</td><td>������</td><td>������ �� �����:</td><td>������������:</td><td>���������� ����:</td><td>������:</td></tr>
	<tr><td align=center>������</td><td align=center>+2</td><td align=center>+2</td><td align=center>0</td><td align=center>+1</td><td align=center>0</td><td align=center>0</td></tr>
    <tr><td align=center>�������</td><td align=center>+1</td><td align=center>+2</td><td align=center>0</td><td align=center>+2</td><td align=center>0</td><td align=center>0</td></tr>
    <tr><td align=center>���</td><td align=center>0</td><td align=center>0</td><td align=center>0</td><td align=center>0</td><td align=center>+3</td><td align=center>+2</td></tr>
    <tr><td align=center>��������</td><td align=center>0</td><td align=center>0</td><td align=center>0</td><td align=center>0</td><td align=center>+2</td><td align=center>+3</td></tr>
    <tr><td align=center>���������</td><td align=center>0</td><td align=center>0</td><td align=center>0</td><td align=center>0</td><td align=center>+4</td><td align=center>+1</td></tr>
    <tr><td align=center>������</td><td align=center>+3</td><td align=center>+1</td><td align=center>0</td><td align=center>+1</td><td align=center>0</td><td align=center>0</td></tr>
	</table>	
	</td></tr>
    <tr><td><font color=yellow>�������� �����:<font color=red>*</font></td><td align = center><input type='text' name='moneyname' style='background:#000099; font-WEIGHT: BOLD; COLOR: white; cursor: hand; list-style: lower-alpha; filter: Alpha(Opacity=20, FinishOpacity=100, Style=3)'></td><td>��� ����� ���������� ������ � ����� ������ (��. �. ��������, [�����])</td></tr> 
	<tr><td><font color=yellow>���� ����� � �������:<font color=red>*</font></td><td align = center><input type='text' name='curse' style='background:#000099; font-WEIGHT: BOLD; COLOR: white; cursor: hand; list-style: lower-alpha; filter: Alpha(Opacity=20, FinishOpacity=100, Style=3)' maxlength=3></td><td>����� �����, ������� ����. �.�. �� 1 ������� ������, �� ��������� n ������ ������ � ����� ������.</td></tr>
    <tr><td><font color=yellow>�������� ������:<font color=red>*</font></td><td align = center><input type='text' name='gcountry' style='background:#000099; font-WEIGHT: BOLD; COLOR: white; cursor: hand; list-style: lower-alpha; filter: Alpha(Opacity=20, FinishOpacity=100, Style=3)'></td><td>������ ��� ����������� ���� ������������ �� ������ �������� � ����.</td></tr>
    <tr><td><font color=yellow>�������� �������:<font color=red>*</font></td><td align = center><input type='text' name='gcapital' style='background:#000099; font-WEIGHT: BOLD; COLOR: white; cursor: hand; list-style: lower-alpha; filter: Alpha(Opacity=20, FinishOpacity=100, Style=3)'></td><td>��� ����� ���������� ������� ������ �����������</td></tr>
    <tr><td><font color=yellow>��������� ������:<font color=red>*</font></td><td align = center><select name='res' style='background:#000099; font-WEIGHT: BOLD; COLOR: white; cursor: hand; list-style: lower-alpha; filter: Alpha(Opacity=20, FinishOpacity=100, Style=3)'>
	<option value='random'>�����</option>
	<option value='metal'>������</option>
	<option value='rock'>������</option>
	<option value='wood'>������</option>
	</select></td><td>����� �� ��� �������� ����� ���������� � ����� ������? ��������� ��� ��� ������� �������� � ����� ��������� ��� ������������� �� ������ �����.</td></tr>
	</table>
	<br><input type='submit' name='prefinish' value=' ������������������ ' style='background:#000099; font-WEIGHT: BOLD; COLOR: white; cursor: hand; list-style: lower-alpha; filter: Alpha(Opacity=20, FinishOpacity=100, Style=3)'>
	</center>
	</form>
	<?
	}
	else
	{

	//�������� ����� ����� � ������������ e-mail ������ � �������� �������� ���� �����
	$login = trim($login);
	$ohno = 0;
	$err = 0;
	$ml = 0;
	if ((strlen($login) < 2)||(strlen($passwd) < 2)||(strlen($surname) < 2)||(strlen($mname) < 2)||(strlen($country) < 2)||(strlen($city) < 2)||(strlen($email) < 2)||(strlen($heroname) < 2)||(strlen($race) < 2)||(strlen($type) < 2)||(strlen($moneyname) < 2)||(strlen($curse) < 1)||(strlen($gcountry) < 2)||(strlen($gcapital) < 2)||(strlen($res) < 2))
	{
		$ohno = 1;
	}

	//��������� ��� �� ������������
	if (!empty($login))
	{
    $login = trim($login);
		if (!preg_match("/[0-9a-z]/i", $login))
		{
			$lge = 1;
		}
    if ((strtolower($login) == 'settings')||(strtolower($login) == 'admin'))
    {
      $lge = 1;
    }
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
	if (!empty($curse))
	{
		if (!preg_match("/[0-9]/i", $curse))
		{
			$err = 1;
		}
	}

	//���� ������?
	if (($ohno != 0)||($err != 0)||($ml != 0)||($lge != 0))
	{
		echo ("<br>");
	    echo ("<script language=JavaScript>");
		echo ("function rt()");
	    echo ("{");
	    echo ("window.history.go(-1);");
		echo ("}");
	    echo ("</script>");
		echo ("<body background='images\back.jpe'>");
		if ($ohno != 0)
		{
			echo ("<font color=green>������. �� ��� ���� ��������� ��� ����� ������ �� ����� ������ ���� ��������. ��������� ��� ����.<br><br>");
		}
	    if ($lge != 0)
		{
			echo ("��� ������������ ����� �������� ������ �� ���������� ���� �(���) ����. �����, ��� ����������� ��������� ����� Admin � Settings<br><br>");
		}
	    if ($ml != 0)
		{
			echo ("����� ��������� ������������ ���������� e-mail ������.<br><br>");
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

	//� ����� ��� �������
	if (hasuser($login) == 1)
		{
		echo ("<br>");
	    echo ("<script language=JavaScript>");
		echo ("function rt()");
	    echo ("{");
	    echo ("window.history.go(-1);");
		echo ("}");
	    echo ("</script>");	
		echo ("<body background='images\back.jpe'>");
		echo ("<font color=green>��������! ������������ � ����� '������ ������������' ��� ���������������. ������� ��� ������������!</font>");
		?>
		<form action="javascript:rt();">
	    <input type='submit' name='stop' value='  �����  ' style='background:#000099; font-WEIGHT: BOLD; COLOR: white; cursor: hand; list-style: lower-alpha; filter: Alpha(Opacity=20, FinishOpacity=100, Style=3)'>      
		</form>
	    <?
		exit();		
		}
		else
		{
			//�� ���������. ���������� ������.

			//������������ ����� :)
			setcookie("nativeland", $login, time()+3600*24);
			setcookie("password", $passwd, time()+3600*24);

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
			
			//����������� � �����
			baselink();

			//���������� � ������ ������
			mysql_query ("insert into battles values('$login', 0, '0', 0);");

      //���������� �� ����� ������
			mysql_query ("insert into army values('$login', 0, 0, 0, 0);");

			//���������� � ����� ������
			mysql_query ("insert into battle values('$login', '0', '0', '0', '0', '0', '', '0', '0', '0');");

			//��������� ���������� � �������
			mysql_query("insert into status values ('$login', '0', '0', '0', '0', '0');");

			//��������� ��������� ����������
      $tm = time();
			mysql_query("insert into inf values ('$login', '$icq', '$osebe', '0', '$tm', '0', '0', '0', '0', '0', '0', '0', '0', '0');");

			//�����������
			mysql_query ("insert into lostpass values ('$login', '$cquest', '$cansw');");

			//��������� ���������� �� ������
			mysql_query("insert into users values ('$login', '$passwd', '$surname', '$mname', '$city', '$country', '$email', '$url');");

			//���������	���������� �� ��������� ������
			mysql_query("insert into hero values ('$login', '$heroname', '$val', '$lv', '5', '$race', '$type', '$hl', '$login');");

			//��������� ���������� �� ��������� �����������
			mysql_query("insert into economic values ('$login', '$r1', '$r2', '$r3', '$r4', '$curse', '$moneyname', '$p', '$n');");

			//��������� ���������� � ����������� ������
			$r = rand(1, 3);
			if ($r == 2) 
			{
				$r = 6;
			}
			if ($r == 3) 
			{
				$r = 12;
			}
			mysql_query("insert into magic values ('$login', ".$r.", 0, 0, 0, 0, 0);");

      //���������� � ��������
			mysql_query("insert into bottles values ('$login', '0', '0', '0', '0', '0', '0', '0', '0', '0', '0', '0', '0', '0', '0', '0');");

      //��������� ���������� � ��������� ����������
			mysql_query("insert into temp values ('$login', '', '');");

			//��������� ���������� � ����� �� ��������� (���������)
			mysql_query("insert into items values ('$login', '$val', '$val', '$val', '$val', '$val', '$val', '$val', '$val', '$val', '$val', '$val');");
			
			//��������� ������� � ������
//			mysql_query("insert into inclan values ('$login', '0', '0', '0');");

			//��������� ������
			if ($res == "random")
				{
				$i = rand(2, 0);
				if ($i == 0)
				   {
				   $res = "metal";
				   }
				if ($i == 1)
				   {
				   $res = "rock";
				   }
				if ($i == 2)
				   {
				   $res = "wood";
				   }
				}
			//��������� ���������� � �����������
			mysql_query("insert into info values ('$login', '$gcountry', '$gcapital', '$res');");

			//��������� ���������� � ���������� � �����
			mysql_query("insert into city values ('$login', '$login', '$val', '$val', '$val', '$val', '$val', '$val', '$val', '$val', '$val', '$val', '$val', '$val', '$val', '$val', '$val', '$val', '$val', '$val', '$val', '$val');");

			//��������� ���������� � �������
			$t = time();
			mysql_query("insert into time values ('$login', '$t', '10', '0');");

			//��������� ���������� � ����� ������
			mysql_query("insert into unions values ('$login', '$login', '$login', '$login', '$login');");

			//���������� �� IP �������
			mysql_query("insert into ip values ('$login', '$REMOTE_ADDR');");

			//���������� � ���. �������
			mysql_query("insert into help values ('$login', '0', '0', '0', '0');");

			//����������� ����������� ������
			$power = 1;
			$protect = 1;
			$magicpower = 0;
			$know = 1;
			$charism = 1;
			$dexterity = 1;
			$intel = 1;
			$naturemagic = 1;
			$combatmagic = 0;
			$mindmagic = 0;
      $ab = "0";

			switch ($race)
			{
			case "people":
				$power++;
      	$protect++;
	     	$protect++;
				$charism++;
        $charism++;
				$know++;
				break;
			case "elf":
				$power++;
				$power++;
			    $dexterity++;
				$dexterity++;
				$charism++;
				$know++;
				break;
			case "hnom":
				$power++;
				$power++;
	    	$protect++;
				$protect++;
				$dexterity++;
				$dexterity++;
				break;
			case "druid":
				$protect++;
			    $know++;
			    $know++;
			    $know++;
				$naturemagic++;
				$naturemagic++;
				break;
			case "necro":
				$power++;
				$power++;
				$know++;
				$know++;
				$naturemagic++;
        $charism++;
				break;
			case "hell":
				$power++;
				$protect++;
				$protect++;
				$know++;
				$naturemagic++;
				$charism++;
				break;
			}

			switch ($type)
			{
			case "knight":
				$power++;
				$power++;
				$protect++;
				$protect++;
				$charism++;
        $ab = "N3";
				break;
			case "archer":
				$power++;
				$protect++;
				$protect++;
        $charism++;
        $charism++;
        $ab = "N25";
				break;
			case "mag":
				$naturemagic++;
        $naturemagic++;
				$naturemagic++;
        $know++;
        $know++;
        $ab = "N16";
				break;
			case "lekar":
				$naturemagic++;
				$naturemagic++;
        $know++;
        $know++;
        $know++;
        $ab = "N17";
				break;
			case "barbarian":
				$power++;
				$power++;
				$power++;
				$protect++;
        $charism++;
        $ab = "N4";
				break;
			case "wizard":
				$naturemagic++;
				$naturemagic++;
				$naturemagic++;
				$naturemagic++;
        $know++;
        $ab = "N18";
				break;
			}

			//��������� ���������� � ���������� � �����
			mysql_query("insert into abilities values ('$login', '$power', '$protect', '$magicpower', '$know', '$charism', '$dexterity', '$intel', '$naturemagic', '$combatmagic', '$mindmagic');");

			//���������� � ���. ������������
			mysql_query("insert into newchar values ('$login', '$ab', '0', '0', '0', '0', '0', '0', '0', '0', '0', '0', '0', '0', '0', '0', '0');");

      //���������
			mysql_query("insert into inventory values ('$login', '$ab', '0', '0', '0', '0', '0', '0', '0', '0', '0', '0', '0', '0', '0', '0', '0');");

			//��������� �����
			randomplace($login);

			//������ �����...
			$file = fopen("data/logs/".$login.".log", "w");
			fclose($file);
			mkdir("data/mail/".$login, 0700);
			$file = fopen("data/trade/".$login, "w");
			fclose($file);

			//������� ���������� ��� ������������ � ������ �� �������� �����������
			echo ("<br>");
			echo ("<script language=JavaScript>");
			echo ("function rt()");
			echo ("{");
		    echo("window.location.href('game.php?action=1');");		
			echo ("}");
			echo ("</script>");
			echo ("<body background='images\back.jpe'>");
			echo ("<h2><font color=yellow>�����������!</font></h2>");
			echo ("<font color=green>�� ������� ���������������� � �������. ������� ������ '����' ��� �������� �� �������� �����������</font><br><br>");
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
			echo ("<tr><td align=center><font color=green>������ �� �����</font></td><td align=center><font color=green>$dexterity</font></td></tr>");
			echo ("<tr><td align=center><font color=green>������</font></td><td align=center><font color=green>$know</font></td></tr>");
			echo ("<tr><td align=center><font color=green>������������</font></td><td align=center><font color=green>$charism</font></td></tr>");
			echo ("<tr><td align=center><font color=green>���������� ����</font></td><td align=center><font color=green>$naturemagic</font></td></tr>");
		
      //���������� �������������������� +1
      $adm = getadmin();
      $btls = getfrom('admin', $adm, 'settings', 'f2');
      $btls++;
      setto('admin', $adm, 'settings', 'f2', $btls);
      
			//���������� ������ �������
			?>
			</table>
			<center>
			����� � ����, �������� ������ "���������". ���� ����� ������ ������� ����� ����
			</center>

			<br>
			<form action="javascript:rt();">
			<center><input type='submit' name='finish' value='  ����  ' style='background:#000099; font-WEIGHT: BOLD; COLOR: white; cursor: hand; list-style: lower-alpha; filter: Alpha(Opacity=20, FinishOpacity=100, Style=3)'></center>
			</form>
			<?

			//�������� �����
			sms($login, "������� ������������� Native Land", "����� ���������� � ���� Native Land (������ �����). � ���� ��� ����� ������������� ������� ������� �� �������������� �������� ����. ���� �� ��������� �������� �������������� ���� matrix � �����-����������, �� ����� � ��������� ����� #nl � mIRC �� ������ ������ ���� ������� ����� ������� �������, ���������� ������ ������������� � ��������. ��������� ������ ����� ��� ������� � ������� ����, ������� ������� ����� � ���� �����. ����� ����� ������������ � ���������� ����� ��� ������ �������� ���������. ������������� ��� �������� � ����� ����, ����� � ������� ����� ���� �� ���� �����. ���� ���� ��������� �� ������: <a href='http://nld.spb.ru'>http://nld.spb.ru</a> ��� <a href='http://nld.spb.ru'>http://nativeland.spb.ru</a> ��� ����, ����� ������ ��������� ������ � Native Land �� �������� ��� ������������ � ������������� �� ����. ������� � ����� � ���� ����� ��� �� ������: <a href='http://nld.spb.ru/help.php'>http://nld.spb.ru/help.php</a> ���������� e-mail ��������������: admin@nld.spb.ru IRC: Trimax; Local ICQ: 1358; ������ ��� ������� �������� ����� � �������������, ����������� ���� Native Land. ������ �������������: Trimax � Teider.");
			?>
			<script>
				window.open("http://nld.spb.ru/forums/");
			</script>
			<?
		}
	}
?>


