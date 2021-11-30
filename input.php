<script>
function biggrin()
{
	document.forms[0].say.value = document.forms[0].say.value + ":)";
}
function confused()
{
	document.forms[0].say.value = document.forms[0].say.value + ":/";
}
function cry()
{
	document.forms[0].say.value = document.forms[0].say.value + ":(";
}
function eek()
{
	document.forms[0].say.value = document.forms[0].say.value + "8)";
}
function wink()
{
	document.forms[0].say.value = document.forms[0].say.value + ";)";
}
function surprised()
{
	document.forms[0].say.value = document.forms[0].say.value + ";0";
}
function cool()
{
	document.forms[0].say.value = document.forms[0].say.value + ":0";
}
function evil()
{
	document.forms[0].say.value = document.forms[0].say.value + ">:";
}
function lol()
{
	document.forms[0].say.value = document.forms[0].say.value + "8^";
}
function mad()
{
	document.forms[0].say.value = document.forms[0].say.value + "/:";
}
function mrgreen()
{
	document.forms[0].say.value = document.forms[0].say.value + ":]";
}
function neutral()
{
	document.forms[0].say.value = document.forms[0].say.value + ":|";
}
function razz()
{
	document.forms[0].say.value = document.forms[0].say.value + ":>";
}
function redface()
{
	document.forms[0].say.value = document.forms[0].say.value + "<:";
}
function rolleyes()
{
	document.forms[0].say.value = document.forms[0].say.value + ":?";
}
function twisted()
{
	document.forms[0].say.value = document.forms[0].say.value + ":!";
}

function ok()
{
    document.forms[0].say.focus();
}
</script>
<center>
<form action=input.php method=post onsubmit="javascript:ok();">
<table border=0>
<tr><td><input type=text selected maxlength=100 name=say><tt><input type=submit value='Сказать'></td></tr>
<tr><td align=center>
<a href="javascript:biggrin();"><img src=images/smiles/icon_biggrin.gif border=0></a>
<a href="javascript:cry();"><img src=images/smiles/icon_cry.gif border=0></a>
<a href="javascript:eek();"><img src=images/smiles/icon_eek.gif border=0></a>
<a href="javascript:wink();"><img src=images/smiles/icon_wink.gif border=0></a>
<a href="javascript:surprised();"><img src=images/smiles/icon_surprised.gif border=0></a>
<a href="javascript:confused();"><img src=images/smiles/icon_confused.gif border=0></a>
<a href="javascript:cool();"><img src=images\smiles\icon_cool.gif border=0>
<a href="javascript:evil();"><img src=images\smiles\icon_evil.gif border=0>
<a href="javascript:lol();"><img src=images\smiles\icon_lol.gif border=0>
<a href="javascript:mad();"><img src=images\smiles\icon_mad.gif border=0>
<a href="javascript:mrgreen();"><img src=images\smiles\icon_mrgreen.gif border=0>
<a href="javascript:neutral();"><img src=images\smiles\icon_neutral.gif border=0>
<a href="javascript:razz();"><img src=images\smiles\icon_razz.gif border=0>
<a href="javascript:redface();"><img src=images\smiles\icon_redface.gif border=0>
<a href="javascript:rolleyes();"><img src=images\smiles\icon_rolleyes.gif border=0>
<a href="javascript:twisted();"><img src=images\smiles\icon_twisted.gif border=0>
</td></tr>
</form>
</table>
</center>
<script>
ok();
</script>

<?

//Функции для работы со строками
function replace($txt)
{
	$msg = "";
	for ($i = 0; $i < strlen($txt); $i++)
	{

		//Смайлик?
		$yes = 0;

		//Улыбка
		if (substr($txt, $i, 2) == ":)")
		{
			$msg = $msg."<img src=images/smiles/icon_biggrin.gif>";
			$yes = 1;
			$i++;
		}

		//Ничего, просто добавляем символ
		if ($yes == 0)
		{
			$msg = $msg.$txt[$i];
		}
	}
	$txt = $msg;
}

//Заголовок страницы
echo ("<html>\n<head>\n<link rel='stylesheet' type='text/css' href='style.css'/>\n<title>Native Land</title>\n</head>\n<body background='images\back.jpe'>");

//Если не указано имя пользователя, то выкинуть юзера нафиг
$lg = trim($HTTP_COOKIE_VARS["nativeland"]);
$pw = trim($HTTP_COOKIE_VARS["password"]);

