<title>����������</title>
<link rel='stylesheet' type='text/css' href='style.css'/>
<body background='images/back.jpe'>
<?
  include "functions.php";

  /* ����� ������� */
  GLOBAL $total_hosts;
  $total_hosts = 0;
  GLOBAL $total_hits;
  $total_hits  = 0;
  GLOBAL $total_total;
  $total_total = 0;

  //��������� ���������� �� �������
  show_ip_host(1, 0, $id_page);
  $host1    = $total_hosts;
  $hit1     = $total_hits;
  $total1   = $total_total;
  //��������� ���������� �� �����
  show_ip_host(2, 1, $id_page);
  $host2    = $total_hosts;
  $hit2     = $total_hits;
  $total2   = $total_total;
  //��������� ���������� �� ��������� 7 ����
  show_ip_host(7, 0, $id_page);
  $host7    = $total_hosts;
  $hit7     = $total_hits;
  $total7   = $total_total;
  //��������� ���������� �� ��������� �����
  show_ip_host(30, 0, $id_page);
  $host30   = $total_hosts;
  $hit30    = $total_hits;
  $total30  = $total_total;
  //�� �� �����
  show_ip_host(0, 0, $id_page);
  $hostall  = $total_hosts;
  $hitall   = $total_hits;
  $totalall = $total_total;
  ?>
    <table width=100% border=1 cellpadding=0 cellspacing=0>
    <td>&nbsp;</td>
    <td>�������</td><td>�����</td><td>��������� ������</td><td>��������� �����</td><td>�� �����</td></tr>
    <tr><td>�����</td>
        <td><?php echo $host1; ?></td>
        <td><?php echo $host2; ?></td>
        <td><?php echo $host7; ?></td>
        <td><?php echo $host30; ?></td>
        <td><?php echo $hostall; ?></td>
    </tr>
    <tr><td>����������� ����</td>
        <td><?php echo $hit1; ?></td>
        <td><?php echo $hit2; ?></td>
        <td><?php echo $hit7; ?></td>
        <td><?php echo $hit30; ?></td>
        <td><?php echo $hitall; ?></td>
    </tr>
    <tr><td>��� ����</td>
        <td><?php echo $total1; ?></td>
        <td><?php echo $total2; ?></td>
        <td><?php echo $total7; ?></td>
        <td><?php echo $total30; ?></td>
        <td><?php echo $totalall; ?></td>
    </tr>
  </table>
  <center><a href='statistic.php'>�����</a></center>
  <?

  //������� ������� ������ � ���������� ����������
  function show_ip_host($begin, $end, $id_page)
  {
    GLOBAL $total_hosts;
    GLOBAL $total_hits;
    GLOBAL $total_total;

    //������ �� ����� ����� ��� ��������
    if ($id_page == "")
      $tmp = "";
    else
      $tmp = "id_page=$id_page";

    //��������� ��������
    if ($begin == 0)
      $tmp2 = "";
    else
      $tmp2 = "putdate>=date_sub(date_format(now(), '%Y-%m-%d 23:59:59'), interval $begin day)";
    $tmp1 = "putdate<date_sub(date_format(now(), '%Y-%m-%d 23:59:59'), interval $end day)";

    //����������� "and" � �������
    if ($tmp2 != "" || $tmp != "") $and1 = " and ";
    if ($tmp2 != "" && $tmp != "") $and2 = " and ";

    //��������� �������
    //����������� ����
    $query = "select count(*) from stat_ip where search>=0 and ".$tmp1.$and1.$tmp2.$and2.$tmp.";";

    //����� ����� �����
    $query_total =  "select count(*) from stat_ip where ".$tmp1.$and1.$tmp2.$and2.$tmp.";";

    //����� ������
    $query_host = "select count(distinct ip) from stat_ip where ".$tmp1.$and1.$tmp2.$and2.$tmp.";";

    //������������ �������
    $pht   = mysql_query($query);
    $tot   = mysql_query($query_total);
    $ipsad = mysql_query($query_host);

    //���� ��� ��� ������� ������, ���������� ���������
    if ($pht && $tot && $ipsad)
    {
      $ip   = mysql_fetch_array($pht);
      $totl = mysql_fetch_array($tot);
      $ipn   = mysql_fetch_array($ipsad);

      //���������� �� �����
      $total_hits  = $ip['count(*)'];
      $total_total = $totl['count(*)'];
      //�� ������
      $total_hosts = $ipn['count(distinct ip)'];
    }
  }
?>