<title>�������� �������</title>
<link rel='stylesheet' type='text/css' href='style.css'/>
<body background='images/back.jpe'>
<?
  include "functions.php";
  $lg = trim($HTTP_COOKIE_VARS["nativeland"]);
  if ((hasuser($lg) == 0)||($login != $lg))
    exit();
  FromBattle($lg);

//������� ����� �������������
function export($name)
{
	echo ("<center><table border=1 width=100% CELLSPACING=0 CELLPADDING=0>");
	echo ("<tr><td align=center>�����</td><td align=center>�������</td><td align=center>�������</td><td align=center>����</td><td align=center>������</td></tr>");
//	link();
	$ath = mysql_query("select * from users;");
	if ($ath)
	{
		while ($rw = mysql_fetch_row($ath))
		{
		if (getdata($rw[0], 'info', 'resource') == 'metal')
			{
			$tp = '������';
			}
		if (getdata($rw[0], 'info', 'resource') == 'rock')
			{
			$tp = '������';
			}
		if (getdata($rw[0], 'info', 'resource') == 'wood')
			{
			$tp = '������';
			}

		if ((getdata($rw[0], 'inf', 'fld5') != 0)||($rw[0] == $name))
			{
		
		echo ("<tr><td align=center>".$rw[0]."</td><td align=center>".$tp."</td><td align=center>");
		if ($name == $rw[0])
			{
				echo ("<a href='game.php?action=45'><font color=blue>[-5]</font></a> ");
			}
		echo (getdata($rw[0], 'inf', 'fld5'));
		if ($name == $rw[0])
			{
				echo (" <a href='game.php?action=42'><font color=blue>[+5]</font></a>");
			}
		echo("</td><td align=center>");
		if ($name == $rw[0])
			{
				echo ("<a href='game.php?action=44'><font color=blue>[-0.2]</font></a> ");
			}
		echo(getdata($rw[0], 'inf', 'fld4'));
		if ($name == $rw[0])
			{
				echo (" <a href='game.php?action=43'><font color=blue>[+0.2]</font></a>");
			}
		echo ("</td><td align=center><a href='game.php?action=46&export=".$rw[0]."'><font color=black><b>�� ������</b></font></a><br><a href='game.php?action=47&export=".$rw[0]."'><font color=#828282><b>�� ������</b></font></a><br><a href='game.php?action=48&export=".$rw[0]."'><font color=brown><b>�� ������</b></font></a></td></tr>");
		}
		}
	}
	echo ("</table></center>");
}

//��������
function trade($login)
{
	?>
	<center>
	<table border=1 width=90% CELLSPACING=0 CELLPADDING=0>
	<tr><td colspan=2 align=center><font color=blue><b>��������</b></font></td></tr>
  <tr><td colspan=2 align=center>
  <table border=0 cellpadding=0 cellspacing=0>
  <tr>
  <?
    echo ("<td valign=top><img src=images/menu/gold.gif alt='".getdata($login, 'economic', 'moneyname')."'></td><td valign=center>".getdata($login, 'economic', 'money')."</td>");
    echo("<td valign=top><img src=images/menu/metal.gif alt='������'></td><td valign=center>".getdata($login, 'economic', 'metal')."</td>");
    echo("<td valign=top><img src=images/menu/rock.gif alt='������'></td><td valign=center>".getdata($login, 'economic', 'rock')."</td>");
    echo("<td valign=top><img src=images/menu/wood.gif alt='������'></td><td valign=center>".getdata($login, 'economic', 'wood')."</td>");
  ?>
  </tr>
  </table>
  </td>
  </tr>
  <tr><td align=center><font color=blue>��������</font></td><td align=center><font color=blue>��������</font></td></tr>
	<tr><td align=center width=30%>����</td><td>
	<form name="trade" action="trade.php" method=post>
	<?
	echo ("<input type='hidden' name='login' value='$login'><center>");
	indexuserlist('userlist');
	?>
	</center>
	</td></tr>
	<tr><td align=center width=30%>���</td><td align=center>
	<select name="what" style='background:#000099; font-WEIGHT: BOLD; COLOR: white; cursor: hand; list-style: lower-alpha; filter: Alpha(Opacity=20, FinishOpacity=100, Style=3)'>
	<option name="metal" value="metal">������</option>
	<option name="rock" value="rock">������</option>
	<option name="wood" value="wood">������</option>
	</select>
	</td></tr>
	<tr><td align=center width=30%>�������</td><td align=center>
	<input type="text" name="how" maxlength=4 cols=4 style='background:#000099; font-WEIGHT: BOLD; COLOR: white; cursor: hand; list-style: lower-alpha; filter: Alpha(Opacity=20, FinishOpacity=100, Style=3)'>
	</td></tr>
	<tr><td colspan=2 align=center><input type="submit" value=" ����� " style='background:#000099; font-WEIGHT: BOLD; COLOR: white; cursor: hand; list-style: lower-alpha; filter: Alpha(Opacity=20, FinishOpacity=100, Style=3)'></td></tr>
	</form>
	<tr><td colspan=2 align=center><b><font color=blue>�������� ��������</font></b><br>
	<?
	readfile("data/trade/".$login);
	?>
	</td></tr>
	<tr><td colspan=2 align=center><b><font color=blue>������� � ����</font></b></td></tr>
	<tr><td colspan=2 align=center>
	<?
		export($login);
	?>
	</td></tr>
	</table>
	</center>
	<?
}  
  
  if (getdata($lg, 'city', 'build5') != 0)
  	trade($lg);
	else
		messagebox("� ��� �� �������� �����. ��������� ���.", "city.php?login=".$lg);
  echo("<center><a href='city.php?login=".$lg."'>����� � �����</a>");
  HelpMe(14, 0);
  echo("</center>");
?>
