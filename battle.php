<?
//Фон и стиль и модули
include "functions.php";

//Время на ход (в секундах)
$TimeOut = 60;

//Если не указано имя пользователя, то выкинуть юзера нафиг
$lg = trim($HTTP_COOKIE_VARS["nativeland"]);
$pw = trim($HTTP_COOKIE_VARS["password"]);

//А есть ли такой пользователь
if (finduser($lg, $pw) == 0)
{
  setcookie("nativeland");
  setcookie("password");
	moveto("index.php");
}

ban();

//Приз победителю монстра
function Price($lg, $Opp)
{
  //Обнуляем переменную
  $Msg = "";

  //Монстр ли это
  $Mnstr = getdata($Opp, 'city', 'build14');

  //Если битва была с монстром
  if ($Mnstr == 1)
  {
    //Добавляем ресурсы
    $Res = rand(1, 3);
    $Mn  = rand(10, 100);
    $Tp  = rand(1, 3);
      
    //Название денег
    $Valute = getdata($lg, 'economic', 'moneyname');

    //Курс денег
    $Curse = getdata($lg, 'economic', 'curse');
    $Mn = $Mn * $Curse;

    //Заполняем сообщение
    $Msg = "Вы получили ".$Res;

    //Добавить металл
    if ($Tp == 1)
    {
      change($lg, 'economic', 'metal', getdata($lg, 'economic', 'metal') + $Res);
      $Msg = $Msg." металла и ".$Mn;
    }

    //Добавить камня
    if ($Tp == 2)
    {
      change($lg, 'economic', 'rock', getdata($lg, 'economic', 'rock') + $Res);
      $Msg = $Msg." камня и ".$Mn;
    }

    //Добавить дерева
    if ($Tp == 3)
    {
      change($lg, 'economic', 'wood', getdata($lg, 'economic', 'wood') + $Res);
      $Msg = $Msg." дерева и ".$Mn;
    }

    //Добавляем название денег
    $Msg = $Msg.$Valute;

    //Получаем бутылки, которые есть у монстра
    $H[0] = getdata($Opp, 'bottles', 'hmaxi');
    $H[1] = getdata($Opp, 'bottles', 'hmedi');
    $H[2] = getdata($Opp, 'bottles', 'hmini');
    $M[0] = getdata($Opp, 'bottles', 'mmaxi');
    $M[1] = getdata($Opp, 'bottles', 'mmedi');
    $M[2] = getdata($Opp, 'bottles', 'mmini');

    //Отдаём все бутылки игроку
    $Items = "<br>";
    if ($H[0] != 0)
      $Items = $Items."<img src='images\bottles\big_h.jpg'>";
    if ($H[1] != 0)
      $Items = $Items."<img src='images\bottles\med_h.jpg'>";
    if ($H[2] != 0)
      $Items = $Items."<img src='images\bottles\sma_h.jpg'>";
    if ($M[0] != 0)
      $Items = $Items."<img src='images\bottles\big_m.jpg'>";
    if ($M[1] != 0)
      $Items = $Items."<img src='images\bottles\med_m.jpg'>";
    if ($M[2] != 0)
      $Items = $Items."<img src='images\bottles\sma_m.jpg'>";

    //Перекидываем в базе
    $H[0] = $H[0] + getdata($lg, 'bottles', 'hmaxi');
    $H[1] = $H[1] + getdata($lg, 'bottles', 'hmedi');
    $H[2] = $H[2] + getdata($lg, 'bottles', 'hmini');
    $M[0] = $M[0] + getdata($lg, 'bottles', 'mmaxi');
    $M[1] = $M[1] + getdata($lg, 'bottles', 'mmedi');
    $M[2] = $M[2] + getdata($lg, 'bottles', 'mmini');
    change($lg, 'bottles', 'hmaxi', $H[0]);
    change($lg, 'bottles', 'hmedi', $H[1]);
    change($lg, 'bottles', 'hmini', $H[2]);
    change($lg, 'bottles', 'mmaxi', $M[1]);
    change($lg, 'bottles', 'mmedi', $M[2]);
    change($lg, 'bottles', 'mmini', $M[3]);

    //Перекидываем вещи
    $Items = $Items."<br>";

    //Получаем номера вещей
    $Num1 = getdata($Opp, 'items', 'rightruka');
    $Num2 = getdata($Opp, 'items', 'leftruka');

    //Добавляем вещи в инвентарь
    PushItem($lg, $Num1);
    PushItem($lg, $Num2);

    //Получаем картинки вещей
    $Img1 = getfrom('num', $Num1, 'allitems', 'img');
    $Img2 = getfrom('num', $Num2, 'allitems', 'img');

    //Дополняем сообщение
    $Items = $Items."<img src='images/weapons/$Img1'><img src='images/weapons/$Img2'>";

    //Дополняем сообщение
    $Msg = $Msg.$Items;
  } //Битва была с монстром

  //Возврат строки
  return $Msg;
}

//Заголовок
echo ("<html>\n<head>\n<link rel='stylesheet' type='text/css' href='style.css'/>\n<title>Native Land</title>\n</head>\n<body background='images\back.jpe'>\n");
echo ("<META HTTP-EQUIV='REFRESH' CONTENT=6>\n");

//Автоотруб
change ($lg, 'status', 'online', '1');
change ($lg, 'inf', 'fld3', time());

//Сначала необходимо проверить, а в битве ли игрок?
$battle = getdata($lg, 'battle', 'battle');
if ($battle == 0)
{
  $Winner = getdata($lg, 'hero', 'health');
  if ($Winner > 0)
    $Winner = 1;
  if ($Winner == 1)
    messagebox("В этом поединке Вы победили.<br>".$Finish, "game.php?action=1");
  else
    messagebox("В этом поединке Вы проиграли.", "game.php?action=1");
}

//Добавить строчку в ЛОГ файл
function ToLog($Num, $String)
{
  //Читаем файл
  $file = fopen("data/logs/".$Num.".log", "r");
  for ($i = 0; $i < 10; $i++)
    $Str[$i+1] = trim(fgets($file, 255));
  fclose($file);
  
  //Текущее время
  $tm = "<font color=black><b>(".date("<b>H:i:s</b>", time()).")</b></font>";

  //Вписываем в начало строчку
  $Str[0] = $String." ".$tm."<br>";

  //Сохраняем файл
  $file = fopen("data/logs/".$Num.".log", "w");
  for ($i = 0; $i < 10; $i++)
    fputs($file, $Str[$i]."\n");
  fclose($file);
}

//Какое заклинание самое ущербное
function MaxiCast($Login)
{
  $max = 0;
  $num = getdata($Login, 'magic', 'cast1');
  $eff = getfrom('num', $num, 'allcasts', 'effect');
  for ($i = 2; $i <= 6; $i++)
  {
    $num = getdata($Login, 'magic', 'cast'.$i);
    $tmp = getfrom('num', $num, 'allcasts', 'effect');
    if ($tmp > $eff)
    {
      $eff = $tmp;
      $max = $num;
    }
  }

  //Возвращаем номер заклинания! (Для ускорения работы)
  return $max;
}

//Расчёт атаки
function BladeStrike($Login)
{
  //Базовый урон - сила игрока
  $Damage = getdata($Login, 'abilities', 'power');

  //Номер оружия в руке
  (int)$Num = getdata($Login, 'items', 'rightruka');

  //Дополнительный урон (от оружия в руке)
  $Procent = getfrom('num', $Num, 'allitems', 'effect');

  //Расчёт дополнительного урона
  $AddOn = ($Damage / 100) * $Procent;

  //Дополнительный урон, если выпита баночка
  $Bottle = getdata($Login, 'items', 'vrukah');
  switch($Bottle)
  {
    case 1:
      $Damage = 2*$Damage;
      change($Login, 'items', 'vrukah', '0');
      break;
    case 2:
      $Damage = 1.5*$Damage;
      change($Login, 'items', 'vrukah', '0');
      break;
    case 3:
      $Damage = 1.25*$Damage;
      change($Login, 'items', 'vrukah', '0');
      break;
  }

  //Увеличиваем урон от доп. способностей
    //Бой
    $Battle = $Damage*Level(4, $Login)/100;

  //Пересчитываем конечные повреждения
  $Damage = round($Damage + $AddOn + $Battle);

  //Возвращаем значение урона
  return $Damage;
}

