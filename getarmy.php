<title>Набор армии</title>
<link rel='stylesheet' type='text/css' href='style.css'/>
<body background='images/back.jpe'>
<?
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

//Вывод строки с информацией о монстре
function AddRow($Level, $Race, $Login)
{
  //Получаем информацию о персонаже
    //Картинка
    $img = query($Level, $Race, 'img');
    //Название
    $name = query($Level, $Race, 'name');
    //Здоровье
    $health = query($Level, $Race, 'health');
    //Ближний бой
    $near = query($Level, $Race, 'power');
    //Защита
    $protect = query($Level, $Race, 'protect');
    //Дальний бой
    $far = query($Level, $Race, 'archery');
    //Количество стрел
    $arrows = query($Level, $Race, 'arrows');
    //Род. падеж
    $absent = query($Level, $Race, 'addon');
    //Сколько доступно
    $temp = $Level+1;
    $has = getdata($Login, 'unions', 'login'.$temp);
    //Цена
    $curse = getdata($Login, 'economic', 'curse');
    switch($Level)
    {
      case 1:
        $k = 1;
        break;
      case 2:
        $k = 3;
        break;
      case 3:
        $k = 6;
        break;
      case 4:
        $k = 15;
        break;
    }
    $cena = $k*$curse;

  //Выводим строку
  echo("<tr>");
  echo("<td align=center><img src='images/warriors/".$img.".jpg' width=150 height=200 alt='".$name."'></td>");
  echo("<td align=center>");
  ?>
    <table border=1 cellpadding=0 cellspacing=0 width=100%>
    <?
      echo("<tr><td colspan=2 align=center>".$name."</tr>");
      echo("<tr><td align=center>Параметр</td><td align=center>Значение</td></tr>");
      echo("<tr><td align=center>Здоровье</td><td align=center>".$health."</td></tr>");
      echo("<tr><td align=center>Ближний бой</td><td align=center>".$near."</td></tr>");
      echo("<tr><td align=center>Защита</td><td align=center>".$protect."</td></tr>");
      echo("<tr><td align=center>Стрельба</td><td align=center>".$far."</td></tr>");
      echo("<tr><td align=center>Запас стрел</td><td align=center>".$arrows."</td></tr>");
      echo("<tr><td align=center>Доступно</td><td align=center>".$has."</td></tr>");
      echo("<tr><td align=center>Цена</font></td><td align=center>".$cena."</td></tr>");
    ?>
    </table>
  <?
  echo("</td>");
  echo("<td align=center>");
  ?>
    <br>
    <form action='getarmy.php' method=post>
    Количество:
    <?
      echo("<input type='hidden' name='login' style='background:#000099; font-WEIGHT: BOLD; COLOR: white; cursor: hand; list-style: lower-alpha; filter: Alpha(Opacity=20, FinishOpacity=100, Style=3)' value='".$lg."'>");
      echo("<input type='hidden' name='Level' style='background:#000099; font-WEIGHT: BOLD; COLOR: white; cursor: hand; list-style: lower-alpha; filter: Alpha(Opacity=20, FinishOpacity=100, Style=3)' value='".$Level."'>");
    ?>
    <input type='hidden' name='take' value=1>
    <input type='text' name='how' style='background:#000099; font-WEIGHT: BOLD; COLOR: white; cursor: hand; list-style: lower-alpha; filter: Alpha(Opacity=20, FinishOpacity=100, Style=3)'>
    <input type='submit' value='Нанять' style='background:#000099; font-WEIGHT: BOLD; COLOR: white; cursor: hand; list-style: lower-alpha; filter: Alpha(Opacity=20, FinishOpacity=100, Style=3)'>
    </form>
  <?
  echo("</td>");
  echo("</tr>");
}

$lg = trim($HTTP_COOKIE_VARS["nativeland"]);
if (hasuser($lg) == 0) 
  exit();
FromBattle($lg);

//Наём армии
if ($take == 1)
{
  //Сброс флажка
  $take = 0;

  //Преобразуем переданное количество
  $temp = $Level+1;
  $max = getdata($lg, 'unions', 'login'.$temp);
  if ($how < 0)
    $how = 0;
  if ($how > $max)
    $how = $max;

  //Если количество 0 - нет смысла
  if ($how == 0)
    moveto("getarmy.php?login=".$lg);

  //Расчитываем сумму
  $Curse = getdata($lg, 'economic', 'curse');
  switch($Level)
  {
    case 1:
      $k = 1;
      break;
    case 2:
      $k = 3;
      break;
    case 3:
      $k = 6;
      break;
    case 4:
      $k = 15;
      break;
  }
  $summ = $k*$Curse*$how;
  $money = getdata($lg, 'economic', 'money');
  $mn = getdata($lg, 'economic', 'moneyname'); 

  //А денег хватает
  if ($summ > $money)
    messagebox("У Вас недостаточно денег для найма. Вам необходимо ".$summ." ".$mn, "getarmy.php?login=".$lg);

  //Вычитаем деньги
  change($lg, 'economic', 'money', $money-$summ);

  //Убираем армию из слотов для найма
  change($lg, 'unions', 'login'.$temp, $max-$how);

  //Добавляем армию к нашей
  $army = getdata($lg, 'army', 'level'.$Level);
  change($lg, 'army', 'level'.$Level, $army+$how);

  //Сообщение об успехе
  $Race = getdata($lg, 'hero', 'race');
  $who = query($Level, $Race, 'addon');
  messagebox("На службу в Вашу армию было рекрутировано ".$how." ".$who, "getarmy.php?login=".$lg);  
}

//Выводим табличку...
?>
<center>
  <h1>Рекрутирование армии</h1>
  <?
  HelpMe(13, 0);
  ?>
  <table border=1 cellpadding=0 cellspacing=0 width=90%>
  <tr>
    <td align=center width=5%>Воин</td><td align=center>Характеристики</td><td align=center width=5%>Набор</td>
  </tr>
  <?
    //Получаем расу персонажа
    $race = getdata($lg, 'hero', 'race');

    //Выводим табличку с воинами
    AddRow(1, $race, $lg);
    AddRow(2, $race, $lg);
    AddRow(3, $race, $lg);
    AddRow(4, $race, $lg);

    //Сколько у нас ресурсов
    $money = getdata($lg, 'economic', 'money');
    echo("<tr><td colspan=3 align=center><b>У Вас денег: $money <img src='images/menu/gold.gif'></b></td></tr>");
  ?>
  </table>
  <?
    echo("<a href='city.php?login=".$lg."'>Назад в город</a>");
  ?>
</center>