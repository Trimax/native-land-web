<title>�����������</title>
<link rel='stylesheet' type='text/css' href='style.css'/>
<body background='images/back.jpe'>
<?
include "functions.php";

$lg = trim($HTTP_COOKIE_VARS["nativeland"]);
if ($lg != $login) 
  exit();

//� ����� ������
FromBattle($lg);

//���� ���� ������� �� ������������
if (!empty($tlp))
{
  change($lg, 'coords', 'rx', $trx);
  change($lg, 'coords', 'ry', $try);
  change($lg, 'coords', 'x', $tcx);
  change($lg, 'coords', 'y', $tcy);

  //��������� ��������
  moveto('teleport.php?login='.$lg);
}

//���������� �������
$CityName[0]          = getdata($lg, 'info', 'capital');
$CityRx[0]            = getdata($lg, 'capital', 'rx');
$CityRy[0]            = getdata($lg, 'capital', 'ry');
$CityCx[0]            = getdata($lg, 'capital', 'x');
$CityCy[0]            = getdata($lg, 'capital', 'y');

//������ ������ ������� �����������
$count = 1;
$sql = mysql_query("select * from mapbuild where login='$lg' and type='1';");
if ($sql)
  while ($rw = mysql_fetch_array($sql))
  {
    $CityName[$count] = $rw[7];
    $CityRx[$count]   = $rw[3];
    $CityRy[$count]   = $rw[4];
    $CityCx[$count]   = $rw[5];
    $CityCy[$count]   = $rw[6];
    $count++;
  }

//���������� �������
?>
<center>
<h2>�������� �����</h2>
<table border=1 cellspacing=0 cellpadding=0 width=95%>
<tr><td align=center width=70%>�����</td><td align=center>������������</td></tr>
<?
  //��� ������� ������
  for ($i = 0; $i < $count; $i++)
  {
    if (!empty($CityName[$i]))
    {
      echo("<tr>\n");
      echo("<td align=center>".$CityName[$i]."</td><td align=center>\n");
      ?>
      <form action=teleport.php method=post>
      <?
        echo("<input type=hidden name=tlp value=1>\n");
        echo("<input type=hidden name=trx value=".$CityRx[$i].">\n");
        echo("<input type=hidden name=try value=".$CityRy[$i].">\n");
        echo("<input type=hidden name=tcx value=".$CityCx[$i].">\n");
        echo("<input type=hidden name=tcy value=".$CityCy[$i].">\n");
        echo("<input type=hidden name=login value=$lg>\n");
      ?>
      <input type=submit value='�������������' style='background:#000099; font-WEIGHT: BOLD; COLOR: white; cursor: hand; list-style: lower-alpha; filter: Alpha(Opacity=20, FinishOpacity=100, Style=3)'>    
      </form>
      <?
      echo("</td></tr>\n");
    } //Empty
  } //For
?>
</table>
<a href='city.php?login=<?echo($lg);?>'>�����</a>
</center>