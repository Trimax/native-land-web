<?


Error_Reporting(E_ALL & ~E_NOTICE);

if ($ch == 1)
{
	include "functions.php";
	//Ставим её в базу

	$lg = trim($HTTP_COOKIE_VARS["nativeland"]);
	change ($lg, 'inf', 'fld1', $photo);
	ban();
	moveto('up.php');
}
?>

<link rel='stylesheet' type='text/css' href='style.css'/>
<html>
<head>
<title>Загрузка фотографии</title>
</head>
<body background='images\back.jpe'>
<center><h2><p><b>Форма загрузки фотографии</b></p></h2>
Внимание! Все картинки должны быть размером 150х200 (остальные будут ужаты до такового)
</center>
<form action="upload.php" method="post" enctype="multipart/form-data">
<center>
<table border=0>
<?
echo ("<input type=hidden name=login value=".$login.">");
?>
<tr><td align=center><input type="file" name="filename"></td></tr>
<tr><td align=center><input type="submit" value="Загрузить" style='background:#000099; font-WEIGHT: BOLD; COLOR: white; cursor: hand; list-style: lower-alpha; filter: Alpha(Opacity=20, FinishOpacity=100, Style=3)'></td></tr>
</table>
</form>
<center>
<table border=0>
<tr><td align=center><b>Выбор фотографии из списка</b></td></tr>
<tr><td align=center>
<?

$dir_rec= dir("images/photos");
$i = 0;
while ($entry = $dir_rec->read())
   {
		if (($entry != '.')&&($entry != '..'))
	   {
			$names[$i] = strtolower(trim($entry));
			$i++;
	   }
   }
$dir_rec->close();
$count = $i;
@sort($names);

//Выводим список со всеми фотками
echo("<table border=1 cellpadding=0 cellspacing=0 width=50%>");
for ($i = 0; $i < $count; $i++)
{
  if (($names[$i][0] == 'f')&&($names[$i][1] == 'o')&&($names[$i][2] == 't')&&($names[$i][3] == 'o'))
  {
    ?>
      <tr><td align=center width=0>
      <form action="up.php" method="post" enctype="multipart/form-data">
      <input type=hidden name=ch value=1>
    <?
      echo("<input type='hidden' name='photo' value='".$names[$i]."'>");
      echo("<img src='images/photos/".$names[$i]."' width=150 height=200></td><td align=center>");
    ?>
      <input type="submit" value="Выбрать" style='background:#000099; font-WEIGHT: BOLD; COLOR: white; cursor: hand; list-style: lower-alpha; filter: Alpha(Opacity=20, FinishOpacity=100, Style=3)'>
      </form>
      </tr></tr>
    <?
  }
}
?>
</table>
</td></tr>
</table>
</center>
</body>
</html>