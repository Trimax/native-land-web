<body background="images/back.jpe">
<link rel='stylesheet' type='text/css' href='style.css'/>
<title>������� �������</title>
<script>
function playersinfo(name)
{
	var s;
	s = 'info.php?name=' + name;
	window.open(s, null,'toolbar=no, location=no, menubar=no,scrollbars=yes,width=656,height=480');
}
</script>

<?
	include "functions.php";
	ban();
	
	$ath = mysql_query("select * from hero;");
	$count = 0;
	if ($ath)
	{
		//���� ����
		while ($rw = mysql_fetch_row($ath))
		{
			//���� � �������������
			$users[$count] = $rw[0];
			$names[$count] = $rw[1];
			$expas[$count] = $rw[2];
			$levels[$count] = $rw[3];
			$count++;
		}
	}

	//��������� ���� ������������� �� �����
	for($i = 0; $i < $count; $i++)
	{
		for($j = $i+1; $j < $count; $j++)
		{
			if ($expas[$j] > $expas[$i])
			{
				//Reverse expa
				$y = $expas[$i];
				$expas[$i] = $expas[$j];
			    $expas[$j] = $y;
	
				//Reverse users
				$y = $users[$i];
				$users[$i] = $users[$j];
			    $users[$j] = $y;

				//Reverse names
				$y = $names[$i];
				$names[$i] = $names[$j];
				$names[$j] = $y;

				//Reverse levels
				$y = $levels[$i];
				$levels[$i] = $levels[$j];
			    $levels[$j] = $y;
			} //End of condition
		} //End of "j" cycle
	} // End of "i" cycle

	//��������� ��������
	echo("<center><h1>������� �������</h1></center>");
	?>
	<center>
	<a href="#rul">	<b>������� ������� � ������������ ��������</b><br>
	</center>
	<?

	//������� ��������� � �������
	echo("<center><table border=1 CELLPADDING=0 CELLSPACING=0 width=90%>");
	echo("<tr><td align=center>�����</td><td align=center>�����</td><td align=center>��� �����</td><td align=center>�������</td><td align=center>����</td></tr>");
	for ($i = 0; $i < $count; $i++)
	{
		//�������� ����� �������� �����
		if ($i < 10)
		{
			//������ ������
			if ($i < 5)
			{
				//������ �������
				if ($i < 3)
				{
					echo("<tr><td align=center><a href=javascript:playersinfo('".$users[$i]."')><b><font color=#006600>".($i+1)."</font></b></a></td><td align=center><a href=javascript:playersinfo('".$users[$i]."')><b><font color=#006600>".$users[$i]."</font></b></a></td><td align=center><a href=javascript:playersinfo('".$users[$i]."')><b><font color=#006600>".$names[$i]."</font></b></a></td><td align=center><a href=javascript:playersinfo('".$users[$i]."')><b><font color=#006600>".$levels[$i]."</font></b></a></td><td align=center><a href=javascript:playersinfo('".$users[$i]."')><b><font color=#006600>".$expas[$i]."</font></b></a></td></tr>");
				} else
				{
					echo("<tr><td align=center><a href=javascript:playersinfo('".$users[$i]."')><b><font color=yellow>".($i+1)."</font></b></a></td><td align=center><a href=javascript:playersinfo('".$users[$i]."')><b><font color=yellow>".$users[$i]."</font></b></a></td><td align=center><a href=javascript:playersinfo('".$users[$i]."')><b><font color=yellow>".$names[$i]."</font></b></a></td><td align=center><a href=javascript:playersinfo('".$users[$i]."')><b><font color=yellow>".$levels[$i]."</font></b></a></td><td align=center><a href=javascript:playersinfo('".$users[$i]."')><b><font color=yellow>".$expas[$i]."</font></b></a></td></tr>");
				}
			} else
			{
				echo("<tr><td align=center><a href=javascript:playersinfo('".$users[$i]."')><b><font color=#CC0033>".($i+1)."</font></b></a></td><td align=center><a href=javascript:playersinfo('".$users[$i]."')><b><font color=#CC0033>".$users[$i]."</font></b></a></td><td align=center><a href=javascript:playersinfo('".$users[$i]."')><b><font color=#CC0033>".$names[$i]."</font></b></a></td><td align=center><a href=javascript:playersinfo('".$users[$i]."')><b><font color=#CC0033>".$levels[$i]."</font></b></a></td><td align=center><a href=javascript:playersinfo('".$users[$i]."')><b><font color=#CC0033>".$expas[$i]."</font></b></a></td></tr>");
			}
		} else
		{
			if ($expas[$i] > 10)
			{
				echo("<tr><td align=center>".($i+1)."</td><td align=center><a href=javascript:playersinfo('".$users[$i]."')><font color=blue><b>".$users[$i]."</b></font></a></td><td align=center><b>".$names[$i]."</b></td><td align=center><b>".$levels[$i]."</b></td><td align=center><b>".$expas[$i]."</b></td></tr>");
			} else
			{
				echo("<tr><td align=center><b><font color=black>".($i+1)."</font></b></td><td align=center><a href=javascript:playersinfo('".$users[$i]."')><b><font color=black>".$users[$i]."</font></b></a></td><td align=center><b><font color=black>".$names[$i]."</font></b></td><td align=center><b><font color=black>".$levels[$i]."</font></b></td><td align=center><b><font color=black>".$expas[$i]."</font></b></td></tr>");
			}
		}
	}
	echo("</table></center>");
?>

<br><A NAME="RUL"><b>������� ������� � ������������ ��������</b></A><br>
1) � ������� � �������� �� ����������� �������������� � ������������� ����, �.�. Admin � Teider.<br>
2) ���� �����, �������� �������� ����� ������ � ������ (�����-���� �������), �� ����� �������� �� �������� � ��� ���� ���������� ������ ���� (��� ����, ������� � ������ �����������)<br>
<br><font color=darkblue><b>�������� �����</b></font><br>
��������� ������� ��������� ������ ������ ����, ������:<br>
<font color=#006600>1)</font> �������� � 10 �� 6 ����������� �������������� ���� �������� (100 ��)<br>
<font color=yellow>2)</font> �������� 5 � 4 ����� ����������� �������������� ������� (�� 50 �������)<br>
<font color=#CC0033>3)</font> �������� � 3 �� 1 ����� �� ���� � ������� ����� ����������� ������ (10000 �� ����� 1 � 1)<br>

<font color=black>0)</font> ������, ���������� ������ ������ ����� ������� 1 ����� ���������� ������<br>
<br>P.S. ����� ��� �� ���������� ����������. �� ������� � �������, ���, ��� �������� ������ ����� �� ����� �� ������, �� �������, �� ��. �������������. :)<br>
