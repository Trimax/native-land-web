<?
  include "functions.php";

  //Безопастность
  $lg = trim($HTTP_COOKIE_VARS["nativeland"]);
  $pw = trim($HTTP_COOKIE_VARS["password"]);
  if ($lg != $login) 
    exit();

  //Залогигнен?
  if (finduser($lg, $pw) != 1)
  	moveto('index.php');

  //Из битвы
  FromBattle($lg);

  //Обработчик событий
  switch($action)
  {
    //Выбросить предмет
    case 1:
      PopItem($lg, $numb);
      break;

    //Одеть предмет
    case 2:
      //1) Определить тип предмета
      $place = getfrom('num', $numb, 'allitems', 'type');
  		if ($place == 1) {$field = 'golova';}
	  	if ($place == 2) {$field = 'shea';}
	  	if ($place == 3) {$field = 'telo';}
	  	if ($place == 4) {$field = 'tors';}
	  	if ($place == 5) {$field = 'palec';}
	  	if ($place == 6) {$field = 'leftruka';}
	  	if ($place == 7) {$field = 'rightruka';}
	  	if ($place == 8) {$field = 'nogi';}
	  	if ($place == 9) {$field = 'koleni';}
	  	if ($place == 10) {$field = 'plash';}
	  	if ($place == 11) {$field = 'rightruka';}

      //Меняем предметы местами
      $Was = getdata($lg, 'items', $field);
      change($lg, 'items', $field, $numb);
      change($lg, 'inventory', 'inv'.$cell, $Was);
      break;

  //Передать предмет
  case 3:
    if (PushItem($recepient, $numb) == 1)
      PopItem($lg, $numb);
    break;
  } //Конец switch

  //Куда возвращаемся
  moveto('game.php?action='.$Back);
?>