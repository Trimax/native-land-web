<link rel='stylesheet' type='text/css' href='style.css'/>
<style>
img.x
{
position:absolute;
left:0;
top:0;
z-index:-1;
}
img.1
{
position:absolute;
width:90;
top:90;
z-index:1;
}
img.2
{
position:absolute;
width:90;
top:90;
z-index:2;
}
</style>

<body background='images\back.jpe'>

<?

//���������� ������ � ������������� �����
include "functions.php";

$lg = trim($HTTP_COOKIE_VARS["nativeland"]);

FromBattle($lg);

echo ("<link rel='stylesheet' type='text/css' href='style.css'/>\n<title>Native Land</title>\n</head>\n");

//��� �����
$addr = "images/castles/".getdata($login, 'hero', 'race')."/";
echo("<img class='x' src='".$addr."back.JPG'>");

//����� �� �����
echo("<center><h2><font color=yellow><b>".getdata($login, 'info', 'capital')."</b></h2></center>");
ban();

//��������� � ��� JS ������� �������� � ������
echo("<script language='JavaScript'>");
echo("function link(n) {");
echo("if ((n == 1)||(n == 2)||(n == 3)||(n == 5)||(n == 6)||(n == 7)||(n == 8)||(n == 9)||(n == 10)||(n == 11)||(n == 12)||(n == 13))");
echo("   {");
echo("   if (n == 1) {s = 'teleport';}");
echo("   if (n == 2) {s = 'bank';}");
echo("   if (n == 3) {s = 'regclan';}");
echo("   if (n == 5) {s = 'trademark';}");
echo("   if (n == 6) {s = 'armory';}");
echo("   if (n == 7) {s = 'church';}");
echo("   if (n == 8) {s = 'guild';}");
echo("   if (n == 9) {s = 'mguild';}");
echo("   if (n == 10) {s = 'bankomat';}");
echo("   if (n == 11) {s = 'spy';}");
echo("   if (n == 12) {s = 'tavern';}");
echo("   if (n == 13) {s = 'getarmy';}");
echo("   window.location.href(s + '.php?login=".$login."');");
echo("   }");
echo("}");
echo("</script>");

//�������� ����:
if (getdata($login, 'hero', 'race') == 'people')
	{
		//�����������
		$lf[1] = 336;
		$tp[1] = 120;

		//�������������
		$lf[2] = 230;
		$tp[2] = 118;

		//���������
		$lf[3] = 42;
		$tp[3] = 76;

		//�����
		$lf[4] = 26;
		$tp[4] = 228;

		//�����
		$lf[5] = 397;
		$tp[5] = 214;

		//�������
		$lf[6] = 123;
		$tp[6] = 315;

		//����
		$lf[7] = 158;
		$tp[7] = 210;

		//������� ����� 1
		$lf[8] = 295;
		$tp[8] = 297;

		//������� ����� 2
		$lf[9] = 495;
		$tp[9] = 290;

		//����
		$lf[10] = 300;
		$tp[10] = 204;

		//��������
		$lf[11] = 526;
		$tp[11] = 197;

		//�������
		$lf[12] = 8;
		$tp[12] = 319;

		//�������
		$lf[13] = 423;
		$tp[13] = 123;
	}

//�������� ����:
if (getdata($login, 'hero', 'race') == 'elf')
	{
		//�����������
		$lf[1] = 360;
		$tp[1] = 94;

		//�������������
		$lf[2] = 222;
		$tp[2] = 70;

		//���������
		$lf[3] = 65;
		$tp[3] = 28;

		//�����
		$lf[4] = 231;
		$tp[4] = 361;

		//�����
		$lf[5] = 434;
		$tp[5] = 208;

		//�������
		$lf[6] = 354;
		$tp[6] = 210;

		//����
		$lf[7] = 221;
		$tp[7] = 200;

		//������� ����� 1
		$lf[8] = 577;
		$tp[8] = 198;

		//������� ����� 2
		$lf[9] = 500;
		$tp[9] = 280;

		//����
		$lf[10] = 376;
		$tp[10] = 318;

		//��������
		$lf[11] = 126;
		$tp[11] = 219;

		//�������
		$lf[12] = 3;
		$tp[12] = 219;

		//�������
		$lf[13] = 478;
		$tp[13] = 94;
	}


