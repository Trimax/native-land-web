<title>Статистика</title>
<link rel='stylesheet' type='text/css' href='style.css'/>
<body background='images/back.jpe'>
<center>
<h1>Игровая статистика</h1>
<?
  include "functions.php";

  //Сколько игроков по параметру
  function HowPlayers($table, $race, $fld)
  {
    $count = 0;
  	$ath = mysql_query("select * from ".$table.";");
	  if ($ath)
  		while ($rw = mysql_fetch_row($ath))
        if ($rw[$fld] == $race)
          $count++;
    return $count;
  }

  $lg = trim($HTTP_COOKIE_VARS["nativeland"]);  
  if (isadmin($lg))
  {
    //Всего зарегистрировавшихся
    $regged = getfrom('admin', $lg, 'settings', 'f2');

    //Получаем количество игроков в базе
    $cnt = mysql_query("select count(*) from users;");
    $total = mysql_fetch_array($cnt);

    //Всего удалённых
    $kicked = $regged - $total[0];
    $kicked_percent = 100*$kicked/$regged;

    //Всего игроков в базе
    $players = $regged - $kicked;
    $players = $total[0];
    $players_percent = 100*$players/$regged;

    //Проведено боёв
    $battles = getfrom('admin', $lg, 'settings', 'f4');

    //Сколько людей
    $peoples = HowPlayers('hero', 'people', 5);
    $peoples_percent = 100*$peoples/$players;

    //Сколько эльфов
    $elf = HowPlayers('hero', 'elf', 5);
    $elf_percent = 100*$elf/$players;

    //Сколько гномов
    $hnom = HowPlayers('hero', 'hnom', 5);
    $hnom_percent = 100*$hnom/$players;

    //Сколько друидов
    $druid = HowPlayers('hero', 'druid', 5);
    $druid_percent = 100*$druid/$players;

    //Сколько эльфов
    $necro = HowPlayers('hero', 'necro', 5);
    $necro_percent = 100*$necro/$players;

    //Сколько эльфов
    $hell = HowPlayers('hero', 'hell', 5);
    $hell_percent = 100*$hell/$players;

    //Сколько рыцарей
    $knights = HowPlayers('hero', 'knight', 6);
    $knights_percent = 100*$knights/$players;

    //Сколько стрелков
    $archer = HowPlayers('hero', 'archer', 6);
    $archer_percent = 100*$archer/$players;

    //Сколько магов
    $mag = HowPlayers('hero', 'mag', 6);
    $mag_percent = 100*$mag/$players;

    //Сколько лекарей
    $lekar = HowPlayers('hero', 'lekar', 6);
    $lekar_percent = 100*$lekar/$players;

    //Сколько волшебников
    $wizard = HowPlayers('hero', 'wizard', 6);
    $wizard_percent = 100*$wizard/$players;

    //Сколько варваров
    $barbar = HowPlayers('hero', 'barbarian', 6);
    $barbar_percent = 100*$barbar/$players;

    //Сколько металла
    $metal = HowPlayers('info', 'metal', 3);
    $metal_percent = 100*$metal/$players;

    //Сколько камня
    $rock = HowPlayers('info', 'rock', 3);
    $rock_percent = 100*$rock/$players;

    //Сколько дерева
    $wood = HowPlayers('info', 'wood', 3);
    $wood_percent = 100*$wood/$players;

    //Выводим результат
    echo("<table border=1 cellpadding=0 cellspacing=0 width=60%>");
    echo("<tr><td align=center width=25%>Параметр</tr><td align=center width=25%>Значение</td><td align=center>Диаграмма</td></tr>");
    echo("<tr><td colspan=3 align=center><b>Игроки</b></td></tr>");
    echo("<tr><td>Всего игроков</tr><td align=center colspan=2>$players</td></tr>");
    echo("<tr><td>Зарегистрированных</tr><td align=center>$regged</td><td align=center>");
      PBar($players_percent, 'blue');
    echo("</td></tr>");
    echo("<tr><td>Удалённых</tr><td align=center>$kicked</td><td align=center>");
      PBar($kicked_percent, 'blue');
    echo("</td></tr>");
    echo("<tr><td colspan=3 align=center><b>Битвы</b></td></tr>");
    echo("<tr><td>Проведено боёв</tr><td align=center colspan=2>$battles</td><td align=center>");
    echo("<tr><td colspan=3 align=center><b>Расы</b></td></tr>");
    echo("<tr><td>Люди</tr><td align=center>$peoples</td><td align=center>");
      PBar($peoples_percent, 'blue');
    echo("</td></tr>");
    echo("<tr><td>Эльфы</tr><td align=center>$elf</td><td align=center>");
      PBar($elf_percent, 'blue');
    echo("</td></tr>");
    echo("<tr><td>Гномы</tr><td align=center>$hnom</td><td align=center>");
      PBar($hnom_percent, 'blue');
    echo("</td></tr>");
    echo("<tr><td>Друиды</tr><td align=center>$druid</td><td align=center>");
      PBar($druid_percent, 'blue');
    echo("</td></tr>");
    echo("<tr><td>Нежить</tr><td align=center>$necro</td><td align=center>");
      PBar($necro_percent, 'blue');
    echo("</td></tr>");
    echo("<tr><td>Еретики</tr><td align=center>$hell</td><td align=center>");
      PBar($hell_percent, 'blue');
    echo("</td></tr>");
    echo("<tr><td colspan=3 align=center><b>Специализации</b></td></tr>");
    echo("<tr><td>Рыцари</tr><td align=center>$knights</td><td align=center>");
      PBar($knights_percent, 'blue');
    echo("</td></tr>");
    echo("<tr><td>Стрелки</tr><td align=center>$archer</td><td align=center>");
      PBar($archer_percent, 'blue');
    echo("</td></tr>");
    echo("<tr><td>Маги</tr><td align=center>$mag</td><td align=center>");
      PBar($mag_percent, 'blue');
    echo("</td></tr>");
    echo("<tr><td>Лекари</tr><td align=center>$lekar</td><td align=center>");
      PBar($lekar_percent, 'blue');
    echo("</td></tr>");
    echo("<tr><td>Волшебники</tr><td align=center>$wizard</td><td align=center>");
      PBar($wizard_percent, 'blue');
    echo("</td></tr>");
    echo("<tr><td>Варвары</tr><td align=center>$barbar</td><td align=center>");
      PBar($barbar_percent, 'blue');
    echo("</td></tr>");
    echo("<tr><td colspan=3 align=center><b>Основной ресурс</b></td></tr>");
    echo("<tr><td>Металл добывают</tr><td align=center>$metal</td><td align=center>");
      PBar($metal_percent, 'blue');
    echo("</td></tr>");
    echo("<tr><td>Камень добывают</tr><td align=center>$rock</td><td align=center>");
      PBar($rock_percent, 'blue');
    echo("</td></tr>");
    echo("<tr><td>Дерево добывают</tr><td align=center>$wood</td><td align=center>");
      PBar($wood_percent, 'blue');
    echo("</td></tr>");
    echo("</table>");
  } //Только для админа
