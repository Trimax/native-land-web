<?
  include "functions.php";

  //Проверка безопастности
  if (empty($Login)||empty($Password))
  {
    echo "Blocked<br>\nError #1\n";
    exit();
  }

  //Если такого пользователя нетв базе
  if (finduser($Login, $Password) == 0)
  {
    echo "Blocked<br>\nError #2\n";
    exit();
  }

  //Правильный ли запрос
  if (empty($Table)||empty($Field)||empty($AField)||empty($Value))
  {
    echo "Blocked<br>\nError #3\n";
    exit();
  }

  //Что мы производим? Запись в базу или чтение из неё
  if (empty($Set))
  {
    //Формируем запросную строчку
    $Query = "Get data from ".$Table." and field ".$Field." where another field (".$AField.") is equal to ".$Value;

    //Выводим шапку запроса
    echo "Answer\n";

    //Формируем запрос
    $Data = getfrom($AField, $Value, $Table, $Field);
    echo $Data;
  }
  else
  {
    //Если не переданы даные
    if (empty($Data))
    {
      echo "Blocked<br>\nError #4\n";
      exit();
    }

    //Формируем запросную строчку
    $Query = "Set data in ".$Table." and field ".$Field." where another field (".$AField.") is equal to ".$Value;

    //Выводим шапку запроса
    echo "Native Land Server reply<br>\n";
    echo "Query: ".$Query."<br>\n";
    echo "Data is: ".$Data."<br>\n";

    //Формируем запрос
    setto($AField, $Value, $Table, $Field, $Data);
  }
?>