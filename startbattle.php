<title>Начало осады</title>
<link rel='stylesheet' type='text/css' href='style.css'/>
<body background='images/back.jpe'>
<?
  //Подключаем модуль функций
  include "battlemodule.php";

  //Проверка игрока
  $lg = trim($HTTP_COOKIE_VARS["nativeland"]);
  if ((hasuser($lg) == 0)||(trim($login) != trim($lg)))
    moveto("index.php");
  FromBattle($lg);

  //Правильно ли переданы данные
  if (  ( empty($login) ) || ( empty($oppon) ) || ( empty($type) )  )
    moveto('map.php');

  //А находимся ли мы в том же регионе, что и замок обороняющегося
  $rx = getdata($lg, 'coords', 'rx');
  $ry = getdata($lg, 'coords', 'ry');

  //Координаты столицы
  $cx = getdata($oppon, 'capital', 'rx');
  $cy = getdata($oppon, 'capital', 'ry');

  //Может мы на себя нападаем
  if ($lg == $oppon)
    messagebox("Вы же не намерены начинать осаду своего собственного замка?", "map.php");

  //Всё нормально
  if (($cx == $rx)&&($cy == $ry))
  {
  }
  else
    moveto("map.php");

  //Если наш оппонент охраняется северным союзом?
  $north = getdata($oppon, 'city', 'build13');
  if ($north == 0)
    messagebox("Было бы глупо нападать на это государство. Его суверинитет охраняет Великий Северный Союз...", "map.php");

  //Все данные переданы верно. Теперь определяем армию нападающего и обороняющегося
  $my_level1 = getdata($lg, 'army', 'level1');
  $my_level2 = getdata($lg, 'army', 'level2');
  $my_level3 = getdata($lg, 'army', 'level3');
  $my_level4 = getdata($lg, 'army', 'level4');

  //Не ноль ли у нас армии
  $summ = $my_level1 + $my_level2 + $my_level3 + $my_level4;

  //Если армии нет, то до свидания
  if ($summ == 0)
    messagebox("Да у Вас ведь совсем нет армии. Чем же Вы собирались держать осаду? Будьте рассудительнее в следующий раз...", "map.php");

  //Все проверки сделаны, можно начинать битву и перенаправлять туда игроков...
  change($lg, 'battles', 'battle', '1');
  change($lg, 'battles', 'opponent', $oppon);
  change($lg, 'battles', 'health', '2');
  change($oppon, 'battles', 'battle', '1');
  change($oppon, 'battles', 'opponent', $lg);
  change($oppon, 'battles', 'health', '1');

  //Устанавливаем дополнительные параметры в таблицу битвы персонажей
  change($lg, 'battle', 'timeout', time());      //Таймаут
  change($lg, 'battle', 'turn', $lg);            //Кто ходит
  change($lg, 'battle', 'attack', 1);            //Какое существо
  change($lg, 'battle', 'health', 3);            //Сколько у него ОД
  change($oppon, 'battle', 'timeout', time());
  change($oppon, 'battle', 'turn', $lg);
  change($oppon, 'battle', 'attack', 1); 
  change($oppon, 'battle', 'health', 3);            

  //Добавляем номер нового ЛОГа
  $file = fopen("data/count.dat", "r");
  $num = fgets($file, 255);
	fclose ($file);
	$num++;
	$file = fopen("data/count.dat", "w");
	fputs ($file, $num);
	fclose ($file);

  //Количество проведённых боёв увеличиваем на "1"
  $adm = getadmin();
  $btls = getfrom('admin', $adm, 'settings', 'f4');
  $btls++;
  setto('admin', $adm, 'settings', 'f4', $btls);

  //Создаём ЛОГ файл
	$file = fopen("data/logs/".$num.".log", "w");
	fclose ($file);

  //Количество боёв на один больше
  change($lg, 'time', 'combats', getdata($lg, 'time', 'combats')+1);
  change($oppon, 'time', 'combats', getdata($oppon, 'time', 'combats')+1);

  //Номер ЛОГ файла боя
  change($lg, 'battle', 'info', $num);
  change($oppon, 'battle', 'info', $num);

  //Координаты столицы
  $xc = getdata($oppon, 'capital', 'x');
  $yc = getdata($oppon, 'capital', 'y');

  //Определяем тип территории под столицей
  $file = fopen("maps/".$cx."x".$cy.".map", "r");
  for ($i = 1; $i < 11; $i++)
    for ($j = 1; $j < 11; $j++)
    {
      $temp = fgets($file);
      if (($i == $xc)&&($j == $yc))
        $fld = $temp;
    }
  fclose($file);

  //Определяем тип территории и запоминаем его
  $terr = $fld[0];

  //Тип территории в БД
  change($lg, 'battle', 'value', $terr);
  change($oppon, 'battle', 'value', $terr);

  //Распределяем войска
  PositionWarriors($lg, 0);
  PositionWarriors($oppon, 1);

  //Перенаправляем на экран битвы...
  moveto("fight.php");
?>