?>

<h2>Статистика посещаемости</h2>
<?
  //Дата начала ведения статистики
  $dat = mysql_query("select UNIX_TIMESTAMP(min(putdate)) from stat_ip;");
  if ($dat)
  {
    $date = mysql_fetch_array($dat);
    echo("Дата начала регистрации данных: ".date("Y-m-d H:i:s", $date['UNIX_TIMESTAMP(min(putdate))'])."&nbsp;&nbsp;&nbsp;");
    printf("Прошло: %d дней", ((time()-$date['UNIX_TIMESTAMP(min(putdate))'])/3600/24));
  }

  /* СТРАНИЦЫ СЕРВЕРА */
  ?>
  </center>
  <p>Посещаемость страниц</p>
  <?

  //Номер текущей страницы
  if ($page == "")
    $page = 1;

  //Сортировка страниц
  if ($order == "")
    $orderstr = "name";
    else
    $orderstr = "num desc";
  
  //Отображаем все страницы, которые есть в таблице page
  $pgs = mysql_query("select stat_ip.id_page, stat_pages.name, count(stat_ip.id_ip) as num from stat_ip,stat_pages where stat_ip.id_page = stat_pages.id_page group by stat_ip.id_page order by $orderstr;");

  //Количество страниц, учавствющих в статистике
  $num = mysql_query("SELECT count(*) FROM stat_pages;");

  //Если запросы выполнены успешно, выводим результаты
  if ($pgs && $num)
  {
    $total = mysql_fetch_array($num);

    //Выводим таблицу с количеством посещений сайта
    echo ("<table border=1 width=100% cellpadding=0 cellspacing=0>");
    echo ("<tr><td>Страница</td><td>Количество посещений</td><td>Удалить из списка</td></tr>");
    while ($pag = mysql_fetch_array($pgs))
    {
      echo ("<tr><td><a href=statmain.php?id_page=".$pag['id_page'].">http://nld.spb.ru".$pag['name']."</a></td><td>".$pag['num']."</td><td><a href='statdelpage.php?page=".$pag['id_page']."'>Удалить</a></td></tr>");
    }
    echo ("</table>");
  }
