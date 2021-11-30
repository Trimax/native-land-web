<?php

//Фон и стиль
echo ("<html>\n<head>\n<link rel='stylesheet' type='text/css' href='style.css'/>\n<title>Native Land</title>\n</head>\n<body background='images\back.jpe'>");


if ($state == "")
{
    $title="Native Land Mail Server";
    $header="Send An Email";
}
else
{
    $title="PhpMail";
    $header="Your Message Sent!";
    $announce="Send Another Email...";
	$fromText=$login;
	$cc="";
	$bcc="";
    if ($ccText != "") $ccText="cc: $ccText <$ccText>\n";
	if ($bccText != "") $bccText="Bcc: $bccText <$bccText>\n";
    if ($mailformat == "Text") mail($toText, $subjectText, $msgText,     "To: $toText <$toText>\n" .     "From: $fromText <$fromText>\n" .$ccText.$bccText.   "X-Mailer: PHP 4.x");
    if ($mailformat == "Html") mail($toText, $subjectText, $msgText,     "To: $toText <$toText>\n" .     "From: $fromText <$fromText>\n" .$ccText.$bccText.     "MIME-Version: 1.0\n" .     "Content-type: text/html; charset=iso-8859-1");
	echo("Сообщение отправлено<br>");
}

?>

<html>

<head>
<meta http-equiv="Content-Type" content="text/html; charset=windows-1252">
<title>
<?php 
  echo($title)
?>
</title>
</head>
<body link="#0000ff" alink="#0000ff" vlink="#0000ff" topmargin="0" leftmargin="0" marginwidth="0" marginheight="0">
<center>
<table>
<tr><td>
  <b><font face="Arial" size="4" color="#000080">Native Land Mail Server</font><font face="Arial" size="3"><br>
  </font></b>
  <form method="POST" action="<?php echo($PHP_SELF)?>">
    <p><font face="Arial" size="3"><b>Кому: <input type="text" name="toText" size="35" style='background:#000099; font-WEIGHT: BOLD; COLOR: white; cursor: hand; list-style: lower-alpha; filter: Alpha(Opacity=20, FinishOpacity=100, Style=3)'></b></font></p>
    <p><font face="Arial" size="3"><b>Тема: <input type="text" name="subjectText" size="46" style='background:#000099; font-WEIGHT: BOLD; COLOR: white; cursor: hand; list-style: lower-alpha; filter: Alpha(Opacity=20, FinishOpacity=100, Style=3)'></b></font></p>
    <p><font face="Arial" size="3"><b>Формат сообщения: </b></font>
    <font face="Arial" size="2">Текст <input type="radio" name="mailformat" value="Text" checked=true> HTML  <input type="radio" name="mailformat" value="Html"></font>
    </p>
    <p><font face="Arial" size="3"><b>Текст сообщения:</b></font></p>
    <p><font face="Arial" size="3"><b><textarea rows="11" name="msgText" cols="60" style='background:#000099; font-WEIGHT: BOLD; COLOR: white; cursor: hand; list-style: lower-alpha; filter: Alpha(Opacity=20, FinishOpacity=100, Style=3)'></textarea></b></font></p>
    <p><font face="Arial" size="3"><b><input type="submit" value="Отправить" name="send" style="font-family: Arial; font-size: 12pt; font-weight: bold" style='background:#000099; font-WEIGHT: BOLD; COLOR: white; cursor: hand; list-style: lower-alpha; filter: Alpha(Opacity=20, FinishOpacity=100, Style=3)'></b></font></p>
    <p>&nbsp;</p>
    <input type="hidden" name="state" value="1">
	<?
	echo("<input type='hidden' name='login' value='".$login."'>");
	?>
  </form>
</td></tr>
</table>
</center>
</body>
</html>

