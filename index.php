<?

//���������� ������ �������
include "count.php";

//������ ������
if (!empty($lostpwd))
{
	?>
	<link rel='stylesheet' type='text/css' href='style.css'/>
	<title>Native Land</title>
	<body background="images/back.jpe">
	<center>
	<h2>������� �������������� ������. ��� 1.</h2>
	<form action='index.php' method=post>
	<input type='hidden' name='getlost' value='shit'>
	<table border=0 width=40%>
	<tr><td colspan=2 align=center>������� ����� �� ������, ������� ��������� ��� �����������</td></tr>
	<tr><td>��� ������������:</td><td><input type='text' name='llogin' style='background:#000099; font-WEIGHT: BOLD; COLOR: white; cursor: hand; list-style: lower-alpha; filter: Alpha(Opacity=20, FinishOpacity=100, Style=3)'></td></tr>
	<tr><td align=center colspan=2><input type='submit' style='background:#000099; font-WEIGHT: BOLD; COLOR: white; cursor: hand; list-style: lower-alpha; filter: Alpha(Opacity=20, FinishOpacity=100, Style=3)'></tr>
	</table>
	</form>
	<br><a href='index.php'>��������� �����</a>
	</center>
	<?
	exit();
}

//�������-���� ����� ������������ ����������:
if (!empty($answer))
{
	if (!empty($flogin))
	{
		if (hasuser($flogin) == 1)
			{
			if (getdata($flogin, 'lostpass', 'answer') == $answer)
				{
				?>
				<link rel='stylesheet' type='text/css' href='style.css'/>
				<title>Native Land</title>
				<body background="images/back.jpe">
				<center>
				<h2>��������� ��������������</h2>
				<table border=0 width=40%>
				<tr><td colspan=2 align=center>��� ���������� ������</td></tr>
				<tr><td>��� ������������:</td><td>
				<?
				echo ($flogin);
				?>
				</td></tr>
				<tr><td>������:</td><td>
				<?
				echo (getdata($flogin, 'users', 'pwd'));
				?>
				</td></tr>
				<tr><td colspan=2 align=center>�� ������� ������ ������ :)</td></tr>
				</table>
				<br><a href='index.php'>��������� �����</a>
				</center>
				<?			
				exit();
				} else
					{
					echo ("<link rel='stylesheet' type='text/css' href='style.css'/>");
					echo ("<title>Native Land</title>");
					echo ("<body background='images/back.jpe'>");
					echo ("<center>");
					echo ("����� ��������!<br>");
					echo ("<a href='index.php'>�����</a>");
					exit();
					}
			} else
				{
				echo ("<link rel='stylesheet' type='text/css' href='style.css'/>");
				echo ("<title>Native Land</title>");
				echo ("<body background='images/back.jpe'>");
				echo ("<center>");
				echo ("����� ������������ �� ������!<br>");
				echo ("<a href='index.php'>�����</a>");
				exit();
				}
	}
}

//������ �� �������
if (!empty($getlost))
{
	if (!empty($llogin))
		{
		if (hasuser($llogin) == 1)
			{
			?>
			<link rel='stylesheet' type='text/css' href='style.css'/>
			<title>Native Land</title>
			<body background="images/back.jpe">
			<center>
			<h2>������� �������������� ������. ��� 2.</h2>
			<form action='index.php' method=post>
			<table border=1 width=40% CELLSPACING=0 CELLPADDING=0>
			<tr><td colspan=2 align=center>������� �����:</td></tr>
			<tr><td>
			<?
				echo ("<input type='hidden' name='flogin' value=$llogin>");
				echo (getdata($llogin, 'lostpass', 'question'));
			?>
			</td><td><input type='text' name='answer' style='background:#000099; font-WEIGHT: BOLD; COLOR: white; cursor: hand; list-style: lower-alpha; filter: Alpha(Opacity=20, FinishOpacity=100, Style=3)'></td></tr>
			<tr><td align=center colspan=2><input type='submit' style='background:#000099; font-WEIGHT: BOLD; COLOR: white; cursor: hand; list-style: lower-alpha; filter: Alpha(Opacity=20, FinishOpacity=100, Style=3)'></tr>
			</table>
			</form>
			<br><a href='index.php'>��������� �����</a>
			</center>
			<?
			exit();
			} else
				{
				echo ("<link rel='stylesheet' type='text/css' href='style.css'/>");
				echo ("<title>Native Land</title>");
				echo ("<body background='images/back.jpe'>");
				echo ("<center>");
				echo ("����� ������������ �� ������!<br>");
				echo ("<a href='index.php'>�����</a>");
				exit();
				}
		}
}


