<title>������ �����</title>
<link rel='stylesheet' type='text/css' href='style.css'/>
<body background='images/back.jpe'>
<?
  //���������� ������ �������
  include "battlemodule.php";

  //�������� ������
  $lg = trim($HTTP_COOKIE_VARS["nativeland"]);
  if ((hasuser($lg) == 0)||(trim($login) != trim($lg)))
    moveto("index.php");
  FromBattle($lg);

  //��������� �� �������� ������
  if (  ( empty($login) ) || ( empty($oppon) ) || ( empty($type) )  )
    moveto('map.php');

  //� ��������� �� �� � ��� �� �������, ��� � ����� ��������������
  $rx = getdata($lg, 'coords', 'rx');
  $ry = getdata($lg, 'coords', 'ry');

  //���������� �������
  $cx = getdata($oppon, 'capital', 'rx');
  $cy = getdata($oppon, 'capital', 'ry');

  //����� �� �� ���� ��������
  if ($lg == $oppon)
    messagebox("�� �� �� �������� �������� ����� ������ ������������ �����?", "map.php");

  //�� ���������
  if (($cx == $rx)&&($cy == $ry))
  {
  }
  else
    moveto("map.php");

  //���� ��� �������� ���������� �������� ������?
  $north = getdata($oppon, 'city', 'build13');
  if ($north == 0)
    messagebox("���� �� ����� �������� �� ��� �����������. ��� ����������� �������� ������� �������� ����...", "map.php");

  //��� ������ �������� �����. ������ ���������� ����� ����������� � ��������������
  $my_level1 = getdata($lg, 'army', 'level1');
  $my_level2 = getdata($lg, 'army', 'level2');
  $my_level3 = getdata($lg, 'army', 'level3');
  $my_level4 = getdata($lg, 'army', 'level4');

  //�� ���� �� � ��� �����
  $summ = $my_level1 + $my_level2 + $my_level3 + $my_level4;

  //���� ����� ���, �� �� ��������
  if ($summ == 0)
    messagebox("�� � ��� ���� ������ ��� �����. ��� �� �� ���������� ������� �����? ������ �������������� � ��������� ���...", "map.php");

  //��� �������� �������, ����� �������� ����� � �������������� ���� �������...
  change($lg, 'battles', 'battle', '1');
  change($lg, 'battles', 'opponent', $oppon);
  change($lg, 'battles', 'health', '2');
  change($oppon, 'battles', 'battle', '1');
  change($oppon, 'battles', 'opponent', $lg);
  change($oppon, 'battles', 'health', '1');

  //������������� �������������� ��������� � ������� ����� ����������
  change($lg, 'battle', 'timeout', time());      //�������
  change($lg, 'battle', 'turn', $lg);            //��� �����
  change($lg, 'battle', 'attack', 1);            //����� ��������
  change($lg, 'battle', 'health', 3);            //������� � ���� ��
  change($oppon, 'battle', 'timeout', time());
  change($oppon, 'battle', 'turn', $lg);
  change($oppon, 'battle', 'attack', 1); 
  change($oppon, 'battle', 'health', 3);            

  //��������� ����� ������ ����
  $file = fopen("data/count.dat", "r");
  $num = fgets($file, 255);
	fclose ($file);
	$num++;
	$file = fopen("data/count.dat", "w");
	fputs ($file, $num);
	fclose ($file);

  //���������� ���������� ��� ����������� �� "1"
  $adm = getadmin();
  $btls = getfrom('admin', $adm, 'settings', 'f4');
  $btls++;
  setto('admin', $adm, 'settings', 'f4', $btls);

  //������ ��� ����
	$file = fopen("data/logs/".$num.".log", "w");
	fclose ($file);

  //���������� ��� �� ���� ������
  change($lg, 'time', 'combats', getdata($lg, 'time', 'combats')+1);
  change($oppon, 'time', 'combats', getdata($oppon, 'time', 'combats')+1);

  //����� ��� ����� ���
  change($lg, 'battle', 'info', $num);
  change($oppon, 'battle', 'info', $num);

  //���������� �������
  $xc = getdata($oppon, 'capital', 'x');
  $yc = getdata($oppon, 'capital', 'y');

  //���������� ��� ���������� ��� ��������
  $file = fopen("maps/".$cx."x".$cy.".map", "r");
  for ($i = 1; $i < 11; $i++)
    for ($j = 1; $j < 11; $j++)
    {
      $temp = fgets($file);
      if (($i == $xc)&&($j == $yc))
        $fld = $temp;
    }
  fclose($file);

  //���������� ��� ���������� � ���������� ���
  $terr = $fld[0];

  //��� ���������� � ��
  change($lg, 'battle', 'value', $terr);
  change($oppon, 'battle', 'value', $terr);

  //������������ ������
  PositionWarriors($lg, 0);
  PositionWarriors($oppon, 1);

  //�������������� �� ����� �����...
  moveto("fight.php");
?>