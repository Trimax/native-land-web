<style>
img.x
{
	position:absolute;
	left:0;
	top:0;
	z-index:-1;
}
img.z
{
	position:absolute;
	left:-10;
	top:-10;
	z-index:-100;
}
</style>
<script>
var sw;
var sh;
sw = screen.width-20;
sh  = screen.height-140;
sw = screen.width;
sh  = screen.height-80;
document.write("<img class=x src=images/map.jpg width=" + sw + " height=" + sh + ">");
</script>
<?
//������ �������
include "functions.php";

//���� �� ������� ��� ������������, �� �������� ����� �����
$lg = trim($HTTP_COOKIE_VARS["nativeland"]);
$pw = trim($HTTP_COOKIE_VARS["password"]);
ban();

//����������?
if (finduser($lg, $pw) != 1)
	moveto('index.php');

//���� ����� � �����, �� �������������� ��� ����
FromBattle($lg);

//���� ����� � ����� �������, �� �������������� ��� ����
(int)$Battle = getdata($lg, 'battles', 'battle');
if ($Battle != 0)
  moveto("fight.php");

//��������� �������� �� ����
function GetPicture($Lgn)
{
  $Picture = getfrom('name', $Lgn, 'monsters', 'art');

  //������� ��������
  return $Picture;
}

//����� ��������� (������� 20x20 � ����������� ��������, ��������� � ����������)
function minimap($Login)
{
  //�������� �����
  for ($i = 1; $i <= 20; $i++)
    for ($j = 1; $j <= 20; $j++)
      $MInfo[$i][$j] = 0;

  //�������� ��� ���� ������
  $ath = mysql_query("select * from mapbuild where login='$Login';");
  if ($ath)	//���� ���������
	  while ($rw = mysql_fetch_row($ath))	//��������� ������?
		  $MInfo[$rw[4]][$rw[3]] = 3;

  //�������� ���� ���������� � ���������� �������
  $Rx = getdata($Login, 'coords', 'rx');
  $Ry = getdata($Login, 'coords', 'ry');

  //�������� ����
  $MInfo[$Ry][$Rx] = 1;

  //���������� �������
  $Rx = getdata($Login, 'capital', 'rx');
  $Ry = getdata($Login, 'capital', 'ry');

  //�������� �������
  $MInfo[$Ry][$Rx] = 2;

  //������� �����
  ?>
  <style>
    .xl24 {mso-style-parent:style0; border:.5pt solid windowtext;}
  </style>
  <table border=0 cellpadding=0 cellspacing=0 style='border-collapse: collapse; width:100pt'>
  <?
    for ($i = 1; $i <= 20; $i++)
    {
      echo ("<tr height=5 style='mso-height-source:userset; height:5.1pt'>");
      for ($j = 1; $j <= 20; $j++)
      {
        //���������
        //$Help = getregion($i, $j, 0);

        //�������� ��� ������
        switch($MInfo[$i][$j])
        {
        case 0:
          echo("<td class=xl24 width=5 style='border-left:none;width:5pt' bgcolor=#339900><span style='display:none'>.</span></td>");
          break;
        case 1:
          echo("<td title='$Help' class=xl24 width=5 style='border-left:none;width:5pt' bgcolor=#3366FF><span style='display:none'>.</span></td>");
          break;
        case 2:
          echo("<td title='$Help' class=xl24 width=5 style='border-left:none;width:5pt' bgcolor=#FF0000><span style='display:none'>.</span></td>");
          break;
        case 3:
          echo("<td title='$Help' class=xl24 width=5 style='border-left:none;width:5pt' bgcolor=#FFFF00><span style='display:none'>.</span></td>");
          break;
        } //Switch
      } //For
      echo ("</tr>");
    }
  ?>
  </table>
  <?
}