//���� ��� ����� �����, ������ ���� �� �����������, ������ - ���� ����������.
$file_array = fopen("config.ini.php", "r");
if (!$file_array)
   {
   echo ("<META HTTP-EQUIV='REFRESH' CONTENT=0 TARGET='install.php'>");
   exit();
   }
$temp = trim(fgets($file_array, 255));
$temp = trim(fgets($file_array, 255));
$host = trim(fgets($file_array, 255));
$db = trim(fgets($file_array, 255));
$user = trim(fgets($file_array, 255));
$pass = trim(fgets($file_array, 255));

$ms = @mysql_connect($host, $user, $pass);

if (!$ms)
{
	echo("<title>Native Land</title><font color=green><link rel='stylesheet' type='text/css' href='style.css'/>��������! ��������� ������ ��� ������� ������������� � ������� MySql. �������� ��������� ������:<br>1) ������ ������ �������� (��������� � �������� ������� � �������� ��� � ����)<br>2) ����������� ������� ������ ��� ����������� � �������.<br> � ����� ������, ��������� � ��������������� �������.</font>");
	/*
	echo("<font color=green><link rel='stylesheet' type='text/css' href='style.css'/>��������! ��������� ������ ��� ������� ������������� � ������� MySql. �������� ��������� ������:<br>1) ������ ������ �������� (��������� � �������� ������� � �������� ��� � ����)<br>2)����������� ������� ������ ��� ����������� � �������.<br>���� ������:<br>���� ����: $host<br>����: $db<br>��� ������������: $user<br>������: $pass<br>�������� ������ ����� <a href='config.php'>�����</a></font>");
	*/
	exit();
}

$rs = mysql_select_db($db, $ms);

if (!$rs)
{
	echo("<title>Native Land</title><h2><font color=blue><link rel='stylesheet' type='text/css' href='style.css'/>������!</font></h2>");
	echo("<font color=green>��������! ��������� ������ ��� ������� ���������� � ����� ������ $db<br>���������, ��������� �� ������� � ���. ���� ���, �� �������� ��� ����� <a href='config.php'>�����</a><br>���� ������ ������� �� ��������, �� ���������� �������������� ����. ������� ��� ����� <a href='install.php'>�����</a></font>");
	exit();
}

//����� �� ��� ����������?
$lg = trim($HTTP_COOKIE_VARS["nativeland"]);
$pw = trim($HTTP_COOKIE_VARS["password"]);
if (finduser($lg, $pw) == 1)
{
	moveto('game.php?action=1');
	exit();
}

