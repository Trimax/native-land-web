<body background="images/back.jpe">
<link rel='stylesheet' type='text/css' href='style.css'/>
<title>��������� ������ ���������</title>
<center>
<?
  include "functions.php";

  //���� �� ������� ��� ������������, �� �������� ����� �����
  $lg = trim($HTTP_COOKIE_VARS["nativeland"]);
  $pw = trim($HTTP_COOKIE_VARS["password"]);

  //����������?
  if (hasuser($lg) == 0)
  	exit();

  //������ �������� (������ ���� ��������� ���� �� ����!!!!!)
  $ch1 = getdata($lg, 'abilities', 'combatmagic');
  $level1 = 1;
  $ch2 = getdata($lg, 'abilities', 'mindmagic');
  $level2 = 1;

  //�������� ��������
  $pic1 = getfrom('num', $ch1, 'additional', 'img');
  $pic2 = getfrom('num', $ch2, 'additional', 'img');
  $pic1 = "images/newchar/".$pic1."/";
  $pic2 = "images/newchar/".$pic2."/";
  $name1 = getfrom('num', $ch1, 'additional', 'name');
  $name2 = getfrom('num', $ch2, 'additional', 'name');

  //��� ��������
  $cell1 = "N".$ch1;
  $cell2 = "N".$ch2;

  //��������
  if (($ch1 != '0')&&($ch2 != '0'))
  {
    //� ������ ������ � ��� ��� ������?
    for ($i = 1; $i <= 16; $i++)
    {
      $pers[$i] = getdata($lg, 'newchar', 'achar'.$i);
      
      //��������� �������
      $lvl = $pers[$i][0];
      $numb = cut($pers[$i]);

      //���� �������������� ���������
      if ($numb == $ch1)
      {
        switch($lvl)
        {
          case 'N':
            $level1 = 2;
            $cell1 = "A".$numb;
            $cn1 = "achar".$i;
            break;
          case 'A':
            $level1 = 3;
            $cell1 = "E".$numb;
            $cn1 = "achar".$i;
            break;
        }
      } // ����� ���������� 1 ����

      //���� �������������� ���������
      if ($numb == $ch2)
      {
        switch($lvl)
        {
          case 'N':
            $level2 = 2;
            $cell2 = "A".$numb;            
            $cn2 = "achar".$i;
            break;
          case 'A':
            $level2 = 3;
            $cell2 = "E".$numb;
            $cn2 = "achar".$i;
            break;
        }
      } // ����� ���������� 1 ����
    } // ����� ��������

    //��������� ��������
    $pic1 = $pic1.$level1.".jpg";
    $pic2 = $pic2.$level2.".jpg";

    //��������
    $desc1 = getfrom('num', $ch1, 'additional', 'desc'.$level1);
    $desc2 = getfrom('num', $ch2, 'additional', 'desc'.$level2);

    //�������� �������
    if ($level1 == 1)
      $lev1 = "�������";
    if ($level1 == 2)
      $lev1 = "�����������";
    if ($level1 == 3)
      $lev1 = "�������";
    if ($level2 == 1)
      $lev2 = "�������";
    if ($level2 == 2)
      $lev2 = "�����������";
    if ($level2 == 3)
      $lev2 = "�������";

    //��� ������ �����?
    if (!empty($action))
    {
      //���� ��� ������ �������, �� ������� ������ �����
      if ($level1 == 1)
      {
        for ($i = 16; $i >= 1; $i--)
        {
          if ($pers[$i] == '0')
            $cn1 = "achar".$i;
        }
      }
      if ($level2 == 1)
      {
        for ($i = 16; $i >= 1; $i--)
        {
          if ($pers[$i] == '0')
            $cn2 = "achar".$i;
        }
      }

      //����� ��������
      switch($action)
      {
        case 1:
          change($lg, 'newchar', $cn1, $cell1);
          change($lg, 'abilities', 'combatmagic', '0');
          change($lg, 'abilities', 'mindmagic', '0');
          moveto("game.php?action=1");
          break;
        case 2:
          change($lg, 'newchar', $cn2, $cell2);
          change($lg, 'abilities', 'combatmagic', '0');
          change($lg, 'abilities', 'mindmagic', '0');
          moveto("game.php?action=1");
          break;
      } //��� �������
    } //���� ������

    //������� ��������
    echo ("<table border=1 width=40% CELLSPACING=0 CELLPADDING=0>");
    echo ("<tr><td colspan=2 align=center><font size=6>��������� ������</font></td></tr>");
    echo ("<tr><td colspan=2 align=center><b>�������� ���� �� ��������� ���� �������������� �������������</b></td></tr>");
    echo ("<tr>");
      echo ("<td align=center width=50%>$name1<br><a href='levelup.php?action=1'><img src='$pic1' alt='$desc1' border=0></a><br><b>$lev1</b></td>");    
      echo ("<td align=center width=50%>$name2<br><a href='levelup.php?action=2'><img src='$pic2' alt='$desc2' border=0></a><br><b>$lev2</b></td>");    
    echo ("</tr>");
    echo ("<tr>");
      echo ("<td align=center width=50%>$desc1</td>");    
      echo ("<td align=center width=50%>$desc2</td>");    
    echo ("</tr>");
    echo ("</table>");
  }
?>
</center>