//Расчёт атаки посохом
function PosohStrike($Login)
{
  //Базовый урон - сила игрока
  $Damage = getdata($Login, 'abilities', 'naturemagic');

  //Номер оружия в руке
  (int)$Num = getdata($Login, 'items', 'rightruka');

  //Дополнительный урон (от оружия в руке)
  $Procent = getfrom('num', $Num, 'allitems', 'effect');

  //Расчёт дополнительного урона
  $AddOn = ($Damage / 100) * $Procent;

  //Пересчитываем конечные повреждения
  $Damage = round($Damage + $AddOn + $Battle);

  //Возвращаем значение урона
  return $Damage;
}

//Расчёт защиты
function ShieldProtection($Login)
{
  //Базовый урон - сила игрока
  $Damage = getdata($Login, 'abilities', 'protect');

  //Защита от щита
    //Номер оружия в левой руке
    (int)$Num = getdata($Login, 'items', 'leftruka');

    //Если конечно прибавка к защите
    $Verb = getfrom('num', $Num, 'allitems', 'action');
    $AddOn = 0;
    if ($Verb == 2)
    {
      //Дополнительный урон (от оружия в руке)
      $Procent = getfrom('num', $Num, 'allitems', 'effect');

      //Расчёт дополнительного урона
      $AddOn = ($Damage / 100) * $Procent;
    }

  //Защита от брони
    //Номер оружия на теле
    (int)$Num = getdata($Login, 'items', 'telo');

    //Если конечно прибавка к защите
    $Verb = getfrom('num', $Num, 'allitems', 'action');
    $Armor = 0;
    if ($Verb == 2)
    {
      //Дополнительный урон (от оружия в руке)
      $Procent = getfrom('num', $Num, 'allitems', 'effect');

      //Расчёт дополнительного урона
      $Armor = ($Damage / 100) * $Procent;
    }

  //Защита от шлема
    //Номер оружия на голове
    (int)$Num = getdata($Login, 'items', 'golova');

    //Если конечно прибавка к защите
    $Verb = getfrom('num', $Num, 'allitems', 'action');
    $Head = 0;
    if ($Verb == 2)
    {
      //Дополнительный урон (от оружия в руке)
      $Procent = getfrom('num', $Num, 'allitems', 'effect');

      //Расчёт дополнительного урона
      $Head = ($Damage / 100) * $Procent;
    }

  //Защита от пояса
    //Номер оружия на теле
    (int)$Num = getdata($Login, 'items', 'tors');

    //Если конечно прибавка к защите
    $Verb = getfrom('num', $Num, 'allitems', 'action');
    $Tors = 0;
    if ($Verb == 2)
    {
      //Дополнительный урон (от оружия в руке)
      $Procent = getfrom('num', $Num, 'allitems', 'effect');

      //Расчёт дополнительного урона
      $Tors = ($Damage / 100) * $Procent;
    }

  //Защита от щитков на коленях
    //Номер оружия на коленях
    (int)$Num = getdata($Login, 'items', 'koleni');

    //Если конечно прибавка к защите
    $Verb = getfrom('num', $Num, 'allitems', 'action');
    $Koleni = 0;
    if ($Verb == 2)
    {
      //Дополнительный урон (от оружия в руке)
      $Procent = getfrom('num', $Num, 'allitems', 'effect');

      //Расчёт дополнительного урона
      $Koleni = ($Damage / 100) * $Procent;
    }

  //Защита от ног
    //Номер оружия на ногах
    (int)$Num = getdata($Login, 'items', 'nogi');

    //Если конечно прибавка к защите
    $Verb = getfrom('num', $Num, 'allitems', 'action');
    $Nogi = 0;
    if ($Verb == 2)
    {
      //Дополнительный урон (от оружия в руке)
      $Procent = getfrom('num', $Num, 'allitems', 'effect');

      //Расчёт дополнительного урона
      $Nogi = ($Damage / 100) * $Procent;
    }

  //Защита от плаща
    //Номер оружия на теле
    (int)$Num = getdata($Login, 'items', 'plash');

    //Если конечно прибавка к защите
    $Verb = getfrom('num', $Num, 'allitems', 'action');
    $Plash = 0;
    if ($Verb == 2)
    {
      //Дополнительный урон (от оружия в руке)
      $Procent = getfrom('num', $Num, 'allitems', 'effect');

      //Расчёт дополнительного урона
      $Plash = ($Damage / 100) * $Procent;
    }

  //Пересчитываем конечные повреждения
  $Damage = $Damage + $Armor + $Tors + $Head + $Koleni + $Nogi + $Plash;

  //Если нажата кнопка прикрыться
  $Shield = getdata($Login, 'battle', 'attack');
  if ($Shield == 1)
  {
    change($Login, 'battle', 'attack', '0');
    $Damage = $Damage + $AddOn;
  }

  //Если мораль низкая
  if ($Shield < 0)
  {
    change($Login, 'battle', 'attack', '0');
    $Damage = $Damage + $Shield;
    if ($Damage < 0)
      $Damage = 0;
  }

  //Возвращаем значение урона
  return $Damage;
}

//Увеличение опыта
function ItExpa($Login)
{
  //Получаем комбинацию
  $Combo = getdata($Login, 'battle', 'data');
  $Code = "";

  //Конвертируем комбинацию в SHORT код
  $pos = 0;
  $Code = $Combo[0];
  for ($i = 1; $i < strlen($Combo); $i++)
  {
    //Если две буквы подряд не повторяются...
    if ($Code[$pos] != $Combo[$i])
    {
      $pos++;
      $Code[$pos] = $Combo[$i];
    }
  }

  //Начисляем экспу за удары
  $TurnExpa = 0;
  for ($i = 0; $i < strlen($Code)+1; $i++)
  {
    //Что за удар?
    switch($Code[$i])
    {
      //Удар
      case 'A':
        $TurnExpa++;
        $TurnExpa++;
        break;

      //Защита
      case 'D':
        $TurnExpa++;
        break;

      //Магия
      case 'M':
        $TurnExpa++;
        $TurnExpa++;
        break;

      //Клановый удар
      case 'C':
        $TurnExpa++;
        $TurnExpa++;
        $TurnExpa++;
        break;
    } // конец выбора
  } // конец цикла

  //Начисляем экспу
  $Exp = getdata($Login, 'battle', 'health');
  change($Login, 'battle', 'health', $TurnExpa + $Exp);

  //Обнуляем ход
  change($Login, 'battle', 'data', '');
}

//Передача хода
function TurnStep($Login, $Opp)
{
  //Начисляем очков действия
  $hp = getdata($Login, 'battle', 'value');
  $Dex = 6 + Level(3, $Login);
  change($Login, 'battle', 'value', $hp+$Dex);

  //Записываем момент передачи хода
  change($Login, 'battle', 'timeout', time());
  change($Opp, 'battle', 'timeout', time());

  //Наконец меняем Логины игроков
  change($Login, 'battle', 'turn', $Opp);
  change($Opp, 'battle', 'turn', $Opp);

  //Узнаём имена героев
  $me = getdata($Login, 'hero', 'name');
  $he = getdata($Opp, 'hero', 'name');

  //Узнаём номер боя
  $Num = getdata($Login, 'battle', 'info');

  //Восстановление здоровья
  $How = Level(5, $Login);
  if ($How != 0)
  {
    $Health = getdata($Login, 'hero', 'health');
    $Level = getdata($Login, 'hero', 'level');
    $AddHealth = $Level*$How;
    $Health = $Health + $AddHealth;
    if ($Health > $Level*100)
      $Health = $Level*100;

    //Добавляем строчку про здоровье
    ToLog($Num, "<font color=yellow><b>".$me."</b></font> восстанавливает себе <font color=green>".$AddHealth."</font> очков здоровья</font>");

    //Убираем действие бутылки
    change($Login, 'abilities', 'magicpower', '0');
    change($Opp, 'abilities', 'magicpower', '0');

    //Меняем параметр
    change($Login, 'hero', 'health', $Health);

    //Обновляем экран
    moveto("battle.php");
  }

  //Восстановление маны
  $How = Level(7, $Login);
  if ($How != 0)
  {
    $Health = getdata($Login, 'abilities', 'intellegence');
    $Level = getdata($Login, 'abilities', 'cnowledge');
    $AddHealth = round($Level*$How/10);
    $Health = $Health + $AddHealth;
    if ($Health > $Level*10)
      $Health = $Level*10;

    //Добавляем строчку про ману
    ToLog($Num, "<font color=yellow><b>".$me."</b></font> восстанавливает себе <font color=green>".$AddHealth."</font> очков маны</font>");

    //Меняем параметр
    change($Login, 'abilities', 'intellegence', $Health);
  }

  //Записываем в ЛОГ
  ToLog($Num, "<font color=yellow><b>".$me."</b></font> передаёт ход <font color=yellow><b>".$he."</b></font>");

  //Прибавляем опыт за прошедший ход
  ItExpa($Login);

  //Обновляем экран
  moveto("battle.php");
}