if (empty($login))
{
	?>
	<style>
	img.x
	{
		position:absolute;
		left:0;
		top:0;
		z-index:-1;
	}
	img.z
	{
		position:absolute;
		left:-10;
		top:-10;
		z-index:-100;
	}
	</style>
	<html>
  <!-- <body scroll=no> -->
  <body>
	<head><link rel='stylesheet' type='text/css' href='style.css'/><title>Native Land</title></head>
	<script>
	var sw;
	var sh;
	sw = screen.width-20;
	sh  = screen.height-140;
	sw = screen.width;
	sh  = screen.height-80;
	document.write("<img class=x src=images/startup.jpg width=" + sw + " height=" + sh + ">");
	document.write("<img class=z src=http://active.mns.ru/banner/show.php width=0 height=0>");
	</script>
	<center>
   	<form action="index.php" method=post>
	<br>	<br>	<br>	<br>	<br><br>	<br><br>	<br><br>	<br><br>	<br><br>	<br><br><br>
	<table border=0 width=40%>
    <tr><td colspan=2 align=center><font color=black size=5><b>����������� � ����<b></font></td></tr>
	<tr><td align=right><font color=white><b>��� ������������:</b></font></td><td>
	<?
		indexuserlist('login');
	?>
	</td></tr>
	<tr><td align=right><font color=white><b>������:</b></font></tr><td><input type="password" name="pss" style="background:#000099; font-WEIGHT: BOLD; COLOR: white; cursor: hand; list-style: lower-alpha; filter: Alpha(Opacity=20, FinishOpacity=100, Style=3)">
	<a href='index.php?lostpwd=1'><font color=white><b>������?</b></font></a></td></tr>
     <tr><td colspan=2 align=center><br><input type="submit" name="enter" value="  ����  " style="background:#000099; font-WEIGHT: BOLD; COLOR: white; cursor: hand; list-style: lower-alpha; filter: Alpha(Opacity=20, FinishOpacity=100, Style=3)"></td></tr>
	</form>
	<tr><td colspan=2 align=center><br><form action="reg.php" method=post><input type="submit" name="enter" value="  �����������  " style="background:#000099; font-WEIGHT: BOLD; COLOR: white; cursor: hand; list-style: lower-alpha; filter: Alpha(Opacity=20, FinishOpacity=100, Style=3)"></form></td></tr>
	<tr><td align=center colspan=2><a href='help.php'><font color=yellow><b>������������</b></font></a><br></td></tr>
	<tr><td align=center colspan=2><a href='forums/'><font color=yellow><b>�����</b></font></a><br></td></tr>
  </table>
	</center>
	</html>
	<?
}
else
{

//���� �� ���������
if (finduser($login, $pss) == 1)
	{
		$how = toomuch($login);

		if (($how > 15)&&($login != 'Admin'))
		{
			echo ("<title>Native Land</title><br>");
			echo ("<body background='images\back.jpe'>");
			echo ("<h2><font color=yellow>��������!</font></h2>");
			echo ("<font color=green>������� ����� �������������. ������� �����.<br><br>");
			echo ("<a href=index.php>�����</a>");
			exit();
		} else
		{
      if (hasuser($login) == 0)
      {
        setcookie("nativeland");
        setcookie("password");
        moveto("index.php");
      }
			setcookie("nativeland", $login, time()+3600*24);
			setcookie("password", $pss, time()+3600*24);
			change ($login, 'status', 'online', '1');
      echo ("<META HTTP-EQUIV='REFRESH' CONTENT=0 TARGET='game.php?action=1'>");			
		}
	}
	else
	{
	echo ("<title>Native Land</title><br>");
	echo ("<body background='images\back.jpe'>");
	echo ("<h2><font color=yellow>��������!</font></h2>");
	echo ("<font color=green>������������ � ����� ������ �� ������. ��������� ������������ �������� ���� ������. <br><br>");
	echo ("��������, ��� �� ������ �� ����������������. ��� ���� ����� ������������������, ������� ���������� ������.<br><br>");
	echo ("��� ������, ������ ���������� ������������� ��� �������</font><br>");
	?>
	<title>Native Land</title>
	<center>
	<form action="index.php">
	<input type='submit' name='finish' value='  �����  ' style='background:#000099; font-WEIGHT: BOLD; COLOR: white; cursor: hand; list-style: lower-alpha; filter: Alpha(Opacity=20, FinishOpacity=100, Style=3)'>
	</form>
	<form action="reg.php" method=post>
	<input type='submit' name='reg' value=' ������������������ ' style='background:#000099; font-WEIGHT: BOLD; COLOR: white; cursor: hand; list-style: lower-alpha; filter: Alpha(Opacity=20, FinishOpacity=100, Style=3)'>
	</form>
	</center>
	<?
	}
}

?>
<title>Native Land</title>
<center><font color=white><b>��� �������: <a href="mailto:admin@nld.spb.ru"><font color=yellow><b>Trimax</b></font></a></b></font></center>