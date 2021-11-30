<link rel='stylesheet' type='text/css' href='style.css'/>
<html>
<head>
<title>Загрузка фотографии</title>
</head>
<body background='images\back.jpe'>

<?

//Подключаем функции
include "functions.php";

//Если не указано имя пользователя, то выкинуть юзера нафиг
$lg = trim($HTTP_COOKIE_VARS["nativeland"]);
$pw = trim($HTTP_COOKIE_VARS["password"]);
ban();

//Если не админ, то фиг-вам
if (($lg != 'Admin')||(finduser($lg, $pw) != 1))
{
	exit();
}

//Загружаем
if ($up == 1)
{
	if (copy($HTTP_POST_FILES["filename"]["tmp_name"], $dir.$HTTP_POST_FILES["filename"]["name"]))
	{
		echo ("<font color=blue><b>Файл ".$HTTP_POST_FILES['filename']['name']." успешно загружен. В директорию /".$dir.$HTTP_POST_FILES["filename"]["name"]."</b></font>");
		echo ("<center><a href=javascript:window.close();>Закрыть</a></center>");
		exit();
	}
}
?>

<center><h2><p><b>Форма загрузки файлов</b></p></h2></center>
<form action="fileup.php" method="post" enctype="multipart/form-data">
<input type=hidden name=up value=1>
<center>
<table border=0>
<tr><td align=center><input type="file" name="filename"></td></tr>
<tr><td align=center><input type="text" name="dir" value="images/"></td></tr>
<tr><td align=center><input type="submit" value="Загрузить"></td></tr>
</table>
</center>
</form>
</body>
</html>