//Исполнение заклинания
function DoCast($Login, $Number, $Type)
{
  //Какая там атака
  $Damage = getdata($Login, 'abilities', 'naturemagic');

  //Атака от заклинания
  $Cast = getfrom('num', $Number, 'allcasts', 'effect'); 
  $AddOn = ($Damage / 100) * $Cast;

  //Увеличение
  $AddOn = $AddOn*1.7;

  //Дополнительный урон, если выпита баночка
  $Bottle = getdata($Login, 'abilities', 'magicpower');
  switch($Bottle)
  {
    case 1:
      $Damage = 2*$Damage;
      break;
    case 2:
      $Damage = 1.5*$Damage;
      break;
    case 3:
      $Damage = 1.25*$Damage;
      break;
  }

  //Доп. способности
  switch($Type)
  {
    case 1:
      $Damage = $Damage + Level(17, $Login)*$Damage/100;
      break;
    case 2:
      $Damage = $Damage + Level(16, $Login)*$Damage/100;
      break;
    case 3:
      $Damage = $Damage + Level(18, $Login)*$Damage/100;
      break;
  }

  //Полная атака
  $Damage = round($Damage + $AddOn);

  //Возвращаем урон
  return $Damage;
}

//Использование магии
function UseCast($Login, $Opp, $Damage, $Verb)
{
  //Антимагия
  $Interval = rand(100, 1);
  $Dxt = Level(13, $Opp);
  if (($Interval < $Dxt)&&($Verb != 2))
    $Verb = 0;

  //Что делает заклинание?
  switch($Verb)
  {
    //Антимагия
    case 0:
      $What = "Антимагия";
      break;
    //Обычная атака
    case 1:
      //Строка для ЛОГ файла
      $What = "наносит вред ".$Damage;
      
      //Здоровье оппонента
      $NewHealth = getdata($Opp, 'hero', 'health');
      $NewHealth = $NewHealth - $Damage;
      if ($NewHealth < 0)
        $NewHealth = 0;

      //Делаем его
      change($Opp, 'hero', 'health', $NewHealth);
      break;

    //Лечение
    case 2:
      //Строка для ЛОГ файла
      $What = "восстанавливает себе ".$Damage." здоровья";
      
      //Здоровье оппонента
      $NewHealth = getdata($Login, 'hero', 'health');
      $NewHealth = $NewHealth + $Damage;

      //Делаем его
      change($Login, 'hero', 'health', $NewHealth);
      break;

    //Высосать жизнь
    case 3:
      //Строка для ЛОГ файла
      $Damage = $Damage / 2;
      $What = "высасывает ".$Damage." жизни из оппонента";
      
      //Здоровье оппонента
      $NewHealth = getdata($Opp, 'hero', 'health');
      $NewHealth = $NewHealth - $Damage;
      if ($NewHealth < 0)
        $NewHealth = 0;

      //Моё здоровье
      $MyHealth = getdata($Login, 'hero', 'health');
      if ($NewHealth > 0)
        $MyNewHealth = $MyHealth + $Damage;
      else
        $MyNewHealth = $MyHealth;

      //Но не более, чем можно
      $Lvl = getdata($Login, 'hero', 'level');
      if ($MyNewHealth > $Lvl*100)
        $MyNewHealth = $Lvl*100;

      //Делаем его
      change($Opp, 'hero', 'health', $NewHealth);
      change($Login, 'hero', 'health', $MyNewHealth);
      break;

    //Повреждения обоим
    case 4:
      //Строка для ЛОГ файла
      $What = "наносит всем вред ".$Damage;
      
      //Здоровье оппонента
      $NewHealth = getdata($Opp, 'hero', 'health');
      $NewHealth = $NewHealth - $Damage;
      if ($NewHealth < 0)
        $NewHealth = 0;

      //Делаем его
      change($Opp, 'hero', 'health', $NewHealth);

      //Моё здоровье
      $NewHealth = getdata($Login, 'hero', 'health');
      $NewHealth = $NewHealth - $Damage;
      if ($NewHealth < 0)
        $NewHealth = 0;

      //Делаем его
      change($Login, 'hero', 'health', $NewHealth);
      break;

    //Снижение ОД на 1
    case 5:
      //Строка для ЛОГ файла
      $Damage = 1;
      $What = "снижает очки действия на ".$Damage;
      
      //Здоровье оппонента
      $NewHealth = getdata($Opp, 'battle', 'value');
      $NewHealth = $NewHealth - $Damage;
      if ($NewHealth < 0)
        $NewHealth = 0;

      //Делаем его
      change($Opp, 'battle', 'value', $NewHealth);
      break;

    //Снижение ОД на 2
    case 6:
      //Строка для ЛОГ файла
      $Damage = 2;
      $What = "снижает очки действия на ".$Damage;
      
      //Здоровье оппонента
      $NewHealth = getdata($Opp, 'battle', 'value');
      $NewHealth = $NewHealth - $Damage;
      if ($NewHealth < 0)
        $NewHealth = 0;

      //Делаем его
      change($Opp, 'battle', 'value', $NewHealth);
      break;

    //Проклятья
    case 7:
      //Строка для ЛОГ файла
      $What = "снижает защиту оппонента на ".$Damage;
      
      //Делаем его
      change($Opp, 'battle', 'attack', (-1)*$Damage);
      break;

    //Высасывание маны врага
    case 8:
      //Строка для ЛОГ файла
      $Damage = $Damage / 2;
      $What = "высасывает ".$Damage." маны оппонента";
      
      //Мана оппонента
      $NewHealth = getdata($Opp, 'abilities', 'intellegence');
      $NewHealth = $NewHealth - $Damage;
      if ($NewHealth < 0)
        $NewHealth = 0;

      //Моя мана
      $MyHealth = getdata($Login, 'abilities', 'intellegence');
      if ($NewHealth > 0)
        $MyNewHealth = $MyHealth + $Damage;
      else
        $MyNewHealth = $MyHealth;

      //Делаем его
      change($Opp, 'abilities', 'intellegence', $NewHealth);
      change($Login, 'abilities', 'intellegence', $MyNewHealth);
      break;

    //Уменьшение маны оппонента
    case 9:
      //Строка для ЛОГ файла
      $What = "уменьшает количество маны на ".$Damage;
      
      //Здоровье оппонента
      $NewHealth = getdata($Opp, 'abilities', 'intellegence');
      $NewHealth = $NewHealth - $Damage;
      if ($NewHealth < 0)
        $NewHealth = 0;

      //Делаем его
      change($Opp, 'abilities', 'intellegence', $NewHealth);
      break;
  }

  //Возвращаем результат
  return $What;
}

//Проверка на победу / поражение
function OffBattle($Login, $Opp)
{
  //Отключаем битву
  change($Login, 'battle', 'battle', '0');
  change($Opp, 'battle', 'battle', '0');

  //А с кем мы дрались
  $Monster = getdata($Opp, 'city', 'build14');

  //Сбрасываем все данные на 0
  change($Login, 'battle', 'health', '0');
  change($Opp, 'battle', 'health', '0');
  change($Login, 'battle', 'opponent', '0');
  change($Opp, 'battle', 'opponent', '0');
  change($Login, 'battle', 'turn', '0');
  change($Opp, 'battle', 'turn', '0');
  change($Login, 'battle', 'attack', '0');
  change($Opp, 'battle', 'attack', '0');
  change($Login, 'battle', 'data', '');
  change($Opp, 'battle', 'data', '');
  change($Login, 'battle', 'value', '0');
  change($Opp, 'battle', 'value', '0');
  change($Login, 'battle', 'info', '0');
  change($Opp, 'battle', 'info', '0');
  change($Login, 'battle', 'timeout', '0');
  change($Opp, 'battle', 'timeout', '0');

  //Скидываем проигравшего в свой город (а кто проиграл?)
  $mh = getdata($Login, 'hero', 'health');
  if ($mh <= 0)
  {
    $Loser = $Login;
    $Winner = $Opp;
  }
  $op = getdata($Opp, 'hero', 'health');
  if ($op <= 0)
  {
    $Loser = $Opp;
    $Winner = $Login;
  }

  //Сброс проигравшего
    //Определяем координаты столицы
    $rx = getdata($Loser, 'capital', 'rx');
    $ry = getdata($Loser, 'capital', 'ry');
    $cx = getdata($Loser, 'capital', 'x');
    $cy = getdata($Loser, 'capital', 'y');

    //Сбрасываем туда игрока
    change($Loser, 'coords', 'rx', $rx);
    change($Loser, 'coords', 'ry', $ry);
    change($Loser, 'coords', 'x',  $cx);
    change($Loser, 'coords', 'y',  $cy);

    //Сообщаем об этом проигравшему (если это не монстр конечно)
    $HName = getdata($Winner, 'hero', 'name');
    if (($Loser == $Opp)&&($Monster == 1))
    {
      //В этом случае посылать мессагу не надо!
    }
    else
      sms($Loser, "Священник", "После поединка с ".$HName." Вы некоторое время пребывали в беспамятстве. Очнувшись, Вы обнаружили себя в своём родном городе");

  //Если мы выиграли, удаляем монстра с карты
  if ($Winner == $Login)
  {
    //Получаем данные
    $Mx  = getdata($Opp, 'users', 'surname');
    $My  = getdata($Opp, 'users', 'name');
    $MRx = getdata($Opp, 'users', 'city');
    $MRy = getdata($Opp, 'users', 'country');

    //Удаляем монстра
  	mysql_query("delete from random where x = '".$Mx."' and y = '".$My."' and rx = '".$MRx."' and ry = '".$MRy."';");
  }
  
  //Удаляем монстра из базы
  $Pr = "";
  if ($Monster == 1)
  {
    //Если игрок выиграл
    if ($op <= 0)
      $Pr = Price($Login, $Opp);

    //Убираем монстра из базы
    kickuser($Opp);
  }

  //Возврат сообщения
  return $Pr;
}

