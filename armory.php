<?
include "functions.php";

$lg = trim($HTTP_COOKIE_VARS["nativeland"]);
if ($lg != $login) 
  exit();

FromBattle($lg);

//�������
if ($action == 2)
{

	//������ �� �������
	if (!empty($myitems))
	{
		//�������� ���� ��������
		$cena = round(getfrom('name', $myitems, 'allitems', 'cena')*getdata($login, 'economic', 'curse')*0.5);

		//��������� ��� ������
		change ($login, 'economic', 'money', getdata($login, 'economic', 'money')+$cena);

    //������� ���
    ChangeWeight($login, -4);

		//������� �������
		kickitem($login, $myitems);
	}
}

//�������
if ($action == 3)
{

	//������ �� �������
	if (!empty($item))
	{
		//�������� ���� ��������
		$cena = 200*round(getfrom('name', $item, 'allitems', 'cena')*getdata($login, 'economic', 'curse'));

		//�������� ����� ��������
		$number = getfrom('name', $item, 'allitems', 'num');

		//�������� ��� ��������
		$type = getfrom('name', $item, 'allitems', 'num');

		//����� ��� ����� � ��� ������...
		$place = getfrom('name', $item, 'allitems', 'type');
		
		//������������ ����� � �������� ����
		if ($place == 1) {$field = 'golova';}
		if ($place == 2) {$field = 'shea';}
		if ($place == 3) {$field = 'telo';}
		if ($place == 4) {$field = 'tors';}
		if ($place == 5) {$field = 'palec';}
		if ($place == 6) {$field = 'leftruka';}
		if ($place == 7) {$field = 'rightruka';}
		if ($place == 8) {$field = 'nogi';}
		if ($place == 9) {$field = 'koleni';}
		if ($place == 10) {$field = 'plash';}
		if ($place == 11) {$field = 'rightruka';}

		//����� ����� �������
		$here = getdata($login, 'items', $field);

		if ($here == 0)
			{

      //� ������ �� ������?
      $total = getdata($login, 'status', 'timeout');
      $max = getdata($login, 'abilities', 'charism');
      if (($total+4) > $max*2)
      {
        messagebox("���� ������������ �� ��������� ����� ��� �� ���� ������, ��� �� ��� �����", "armory.php?login=".$login);
      }

			//� ���� �� � ��� ������� �����
			if ($cena < getdata($login, 'economic', 'money'))
				{
				//�������� ������
				change ($login, 'economic', 'money', getdata($login, 'economic', 'money')-$cena);

        //����������� ���
        ChangeWeight($login, 4);

				//������ �������
				change ($login, 'items', $field, $number);

				//���������
				$msg = "<br>�� ������� ������ ".getfrom('num', $number, 'allitems', 'name');
				}
				else
				{
					$msg = "<br>� ��� ������������ ����� ��� ������� ��������. ������� ����� ".$cena." ".getdata($login, 'economic', 'moneyname');
				}
			}
			else
				{
				$what = getfrom('num', getdata($login, 'items', $field), 'allitems', name);
				$msg = "<br>������� �������� ".$what;
				}
	}
}

//����������...
?>
<title>�������</title>
<link rel='stylesheet' type='text/css' href='style.css'/>
<body background='images/back.jpe'>
<?

//�����������
echo ("<center><h2> ������� </h2>(<a href='city.php?login=$login'>� �����</a>)</center><br>");
HelpMe(15, 1);
echo("<center>����� ���������� � �������, ".getdata($login, 'hero', 'name')."<br><br>");

echo("<table border=1 width=95% CELLSPACING=0 CELLPADDING=0>");
echo("<tr><td align=center width=50%>������ � �������</td><td align=center>���� ����</td></tr>");
echo("<tr><td align=center>");
echo("<form action='armory.php' method=post>");
echo("<input type='hidden' name='login' value=$login>");
echo("<input type='hidden' name='action' value=1>");
echo("<br><select name='part' style='background:#000099; font-WEIGHT: BOLD; COLOR: white; cursor: hand; list-style: lower-alpha; filter: Alpha(Opacity=20, FinishOpacity=100, Style=3)'>");
echo("<option value=7>������</option>");
echo("<option value=6>����</option>");
echo("<option value=3>�����</option>");
echo("<option value=1>�����</option>");
echo("<option value=2>�������</option>");
echo("<option value=4>�����</option>");
echo("<option value=9>�����</option>");
echo("<option value=8>������</option>");
echo("<option value=5>������</option>");
echo("<option value=10>�����</option>");
echo("<option value=11>������</option>");
echo("</select>");
?>
	<br><br>
	<input type=submit value=' ������� ' style='background:#000099; font-WEIGHT: BOLD; COLOR: white; cursor: hand; list-style: lower-alpha; filter: Alpha(Opacity=20, FinishOpacity=100, Style=3)'>
	</form></td>
<?
echo("<td align=center>");
echo("<form action='armory.php'  method=post>");
echo("<input type='hidden' name='login' value=$login>");
echo("<input type='hidden' name='action' value=2>");
?>
	<br>
	<?
	allmyitems($login, 'myitems');
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
  $money = getdata($login, 'economic', 'money');
  echo("<tr><td colspan=4 align=center><b>���� ����������: $money <img src='images/menu/gold.gif'></b></td></tr>");
	echo ("<tr><td align=center>��������</td><td align=center>�����������</td><td align=center>��������</td><td align=center>����</td></tr>");
	table($part, $login);
	echo ("</table></center>");
}

echo("</td></tr></table>".$msg."</center>");
ban();

?>