<?
//������ �������
include "functions.php";

//���� �� ������� ��� ������������, �� �������� ����� �����
$lg = trim($HTTP_COOKIE_VARS["nativeland"]);
$pw = trim($HTTP_COOKIE_VARS["password"]);
ban();

//����������?
if (finduser($lg, $pw) != 1)
{
	moveto('index.php');
	exit();
}

if (($lg != "Admin")&&($lg != "DpAkO")&&($lg != "Anarki"))
{
//	exit();
}

//����������� ������
$size = 32;

//���� ���� ��������, �� ��� ������
if (!empty($newsize))
{
	$size = $newsize;
}

//����� �������
echo ("<title>����� ��������</title>");
echo ("<link rel='stylesheet' type='text/css' href='style.css'/><body background='images/terrain/grass.gif'>");
echo ("<center><h2><font color=white>������ ������� �����</font></h2></center>");
echo ("<TABLE ALIGN=CENTER WIDTH='10%' BORDER=0 CELLSPACING=0 CELLPADDING=0><TR>\n");

//�����
$emp = 0;

//������ ��� ����� � ������
for ($i = 1; $i < 21; $i++)
{
echo ("<TD>\n");
	for ($j = 1; $j < 21; $j++)
	{
	//���������� ��� �����
	$rx = $i;
	$ry = $j;
	$file = fopen("maps/".$rx."x".$ry.".map", "r");

	//���������� �����
	echo("<TABLE ALIGN=CENTER WIDTH='10%' BORDER=0 CELLSPACING=0 CELLPADDING=0>");

	//�����
	for ($x = 1; $x < 11; $x++)
	{
		//����� ������
		echo ("<tr>");

		//����� ������ �������
		for ($y = 1; $y < 11; $y++)
		{

			//�������� ������ �� ������
			$fld = fgets($file, 255);

			//����� ��� ���?
			if (($i < 7)&&($j < 5)&&($fld[0] != '4')&&($fld[2] == '0'))
			{
				$emp++;
			}

			//���������� ���������� ����� �����
			$gx = ($rx-1)*10+$x;
			$gy = ($ry-1)*10+$y;

			//�����
			if (($fld[0] == '0')&&($fld[2] == 0)&&($fld[4] == '0'))
			{
				$map = "<img src='images/terrain/grass.gif' width=$size height=$size BORDER=0 alt=�����>";
			}

			//����
			if (($fld[0] == '1')&&($fld[2] == 0)&&($fld[4] == '0'))
			{
				$map = "<img src='images/terrain/snow.gif' width=$size height=$size BORDER=0>";
			}

			//�����
			if (($fld[0] == '2')&&($fld[2] == 0)&&($fld[4] == '0'))
			{
				$map = "<img src='images/terrain/sand.bmp' width=$size height=$size BORDER=0 alt=�����>";
			}

			//����
			if (($fld[0] == '3')&&($fld[2] == 0)&&($fld[4] == '0'))
			{
				$map = "<img src='images/terrain/Fire.jpg' width=$size height=$size BORDER=0 alt=����>";
			}

			//����
			if (($fld[0] == '4')&&($fld[2] == 0)&&($fld[4] == '0'))
			{
				$map = "<img src='images/terrain/water.gif' width=$size height=$size BORDER=0 alt=����>";
			}

			//������
			if (($fld[2] == '1')&&($fld[4] == '0')&&($fld[0] == '1'))
			{
				$map = "<img src='images/terrain/snmetal.bmp' width=$size height=$size BORDER=0 alt='������ �������'>";
			}
			if (($fld[2] == '1')&&($fld[4] == '0')&&($fld[0] == '2'))
			{
				$map = "<img src='images/terrain/smetal.bmp' width=$size height=$size BORDER=0 alt='������ �������'>";
			}
			if (($fld[2] == '1')&&($fld[4] == '0')&&($fld[0] == '3'))
			{
				$map = "<img src='images/terrain/lmetal.bmp' width=$size height=$size BORDER=0 alt='������ �������'>";
			}
			if (($fld[2] == '1')&&($fld[4] == '0')&&($fld[0] == '0'))
			{
				$map = "<img src='images/terrain/metal.bmp' width=$size height=$size BORDER=0 alt='������ �������'>";
			}
	
			//������
			if (($fld[2] == '2')&&($fld[4] == '0')&&($fld[0] == '3'))
			{
				$map = "<img src='images/terrain/lrock.bmp' width=$size height=$size BORDER=0 alt='������ �����'>";
			}
			if (($fld[2] == '2')&&($fld[4] == '0')&&($fld[0] == '2'))
			{
				$map = "<img src='images/terrain/srock.bmp' width=$size height=$size BORDER=0 alt='������ �����'>";
			}
			if (($fld[2] == '2')&&($fld[4] == '0')&&($fld[0] == '1'))
			{
				$map = "<img src='images/terrain/snrock.bmp' width=$size height=$size BORDER=0 alt='������ �����'>";
			}
			if (($fld[2] == '2')&&($fld[4] == '0')&&($fld[0] == '0'))
			{
				$map = "<img src='images/terrain/rock.bmp' width=$size height=$size BORDER=0 alt='������ �����'>";
			}

			//������
			if (($fld[2] == '3')&&($fld[0] == '0')&&($fld[4] == '0'))
			{
				$map = "<img src='images/terrain/tree".rand(1,3).".gif' width=$size height=$size BORDER=0 alt=�������>";
			}
			if (($fld[2] == '3')&&($fld[0] == '1')&&($fld[4] == '0'))
			{
				$map = "<img src='images/terrain/sntree".rand(1,3).".bmp' width=$size height=$size BORDER=0 alt=�������>";
			}
			if (($fld[2] == '3')&&($fld[0] == '2')&&($fld[4] == '0'))
			{
				$map = "<img src='images/terrain/stree".rand(1,2).".bmp' width=$size height=$size BORDER=0 alt=�������>";
			}
			if (($fld[2] == '3')&&($fld[0] == '3')&&($fld[4] == '0'))
			{
				$map = "<img src='images/terrain/ltree.bmp' width=$size height=$size BORDER=0 alt=�������>";
			}
			//�������, �� ��������� �� ����������
			if (($fld[2] == '4'))
			{
				$map = "<img src='images/objects/wall.gif' width=$size height=$size BORDER=0 alt=�����>";
			}
			//�����
			 if ($fld[4] != '0')
			{
				 if ($fld[0] == '0')
				{
					$map = "<img src='images/terrain/castle.gif' width=$size height=$size BORDER=0 alt='�����. ����������� �������� ".getdata(trim(substr($fld, 4)), 'hero', 'name')."'>";
				}
				 if ($fld[0] == '1')
				{
					$map = "<img src='images/terrain/castlesnow.gif' width=$size height=$size BORDER=0 alt='�����. ����������� �������� ".getdata(trim(substr($fld, 4)), 'hero', 'name')."'>";
				}
				 if ($fld[0] == '2')
				{
					$map = "<img src='images/terrain/castlesand.gif' width=$size height=$size BORDER=0 alt='�����. ����������� �������� ".getdata(trim(substr($fld, 4)), 'hero', 'name')."'>";
				}
				 if ($fld[0] == '3')
				{
					$map = "<img src='images/terrain/castlefire.gif' width=$size height=$size BORDER=0 alt='�����. ����������� �������� ".getdata(trim(substr($fld, 4)), 'hero', 'name')."'>";
				}
			}

		//������� � ������
		echo ("<td>".$map."</td>");

		//��������� $y ����
		}
	
	echo ("</tr>");
	//��������� $x ����
	}

	//��������� ����
	fclose($file);

	//����� �������
	echo ("</table>");
	}
echo ("</TD>\n");
}
echo ("</TR></table>\n");

echo ("<br>������ ������: ".$emp);

?>

