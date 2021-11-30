<title>Битва</title>
<link rel='stylesheet' type='text/css' href='style.css'/>
<?
  //Подключаем модуль функций
  include "battlemodule.php";

  //Проверка игрока
  $lg = trim($HTTP_COOKIE_VARS["nativeland"]);
  if ((hasuser($lg) == 0))
    exit();

  //Если нет битвы
  $btl = getdata($lg, 'battles', 'battle');
  if ($btl == 0)
    moveto('game.php');
  
  //Сначала получаем данные из базы
  $me = $lg;
  $op = getdata($lg, 'battles', 'opponent');
  $tp = getdata($lg, 'battles', 'health');

  //Моя армия
  $my_full = 0;
  for ($i = 1; $i <=4; $i++)
  {
    $my[$i] = getdata($lg, 'army', 'level'.$i);
    $my_full = $my_full + $my[$i];
  }

  //Армия оппонента
  $he_full = 0;
  for ($i = 1; $i <=4; $i++)
  {
    $he[$i] = getdata($op, 'army', 'level'.$i);
    $he_full = $he_full + $he[$i];
  }

  //Если сдаёмся, то 50% армии убираем
  if ($action == 2)
  {
    //Уменьшаем армию в два раза
    for ($i = 1; $i <= 4; $i++)
    {
      $army = getdata($me, 'army', 'level'.$i);
      change($me, 'army', 'level'.$i, round($army/2));
    }

    //Сбрасываем армию
    $my_full = 0;
  }

  //Теперь проверка на выигрыш/проигрыш
    //Если проиграли
    if ($my_full == 0)
    {
      //Выключаем битву
      OffThisBattle($me, $op);
      $rx = getdata($me, 'capital', 'rx');
      $ry = getdata($me, 'capital', 'ry');
      $cx = getdata($me, 'capital', 'x');
      $cy = getdata($me, 'capital', 'y');

      //Отправляем игрока в столицу
      change($me, 'coords', 'rx', $rx);
      change($me, 'coords', 'ry', $ry);
      change($me, 'coords', 'x',  $cx);
      change($me, 'coords', 'y',  $cy);
      moveto('map.php');
    }

    //Если выиграли
    if ($he_full == 0) 
    {
      //Выключаем битву
      OffThisBattle($me, $op);    

      //Грабим осаждённого
      Grab($me, $op);
    }
 
  //Выясняем фон, на котором мы находимся...
  $Back = getdata($me, 'battle', 'value');
  switch($Back)
  {
    case 0:
      $Type = "grass";
    break;
    case 1:
      $Type = "snow";
    break;
    case 2:
      $Type = "sand";
    break;
    case 3:
      $Type = "fire";
    break;
  }
  
  //Отображаем фон
