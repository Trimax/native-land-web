<title>����</title>
<link rel='stylesheet' type='text/css' href='style.css'/>
<body background='images/back.jpe'>
<?
  include "functions.php";
  $lg = trim($HTTP_COOKIE_VARS["nativeland"]);
  if ((hasuser($lg) == 0)||($login != $lg))
    exit();
  FromBattle($lg);

  //����
  function bank($login)
  {
	  echo ("<center><h2>���������� �������</h2><table border=1 width=40% CELLSPACING=0 CELLPADDING=0>");
  	echo ("<tr><td align=center>���� �������</td><td align=center>");
  	echo ("<form action='game.php' method=post><input type='hidden' name='action' value=35>");
	  indexuserlist("users");
  	echo ("</td></tr><tr><td align=center>�������</td><td align=center><input type='text' name='count' value='0' style='background:#000099; font-WEIGHT: BOLD; COLOR: white; cursor: hand; list-style: lower-alpha; filter: Alpha(Opacity=20, FinishOpacity=100, Style=3)'></td></tr>");
  	echo ("<tr><td colspan=2 align=center><input type='submit' value = ' ��������� ������ ' style='background:#000099; font-WEIGHT: BOLD; COLOR: white; cursor: hand; list-style: lower-alpha; filter: Alpha(Opacity=20, FinishOpacity=100, Style=3)'></td></tr>");
  	echo ("</form></table></center>");
  }

  //� �������� �� ����?
  $name = $lg;
  if (getdata($name, 'city', 'build10') != 0)
  {
    bank($name);
  } else
    messagebox("� ��� �� ��������(�) ".getfrom('race', getdata($name, 'hero', 'race'), 'buildings', 'build10').". ������� ���������� ��������� ��� ������!", "city.php?login=".$lg);
  echo("<center><a href='city.php?login=".$lg."'>����� � �����</a></center>");
  HelpMe(17, 1);
?>						