?>
<p>Операционные системы, броузеры и поисковые машины</p>
<?
  GLOBAL $win;
  GLOBAL $lin;
  GLOBAL $mac;
  GLOBAL $os_oth;
  GLOBAL $ie;
  GLOBAL $opera;
  GLOBAL $netscape;
  GLOBAL $br_oth;
  GLOBAL $total;
  $total = 0;
  GLOBAL $stackrambler;
  $stackrambler = 0;
  GLOBAL $googlebot;
  $googlebot = 0;
  GLOBAL $yandex_bot;
  $yandex_bot = 0;
  GLOBAL $aport;
  $aport = 0;
  GLOBAL $other;
  $other = 0;
  GLOBAL $ftotal;
  $ftotal = 0;

  //Получаем информацию
  system_info(1, 0, $id_page);
  $win1      = $win;
  $lin1      = $lin;
  $mac1      = $mac;
  $os_oth1   = $os_oth;
  $ie1       = $ie;
  $opera1    = $opera;
  $netscape1 = $netscape;
  $br_oth1   = $br_oth;
  $total1    = $total;
  if ($total1 == 0)
    $total1 = 1;
  system_info(2, 0, $id_page);
  $win2      = $win;
  $lin2      = $lin;
  $mac2      = $mac;
  $os_oth2   = $os_oth;
  $ie2       = $ie;
  $opera2    = $opera;
  $netscape2 = $netscape;
  $br_oth2   = $br_oth;
  $total2    = $total;
  if ($total2 == 0)
    $total2 = 1;
  system_info(7, 0, $id_page);
  $win7      = $win;
  $lin7      = $lin;
  $mac7      = $mac;
  $os_oth7   = $os_oth;
  $ie7       = $ie;
  $opera7    = $opera;
  $netscape7 = $netscape;
  $br_oth7   = $br_oth;
  $total7    = $total;
  if ($total7 == 0)
    $total7 = 1;
  system_info(30, 0, $id_page);
  $win30     = $win;
  $lin30     = $lin;
  $mac30     = $mac;
  $os_oth30  = $os_oth;
  $ie30      = $ie;
  $opera30   = $opera;
  $netscape30= $netscape;
  $br_oth30  = $br_oth;
  $total30   = $total;
  if ($total30 == 0)
    $total30 = 1;
  system_info(0, 0, $id_page);
  $win0      = $win;
  $lin0      = $lin;
  $mac0      = $mac;
  $os_oth0   = $os_oth;
  $ie0       = $ie;
  $opera0    = $opera;
  $netscape0 = $netscape;
  $br_oth0   = $br_oth;
  $total0    = $total;
  if ($total0 == 0)
    $total0 = 1;

  //Информация по поисковикам
  robots(1, 0, $id_page);
  $googlebot1 = $googlebot;
  $yandex_bot1 = $yandex_bot;
  $aport1 = $aport;
  $other1 = $other;
  $ftotal1 = $ftotal;
  $stackrambler1 = $stackrambler;
  if ($ftotal1 == 0)
    $ftotal1 = 1;
  robots(2, 0, $id_page);
  $googlebot2 = $googlebot;
  $yandex_bot2 = $yandex_bot;
  $aport2 = $aport;
  $other2 = $other;
  $ftotal2 = $ftotal;
  $stackrambler2 = $stackrambler;
  if ($ftotal2 == 0)
    $ftotal2 = 1;
  robots(7, 0, $id_page);
  $googlebot7 = $googlebot;
  $yandex_bot7 = $yandex_bot;
  $aport7 = $aport;
  $other7 = $other;
  $ftotal7 = $ftotal;
  $stackrambler7 = $stackrambler;
  if ($ftotal7 == 0)
    $ftotal7 = 1;
  robots(30, 0, $id_page);
  $googlebot30= $googlebot;
  $yandex_bot30= $yandex_bot;
  $aport30= $aport;
  $other30= $other;
  $ftotal30= $ftotal;
  $stackrambler30= $stackrambler;
  if ($ftotal30 == 0)
    $ftotal30 = 1;
  robots(0, 0, $id_page);
  $googlebot0 = $googlebot;
  $yandex_bot0 = $yandex_bot;
  $aport0 = $aport;
  $other0 = $other;
  $ftotal0 = $ftotal;
  $stackrambler0 = $stackrambler;
  if ($ftotal0 == 0)
    $ftotal0 = 1;
