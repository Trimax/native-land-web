<?
//������ �������
include "functions.php";

//���� �� ������� ��� ������������, �� �������� ����� �����
$lg = trim($HTTP_COOKIE_VARS["nativeland"]);
$pw = trim($HTTP_COOKIE_VARS["password"]);
ban();

//����������?
if (finduser($lg, $pw) != 1)
{
	moveto('index.php');
	exit();
}

if (($lg != 'Anarki')&&($lg != 'Admin')&&($lg != 'TbMA')&&($lg != 'Dpako')&&($lg != 'LLIyTnick')&&($lg != 'Dictator'))
{
	moveto('edit.php');
	exit();
}

//������
if (($ry == 0)||(empty($ry))) {$ry = 1;}
if (($rx == 0)||(empty($rx))) {$rx = 1;}
if (($ry == 21)||(empty($ry))) {$ry = 20;}
if (($rx == 21)||(empty($rx))) {$rx = 20;}

//������
$ury = $ry-1;
$urx = $rx-1;
$dry = $ry+1;
$drx = $rx+1;

//������
if ($ury == 0) {$ury = 1;}
if ($urx == 0) {$urx = 1;}
if ($ury == 21) {$ury = 20;}
if ($ury == 21) {$ury = 20;}

//������ �����...
$file = fopen("maps/".$rx."x".$ry.".map", "r");
for ($x = 1; $x < 11; $x++)
{
	for ($y = 1; $y < 11; $y++)
	{
		$map[$x][$y] = fgets($file, 255);
	}
}
fclose($file);

//����� �������
echo ("<title>�������������� ����� ��������</title>");
echo ("<link rel='stylesheet' type='text/css' href='mapstyle.css'/><body background='images/terrain/grass.gif'>");

//������� ��� ������������
echo ("<center><h2><font color=white>�������������� �������: $rx x $ry ($lg). ����: $ax x $ay</font></h2></center>");

//�������� ������
$s = $map[$ax][$ay];

//�����
echo ("<form action=edit.php method=post>");
echo ("<input type=hidden name=rx value=$rx>");
echo ("<input type=hidden name=ry value=$ry>");
echo ("<input type=hidden name=ax value=$ax>");
echo ("<input type=hidden name=ay value=$ay>");
echo ("<input type=hidden name=add value=1>");
echo ("<center><table border=1 CELLSPACING=0 CELLPADDING=0>");
echo ("<tr><td align=center>��������</td><td align=center>��������</td></tr>");
echo ("<tr><td align=center>����������</td><td>");
echo ("<select name=ter style='background:#000099; font-WEIGHT: BOLD; COLOR: white; cursor: hand; list-style: lower-alpha; filter: Alpha(Opacity=20, FinishOpacity=100, Style=3)'>");
echo ("<option ");
echo (" value='0'>�����</option>");
if ($s[0] == '0') {echo (" selected");}
echo ("<option ");
if ($s[0] == '1') {echo (" selected");}
echo (" value='1'>����</option>");
echo ("<option ");
if ($s[0] == '2') {echo (" selected");}
echo (" value='2'>�����</option>");
echo ("<option ");
if ($s[0] == '3') {echo (" selected");}
echo (" value='3'>����</option>");
echo ("<option ");
if ($s[0] == '4') {echo (" selected");}
echo (" value='4'>����</option>");
echo ("</select></td></tr>");
echo ("<tr><td align=center>������</td><td><select name=res style='background:#000099; font-WEIGHT: BOLD; COLOR: white; cursor: hand; list-style: lower-alpha; filter: Alpha(Opacity=20, FinishOpacity=100, Style=3)'>");
echo ("<option value='0'>�����</option>");
echo ("<option ");
if ($s[2] == '1') {echo (" selected ");}
echo ("value='1'>������</option>");
echo ("<option ");
if ($s[2] == '2') {echo (" selected ");}
echo ("value='2'>������</option>");
echo ("<option ");
if ($s[2] == '3') {echo (" selected ");}
echo ("value='3'>������</option>");
echo ("<option ");
if ($s[2] == '4') {echo (" selected ");}
echo ("value='4'>�����</option>");
echo ("</select></td></tr>");
echo ("<tr><td align=center>�����</td><td>");
//user2('users');
echo ("<select name='users' style='background:#000099; font-WEIGHT: BOLD; COLOR: white; cursor: hand; list-style: lower-alpha; filter: Alpha(Opacity=20, FinishOpacity=100, Style=3)'>");
echo ("<option value=0>�����</option>");
echo ("<option value=#KLAN>���. ������</option>");
echo ("</select></td></tr>");
echo ("<tr><td align=center colspan=2><input type=submit value='��������' style='background:#000099; font-WEIGHT: BOLD; COLOR: white; cursor: hand; list-style: lower-alpha; filter: Alpha(Opacity=20, FinishOpacity=100, Style=3)'></td></tr>");
echo ("</table></form>");

echo ("<center><a href='edit.php?rx=".$rx."&ry=".$ry."'>�����</a></center>");
?>


