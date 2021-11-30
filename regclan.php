<?
include "functions.php";

//Цена на регистрацию клана (курс 1 к 1)
$cena = 5000000;

//Есть уже?
function hasclan($username)
{
//link();
$usr = mysql_query("select * from clans;");
$find = 0;
if ($usr)
   {
   while ($user = mysql_fetch_array($usr))
      {
      if (($user['clan'] == $username))
         {
         $find = 1;
		 }
	  }
   }
   else
	{
	   $find = 2;
	}
return $find;
}

//Не админ ли клана есть мы
function isnotadmin($username)
{
//link();
$usr = mysql_query("select * from clans;");
$find = 0;
if ($usr)
   {
   while ($user = mysql_fetch_array($usr))
      {
      if (($user['login'] == $username))
         {
         $find = 1;
		 }
	  }
   }
   else
	{
	   $find = 2;
	}
return $find;
}

//Получаем ЛОГИН пользователя
$lg = trim($HTTP_COOKIE_VARS["nativeland"]);
if (hasuser($lg) != 1) 
{
   exit();
}

//Если место на карте не то (20х18 - региональное место положения ВАНК)
$rx = getdata($lg, 'coords', 'rx');
$ry = getdata($lg, 'coords', 'ry');

//Проверяем
if (($rx == 20)&&($ry == 18))
{
	//Всё ОК
} else
{
//	exit();
}

//Может быть уже подтвердили уже
if (!empty($clan))
{
	//Проверяем наличность...
	$nal = getdata($lg, 'economic', 'money');
	$cr = getdata($lg, 'economic', 'curse');
  $std = $cena*$cr;

	//Проверка
	if ($nal <= $std)
	{
		messagebox("<center>У Вас недостаточно денег. Для регистрации клана необходимо ".$std." ".getdata($lg, 'economic', 'moneyname')."</center>", "regclan.php");
	} else
	{
		//Проверяем заполненность
		if (empty($clan)||empty($description)||empty($link)||empty($logo)||empty($gerb)||empty($procent))
		{
			messagebox("<center>Необходимо заполнить все поля</center>", "regclan.php");
		} else
		{
			//Может клан с таким именем уже есть
			if (hasclan($clan) == 1)
			{
				messagebox("<center>Клан с таким названием уже зарегистрирван. Придумайте себе другое.</center>", "regclan.php");
			} else
			{
				//Если налог не число
				if (preg_match("/[0-9]/i", $procent))
				{

					//Ещё одна проверка. Последняя
					if (($rx == 20)&&($ry == 18))
					{
						//Всё ОК
					} else
					{
 						//exit();
					}

					//Отнимаем деньги
					change($lg, 'economic', 'money', $nal - $std);

					//Регистрируем клан
					mysql_query("insert into clans values ('$clan', '$lg', '$description', '$link', '$logo', '$gerb', '$procent', '100', '0', '0', '0');");

					//Заносим администратора в клан
					change($lg, 'inclan', 'clan', $clan);
					change($lg, 'inclan', 'status', 'Admin');

					//Готово
					messagebox("<center>Ваш клан $clan успешно зарегистрирован</center>", "city.php?login=".$lg);
				} else
				{
					messagebox("<center>Налог должен быть числом. Причём, число это должно быть меньше 90</center>", "regclan.php");
				}
			} //OK
		} //Не все поля заполнены
		exit();
	} //Денег мало
	moveto('regclan.php');
}

//Заголовок страницы
echo("<link rel='stylesheet' type='text/css' href='style.css'/>");
echo("<title>Великая ассоциация по наблюдению за кланами</title>");
echo("<body background=images/back.jpe>");
if (isnotadmin($lg) == 0)
{
	echo("<center>");
	echo("<form action=regclan.php method=post>");
	echo("<input type=hidden name=login value=".$lg.">");
	echo("<h2>Здание администрации кланов</h2>");
  echo("<a href=city.php?login=".$lg.">Вернуться назад в город</a><br>");
	echo("<h3>Добро пожаловать, ".getdata($lg, 'hero', 'name')."</h3>");
	echo("<table border=1 CELLSPACING=0 CELLPADDING=0 width=60%>");
	echo("<tr><td colspan=2 align=center>Регистрация нового клана</td></tr>");
	echo("<tr><td align=center width=30%>Название клана:</td><td align=center><input type=text name=clan style='background:#000099; font-WEIGHT: BOLD; COLOR: white; cursor: hand; list-style: lower-alpha; filter: Alpha(Opacity=20, FinishOpacity=100, Style=3)'></td></tr>");
	echo("<tr><td align=center>Описание клана</td><td align=center><textarea name='description' maxlength=200 cols=60 rows=15 maxlength=255 style='background:#000099; font-WEIGHT: BOLD; COLOR: white; cursor: hand; list-style: lower-alpha; filter: Alpha(Opacity=20, FinishOpacity=100, Style=3)'></textarea></td></tr>");
	echo("<tr><td align=center width=30%>Ссылка на форум клана:</td><td align=center><input type=text name=link style='background:#000099; font-WEIGHT: BOLD; COLOR: white; cursor: hand; list-style: lower-alpha; filter: Alpha(Opacity=20, FinishOpacity=100, Style=3)'></td></tr>");
	echo("<tr><td align=center width=30%>Ссылка на логотип клана:</td><td align=center><input type=text name=logo style='background:#000099; font-WEIGHT: BOLD; COLOR: white; cursor: hand; list-style: lower-alpha; filter: Alpha(Opacity=20, FinishOpacity=100, Style=3)'></td></tr>");
	echo("<tr><td align=center width=30%>Ссылка на герб клана:</td><td align=center><input type=text name=gerb style='background:#000099; font-WEIGHT: BOLD; COLOR: white; cursor: hand; list-style: lower-alpha; filter: Alpha(Opacity=20, FinishOpacity=100, Style=3)'></td></tr>");
	echo("<tr><td align=center width=30%>Налог для соклановцев [в час] (в деньгах по курсу 1 к 1):</td><td align=center><input type=text name=procent value=10 style='background:#000099; font-WEIGHT: BOLD; COLOR: white; cursor: hand; list-style: lower-alpha; filter: Alpha(Opacity=20, FinishOpacity=100, Style=3)'></td></tr>");
	echo("<tr><td colspan=2 align=center><input type=submit value='Зарегистрировать клан' style='background:#000099; font-WEIGHT: BOLD; COLOR: white; cursor: hand; list-style: lower-alpha; filter: Alpha(Opacity=20, FinishOpacity=100, Style=3)'>");
  echo("</td></tr>");
	echo("</table>");
	echo("</form>");
  HelpMe(10, 1);
}
else
{
	messagebox("Вы уже являетесь главой клана клана", "javascript:window.close();");
}
echo("</center>");

?>