//�������� ����:
if (getdata($login, 'hero', 'race') == 'druid')
	{
		//�����������
		$lf[1] = 364;
		$tp[1] = 119;

		//�������������
		$lf[2] = 255;
		$tp[2] = 55;

		//���������
		$lf[3] = 2;
		$tp[3] = 3;

		//�����
		$lf[4] = 156;
		$tp[4] = 185;

		//�����
		$lf[5] = 289;
		$tp[5] = 154;

		//�������
		$lf[6] = 46;
		$tp[6] = 313;

		//����
		$lf[7] = 230;
		$tp[7] = 255;

		//������� ����� 1
		$lf[8] = 537;
		$tp[8] = 216;

		//������� ����� 2
		$lf[9] = 392;
		$tp[9] = 230;

		//����
		$lf[10] = 166;
		$tp[10] = 335;

		//��������
		$lf[11] = 165;
		$tp[11] = 31;

		//�������
		$lf[12] = 3;
		$tp[12] = 214;

		//�������
		$lf[13] = 465;
		$tp[13] = 52;
	}

//�������� ����:
if (getdata($login, 'hero', 'race') == 'necro')
	{
		//�����������
		$lf[1] = 316;
		$tp[1] = 138;

		//�������������
		$lf[2] = 177;
		$tp[2] = 90;

		//���������
		$lf[3] = 8;
		$tp[3] = 91;

		//�����
		$lf[4] = 320;
		$tp[4] = 402;

		//�����
		$lf[5] = 7;
		$tp[5] = 375;

		//�������
		$lf[6] = 178;
		$tp[6] = 228;

		//����
		$lf[7] = 169;
		$tp[7] = 342;

		//������� ����� 1
		$lf[8] = 550;
		$tp[8] = 221;

		//������� ����� 2
		$lf[9] = 485;
		$tp[9] = 315;

		//����
		$lf[10] = 333;
		$tp[10] = 315;

		//��������
		$lf[11] = 11;
		$tp[11] = 255;

		//�������
		$lf[12] = 333;
		$tp[12] = 218;

		//�������
		$lf[13] = 438;
		$tp[13] = 119;
	}

//�������� ����:
if (getdata($login, 'hero', 'race') == 'hnom')
	{
		//�����������
		$lf[1] = 108;
		$tp[1] = 148;

		//�������������
		$lf[2] = 208;
		$tp[2] = 148;

		//���������
		$lf[3] = 379;
		$tp[3] = 33;

		//�����
		$lf[4] = 391;
		$tp[4] = 227;

		//�����
		$lf[5] = 129;
		$tp[5] = 367;

		//�������
		$lf[6] = 1;
		$tp[6] = 294;

		//����
		$lf[7] = 124;
		$tp[7] = 238;

		//������� ����� 1
		$lf[8] = 520;
		$tp[8] = 283;

		//������� ����� 2
		$lf[9] = 528;
		$tp[9] = 120;

		//����
		$lf[10] = 357;
		$tp[10] = 339;

		//��������
		$lf[11] = 210;
		$tp[11] = 356;

		//�������
		$lf[12] = 264;
		$tp[12] = 227;

		//�������
		$lf[13] = 7;
		$tp[13] = 146;
	}

//�������� ����:
if (getdata($login, 'hero', 'race') == 'hell')
	{
		//�����������
		$lf[1] = 124;
		$tp[1] = 137;

		//�������������
		$lf[2] = 250;
		$tp[2] = 134;

		//���������
		$lf[3] = 388;
		$tp[3] = 77;

		//�����
		$lf[4] = 27;
		$tp[4] = 377;

		//�����
		$lf[5] = 138;
		$tp[5] = 218;

		//�������
		$lf[6] = 1;
		$tp[6] = 269;

		//����
		$lf[7] = 194;
		$tp[7] = 222;

		//������� ����� 1
		$lf[8] = 489;
		$tp[8] = 344;

		//������� ����� 2
		$lf[9] = 507;
		$tp[9] = 224;

		//����
		$lf[10] = 328;
		$tp[10] = 344;

		//��������
		$lf[11] = 170;
		$tp[11] = 350;

		//�������
		$lf[12] = 354;
		$tp[12] = 234;

		//�������
		$lf[13] = 0;
		$tp[13] = 150;
	}

//������ �� ������� � ����������� �� ����
for ($i = 1; $i < 14; $i++)
{
	//���������� � ������ � ��� ������
	$inf = getfrom('race', getdata($login, 'hero', 'race'), 'buildings', 'build'.$i);
	$src = $addr."build".$i.".JPG";

	//���� ������ ���������, �� ������ ���
	if (getdata($login, 'city', 'build'.$i) != 0)
	{
		echo("<div style='position:absolute; left:".$lf[$i]."px; top:".$tp[$i]."px; width:90px; height:90px; z-index:12'>");
		if ($lg == $login) 
		{
			echo("<IMG SRC=".$src." ALT='".$inf."' CLASS=aFilter onclick='link(".$i.")'></div>");
		}
		else
		{
			echo("<IMG SRC=".$src." ALT='".$inf."' CLASS=aFilter'></div>");
		}
	}

}

?>

