<?
  //Постройка города
  include "functions.php";

  //Если не указано имя пользователя, то выкинуть юзера нафиг
  $lg = trim($HTTP_COOKIE_VARS["nativeland"]);
  $pw = trim($HTTP_COOKIE_VARS["password"]);

  //Залогигнен?
  if (finduser($lg, $pw) != 1)
  	moveto('index.php');

  //Если игрок в битве, то перенаправляем его туда
  FromBattle($lg);
  ban();

  //Постройка здания
  function NewBuild($Number, $Login, $Type, $RegionX, $RegionY, $CoordX, $CoordY, $Info)
  {
    mysql_query("insert into mapbuild values('$Number', '$Login', '$Type', '$RegionX', '$RegionY', '$CoordX', '$CoordY', '$Info');");
    //Увеличиваем население
    if ($Type == 1)
    {
      $Peoples = getdata($Login, 'economic', 'peoples');
      $Peoples = $Peoples + 20;
      change($Login, 'economic', 'peoples', $Peoples);
    }
  }

  //Проверяем, в этом ли мы месте карты находимся?
  $mx = getdata($lg, 'coords', 'rx');
  $my = getdata($lg, 'coords', 'ry');
  if (($mx == $rx)&&($my == $ry))
  {
  }
  else
    moveto("map.php");  

  //Если это вода, то возвращаемся назад
  if ($tp == 4)
    moveto("map.php");

  //Если строители ещё на отдыхе
  $lastbuild = getdata($lg, 'temp', 'param');
  $delta = time()-$lastbuild;
  $delta = round($delta/3600);
  $hours = 24 - $delta;
  if ($hours < 0)
    $hours = 0;
  if ($delta < 24)
    messagebox("Дайте строителям отдохнуть ещё ".$hours." часов", "map.php");

  //Расчитываем цену на здание
  $curse     = getdata($lg, 'economic', 'curse');
  $moneyname = getdata($lg, 'economic', 'moneyname'); 
  switch($object)
  {
    case 0: //Город
      $money = 10000*$curse;
      $metal = 1000;
      $rock  = 2000;
      $wood  = 2000;
      $type = 1;
      break;
    case 1: //Металл
      $money = 7000*$curse;
      $metal = 4000;
      $rock  = 4000;
      $wood  = 500;
      $type = 2;
      break;
    case 2: //Камень
      $money = 7000*$curse;
      $metal = 6000;
      $rock  = 2500;
      $wood  = 1500;
      $type = 3;
      break;
    case 3: //Дерево
      $money = 5000*$curse;
      $metal = 2000;
      $rock  = 500;
      $wood  = 3500;
      $type = 4;
      break;
  }
  
  //А какие мы имеем ресурсы?
  $mymoney = getdata($lg, 'economic', 'money');
  $mymetal = getdata($lg, 'economic', 'metal');
  $myrock  = getdata($lg, 'economic', 'rock');
  $mywood  = getdata($lg, 'economic', 'wood');

  //Достаточно ли
  if ($mymoney < $money)
    messagebox("У Вас недостаточно ".$moneyname." для строительства", "map.php");
  if ($mymetal < $metal)
    messagebox("У Вас недостаточно металла для строительства", "map.php");
  if ($myrock  < $rock)
    messagebox("У Вас недостаточно камня для строительства", "map.php");
  if ($mywood  < $wood)
    messagebox("У Вас недостаточно дерева для строительства", "map.php");

  //Отнимаем ресурсы
  change($lg, 'economic', 'money', $mymoney - $money);
  change($lg, 'economic', 'metal', $mymetal - $metal);
  change($lg, 'economic', 'rock',  $myrock  - $rock);
  change($lg, 'economic', 'wood',  $mywood  - $wood);

  //Получаем индекс строительства и увеличиваем его на "1"
  $index = getfrom('admin', getadmin(), 'settings', 'f5');
  $index++;
  setto('admin', getadmin(), 'settings', 'f5', $index);

  //Строим здание
  newbuild($index, $lg, $type, $rx, $ry, $cx, $cy, $bname);

  //Отправляем строителей отдыхать
  change($lg, 'temp', 'param', time());
  
  //Перенаправляем на карту
  moveto('map.php');
?>