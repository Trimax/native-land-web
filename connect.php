<?
/*
 * ����, ����������� ��������� ������� ��� ����� ������� ������
*/

//���������� ������ ������
Error_Reporting(E_ALL & ~E_NOTICE);

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

//���������� � �����...
baselink();

//�������� ����� ������������ � ������
function finduser($username, $pass)
{
//link();
$usr = mysql_query("select * from users;");
$find = 0;
if ($usr)
   {
   while ($user = mysql_fetch_array($usr))
      {
      if (($user['login'] == $username)&&($user['pwd'] == $pass))
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

//��������� ������ �� ������� �� ����� ����, ����� ������������ � ����� �������
function getdata($username, $table, $field)
{
//link();
$usr = mysql_query("select * from ".$table.";");
$find = "";
if ($usr)
   {
   while ($user = mysql_fetch_array($usr))
      {
      if (($user['login'] == $username))
         {
         $find = $user[$field];
		 }
	  }
   }
   else
	{
	   $find = "<font color=red>������ �����������</font>";
	}
	return $find;
}

//��������� ������ � ����
function change($username, $table, $field, $value)
{
	mysql_query("update ".$table." set ".$field." = '".$value."' where login = '".$username."';");
}

//�������� �� ����
function delfrom($fld, $value, $table)
{
	mysql_query("delete from ".$table." where ".$fld." = '".$value."';");
}

//��������� ������ � ����
function setto($fld, $value, $table, $field, $new)
{
	mysql_query("update ".$table." set ".$field." = '".$new."' where ".$fld." = '".$value."';");
}

//��������� ������ �� ������� �� ����� ����, ����� ������������ � ����� �������
function getfrom($fld, $value, $table, $field)
{
//link();
$usr = mysql_query("select * from ".$table.";");
$find = "";
if ($usr)
   {
   while ($user = mysql_fetch_array($usr))
      {
      if (($user[$fld] == $value))
         {
         $find = $user[$field];
		 }
	  }
   }
   else
	{
	   $find = "<font color=red>������ �����������</font>";
	}
	return $find;
}

//����� �� ������������
function isadmin($username)
{
//link();
$usr = mysql_query("select * from settings");
$find = 0;
if ($usr)
   {
   while ($user = mysql_fetch_array($usr))
      {
      if (($user['admin'] == $username))
         {
         $find = 1;
		 }
	  }
   }
   else
	{
	   $find = 0;
	}
	return $find;
}

//������������ ������ (���, �������, ���)
function sortgenerate($name, $table, $row)
{
	$count=0;
	$ath = mysql_query("select * from ".$table.";");
	if ($ath)
	{
		while ($rw = mysql_fetch_row($ath))
		{
			$info[$count] = $rw[$row];
			$count++;
		}
	}

	//����������
	@sort($info);
	echo ("<select name='$name' style='background:#000099; font-WEIGHT: BOLD; COLOR: white; cursor: hand; list-style: lower-alpha; filter: Alpha(Opacity=20, FinishOpacity=100, Style=3)'>");
	for ($i = 0; $i < $count; $i++)
		echo("<option name='".$info[$i]."'>".$info[$i]."</option>");
	echo("</select>");

	//������ ������ ������!
}

//�����������
function moveto($page)
{
   echo ("<script>window.location.href('".$page."');</script>");
}

//�������������
$lg = trim($HTTP_COOKIE_VARS["nativeland"]);
$pw = trim($HTTP_COOKIE_VARS["password"]);
if (finduser($lg, $pw) != 1) 
{
	exit();
}

?>