//  echo("<body background='images/terrain/battle/".$Type.".jpg'>\n");
  echo("<body background='images/back.jpe'>\n");

  //Включаем автообновление (каждые 10 секунд)
  echo ("<META HTTP-EQUIV='REFRESH' CONTENT=10>\n");

  //А чей собственно ход
  $Turn = getdata($me, 'battle', 'turn');

  //Проверяем какой монстр сейчас ходит?
  $Current  = getdata($me, 'battle', 'attack');
  $Movement = getdata($me, 'battle', 'health');
  $Count    = getdata($me, 'army', 'level'.$Current);

  //Если передача хода следующему монстру (и наш ход)
  if (($action == 3)&&($Turn == $me))
    $Movement = 0;

  //Передача хода следующему монстру
  if ( (($Movement <= 0)||($Count <= 0)) && ($Turn == $me) )
  {
    $Current++;

    //Если все монстры уже сделали ход
    if ($Current >= 5)
    {
      $Current = 1;
      ToOpponent($me, $op);
    }

    //Перемещение след. монстра
    $Mvm = 2*$Current + 1;

    //Изменяем данные в базе
    change($me, 'battle', 'attack', $Current);
    change($me, 'battle', 'health', $Mvm);
    moveto('fight.php');
  }

  //Получаем количество моих воинов
  for ($i = 1; $i <= 4; $i++)
  {
    $MyWLevel[$i] = getdata($me, 'army', 'level'.$i);
    $OpWLevel[$i] = getdata($op, 'army', 'level'.$i);
    $x[$i]        = getdata($me, 'war', 'lx'.$i);
    $y[$i]        = getdata($me, 'war', 'ly'.$i);
    $px[$i]       = getdata($op, 'war', 'lx'.$i);
    $py[$i]       = getdata($op, 'war', 'ly'.$i);
  }

  //Заполняем массив карты
  for ($i = 0; $i < 10; $i++)
    for ($j = 0; $j < 8; $j++);
      $map[$i][$j] = 0;
  
  //Мои воины и оппонента
  for ($i = 1; $i <= 4; $i++)
  {
    //Если есть
    if ($MyWLevel[$i] != 0)
      $map[$y[$i]][$x[$i]] = $i;
    if ($OpWLevel[$i] != 0)
      $map[$py[$i]][$px[$i]] = $i+4;
  }

  //Рисуем стену
  $map[0][7] = "9";
  $map[1][7] = "9";
  $map[2][7] = "9";
  $map[4][7] = "9";
  $map[5][7] = "9";
  $map[6][7] = "9";

  //Если я атакующий
  $Who = getdata($me, 'battles', 'health');
  if ($Who == 2)
  {
    $Add = Level(21, $me);

    //И у меня есть осада
    switch($Add)  
    {
      case 1:
        $map[0][7] = "0";
        break;
      case 2:
        $map[0][7] = "0";
        $map[2][7] = "0";
        break;
      case 3:
        $map[0][7] = "0";
        $map[2][7] = "0";
        $map[6][7] = "0";
        break;
    }
  }

  //Перемещение персонажей или атака
  if (($action == 1)&&($me == $Turn))
  {
    //Проверка на корректность
    if ($kx < 0)
      $kx = 0;
    if ($kx > 6)
      $kx = 6;
    if ($ky < 0)
      $ky = 0;
    if ($ky > 9)
      $ky = 9;

    //Сначала определяем пустая ли клетка
    if ($map[$kx][$ky] == 0)
    {
      //КЛЕТКА ПУСТАЯ, СЮДА МОЖНО ПЕРЕМЕЩАТЬСЯ
      //Определяем координаты текущего воина
      $MyX = $y[$Current];
      $MyY = $x[$Current];

      //Определяем, далеко ли она от нас?
      $Path = abs($kx - $MyX) + abs($ky - $MyY);

      //Если она ближе, чем можно дойти, то перемещаем в неё персонажа
      if ($Path <= $Movement)
      {
        change($me, 'war', 'lx'.$Current, $ky);
        change($me, 'war', 'ly'.$Current, $kx);
        change($me, 'battle', 'health', $Movement-$Path);
      }
    } 
    else
    {
      //В ЭТОЙ КЛЕТКЕ КТО-ТО ЕСТЬ (ИЛИ ПРЕПЯТСТВИЕ)
      //Если это не свой, то атакуем его
      if (($map[$kx][$ky] > 4)&&($map[$kx][$ky] < 9))
      {
        //Флаг на атаку
        $AttackFlag = 0;

        //Причём, расстояние до него должно быть равно 1 (если воин не стреляющий)
        $MyX = $y[$Current];
        $MyY = $x[$Current];

        //Определяем, далеко ли она от нас?
        $Path = abs($kx - $MyX) + abs($ky - $MyY);

        //Если путь 1, то атаковать можно
        if ($Path == 1)
          $AttackFlag = 1;

        //Если монстр имеет стрелы <=> монстр стреляющий <=> можно атаковать с любой позиции
        $Arrows = getdata($me, 'war', 'arrow'.$Current);
        if ($Arrows != 0)
          $AttackFlag = 1;

        //Теперь проводим саму атаку (по завершении передаём ход следующему воину <=> Movement -> 0)
        if ($AttackFlag == 1)
        {
          AttackWarrior($Current, $map[$kx][$ky], $me, $op);
        } //AttackFlag
      } //Чужой на клетке
    } //Атака

    //Перенаправляем назад, в битву
    $action = 0;
    moveto("fight.php");
  }

  //Отображаем большую таблицу
  /*
  0000000000000000
  0   0          0
  0 M 0  Battle  0
  0   0          0
  0000000000000000
  */
  ?>
    <center>
    <table border=1 cellpadding=0 cellspacing=0 width=100% height=1%>
    <tr>
    <td align=center valign=top>
    <font color=white>
    <b>
  <?

  //Отображаем информацию о текущем монстре, а также, управляющие кнопки
  $MyRace   = getdata($me, 'hero', 'race');
  $Count    = $MyWLevel[$Current];
  $Health   = getdata($me, 'war', 'health'.$Current);
  $Name     = query($Current, $MyRace, 'name');
  $Pic      = query($Current, $MyRace, 'img');
  $Arrows   = getdata($me, 'war', 'arrow'.$Current);
  $Movement = getdata($me, 'battle', 'health');
  $Movement = round($Movement * 100 / (2 * $Current + 1));
  echo("<img src='images/warriors/".$Pic.".jpg' border=0 alt=''><br>\n");
  echo("Отряд: ".$Name."<br>\n");
  echo("Количество: ".$Count."<br>\n");
  echo("Остаток здоровья: ".$Health."<br>\n");
  echo("Остаток стрел: ".$Arrows."<br>\n");
  echo("Остаток перемещения:");
  Progress($Movement);

  //Кнопка передать ход
  ?>
    <form action='fight.php' method=post>
    <input type='hidden' name='action' value=3>
    <input type="submit" value="Передать ход" style='background:#000099; font-WEIGHT: BOLD; COLOR: white; cursor: hand; list-style: lower-alpha; filter: Alpha(Opacity=20, FinishOpacity=100, Style=3)'>
    </form>
  <?

  //Кнопка сдаться
  ?>
    <form action='fight.php' method=post>
    <input type='hidden' name='action' value=2>
    <input type="submit" value="Сдаться" style='background:#000099; font-WEIGHT: BOLD; COLOR: white; cursor: hand; list-style: lower-alpha; filter: Alpha(Opacity=20, FinishOpacity=100, Style=3)'>
    </form>
  <?

  //Отображаем саму битву (таблицы 10х7 с монстрами и стенами)
  //Начало таблицы
  ?>
    </b>
    </font>
    </td>
    <td width=2%>
    <center>
    <table border=1 cellpadding=0 cellspacing=0 width=10% height=10%>
  <?

  //Выводим информацию
  PageHeader($me, $op);

  //Моя раса и оппонента
  $MyRace = getdata($me, 'hero', 'race');
  $OpRace = getdata($op, 'hero', 'race');
  
  //Собираем информацию о названиях монстров в архив
  $MName[9] = "";
  $MName[1] = query(1, $MyRace, 'name');
  $MName[2] = query(2, $MyRace, 'name');
  $MName[3] = query(3, $MyRace, 'name');
  $MName[4] = query(4, $MyRace, 'name');
  $MName[5] = query(1, $OpRace, 'name');
  $MName[6] = query(2, $OpRace, 'name');
  $MName[7] = query(3, $OpRace, 'name');
  $MName[8] = query(4, $OpRace, 'name');

  //Отображаем все 70 клеток...
  for ($i = 0; $i < 7; $i++)
  {
    echo("<tr>");
    for ($j = 0; $j < 10; $j++)
    {
      echo("<td align=center width=64 height=64 valign=middle>");
      echo("<a href='fight.php?action=1&kx=".$i."&ky=".$j."'>");

      //Определяем защищающегося
      $Dft = getdata($me, 'battles', 'health');
      if ($Dft == 1)
      {
        $Lft = $op;
        $Rgt = $me;
      }
      else
      {
        $Lft = $me;
        $Rgt = $op;
      }

      //Определеяем расу монстра
      $LftR = getdata($Lft, 'hero', 'race');
      $RgtR = getdata($Rgt, 'hero', 'race');
      
      //Выводим картинку
      if ($map[$i][$j] != 0)
      {
        //Если это монстр
        if ($map[$i][$j] < 5)
        {
          $Monster = $LftR."/".$map[$i][$j].$Type."right";
          echo("<img src='images/warriors/battle/".$Monster.".jpg' width=64 height=64 border=0 alt='".$MName[$Monster]."'>");
        }
        if (($map[$i][$j] < 9)&&($map[$i][$j] > 4))
        {
          $Monster = $RgtR."/".($map[$i][$j]-4).$Type."left";
          echo("<img src='images/warriors/battle/".$Monster.".jpg' width=64 height=64 border=0 alt='".$MName[$Monster]."'>");
        }
        
        //Другие объекты
        if ($map[$i][$j] == 9)
         echo("<img src='images/objects/".$Type.".jpg' width=64 height=64 border=0 alt='Крепостная стена'>");
      }
      else //Никого нет в клетке. Выводим территорию
        echo("<img src='images/terrain/battle/".$Type.".jpg' width=64 height=64 border=0>");
      echo("</a>");
      echo("</td>");
    }
    echo("</tr>");
  }

  //Выводим последние 3 строчки ЛОГ файла
  echo("<tr>\n");
    echo("<td colspan=10 align=center>\n");
      PrintLog($me);
    echo("</td>\n");
  echo("</tr>\n");

  //Конец таблицы
  ?>
    </table>
    </center>
    </td>
    <td>
      <table border=1 cellpadding=0 cellspacing=0 width=100%>
      <?
        //Выводим количество воинов для каждого отряда оппонента
        for ($i = 1; $i <= 4; $i++)
        {
          $Name  = query($i, $OpRace, 'name');
          $Count = getdata($op, 'army', 'level'.$i);
          echo("<tr>\n");
          echo("<td><font color=white>$Name</font></td>\n");
          echo("<td><font color=white>$Count</font></td>\n");
          echo("</tr>\n");
        }
      ?>
      </table>
    </td>
    </tr>
    </table>
    </center>
  <?
?>