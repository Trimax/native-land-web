<body background="images/back.jpe">
<link rel='stylesheet' type='text/css' href='style.css'/>
<title>�������</title>

<?

  Error_Reporting(E_ALL & ~E_NOTICE);

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

	//����� ��������?
	if ($kick == 1)
	{
		//� ������ ����?
		if (($login == 'Admin')||($login == 'PANTERKA')) 
		{
			//������� ������
			$name = "data/news/rec.".$num;
			unlink ($name);
			moveto('tavern.php?login='.$login);
		}
	}

	//����� ����������?
	if ($add == 1)
	{
		//� ������ ����?
		if (($login == 'Admin')||($login == 'PANTERKA')) 
		{
			//������ ������
			$file = fopen ("data/news/rec.".time(), "w");
			fputs ($file, $msg."\n");
			fclose ($file);
			moveto('tavern.php?login='.$login);
		}
	}

	//������� ����
	echo ("<center><a href=city.php?login=".$login.">����� �� �������</a><br><br><table border=1 width=90% CELLSPACING=0 CELLPADDING=0>");
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

	if (($login == 'Admin')||($login == 'PANTERKA')) 
	{
		echo("</td><td align=center>");
		echo("<form action='tavern.php' method=post><input type='hidden' name='num' value=$entry><input type='hidden' name='kick' value=1><input type='hidden' name='login' value='$login'><br><input type='submit' value='�������' style='background:#000099; font-WEIGHT: BOLD; COLOR: white; cursor: hand; list-style: lower-alpha; filter: Alpha(Opacity=20, FinishOpacity=100, Style=3)'></form>");
	}
	//����� ������� ������
	echo("</td></tr>");
	fclose ($data);
	}

	//����������� ���������� ��������
	if (($login == 'Admin')||($login == 'PANTERKA')) 
	{
		echo("<tr><td align=center colspan=2><b>�������� �������</b><form action='tavern.php' method=post><input type='hidden' name='num' value=$entry><input type='hidden' name='add' value=1><input type='hidden' name='login' value='$login'><textarea name='msg' cols=70 rows=15 maxlength=255 style='background:#000099; font-WEIGHT: BOLD; COLOR: white; cursor: hand; list-style: lower-alpha; filter: Alpha(Opacity=20, FinishOpacity=100, Style=3)'></textarea><br><br><input type='submit' value='�������� �������' style='background:#000099; font-WEIGHT: BOLD; COLOR: white; cursor: hand; list-style: lower-alpha; filter: Alpha(Opacity=20, FinishOpacity=100, Style=3)'></form></td></tr>");
	}

	//����� �������
	echo ("</table></center>");
?>