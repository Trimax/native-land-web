<link rel='stylesheet' type='text/css' href='style.css'/>
<html>
<head>
<title>�������� ����������</title>
</head>
<body background='images\back.jpe'>

<?

//���������� �������
include "functions.php";

//���� �� ������� ��� ������������, �� �������� ����� �����
$lg = trim($HTTP_COOKIE_VARS["nativeland"]);
$pw = trim($HTTP_COOKIE_VARS["password"]);
ban();

//���� �� �����, �� ���-���
if (($lg != 'Admin')||(finduser($lg, $pw) != 1))
{
	exit();
}

//���������
if ($up == 1)
{
	if (copy($HTTP_POST_FILES["filename"]["tmp_name"], $dir.$HTTP_POST_FILES["filename"]["name"]))
	{
		echo ("<font color=blue><b>���� ".$HTTP_POST_FILES['filename']['name']." ������� ��������. � ���������� /".$dir.$HTTP_POST_FILES["filename"]["name"]."</b></font>");
		echo ("<center><a href=javascript:window.close();>�������</a></center>");
		exit();
	}
}
?>

<center><h2><p><b>����� �������� ������</b></p></h2></center>
<form action="fileup.php" method="post" enctype="multipart/form-data">
<input type=hidden name=up value=1>
<center>
<table border=0>
<tr><td align=center><input type="file" name="filename"></td></tr>
<tr><td align=center><input type="text" name="dir" value="images/"></td></tr>
<tr><td align=center><input type="submit" value="���������"></td></tr>
</table>
</center>
</form>
</body>
</html>