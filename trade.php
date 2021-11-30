<?

include "functions.php";
ban();

//Проверяем курс
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

//Если чел посылает сам себе, то фиг ему
if ($login == $userlist)
   {
   $err = 1;
   }

//Если всё нормально, то можно отсылать
if ($err == 0)
   {
   
   //Проверяем количество наличности
   $has = getdata($login, 'economic', $what);
   if ($how > $has)
      {
      $how = $has;
      }
   $dlt = $has - $how;

   //Снимаем с меня
   change($login, 'economic', $what, $dlt);
 
   //Добавляем ему
   change($userlist, 'economic', $what, $how+getdata($userlist, 'economic', $what));

   //Добавляем в ЛОГ
   if ($what == 'metal')
      {
      $w = 'металла.';
      }
   if ($what == 'rock')
      {
      $w = 'камня.';
      }
   if ($what == 'wood')
      {
      $w = 'дерева.';
      }

   $txt = getdata($login, 'hero', 'name')." послал Вам ".$how." единиц ".$w;
   intolog($userlist, 'trade', $txt);
   }

  moveto("trademark.php?login=".$login);
?>