?>
  <table border=1 width=100% cellspacing=0 cellpadding=0>
  <tr>
    <td>&nbsp;</td>
    <td>Сегодня</td>
    <td>Вчера</td>
    <td>Последняя неделя</td>
    <td>Последний месяц</td>
    <td>Всё время</td>
  </tr>
  <tr><td colspan=6><b>Операционные системы</b></td></tr>
  <tr>
    <td>Windows</td>
    <td><?php echo sprintf("%d (%01.1f%s)", $win1, $win1/$total1*100, '%'); ?></td>
    <td><?php echo sprintf("%d (%01.1f%s)", $win2, $win2/$total2*100, '%'); ?></td>
    <td><?php echo sprintf("%d (%01.1f%s)", $win7, $win7/$total7*100, '%'); ?></td>
    <td><?php echo sprintf("%d (%01.1f%s)", $win30, $win30/$total30*100, '%'); ?></td>
    <td><?php echo sprintf("%d (%01.1f%s)", $win0, $win0/$total0*100, '%'); ?></td>
  </tr>
  <tr>
    <td>Linux & Unix</td>
    <td><?php echo sprintf("%d (%01.1f%s)", $lin1, $lin1/$total1*100, '%'); ?></td>
    <td><?php echo sprintf("%d (%01.1f%s)", $lin2, $lin2/$total2*100, '%'); ?></td>
    <td><?php echo sprintf("%d (%01.1f%s)", $lin7, $lin7/$total7*100, '%'); ?></td>
    <td><?php echo sprintf("%d (%01.1f%s)", $lin30, $lin30/$total30*100, '%'); ?></td>
    <td><?php echo sprintf("%d (%01.1f%s)", $lin0, $lin0/$total0*100, '%'); ?></td>
  </tr>
  <tr>
    <td>MacOS</td>
    <td><?php echo sprintf("%d (%01.1f%s)", $mac1, $mac1/$total1*100, '%'); ?></td>
    <td><?php echo sprintf("%d (%01.1f%s)", $mac2, $mac2/$total2*100, '%'); ?></td>
    <td><?php echo sprintf("%d (%01.1f%s)", $mac7, $mac7/$total7*100, '%'); ?></td>
    <td><?php echo sprintf("%d (%01.1f%s)", $mac30, $mac30/$total30*100, '%'); ?></td>
    <td><?php echo sprintf("%d (%01.1f%s)", $mac0, $mac0/$total0*100, '%'); ?></td>
  </tr>
  <tr>
    <td>Другие</td>
    <td><?php echo sprintf("%d (%01.1f%s)", $os_oth1, $os_oth1/$total1*100, '%'); ?></td>
    <td><?php echo sprintf("%d (%01.1f%s)", $os_oth2, $os_oth2/$total2*100, '%'); ?></td>
    <td><?php echo sprintf("%d (%01.1f%s)", $os_oth7, $os_oth7/$total7*100, '%'); ?></td>
    <td><?php echo sprintf("%d (%01.1f%s)", $os_oth30, $os_oth30/$total30*100, '%'); ?></td>
    <td><?php echo sprintf("%d (%01.1f%s)", $os_oth0, $os_oth0/$total0*100, '%'); ?></td>
  </tr>
  <tr><td colspan=6><b>Броузеры</b></td></tr>
  <tr>
    <td>Internet explorer</td>
    <td><?php echo sprintf("%d (%01.1f%s)", $ie1, $ie1/$total1*100, '%'); ?></td>
    <td><?php echo sprintf("%d (%01.1f%s)", $ie2, $ie2/$total2*100, '%'); ?></td>
    <td><?php echo sprintf("%d (%01.1f%s)", $ie7, $ie7/$total7*100, '%'); ?></td>
    <td><?php echo sprintf("%d (%01.1f%s)", $ie30, $ie30/$total30*100, '%'); ?></td>
    <td><?php echo sprintf("%d (%01.1f%s)", $ie0, $ie0/$total0*100, '%'); ?></td>
  </tr>
  <tr>
    <td>Netscape navigator</td>
    <td><?php echo sprintf("%d (%01.1f%s)", $netscape1, $netscape1/$total1*100, '%'); ?></td>
    <td><?php echo sprintf("%d (%01.1f%s)", $netscape2, $netscape2/$total2*100, '%'); ?></td>
    <td><?php echo sprintf("%d (%01.1f%s)", $netscape7, $netscape7/$total7*100, '%'); ?></td>
    <td><?php echo sprintf("%d (%01.1f%s)", $netscape30, $netscape30/$total30*100, '%'); ?></td>
    <td><?php echo sprintf("%d (%01.1f%s)", $netscape0, $netscape0/$total0*100, '%'); ?></td>
  </tr>
  <tr>
    <td>Opera</td>
    <td><?php echo sprintf("%d (%01.1f%s)", $opera1, $opera1/$total1*100, '%'); ?></td>
    <td><?php echo sprintf("%d (%01.1f%s)", $opera2, $opera2/$total2*100, '%'); ?></td>
    <td><?php echo sprintf("%d (%01.1f%s)", $opera7, $opera7/$total7*100, '%'); ?></td>
    <td><?php echo sprintf("%d (%01.1f%s)", $opera30, $opera30/$total30*100, '%'); ?></td>
    <td><?php echo sprintf("%d (%01.1f%s)", $opera0, $opera0/$total0*100, '%'); ?></td>
  </tr>
  <tr>
    <td>Другие</td>
    <td><?php echo sprintf("%d (%01.1f%s)", $br_oth1, $br_oth1/$total1*100, '%'); ?></td>
    <td><?php echo sprintf("%d (%01.1f%s)", $br_oth2, $br_oth2/$total2*100, '%'); ?></td>
    <td><?php echo sprintf("%d (%01.1f%s)", $br_oth7, $br_oth7/$total7*100, '%'); ?></td>
    <td><?php echo sprintf("%d (%01.1f%s)", $br_oth30, $br_oth30/$total30*100, '%'); ?></td>
    <td><?php echo sprintf("%d (%01.1f%s)", $br_oth0, $br_oth0/$total0*100, '%'); ?></td>
  </tr>
  <tr><td colspan=6><b>Поисковые машины</b></td></tr>
  <tr>
    <td>Rambler</td>
    <td><?php echo sprintf("%d (%01.1f%s)", $stackrambler11, $stackrambler1/$ftotal1*100, '%'); ?></td>
    <td><?php echo sprintf("%d (%01.1f%s)", $stackrambler12, $stackrambler2/$ftotal2*100, '%'); ?></td>
    <td><?php echo sprintf("%d (%01.1f%s)", $stackrambler17, $stackrambler7/$ftotal7*100, '%'); ?></td>
    <td><?php echo sprintf("%d (%01.1f%s)", $stackrambler130, $stackrambler30/$ftotal30*100, '%'); ?></td>
    <td><?php echo sprintf("%d (%01.1f%s)", $stackrambler10, $stackrambler0/$ftotal0*100, '%'); ?></td>
  </tr>
  <tr>
    <td>Yandex</td>
    <td><?php echo sprintf("%d (%01.1f%s)", $yandex_bot1, $yandex_bot1/$ftotal1*100, '%'); ?></td>
    <td><?php echo sprintf("%d (%01.1f%s)", $yandex_bot2, $yandex_bot2/$ftotal2*100, '%'); ?></td>
    <td><?php echo sprintf("%d (%01.1f%s)", $yandex_bot7, $yandex_bot7/$ftotal7*100, '%'); ?></td>
    <td><?php echo sprintf("%d (%01.1f%s)", $yandex_bot30, $yandex_bot30/$ftotal30*100, '%'); ?></td>
    <td><?php echo sprintf("%d (%01.1f%s)", $yandex_bot0, $yandex_bot0/$ftotal0*100, '%'); ?></td>
  </tr>
  <tr>
    <td>Google</td>
    <td><?php echo sprintf("%d (%01.1f%s)", $googlebot1, $googlebot1/$ftotal1*100, '%'); ?></td>
    <td><?php echo sprintf("%d (%01.1f%s)", $googlebot2, $googlebot2/$ftotal2*100, '%'); ?></td>
    <td><?php echo sprintf("%d (%01.1f%s)", $googlebot7, $googlebot7/$ftotal7*100, '%'); ?></td>
    <td><?php echo sprintf("%d (%01.1f%s)", $googlebot30, $googlebot30/$ftotal30*100, '%'); ?></td>
    <td><?php echo sprintf("%d (%01.1f%s)", $googlebot0, $googlebot0/$ftotal0*100, '%'); ?></td>
  </tr>
  <tr>
    <td>Aport</td>
    <td><?php echo sprintf("%d (%01.1f%s)", $aport1, $aport1/$ftotal1*100, '%'); ?></td>
    <td><?php echo sprintf("%d (%01.1f%s)", $aport2, $aport2/$ftotal2*100, '%'); ?></td>
    <td><?php echo sprintf("%d (%01.1f%s)", $aport7, $aport7/$ftotal7*100, '%'); ?></td>
    <td><?php echo sprintf("%d (%01.1f%s)", $aport30, $aport30/$ftotal30*100, '%'); ?></td>
    <td><?php echo sprintf("%d (%01.1f%s)", $aport0, $aport0/$ftotal0*100, '%'); ?></td>
  </tr>
  <tr>
    <td>Другие</td>
    <td><?php echo sprintf("%d (%01.1f%s)", $other1, $other1/$ftotal1*100, '%'); ?></td>
    <td><?php echo sprintf("%d (%01.1f%s)", $other2, $other2/$ftotal2*100, '%'); ?></td>
    <td><?php echo sprintf("%d (%01.1f%s)", $other7, $other7/$ftotal7*100, '%'); ?></td>
    <td><?php echo sprintf("%d (%01.1f%s)", $other30, $other30/$ftotal30*100, '%'); ?></td>
    <td><?php echo sprintf("%d (%01.1f%s)", $other0, $other0/$ftotal0*100, '%'); ?></td>
  </tr>
  </table>
  <?
    //Функция составляет информацию о системах и броузерах
    function system_info($begin, $end, $id_page)
    {
      GLOBAL $win;
      GLOBAL $lin;
      GLOBAL $mac;
      GLOBAL $os_oth;
      GLOBAL $ie;
      GLOBAL $opera;
      GLOBAL $netscape;
      GLOBAL $br_oth;
      GLOBAL $total;

      //Запрос по всему сайту или странице
      if ($id_page == "")
        $tmp = "";
      else
        $tmp = "id_page=$id_page";

      //Временной интервал
      if ($begin == 0)
        $tmp2 = "";
      else
        $tmp2 = "putdate>=date_sub(date_format(now(), '%Y-%m-%d 23:59:59'), interval $begin day)";
      $tmp1 = "putdate<=date_sub(date_format(now(), '%Y-%m-%d 23:59:59'), interval $end day)";

      //Расставляем "and" в запросе
      if ($tmp2 != "" || $tmp != "") $and1 = " and ";
      if ($tmp2 != "" && $tmp != "") $and2 = " and ";
      if ($tmp1 != "") $and3 = " and ";

      //Формируем запросы
      //Засчитанные хиты
      $query_win = "select count(*) from stat_ip where system = 1".$and3.$tmp1.$and1.$tmp2.$and2.$tmp.";";
      $query_lin = "select count(*) from stat_ip where system = 2".$and3.$tmp1.$and1.$tmp2.$and2.$tmp.";";
      $query_mac = "select count(*) from stat_ip where system = 3".$and3.$tmp1.$and1.$tmp2.$and2.$tmp.";";
      $query_oth = "select count(*) from stat_ip where system = 0".$and3.$tmp1.$and1.$tmp2.$and2.$tmp.";";

      //Общее число хитов
      $query_ie = "select count(*) from stat_ip where browser = 1".$and3.$tmp1.$and1.$tmp2.$and2.$tmp.";";
      $query_op = "select count(*) from stat_ip where browser = 2".$and3.$tmp1.$and1.$tmp2.$and2.$tmp.";";
      $query_nv = "select count(*) from stat_ip where browser = 3".$and3.$tmp1.$and1.$tmp2.$and2.$tmp.";";
      $query_ot = "select count(*) from stat_ip where browser = 0".$and3.$tmp1.$and1.$tmp2.$and2.$tmp.";";

      //Осуществляем запросы
      $winq = mysql_query($query_win);
      $linq = mysql_query($query_lin);
      $macq = mysql_query($query_mac);     
      $othq = mysql_query($query_oth);     
      $ieq  = mysql_query($query_ie);
      $oprq = mysql_query($query_op);
      $netq = mysql_query($query_nv);     
      $nthq = mysql_query($query_ot);     

      //Если всё сработало, обрабатываем
      if ($winq && $linq && $macq && $othq && $ieq && $oprq && $netq && $nthq)
      {
        $wina = mysql_fetch_array($winq);
        $lina = mysql_fetch_array($linq);
        $maca = mysql_fetch_array($macq);
        $otha = mysql_fetch_array($othq);
        $iea  = mysql_fetch_array($ieq);
        $opra = mysql_fetch_array($oprq);
        $neta = mysql_fetch_array($netq);
        $ntha = mysql_fetch_array($nthq);

        //Заносим данные в глобальные переменные
        $win = $wina['count(*)'];
        $lin = $lina['count(*)'];
        $mac = $maca['count(*)'];
        $os_oth = $otha['count(*)'];
        $total = $os_oth + $mac + $lin + $win;
        $ie = $iea['count(*)'];
        $opera = $opra['count(*)'];
        $netscape = $neta['count(*)'];
        $br_oth = $ntha['count(*)'];
        $ftotal = $other + $aport + $yandex_bot + $googlebot + $stackrambler;
        if ($ftotal == 0)
          $ftotal = 1;
      }
      else
        echo("Error<br>");
    }

    //Заносим информацию о поисковиках
    function robots($begin, $end, $id_page)
    {
      GLOBAL $stackrambler;
      $stackrambler = 0;
      GLOBAL $googlebot;
      $googlebot = 0;
      GLOBAL $yandex_bot;
      $yandex_bot = 0;
      GLOBAL $aport;
      $aport = 0;
      GLOBAL $other;
      $other = 0;
      GLOBAL $ftotal;
      $ftotal = 0;

      //Запрос по всему сайту или странице
      if ($id_page == "")
        $tmp = "";
      else
        $tmp = "id_page=$id_page";

      //Временной интервал
      if ($begin == 0)
        $tmp2 = "";
      else
        $tmp2 = "putdate>=date_sub(date_format(now(), '%Y-%m-%d 23:59:59'), interval $begin day)";
      $tmp1 = "putdate<=date_sub(date_format(now(), '%Y-%m-%d 23:59:59'), interval $end day)";

      //Расставляем "and" в запросе
      if ($tmp2 != "" || $tmp != "") $and1 = " and ";
      if ($tmp2 != "" && $tmp != "") $and2 = " and ";

      //Формируем запросы и исполняем их
      $query_ynd = "select count(*) from stat_ip where system=6 and ".$tmp1.$and1.$tmp2.$and2.$tmp.";";
      $ynd = mysql_query($query_ynd);
      $query_ram = "select count(*) from stat_ip where system=4 and ".$tmp1.$and1.$tmp2.$and2.$tmp.";";
      $ram = mysql_query($query_ram);
      $query_gog = "select count(*) from stat_ip where system=5 and ".$tmp1.$and1.$tmp2.$and2.$tmp.";";
      $gog = mysql_query($query_gog);
      $query_apt = "select count(*) from stat_ip where system=7 and ".$tmp1.$and1.$tmp2.$and2.$tmp.";";
      $apt = mysql_query($query_apt);
      $query_oth = "select count(*) from stat_ip where system=0 and ".$tmp1.$and1.$tmp2.$and2.$tmp.";";
      $oth = mysql_query($query_oth);

      //Если всё прошло удачно
      if ($ynd && $ram && $gog && $apt && $oth)
      {
        $rama = mysql_fetch_array($ram);
        $goga = mysql_fetch_array($gog);
        $apta = mysql_fetch_array($apt);
        $otha = mysql_fetch_array($oth);
        $ynda = mysql_fetch_array($ynd);

        //Возврат информации
        $stackrambler = $rama['count(*)'];
        $yandex_bot   = $ynda['count(*)'];
        $googlebot    = $goga['count(*)'];
        $aport        = $apta['count(*)'];
        $other        = $otha['count(*)'];
      }
    }
  ?>
  <p>Ссылающиеся страницы</p>
  <?
    $return_array = array();
    $reffers = array();
    $is_reff = false;
    $ref = mysql_query("select name from stat_links order by name");
    if ($ref)
    {
      $num = 0;
      while ($reff = mysql_fetch_array($ref))
      {
        $is_reff = true;
        $reffers[$num++] = $reff['name'];
      }
    }

    //Извлекаем данные
    refferers(1, 0, $id_page);
    $ar1 = $return_array;
    refferers(2, 0, $id_page);
    $ar2 = $return_array;
    refferers(7, 0, $id_page);
    $ar7 = $return_array;
    refferers(30, 0, $id_page);
    $ar30 = $return_array;
    refferers(0, 0, $id_page);
    $ar0 = $return_array;

    //Таблица с информацией
    echo ("<table border=1 cellpadding=0 cellspacing=0 width=100%>");
    ?>
      <tr>
        <td align=center>Реферер</td>
        <td align=center>Сегодня</td>
        <td align=center>Вчера</td>
        <td align=center>Последняя неделя</td>
        <td align=center>Последний месяц</td>
        <td align=center>Всё время</td>
      </tr>
    <?
    while($tmp = current($reffers))
    {
      echo("<tr><td>&nbsp;".$tmp."</td><td>&nbsp;".$ar1[$tmp]."</td><td>&nbsp;".$ar2[$tmp]."</td><td>&nbsp;".$ar7[$tmp]."</td><td>&nbsp;".$ar30[$tmp]."</td><td>&nbsp;".$ar0[$tmp]."</td></tr>");
      next($reffers);
    }
    echo("</table>");
  ?>
  <p>
  <form action=statadd.php method=post>
  <input type=text name=reff style='background:#000099; font-WEIGHT: BOLD; COLOR: white; cursor: hand; list-style: lower-alpha; filter: Alpha(Opacity=20, FinishOpacity=100, Style=3)'>
  <input type=submit value=Добавить style='background:#000099; font-WEIGHT: BOLD; COLOR: white; cursor: hand; list-style: lower-alpha; filter: Alpha(Opacity=20, FinishOpacity=100, Style=3)'>
  </form>
  <?
  //Если есть страницы
  if($is_reff) 
  {
    $ref = mysql_query("select * from stat_links;");
    if ($ref)
    {
      ?>
        <form action="statdel.php" method=get>
        <select type=text name=id_links style='background:#000099; font-WEIGHT: BOLD; COLOR: white; cursor: hand; list-style: lower-alpha; filter: Alpha(Opacity=20, FinishOpacity=100, Style=3)'>
        <?
          while($reff = mysql_fetch_array($ref))
          {
            echo "<option value=".$reff['id_links'].">".$reff['name'];
          }
        ?>
        </select>
        <input type=submit value=Удалить style='background:#000099; font-WEIGHT: BOLD; COLOR: white; cursor: hand; list-style: lower-alpha; filter: Alpha(Opacity=20, FinishOpacity=100, Style=3)'>
        </form>
      <?
    }
  }
  ?>
  </p>

  <?
  //Определение реферера
  function refferers($begin, $end, $id_page)
  {
    GLOBAL $return_array;
    $reffers = array();
    $ref = mysql_query("select name from stat_links order by name;");
    if ($ref)
    {
      $num = 0;
      while($reff = mysql_fetch_array($ref))
        $reffers[$num++] = $reff['name'];
    }

    //Запрос по всему сайту или странице
    if ($id_page == "")
      $tmp = "";
    else
      $tmp = "id_page=$id_page";

    //Временной интервал
    if ($begin == 0)
      $tmp2 = "";
    else
      $tmp2 = "putdate>=date_sub(date_format(now(), '%Y-%m-%d 23:59:59'), interval $begin day)";
    $tmp1 = "putdate<=date_sub(date_format(now(), '%Y-%m-%d 23:59:59'), interval $end day)";

    //Расставляем "and" в запросе
    if ($tmp2 != "" || $tmp != "") $and1 = " and ";
    if ($tmp2 != "" && $tmp != "") $and2 = " and ";

    //Формируем запрос
    $query = "select name,putdate,id_page from stat_refferer where search>=0 and ".$tmp1.$and1.$tmp2.$and2.$tmp.";";
    $ips = mysql_query($query);
    if ($ips)
    {
      while ($ip = mysql_fetch_array($ips))
      {
        if (strpos($ip['name'], $tmp) != NULL)
        {
          if (array_key_exists($tmp, $return_array))
            $return_array[$tmp]++;
          else
            $return_array[$tmp] = 1;
        } //!= NULL
        next($reffers);
      } //while
      reset($reffers);
    } //if $ips
  } //function
  ?>
