<body background="images/back.jpe">
<link rel='stylesheet' type='text/css' href='style.css'/>
<title>�������� ������</title>
<?
include "functions.php";

//�������� �����������
$lg = trim($HTTP_COOKIE_VARS["nativeland"]);
$pw = trim($HTTP_COOKIE_VARS["password"]);

if (finduser($lg, $pw) == 0)
{
	exit();
}

FromBattle($lg);

//����
if ((issubadmin($lg) == 0)&&(isadmin($lg) == 0))
{
	exit();
}

//����:
if (!empty($cheat))
{
	//�������� �������
	if ($cheat == 1)
	{
		$level = getdata($lg, 'hero', 'level');
    $expa = round(60*pow($level, 1.4));
		change($lg, 'hero', 'expa', $expa);
	}

	//�������� �����
	if ($cheat == 2)
	{
		$curse = getdata($lg, 'economic', 'curse');
		change($lg, 'economic', 'money', getdata($lg, 'economic', 'money') + 10000*$curse);
	}

	//�������� ��������
	if ($cheat == 3)
	{
		change($lg, 'economic', 'metal', getdata($lg, 'economic', 'metal') + 100);
		change($lg, 'economic', 'rock', getdata($lg, 'economic', 'rock') + 100);
		change($lg, 'economic', 'wood', getdata($lg, 'economic', 'wood') + 100);
	}

	//�������� �����
	if ($cheat == 4)
	{
		change($lg, 'economic', 'peoples', getdata($lg, 'economic', 'peoples') + 10000);
	}

  //�������� ���. �����������
  if ($cheat == 5)
  {
    //��������� ��� ��������� ��������������
    change($lg, 'abilities', 'combatmagic', newchar($lg, 'combatmagic'));
    change($lg, 'abilities', 'mindmagic',   newchar($lg, 'mindmagic'));	
  }
	moveto('cheats.php');
}

?>
<center>
<h2>������ �������������</h2>
<form action="cheats.php" method=post>
<input type="hidden" name="cheat" value=1>
<input type="submit" value="�������� �������" style='background:#000099; font-WEIGHT: BOLD; COLOR: white; cursor: hand; list-style: lower-alpha; filter: Alpha(Opacity=20, FinishOpacity=100, Style=3)'>
</form>

<form action="cheats.php" method=post>
<input type="hidden" name="cheat" value=2>
<input type="submit" value="�������� �����" style='background:#000099; font-WEIGHT: BOLD; COLOR: white; cursor: hand; list-style: lower-alpha; filter: Alpha(Opacity=20, FinishOpacity=100, Style=3)'>
</form>

<form action="cheats.php" method=post>
<input type="hidden" name="cheat" value=3>
<input type="submit" value="�������� ��������" style='background:#000099; font-WEIGHT: BOLD; COLOR: white; cursor: hand; list-style: lower-alpha; filter: Alpha(Opacity=20, FinishOpacity=100, Style=3)'>
</form>

<form action="cheats.php" method=post>
<input type="hidden" name="cheat" value=4>
<input type="submit" value="��������� �����" style='background:#000099; font-WEIGHT: BOLD; COLOR: white; cursor: hand; list-style: lower-alpha; filter: Alpha(Opacity=20, FinishOpacity=100, Style=3)'>
</form>

<form action="cheats.php" method=post>
<input type="hidden" name="cheat" value=5>
<input type="submit" value="�������� ���. �����������" style='background:#000099; font-WEIGHT: BOLD; COLOR: white; cursor: hand; list-style: lower-alpha; filter: Alpha(Opacity=20, FinishOpacity=100, Style=3)'>
</form>
</center>
<?

?>