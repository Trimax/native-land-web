<body background="images/back.jpe">
<link rel='stylesheet' type='text/css' href='style.css'/>
<title>������� �������</title>

<?

	//�����������
	function moveto($page)
	{
	   echo ("<script>window.location.href('".$page."');</script>");
	}

	//�������������
	$lg = trim($HTTP_COOKIE_VARS["nativeland"]);

	//�� �������, ���� ����� ����
	if ($lg != $login) 
	{
		exit();
	}

	//������� ����
	echo ("<center><table border=1 width=95% CELLSPACING=0 CELLPADDING=0>");
	echo ("<tr><td align=center colspan=2><b><font size=4>��������� ������� �������</font></b></td></tr>");

	//�������� ������ ���� ���������	
	$dir_rec= dir("data/news");
	$i = 0;
	while ($entry = $dir_rec->read())
	{
	   if (substr($entry,0,3)=="rec")
		  {
	      $names[$i]=trim(substr($entry,4));
		  $i++;
	      }
	   }
	$dir_rec->close();
	$count = $i;
	@rsort($names);
	
	//������ ������� �� �������
	for ($i = 0; $i < $count; $i++)
	{
		$entry = $names[$i];
		if (($login != 'Admin')&&($login != 'PANTERKA')) 
		{
			echo("<tr><td align=center colspan=2 width=80%>");
		}
		else
		{
			echo("<tr><td align=center>");
		}
		
		//������ ���� �� �����
		$data = fopen("data/news/rec.".$entry, "r");
		while (!feof($data))
		{
		   echo (fgets($data, 255)."<br>");
		}

	//����� ������� ������
	echo("</td></tr>");
	fclose ($data);
	}

	//����� �������
	echo ("</table></center>");
?>