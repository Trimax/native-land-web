<title>Native Land</title>
<link rel='stylesheet' type='text/css' href='style.css'/>
<body background='../images/back.jpe'>
<?
  //По умолчанию установлена помощь по меню слева
  $filename = "menu.html";
  switch($index)
  {
    //Информация о персонаже
    case 1:
      $filename = "hero.html";
      break;
    //Экономика
    case 2:
      $filename = "economic.html";
      break;
    //Инвентарь
    case 3:
      $filename = "inventory.html";
      break;
    //Карта
    case 4:
      $filename = "map.html";
      break;
    //Строительство на карте
    case 5:
      $filename = "newcity.html";
      break;
    //Осада замка
    case 6:
      $filename = "castlefight.html";
      break;
    //Вызов на поединок
    case 7:
      $filename = "fight.html";
      break;
    //Строительство замка
    case 8:
      $filename = "build.html";
      break;
    //Послать гонца
    case 9:
      $filename = "message.html";
      break;
    //Регистрация клана
    case 10:
      $filename = "newclan.html";
      break;
    //Шпионаж
    case 11:
      $filename = "spy.html";
      break;     
    //Информация о кланах
    case 12:
      $filename = "clans.html";
      break;   
    //Набор армии
    case 13:
      $filename = "army.html";
      break;        
    //Рынок
    case 14:
      $filename = "trade.html";
      break;   
    //Кузница
    case 15:
      $filename = "armory.html";
      break;        
    //Храм
    case 16:
      $filename = "church.html";
      break;  
    //Банк
    case 17:
      $filename = "money.html";
      break;  
    //Гильдия №1
    case 18:
      $filename = "guild1.html";
      break;  
    //Гильдия №2
    case 19:
      $filename = "guild2.html";
      break;  
  }
  readfile($filename);
?>