//������� ������
function Button($Opp, $Go)
{
  $hn = getdata($Opp, 'hero', 'name');
  if (empty($hn))
    $hn = $Opp;
  if ($hn == '0')
    $hn = '�����';
  echo("<br>");
  echo("<form action='map.php' method='post'>");
  echo("<input type='hidden' name='go' value='".$Go."'>");
  echo("<input type='hidden' name='attack' value='1'>");
  echo("<input type='submit' value='".$hn."' style='background:#000099; font-WEIGHT: BOLD; COLOR: white; cursor: hand; list-style: lower-alpha; filter: Alpha(Opacity=20, FinishOpacity=100, Style=3)'>");
  echo("</form>");
}

//����������
echo ("<META HTTP-EQUIV='REFRESH' CONTENT=15>");

//��� ������ ������:
$rx = getdata ($lg, 'coords', 'rx');
$ry = getdata ($lg, 'coords', 'ry');
$hx = getdata ($lg, 'coords', 'x');
$hy = getdata ($lg, 'coords', 'y');

//������
if (($ry < 1)||(empty($ry))) {$ry = 1;}
if (($rx < 1)||(empty($rx))) {$rx = 1;}
if (($ry > 20)||(empty($ry))) {$ry = 20;}
if (($rx > 20)||(empty($rx))) {$rx = 20;}

//������
$ury = $ry-1;
$urx = $rx-1;
$dry = $ry+1;
$drx = $rx+1;

//������
if ($ury < 1) {$ury = 1;}
if ($urx < 1) {$urx = 1;}
if ($ury > 20) {$ury = 20;}
if ($ury > 20) {$ury = 20;}

//����� �������
echo ("<title>����� ��������</title>");
if ($build != 1) 
  echo ("<link rel='stylesheet' type='text/css' href='style.css'/>");

//������� ��� ������������
$name = getregion($rx, $ry, 0);
if ($build != 1) 
  echo ("<center><h2><font color=white>$name (".getdata($lg, 'hero', 'name').")</font></h2></center>");

echo ("<center><table border=0 width=80%><tr><td>");
echo ("<center><table border=0 width=30%>");
echo ("<tr>");
echo ("<td width=60%>");
echo("<center><TABLE ALIGN=CENTER WIDTH='100%' BORDER=1 CELLSPACING=0 CELLPADDING=0>");

//������ ���� �������������
for ($i = 1; $i < 11; $i++)
	for ($j = 1; $j < 11; $j++)
		$hmap[$i][$j] = "0*0=0";

//������ ���� ������ � ������
$ath = mysql_query("select * from coords;");
if ($ath) //���� ���������
	while ($rw = mysql_fetch_row($ath))	//��������� ������?
		if (($rx == $rw[1])&&($ry == $rw[2]))	//������ ������
			$hmap[$rw[3]][$rw[4]] = "1*1=".$rw[0];

//� ���
$hmap[$hx][$hy] = "1*1=".$lg;

//�������
$ath = mysql_query("select * from random where rx='".$rx."' and ry='".$ry."';");
if ($ath) //���� ���������
	while ($rw = mysql_fetch_row($ath))	//��������� ������?
			$hmap[$rw[3]][$rw[4]] = "1*1=".$rw[0];

//������ �����...
$file = fopen("maps/".$rx."x".$ry.".map", "r");
for ($x = 1; $x < 11; $x++)
	for ($y = 1; $y < 11; $y++)
		$map[$x][$y] = fgets($file, 255);
fclose($file);

//������ ��� �������
$ath = mysql_query("select * from capital;");
$castle_count=0;
if ($ath)	//���� ���������
	while ($rw = mysql_fetch_row($ath))	//��������� ������?
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
      $obji[$object_count] = $rw[7]; //���������� � ��������� (�������� ������)
      $objx[$object_count] = $rw[5]; //���������� ��������� X
      $objy[$object_count] = $rw[6]; //���������� ��������� Y
      $object_count++;
		} //if

$hp = getdata($lg, 'inf', 'def');
if (($hp < 1)&&($go != 0))
{
  $go = 0;
  moveto('map.php?message=<b><font color=white>� ��� ���� ��</font></b>');
}