//Капитуляция
function Capitulation($Login, $Opp)
{
  //Переводим опыт только в случае выигрыша
  $Expa = getdata($Opp, 'hero', 'expa');
  $Exp = getdata($Opp, 'battle', 'health');
  change($Opp, 'hero', 'expa', $Expa + $Exp);

  //Отключение битвы
  $Finish = OffBattle($Login, $Opp);
}

//Задаём константы
  //Сколько надо на удар мечом (в будущем от меча зависит) (max = ловкость)
  $blade_need = 3; 
  //Сколько надо на магию (от заклинания) (max = интеллект)
  $magic_need = 3;
  //Сколько надо на защиту (от щита) (max = ловкость)
  $protect_need = 2;
  //Сколько надо на бутылку здоровья
  $hbottle_need = 12;
  //Сколько надо на бутылку
  $bottle_need = 2;

//Получение данных из БД
  //Логин оппонента
  $opp = getdata($lg, 'battle', 'opponent');
  //Имя моего героя
  $me = getdata($lg, 'hero', 'name');
  //Имя героя оппонента
  $opponent = getdata($opp, 'hero', 'name');
  //Моё фото
  $myphoto = getdata($lg, 'inf', 'fld1');
  //Фото оппонента
  $opphoto = getdata($opp, 'inf', 'fld1');
  //Кто есть оппонент. Монстр?
  $opwho   = getdata($opp, 'city', 'build14');
  //Читаем ЛОГ битвы
  $info = "Лог битвы читать сюда";
  //Моё здоровье
  $myhealth = getdata($lg, 'hero', 'health');
  //Здоровье оппонента
  $ophealth = getdata($opp, 'hero', 'health');
  //Сколько секунд осталось
  $sec = getdata($lg, 'battle', 'timeout');
  //Передача хода
  $timeout = "До передачи хода осталось ".$sec." секунд";
  //Номер поединка
  $log = getdata($lg, 'battle', 'info');
  //ЛОГ файл поединка
  $logfile = "data/logs/".$log.".log";
  //Чей ход
  $turn = getdata($lg, 'battle', 'turn');
  //Информация о ходе человека
  $plturn = "Сейчас ходит: <font color=darkblue><b>".getdata($turn, 'hero', 'name')."</font></b>";
  //Очки действия
  $hp = getdata($lg, 'battle', 'value');
  //Очки действия оппонента
  $ophp = getdata($opp, 'battle', 'value');
  //Мой уровень
  $mylevel = getdata($lg, 'hero', 'level');
  //Уровень оппонента
  $oplevel = getdata($opp, 'hero', 'level');
  //Моя защита от магии
  $MagicProt = getdata($lg, 'abilities', 'dexterity');
  //Защита от магии оппонента
  $opMagicProt = getdata($opp, 'abilities', 'dexterity');
  //Метка времени
  $LastTime = getdata($lg, 'battle', 'timeout');
  //Моя мана
  $mana = getdata($lg, 'abilities', 'intellegence');
  //Мана оппонента
  $opmana = getdata($opp, 'abilities', 'intellegence');
  //Мой ход
  $info = getdata($lg, 'battle', 'data');
  //Знания
  $cnow = getdata($lg, 'abilities', 'cnowledge');
  //Знания оппонента
  $opcnow = getdata($opp, 'abilities', 'cnowledge');
  //Заработанный опыт
  $mynewexpa = getdata($lg, 'battle', 'health');
  //Заработанный опыт оппонентом
  $opnewexpa = getdata($opp, 'battle', 'health');
  //Бутылочки
    //Здоровье
    (int)$HMax = getdata($lg, 'bottles', 'hmaxi');
    (int)$HMed = getdata($lg, 'bottles', 'hmedi');
    (int)$HMin = getdata($lg, 'bottles', 'hmini');
    //Мана
    (int)$MMax = getdata($lg, 'bottles', 'mmaxi');
    (int)$MMed = getdata($lg, 'bottles', 'mmedi');
    (int)$MMin = getdata($lg, 'bottles', 'mmini');
    //Сила
    (int)$PMax = getdata($lg, 'bottles', 'pmaxi');
    (int)$PMed = getdata($lg, 'bottles', 'pmedi');
    (int)$PMin = getdata($lg, 'bottles', 'pmini');
    //Заклинания
    (int)$SMax = getdata($lg, 'bottles', 'smaxi');
    (int)$SMed = getdata($lg, 'bottles', 'smedi');
    (int)$SMin = getdata($lg, 'bottles', 'smini');
  //On-Line ли нащ оппонент
  $online = getdata($opp, 'status', 'online');

//Если оппонент - монстр, то фотография другая
if ($opwho == 1)
{
  $mnstr    = getdata($opp, 'hero', 'name');
  $pht      = getfrom('name', $mnstr, 'monsters', 'art');
  $opphoto = "monsters/".$pht;
}

//Если нет картинки, добавляем .JPG
if ($myphoto == '0')
  $myphoto = $myphoto.".jpg";
if ($opphoto == '0')
  $opphoto = $opphoto.".jpg";

//Тест на превышение маны
if ($mana > ($cnow*10))
{
  $mana = $cnow*10;
  change($lg, 'abilities', 'intellegence', $mana);
}

//Если мы проиграли или победили
if (($myhealth <= 0)||($ophealth <= 0))
{
  //Победитель ли?
  $Winner = 0;

  //Переводим опыт только в случае выигрыша
  $Expa = getdata($lg, 'hero', 'expa');
  $Exp = getdata($lg, 'battle', 'health');
  if ($myhealth > 0)
  {
    //Начисляем опыт
    change($lg, 'hero', 'expa', $Expa + $Exp);

    //Победитель
    $Winner = 1;

    //Если есть способность вор, то крадём часть денег
    $Rob = Level(11, $lg);
    $Rnd = rand(100, 0);

    //Если удалось денжат украсть...
    if ($Rnd < $Rob)
    {
      //Прибавляем денег
      $Money = getdata($opp, 'economic', 'money');
      $Money = $Money - $Rnd;
      if ($Money < 0)
        $Money = 0;

      //Убираем эти деньги у оппонента
      change($opp, 'economic', 'money', $Money);

      $Monster = getdata($opp, 'city', 'build14');

      //Сообщаем ему об этом
      if ($Monster != 1)
        sms($opp, $lg, "После битвы Ваш оппонент Вас обокрал. Он украл у Вас ".$Rnd." денег");

      //Прибавляем эти деньги нам
      $Curse = getdata($opp, 'economic', 'curse');
      if ($Curse == 0)
        $Curse = 1;
      $Rnd = $Rnd / $Curse;
      $Curse = getdata($lg, 'economic', 'curse');
      $Money = round($Rnd*$Curse);

      //Прибавляем нам денег
      $Our = getdata($lg, 'economic', 'money');
      change($lg, 'economic', 'money', $Our + $Money);
    }
  }
  else
  {
    $Expa = getdata($opp, 'hero', 'expa');
    $Exp = getdata($opp, 'battle', 'health');
    change($opp, 'hero', 'expa', $Expa + $Exp);
  }

  //Вырубаем битву
  $Finish = OffBattle($lg, $opp);

  //Обновляем страницу
  if ($Winner == 1)
    messagebox("В этом поединке Вы победили.<br>".$Finish, "game.php?action=1");
  else
    messagebox("В этом поединке Вы проиграли.", "game.php?action=1");
}

