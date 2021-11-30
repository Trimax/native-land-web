<?

//������ MAX_ABILITY
$ZERO = 1106665145;

//��� ������� ��������� ������ ���������������� ����� ($min & $max - �����)
function random($min, $max)
{
  //������������� �������
  if ($max < $min)
  {
    $temp = $max;
    $max = $min;
    $min = $temp;
  }

  //����� �����
  $number = 0;

  //������� �������� ������������ ����� � ��������� (1; 3)
  $moment = time() - $ZERO; 
  $rnd = sin($moment)+2;
 
  //��������� ���������� ����� � ��� ��������
  $number = round($rnd*$max/3);

  //���������� �����
  return $number;
}

//�������� �� ������ ������ ������
function cut($s)
{
  $ns = "";
  for ($i = 1; $i < strlen($s); $i++)
    $ns = $ns.$s[$i];
  return $ns;
}

//������� ��� ������� ���. ������������
function newchar($Login)
{
  //�������
  $num = 0;

  //��� ��� �������
  $ch1 = getdata($Login, 'abilities', 'combatmagic');
  $ch2 = getdata($Login, 'abilities', 'mindmagic');

  //���� ��� ������ ������ � �� �� ��������, ���������� ����
  $emptycell = 0;
  for ($i = 1; $i <= 16; $i++)
  {
    $inf = getdata($Login, 'newchar', 'achar'.$i);
    if ($inf[0] != 'E')
      $emptycell = 1;
  }

  //���� �� �� ��������
  if ($emptycell == 0)
  {
    change($Login, 'abilities', 'combatmagic', '0');
    change($Login, 'abilities', 'mindmagic', '0');
    moveto("game.php?action=1");
  }

  //���� �� �������
  while ($num == 0)
  {
    //�������� ��������� ����������� (MAX_ABILITY)
    $num = rand(30, 1);

    //� ����� ��� ������ ��� ������, ����� ����� ����������� ������ ������
    $emptycell = 0;
    $same = 0;
    for ($i = 1; $i <= 16; $i++)
    {
      $inf = getdata($Login, 'newchar', 'achar'.$i);
      if ($inf[0] == '0')
        $emptycell = 1;

      //���� ��������� � ����� �� ��� ���������
      if (cut($inf) == $num)
        $same = 1;
    }

    //���� ��������� ���� ���, � ��� ����������� �����, �� ��� ���� �� �����!
    if (($same == 0)&&($emptycell == 0))
      $num = 0;

    //���������, �� ������������ �� ��� ��� � �������
    if (($ch1 != $num)&&($ch2 != $num)&&($num != 0))
    {
      //��� �� ����� ����������� ��� � ��� (� ���� ����, �� ������ ��������� �)
      for ($i = 1; $i <= 16; $i++)
      {
        //������������ �����
        $ok[$i] = 1;

        //� ������� ������ � ���...
        $cell = getdata($Login, 'newchar', 'achar'.$i);

        //����� ����������� � ������� ������
        (int)$numb = cut($cell);

        //���� ��� �� �������, �� ������������ �����
        if (($cell[0] == 'E')&&($numb == $num))
        {
          //������������ � ������, �.�. ��� ��� ���������
          $ok[$i] = 0; 
        } // ���� �� �������
      } // ���������, ��� �� ����� �� ��� ���������

      //���� ���� ���� � ����� ������ ���������, �� ������
      $yes = 1;
      for ($i = 1; $i <= 16; $i++)
        if ($ok[$i] == 0)
        {
          $yes = 0;
          $num = 0;
        } // ���. ������
    } // ��� �� � � ������� ����� ����������� & != 0
  } // ���� �� ������� (����)

  //������� �����������
  return $num;
}

//�������� ������� �������������� �������������� �� ������ (���� ��� ���� � ������ ������)
function Level($Num, $Login)
{
  //���� �� � ��� ����� ��������������
  $finded = 0;
  for ($i = 1; $i <= 16; $i++)
  {
    $ability = getdata($Login, 'newchar', 'achar'.$i);
    $number = cut($ability);

    //���� ����������� �������, ���������� �����, ��������������� ������
    if ($number == $Num)
    {
      //����� �������
      switch($ability[0])
      {
        case 'N':
          $finded = 1;
          break;
        case 'A':
          $finded = 2;
          break;
        case 'E':
          $finded = 3;
          break;
      }
    }
  }

  //���� ������
  if ($finded != 0)
  {
    $Lvl = getfrom('num', $Num, 'additional', 'level'.$finded);
  }
  else
    $Lvl = 0;

  //���������� ������
  return $Lvl;
}

//�������� ���������� �� ������ ������
function CastName($num)
{
  //��������� ���������� ���������, ���� ��� �����
  switch($num)
  {
    case 109:
      $find = "���������� ����";
      break;
    case 110:
      $find = "���� ����";
      break;
    case 111:
      $find = "������";
      break;
    case 112:
      $find = "������������� ������";
      break;
    case 113:
      $find = "�������� �����";
      break;
    case 114:
      $find = "�������� ���";
      break;
    case 115:
      $find = "����������";
      break;
    case 116:
      $find = "�������";
      break;
    case 117:
      $find = "����������� ����";
      break;
    case 118:
      $find = "�������";
      break;
  }
  return $find;
}
?>