<title>�����</title>
<link rel='stylesheet' type='text/css' href='style.css'/>
<?
  //���������� ������ �������
  include "battlemodule.php";

  //�������� ������
  $lg = trim($HTTP_COOKIE_VARS["nativeland"]);
  if ((hasuser($lg) == 0))
    exit();

  //���� ��� �����
  $btl = getdata($lg, 'battles', 'battle');
  if ($btl == 0)
    moveto('game.php');
  
  //������� �������� ������ �� ����
  $me = $lg;
  $op = getdata($lg, 'battles', 'opponent');
  $tp = getdata($lg, 'battles', 'health');

  //��� �����
  $my_full = 0;
  for ($i = 1; $i <=4; $i++)
  {
    $my[$i] = getdata($lg, 'army', 'level'.$i);
    $my_full = $my_full + $my[$i];
  }

  //����� ���������
  $he_full = 0;
  for ($i = 1; $i <=4; $i++)
  {
    $he[$i] = getdata($op, 'army', 'level'.$i);
    $he_full = $he_full + $he[$i];
  }

  //���� ������, �� 50% ����� �������
  if ($action == 2)
  {
    //��������� ����� � ��� ����
    for ($i = 1; $i <= 4; $i++)
    {
      $army = getdata($me, 'army', 'level'.$i);
      change($me, 'army', 'level'.$i, round($army/2));
    }

    //���������� �����
    $my_full = 0;
  }

  //������ �������� �� �������/��������
    //���� ���������
    if ($my_full == 0)
    {
      //��������� �����
      OffThisBattle($me, $op);
      $rx = getdata($me, 'capital', 'rx');
      $ry = getdata($me, 'capital', 'ry');
      $cx = getdata($me, 'capital', 'x');
      $cy = getdata($me, 'capital', 'y');

      //���������� ������ � �������
      change($me, 'coords', 'rx', $rx);
      change($me, 'coords', 'ry', $ry);
      change($me, 'coords', 'x',  $cx);
      change($me, 'coords', 'y',  $cy);
      moveto('map.php');
    }

    //���� ��������
    if ($he_full == 0) 
    {
      //��������� �����
      OffThisBattle($me, $op);    

      //������ ����������
      Grab($me, $op);
    }
 
  //�������� ���, �� ������� �� ���������...
  $Back = getdata($me, 'battle', 'value');
  switch($Back)
  {
    case 0:
      $Type = "grass";
    break;
    case 1:
      $Type = "snow";
    break;
    case 2:
      $Type = "sand";
    break;
    case 3:
      $Type = "fire";
    break;
  }
  
  //���������� ���
//  echo("<body background='images/terrain/battle/".$Type.".jpg'>\n");
  echo("<body background='images/back.jpe'>\n");

  //�������� �������������� (������ 10 ������)
  echo ("<META HTTP-EQUIV='REFRESH' CONTENT=10>\n");

  //� ��� ���������� ���
  $Turn = getdata($me, 'battle', 'turn');

  //��������� ����� ������ ������ �����?
  $Current  = getdata($me, 'battle', 'attack');
  $Movement = getdata($me, 'battle', 'health');
  $Count    = getdata($me, 'army', 'level'.$Current);

  //���� �������� ���� ���������� ������� (� ��� ���)
  if (($action == 3)&&($Turn == $me))
    $Movement = 0;

  //�������� ���� ���������� �������
  if ( (($Movement <= 0)||($Count <= 0)) && ($Turn == $me) )
  {
    $Current++;

    //���� ��� ������� ��� ������� ���
    if ($Current >= 5)
    {
      $Current = 1;
      ToOpponent($me, $op);
    }

    //����������� ����. �������
    $Mvm = 2*$Current + 1;

    //�������� ������ � ����
    change($me, 'battle', 'attack', $Current);
    change($me, 'battle', 'health', $Mvm);
    moveto('fight.php');
  }

  //�������� ���������� ���� ������
  for ($i = 1; $i <= 4; $i++)
  {
    $MyWLevel[$i] = getdata($me, 'army', 'level'.$i);
    $OpWLevel[$i] = getdata($op, 'army', 'level'.$i);
    $x[$i]        = getdata($me, 'war', 'lx'.$i);
    $y[$i]        = getdata($me, 'war', 'ly'.$i);
    $px[$i]       = getdata($op, 'war', 'lx'.$i);
    $py[$i]       = getdata($op, 'war', 'ly'.$i);
  }

  //��������� ������ �����
  for ($i = 0; $i < 10; $i++)
    for ($j = 0; $j < 8; $j++);
      $map[$i][$j] = 0;
  
  //��� ����� � ���������
  for ($i = 1; $i <= 4; $i++)
  {
    //���� ����
    if ($MyWLevel[$i] != 0)
      $map[$y[$i]][$x[$i]] = $i;
    if ($OpWLevel[$i] != 0)
      $map[$py[$i]][$px[$i]] = $i+4;
  }

  //������ �����
  $map[0][7] = "9";
  $map[1][7] = "9";
  $map[2][7] = "9";
  $map[4][7] = "9";
  $map[5][7] = "9";
  $map[6][7] = "9";

  //���� � ���������
  $Who = getdata($me, 'battles', 'health');
  if ($Who == 2)
  {
    $Add = Level(21, $me);

    //� � ���� ���� �����
    switch($Add)  
    {
      case 1:
        $map[0][7] = "0";
        break;
      case 2:
        $map[0][7] = "0";
        $map[2][7] = "0";
        break;
      case 3:
        $map[0][7] = "0";
        $map[2][7] = "0";
        $map[6][7] = "0";
        break;
    }
  }

  //����������� ���������� ��� �����
  if (($action == 1)&&($me == $Turn))
  {
    //�������� �� ������������
    if ($kx < 0)
      $kx = 0;
    if ($kx > 6)
      $kx = 6;
    if ($ky < 0)
      $ky = 0;
    if ($ky > 9)
      $ky = 9;

    //������� ���������� ������ �� ������
    if ($map[$kx][$ky] == 0)
    {
      //������ ������, ���� ����� ������������
      //���������� ���������� �������� �����
      $MyX = $y[$Current];
      $MyY = $x[$Current];

      //����������, ������ �� ��� �� ���?
      $Path = abs($kx - $MyX) + abs($ky - $MyY);

      //���� ��� �����, ��� ����� �����, �� ���������� � �� ���������
      if ($Path <= $Movement)
      {
        change($me, 'war', 'lx'.$Current, $ky);
        change($me, 'war', 'ly'.$Current, $kx);
        change($me, 'battle', 'health', $Movement-$Path);
      }
    } 
    else
    {
      //� ���� ������ ���-�� ���� (��� �����������)
      //���� ��� �� ����, �� ������� ���
      if (($map[$kx][$ky] > 4)&&($map[$kx][$ky] < 9))
      {
        //���� �� �����
        $AttackFlag = 0;

        //������, ���������� �� ���� ������ ���� ����� 1 (���� ���� �� ����������)
        $MyX = $y[$Current];
        $MyY = $x[$Current];

        //����������, ������ �� ��� �� ���?
        $Path = abs($kx - $MyX) + abs($ky - $MyY);

        //���� ���� 1, �� ��������� �����
        if ($Path == 1)
          $AttackFlag = 1;

        //���� ������ ����� ������ <=> ������ ���������� <=> ����� ��������� � ����� �������
        $Arrows = getdata($me, 'war', 'arrow'.$Current);
        if ($Arrows != 0)
          $AttackFlag = 1;

        //������ �������� ���� ����� (�� ���������� ������� ��� ���������� ����� <=> Movement -> 0)
        if ($AttackFlag == 1)
        {
          AttackWarrior($Current, $map[$kx][$ky], $me, $op);
        } //AttackFlag
      } //����� �� ������
    } //�����

    //�������������� �����, � �����
    $action = 0;
    moveto("fight.php");
  }

  //���������� ������� �������
  /*
  0000000000000000
  0   0          0
  0 M 0  Battle  0
  0   0          0
  0000000000000000
  */
  ?>
    <center>
    <table border=1 cellpadding=0 cellspacing=0 width=100% height=1%>
    <tr>
    <td align=center valign=top>
    <font color=white>
    <b>
  <?

  //���������� ���������� � ������� �������, � �����, ����������� ������
  $MyRace   = getdata($me, 'hero', 'race');
  $Count    = $MyWLevel[$Current];
  $Health   = getdata($me, 'war', 'health'.$Current);
  $Name     = query($Current, $MyRace, 'name');
  $Pic      = query($Current, $MyRace, 'img');
  $Arrows   = getdata($me, 'war', 'arrow'.$Current);
  $Movement = getdata($me, 'battle', 'health');
  $Movement = round($Movement * 100 / (2 * $Current + 1));
  echo("<img src='images/warriors/".$Pic.".jpg' border=0 alt=''><br>\n");
  echo("�����: ".$Name."<br>\n");
  echo("����������: ".$Count."<br>\n");
  echo("������� ��������: ".$Health."<br>\n");
  echo("������� �����: ".$Arrows."<br>\n");
  echo("������� �����������:");
  Progress($Movement);

  //������ �������� ���
  ?>
    <form action='fight.php' method=post>
    <input type='hidden' name='action' value=3>
    <input type="submit" value="�������� ���" style='background:#000099; font-WEIGHT: BOLD; COLOR: white; cursor: hand; list-style: lower-alpha; filter: Alpha(Opacity=20, FinishOpacity=100, Style=3)'>
    </form>
  <?

  //������ �������
  ?>
    <form action='fight.php' method=post>
    <input type='hidden' name='action' value=2>
    <input type="submit" value="�������" style='background:#000099; font-WEIGHT: BOLD; COLOR: white; cursor: hand; list-style: lower-alpha; filter: Alpha(Opacity=20, FinishOpacity=100, Style=3)'>
    </form>
  <?

  //���������� ���� ����� (������� 10�7 � ��������� � �������)
  //������ �������
  ?>
    </b>
    </font>
    </td>
    <td width=2%>
    <center>
    <table border=1 cellpadding=0 cellspacing=0 width=10% height=10%>
  <?

  //������� ����������
  PageHeader($me, $op);

  //��� ���� � ���������
  $MyRace = getdata($me, 'hero', 'race');
  $OpRace = getdata($op, 'hero', 'race');
  
  //�������� ���������� � ��������� �������� � �����
  $MName[9] = "";
  $MName[1] = query(1, $MyRace, 'name');
  $MName[2] = query(2, $MyRace, 'name');
  $MName[3] = query(3, $MyRace, 'name');
  $MName[4] = query(4, $MyRace, 'name');
  $MName[5] = query(1, $OpRace, 'name');
  $MName[6] = query(2, $OpRace, 'name');
  $MName[7] = query(3, $OpRace, 'name');
  $MName[8] = query(4, $OpRace, 'name');

  //���������� ��� 70 ������...
  for ($i = 0; $i < 7; $i++)
  {
    echo("<tr>");
    for ($j = 0; $j < 10; $j++)
    {
      echo("<td align=center width=64 height=64 valign=middle>");
      echo("<a href='fight.php?action=1&kx=".$i."&ky=".$j."'>");

      //���������� �������������
      $Dft = getdata($me, 'battles', 'health');
      if ($Dft == 1)
      {
        $Lft = $op;
        $Rgt = $me;
      }
      else
      {
        $Lft = $me;
        $Rgt = $op;
      }

      //����������� ���� �������
      $LftR = getdata($Lft, 'hero', 'race');
      $RgtR = getdata($Rgt, 'hero', 'race');
      
      //������� ��������
      if ($map[$i][$j] != 0)
      {
        //���� ��� ������
        if ($map[$i][$j] < 5)
        {
          $Monster = $LftR."/".$map[$i][$j].$Type."right";
          echo("<img src='images/warriors/battle/".$Monster.".jpg' width=64 height=64 border=0 alt='".$MName[$Monster]."'>");
        }
        if (($map[$i][$j] < 9)&&($map[$i][$j] > 4))
        {
          $Monster = $RgtR."/".($map[$i][$j]-4).$Type."left";
          echo("<img src='images/warriors/battle/".$Monster.".jpg' width=64 height=64 border=0 alt='".$MName[$Monster]."'>");
        }
        
        //������ �������
        if ($map[$i][$j] == 9)
         echo("<img src='images/objects/".$Type.".jpg' width=64 height=64 border=0 alt='���������� �����'>");
      }
      else //������ ��� � ������. ������� ����������
        echo("<img src='images/terrain/battle/".$Type.".jpg' width=64 height=64 border=0>");
      echo("</a>");
      echo("</td>");
    }
    echo("</tr>");
  }

  //������� ��������� 3 ������� ��� �����
  echo("<tr>\n");
    echo("<td colspan=10 align=center>\n");
      PrintLog($me);
    echo("</td>\n");
  echo("</tr>\n");

  //����� �������
  ?>
    </table>
    </center>
    </td>
    <td>
      <table border=1 cellpadding=0 cellspacing=0 width=100%>
      <?
        //������� ���������� ������ ��� ������� ������ ���������
        for ($i = 1; $i <= 4; $i++)
        {
          $Name  = query($i, $OpRace, 'name');
          $Count = getdata($op, 'army', 'level'.$i);
          echo("<tr>\n");
          echo("<td><font color=white>$Name</font></td>\n");
          echo("<td><font color=white>$Count</font></td>\n");
          echo("</tr>\n");
        }
      ?>
      </table>
    </td>
    </tr>
    </table>
    </center>
  <?
?>