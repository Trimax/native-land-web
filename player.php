<body background="images/back.jpe">
<link rel='stylesheet' type='text/css' href='style.css'/>
<title>:)</title>

<?

//��������� ������
echo("<bgsound src='music/track".$track.".mp3' loop=100>\n");

//���������� � ������� �����
echo("<b>Track �</b>".$track."\n");

//��������� ����
$track++;
if ($track > 3)
{
	$track = 0;
}

//����� ������ �����
echo("<center>\n");
echo("<form action=player.php>\n");
echo("<input type='submit' value='�����' style='background:#000099; font-WEIGHT: BOLD; COLOR: white; cursor: hand; list-style: lower-alpha; filter: Alpha(Opacity=20, FinishOpacity=100, Style=3)'>\n");
echo("<input type='hidden' name='track' value=".$track.">\n");
echo("</form>\n");
echo("</center>\n");

?>