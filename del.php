<?
include "functions.php";
ban();

//������� ��?
if ($num == 0)
{
	//�������� ������ ���� ���������	
	$dir_rec= dir("data/mail/".$login);
	$i = 0;
	while ($entry = $dir_rec->read())
	{
	   if (substr($entry,0,3)=="rec")
		  {
	      $names[$i]=trim(substr($entry,4));
		  $name = "data/mail/".$login."/rec.".$names[$i];
		  unlink ($name);
	      $i++;
	      }
	   }
	$dir_rec->close();
	$count = $i;
	@rsort($names);
} else
{
	//������� ������
	$name = "data/mail/".$login."/rec.".$num;
	unlink ($name);
}

//���������������� �����
moveto("game.php?action=19");
?>