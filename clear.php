<?

//���������� � �����
function baselink()
{
$file = fopen("config.ini.php", "r");
$temp = trim(fgets($file, 255));
$temp = trim(fgets($file, 255));
$host = trim(fgets($file, 255));
$base = trim(fgets($file, 255));
$name = trim(fgets($file, 255));
$pass = trim(fgets($file, 255));
fclose($file);
$ret = @mysql_connect($host, $name, $pass);
$slc = mysql_select_db($base);
}

//��������� �����
function randomplace($lgn)
{
	//������� �������� ��������� ������
	$ok = 0;
	while ($ok == 0)
	{
		//������� ��������� ����������
		$rx = rand(20, 1);
		$ry = rand(20, 1);

		//������ �����...
		$fp = 0;
		$file = fopen("maps/".$rx."x".$ry.".map", "r");
		for ($x = 1; $x < 11; $x++)
		{
			for ($y = 1; $y < 11; $y++)
			{

				//������ ������
				$map[$x][$y] = "0*0=0";
				$map[$x][$y] = fgets($file, 255);
				$fld = $map[$x][$y];

				//���� ������ ��������
				if (($fld[0] != '4')&&($fld[2] == '0')&&($fld[4] == '0'))
				{
					$fp++;
				}//���� ������ �� ��������
			} //$y
		} //$x
		fclose($file);

		//���� ���������� ������ ����
		if ($fp != 0)
		{
			//���������� ��������� ����� �� ������ ��������
			$pk = 0;
			while ($pk == 0)
			{
				//��������� �����
				$cx = rand(10, 1);
				$cy = rand(10, 1);
				$fld = $map[$cx][$cy];

				//���� ��� ��������, �������������� ���.
				if (($fld[0] != '4')&&($fld[2] == '0')&&($fld[4] == '0'))
				{
					//��������� ������ ����
					$map[$cx][$cy] = $fld[0]."*".$fld[2]."=".$lgn."\n";

					//������� � ���� ���������� � �������
					mysql_query("insert into coords values ('".$lgn."', '".$rx."', '".$ry."', '".$cx."', '".$cy."');");

					//������� � ���� ���������� � �������
					mysql_query("insert into capital values ('".$lgn."', '".$rx."', '".$ry."', '".$cx."', '".$cy."');");

					//��������� �����
/*					$file = fopen("maps/".$rx."x".$ry.".map", "w");
					for ($x = 1; $x < 11; $x++)
					{
						for ($y = 1; $y < 11; $y++)
						{
							fputs($file, $map[$x][$y]);
						} //$y
					} //$x
					fclose($file); */

					//��������� ���������
					$pk = 1;
					$ok = 1;
				} //����������� �� ��������� �����
			} //$pk
		} //$fp
	} //$ok
} //End of function

//������� ������� �� �����
function placeplayers($login)
{
	//�������
	$all = 0;

	//������ ������������ �����
	if ($login == 'Admin')
	{
		//�������� ���� �������������
		$ath = mysql_query("select * from users;");

		//��� ������� ������������ ���� ���������� ����� �� �����
		if ($ath)
		{
			//��� �������
			while ($rw = mysql_fetch_row($ath))
			{
				//��� ������������
				$lgn = $rw[0];
				randomplace($lgn);
				$all++;
			}
		}
	}

	//�����
	echo("<br>��������� �������: ".$all);
}

//�������� �� ������ �����
function replace()
{
	//������ �����
	$map[15][15] = 0;

	//������� �����
	$itog = 0;

	//���� ����������
	$rw = 0;

	//������ ��� ����� � ������
	for ($i = 1; $i < 21; $i++)
	{
		for ($j = 1; $j < 21; $j++)
		{
			//���������� ��� �����
			$rx = $i;
			$ry = $j;
			$file = fopen("maps/".$rx."x".$ry.".map", "r");
			$rw = 0;

			//�����
			for ($x = 1; $x < 11; $x++)
			{
				//����� ������ �������
				for ($y = 1; $y < 11; $y++)
				{
					//�������� ������ �� ������
					$fld = fgets($file, 255);
					$map[$x][$y] = $fld;

					//��� ������?
					$t = trim(substr($fld, 4));
	
					//����� ��� ���?
					if (!empty($t))
					{
						//���� ���-�� ����
						if (($t != '0')&&($t != 'BANK'))
						{
							$itog++;
							$s = $fld[0].$fld[1].$fld[2].$fld[3]."0\n";
							$map[$x][$y] = $s;
							mysql_query("delete from coords where login = '".$t."';");
							$rw = 1;
						}
					}

				//��������� $y ����
				}
			//��������� $x ����
			}

			//��������� ����
			fclose($file);

			if ($rw == 1)
			{
				//������������ �����
				$file = fopen("maps/".$rx."x".$ry.".map", "w");
				for ($x = 1; $x < 11; $x++)
				{
					//����� ������ �������
					for ($y = 1; $y < 11; $y++)
					{
						fputs($file, $map[$x][$y]);
					}
				}
				fclose($file);
			}
		}
	}

	//�����
	echo("��������������� ���������. ���������������� ������: ".$itog);
}

//����������
$lg = trim($HTTP_COOKIE_VARS["nativeland"]);

//���������� � �����...
baselink();
srand(20);
replace();
placeplayers($lg);

?>