<title>����� �����</title>
<link rel='stylesheet' type='text/css' href='style.css'/>
<body background='images/back.jpe'>
<?
include "functions.php";

//��������� ������ �� ������� �� ����� ����, ����� ������������ � ����� �������
function query($level, $race, $field)
{
  $usr = mysql_query("select * from warriors;");
  $find = "";
  if ($usr)
    while ($user = mysql_fetch_array($usr))
      if (($user['level'] == $level)&&($user['race'] == $race))
        $find = $user[$field];
	return $find;
}

//����� ������ � ����������� � �������
function AddRow($Level, $Race, $Login)
{
  //�������� ���������� � ���������
    //��������
    $img = query($Level, $Race, 'img');
    //��������
    $name = query($Level, $Race, 'name');
    //��������
    $health = query($Level, $Race, 'health');
    //������� ���
    $near = query($Level, $Race, 'power');
    //������
    $protect = query($Level, $Race, 'protect');
    //������� ���
    $far = query($Level, $Race, 'archery');
    //���������� �����
    $arrows = query($Level, $Race, 'arrows');
    //���. �����
    $absent = query($Level, $Race, 'addon');
    //������� ��������
    $temp = $Level+1;
    $has = getdata($Login, 'unions', 'login'.$temp);
    //����
    $curse = getdata($Login, 'economic', 'curse');
    switch($Level)
    {
      case 1:
        $k = 1;
        break;
      case 2:
        $k = 3;
        break;
      case 3:
        $k = 6;
        break;
      case 4:
        $k = 15;
        break;
    }
    $cena = $k*$curse;

  //������� ������
  echo("<tr>");
  echo("<td align=center><img src='images/warriors/".$img.".jpg' width=150 height=200 alt='".$name."'></td>");
  echo("<td align=center>");
  ?>
    <table border=1 cellpadding=0 cellspacing=0 width=100%>
    <?
      echo("<tr><td colspan=2 align=center>".$name."</tr>");
      echo("<tr><td align=center>��������</td><td align=center>��������</td></tr>");
      echo("<tr><td align=center>��������</td><td align=center>".$health."</td></tr>");
      echo("<tr><td align=center>������� ���</td><td align=center>".$near."</td></tr>");
      echo("<tr><td align=center>������</td><td align=center>".$protect."</td></tr>");
      echo("<tr><td align=center>��������</td><td align=center>".$far."</td></tr>");
      echo("<tr><td align=center>����� �����</td><td align=center>".$arrows."</td></tr>");
      echo("<tr><td align=center>��������</td><td align=center>".$has."</td></tr>");
      echo("<tr><td align=center>����</font></td><td align=center>".$cena."</td></tr>");
    ?>
    </table>
  <?
  echo("</td>");
  echo("<td align=center>");
  ?>
    <br>
    <form action='getarmy.php' method=post>
    ����������:
    <?
      echo("<input type='hidden' name='login' style='background:#000099; font-WEIGHT: BOLD; COLOR: white; cursor: hand; list-style: lower-alpha; filter: Alpha(Opacity=20, FinishOpacity=100, Style=3)' value='".$lg."'>");
      echo("<input type='hidden' name='Level' style='background:#000099; font-WEIGHT: BOLD; COLOR: white; cursor: hand; list-style: lower-alpha; filter: Alpha(Opacity=20, FinishOpacity=100, Style=3)' value='".$Level."'>");
    ?>
    <input type='hidden' name='take' value=1>
    <input type='text' name='how' style='background:#000099; font-WEIGHT: BOLD; COLOR: white; cursor: hand; list-style: lower-alpha; filter: Alpha(Opacity=20, FinishOpacity=100, Style=3)'>
    <input type='submit' value='������' style='background:#000099; font-WEIGHT: BOLD; COLOR: white; cursor: hand; list-style: lower-alpha; filter: Alpha(Opacity=20, FinishOpacity=100, Style=3)'>
    </form>
  <?
  echo("</td>");
  echo("</tr>");
}

$lg = trim($HTTP_COOKIE_VARS["nativeland"]);
if (hasuser($lg) == 0) 
  exit();
FromBattle($lg);

//��� �����
if ($take == 1)
{
  //����� ������
  $take = 0;

  //����������� ���������� ����������
  $temp = $Level+1;
  $max = getdata($lg, 'unions', 'login'.$temp);
  if ($how < 0)
    $how = 0;
  if ($how > $max)
    $how = $max;

  //���� ���������� 0 - ��� ������
  if ($how == 0)
    moveto("getarmy.php?login=".$lg);

  //����������� �����
  $Curse = getdata($lg, 'economic', 'curse');
  switch($Level)
  {
    case 1:
      $k = 1;
      break;
    case 2:
      $k = 3;
      break;
    case 3:
      $k = 6;
      break;
    case 4:
      $k = 15;
      break;
  }
  $summ = $k*$Curse*$how;
  $money = getdata($lg, 'economic', 'money');
  $mn = getdata($lg, 'economic', 'moneyname'); 

  //� ����� �������
  if ($summ > $money)
    messagebox("� ��� ������������ ����� ��� �����. ��� ���������� ".$summ." ".$mn, "getarmy.php?login=".$lg);

  //�������� ������
  change($lg, 'economic', 'money', $money-$summ);

  //������� ����� �� ������ ��� �����
  change($lg, 'unions', 'login'.$temp, $max-$how);

  //��������� ����� � �����
  $army = getdata($lg, 'army', 'level'.$Level);
  change($lg, 'army', 'level'.$Level, $army+$how);

  //��������� �� ������
  $Race = getdata($lg, 'hero', 'race');
  $who = query($Level, $Race, 'addon');
  messagebox("�� ������ � ���� ����� ���� ������������� ".$how." ".$who, "getarmy.php?login=".$lg);  
}

//������� ��������...
?>
<center>
  <h1>�������������� �����</h1>
  <?
  HelpMe(13, 0);
  ?>
  <table border=1 cellpadding=0 cellspacing=0 width=90%>
  <tr>
    <td align=center width=5%>����</td><td align=center>��������������</td><td align=center width=5%>�����</td>
  </tr>
  <?
    //�������� ���� ���������
    $race = getdata($lg, 'hero', 'race');

    //������� �������� � �������
    AddRow(1, $race, $lg);
    AddRow(2, $race, $lg);
    AddRow(3, $race, $lg);
    AddRow(4, $race, $lg);

    //������� � ��� ��������
    $money = getdata($lg, 'economic', 'money');
    echo("<tr><td colspan=3 align=center><b>� ��� �����: $money <img src='images/menu/gold.gif'></b></td></tr>");
  ?>
  </table>
  <?
    echo("<a href='city.php?login=".$lg."'>����� � �����</a>");
  ?>
</center>