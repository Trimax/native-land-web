<?

include "functions.php";
ban();

//��������� ����
$err = 0;
if (!empty($how))
	{
	if (!preg_match("/[0-9]/i", $how))
		{
		$err = 1;
		}
	if ($how < 0)
           {
           $err = 1;
           }
	}

//���� ��� �������� ��� ����, �� ��� ���
if ($login == $userlist)
   {
   $err = 1;
   }

//���� �� ���������, �� ����� ��������
if ($err == 0)
   {
   
   //��������� ���������� ����������
   $has = getdata($login, 'economic', $what);
   if ($how > $has)
      {
      $how = $has;
      }
   $dlt = $has - $how;

   //������� � ����
   change($login, 'economic', $what, $dlt);
 
   //��������� ���
   change($userlist, 'economic', $what, $how+getdata($userlist, 'economic', $what));

   //��������� � ���
   if ($what == 'metal')
      {
      $w = '�������.';
      }
   if ($what == 'rock')
      {
      $w = '�����.';
      }
   if ($what == 'wood')
      {
      $w = '������.';
      }

   $txt = getdata($login, 'hero', 'name')." ������ ��� ".$how." ������ ".$w;
   intolog($userlist, 'trade', $txt);
   }

  moveto("trademark.php?login=".$login);
?>
