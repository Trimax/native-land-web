<?

include "functions.php";

if ($HTTP_POST_FILES["filename"]["size"] > 1024*15)
{
	echo ("<title>Native Land</title>");
	echo ("<link rel='stylesheet' type='text/css' href='style.css'/>");
	echo ("<body background='images/back.jpe'>");
	echo ("<font color=blue><b>������ ����� �� ������ ��������� 15 ��������</b></font>");
	echo ("<center><a href=javascript:history.go(-1);>�����</a></center>");
	exit();
}
$s = strtolower(substr($HTTP_POST_FILES["filename"]["name"], strlen($HTTP_POST_FILES["filename"]["name"])-3));
if (($s != 'jpg')&&($s != 'gif'))
{
	echo ("<title>Native Land</title>");
	echo ("<link rel='stylesheet' type='text/css' href='style.css'/>");
	echo ("<body background='images/back.jpe'>");
	echo ("<font color=blue><b>�������� ������ ���� ������ � ������� JPEG ��� GIF (���������� JPG)</b></font>");
	echo ("<center><a href=javascript:history.go(-1);>�����</a></center>");
	exit();
}
if (copy($HTTP_POST_FILES["filename"]["tmp_name"], "images/photos/".$login.".jpg"))
{
	echo ("<title>Native Land</title>");
	echo ("<link rel='stylesheet' type='text/css' href='style.css'/>");
	echo ("<body background='images/back.jpe'>");
	echo ("<font color=blue><b>���������� ������� ���������. ������ �� ������ ������� � �� ������.</b></font>");
	echo ("<center><a href=javascript:window.close();>�������</a></center>");

	//������ � � ����
	change ($login, 'inf', 'fld1', $login.".jpg");
	ban();
}
?>