<title>Шпионаж</title>
<link rel='stylesheet' type='text/css' href='style.css'/>
<body background='images/back.jpe'>
<?
include "functions.php";
  $lg = trim($HTTP_COOKIE_VARS["nativeland"]);
  if ((hasuser($lg) == 0)||($login != $lg))
    exit();
  FromBattle($lg);

//Шпионаж
function spy($login)
{
	echo ("<center><h2>Разведовательное управление</h2><table border=1 width=40% CELLSPACING=0 CELLPADDING=0>");
	echo ("<tr><td align=center>За кем шпионим</td><td align=center>");
	echo ("<form action='game.php' method=post><input type='hidden' name='action' value=33>");
	indexuserlist("users");
	echo ("</td></tr><tr><td colspan=2 align=center><input type='submit' value = ' Послать шпиона ' style='background:#000099; font-WEIGHT: BOLD; COLOR: white; cursor: hand; list-style: lower-alpha; filter: Alpha(Opacity=20, FinishOpacity=100, Style=3)'></td></tr>");
	echo ("</form></table>");
  HelpMe(11, 0);
  echo ("</center>");
}

$name = $lg;
if (getdata($name, 'city', 'build11') != 0)
{
  spy($name);
} else
  messagedlg("У Вас не построено здание разведки Сначала необходимо его построить!", "spy.php?login=".$lg);
echo("<center><a href='city.php?login=".$lg."'>Назад в город</a></center>");
?>
