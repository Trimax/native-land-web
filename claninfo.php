<body background="images/back.jpe">
<link rel='stylesheet' type='text/css' href='style.css'/>
<title>���������� � �����</title>

<script>
function wnd(name)
{
  wd = screen.width - 10;
  hg = screen.height - 120;
	window.open(name,11,"toolbar=yes, location=yes, menubar=yes,scrollbars=yes, resizable=yes, width=" + wd + ", height=" + hg + ", left=0, top=0");
}
</script>

<?
include "functions.php";
ban();

//��������� � ���� ����
function addusertoclan($login, $clan)
{
	mysql_query ("insert into inclan values ('$login', '$clan', '0' , '0');");
}

//������ �� �����
function kickuserfromclan($login)
{
	mysql_query("delete from inclan where login = '".$login."';");
}

//������� ����� ������������
function userinclan($username, $table)
{
//link();
$usr = mysql_query("select * from ".$table.";");
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

//�������������
$lg = trim($HTTP_COOKIE_VARS["nativeland"]);
if ($lg != $login) 
  exit();

FromBattle($lg);

//����� ����?
if (!empty($admin))
{

	//������������ �������
	if ($login != $admin)
	{
		//����� �� ����� ��������?
		if ($enter == 1)
		{
			//���������, � �� � ����� �� �� ���? � ����� �� ����� ������� �����?
			if ((userinclan($login, 'inclan') == 0)&&(userinclan($login, 'clans') == 0))
			{
        $send = "<center>������ �� ���������� � ����. <form action='comehere.php' method=post><input type='hidden' name='member' value='".$login."'><input type='hidden' name='admin' value='".$admin."'><br><input type='submit' value='�������' style='background:#000099; font-WEIGHT: BOLD; COLOR: white; cursor: hand; list-style: lower-alpha; filter: Alpha(Opacity=20, FinishOpacity=100, Style=3)'></form><br><a href=javascript:hinfo('".$login."');>���������� � ���������</a><br>� ������ ������, �������� ������ �� ����</center>";
        sms($admin, $login, $send);
				messagebox("���� ������ ���������� � ������������� �����. ��� ����� ����������� � �� �������� ��������� ����� ���������. ��������.", "bank.php?login=".$login);
			}
		}

		//����� �� ����� �����?
		if ($out == 1)
		{
			//���������, � �� � ����� �� �� ���? � ����� �� ����� ������� �����?
			if ((userinclan($login, 'inclan') == 1)&&(userinclan($login, 'clans') == 0))
			{
				kickuserfromclan($login);
				messagebox("�� ������� ����� �� ����� ".$rw[0], "bank.php?login=".$login);
				exit();
			}
		}
	}

	//������ �������
	echo("<center><h1>���������� � �����</h1><a href='bank.php?login=".$login."'>����� � ������ ������</a><br><table border=1  cellspacing=0 cellpading=0 width=90%>");
	if ($login == $admin)
	{
		echo("<tr><td align=center width=30%>��������</td><td align=center>��������</td></tr>");
	} else
	{
		echo("<tr><td align=center width=30%>��������</td><td align=center>��������</td></tr>");
	}

	//�������� ������ � �����
	$ath = mysql_query("select * from clans;");
	$count = 0;
	if ($ath)
	{
		//���� ����
		while ($rw = mysql_fetch_row($ath))
		{
			//��� ����? ����� ������� ����������!
			if ($rw[1] == $admin)
			{
				if ($login != $admin)
				{
					//������� �������
					echo("<tr><td align=center>�������� �����</td><td align=center>".$rw[0]."</td></tr>");
					echo("<tr><td align=center>�������� �����</td><td align=center>".$rw[2]."</td></tr>");
					echo("<tr><td align=center>�������������</td><td align=center>".$rw[1]."</td></tr>");
					echo("<tr><td align=center>�����</td><td align=center>".$rw[6]."</td></tr>");
					echo("<tr><td align=center>���� �����</td><td align=center>".$rw[7]."</td></tr>");
					echo("<tr><td align=center>����������� �����</td><td align=center>".$rw[8].$rw[9].$rw[10]."</td></tr>");
					echo("<tr><td align=center>������ � ������</td><td align=center><a href=javascript:wnd('".$rw[3]."');>�������</a></td></tr>");
					echo("<tr><td align=center>�������</td><td align=center><img src='".$rw[4]."' width=32 height=32></td></tr>");
					echo("<tr><td align=center>����</td><td align=center><img src='".$rw[5]."' width=320 height=240></td></tr><tr><td align=center colspan=2>");
          if (userinclan($login, 'inclan') == 0)
          {
            echo("<form action='claninfo.php' method=post><input type='hidden' name='enter' value=1><input type='hidden' name='login' value='".$login."'><input type='hidden' name='admin' value='".$admin."'><input type='hidden' name='inclan' value='".$rw[0]."'><input type='submit' value='�������� � ����' style='background:#000099; font-WEIGHT: BOLD; COLOR: white; cursor: hand; list-style: lower-alpha; filter: Alpha(Opacity=20, FinishOpacity=100, Style=3)'></form>");
          }
          if (userinclan($login, 'inclan') == 1)
					{
						echo("<form action='claninfo.php' method=post><input type='hidden' name='out' value=1><input type='hidden' name='login' value='".$login."'><input type='hidden' name='admin' value='".$admin."'><input type='submit' value='����� �� �����' style='background:#000099; font-WEIGHT: BOLD; COLOR: white; cursor: hand; list-style: lower-alpha; filter: Alpha(Opacity=20, FinishOpacity=100, Style=3)'></form>");
					}
					echo("</td></tr>");
				} else //���� ��������������
				{
					//������ ������������ �������:
					//1) ��������� ��������� � �����
					if ($save == 1)
					{
						change($admin, 'clans', 'name', $name);
						$rw[0] = $name;
						change($admin, 'clans', 'link', $link);
						$rw[3] = $link;
						change($admin, 'clans', 'description', $desc);
						$rw[2] = $desc;
						change($admin, 'clans', 'logo', $logo);
						$rw[4] = $logo;
						change($admin, 'clans', 'gerb', $gerb);
						$rw[5] = $gerb;
					}

					//������� �������
					echo("<form action='claninfo.php' method=post><input type='hidden' name='save' value=1><input type='hidden' name='login' value='".$login."'><input type='hidden' name='admin' value='".$admin."'>");
					echo("<tr><td align=center>�������� �����</td><td align=center><input type='text' style='background:#000099; font-WEIGHT: BOLD; COLOR: white; cursor: hand; list-style: lower-alpha; filter: Alpha(Opacity=20, FinishOpacity=100, Style=3)' name='name' value='".$rw[0]."'></td></tr>");
					echo("<tr><td align=center>�������� �����</td><td align=center><textarea name='desc' cols=45 rows=6 style='background:#000099; font-WEIGHT: BOLD; COLOR: white; cursor: hand; list-style: lower-alpha; filter: Alpha(Opacity=20, FinishOpacity=100, Style=3)'>".$rw[2]."</textarea></td></tr>");
					echo("<tr><td align=center>�������������</td><td align=center>".$rw[1]."</td></tr>");
					echo("<tr><td align=center>�����</td><td align=center><input type='text' name='nalog' value='".$rw[6]."' style='background:#000099; font-WEIGHT: BOLD; COLOR: white; cursor: hand; list-style: lower-alpha; filter: Alpha(Opacity=20, FinishOpacity=100, Style=3)'></td></tr>");
					echo("<tr><td align=center>���� �����</td><td align=center>".$rw[7]."</td></tr>");					
					echo("<tr><td align=center>����������� �����</td><td align=center>".$rw[8].$rw[9].$rw[10]."</td></tr>");
					echo("<tr><td align=center>������ � ������</td><td align=center><input type='text' name='link' value='".$rw[3]."' style='background:#000099; font-WEIGHT: BOLD; COLOR: white; cursor: hand; list-style: lower-alpha; filter: Alpha(Opacity=20, FinishOpacity=100, Style=3)'></td></tr>");
					echo("<tr><td align=center>�������</td><td align=center><input type='text' name='logo' value='".$rw[4]."' style='background:#000099; font-WEIGHT: BOLD; COLOR: white; cursor: hand; list-style: lower-alpha; filter: Alpha(Opacity=20, FinishOpacity=100, Style=3)'></td></tr>");
					echo("<tr><td align=center>����</td><td align=center><input type='text' name='gerb' value='".$rw[5]."' style='background:#000099; font-WEIGHT: BOLD; COLOR: white; cursor: hand; list-style: lower-alpha; filter: Alpha(Opacity=20, FinishOpacity=100, Style=3)'></td></tr>");
					echo("<tr><td align=center colspan=2><input type='submit' value='��������� ���������' style='background:#000099; font-WEIGHT: BOLD; COLOR: white; cursor: hand; list-style: lower-alpha; filter: Alpha(Opacity=20, FinishOpacity=100, Style=3)'></td></tr>");
					echo("</form>");
				}//$login==$admin
			}//$admin
		}//$rw
	} //$ath

	//���������
	echo("</table></center>");
} //$admin (empty)

?>