<?

include "functions.php";

if ($HTTP_POST_FILES["filename"]["size"] > 1024*15)
{
	echo ("<title>Native Land</title>");
	echo ("<link rel='stylesheet' type='text/css' href='style.css'/>");
	echo ("<body background='images/back.jpe'>");
	echo ("<font color=blue><b>Размер файла не должен превышать 15 килобайт</b></font>");
	echo ("<center><a href=javascript:history.go(-1);>Назад</a></center>");
	exit();
}
$s = strtolower(substr($HTTP_POST_FILES["filename"]["name"], strlen($HTTP_POST_FILES["filename"]["name"])-3));
if (($s != 'jpg')&&($s != 'gif'))
{
	echo ("<title>Native Land</title>");
	echo ("<link rel='stylesheet' type='text/css' href='style.css'/>");
	echo ("<body background='images/back.jpe'>");
	echo ("<font color=blue><b>Картинка должна быть только в формате JPEG или GIF (расширение JPG)</b></font>");
	echo ("<center><a href=javascript:history.go(-1);>Назад</a></center>");
	exit();
}
if (copy($HTTP_POST_FILES["filename"]["tmp_name"], "images/photos/".$login.".jpg"))
{
	echo ("<title>Native Land</title>");
	echo ("<link rel='stylesheet' type='text/css' href='style.css'/>");
	echo ("<body background='images/back.jpe'>");
	echo ("<font color=blue><b>Фотография успешно загружена. Теперь Вы можете выбрать её из списка.</b></font>");
	echo ("<center><a href=javascript:window.close();>Закрыть</a></center>");

	//Ставим её в базу
	change ($login, 'inf', 'fld1', $login.".jpg");
	ban();
}
?>