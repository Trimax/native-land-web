<?
  include "functions.php";

  //���� �� ������� ��� ������������, �� �������� ����� �����
  $lg = trim($HTTP_COOKIE_VARS["nativeland"]);
  $pw = trim($HTTP_COOKIE_VARS["password"]);

  //����������?
  if (finduser($lg, $pw) != 1)
  	moveto('index.php');

  //���� ����� � �����, �� �������������� ��� ����
  FromBattle($lg);
  ban();

  //���������� ������� ����������
  $cx = $x;
  $cy = $y;

  //���������, � ���� �� �� ����� ����� ���������?
  $mx = getdata($lg, 'coords', 'rx');
  $my = getdata($lg, 'coords', 'ry');
  if (($mx == $rx)&&($my == $ry))
  {
  }
  else
    moveto("map.php");

  //������ ��� �������
  $ath = mysql_query("select * from capital;");
  $castle_count=0;
  if ($ath)
  	while ($rw = mysql_fetch_row($ath))
    	if (($rx == $rw[1])&&($ry == $rw[2]))
		  {
			  //���������� ���������� �����
        $cstl[$castle_count] = $rw[0];
        $cstx[$castle_count] = $rw[3];
        $csty[$castle_count] = $rw[4];
        $castle_count++;
  		}

  //������ ��� ������
  $ath = mysql_query("select * from mapbuild;");
  $object_count=0;
  if ($ath)	//���� ���������
	  while ($rw = mysql_fetch_row($ath))	//��������� ������?
  		if (($rx == $rw[3])&&($ry == $rw[4]))
	  	{
		  	//���������� ���������� �����
        $objl[$object_count] = $rw[1]; //��� ���������
        $objt[$object_count] = $rw[2]; //��� ���������
        $obji[$object_count] = $rw[7]; //���������� � ���������
        $objx[$object_count] = $rw[5]; //���������� ��������� X
        $objy[$object_count] = $rw[6]; //���������� ��������� Y
        $object_count++;
  		} //if

  //������ �����...
	$file = fopen("maps/".$rx."x".$ry.".map", "r");
  for ($x = 1; $x < 11; $x++)
  	for ($y = 1; $y < 11; $y++)
    {
  		//���������� ��� ���
      $map[$x][$y] = fgets($file, 255);
      $fld = trim($map[$x][$y]);

      //���� ��� �������
      for ($i = 0; $i < $castle_count; $i++)
        if (($x == $cstx[$i])&&($y == $csty[$i]))
          $map[$x][$y] = $fld[0].$fld[1].$fld[2].$fld[3].$cstl[$i];

      //� ��� �� ��� � ��� ������� �������
      for ($i = 0; $i < $object_count; $i++) //��������� ����������?
        if (($objx[$i] == $x)&&($objy[$i] == $y))
        {
          $map[$x][$y] = $fld[0].$fld[1].$fld[2].$fld[3].$objl[$i];
          $CastleName = $obji[$i];
        }
    }
	fclose($file);

  //���������, ��� ��������� � ���� ������
  (int)$x = (int)$cx;
  (int)$y = (int)$cy;
  $fld = $map[$x][$y];

    //��� ����������
    $type = $fld[0];
    //��� �������
    $object = $fld[2];
    //��� ��
    $hoster = trim(substr($fld, 4));

  //�����
  echo("<title>�������������</title>");
  echo ("<link rel='stylesheet' type='text/css' href='style.css'/><body background='images\back.jpe'>");

  //���������� � ���� ��������
  $login = $lg;
  echo ("<center><h2>��� �� �������� ������?</h2><TABLE BORDER=0 CELLSPACING=0 CELLPADDING=0><tr>");
	echo ("<td valign=top><img src=images/menu/gold.gif alt='".getdata($login, 'economic', 'moneyname')."'></td><td valign=center>".getdata($login, 'economic', 'money')."</td><td valign=top><img src=images/menu/metal.gif alt='������'></td><td valign=center>".getdata($login, 'economic', 'metal')."</td><td valign=top><img src=images/menu/rock.gif alt='������'></td><td valign=center>".getdata($login, 'economic', 'rock')."</td><td valign=top><img src=images/menu/wood.gif alt='������'></td><td valign=center>".getdata($login, 'economic', 'wood')."</td>");
	echo ("</tr>");
  echo("<tr><td colspan=8 align=center>$data</td></tr>");
  echo("</TABLE></center>");

  //���� ������ ����� �� ������, �� ����� ����� ���-������ �������
  if ($hoster == '0')
  {
    //���� ��� ����, �� ������ ������� ������
    if ($type == 4)
      messagebox("�� �� ������ ������ ��������� �� ����", "map.php");

    //���������� ��� ������� (����� ������, ��� ��� �������)
    switch($object)
    {
      case 0:
        $name = "�����";
        break;
      case 1:
        $name = "�����";
        break;
      case 2:
        $name = "�����������";
        break;
      case 3:
        $name = "���������";
        break;
    }
    ?>
      <center>
      <form action='doit.php' method=post>
      <table border=1 cellspacing=0 cellpadding=0 width=50%>
      <?
        //����������� ���� �� ������
        $curse     = getdata($lg, 'economic', 'curse');
        $moneyname = getdata($lg, 'economic', 'moneyname'); 
        switch($object)
        {
          case 0:
            $money = 10000*$curse;
            $metal = 1000;
            $rock  = 2000;
            $wood  = 2000;
            break;
          case 1:
            $money = 7000*$curse;
            $metal = 4000;
            $rock  = 4000;
            $wood  = 500;
            break;
          case 2:
            $money = 7000*$curse;
            $metal = 6000;
            $rock  = 2500;
            $wood  = 1500;
            break;
          case 3:
            $money = 5000*$curse;
            $metal = 2000;
            $rock  = 500;
            $wood  = 3500;
            break;
        }

        //������� ����������
        echo("<tr><td colspan=2 align=center>��������� ".$name."</td></tr>");
        echo("<tr><td width=20% align=center>������</td><td align=center>����������</td></tr>");
        echo("<tr><td width=20%>".$moneyname."</td><td align=center>".$money."</td></tr>");
        echo("<tr><td width=20%>������</td><td align=center>".$metal."</td></tr>");
        echo("<tr><td width=20%>������</td><td align=center>".$rock."</td></tr>");
        echo("<tr><td width=20%>������</td><td align=center>".$wood."</td></tr>");

        //��������� ������ �����
        echo("<input type='hidden' name='rx' value='$rx'>");
        echo("<input type='hidden' name='ry' value='$ry'>");
        echo("<input type='hidden' name='cx' value='$x'>");
        echo("<input type='hidden' name='cy' value='$y'>");
        echo("<input type='hidden' name='tp' value='$type'>");
        echo("<input type='hidden' name='bj' value='$object'>");
      ?>
       <tr>
        <td colspan=2 align=center>
        <br>
        <input type='text' name='bname' value='�����' style='background:#000099; font-WEIGHT: BOLD; COLOR: white; cursor: hand; list-style: lower-alpha; filter: Alpha(Opacity=20, FinishOpacity=100, Style=3)'>
        <br>
        <input type='submit' value='���������' style='background:#000099; font-WEIGHT: BOLD; COLOR: white; cursor: hand; list-style: lower-alpha; filter: Alpha(Opacity=20, FinishOpacity=100, Style=3)'>
        </form>
        </td>
       </tr>
       <tr>
        <td colspan=2 align=center>
          <br>
          <form action='map.php' method=post>
          <input type='submit' value='���������' style='background:#000099; font-WEIGHT: BOLD; COLOR: white; cursor: hand; list-style: lower-alpha; filter: Alpha(Opacity=20, FinishOpacity=100, Style=3)'>
          </form>
        <?
            HelpMe(5, 0);
        ?>
        </td>
       </tr>
       </table>
      </center>
    <?
    exit();
  }
  else
  {
    $nick = getdata($hoster, 'hero', 'name');
    ?>
      <center>
      <table border=1 cellpadding=0 cellspacing=0 width=70%>
      <tr>
        <td align=center>
        <br>
        <form action='startbattle.php' method='post'>
        <?
          echo("<h2>".$CastleName."</h2>");
          echo("<b>�� �������, ��� ������ ����������� ����� ".$nick."?</b>"); 
          echo("<input type='hidden' name='login' value='".$lg."'>");     //����� �����������            
          echo("<input type='hidden' name='oppon' value='".$hoster."'>"); //����� ��������������              
          echo("<input type='hidden' name='type'  value='1'>");           //��� �������� - �����   
          echo("<input type='hidden' name='terr'  value='".$terr."'>");   //���������� ��� ����
        ?>
        <br>
        <br>
        <input type='submit' value='������ �����' style='background:#000099; font-WEIGHT: BOLD; COLOR: white; cursor: hand; list-style: lower-alpha; filter: Alpha(Opacity=20, FinishOpacity=100, Style=3)'>
        </form>
        <?
          HelpMe(6, 0);
        ?>
        </td>
      </tr>
      </table>
        <br>
        <form action='map.php' method='post'>
        <input type='submit' value='���������' style='background:#000099; font-WEIGHT: BOLD; COLOR: white; cursor: hand; list-style: lower-alpha; filter: Alpha(Opacity=20, FinishOpacity=100, Style=3)'>
        </form>
      </center>
    <?
  }
?>