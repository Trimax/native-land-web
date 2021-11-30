<?
include "functions.php";
ban();

//Стереть всё?
if ($num == 0)
{
	//Получаем список всех сообщений	
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
	//Стираем запись
	$name = "data/mail/".$login."/rec.".$num;
	unlink ($name);
}

//Перенаправляемся назад
moveto("game.php?action=19");
?>