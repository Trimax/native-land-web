<body background="images/back.jpe">
<link rel='stylesheet' type='text/css' href='style.css'/>
<title>:)</title>

<?

//Вставляем музыку
echo("<bgsound src='music/track".$track.".mp3' loop=100>\n");

//Информация о текущем треке
echo("<b>Track №</b>".$track."\n");

//Следующий трек
$track++;
if ($track > 3)
{
	$track = 0;
}

//Форма выбора трека
echo("<center>\n");
echo("<form action=player.php>\n");
echo("<input type='submit' value='Далее' style='background:#000099; font-WEIGHT: BOLD; COLOR: white; cursor: hand; list-style: lower-alpha; filter: Alpha(Opacity=20, FinishOpacity=100, Style=3)'>\n");
echo("<input type='hidden' name='track' value=".$track.">\n");
echo("</form>\n");
echo("</center>\n");

?>