<?

//Набери MAX_ABILITY
$ZERO = 1106665145;

//Моя функция генерации ЦЕЛОГО псевдослучайного числа ($min & $max - целые)
function random($min, $max)
{
  //Стабилизируем границы
  if ($max < $min)
  {
    $temp = $max;
    $max = $min;
    $min = $temp;
  }

  //Сброс числа
  $number = 0;

  //Сначала получаем произвольное число в интервале (1; 3)
  $moment = time() - $ZERO; 
  $rnd = sin($moment)+2;
 
  //Переводим полученное число в наш интервал
  $number = round($rnd*$max/3);

  //Возвращаем число
  return $number;
}

//Вырезает из строки первый символ
function cut($s)
{
  $ns = "";
  for ($i = 1; $i < strlen($s); $i++)
    $ns = $ns.$s[$i];
  return $ns;
}

//Функция для подбора доп. способностей
function newchar($Login)
{
  //Нулевая
  $num = 0;

  //Что уже выбрано
  $ch1 = getdata($Login, 'abilities', 'combatmagic');
  $ch2 = getdata($Login, 'abilities', 'mindmagic');

  //Если все ячейки заняты и всё на эксперте, возвращаем ноль
  $emptycell = 0;
  for ($i = 1; $i <= 16; $i++)
  {
    $inf = getdata($Login, 'newchar', 'achar'.$i);
    if ($inf[0] != 'E')
      $emptycell = 1;
  }

  //Если всё на эксперте
  if ($emptycell == 0)
  {
    change($Login, 'abilities', 'combatmagic', '0');
    change($Login, 'abilities', 'mindmagic', '0');
    moveto("game.php?action=1");
  }

  //Пока не найдена
  while ($num == 0)
  {
    //Выбираем случайную способность (MAX_ABILITY)
    $num = rand(30, 1);

    //А может все клетки уже заняты, тогда новые способности давать нельзя
    $emptycell = 0;
    $same = 0;
    for ($i = 1; $i <= 16; $i++)
    {
      $inf = getdata($Login, 'newchar', 'achar'.$i);
      if ($inf[0] == '0')
        $emptycell = 1;

      //Если совпадает с одной из уже имеющихся
      if (cut($inf) == $num)
        $same = 1;
    }

    //Если свободных мест нет, а эта способность новая, то так дело не пойдёт!
    if (($same == 0)&&($emptycell == 0))
      $num = 0;

    //Проверяем, не используется ли она уже в ячейках
    if (($ch1 != $num)&&($ch2 != $num)&&($num != 0))
    {
      //Нет ли такой способности уже у нас (а если есть, то просто увеличить её)
      for ($i = 1; $i <= 16; $i++)
      {
        //Использовать можно
        $ok[$i] = 1;

        //В текущей ячейке у нас...
        $cell = getdata($Login, 'newchar', 'achar'.$i);

        //Номер способности в текущей ячейке
        (int)$numb = cut($cell);

        //Если ещё не эксперт, то использовать можно
        if (($cell[0] == 'E')&&($numb == $num))
        {
          //Использовать её нельзя, т.к. она уже прокачана
          $ok[$i] = 0; 
        } // если не эксперт
      } // проверить, нет ли таких же уже изученных

      //Если есть хоть в одной ячейке изученная, то нельзя
      $yes = 1;
      for ($i = 1; $i <= 16; $i++)
        if ($ok[$i] == 0)
        {
          $yes = 0;
          $num = 0;
        } // нет. нельзя
    } // нет ли её в ячейках вновь появившихся & != 0
  } // пока не найдена (цикл)

  //Возврат способности
  return $num;
}

//Получаем уровень дополнительной характеристики по номеру (если она есть в списке игрока)
function Level($Num, $Login)
{
  //Есть ли у нас такая характеристика
  $finded = 0;
  for ($i = 1; $i <= 16; $i++)
  {
    $ability = getdata($Login, 'newchar', 'achar'.$i);
    $number = cut($ability);

    //Если способность найдена, возвращаем число, соответствующее уровню
    if ($number == $Num)
    {
      //Какой уровень
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

  //Если найден
  if ($finded != 0)
  {
    $Lvl = getfrom('num', $Num, 'additional', 'level'.$finded);
  }
  else
    $Lvl = 0;

  //Возвращаем данные
  return $Lvl;
}

//Название заклинания по номеру посоха
function CastName($num)
{
  //Дополняем полученный результат, если это посох
  switch($num)
  {
    case 109:
      $find = "Магический удар";
      break;
    case 110:
      $find = "Удар силы";
      break;
    case 111:
      $find = "Молния";
      break;
    case 112:
      $find = "Электрический разряд";
      break;
    case 113:
      $find = "Огненная струя";
      break;
    case 114:
      $find = "Огненный шар";
      break;
    case 115:
      $find = "Зарядиться";
      break;
    case 116:
      $find = "Лечение";
      break;
    case 117:
      $find = "Метеоритный град";
      break;
    case 118:
      $find = "Инферно";
      break;
  }
  return $find;
}
?>