//���������� ������ ����������
$orx = $rx;
$ory = $ry;
$ox = $hx;
$oy = $hy;
$no = 0;

//������ � ������
if ($enter == 1)
  moveto("newbuild.php?login=".$lg."&rx=".$rx."&ry=".$ry."&x=".$ax."&y=".$ay);

//����� ���� ����-������ �������?
if ($attack == 1)
{
	//�������� ��� ���������
	$dx = 0;
	$dy = 0;
	if ($go == 1) {$dx=-1;}
	if ($go == 2) {$dy=-1;}
	if ($go == 3) {$dy=1;}
	if ($go == 4) {$dx=1;}
	$beet = trim(substr($hmap[$hx+$dx][$hy+$dy], 4));

  //���� ��� ������, �� ���������� ��������� ��� �������� � ��������
  $hname = getdata($beet, 'hero', 'name');

  //���� ��� ������
  if (empty($hname))
  {
    MonsterBattle($lg, $beet, $hx+$dx, $hy+$dy, $rx, $ry);
    moveto("battle.php");
  } //����� �������
  else
  {
    //���� ��� ����� �����
	  if (trim(substr($map[$hx+$dx][$hy+$dy], 4)) != '0')	//� ��������� �� �������?
		  if (getdata($lg, 'city', 'build13') != 0)
			  {
				  $bt = trim(substr($map[$hx+$dx][$hy+$dy], 4));
  				//moveto("game.php?action=34&users=".$bt);
           moveto("map.php?message=�������� ����������");
		  	} else //��� �������
           moveto("map.php?message=� ��� �� ���������(�) ".getfrom('race', getdata($lg, 'hero', 'race'), 'buildings', 'build13').". ������� ���������� ��������� ������!");

    //���������, � �� � ����� �� �����?
    $OpBattle = getdata($beet, 'battle', 'battle');
    if (($Battle == 0)&&($OpBattle == 0))
    {
      if ($lg != $beet)
      {
        BattleOn($lg, $beet);
        moveto("battle.php");
      } else //���� �������� �� ����-��
        moveto("map.php?message=�� �� �� ������ ������� ������ ���� �� ��������");
    } else // ��� ������ �� � �����
      moveto("map.php?message=���� ����� ������ ����� � �����. ��������� � ����������...");
  } //����� �� �������
} //����� ����-��

//�����
if ($go == 2)
{
  $hy = $hy - 1;

	//�������
	if ($hy == 0)
	{
  	//���� �������
		$hy = 10;
		$rx = $rx - 1;

    //������ �����
		if ($rx == 0)
		{
			$rx = 1;
			$hy = 1;
		}
  }
}

//������
if ($go == 3)
{
	$hy = $hy + 1;

  //�������
	if ($hy == 11)
	{
  	//���� �������
	  $hy = 1;
  	$rx = $rx + 1;

    //������ �����
		if ($rx == 21)
		{
			$rx = 20;
			$hy = 10;
		}
	}
}

//�����
if ($go == 1)
{
	$hx = $hx - 1;

  //�������
	if ($hx == 0)
	{
    //���� �������
		$hx = 10;
		$ry = $ry - 1;

		//������ �����
		if ($ry == 0)
		{
			$ry = 1;
			$hx = 1;
		}
	}
}

//����
if ($go == 4)
{
	$hx = $hx + 1;

  //�������
	if ($hx == 11)
	{
		//���� �������
		$hx = 1;
		$ry = $ry + 1;

    //������ �����
		if ($ry == 21)
		{
			$ry = 20;
			$hx = 10;
		}
	}
}

