<title>Банк</title>
<link rel='stylesheet' type='text/css' href='style.css'/>
<body background='images/back.jpe'>
<?
  include "functions.php";
  $lg = trim($HTTP_COOKIE_VARS["nativeland"]);
  if ((hasuser($lg) == 0)||($login != $lg))
    exit();
  FromBattle($lg);

  //Банк
  function bank($login)
  {
	  echo ("<center><h2>Банковский перевод</h2><table border=1 width=40% CELLSPACING=0 CELLPADDING=0>");
  	echo ("<tr><td align=center>Кому перевод</td><td align=center>");
  	echo ("<form action='game.php' method=post><input type='hidden' name='action' value=35>");
	  indexuserlist("users");
  	echo ("</td></tr><tr><td align=center>Сколько</td><td align=center><input type='text' name='count' value='0' style='background:#000099; font-WEIGHT: BOLD; COLOR: white; cursor: hand; list-style: lower-alpha; filter: Alpha(Opacity=20, FinishOpacity=100, Style=3)'></td></tr>");
  	echo ("<tr><td colspan=2 align=center><input type='submit' value = ' Перевести деньги ' style='background:#000099; font-WEIGHT: BOLD; COLOR: white; cursor: hand; list-style: lower-alpha; filter: Alpha(Opacity=20, FinishOpacity=100, Style=3)'></td></tr>");
  	echo ("</form></table></center>");
  }

  //А построен ли банк?
  $name = $lg;
  if (getdata($name, 'city', 'build10') != 0)
  {
    bank($name);
  } else
    messagebox("У Вас не построен(а) ".getfrom('race', getdata($name, 'hero', 'race'), 'buildings', 'build10').". Сначала необходимо построить это здание!", "city.php?login=".$lg);
  echo("<center><a href='city.php?login=".$lg."'>Назад в город</a></center>");
  HelpMe(17, 1);
?>						