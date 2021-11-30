<?
  include "functions.php";

  //Фон и стиль
  echo ("<html>\n<head>\n<link rel='stylesheet' type='text/css' href='style.css'/>\n<title>Native Land</title>\n</head>\n<body background='images\back.jpe'>");
  
  $lg = trim($HTTP_COOKIE_VARS["nativeland"]);
  if (hasuser($lg) == 0)
    exit();

  //Добавление пользователя в клан
  function addusertoclan($login, $clan)
  {
  	mysql_query ("insert into inclan values ('$login', '$clan', '0' , '0');");
  }

  //Обычный поиск пользователя
  function inbases($username)
  {
    //Может он уже в клане
    $usr = mysql_query("select * from inclan;");
    $find = 0;
    if ($usr)
      while ($user = mysql_fetch_array($usr))
      if (($user['login'] == $username))
        $find = 1;
    else
      $find = 2;

    //Может он сам админ
    $usr = mysql_query("select * from clans;");
    if ($usr)
      while ($user = mysql_fetch_array($usr))
      if (($user['login'] == $username))
        $find = 1;
    else
      $find = 2;
    return $find;
  }

  //Кого мы принимаем:
  $login = $member;

  //Если пользователь уже в клане, или сам админ, то нельзя
  if (inbases($login) != 0)
    moveto("game.php?action=19");

  //Проверяем количество денег у того, кто вступает
  $money = getdata($login, 'economic', 'money');
  $curse = getdata($login, 'economic', 'curse');

  //Получаем налог для соклановцев
  $nalog = getdata($admin, 'clans', 'nalog');
  $summa = $nalog*$curse;

  //Вычитаем деньги
  $money = $money - $summa;
  if ($money < 0)
    $money = 0;

  //Вычитаем деньги у вступившего
  change($login, 'economic', 'money', $money);

  //Переводим остатки в основные единицы
  $money = round($money / $curse);

  //Добавляем эти деньги на счёт клана
  $bill = getdata($admin, 'clans', 'bill');
  change($admin, 'clans', 'bill', $bill + $money);

  //Что хоть за клан?
  $clan = getdata($admin, 'clans', 'name');

  //Записываем игрока в клан
  addusertoclan($login, $clan);

  //Узнаём имя игрока
  $heroname = getdata($login, 'hero', 'name');

  //И наконец сообщаем об этом самому принятому
  sms($login, "Администрация клана ".$clan, "Уважаемый ".$heroname." (".$login."), Вы приняты в наш клан. Добро пожаловать!");
 
  //Перенаправляем администратора назад
  messagebox("Вы успешно приняли в клан игрока ".$heroname." (".$login.")", "game.php?action=19");
?>