//Если кончились очки действия, то передаём ход оппоненту
if ($hp <= 0)
  TurnStep($lg, $opp);

//Сколько прошло времени с 
$Delta = time() - $LastTime;

//Сколько осталось до передачи хода
$CountDown = $TimeOut - $Delta;

//Если вышло время хода, то передаём ход оппоненту
if ($Delta >= $TimeOut)
  if ($lg == $turn)
    TurnStep($lg, $opp);
    else
    TurnStep($opp, $lg);

//Автобой, в случае, если оппонент не on-line
if (($turn == $opp)&&($online == 0))
{
  //Колдовская сила оппонента и его атака
  $omp = getdata($opp, 'abilities', 'naturemagic');
  $obl = getdata($opp, 'abilities', 'power');

  //Сначала определяем чем бить (магия или меч)
  $type = 1; //Изначально стоит меч
  if ($obl > $omp)
    $type = 1; //Всёже мечом
  else
    $type = 2; //Иначе магия

  //Если нет заклинаний, то меняем на атаку руками
  $MCast = MaxiCast($opp);
  if ($MCast == 0)
    $type = 1;

  //Лечимся, если здоровья менее 30% и есть бутылки
    //Переводим здоровье оппонента в проценты
    $hprc = $ophealth/$oplevel;
  //Здоровья менее 30%
  if ($hprc < 30)
  {
    //А есть ли бутылки
    (int)$OHMax = getdata($opp, 'bottles', 'hmaxi');
    (int)$OHMed = getdata($opp, 'bottles', 'hmedi');
    (int)$OHMin = getdata($opp, 'bottles', 'hmini');

    //Есть ли бутылки
    $btl = 0;
    if (($OHMax != 0)||($OHMed != 0)||($OHMin != 0))
      $btl = 1;

    //Если есть хоть одна бутылка
    if ($btl != 0)
    {
      //Если хватает ОД
      if ($ophp >= 12)
      {
        //Снимаем ОД
        $ophp = $ophp - 12;
        change($opp, 'battle', 'value', $ophp);

        //Лечимся
        $ready = 0;
        if ($OHMax != 0)
        {
          change($opp, 'hero', 'health', ($oplevel*100));
          ChangeWeight($opp, -3);
          ToLog($log, "<font color=yellow><b>".$opp."</b></font> использует большое зелье лечения");
          $ready = 1;
        } //Большое зелье
        if (($OHMed != 0)&&($ready == 0))
        {
          $ophealth = $ophealth + $oplevel*50;
          if ($ophealth > ($oplevel*100))
            $ophealth = ($oplevel*100);
          change($opp, 'hero', 'health', $ophealth);
          ChangeWeight($opp, -2);
          ToLog($log, "<font color=yellow><b>".$opp."</b></font> использует среднее зелье лечения");
          $ready = 1;
        } //Среднее зелье
        if (($OHMin != 0)&&($ready == 0))
        {
          $ophealth = $ophealth + $oplevel*25;
          if ($ophealth > ($oplevel*100))
            $ophealth = ($oplevel*100);
          change($opp, 'hero', 'health', $ophealth);
          ChangeWeight($opp, -1);
          ToLog($log, "<font color=yellow><b>".$opp."</b></font> использует малое зелье лечения");
          $ready = 1;
        } //Маленькое зелье
      } //Хватает ли ОД
    } //Есть ли хоть одна бутылка
  } // Если менее 30%

  //М.б. лучше пропустить ход
  $skip = 0;
  if (($btl != 0)&&($hprc < 20))
    $skip = 1;

  //Пока не кончатся ОД
  while (($ophp > 3)&&($skip == 0))
  {
    //Теперь сама битва
    switch($type)
    {
      //Атака мечом
      case 1:
        $ophp = $ophp - 3;
        change($opp, 'battle', 'value', $ophp);

        //Расчитываем повреждения
        $Damage = BladeStrike($opp);

        //Получаем защиту оппонента
        $Protect = ShieldProtection($lg);

        //Тупо считаем разность
        $Result = $Damage - $Protect;
        if ($Result < 0)
          $Result = 0;

        //Случайное везение
        $Result = $Result + rand(0, 2);

        //Отмечаем использование меча
        change($opp, 'battle', 'data', $info.'A');

        //Добавляем в ЛОГ
        if ($Result != 0)
        {
          ToLog($log, "<font color=yellow><b>".$opponent."</b></font> бьёт <font color=yellow><b>".$me."</b></font> и наносит ему урон <font color=white><b>".$Result."</b></font>");
          $myhealth = $myhealth - $Result;
          if ($Health < 0)
            $Health = 0;
          change($lg, 'hero', 'health', $myhealth);
        }
        else
          ToLog($log, "<font color=yellow><b>".$opponent."</b></font> слишком слабо бьёт <font color=yellow><b>".$me."</b></font>");
        break;
      //Атака магией
      case 2:
        $ophp = $ophp - 3;
        change($opp, 'battle', 'value', $ophp);

        //Находим тип заклинания
        $type = getfrom('num', $MCast, 'allcasts', 'type');

        //Вычисляем атаку заклинанием
        $Damage = DoCast($opp, $MCast, $type);

        //Использование доп. способностей
        $Damage = $Damage + Level(12, $opp)*$Damage/100;

        //Используем защиту от магии
        $Protect = getdata($lg, 'abilities', 'dexterity');

        //Выясняем действие заклинания
        $act = getfrom('num', $MCast, 'allcasts', 'action'); 

        //Конечный урон
        $Damage = $Damage - $Protect;
        If ($Damage < 0)
          $Damage = 0;

        //Используем заклинание и получаем текстовую строчку, как результат
        $What = UseCast($opp, $lg, $Damage, $act);

        //Отмечаем использование меча
        change($opp, 'battle', 'data', $info.'M');

        //И строчку в ЛОГ файл
        $String = "<font color=yellow><b>".$me."</b></font> использует заклинание <font color=white><b>".$CastName."</b></font> и ".$What;
        ToLog($log, $String);
        break;
    } //выбор типа
  } //потом передаём ход

  //Передача хода
  TurnStep($opp, $lg);

  //Обновляем экран
  moveto("battle.php");
}

