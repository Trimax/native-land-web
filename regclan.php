<?
include "functions.php";

//���� �� ����������� ����� (���� 1 � 1)
$cena = 5000000;

//���� ���?
function hasclan($username)
{
//link();
$usr = mysql_query("select * from clans;");
$find = 0;
if ($usr)
   {
   while ($user = mysql_fetch_array($usr))
      {
      if (($user['clan'] == $username))
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

//�� ����� �� ����� ���� ��
function isnotadmin($username)
{
//link();
$usr = mysql_query("select * from clans;");
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

//�������� ����� ������������
$lg = trim($HTTP_COOKIE_VARS["nativeland"]);
if (hasuser($lg) != 1) 
{
   exit();
}

//���� ����� �� ����� �� �� (20�18 - ������������ ����� ��������� ����)
$rx = getdata($lg, 'coords', 'rx');
$ry = getdata($lg, 'coords', 'ry');

//���������
if (($rx == 20)&&($ry == 18))
{
	//�� ��
} else
{
//	exit();
}

//����� ���� ��� ����������� ���
if (!empty($clan))
{
	//��������� ����������...
	$nal = getdata($lg, 'economic', 'money');
	$cr = getdata($lg, 'economic', 'curse');
  $std = $cena*$cr;

	//��������
	if ($nal <= $std)
	{
		messagebox("<center>� ��� ������������ �����. ��� ����������� ����� ���������� ".$std." ".getdata($lg, 'economic', 'moneyname')."</center>", "regclan.php");
	} else
	{
		//��������� �������������
		if (empty($clan)||empty($description)||empty($link)||empty($logo)||empty($gerb)||empty($procent))
		{
			messagebox("<center>���������� ��������� ��� ����</center>", "regclan.php");
		} else
		{
			//����� ���� � ����� ������ ��� ����
			if (hasclan($clan) == 1)
			{
				messagebox("<center>���� � ����� ��������� ��� ��������������. ���������� ���� ������.</center>", "regclan.php");
			} else
			{
				//���� ����� �� �����
				if (preg_match("/[0-9]/i", $procent))
				{

					//��� ���� ��������. ���������
					if (($rx == 20)&&($ry == 18))
					{
						//�� ��
					} else
					{
 						//exit();
					}

					//�������� ������
					change($lg, 'economic', 'money', $nal - $std);

					//������������ ����
					mysql_query("insert into clans values ('$clan', '$lg', '$description', '$link', '$logo', '$gerb', '$procent', '100', '0', '0', '0');");

					//������� �������������� � ����
					change($lg, 'inclan', 'clan', $clan);
					change($lg, 'inclan', 'status', 'Admin');

					//������
					messagebox("<center>��� ���� $clan ������� ���������������</center>", "city.php?login=".$lg);
				} else
				{
					messagebox("<center>����� ������ ���� ������. ������, ����� ��� ������ ���� ������ 90</center>", "regclan.php");
				}
			} //OK
		} //�� ��� ���� ���������
		exit();
	} //����� ����
	moveto('regclan.php');
}

//��������� ��������
echo("<link rel='stylesheet' type='text/css' href='style.css'/>");
echo("<title>������� ���������� �� ���������� �� �������</title>");
echo("<body background=images/back.jpe>");
if (isnotadmin($lg) == 0)
{
	echo("<center>");
	echo("<form action=regclan.php method=post>");
	echo("<input type=hidden name=login value=".$lg.">");
	echo("<h2>������ ������������� ������</h2>");
  echo("<a href=city.php?login=".$lg.">��������� ����� � �����</a><br>");
	echo("<h3>����� ����������, ".getdata($lg, 'hero', 'name')."</h3>");
	echo("<table border=1 CELLSPACING=0 CELLPADDING=0 width=60%>");
	echo("<tr><td colspan=2 align=center>����������� ������ �����</td></tr>");
	echo("<tr><td align=center width=30%>�������� �����:</td><td align=center><input type=text name=clan style='background:#000099; font-WEIGHT: BOLD; COLOR: white; cursor: hand; list-style: lower-alpha; filter: Alpha(Opacity=20, FinishOpacity=100, Style=3)'></td></tr>");
	echo("<tr><td align=center>�������� �����</td><td align=center><textarea name='description' maxlength=200 cols=60 rows=15 maxlength=255 style='background:#000099; font-WEIGHT: BOLD; COLOR: white; cursor: hand; list-style: lower-alpha; filter: Alpha(Opacity=20, FinishOpacity=100, Style=3)'></textarea></td></tr>");
	echo("<tr><td align=center width=30%>������ �� ����� �����:</td><td align=center><input type=text name=link style='background:#000099; font-WEIGHT: BOLD; COLOR: white; cursor: hand; list-style: lower-alpha; filter: Alpha(Opacity=20, FinishOpacity=100, Style=3)'></td></tr>");
	echo("<tr><td align=center width=30%>������ �� ������� �����:</td><td align=center><input type=text name=logo style='background:#000099; font-WEIGHT: BOLD; COLOR: white; cursor: hand; list-style: lower-alpha; filter: Alpha(Opacity=20, FinishOpacity=100, Style=3)'></td></tr>");
	echo("<tr><td align=center width=30%>������ �� ���� �����:</td><td align=center><input type=text name=gerb style='background:#000099; font-WEIGHT: BOLD; COLOR: white; cursor: hand; list-style: lower-alpha; filter: Alpha(Opacity=20, FinishOpacity=100, Style=3)'></td></tr>");
	echo("<tr><td align=center width=30%>����� ��� ����������� [� ���] (� ������� �� ����� 1 � 1):</td><td align=center><input type=text name=procent value=10 style='background:#000099; font-WEIGHT: BOLD; COLOR: white; cursor: hand; list-style: lower-alpha; filter: Alpha(Opacity=20, FinishOpacity=100, Style=3)'></td></tr>");
	echo("<tr><td colspan=2 align=center><input type=submit value='���������������� ����' style='background:#000099; font-WEIGHT: BOLD; COLOR: white; cursor: hand; list-style: lower-alpha; filter: Alpha(Opacity=20, FinishOpacity=100, Style=3)'>");
  echo("</td></tr>");
	echo("</table>");
	echo("</form>");
  HelpMe(10, 1);
}
else
{
	messagebox("�� ��� ��������� ������ ����� �����", "javascript:window.close();");
}
echo("</center>");

?>