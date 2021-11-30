<body background="images/back.jpe">
<link rel='stylesheet' type='text/css' href='style.css'/>
<title>Послать сообщение</title>

<?
include "functions.php";

//Получаем данные из cookies
$lg = trim($HTTP_COOKIE_VARS["nativeland"]);

//Выводим посылку сообщения
sendmail($lg, 1);

?>