//Обработка событий (только для игрока, который ходит)
if ($turn == $lg) 
{

  //Выбираем действие пользователя (кто-то капитулирует...)
  if ($action == 22)
  {
    Capitulation($lg, $opp);
    moveto("game.php?action=5");
  }

  //Бутылочки
  if (($action >= 10)&&($action <= 21))
  {
    //Что за бутылка
    switch($action)
    {
      //Здоровье максимум
      case 10:
        if (($HMax > 0)&&($hp >= $hbottle_need))
        {
          //Убрать бутылку
          $HMax--;
          change($lg, 'bottles', 'hmaxi', $HMax);
          ChangeWeight($lg, -3);

          //Добавить здоровье
          change($lg, 'hero', 'health', $mylevel*100);          

          //Убрать очки действия
          $hp = $hp - $hbottle_need;
          change($lg, 'battle', 'value', $hp);

          //В ЛОГ
          ToLog($log, "<font color=yellow><b>".$me."</b></font> использует большое зелье лечения");
        }
        break;
      //Здоровье среднее
      case 11:
        if (($HMed > 0)&&($hp >= $hbottle_need))
        {
          //Убрать бутылку
          $HMed--;
          change($lg, 'bottles', 'hmedi', $HMed);
          ChangeWeight($lg, -2);

          //Добавить здоровье
          $myhealth = $myhealth + $mylevel*50;
          if ($myhealth > $mylevel*100)
            $myhealth = $mylevel*100;
          change($lg, 'hero', 'health', $myhealth);          

          //Убрать очки действия
          $hp = $hp - $hbottle_need;
          change($lg, 'battle', 'value', $hp);

          //В ЛОГ
          ToLog($log, "<font color=yellow><b>".$me."</b></font> использует среднее зелье лечения");
        }
        break;
      //Здоровье минимум
      case 12:
        if (($HMin > 0)&&($hp >= $hbottle_need))
        {
          //Убрать бутылку
          $HMin--;
          change($lg, 'bottles', 'hmini', $HMin);
          ChangeWeight($lg, -1);

          //Добавить здоровье
          $myhealth = $myhealth + $mylevel*25;
          if ($myhealth > $mylevel*100)
            $myhealth = $mylevel*100;
          change($lg, 'hero', 'health', $myhealth);          

          //Убрать очки действия
          $hp = $hp - $hbottle_need;
          change($lg, 'battle', 'value', $hp);

          //В ЛОГ
          ToLog($log, "<font color=yellow><b>".$me."</b></font> использует малое зелье лечения");
        }
        break;
      //Мана максимум
      case 13:
        if (($MMax > 0)&&($hp >= $bottle_need))
        {
          //Убрать бутылку
          $MMax--;
          change($lg, 'bottles', 'mmaxi', $MMax);
          ChangeWeight($lg, -3);

          //Добавить здоровье
          change($lg, 'abilities', 'intellegence', $cnow*10);          

          //Убрать очки действия
          $hp = $hp - $bottle_need;
          change($lg, 'battle', 'value', $hp);

          //В ЛОГ
          ToLog($log, "<font color=yellow><b>".$me."</b></font> использует большое зелье маны");
        }
        break;
      //Мана средняя
      case 14:
        if (($MMed > 0)&&($hp >= $bottle_need))
        {
          //Убрать бутылку
          $MMed--;
          change($lg, 'bottles', 'mmedi', $MMed);
          ChangeWeight($lg, -2);

          //Добавить ману
          $mana = $mana + $cnow*5;
          if ($mana > ($cnow*10))
            $mana = $cnow*10;
          change($lg, 'abilities', 'intellegence', $mana);          

          //Убрать очки действия
          $hp = $hp - $bottle_need;
          change($lg, 'battle', 'value', $hp);

          //В ЛОГ
          ToLog($log, "<font color=yellow><b>".$me."</b></font> использует среднее зелье маны");
        }
        break;
      //Мана минимум
      case 15:
        if (($MMin > 0)&&($hp >= $bottle_need))
        {
          //Убрать бутылку
          $MMin--;
          change($lg, 'bottles', 'mmini', $MMin);
          ChangeWeight($lg, -1);

          //Добавить ману
          $mana = $mana + round($cnow*2.5);
          if ($mana > ($cnow*10))
            $mana = $cnow*10;
          change($lg, 'abilities', 'intellegence', $mana);          

          //Убрать очки действия
          $hp = $hp - $bottle_need;
          change($lg, 'battle', 'value', $hp);

          //В ЛОГ
          ToLog($log, "<font color=yellow><b>".$me."</b></font> использует малое зелье маны");
        }
        break;
      //Сила большая
      case 16:
        if (($PMax > 0)&&($hp >= $bottle_need))
        {
          //Убрать бутылку
          $PMax--;
          change($lg, 'bottles', 'pmaxi', $PMax);
          ChangeWeight($lg, -3);

          //Пишем, что выпили большую бутылочку
          change($lg, 'items', 'vrukah', '1');          

          //Убрать очки действия
          $hp = $hp - $bottle_need;
          change($lg, 'battle', 'value', $hp);

          //В ЛОГ
          ToLog($log, "<font color=yellow><b>".$me."</b></font> использует большое зелье силы и увеличивает свою атаку на 100%");
        }
        break;
      //Сила средняя
      case 17:
        if (($PMed > 0)&&($hp >= $bottle_need))
        {
          //Убрать бутылку
          $PMed--;
          change($lg, 'bottles', 'pmedi', $PMed);
          ChangeWeight($lg, -2);

          //Пишем, что выпили большую бутылочку
          change($lg, 'items', 'vrukah', '2');          

          //Убрать очки действия
          $hp = $hp - $bottle_need;
          change($lg, 'battle', 'value', $hp);

          //В ЛОГ
          ToLog($log, "<font color=yellow><b>".$me."</b></font> использует среднее зелье силы и увеличивает свою атаку на 50%");
        }
        break;
      //Сила минимум
      case 18:
        if (($PMin > 0)&&($hp >= $bottle_need))
        {
          //Убрать бутылку
          $PMin--;
          change($lg, 'bottles', 'pmini', $PMin);
          ChangeWeight($lg, -1);

          //Пишем, что выпили большую бутылочку
          change($lg, 'items', 'vrukah', '3');          

          //Убрать очки действия
          $hp = $hp - $bottle_need;
          change($lg, 'battle', 'value', $hp);

          //В ЛОГ
          ToLog($log, "<font color=yellow><b>".$me."</b></font> использует малое зелье силы и увеличивает свою атаку на 25%");
        }
        break;
      //Магия большая
      case 19:
        if (($SMax > 0)&&($hp >= $bottle_need))
        {
          //Убрать бутылку
          $SMax--;
          change($lg, 'bottles', 'smaxi', $SMax);
          ChangeWeight($lg, -3);

          //Пишем, что выпили большую бутылочку
          change($lg, 'abilities', 'magicpower', '1');          

          //Убрать очки действия
          $hp = $hp - $bottle_need;
          change($lg, 'battle', 'value', $hp);

          //В ЛОГ
          ToLog($log, "<font color=yellow><b>".$me."</b></font> использует большое зелье колдовской силы и увеличивает свою магическую атаку на 100%");
        }
        break;
      //Магия средняя
      case 20:
        if (($SMed > 0)&&($hp >= $bottle_need))
        {
          //Убрать бутылку
          $SMed--;
          change($lg, 'bottles', 'smedi', $SMed);
          ChangeWeight($lg, -2);

          //Пишем, что выпили большую бутылочку
          change($lg, 'abilities', 'magicpower', '2');          

          //Убрать очки действия
          $hp = $hp - $bottle_need;
          change($lg, 'battle', 'value', $hp);

          //В ЛОГ
          ToLog($log, "<font color=yellow><b>".$me."</b></font> использует среднее зелье колдовской силы и увеличивает свою магическую атаку на 50%");
        }
        break;
      //Магия минимум
      case 21:
        if (($SMin > 0)&&($hp >= $bottle_need))
        {
          //Убрать бутылку
          $SMin--;
          change($lg, 'bottles', 'smini', $SMin);
          ChangeWeight($lg, -1);

          //Пишем, что выпили большую бутылочку
          change($lg, 'abilities', 'magicpower', '3');          

          //Убрать очки действия
          $hp = $hp - $bottle_need;
          change($lg, 'battle', 'value', $hp);

          //В ЛОГ
          ToLog($log, "<font color=yellow><b>".$me."</b></font> использует малое зелье колдовской силы и увеличивает свою магическую атаку на 25%");
        }
        break;
    }

    //Обновляем экран
    moveto("battle.php");
  }

	//Выбираем действие пользователя (если это использование магии)
  if (($action >= 1)&&($action <= 6)&&($hp >= $magic_need))
  {
    //Сначала определяем, что это за заклинание (его номер)
    $num = getdata($lg, 'magic', 'cast' + $action);

    //А есть ли у нас такое заклинание
    if ($num != 0)
    {
      //Выясняем цену заклинания
      $cena = getfrom('num', $num, 'allcasts', 'cena'); 

      //А маны хватает
      if ($mana >= $cena)
      {
        //Выясняем тип заклинания
        $type = getfrom('num', $num, 'allcasts', 'type'); 

        //Выясняем действие заклинания
        $act = getfrom('num', $num, 'allcasts', 'action'); 

        //Выясняем название заклинание
        $CastName = getfrom('num', $num, 'allcasts', 'name'); 

        //Исполняем заклинание
        $Damage = DoCast($lg, $num, $type);

        //Использование доп. способностей
        $Damage = $Damage + Level(12, $lg)*$Damage/100;

        //Используем защиту от магии
        $Protect = getdata($opp, 'abilities', 'dexterity');

        //Конечный урон
        $Damage = $Damage - $Protect;
        If ($Damage < 0)
          $Damage = 0;

        //Увеличиваем урон от плащей там всяких...
/*        (int)$Num = getdata($lg, 'items', 'plash');

        //Дополнительный урон
        $Procent = getfrom('num', $Num, 'allitems', 'effect');
        $Type    = getfrom('num', $Num, 'allitems', 'action');
        if ($Type != 5)
          $Procent = 0;

        //Увеличиваем урон от плащей там всяких...
        (int)$Num = getdata($lg, 'items', 'shea');

        //Дополнительный урон
        $Procent2 = getfrom('num', $Num, 'allitems', 'effect');
        $Type    = getfrom('num', $Num, 'allitems', 'action');
        if ($Type != 5)
          $Procent2 = 0;
   
        //Пересчёт с плащом
        $Damage = $Damage + round($Damage*$Procent/100) + round($Damage*$Procent2/100); */
      
        //Используем заклинание и получаем текстовую строчку, как результат
        $What = UseCast($lg, $opp, $Damage, $act);

        //Если сработала антимагия
        if ($What == "Антимагия")
        {
          ToLog($log, "<b><font color=yellow>".$opp."</b></font> обладает антимагией и уворачивается от заклинания <b><font color=yellow>".$me."</font></b>");
        }
        else
        {
          //В ЛОГ
          $String = "<font color=yellow><b>".$me."</b></font> использует заклинание <font color=white><b>".$CastName."</b></font> и ".$What;
          ToLog($log, $String);
        }

        //Вычитаем ману
        change($lg, 'abilities', 'intellegence', $mana-$cena);

        //Вычитаем ОД
        $NewHP = $hp - $magic_need;
        if ($NewHP < 0)
          $NewHP = 0;
        change($lg, 'battle', 'value', $NewHP);
        
        //Отмечаем использование заклинания
        change($lg, 'battle', 'data', $info.'M');

        //Обновляем страницу
        moveto("battle.php");
      } // хватает ли маны
      else
      {
        ToLog($log, "У <font color=yellow><b>".$me."</b></font> не хватает магической энергии");
        moveto("battle.php");
      } // не хватает
    } // есть ли каст
  } // каст

  //Выбираем тип вещи (Если 1, то это посох)
  $item = 0;
  (int)$Num = getdata($lg, 'items', 'rightruka');
  $itype = getfrom('num', $Num, 'allitems', 'type');
  if ($itype == 11)
    $item = 1;

	//Выбираем действие пользователя (если это атака посохом)
  if (($action == 7)&&($hp >= $blade_need)&&($item == 1))
  {
    //Получаем наносимые повреждения
    $Damage = PosohStrike($lg);

    //Получаем защиту оппонента
    $Protect = getdata($opp, 'abilities', 'dexterity');

    //Тупо считаем разность
    $Result = $Damage - $Protect;
    if ($Result < 0)
      $Result = 0;

    //Случайное везение
    $Result = $Result + rand(0, 2);

    //Получаем название заклинания по номеру посоха
    $cast = CastName($Num);

    //Строчка в ЛОГ
    if ($Result != 0)
       ToLog($log, "<font color=yellow><b>".$me."</b></font> использует посох против <font color=yellow><b>".$opponent."</b></font> и тем самым вызывает заклинание ".$cast.", которое наносит оппоненту вред <font color=white><b>".$Result."</b></font>");
    else
       ToLog($log, "<font color=yellow><b>".$me."</b></font> слишком слабо бьёт <font color=yellow><b>".$opponent."</b></font>");

    //Вычитаем здоровье
    if ($Result > 0)
    {
      $NewHealth = $ophealth - $Result;
      if ($NewHealth < 0)
        $NewHealth = 0;
      change($opp, 'hero', 'health', $NewHealth);
    }

    //Вычитаем ОД
    $NewHP = $hp - $blade_need;
    if ($NewHP < 0)
      $NewHP = 0;
    change($lg, 'battle', 'value', $NewHP);

    //Отмечаем использование меча
    change($lg, 'battle', 'data', $info.'A');

    //Обновляем экран
    moveto("battle.php");
  }

	//Выбираем действие пользователя (если это атака мечом)
  if (($action == 7)&&($hp >= $blade_need)&&($item != 1))
  {
    //Получаем наносимые повреждения
    $Damage = BladeStrike($lg);

    //Получаем защиту оппонента
    $Protect = ShieldProtection($opp);

    //Тупо считаем разность
    $Result = $Damage - $Protect;
    if ($Result < 0)
      $Result = 0;

    //Случайное везение
    $Result = $Result + rand(0, 2);

    //Ловкость
    $Interval = rand(100, 1);
    $Dxt = Level(2, $opp);
    if ($Interval < $Dxt)
    {
      $Result = 0;
      ToLog($log, "<font color=yellow><b>".$opponent."</b></font> уворачивается от удара <font color=yellow><b>".$me."</b></font>");
    }
    else //В ЛОГ файл
    {
      if ($Result != 0)
        ToLog($log, "<font color=yellow><b>".$me."</b></font> бьёт <font color=yellow><b>".$opponent."</b></font> и наносит ему урон <font color=white><b>".$Result."</b></font>");
      else
        ToLog($log, "<font color=yellow><b>".$me."</b></font> слишком слабо бьёт <font color=yellow><b>".$opponent."</b></font>");
    }

    //Дополнительные способности
      //Магическое оружие
      $MagicWeapon = getdata($lg, 'abilities', 'naturemagic')*Level(14, $lg)/100;
      //Обессиливающее оружие
      $ManaWeapon = getdata($lg, 'abilities', 'naturemagic')*Level(15, $lg)/100;
  
    //Магическое оружие
    if (($MagicWeapon != 0)&&($Result != 0))
    {
      $MagicWeapon = round($MagicWeapon);
      ToLog($log, "<font color=yellow><b>".$me."</b></font> обладает способностью Магическое оружие и помимо обычного вреда <font color=yellow><b>".$opponent."</b></font> поражает огненный шар и наносит ".$MagicWeapon." повреждения");
      $Result = $Result + $MagicWeapon;
    }   

    //Магическое оружие
    if ($ManaWeapon != 0)
    {
      ToLog($log, "<font color=yellow><b>".$me."</b></font> обладает способностью обессиливающее оружие и помимо обычного вреда <font color=yellow><b>".$opponent."</b></font> теряет ".$ManaWeapon." очков маны");

      //Вычитаем ману
      $opmana = $opmana - $ManaWeapon;
      if ($opmana < 0)
        $opmana = 0;
      change($opp, 'abilities', 'intellegence', $opmana);
    }   

    //Вычитаем здоровье
    if ($Result > 0)
    {
      $NewHealth = $ophealth - $Result;
      if ($NewHealth < 0)
        $NewHealth = 0;
      change($opp, 'hero', 'health', $NewHealth);
    }

    //Вычитаем ОД
    $NewHP = $hp - $blade_need;
    if ($NewHP < 0)
      $NewHP = 0;
    change($lg, 'battle', 'value', $NewHP);

    //Отмечаем использование меча
    change($lg, 'battle', 'data', $info.'A');

    //Обновляем экран
    moveto("battle.php");
  }

	//Выбираем действие пользователя (если это защита)
  if (($action == 8)&&($hp >= $protect_need))
  {
    //Вычитаем ОД
    $NewHP = $hp - $protect_need;
    if ($NewHP < 0)
      $NewHP = 0;
    change($lg, 'battle', 'value', $NewHP);

    //Усиливаем защиту
    change($lg, 'battle', 'attack', '1');

    //В ЛОГ
    ToLog($log, "<font color=yellow><b>".$me."</b></font> усиливает свою защиту");
  
    //Отмечаем использование щита
    change($lg, 'battle', 'data', $info.'D');

    //Обновляем страницу
    moveto("battle.php");
  }

	//Выбираем действие пользователя (если это передача хода)
  if ($action == 9)
    TurnStep($lg, $opp);

} //Если Логин совпадает с логином текущего игрока

