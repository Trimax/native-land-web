<link rel='stylesheet' type='text/css' href='style.css'/>
<title>���������� �� ������</title>
<body background='images\back.jpe'>
<center>
<h2>���������� �� ������</h2>
<a href='javascript:window.close()'>�������</a>
</center>
<?
//������ �������
include "functions.php";

//���� �� ������� ��� ������������, �� �������� ����� �����
$lg = trim($HTTP_COOKIE_VARS["nativeland"]);
$pw = trim($HTTP_COOKIE_VARS["password"]);

//���������� ���������
if ($save == 1)
{
	change($lg, 'users', 'surname', $surname);
	change($lg, 'users', 'name', $mname);
	change($lg, 'users', 'country', $country);
	change($lg, 'users', 'city', $city);
	change($lg, 'users', 'email', $email);
	change($lg, 'users', 'url', $url);
	change($lg, 'inf', 'icq', $icq);
	change($lg, 'inf', 'about', $osebe);
	moveto("info.php?name=".$lg);
	exit();
}

//��������� ����������?
if ($change == 1)
{
	echo("<center>	<form action='info.php' method=post><input type='hidden' name='save' value=1><table border=1 CELLPADDING=0 CELLSPACING=0 width=95%><tr><td><font color=green>�������:</td><td align = center><input type='text' name='surname' value='".getdata($lg, 'users', 'surname')."' style='background:#000099; font-WEIGHT: BOLD; COLOR: white; cursor: hand; list-style: lower-alpha; filter: Alpha(Opacity=20, FinishOpacity=100, Style=3)'></td><td>���� ������� ���������� ��� ���������� ����.</td></tr><tr><td><font color=green>���:</td><td align = center><input type='text' name='mname' value='".getdata($lg, 'users', 'name')."' style='background:#000099; font-WEIGHT: BOLD; COLOR: white; cursor: hand; list-style: lower-alpha; filter: Alpha(Opacity=20, FinishOpacity=100, Style=3)'></td><td>���� ��� ���������� ��� ������� � ����.</td></tr><tr><td><font color=green>������ ����������:</td><td align = center><input type='text' style='background:#000099; font-WEIGHT: BOLD; COLOR: white; cursor: hand; list-style: lower-alpha; filter: Alpha(Opacity=20, FinishOpacity=100, Style=3)' name='country' value='".getdata($lg, 'users', 'country')."'></td><td>���������� ��� ���������� �������� �������. (���������������)</td></tr><tr><td><font color=green>�����:</td><td align = center><input type='text' name='city' value='".getdata($lg, 'users', 'city')."' style='background:#000099; font-WEIGHT: BOLD; COLOR: white; cursor: hand; list-style: lower-alpha; filter: Alpha(Opacity=20, FinishOpacity=100, Style=3)'></td><td>��������� ��� ���������� �������� �������. (���������������)</td></tr><tr><td><font color=green>E-Mail:</td><td align = center><input type='text' name='email' value='".getdata($lg, 'users', 'email')."' style='background:#000099; font-WEIGHT: BOLD; COLOR: white; cursor: hand; list-style: lower-alpha; filter: Alpha(Opacity=20, FinishOpacity=100, Style=3)'></td><td>��������� ��� ����� � ����.</td></tr><tr><td><font color=green>URL:</td><td align = center><input type='text' name='url' value='".getdata($lg, 'users', 'url')."' style='background:#000099; font-WEIGHT: BOLD; COLOR: white; cursor: hand; list-style: lower-alpha; filter: Alpha(Opacity=20, FinishOpacity=100, Style=3)'></td><td>���� � ��� ���� ��� ����������� ����, ����� ��� ������������.</td></tr><tr><td><font color=green>ICQ:</td><td align = center><input type='text' name='icq' value='".getdata($lg, 'inf', 'icq')."' style='background:#000099; font-WEIGHT: BOLD; COLOR: white; cursor: hand; list-style: lower-alpha; filter: Alpha(Opacity=20, FinishOpacity=100, Style=3)'></td><td>���� � ��� ���� ICQ, �� ��� ����, ����� ������ ������ ����� ��������� � ����, ������� ��� �����.</td></tr><tr><td><font color=green>� ����:</td><td align = center colspan = 2><textarea cols=60 rows=10 name='osebe' maxlength=255 style='background:#000099; font-WEIGHT: BOLD; COLOR: white; cursor: hand; list-style: lower-alpha; filter: Alpha(Opacity=20, FinishOpacity=100, Style=3)'>".getdata($lg, 'inf', 'about')."</textarea></td></tr><tr><td colspan=3 align=center><input type='submit' value='��������� ���������'></td></tr>	</table></form></center>");
	exit();
}

//���������� � ����
infoof($name);

//��������� �����������
if ($lg == $name)
{
	?>
		<center>
		<form action="info.php" method=post>
		<input type='hidden' name='change' value='1'>
		<?
			echo("<input type='hidden' name='name' value='".$lg."'>");
		?>
		<input type='submit' value='�������� ����������' style='background:#000099; font-WEIGHT: BOLD; COLOR: white; cursor: hand; list-style: lower-alpha; filter: Alpha(Opacity=20, FinishOpacity=100, Style=3)'>
		</form>
		</center>
		<br>
	<?
}
$ph = getdata($name, 'inf', 'fld1');
if ($ph == '0')
{
	$ph = $ph.".jpg";
}
echo ("<center><img src='images/photos/".$ph."' width=150 height=200></center>");
ban();
?>
