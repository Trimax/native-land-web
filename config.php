<?

if (!empty($host))
{
$file = fopen("config.ini.php", "w");
fputs($file, "<?\n");
fputs($file, "/*\n");
fputs($file, "$host\n");
fputs($file, "$name\n");
fputs($file, "$log\n");
fputs($file, "$pwd\n");
fputs($file, "*\/n");
fputs($file, "?>\n")
fclose($file);
echo ("Настройка завершена. Перейти на стартовую страницу Вы можете <a href='index.php'>здесь</a>");
exit();
}
else
{

$file = fopen("config.ini.php", "r");
$temp = trim(fgets($file, 255));
$temp = trim(fgets($file, 255));
$host = trim(fgets($file, 255));
$name = trim(fgets($file, 255));
$log = trim(fgets($file, 255));
$pwd = trim(fgets($file, 255));
fclose($file);


?>
    <center><h2><font color=#3399FF>Настройка Native Land</font></h2></center>
    <H4><font color=#00CC99>Добро пожаловать в программу настройки PHP продукта - Native Land. Эта программа поможет Вам корректно настроить данные, необходимые для работы с MySql.</H4>
    <center>
    <form action="config.php" method="post">
    <table border=1 width=40% cols=2 CELLSPACING=0 CELLPADDING=0>
    <tr><td align=center>Параметр</td><td align = center>Значение</tr></tr>
<?
    echo ("<tr><td align=left>Хост базы данных:</td><td align=center><input type='text' name='host' value='$host' style='background:#000099; font-WEIGHT: BOLD; COLOR: white; cursor: hand; list-style: lower-alpha; filter: Alpha(Opacity=20, FinishOpacity=100, Style=3)'></td></tr>");
    echo ("<tr><td align=left>Имя базы данных:</td><td align=center><input type='text' name='name' value='$name' style='background:#000099; font-WEIGHT: BOLD; COLOR: white; cursor: hand; list-style: lower-alpha; filter: Alpha(Opacity=20, FinishOpacity=100, Style=3)'></td></tr>");
    echo ("<tr><td align=left>Имя пользователя:</td><td align=center><input type='text' name='log' value='$log' style='background:#000099; font-WEIGHT: BOLD; COLOR: white; cursor: hand; list-style: lower-alpha; filter: Alpha(Opacity=20, FinishOpacity=100, Style=3)'></td></tr>");
    echo ('<tr><td align=left>Пароль доступа:</td><td align=center><input type="password" name="pwd" value='.$pwd.' style="background:#000099; font-WEIGHT: BOLD; COLOR: white; cursor: hand; list-style: lower-alpha; filter: Alpha(Opacity=20, FinishOpacity=100, Style=3)"></td></tr>');
?>
    </table><br>
    <center><input type="submit" value="  Применить  " style='background:#000099; font-WEIGHT: BOLD; COLOR: white; cursor: hand; list-style: lower-alpha; filter: Alpha(Opacity=20, FinishOpacity=100, Style=3)'></center>
    </form>
    </center>
<?
}
?>

