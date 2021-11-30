<?
  /* ������� ��� ������ � ������ */
  include "functions.php";

  //��������� ������ �� ������� �� ����� ����, ����� ������������ � ����� �������
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

  //���������������� ����� ����� ������
  function PositionWarriors($Login, $Side)
  {
    /* ���������������� ������ �� ���� ����� 
    #########################################
    #   #   #   #   #   #   #   # * #   #   #
    #########################################
    # 1 #   #   #   #   #   #   # * #   # 1 #
    #########################################
    # 2 #   #   #   #   #   #   # * #   # 2 #
    #########################################
    #   #   #   #   #   #   #   #   #   #   #
    #########################################
    # 3 #   #   #   #   #   #   # * #   # 3 #
    #########################################
    # 4 #   #   #   #   #   #   # * #   # 4 #
    #########################################
    #   #   #   #   #   #   #   # * #   #   #
    #########################################
    ���� - ������������� 10�7
    (Side == 0) => �����
    (Side == 1) => ������
    */

    //�������� X ���������� �����
    if ($Side == 0)
      $X = 0;
    else
      $X = 9;

    //���������� �������� ������
    $race = getdata($Login, 'hero', 'race');
    $h1 = query(1, $race, 'health');
    $h2 = query(2, $race, 'health');
    $h3 = query(3, $race, 'health');
    $h4 = query(4, $race, 'health');

    //���������� ���������� ����� ������
    $a1 = query(1, $race, 'arrows');
    $a2 = query(2, $race, 'arrows');
    $a3 = query(3, $race, 'arrows');
    $a4 = query(4, $race, 'arrows');

    //������� ��������� � ��
    mysql_query("insert into war values('$Login', '$X', '1', '$X', '2', '$X', '4', '$X', '5', '3', '5', '7', '9', '$h1', '$h2', '$h3', '$h4', '$a1', '$a2', '$a3', '$a4');");
  }

  //���������� �����
  function OffThisBattle($Login, $Opponent)
  {
    //��������� �������������� �����
    change($Login, 'battles', 'opponent', '0');
    change($Login, 'battles', 'battle', '0');
    change($Login, 'battles', 'health', '0');
    change($Opponent, 'battles', 'opponent', '0');
    change($Opponent, 'battles', 'battle', '0');
    change($Opponent, 'battles', 'health', '0');

    //������� ������� ��� ����� �������� - ��������
    change($Login, 'battle', 'health', '0');
    change($Opponent, 'battle', 'health', '0');
    change($Login, 'battle', 'opponent', '0');
    change($Opponent, 'battle', 'opponent', '0');
    change($Login, 'battle', 'turn', '0');
    change($Opponent, 'battle', 'turn', '0');
    change($Login, 'battle', 'attack', '0');
    change($Opponent, 'battle', 'attack', '0');
    change($Login, 'battle', 'data', '');
    change($Opponent, 'battle', 'data', '');
    change($Login, 'battle', 'value', '0');
    change($Opponent, 'battle', 'value', '0');
    change($Login, 'battle', 'info', '0');
    change($Opponent, 'battle', 'info', '0');
    change($Login, 'battle', 'timeout', '0');
    change($Opponent, 'battle', 'timeout', '0');

    //������� �� ������� ������
  	mysql_query("delete from war where login = '".$Login."';");
  	mysql_query("delete from war where login = '".$Opponent."';");
  }

  //����� ����������
  function Grab($Login, $Opponent)
  {
    //��� ����� ���������?
    $MyName = getdata($Login, 'hero', 'name');
    $OpName = getdata($Opponent, 'hero', 'name');

    //����� ���������� �������� ������
    $Metal = getdata($Opponent, 'economic', 'metal');
    $Rock  = getdata($Opponent, 'economic', 'rock');
    $Wood  = getdata($Opponent, 'economic', 'wood');
    $Money = getdata($Opponent, 'economic', 'money');

    //�������� 1/3
    change($Opponent, 'economic', 'metal', round(2*$Metal/3));
    change($Opponent, 'economic', 'rock',  round(2*$Rock/3));
    change($Opponent, 'economic', 'wood',  round(2*$Wood/3));
    change($Opponent, 'economic', 'money', round(2*$Money/3));

    //��������� ������ � ��� ����
    $Curse = getdata($Opponent, 'economic', 'curse');
    $MeMoney = $Money / $Curse;
    $Curse = getdata($Login, 'economic', 'curse');
    $MeMoney = $MeMoney * $Curse;

    //������� � ����� ��������
    $MyMoney = getdata($Login, 'economic', 'money');
    $MyMetal = getdata($Login, 'economic', 'metal');
    $MyRock  = getdata($Login, 'economic', 'rock');
    $MyWood  = getdata($Login, 'economic', 'wood');

    //��������� ���� 1/3
    change($Login, 'economic', 'money', round($MeMoney/3+$MyMoney));
    change($Login, 'economic', 'metal', round($Metal/3  +$MyMetal));
    change($Login, 'economic', 'rock',  round($Rock/3   +$MyRock));
    change($Login, 'economic', 'wood',  round($Wood/3   +$MyWood));

    //�������� �����
    $MonName = getdata($Login, 'economic', 'moneyname');
    $HisName = getdata($Opponent, 'economic', 'moneyname');

    //���������� ����� ����� � ���
    sms($Login, "������� ����", "� ���������� �������� ����� ����� ������ ".$OpName." �� ���������� ����� ���� ��������: <b>".$MonName."</b>: ".$MeMoney."; <b>�������</b>: ".$Metal."; <b>�����</b>: ".$Rock."; <b>������</b>: ".$Wood);
    sms($Opponent, "������� ����", "� ���������� ���������� ������ �����, ".$MyName." ����� �� ���������� �����: <b>".$HisName."</b>: ".$Money."; <b>�������</b>: ".$Metal."; <b>�����</b>: ".$Rock."; <b>������</b>: ".$Wood);
  }

  //�������� ���� ���������
  function ToOpponent($Login, $Oppon)
  {
    $tm = time();
    change($Login, 'battle', 'timeout', $tm);
    change($Oppon, 'battle', 'timeout', $tm);
    change($Login, 'battle', 'turn', $Oppon);
    change($Oppon, 'battle', 'turn', $Oppon);
    change($Oppon, 'battle', 'attack', 1);
    change($Oppon, 'battle', 'health', 3);
    moveto("fight.php");
  }

  //��������� ��������
  function PageHeader($Login, $Oppon)
  {
    echo("<tr>\n");
    echo("<td colspan=10 align=center>\n");

    //��� ���
    $Turn = getdata($Login, 'battle', 'turn');
    $Hero = getdata($Turn, 'hero', 'name');

    //��� ����� ��� ��� ���
    $TimeOut = 180;
    $LastTime = getdata($Turn, 'battle', 'timeout');
    $Delta = time() - $LastTime;

    //���� ����� ����� ����, �� ������� ��� ���������
    if ($Delta >= $TimeOut)
      if ($Login == $Turn)
        ToOpponent($Login, $Oppon);
      else
        ToOpponent($Oppon, $Login);
    
    //������������ � �������� ������
    $Last = $TimeOut - $Delta;
    if ($Last < 0)
      $Last = 0;

    //�������� ��� �������, ������� �����
    $Numb = getdata($Login, 'battle', 'attack');
    $Race = getdata($Login, 'hero', 'race');
    $Monster = query($Numb, $Race, 'addon');

    //���������� ���������
    $Message = "<font color=white><b>���: ".$Hero."<br>�� �������� ���� �������� ".$Last." ������ <br>";
    if ($Turn == $Login)
      $Message = $Message."������ ��� ".$Monster."</b></font>\n";

    //����� ������
    echo($Message);
    echo("</td>\n");
    echo("</tr>\n");
  }

  //������ ������� � ��� ����
  function StringToLog($Login, $String)
  {
    //�������� ����� ��� ����� ���
    $Num = getdata($Login, 'battle', 'info');

    //������ ����
    $file = fopen("data/logs/".$Num.".log", "r");
    for ($i = 0; $i < 3; $i++)
      $Str[$i+1] = trim(fgets($file, 255));
    fclose($file);
  
    //������� �����
    $tm = "<font color=yellow><b>(".date("<b>H:i:s</b>", time()).")</b></font>";

    //��������� � ������ �������
    $Str[0] = $String." ".$tm."<br>";

    //��������� ����
    $file = fopen("data/logs/".$Num.".log", "w");
    for ($i = 0; $i < 3; $i++)
      fputs($file, $Str[$i]."\n");
    fclose($file);
  }

  //������ ����� ������ �� ������
  function AttackWarrior($Level1, $Level2, $Login, $Oppon)
  {
    //������������ ������� ����� ���������
    $Level2 = $Level2 - 4;

    //��� ���� � ���� ���������
    $MyRace = getdata($Login, 'hero', 'race');
    $OpRace = getdata($Oppon, 'hero', 'race');

    //����������� ����, ��������� �������
    $Arrows  = getdata($Login, 'war', 'arrow'.$Level1);
    if ($Arrows != 0)
    {
      //������� ����
      $Damage = query($Level1, $MyRace, 'archery');

      //�������������� ���� �� ���. �����������
      $Damage = $Damage + $Damage*Level(25, $Login)*0.01;

      //��������� ���������� �����
      $Arrows--;
      change($Login, 'war', 'arrow'.$Level1, $Arrows);
    }
    else
    {
      //��� ����
      $Damage = query($Level1, $MyRace, 'power');

      //�������������� ���� �� ���. �����������
      $Damage = $Damage + $Damage*Level(24, $Login)*0.01;
    }

    //���������� ���� ������
    $MyCount = getdata($Login, 'army', 'level'.$Level1);

    //��������� ���� = ����*����������
    $FullDamage = $Damage * $MyCount;

    //���. ����������� �������
    $FullDamage = $FullDamage + $FullDamage * 0.01 * Level(19, $Login);

    //���������� ������ ���������
    $OpCount = getdata($Oppon, 'army', 'level'.$Level2);

    //������ ��������� ������ ���������
    $Protect = query($Level2, $OpRace, 'protect');
    $Health  = query($Level2, $OpRace, 'health');

    //������������ ���� � ������� �� ������
    $FullProtect = $Protect * $OpCount;

    //���. ����������� �������
    $FullProtect = $FullProtect + $FullProtect * 0.01 * Level(19, $Oppon);

    //� ������� ������ ���� ������
    $Full = $FullDamage - $FullProtect;
    if ($Full < 0)
      $Full = 0;

    //������ ���� ������
    $PreFull = $FullDamage / $FullProtect;
    if ($PreFull < 0)
      $PreFull = 0;

    //���������� �������� ���������� ������
    $Health     = query($Level2, $OpRace, 'health');
    $Additional = getdata($Oppon, 'war', 'health'.$Level2);
    $FullHealth = ($OpCount-1)*$Health + $Additional;

    //� ������ �������� �� �������� ��� ����
    $FullHealth = $FullHealth - $Full;
    if ($FullHealth < 0)
      $FullHealth = 0;

    //������� ������� ������...
    $Alive = 0;
    while($FullHealth >= $Health)
    {
      $Alive++;
      $FullHealth = $FullHealth - $Health;
    }
    
    //� ������� �� �������
    $Dead = $OpCount - $Alive;

    //��������� ������� � ��� ���� �����
    $Fight  = query($Level1, $MyRace, 'name');
    $Defeat = query($Level2, $OpRace, 'addon');
    StringToLog($Login, "<font color=white>".$Fight." ������� ".$Defeat.". ".$Dead." ".$Defeat." ��������</font>");

    //������� ��� ���������� �������
    change($Oppon, 'army', 'level'.$Level2, $Alive);
    change($Oppon, 'war', 'health'.$Level2, $FullHealth);
    change($Login, 'battle', 'health', 0);
    moveto('fight.php');
  }

  //����� ��� �����
  function PrintLog($Login)
  {
    $Number = getdata($Login, 'battle', 'info');
    readfile("data/logs/".$Number.".log");
  }
?>