<title>Native Land</title>
<link rel='stylesheet' type='text/css' href='style.css'/>
<body background='../images/back.jpe'>
<?
  //�� ��������� ����������� ������ �� ���� �����
  $filename = "menu.html";
  switch($index)
  {
    //���������� � ���������
    case 1:
      $filename = "hero.html";
      break;
    //���������
    case 2:
      $filename = "economic.html";
      break;
    //���������
    case 3:
      $filename = "inventory.html";
      break;
    //�����
    case 4:
      $filename = "map.html";
      break;
    //������������� �� �����
    case 5:
      $filename = "newcity.html";
      break;
    //����� �����
    case 6:
      $filename = "castlefight.html";
      break;
    //����� �� ��������
    case 7:
      $filename = "fight.html";
      break;
    //������������� �����
    case 8:
      $filename = "build.html";
      break;
    //������� �����
    case 9:
      $filename = "message.html";
      break;
    //����������� �����
    case 10:
      $filename = "newclan.html";
      break;
    //�������
    case 11:
      $filename = "spy.html";
      break;     
    //���������� � ������
    case 12:
      $filename = "clans.html";
      break;   
    //����� �����
    case 13:
      $filename = "army.html";
      break;        
    //�����
    case 14:
      $filename = "trade.html";
      break;   
    //�������
    case 15:
      $filename = "armory.html";
      break;        
    //����
    case 16:
      $filename = "church.html";
      break;  
    //����
    case 17:
      $filename = "money.html";
      break;  
    //������� �1
    case 18:
      $filename = "guild1.html";
      break;  
    //������� �2
    case 19:
      $filename = "guild2.html";
      break;  
  }
  readfile($filename);
?>