<?
include "functions.php";

$lg = trim($HTTP_COOKIE_VARS["nativeland"]);
if ($lg != $login)
  exit();
ban();

FromBattle($lg);

//�������
if ($action == 2)
{

	//������ �� �������
	if (!empty($myitems))
	{
		//�������� ���� ��������
		$cena = round(getfrom('name', $myitems, 'allcasts', 'cena')*getdata($login, 'economic', 'curse')*0.5);

		//��������� ��� ������
		change ($login, 'economic', 'money', getdata($login, 'economic', 'money')+$cena);

		//������� �������
		kickcitem($login, $myitems);
	}
}

//�������
if ($action == 3)
{

	//������ �� �������
	if (!empty($item))
	{
		//�������� ���� ��������
		$cena = 6*round(getfrom('name', $item, 'allcasts', 'cena')*15*getdata($login, 'economic', 'curse'));

		//�������� ����� ��������
		$number = getfrom('name', $item, 'allcasts', 'num');

		//���� ��������� �����
		$free = 0;
		$lg = $login;
		if (getdata($lg, 'magic', 'cast6') == 0) {$free = 6;}
		if (getdata($lg, 'magic', 'cast5') == 0) {$free = 5;}
		if (getdata($lg, 'magic', 'cast4') == 0) {$free = 4;}
		if (getdata($lg, 'magic', 'cast3') == 0) {$free = 3;}
		if (getdata($lg, 'magic', 'cast2') == 0) {$free = 2;}
		if (getdata($lg, 'magic', 'cast1') == 0) {$free = 1;}		

		//�������� �� �����
		if ($free != 0)
			{
			//� ���� �� � ��� ������� �����
			if ($cena < getdata($login, 'economic', 'money'))
				{
				//�������� ������
				change ($login, 'economic', 'money', getdata($login, 'economic', 'money')-$cena);

				//������ �������
				change ($login, 'magic', 'cast'.$free, $number);

				//���������
				$msg = "<br>�� ������� ������ ���� ".getfrom('num', $number, 'allcasts', 'name');
				}
				else
				{
					$msg = "<br>� ��� ������������ ����� ��� ������� ��������. ������� ����� ".$cena." ".getdata($login, 'economic', 'moneyname');
				}
			}
			else
				{
				$msg = "��� 6 ������� ��� ���������� ��� ������";
				}
	}
}

//����������...
?>
<title>������� �����</title>
<link rel='stylesheet' type='text/css' href='style.css'/>
<body background='images/back.jpe'>
<?

//�����������
echo ("<center><h2> ������� ����� </h2>(<a href='city.php?login=$login'>� �����</a>)</center><br>");
HelpMe(18, 1);
echo("<center>����� ���������� � ������� �����, ".getdata($login, 'hero', 'name')."<br><br>");

echo("<table border=1 width=95% CELLSPACING=0 CELLPADDING=0>");
echo("<tr><td align=center width=50%>���������� � �������</td><td align=center>���� ����������</td></tr>");
echo("<tr><td align=center>");
echo("<form action='guild.php' method=post>");
echo("<input type='hidden' name='login' value=$login>");
echo("<input type='hidden' name='action' value=1>");
echo("<br><select name='part' style='background:#000099; font-WEIGHT: BOLD; COLOR: white; cursor: hand; list-style: lower-alpha; filter: Alpha(Opacity=20, FinishOpacity=100, Style=3)'>");
echo("<option value=1>����� �������</option>");
echo("<option value=2>������ �����</option>");
echo("<option value=3>����� ������</option>");
echo("</select>");
?>
	<br><br>
	<input type=submit value=' ������� ' style='background:#000099; font-WEIGHT: BOLD; COLOR: white; cursor: hand; list-style: lower-alpha; filter: Alpha(Opacity=20, FinishOpacity=100, Style=3)'>
	</form></td>
<?
echo("<td align=center>");
echo("<form action='guild.php'  method=post>");
echo("<input type='hidden' name='login' value=$login>");
echo("<input type='hidden' name='action' value=2>");
?>
	<br>
	<?
	allmycasts($login, 'myitems');
	?>
	<br><br>
	<input type=submit value=' ������� ' style='background:#000099; font-WEIGHT: BOLD; COLOR: white; cursor: hand; list-style: lower-alpha; filter: Alpha(Opacity=20, FinishOpacity=100, Style=3)'>
	</form></td>
	<tr><td colspan=2 align=center>
<?

//������
if ($action == 1)
{
	//������� ������� � ������
	echo ("<center><table border=1 width=90% CELLSPACING=0 CELLPADDING=0>");
	echo ("<tr><td align=center>��������</td><td align=center>�����������</td><td align=center>��������</td><td align=center>����</td></tr>");
	ctable($part, $login);
	echo ("</table></center>");
}

echo("</td></tr></table>".$msg."</center>");

?>