//���� �����
if ($no == 0)
{
	//��������� �������
	$hmap[$ox][$oy] = "0*0=0\n";

  //��������� �� ����� �����
	if (($orx != $rx)||($ory != $ry))
	{
		//������ �����...
		$file = fopen("maps/".$rx."x".$ry.".map", "r");
		for ($x = 1; $x < 11; $x++)
			for ($y = 1; $y < 11; $y++)
				$map[$x][$y] = fgets($file, 255);
		fclose($file);
	}

  //���� ��������
	if (($map[$hx][$hy][2] != '0')||($hmap[$hx][$hy][0] != '0'))
	{
		$hx = $ox;
		$hy = $oy;
		$rx = $orx;
		$ry = $ory;
    $go = 0;
	}

	//������ ����� � ����� ������
	$hmap[$hx][$hy] = "1*1=".$lg."\n";

  //�������� ������ � ����
	change ($lg, 'coords', 'rx', $rx);
	change ($lg, 'coords', 'ry', $ry);
	change ($lg, 'coords', 'x', $hx);
	change ($lg, 'coords', 'y', $hy);

 	//���� ����� ����-�� �����, �� ��������� ��
  if ($go != 0)
 		change($lg, 'inf', 'def', $hp-1);

	//��������
	if ($go != 0)
	{
    //�������� ������� ������. ��� �� ���������: � ������ ��� ���
    change($lg, 'city', 'build20', 0);

    //�������� ������ � �������
    $cx  = getdata($lg, 'capital', 'rx');
    $cy  = getdata($lg, 'capital', 'ry');
    $cpx = getdata($lg, 'capital', 'x');
    $cpy = getdata($lg, 'capital', 'y');

    //���� �� � �������, ������ 1
    if (($rx == $cx)&&($ry == $cy)&&($hx == $cpx)&&($hy == $cpy))
      change($lg, 'city', 'build20', 1);

    //���� �� � ������ ������, ������ 2
    $ath = mysql_query("select * from mapbuild where login = '$lg';");
    if ($ath)	//���� ���������
    	while ($rw = mysql_fetch_row($ath))	//��������� ������?
      {
        echo("<b><font color=white>".$rx." = ".$rw[3]."; ".$ry." = ".$rw[4]."; ".$x." = ".$rw[5]."; ".$y." = ".$rw[6]."</font></b><br>");
        if (($rx == $rw[3])&&($ry == $rw[4])&&($hx == $rw[5])&&($hy == $rw[6]))
          change($lg, 'city', 'build20', 2);
      }

    //���������� ������
		$go = 0;
		?>
		<script>
		window.location.href('map.php');
		</script>
		<?
	}
}

//������ ����� ��������
$file = fopen("maps/".$rx."x".$ry.".map", "r");