<p>IP адреса посетителей</p>
<?
  //Страницы по 50 штук
  if ($page == "")
    $page = 1;
  $pnumber = 50;

  $begin = ($page - 1)*$pnumber;
  if ($id_page != "")
  {
    $tmp  = " and id_page=".$id_page;
    $tmp1 = " and id_page=".$id_page;
  }
  else
  {
    $tmp  = "";
    $tmp1 = "";
  }

  //Запрос
  $query = "select distinct(ip), max(putdate) as putdate from stat_ip where putdate>now() - interval 1 day ".$tmp." group by ip order by putdate desc limit $begin, $pnumber;";
  $ips = mysql_query($query);
  $query = "select count(distinct ip) from stat_ip where putdate >now() - interval 1 day ".$tmp.";";
  $num = mysql_query($query);
  if ($ips && $num)
  {
    $total = mysql_fetch_array($num);
    $number = (int)($total['count(distinct ip)']/$pnumber);
    if ((float)($total['count(distinct ip)']/$pnumber)-$number != 0)
      $number++;
    for ($i = 1; $i <= $number; $i++)
    {
      if ($number == $i)
      {
        if ($page == $i)
          echo "[".(($i-1)*$pnumber + 1)."-".$total['count(distinct ip)']."]";
        else
          echo "<a href=statistic.php?id_page=".$id_page."&page=".$i.">[".(($i-1)*$pnumber + 1)."-".$total['count(distinct ip)']."]</a>";
      }
      else
      {
        if ($page == $i)
          echo "[".(($i-1)*$pnumber + 1)."-".$i*$pnumber."]";
        else
          echo "<a href=statistic.php?id_page=".$id_page."&page=".$i.">[".(($i-1)*$pnumber + 1)."-".$i*$pnumber."]</a>";
      }
    } //for

    //Выводим IP адреса
    echo "<table border=1 cellspacing=0 cellpadding=0 width=100%>";
    echo "<tr><td>IP адрес</td><td>Хост</td><td>Всего обращений</td><td>Последнее обращение</td></tr>";
    while ($ip = mysql_fetch_array($ips))
    {
      $pos = strpos($ip['ip'], "/");
      switch ($pos)
      {
        case (NULL):
          echo "<tr><td>".$ip['ip']."</td><td>".gethostbyaddr($ip['ip'])."</td>";
          break;
        default:
          echo "<tr><td>".$ip['ip']."</td><td>".gethostbyaddr(substr($ip['ip'], 0, $pos))."</td>";
          break;
      } //switch

      //Запрос
      $query = "select count(*), max(putdate) from stat_ip where ip='".$ip['ip']."' ".$tmp1.";";
      $nnum = mysql_query($query);
      if ($nnum)
      {
        $nnumber = mysql_fetch_array($nnum);
        echo "<td>".$nnumber['count(*)']."</td><td>".$nnumber['max(putdate)']."</td>";
      } //nnum
    } //while
    echo "</table>";
  } //if all ok
?>