<body background="images/back.jpe">
<link rel='stylesheet' type='text/css' href='style.css'/>
<title>������� ���������</title>

<?
include "functions.php";

//�������� ������ �� cookies
$lg = trim($HTTP_COOKIE_VARS["nativeland"]);

//������� ������� ���������
sendmail($lg, 1);

?>