//И наконец спец. проверка
if ($myhealth > ($mylevel*100))
{
  $myhealth = $mylevel*100;
  change($lg, 'hero', 'health', $myhealth);
}
if ($ophealth > ($oplevel*100))
{
  $ophealth = $oplevel*100;
  change($opp, 'hero', 'health', $opphealth);
}
if ($mana > ($cnow*10))
{
  $mana = $cnow*10;
  change($lg, 'abilities', 'intellegence', $mana);
}
if ($opmana > ($opcnow*10))
{
  $opmana = $opcnow*10;
  change($opp, 'abilities', 'intellegence', $opmana);
}

//Основа в виде таблицы
?>
  <center>
  <table border=1 width=95% CELLSPACING=0 CELLPADDING=0>
  <?
  //Переводим здоровье в проценты
  $hprc = $myhealth/$mylevel;
  if ($cnow != 0)
    $mprc = (10*$mana)/$cnow;
    else
    $mprc = 0;

	//Выводим шапку таблицы (Битва между, фотки и ЛОГ текущего боя)
  echo("<tr><td colspan=3 align=center>Поединок между <b>$me</b> и <b>$opponent</b><br>До передачи хода осталось: $CountDown секунд<br>$plturn<br></td></tr>");
  echo("<tr><td align=center width=15%>$me<br>Уровень: $mylevel<br><img src='images/photos/$myphoto' width=150 height=200><br>Здоровье: $myhealth<br>");
  pbar($hprc, 'red');
  echo("Очков: $hp<br>Мана: $mana<br>");
  pbar($mprc, 'blue');
  echo("Опыт: ".$mynewexpa."</td><td valign=top>");
  echo("<center>");
    echo("<table border=1 width=100% height=100% CELLSPACING=0 CELLPADDING=0>");
    echo("<tr><td valign=top>");
   	readfile($logfile);
    echo("</td></tr>");
    echo("<tr><td align=center>");
    ?>
      <table>
        <tr>
        <td align=center width=33%>
        <br>
        <form action='battle.php' method=post>
        <input type="hidden" name="action" value=23>
       	<input type="submit" value="       Ничья      " style='background:#000099; font-WEIGHT: BOLD; COLOR: white; cursor: hand; list-style: lower-alpha; filter: Alpha(Opacity=20, FinishOpacity=100, Style=3)'>
        </form>
        </td>
        <td align=center width=33%>
        <br>
        <form action='battle.php' method=post>
        <input type="hidden" name="action" value=9>
       	<input type="submit" value="Передать ход" style='background:#000099; font-WEIGHT: BOLD; COLOR: white; cursor: hand; list-style: lower-alpha; filter: Alpha(Opacity=20, FinishOpacity=100, Style=3)'>
        </form>
        </td>
        <td align=center width=33%>
        <br>
        <form action='battle.php' method=post>
        <input type="hidden" name="action" value=22>
       	<input type="submit" value="Капитуляция" style='background:#000099; font-WEIGHT: BOLD; COLOR: white; cursor: hand; list-style: lower-alpha; filter: Alpha(Opacity=20, FinishOpacity=100, Style=3)'>
        </form>
        </td>
        </tr>
      </table>
    <?
    echo("</td></tr>");
    echo("</table>");
  echo("</center>");

  //Для ползунков
  $hprc = $ophealth/$oplevel;
  if ($opcnow != 0)
    $mprc = (10*$opmana)/$opcnow;
    else
    $mprc = 0;

  //Конец таблицы
  echo("</td>");

  //Проверяем, не монстр ли наш оппонент?
  $Mns = getdata($opp, 'city', 'build14');
  if ($Mns == 1)
    $opp = $opponent;

  //Выводим информацию
  echo("<td align=center width=15%>$opp<br>Уровень: $oplevel<br><img src='images/photos/$opphoto' width=150 height=200><br>Здоровье: $ophealth<br>");
  pbar($hprc, 'red');
  echo("Очков: $ophp<br>Мана: $opmana");
  pbar($mprc, 'blue');

  //Выводим опыт, если это не монстр
  if ($Mns != 1)
    echo("Опыт: ".$opnewexpa);

  //Конец правой ячейки
  echo("</td></tr>");

	//Выводим все имеющиеся заклинания (а также, действия)
	echo("<tr><td align=center colspan=3>");

  //Табличка для кнопочек
  echo("<table border=0 CELLSPACING=0 CELLPADDING=0 width=10%><tr>");

	//Кнопка атаковать:
	  //Получаем номер оружия в руке
	  (int)$num = getdata($lg, 'items', 'rightruka');
    if ($num != 0)
    {
      $imgfile = getimg($num);
      $alt = getinfo($num);
    }
      else
    {
      $imgfile = "images/weapons/null/hand.jpg";
      $alt = "У Вас нет оружия в руках";
    }
    //Получаем адрес картинки оружия
	  $s = "<img src='".$imgfile."' alt='".$alt."' width=80 height=80 border=0>";
	echo("<td align=center>ОД: $blade_need<br><a href='battle.php?action=7'>".$s."</a><br>Атака</td>");

	//Теперь вся магия
	for ($i = 1; $i <= 6; $i++)
	{
		//Номер каста
		$num = getdata($lg, 'magic', 'cast' + $i);
		//Адрес картинки
		$cast = getfrom('num', $num, 'allcasts', 'img');

    //Если каст есть, выводим его
    if ($num != 0)
    {
      $cena = getfrom('num', $num, 'allcasts', 'cena'); 
  		echo("<td align=center>ОД: $magic_need<br><a href='battle.php?action=$i'><img src='images/cast/$cast' width=80 height=80 alt='".getcinfo($num)."' border=0></a><br>Мана: $cena</td>");
    }
	}

	//Кнопка защищаться:
	  //Получаем номер оружия в руке
	  (int)$num = getdata($lg, 'items', 'leftruka');
	  //Получаем адрес картинки оружия
	  $s = "<img src='".getimg($num)."' alt='".getinfo($num)."' width=80 height=80 border=0>";
	if ($num != 0)
    echo("<td align=center>ОД: $protect_need<br><a href='battle.php?action=8'>".$s."</a><br>Защита</td>");
  
  //Конец таблицы кнопочек
  echo("</tr></table>");

  //Строка с бутылками
  echo("</td></tr>");
	echo("<tr><td colspan=3 align=center>");

  //Табличка для бутылочек
  echo("<table border=0 CELLSPACING=0 CELLPADDING=0 width=10%><tr>");
  //Бутылочки со здоровьем
  if ($HMax > 0)
    echo("<td align=center>ОД: $hbottle_need<br><a href='battle.php?action=10'><img src='images/bottles/big_h.jpg' alt='Восстанавливает 100% здоровья' width=64 height=64 border=0><br>$HMax</a></td>");
  if ($HMed > 0)
    echo("<td align=center>ОД: $hbottle_need<br><a href='battle.php?action=11'><img src='images/bottles/med_h.jpg' alt='Восстанавливает 50% здоровья' width=64 height=64 border=0><br>$HMed</a></td>");
  if ($HMin > 0)
    echo("<td align=center>ОД: $hbottle_need<br><a href='battle.php?action=12'><img src='images/bottles/sma_h.jpg' alt='Восстанавливает 25% здоровья' width=64 height=64 border=0><br>$HMin</a></td>");

  //Бутылочки с маной
  if ($MMax > 0)
    echo("<td align=center>ОД: $bottle_need<br><a href='battle.php?action=13'><img src='images/bottles/big_m.jpg' alt='Восстанавливает 100% маны' width=64 height=64 border=0><br>$MMax</a></td>");
  if ($MMed > 0)
    echo("<td align=center>ОД: $bottle_need<br><a href='battle.php?action=14'><img src='images/bottles/med_m.jpg' alt='Восстанавливает 50% маны' width=64 height=64 border=0><br>$MMed</a></td>");
  if ($MMin > 0)
    echo("<td align=center>ОД: $bottle_need<br><a href='battle.php?action=15'><img src='images/bottles/sma_m.jpg' alt='Восстанавливает 25% маны' width=64 height=64 border=0><br>$MMin</a></td>");

  //Бутылочки с силой
  if ($PMax > 0)
    echo("<td align=center>ОД: $bottle_need<br><a href='battle.php?action=16'><img src='images/bottles/big_p.jpg' alt='Увеличивает силу на 100%' width=64 height=64 border=0><br>$PMax</a></td>");
  if ($PMed > 0)
    echo("<td align=center>ОД: $bottle_need<br><a href='battle.php?action=17'><img src='images/bottles/med_p.jpg' alt='Увеличивает силу на 50%' width=64 height=64 border=0><br>$PMed</a></td>");
  if ($PMin > 0)
    echo("<td align=center>ОД: $bottle_need<br><a href='battle.php?action=18'><img src='images/bottles/sma_p.jpg' alt='Увеличивает силу на 25%' width=64 height=64 border=0><br>$PMin</a></td>");

  //Бутылочки с колдовской силой
  if ($SMax > 0)
    echo("<td align=center>ОД: $bottle_need<br><a href='battle.php?action=19'><img src='images/bottles/big_i.jpg' alt='Увеличивает колдовскую силу на 100%' width=64 height=64 border=0><br>$SMax</a></td>");
  if ($SMed > 0)
    echo("<td align=center>ОД: $bottle_need<br><a href='battle.php?action=20'><img src='images/bottles/med_i.jpg' alt='Увеличивает колдовскую силу на 50%' width=64 height=64 border=0><br>$SMed</a></td>");
  if ($SMin > 0)
    echo("<td align=center>ОД: $bottle_need<br><a href='battle.php?action=21'><img src='images/bottles/sma_i.jpg' alt='Увеличивает колдовскую силу на 25%' width=64 height=64 border=0><br>$SMin</a></td>");

  //Конец таблицы для бутылочек
  echo("</tr></table>");

  //Закрываем большую таблицу
  echo("</td></tr>");
	echo("</td></tr>");
  ?>
  </table>
  </center>
<?

?>