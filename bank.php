<body background="images/back.jpe">
<link rel='stylesheet' type='text/css' href='style.css'/>
<title>������ ���������� �� �������� ������</title>

<?
include "functions.php";
ban();

//�������������
$lg = trim($HTTP_COOKIE_VARS["nativeland"]);
if ($lg != $login) 
  exit();

FromBattle($lg);

//�������� ������ � ������
$ath = mysql_query("select * from clans;");
$count = 0;
if ($ath)
{
	//���� ����
	while ($rw = mysql_fetch_row($ath))
	{
		//���� � �������������
		$name[$count] = $rw[0];
		$admin[$count] = $rw[1];
		$desc[$count] = $rw[2];
		$link[$count] = $rw[3];
		$logo[$count] = $rw[4];
		$gerb[$count] = $rw[5];
		$nalog[$count] = $rw[6];
		$bill[$count] = $rw[7];
		$super1[$count] = $rw[8];
		$super2[$count] = $rw[9];
		$super3[$count] = $rw[10];
		$count++;
	}
}

//������� ������� ������
echo("<center><h1>������ ������������� ������</h1><a href=city.php?login=".$login.">��������� ����� � �����</a><br><table align=center border=1 cellspacing=0 cellpading=0 width=90%>\n");
echo("<tr><td align=center>����</td><td align=center>�������������</td><td align=center>��������</td></tr>");

//�����
for ($i = 0; $i < $count; $i++)
{
	echo("<tr><td align=center>".$name[$i]."</td><td align=center>".$admin[$i]."</td><td align=center><br><form action='claninfo.php' method=post><input type='hidden' name='login' value='".$login."'><input type='hidden' name='admin' value='".$admin[$i]."'><input type='submit' value='��������' style='background:#000099; font-WEIGHT: BOLD; COLOR: white; cursor: hand; list-style: lower-alpha; filter: Alpha(Opacity=20, FinishOpacity=100, Style=3)'></form></td></tr>");
}
echo("</table>");
HelpMe(12, 0);
echo("</center>");

?>