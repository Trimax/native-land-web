<?
  include "functions.php";

  //Если не указано имя пользователя, то выкинуть юзера нафиг
  $lg = trim($HTTP_COOKIE_VARS["nativeland"]);
  $pw = trim($HTTP_COOKIE_VARS["password"]);

  //Залогигнен?
  if (finduser($lg, $pw) != 1)
  {
	  moveto('index.php');
  	exit();
  }
  
  //Фон и стиль
  echo ("<html>\n<head>\n<link rel='stylesheet' type='text/css' href='style.css'/>\n<title>Native Land</title>\n</head>\n<body background='images\back.jpe'>");

  //Вырезает из строки первый символ
  function tempcut($s)
  {
    $ns = "";
    for ($i = 1; $i < strlen($s); $i++)
      $ns = $ns.$s[$i];
    return $ns;
  }

  //Отображение доп. способности
  function Ablt($Login, $Number)
  {
    //Очередная ячейка
    $Txt = "<td align=center width=25%>";

    //Получаем доп. способность из данной клетки
    $num = getdata($Login, 'newchar', 'achar'.$Number);
    $lvl = $num[0];
    $num = tempcut($num);

    //Есть ли способность?
    if ($lvl != '0')
    {
      //На каком она уроне
      switch ($lvl)
      {
        case 'N':
          $alevel = 1;
          $tlevel = "Новичок ";
          break;
        case 'A':
          $alevel = 2;
          $tlevel = "Продвинутый ";
          break;
        case 'E':
          $alevel = 3;
          $tlevel = "Эксперт ";
          break;
      }

      //Достаём картинку
      $img = getfrom('num', $num, 'additional', 'img');
      $img = "images/newchar/".$img."/".$alevel.".jpg";

      //Достаём описание
      $desc = getfrom('num', $num, 'additional', 'desc'.$alevel);
      $name = getfrom('num', $num, 'additional', 'name');

      //Компануем подсказку
      $tlevel = $tlevel.$name.". ".$desc;

      //Компануем ячейку
      $Txt = $Txt."<img src='$img' alt='$tlevel'>";
    }
      else
    {
        $Txt = $Txt."<img src='images/empty.jpg'>";
    }

    //Возврат значения
    $Txt = $Txt."</td>";
    return $Txt;
  }

  //Выводим доп. характеристики
  echo("<table border=0 width=10% CELLSPACING=0 CELLPADDING=0>");
  echo("<tr>");
  for ($i = 1; $i <= 4; $i++)
    echo(Ablt($login, $i));
  echo("</tr>");
  echo("<tr>");
  for ($i = 5; $i <= 8; $i++)
    echo(Ablt($login, $i));
  echo("</tr>");
  echo("<tr>");
  for ($i = 9; $i <= 12; $i++)
    echo(Ablt($login, $i));
  echo("</tr>");
  echo("<tr>");
  for ($i = 13; $i <= 16; $i++)
    echo(Ablt($login, $i));
  echo("</tr>");
  echo("</table>");
  echo("</center>");
?>