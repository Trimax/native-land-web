<?

include "functions.php";

//��� � �����
echo ("<html>\n<head>\n<link rel='stylesheet' type='text/css' href='style.css'/>\n<title>Native Land</title>\n</head>\n<body background='images\back.jpe'>");

//��� ������������
function printall()
{
	echo("<center><table border=1 width=90% CELLSPACING=0 CELLPADDING=0>");
	echo("<tr><td align=center>�����</td><td align=center width=40%>������ ���� (�����)</td></tr>");
	$ath = mysql_query("select * from money;");
	if ($ath)
	{
		while ($rw = mysql_fetch_row($ath))
		{
			echo("<tr><td align=center>".$rw[0]."</td><td align=center>".$rw[1]."</td></tr>");
		}
	}

	//��� ������
	if (isadmin($lg) == 1)
	{
		echo("<tr><td colspan=2 align=center>");
		echo("<form action='money.php' method=post>");
		indexuserlist('login');
		echo("</form>");
		echo("</td></tr>");
	}

	//����� �������
	echo("</table></center>");
}

//������� ����� ����
echo("<center><font size=16 color=darkblue>����� ������</font><br>����� �� ������ �� ������������ ������ ������ ���� ����� � ������ �������� ����<br></center>");
printall();


?>
