<?
  include "functions.php";

  //�������� �������������
  if (empty($Login)||empty($Password))
  {
    echo "Blocked<br>\nError #1\n";
    exit();
  }

  //���� ������ ������������ ���� ����
  if (finduser($Login, $Password) == 0)
  {
    echo "Blocked<br>\nError #2\n";
    exit();
  }

  //���������� �� ������
  if (empty($Table)||empty($Field)||empty($AField)||empty($Value))
  {
    echo "Blocked<br>\nError #3\n";
    exit();
  }

  //��� �� ����������? ������ � ���� ��� ������ �� ��
  if (empty($Set))
  {
    //��������� ��������� �������
    $Query = "Get data from ".$Table." and field ".$Field." where another field (".$AField.") is equal to ".$Value;

    //������� ����� �������
    echo "Answer\n";

    //��������� ������
    $Data = getfrom($AField, $Value, $Table, $Field);
    echo $Data;
  }
  else
  {
    //���� �� �������� �����
    if (empty($Data))
    {
      echo "Blocked<br>\nError #4\n";
      exit();
    }

    //��������� ��������� �������
    $Query = "Set data in ".$Table." and field ".$Field." where another field (".$AField.") is equal to ".$Value;

    //������� ����� �������
    echo "Native Land Server reply<br>\n";
    echo "Query: ".$Query."<br>\n";
    echo "Data is: ".$Data."<br>\n";

    //��������� ������
    setto($AField, $Value, $Table, $Field, $Data);
  }
?>