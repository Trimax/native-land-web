<?
  include "functions.php";

  //���� �� ������� ��� ������������, �� �������� ����� �����
  $lg = trim($HTTP_COOKIE_VARS["nativeland"]);
  $pw = trim($HTTP_COOKIE_VARS["password"]);

  //����������?
  if (finduser($lg, $pw) != 1)
  {
	  moveto('index.php');
  	exit();
  }
  
  //��� � �����
  echo ("<html>\n<head>\n<link rel='stylesheet' type='text/css' href='style.css'/>\n<title>Native Land</title>\n</head>\n<body background='images\back.jpe'>");

  //�������� �� ������ ������ ������
  function tempcut($s)
  {
    $ns = "";
    for ($i = 1; $i < strlen($s); $i++)
      $ns = $ns.$s[$i];
    return $ns;
  }

  //����������� ���. �����������
  function Ablt($Login, $Number)
  {
    //��������� ������
    $Txt = "<td align=center width=25%>";

    //�������� ���. ����������� �� ������ ������
    $num = getdata($Login, 'newchar', 'achar'.$Number);
    $lvl = $num[0];
    $num = tempcut($num);

    //���� �� �����������?
    if ($lvl != '0')
    {
      //�� ����� ��� �����
      switch ($lvl)
      {
        case 'N':
          $alevel = 1;
          $tlevel = "������� ";
          break;
        case 'A':
          $alevel = 2;
          $tlevel = "����������� ";
          break;
        case 'E':
          $alevel = 3;
          $tlevel = "������� ";
          break;
      }

      //������ ��������
      $img = getfrom('num', $num, 'additional', 'img');
      $img = "images/newchar/".$img."/".$alevel.".jpg";

      //������ ��������
      $desc = getfrom('num', $num, 'additional', 'desc'.$alevel);
      $name = getfrom('num', $num, 'additional', 'name');

      //��������� ���������
      $tlevel = $tlevel.$name.". ".$desc;

      //��������� ������
      $Txt = $Txt."<img src='$img' alt='$tlevel'>";
    }
      else
    {
        $Txt = $Txt."<img src='images/empty.jpg'>";
    }

    //������� ��������
    $Txt = $Txt."</td>";
    return $Txt;
  }

  //������� ���. ��������������
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