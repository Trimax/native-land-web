<?

//Соединение с базой
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

//Случайное место
function randomplace($lgn)
{
	//Сначала выбираем случайный регион
	$ok = 0;
	while ($ok == 0)
	{
		//Генерим случайные координаты
		$rx = rand(20, 1);
		$ry = rand(20, 1);

		//Читаем карту...
		$fp = 0;
		$file = fopen("maps/".$rx."x".$ry.".map", "r");
		for ($x = 1; $x < 11; $x++)
		{
			for ($y = 1; $y < 11; $y++)
			{

				//Читаем клетку
				$map[$x][$y] = "0*0=0";
				$map[$x][$y] = fgets($file, 255);
				$fld = $map[$x][$y];

				//Если клетка свободна
				if (($fld[0] != '4')&&($fld[2] == '0')&&($fld[4] == '0'))
				{
					$fp++;
				}//Если клетка не свободна
			} //$y
		} //$x
		fclose($file);

		//Если количество больше нуля
		if ($fp != 0)
		{
			//Генерируем случайное место на данной подкарте
			$pk = 0;
			while ($pk == 0)
			{
				//Случайное место
				$cx = rand(10, 1);
				$cy = rand(10, 1);
				$fld = $map[$cx][$cy];

				//Если оно свободно, обосновываемся тут.
				if (($fld[0] != '4')&&($fld[2] == '0')&&($fld[4] == '0'))
				{
					//Добавляем игрока сюда
					$map[$cx][$cy] = $fld[0]."*".$fld[2]."=".$lgn."\n";

					//Заносим в базу информацию о столице
					mysql_query("insert into coords values ('".$lgn."', '".$rx."', '".$ry."', '".$cx."', '".$cy."');");

					//Заносим в базу информацию о столице
					mysql_query("insert into capital values ('".$lgn."', '".$rx."', '".$ry."', '".$cx."', '".$cy."');");

					//Сохраняем карту
/*					$file = fopen("maps/".$rx."x".$ry.".map", "w");
					for ($x = 1; $x < 11; $x++)
					{
						for ($y = 1; $y < 11; $y++)
						{
							fputs($file, $map[$x][$y]);
						} //$y
					} //$x
					fclose($file); */

					//Генерация завершена
					$pk = 1;
					$ok = 1;
				} //Обоснование на свободном месте
			} //$pk
		} //$fp
	} //$ok
} //End of function

//Разброс игроков по карте
function placeplayers($login)
{
	//Счётчик
	$all = 0;

	//Только администратр может
	if ($login == 'Admin')
	{
		//Выбираем всех пользователей
		$ath = mysql_query("select * from users;");

		//Для каждого пользователя игры генерируем место на карте
		if ($ath)
		{
			//Для каждого
			while ($rw = mysql_fetch_row($ath))
			{
				//Имя пользователя
				$lgn = $rw[0];
				randomplace($lgn);
				$all++;
			}
		}
	}

	//Отчёт
	echo("<br>Размещено игроков: ".$all);
}

//Проверка на пустые замки
function replace()
{
	//Массив карты
	$map[15][15] = 0;

	//Сколько всего
	$itog = 0;

	//Флаг перезаписи
	$rw = 0;

	//Читаем все файлы в массив
	for ($i = 1; $i < 21; $i++)
	{
		for ($j = 1; $j < 21; $j++)
		{
			//Определяем имя файла
			$rx = $i;
			$ry = $j;
			$file = fopen("maps/".$rx."x".$ry.".map", "r");
			$rw = 0;

			//Карта
			for ($x = 1; $x < 11; $x++)
			{
				//Новая ячейка таблицы
				for ($y = 1; $y < 11; $y++)
				{
					//Получаем данные из ячейки
					$fld = fgets($file, 255);
					$map[$x][$y] = $fld;

					//Чья клетка?
					$t = trim(substr($fld, 4));
	
					//Пусто или нет?
					if (!empty($t))
					{
						//Если кто-то есть
						if (($t != '0')&&($t != 'BANK'))
						{
							$itog++;
							$s = $fld[0].$fld[1].$fld[2].$fld[3]."0\n";
							$map[$x][$y] = $s;
							mysql_query("delete from coords where login = '".$t."';");
							$rw = 1;
						}
					}

				//Завершаем $y цикл
				}
			//Завершаем $x цикл
			}

			//Закрываем файл
			fclose($file);

			if ($rw == 1)
			{
				//Переписываем карту
				$file = fopen("maps/".$rx."x".$ry.".map", "w");
				for ($x = 1; $x < 11; $x++)
				{
					//Новая ячейка таблицы
					for ($y = 1; $y < 11; $y++)
					{
						fputs($file, $map[$x][$y]);
					}
				}
				fclose($file);
			}
		}
	}

	//Отчёт
	echo("Перегруппировка завершена. Перегруппировано замков: ".$itog);
}

//Перезамена
$lg = trim($HTTP_COOKIE_VARS["nativeland"]);

//Соединение с базой...
baselink();
srand(20);
replace();
placeplayers($lg);

?>