//�����
for ($x = 1; $x < 11; $x++)
{
	echo("<tr>");
	//����� ������ �������

	for ($y = 1; $y < 11; $y++)
	{
  	//�������� ������ �� ������
	  $fld = fgets($file, 255);
		$hld = $hmap[$x][$y];

    //���������� ������
    echo ("<td align=center>");
    echo ("<a href=newbuild.php?login=".$lg."&enter=1&rx=".$rx."&ry=".$ry."&x=".$x."&y=".$y."&terr=".$fld[0].">");	

    //� ��� �� ��� � ��� �����?
    for ($i = 0; $i < $castle_count; $i++) //��������� ����������?
      if (($cstx[$i] == $x)&&($csty[$i] == $y))
        $fld = $fld[0].$fld[1].$fld[2].$fld[3].$cstl[$i];

    //� ��� �� ��� � ��� ������� �������
    for ($i = 0; $i < $object_count; $i++) //��������� ����������?
      if (($objx[$i] == $x)&&($objy[$i] == $y))
        $fld = $fld[0].$fld[1].$fld[2].$fld[3].$objl[$i];

		/* -=== ��� ===- */

    //�����
		if (($fld[0] == '0')&&($fld[2] == 0)&&($fld[4] == '0'))
			if ($hld[0] == 0)
				echo ("<img src='images/terrain/grass.gif' width=32 height=32 BORDER=0 alt=�����>");
			else
			{
				$tp = "m";
				if ($hld[2] == '1') 
          $tp = "h";
        $mnm = trim(substr($hld, 4));
        $hnm = getdata($mnm, 'hero', 'name');
        $direct = 'heroes';
        if (empty($hnm))
        {
          $hnm = $mnm;
          $tp = getpicture($mnm);
          $direct = 'monsters/grass'.$tp;
  				echo ("<img src='images/".$direct."' width=32 height=32 BORDER=0 alt='".$hnm."'>");
        }
        else
  				echo ("<img src='images/".$direct."/".$tp."grass.bmp' width=32 height=32 BORDER=0 alt='".$hnm."'>");
			}

    //����
		if (($fld[0] == '1')&&($fld[2] == 0)&&($fld[4] == '0'))
			if ($hld[0] == 0)
				echo ("<img src='images/terrain/snow.gif' width=32 height=32 BORDER=0 alt=����>");
			else
			{
				$tp = "m";
				if ($hld[2] == '1') 
          $tp = "h";
        $mnm = trim(substr($hld, 4));
        $hnm = getdata($mnm, 'hero', 'name');
        $direct = 'heroes';
        if (empty($hnm))
        {
          $tp = getpicture($mnm);
          $hnm = $mnm;
          $direct = 'monsters/snow'.$tp;
  				echo ("<img src='images/".$direct."' width=32 height=32 BORDER=0 alt='".$hnm."'>");
        }
        else
  				echo ("<img src='images/".$direct."/".$tp."snow.bmp' width=32 height=32 BORDER=0 alt='".$hnm."'>");
			}

    //�����
		if (($fld[0] == '2')&&($fld[2] == 0)&&($fld[4] == '0'))
			if ($hld[0] == '0')
				echo ("<img src='images/terrain/sand.bmp' width=32 height=32 BORDER=0 alt=�����>");
			else
			{
				$tp = "m";
				if ($hld[2] == '1') 
          $tp = "h";
        $mnm = trim(substr($hld, 4));
        $hnm = getdata($mnm, 'hero', 'name');
        $direct = 'heroes';
        if (empty($hnm))
        {
          $hnm = $mnm;
          $tp = getpicture($mnm);
          $direct = 'monsters/sand'.$tp;
  				echo ("<img src='images/".$direct."' width=32 height=32 BORDER=0 alt='".$hnm."'>");
        }
        else
  				echo ("<img src='images/".$direct."/".$tp."sand.bmp' width=32 height=32 BORDER=0 alt='".$hnm."'>");
			}

    //����
		if (($fld[0] == '3')&&($fld[2] == 0)&&($fld[4] == '0'))
			if ($hld[0] == '0')
				echo ("<img src='images/terrain/Fire.jpg' width=32 height=32 BORDER=0 alt=����>");
			else
			{
				$tp = "m";
				if ($hld[2] == '1') 
          $tp = "h";
        $mnm = trim(substr($hld, 4));
        $hnm = getdata($mnm, 'hero', 'name');
        $direct = 'heroes';     
        if (empty($hnm))
        {
          $hnm = $mnm;
          $tp = getpicture($mnm);
          $direct = 'monsters/fire'.$tp;
  				echo ("<img src='images/".$direct."' width=32 height=32 BORDER=0 alt='".$hnm."'>");
        }
        else
  				echo ("<img src='images/".$direct."/".$tp."fire.bmp' width=32 height=32 BORDER=0 alt='".$hnm."'>");
			}

    //����
    if (($fld[0] == '4')&&($fld[2] == 0)&&($fld[4] == '0'))
			if ($hld[0] == '0')
  			echo ("<img src='images/terrain/water.gif' width=32 height=32 BORDER=0 alt=����>");
			else
			{
				$tp = "m";
				if ($hld[2] == '1') 
          $tp = "h";
        $mnm = trim(substr($hld, 4));
        $hnm = getdata($mnm, 'hero', 'name');
        if (empty($hnm))
        {
          $hnm = $mnm;
          $tp = getpicture($mnm);
          $direct = 'monsters/water'.$tp;
  				echo ("<img src='images/".$direct."' width=32 height=32 BORDER=0 alt='".$hnm."'>");
        }
        else
  				echo ("<img src='images/heroes/".$tp."water.bmp' width=32 height=32 BORDER=0 alt='".$hnm."'>");
			}

    /* -=== ������ ===- */

    //������ �� �����
		if (($fld[2] == '1')&&($fld[4] == '0')&&($fld[0] == '0'))
			echo ("<img src='images/terrain/metal.bmp' width=32 height=32 BORDER=0 alt='������ �������'>");
    //������ �� �����
		if (($fld[2] == '1')&&($fld[4] == '0')&&($fld[0] == '1'))
			echo ("<img src='images/terrain/snmetal.bmp' width=32 height=32 BORDER=0 alt='������ �������'>");
    //������ �� �����
		if (($fld[2] == '1')&&($fld[4] == '0')&&($fld[0] == '2'))
			echo ("<img src='images/terrain/smetal.bmp' width=32 height=32 BORDER=0 alt='������ �������'>");
    //������ �� ����
		if (($fld[2] == '1')&&($fld[4] == '0')&&($fld[0] == '3'))
			echo ("<img src='images/terrain/lmetal.bmp' width=32 height=32 BORDER=0 alt='������ �������'>");
    //������ �� �����
    if (($fld[2] == '2')&&($fld[4] == '0')&&($fld[0] == '0'))
			echo ("<img src='images/terrain/rock.bmp' width=32 height=32 BORDER=0 alt='������ �����'>");
    //������ �� �����
    if (($fld[2] == '2')&&($fld[4] == '0')&&($fld[0] == '1'))
			echo ("<img src='images/terrain/snrock.bmp' width=32 height=32 BORDER=0 alt='������ �����'>");
    //������ �� �����
    if (($fld[2] == '2')&&($fld[4] == '0')&&($fld[0] == '2'))
			echo ("<img src='images/terrain/srock.bmp' width=32 height=32 BORDER=0 alt='������ �����'>");
    //������ �� ����
		if (($fld[2] == '2')&&($fld[4] == '0')&&($fld[0] == '3'))
			echo ("<img src='images/terrain/lrock.bmp' width=32 height=32 BORDER=0 alt='������ �����'>");
    //������ �� �����
    if (($fld[2] == '3')&&($fld[4] == '0')&&($fld[0] == '0'))
			echo ("<img src='images/terrain/tree".rand(1,3).".gif' width=32 height=32 BORDER=0 alt=�������>");
    //������ �� �����
		if (($fld[2] == '3')&&($fld[4] == '0')&&($fld[0] == '1'))
			echo ("<img src='images/terrain/sntree".rand(1,3).".bmp' width=32 height=32 BORDER=0 alt=�������>");
    //������ �� �����
		if (($fld[2] == '3')&&($fld[4] == '0')&&($fld[0] == '2'))
			echo ("<img src='images/terrain/stree".rand(1,2).".bmp' width=32 height=32 BORDER=0 alt=�������>");
    //������ �� ����
		if (($fld[2] == '3')&&($fld[4] == '0')&&($fld[0] == '3'))
			echo ("<img src='images/terrain/ltree.bmp' width=32 height=32 BORDER=0 alt=�������>");
    

		/* -=== �������, �� ��������� �� ���������� ===- */

    //�����
		if (($fld[2] == '4'))
			echo ("<img src='images/objects/wall.gif' width=32 height=32 BORDER=0 alt=�����>");

   	/* -=== ����� �� ������ ���������� ===- */
 	  if (($fld[4] != '0')&&($fld[2] == '0'))
	 	{
      //���������� ���������
			$nm = getdata(trim(substr($fld, 4)), 'hero', 'name');
			if ($nm != '')
			{
        //����� �� �����
				if ($fld[0] == 0)
					echo ("<img src='images/terrain/castle.gif' width=32 height=32 BORDER=0 alt='�����. ����������� �������� ".$nm."'>");
        //����� �� �����
				if ($fld[0] == 1)
					echo ("<img src='images/terrain/castlesnow.gif' width=32 height=32 BORDER=0 alt='�����. ����������� �������� ".$nm."'>");
        //����� �� �����
				if ($fld[0] == 2)
					echo ("<img src='images/terrain/castlesand.gif' width=32 height=32 BORDER=0 alt='�����. ����������� �������� ".$nm."'>");
				//����� �� ����
				if ($fld[0] == 3)
					echo ("<img src='images/terrain/castlefire.gif' width=32 height=32 BORDER=0 alt='�����. ����������� �������� ".$nm."'>");
			}
			else
				echo ("<img src='images/terrain/castle.gif' width=32 height=32 BORDER=0 alt='����������� �����'>");
		}

    /* -=== ����� �� ������ ���������� ===- */

    //����� �� �����
 	  if (($fld[4] != '0')&&($fld[0] == '0')&&($fld[2] == '1'))
			echo ("<img src='images/buildings/gold_grass.bmp' width=32 height=32 BORDER=0 alt='�����. ����������� �������� ".getdata(trim(substr($fld, 4)), 'hero', 'name')."'>");
    //����� �� �����
	  if (($fld[4] != '0')&&($fld[0] == '1')&&($fld[2] == '1'))
			echo ("<img src='images/buildings/gold_snow.bmp' width=32 height=32 BORDER=0 alt='�����. ����������� �������� ".getdata(trim(substr($fld, 4)), 'hero', 'name')."'>");
    //����� �� �����
		if (($fld[4] != '0')&&($fld[0] == '2')&&($fld[2] == '1'))

			echo ("<img src='images/buildings/gold_sand.bmp' width=32 height=32 BORDER=0 alt='�����. ����������� �������� ".getdata(trim(substr($fld, 4)), 'hero', 'name')."'>");
		//����� �� ����
		if (($fld[4] != '0')&&($fld[0] == '3')&&($fld[2] == '1'))
			echo ("<img src='images/buildings/gold_fire.bmp' width=32 height=32 BORDER=0 alt='�����. ����������� �������� ".getdata(trim(substr($fld, 4)), 'hero', 'name')."'>");

    /* -=== ������� �� ������ ���������� ===- */

		//������ �� �����
		if (($fld[4] != '0')&&($fld[0] == '0')&&($fld[2] == '2'))
			echo ("<img src='images/buildings/kam_grass.bmp' width=32 height=32 BORDER=0 alt='������. ����������� �������� ".getdata(trim(substr($fld, 4)), 'hero', 'name')."'>");
		//������ �� �����
    if (($fld[4] != '0')&&($fld[0] == '1')&&($fld[2] == '2'))
			echo ("<img src='images/buildings/kam_snow.bmp' width=32 height=32 BORDER=0 alt='������. ����������� �������� ".getdata(trim(substr($fld, 4)), 'hero', 'name')."'>");
		//������ �� �����
    if (($fld[4] != '0')&&($fld[0] == '2')&&($fld[2] == '2'))
			echo ("<img src='images/buildings/kam_sand.bmp' width=32 height=32 BORDER=0 alt='������. ����������� �������� ".getdata(trim(substr($fld, 4)), 'hero', 'name')."'>");
		//������ �� ����
		if (($fld[4] != '0')&&($fld[0] == '3')&&($fld[2] == '2'))
			echo ("<img src='images/buildings/kam_fire.bmp' width=32 height=32 BORDER=0 alt='������. ����������� �������� ".getdata(trim(substr($fld, 4)), 'hero', 'name')."'>");

    /* -=== ������� �� ������ ���������� ===- */

		//��������� �� �����
    if (($fld[4] != '0')&&($fld[0] == '0')&&($fld[2] == '3'))
			echo ("<img src='images/buildings/les_grass.bmp' width=32 height=32 BORDER=0 alt='���������. ����������� �������� ".getdata(trim(substr($fld, 4)), 'hero', 'name')."'>");

		//��������� �� �����
    if (($fld[4] != '0')&&($fld[0] == '1')&&($fld[2] == '3'))
			echo ("<img src='images/buildings/les_snow.bmp' width=32 height=32 BORDER=0 alt='���������. ����������� �������� ".getdata(trim(substr($fld, 4)), 'hero', 'name')."'>");
    //��������� �� �����
		if (($fld[4] != '0')&&($fld[0] == '2')&&($fld[2] == '3'))
			echo ("<img src='images/buildings/les_sand.bmp' width=32 height=32 BORDER=0 alt='���������. ����������� �������� ".getdata(trim(substr($fld, 4)), 'hero', 'name')."'>");
		//��������� �� ����
		if (($fld[4] != '0')&&($fld[0] == '3')&&($fld[2] == '3'))
			echo ("<img src='images/buildings/les_fire.bmp' width=32 height=32 BORDER=0 alt='���������. ����������� �������� ".getdata(trim(substr($fld, 4)), 'hero', 'name')."'>");
		echo ("</a></td>");
	}
	echo ("</tr>");
}
fclose($file);

