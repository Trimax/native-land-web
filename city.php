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

//Подключаем модуль и устанавливаем стиль
include "functions.php";

$lg = trim($HTTP_COOKIE_VARS["nativeland"]);

FromBattle($lg);

echo ("<link rel='stylesheet' type='text/css' href='style.css'/>\n<title>Native Land</title>\n</head>\n");

//Фон замка
$addr = "images/castles/".getdata($login, 'hero', 'race')."/";
echo("<img class='x' src='".$addr."back.JPG'>");

//Выход из замка
echo("<center><h2><font color=yellow><b>".getdata($login, 'info', 'capital')."</b></h2></center>");
ban();

//Вставляем в код JS функцию перехода к зданию
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

//Выбираем расу:
if (getdata($login, 'hero', 'race') == 'people')
	{
		//Преффектура
		$lf[1] = 336;
		$tp[1] = 120;

		//Муниципалитет
		$lf[2] = 230;
		$tp[2] = 118;

		//Капитолий
		$lf[3] = 42;
		$tp[3] = 76;

		//Шахта
		$lf[4] = 26;
		$tp[4] = 228;

		//Рынок
		$lf[5] = 397;
		$tp[5] = 214;

		//Кузница
		$lf[6] = 123;
		$tp[6] = 315;

		//Храм
		$lf[7] = 158;
		$tp[7] = 210;

		//Гильдия магов 1
		$lf[8] = 295;
		$tp[8] = 297;

		//Гильдия магов 2
		$lf[9] = 495;
		$tp[9] = 290;

		//Банк
		$lf[10] = 300;
		$tp[10] = 204;

		//Разведка
		$lf[11] = 526;
		$tp[11] = 197;

		//Таверна
		$lf[12] = 8;
		$tp[12] = 319;

		//Казарма
		$lf[13] = 423;
		$tp[13] = 123;
	}

//Выбираем расу:
if (getdata($login, 'hero', 'race') == 'elf')
	{
		//Преффектура
		$lf[1] = 360;
		$tp[1] = 94;

		//Муниципалитет
		$lf[2] = 222;
		$tp[2] = 70;

		//Капитолий
		$lf[3] = 65;
		$tp[3] = 28;

		//Шахта
		$lf[4] = 231;
		$tp[4] = 361;

		//Рынок
		$lf[5] = 434;
		$tp[5] = 208;

		//Кузница
		$lf[6] = 354;
		$tp[6] = 210;

		//Храм
		$lf[7] = 221;
		$tp[7] = 200;

		//Гильдия магов 1
		$lf[8] = 577;
		$tp[8] = 198;

		//Гильдия магов 2
		$lf[9] = 500;
		$tp[9] = 280;

		//Банк
		$lf[10] = 376;
		$tp[10] = 318;

		//Разведка
		$lf[11] = 126;
		$tp[11] = 219;

		//Таверна
		$lf[12] = 3;
		$tp[12] = 219;

		//Казарма
		$lf[13] = 478;
		$tp[13] = 94;
	}


//Выбираем расу:
if (getdata($login, 'hero', 'race') == 'druid')
	{
		//Преффектура
		$lf[1] = 364;
		$tp[1] = 119;

		//Муниципалитет
		$lf[2] = 255;
		$tp[2] = 55;

		//Капитолий
		$lf[3] = 2;
		$tp[3] = 3;

		//Шахта
		$lf[4] = 156;
		$tp[4] = 185;

		//Рынок
		$lf[5] = 289;
		$tp[5] = 154;

		//Кузница
		$lf[6] = 46;
		$tp[6] = 313;

		//Храм
		$lf[7] = 230;
		$tp[7] = 255;

		//Гильдия магов 1
		$lf[8] = 537;
		$tp[8] = 216;

		//Гильдия магов 2
		$lf[9] = 392;
		$tp[9] = 230;

		//Банк
		$lf[10] = 166;
		$tp[10] = 335;

		//Разведка
		$lf[11] = 165;
		$tp[11] = 31;

		//Таверна
		$lf[12] = 3;
		$tp[12] = 214;

		//Казарма
		$lf[13] = 465;
		$tp[13] = 52;
	}