if (empty($say)||(empty($lg))) {exit();}

//Читаем старый файл
$msg[0] = "NULL";
$file = fopen("data/chat.txt", "r+");
for ($i = 1; $i < 10; $i++)
{
	$msg[$i] = fgets($file, 255);
}
fclose ($file);

//Пишем мессагу
$msg[0] = "<font color=green>".$lg."</font><font color=blue> >>> ".$say;

//Заменяем на смайлики
$txt = "";

//Ну и?
for ($i = 0; $i < strlen($msg[0]); $i++)
{

	//Смайлик?
	$yes = 0;

	//Улыбка
	if (substr($msg[0], $i, 2) == ":)")
	{
		$txt = $txt."<img src=images/smiles/icon_biggrin.gif>";
		$yes = 1;
		$i++;
	}

	//Досада
	if (substr($msg[0], $i, 2) == ":(")
	{
		$txt = $txt."<img src=images/smiles/icon_cry.gif>";
		$yes = 1;
		$i++;
	}

	//Очки
	if (substr($msg[0], $i, 2) == "8)")
	{
		$txt = $txt."<img src=images/smiles/icon_eek.gif>";
		$yes = 1;
		$i++;
	}

	//Подмигивает
	if (substr($msg[0], $i, 2) == ";)")
	{
		$txt = $txt."<img src=images/smiles/icon_wink.gif>";
		$yes = 1;
		$i++;
	}

	//Рот открыт + мигает
	if (substr($msg[0], $i, 2) == ";0")
	{
		$txt = $txt."<img src=images/smiles/icon_surprised.gif>";
		$yes = 1;
		$i++;
	}
	//Ещё смайл
	if (substr($msg[0], $i, 2) == ":/")
	{
		$txt = $txt."<img src=images/smiles/icon_confused.gif>";
		$yes = 1;
		$i++;
	}
	//Ещё смайл
	if (substr($msg[0], $i, 2) == ":0")
	{
		$txt = $txt."<img src=images/smiles/icon_cool.gif>";
		$yes = 1;
		$i++;
	}
	//Ещё смайл
	if (substr($msg[0], $i, 2) == ">:")
	{
		$txt = $txt."<img src=images/smiles/icon_evil.gif>";
		$yes = 1;
		$i++;
	}
	//Ещё смайл
	if (substr($msg[0], $i, 2) == "8^")
	{
		$txt = $txt."<img src=images/smiles/icon_lol.gif>";
		$yes = 1;
		$i++;
	}
	//Ещё смайл
	if (substr($msg[0], $i, 2) == "/:")
	{
		$txt = $txt."<img src=images/smiles/icon_mad.gif>";
		$yes = 1;
		$i++;
	}
	//Ещё смайл
	if (substr($msg[0], $i, 2) == ":]")
	{
		$txt = $txt."<img src=images/smiles/icon_mrgreen.gif>";
		$yes = 1;
		$i++;
	}
	//Ещё смайл
	if (substr($msg[0], $i, 2) == ":|")
	{
		$txt = $txt."<img src=images/smiles/icon_neutral.gif>";
		$yes = 1;
		$i++;
	}
	//Ещё смайл
	if (substr($msg[0], $i, 2) == ":>")
	{
		$txt = $txt."<img src=images/smiles/icon_razz.gif>";
		$yes = 1;
		$i++;
	}
	//Ещё смайл
	if (substr($msg[0], $i, 2) == "<:")
	{
		$txt = $txt."<img src=images/smiles/icon_redface.gif>";
		$yes = 1;
		$i++;
	}
	//Ещё смайл
	if (substr($msg[0], $i, 2) == ":?")
	{
		$txt = $txt."<img src=images/smiles/icon_rolleyes.gif>";
		$yes = 1;
		$i++;
	}
	//Ещё смайл
	if (substr($msg[0], $i, 2) == ":!")
	{
		$txt = $txt."<img src=images/smiles/icon_twisted.gif>";
		$yes = 1;
		$i++;
	}

	//Ничего, просто добавляем символ
	if ($yes == 0)
	{
		$txt = $txt.$msg[0][$i];
	}
}

//Ура
$msg[0] = $txt."</font><font color=white> (".date("H:i:s", time()).")</font><br>\n";

//Кидаем назад в файл инфу
$file = fopen("data/chat.txt", "w+");
for ($i = 0; $i < 10; $i++)
{
	fputs ($file, $msg[$i]);
}
fclose ($file);
?>