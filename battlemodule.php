<?
  /* Функции для работы с битвой */
  include "functions.php";

  //Получение данных из таблицы по имени поля, имени пользователя и имени таблицы
  function query($level, $race, $field)
  {
    $usr = mysql_query("select * from warriors;");
    $find = "";
    if ($usr)
      while ($user = mysql_fetch_array($usr))
        if (($user['level'] == $level)&&($user['race'] == $race))
          $find = $user[$field];
  	return $find;
  }

  //Позиционирование войск перед битвой
  function PositionWarriors($Login, $Side)
  {
    /* Позиционирование солдат на поле битвы 
    #########################################
    #   #   #   #   #   #   #   # * #   #   #
    #########################################
    # 1 #   #   #   #   #   #   # * #   # 1 #
    #########################################
    # 2 #   #   #   #   #   #   # * #   # 2 #
    #########################################
    #   #   #   #   #   #   #   #   #   #   #
    #########################################
    # 3 #   #   #   #   #   #   # * #   # 3 #
    #########################################
    # 4 #   #   #   #   #   #   # * #   # 4 #
    #########################################
    #   #   #   #   #   #   #   # * #   #   #
    #########################################
    Поле - прямоугольник 10х7
    (Side == 0) => Слева
    (Side == 1) => Справа
    */

    //Выбираем X координату армии
    if ($Side == 0)
      $X = 0;
    else
      $X = 9;

    //Определяем здоровье воинов
    $race = getdata($Login, 'hero', 'race');
    $h1 = query(1, $race, 'health');
    $h2 = query(2, $race, 'health');
    $h3 = query(3, $race, 'health');
    $h4 = query(4, $race, 'health');

    //Определяем количество стрел воинов
    $a1 = query(1, $race, 'arrows');
    $a2 = query(2, $race, 'arrows');
    $a3 = query(3, $race, 'arrows');
    $a4 = query(4, $race, 'arrows');

    //Заносим остальное в БД
    mysql_query("insert into war values('$Login', '$X', '1', '$X', '2', '$X', '4', '$X', '5', '3', '5', '7', '9', '$h1', '$h2', '$h3', '$h4', '$a1', '$a2', '$a3', '$a4');");
  }

  //Выключение битвы
  function OffThisBattle($Login, $Opponent)
  {
    //Отключаем стратегическую битву
    change($Login, 'battles', 'opponent', '0');
    change($Login, 'battles', 'battle', '0');
    change($Login, 'battles', 'health', '0');
    change($Opponent, 'battles', 'opponent', '0');
    change($Opponent, 'battles', 'battle', '0');
    change($Opponent, 'battles', 'health', '0');

    //Очищаем таблицу для битвы персонаж - персонаж
    change($Login, 'battle', 'health', '0');
    change($Opponent, 'battle', 'health', '0');
    change($Login, 'battle', 'opponent', '0');
    change($Opponent, 'battle', 'opponent', '0');
    change($Login, 'battle', 'turn', '0');
    change($Opponent, 'battle', 'turn', '0');
    change($Login, 'battle', 'attack', '0');
    change($Opponent, 'battle', 'attack', '0');
    change($Login, 'battle', 'data', '');
    change($Opponent, 'battle', 'data', '');
    change($Login, 'battle', 'value', '0');
    change($Opponent, 'battle', 'value', '0');
    change($Login, 'battle', 'info', '0');
    change($Opponent, 'battle', 'info', '0');
    change($Login, 'battle', 'timeout', '0');
    change($Opponent, 'battle', 'timeout', '0');

    //Удаляем из таблицы воинов
  	mysql_query("delete from war where login = '".$Login."';");
  	mysql_query("delete from war where login = '".$Opponent."';");
  }

  //Грабёж осаждённого
  function Grab($Login, $Opponent)
  {
    //Как зовут оппонента?
    $MyName = getdata($Login, 'hero', 'name');
    $OpName = getdata($Opponent, 'hero', 'name');

    //Узнаём количество ресурсов игрока
    $Metal = getdata($Opponent, 'economic', 'metal');
    $Rock  = getdata($Opponent, 'economic', 'rock');
    $Wood  = getdata($Opponent, 'economic', 'wood');
    $Money = getdata($Opponent, 'economic', 'money');

    //Забираем 1/3
    change($Opponent, 'economic', 'metal', round(2*$Metal/3));
    change($Opponent, 'economic', 'rock',  round(2*$Rock/3));
    change($Opponent, 'economic', 'wood',  round(2*$Wood/3));
    change($Opponent, 'economic', 'money', round(2*$Money/3));

    //Переводим деньги в наш курс
    $Curse = getdata($Opponent, 'economic', 'curse');
    $MeMoney = $Money / $Curse;
    $Curse = getdata($Login, 'economic', 'curse');
    $MeMoney = $MeMoney * $Curse;

    //Плюсуем к своим ресурсам
    $MyMoney = getdata($Login, 'economic', 'money');
    $MyMetal = getdata($Login, 'economic', 'metal');
    $MyRock  = getdata($Login, 'economic', 'rock');
    $MyWood  = getdata($Login, 'economic', 'wood');

    //Добавляем себе 1/3
    change($Login, 'economic', 'money', round($MeMoney/3+$MyMoney));
    change($Login, 'economic', 'metal', round($Metal/3  +$MyMetal));
    change($Login, 'economic', 'rock',  round($Rock/3   +$MyRock));
    change($Login, 'economic', 'wood',  round($Wood/3   +$MyWood));

    //Название денег
    $MonName = getdata($Login, 'economic', 'moneyname');
    $HisName = getdata($Opponent, 'economic', 'moneyname');

    //Результаты осады обоим в СМС
    sms($Login, "Военный штаб", "В результате успешной осады замка игрока ".$OpName." из осаждённого замка было вывезено: <b>".$MonName."</b>: ".$MeMoney."; <b>Металла</b>: ".$Metal."; <b>Камня</b>: ".$Rock."; <b>Дерева</b>: ".$Wood);
    sms($Opponent, "Военный штаб", "В результате неуспешной защиты замка, ".$MyName." вывез из осаждённого замка: <b>".$HisName."</b>: ".$Money."; <b>Металла</b>: ".$Metal."; <b>Камня</b>: ".$Rock."; <b>Дерева</b>: ".$Wood);
  }

  //Передача хода оппоненту
  function ToOpponent($Login, $Oppon)
  {
    $tm = time();
    change($Login, 'battle', 'timeout', $tm);
    change($Oppon, 'battle', 'timeout', $tm);
    change($Login, 'battle', 'turn', $Oppon);
    change($Oppon, 'battle', 'turn', $Oppon);
    change($Oppon, 'battle', 'attack', 1);
    change($Oppon, 'battle', 'health', 3);
    moveto("fight.php");
  }

  //Заголовок страницы
  function PageHeader($Login, $Oppon)
  {
    echo("<tr>\n");
    echo("<td colspan=10 align=center>\n");

    //Чей ход
    $Turn = getdata($Login, 'battle', 'turn');
    $Hero = getdata($Turn, 'hero', 'name');

    //Как давно был дан ход
    $TimeOut = 180;
    $LastTime = getdata($Turn, 'battle', 'timeout');
    $Delta = time() - $LastTime;

    //Если вышло время хода, то передаём ход оппоненту
    if ($Delta >= $TimeOut)
      if ($Login == $Turn)
        ToOpponent($Login, $Oppon);
      else
        ToOpponent($Oppon, $Login);
    
    //Конвертируем в обратный отсчёт
    $Last = $TimeOut - $Delta;
    if ($Last < 0)
      $Last = 0;

    //Получаем имя монстра, который ходит
    $Numb = getdata($Login, 'battle', 'attack');
    $Race = getdata($Login, 'hero', 'race');
    $Monster = query($Numb, $Race, 'addon');

    //Компановка сообщения
    $Message = "<font color=white><b>Ход: ".$Hero."<br>До передачи хода осталось ".$Last." секунд <br>";
    if ($Turn == $Login)
      $Message = $Message."Сейчас ход ".$Monster."</b></font>\n";

    //Конец строки
    echo($Message);
    echo("</td>\n");
    echo("</tr>\n");
  }

  //Запись строчки в ЛОГ файл
  function StringToLog($Login, $String)
  {
    //Получаем номер ЛОГ файла боя
    $Num = getdata($Login, 'battle', 'info');

    //Читаем файл
    $file = fopen("data/logs/".$Num.".log", "r");
    for ($i = 0; $i < 3; $i++)
      $Str[$i+1] = trim(fgets($file, 255));
    fclose($file);
  
    //Текущее время
    $tm = "<font color=yellow><b>(".date("<b>H:i:s</b>", time()).")</b></font>";

    //Вписываем в начало строчку
    $Str[0] = $String." ".$tm."<br>";

    //Сохраняем файл
    $file = fopen("data/logs/".$Num.".log", "w");
    for ($i = 0; $i < 3; $i++)
      fputs($file, $Str[$i]."\n");
    fclose($file);
  }

  //Расчёт атаки монстр на монстр
  function AttackWarrior($Level1, $Level2, $Login, $Oppon)
  {
    //Конвертируем уровень воина оппонента
    $Level2 = $Level2 - 4;

    //Моя раса и раса оппонента
    $MyRace = getdata($Login, 'hero', 'race');
    $OpRace = getdata($Oppon, 'hero', 'race');

    //Расчитываем урон, наносимый монстру
    $Arrows  = getdata($Login, 'war', 'arrow'.$Level1);
    if ($Arrows != 0)
    {
      //Считаем урон
      $Damage = query($Level1, $MyRace, 'archery');

      //Дополнительный урон от доп. способности
      $Damage = $Damage + $Damage*Level(25, $Login)*0.01;

      //Уменьшаем количество стрел
      $Arrows--;
      change($Login, 'war', 'arrow'.$Level1, $Arrows);
    }
    else
    {
      //Сам урон
      $Damage = query($Level1, $MyRace, 'power');

      //Дополнительный урон от доп. способности
      $Damage = $Damage + $Damage*Level(24, $Login)*0.01;
    }

    //Количество моих солдат
    $MyCount = getdata($Login, 'army', 'level'.$Level1);

    //Суммарный урон = урон*количество
    $FullDamage = $Damage * $MyCount;

    //Доп. способность стратег
    $FullDamage = $FullDamage + $FullDamage * 0.01 * Level(19, $Login);

    //Количество солдат оппонента
    $OpCount = getdata($Oppon, 'army', 'level'.$Level2);

    //Теперь вычисляем защиту оппонента
    $Protect = query($Level2, $OpRace, 'protect');
    $Health  = query($Level2, $OpRace, 'health');

    //Пересчитывем урон в расчёте на защиту
    $FullProtect = $Protect * $OpCount;

    //Доп. способность оборона
    $FullProtect = $FullProtect + $FullProtect * 0.01 * Level(19, $Oppon);

    //И наконец чистый урон войску
    $Full = $FullDamage - $FullProtect;
    if ($Full < 0)
      $Full = 0;

    //Чистый урон войску
    $PreFull = $FullDamage / $FullProtect;
    if ($PreFull < 0)
      $PreFull = 0;

    //Определяем здоровье выбранного отряда
    $Health     = query($Level2, $OpRace, 'health');
    $Additional = getdata($Oppon, 'war', 'health'.$Level2);
    $FullHealth = ($OpCount-1)*$Health + $Additional;

    //А теперь вычитаем из здоровья наш урон
    $FullHealth = $FullHealth - $Full;
    if ($FullHealth < 0)
      $FullHealth = 0;

    //Считаем сколько выжило...
    $Alive = 0;
    while($FullHealth >= $Health)
    {
      $Alive++;
      $FullHealth = $FullHealth - $Health;
    }
    
    //И сколько же погибло
    $Dead = $OpCount - $Alive;

    //Добавляем строчку в ЛОГ файл битвы
    $Fight  = query($Level1, $MyRace, 'name');
    $Defeat = query($Level2, $OpRace, 'addon');
    StringToLog($Login, "<font color=white>".$Fight." атакуют ".$Defeat.". ".$Dead." ".$Defeat." погибает</font>");

    //Передаём ход следующему монстру
    change($Oppon, 'army', 'level'.$Level2, $Alive);
    change($Oppon, 'war', 'health'.$Level2, $FullHealth);
    change($Login, 'battle', 'health', 0);
    moveto('fight.php');
  }

  //Вывод ЛОГ файла
  function PrintLog($Login)
  {
    $Number = getdata($Login, 'battle', 'info');
    readfile("data/logs/".$Number.".log");
  }
?>