//Выбираем расу:
if (getdata($login, 'hero', 'race') == 'necro')
	{
		//Преффектура
		$lf[1] = 316;
		$tp[1] = 138;

		//Муниципалитет
		$lf[2] = 177;
		$tp[2] = 90;

		//Капитолий
		$lf[3] = 8;
		$tp[3] = 91;

		//Шахта
		$lf[4] = 320;
		$tp[4] = 402;

		//Рынок
		$lf[5] = 7;
		$tp[5] = 375;

		//Кузница
		$lf[6] = 178;
		$tp[6] = 228;

		//Храм
		$lf[7] = 169;
		$tp[7] = 342;

		//Гильдия магов 1
		$lf[8] = 550;
		$tp[8] = 221;

		//Гильдия магов 2
		$lf[9] = 485;
		$tp[9] = 315;

		//Банк
		$lf[10] = 333;
		$tp[10] = 315;

		//Разведка
		$lf[11] = 11;
		$tp[11] = 255;

		//Таверна
		$lf[12] = 333;
		$tp[12] = 218;

		//Казарма
		$lf[13] = 438;
		$tp[13] = 119;
	}

//Выбираем расу:
if (getdata($login, 'hero', 'race') == 'hnom')
	{
		//Преффектура
		$lf[1] = 108;
		$tp[1] = 148;

		//Муниципалитет
		$lf[2] = 208;
		$tp[2] = 148;

		//Капитолий
		$lf[3] = 379;
		$tp[3] = 33;

		//Шахта
		$lf[4] = 391;
		$tp[4] = 227;

		//Рынок
		$lf[5] = 129;
		$tp[5] = 367;

		//Кузница
		$lf[6] = 1;
		$tp[6] = 294;

		//Храм
		$lf[7] = 124;
		$tp[7] = 238;

		//Гильдия магов 1
		$lf[8] = 520;
		$tp[8] = 283;

		//Гильдия магов 2
		$lf[9] = 528;
		$tp[9] = 120;

		//Банк
		$lf[10] = 357;
		$tp[10] = 339;

		//Разведка
		$lf[11] = 210;
		$tp[11] = 356;

		//Таверна
		$lf[12] = 264;
		$tp[12] = 227;

		//Казарма
		$lf[13] = 7;
		$tp[13] = 146;
	}

//Выбираем расу:
if (getdata($login, 'hero', 'race') == 'hell')
	{
		//Преффектура
		$lf[1] = 124;
		$tp[1] = 137;

		//Муниципалитет
		$lf[2] = 250;
		$tp[2] = 134;

		//Капитолий
		$lf[3] = 388;
		$tp[3] = 77;

		//Шахта
		$lf[4] = 27;
		$tp[4] = 377;

		//Рынок
		$lf[5] = 138;
		$tp[5] = 218;

		//Кузница
		$lf[6] = 1;
		$tp[6] = 269;

		//Храм
		$lf[7] = 194;
		$tp[7] = 222;

		//Гильдия магов 1
		$lf[8] = 489;
		$tp[8] = 344;

		//Гильдия магов 2
		$lf[9] = 507;
		$tp[9] = 224;

		//Банк
		$lf[10] = 328;
		$tp[10] = 344;

		//Разведка
		$lf[11] = 170;
		$tp[11] = 350;

		//Таверна
		$lf[12] = 354;
		$tp[12] = 234;

		//Казарма
		$lf[13] = 0;
		$tp[13] = 150;
	}

//Здания по уровням в зависимости от расы
for ($i = 1; $i < 14; $i++)
{
	//Информация о здании и его ссылка
	$inf = getfrom('race', getdata($login, 'hero', 'race'), 'buildings', 'build'.$i);
	$src = $addr."build".$i.".JPG";

	//Если здание построено, то рисуем его
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