//����� �������
echo ("</table></center></td>");
echo ("</table></td>");
echo ("<td valign=top><center><font color=yellow><h2>��������</h2></font>");
echo ("<a href=game.php?action=8><b><font color=white>����� ������</font></b></a><br><br>");
echo ("<font color=white>� ���: ".getdata($lg, 'inf', 'def')." ����� ��������</font><br><br>");

//���� ��������
	//������
	echo ("<table border=0><tr><td colspan=3 align=center>");
		echo ("<a href='map.php?go=1'><img src='images/arrows/up.gif' alt='���� �� �����' width=40 height=40 border=0></a></td></tr>");
			echo ("<tr><td align=center><a href='map.php?go=2'><img src='images/arrows/left.gif' alt='���� �� �����' width=40 height=40 border=0></a></td>");
			echo ("<td align=center><img src='images/terrain/empty.gif' width=40 height=40></a></td>");
			echo ("<td align=center><a href='map.php?go=3'><img src='images/arrows/right.gif' alt='���� �� ������' width=40 height=40 border=0></a></td></tr>");
		echo ("<tr><td align=center colspan=3><a href='map.php?go=4'><img src='images/arrows/down.gif' alt='���� �� ��' width=40 height=40 border=0></a></td></tr>");
    echo("<tr><td align=center colspan=3>");

    //������� ������ ��� ����� ���, ��� ��������� ������
    echo("<font color=white><b>������� ��</b></font><br>");
      //���-������ ����� ������?
      $smb = trim(substr($hmap[$hx-1][$hy], 4));
      if (empty($smb))
        $smb = '0';
      if ($smb != '0')
        Button($smb, 1);
      //���-������ ����� �����?
      $smb = trim(substr($hmap[$hx+1][$hy], 4));
      if (empty($smb))
        $smb = '0';
      if ($smb != '0')
        Button($smb, 4);
      //���-������ ����� �����?
      $smb = trim(substr($hmap[$hx][$hy-1], 4));
      if (empty($smb))
        $smb = '0';
      if ($smb != '0')
        Button($smb, 2);
      //���-������ ����� ������?
      $smb = trim(substr($hmap[$hx][$hy+1], 4));
      if (empty($smb))
        $smb = '0';
      if ($smb != '0')
        Button($smb, 3);
    //������ ������
    HelpMe(4, 1);
    echo("</td></tr>");
	echo ("</table>");
echo ("</center></td></tr></table><font color=white><b>".$message."</b><font>");
MiniMap($lg);
echo ("</center>");
?>