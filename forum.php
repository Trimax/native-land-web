<body background="images/back.jpe">
<link rel='stylesheet' type='text/css' href='style.css'/>
<title>�����</title>

<?
include "connect.php";

//�������� ������ �� cookies
$lg = trim($HTTP_COOKIE_VARS["nativeland"]);
$pw = trim($HTTP_COOKIE_VARS["password"]);

//������������ �������, ������������ ���������� � ���������� ����� ��������� � ������
function messages($category, $forum)
{
	//0) ���� ��� ���������� (0 - ���;  < 0 - ��� ���������; > 0 ���� �����)
	$flag = 0;
	$one = 0; //������ ���� �����
	$count = 0; //���������� �����

	//1) ������� (�� ��������� 2 ����)
	$lasttime = time() - 2*3600;

	//2) �������� ������ ���� ��� �� �������� ������ � �� ������� ���������
	$ath = mysql_query("select * from forum_subjects;");
	if ($ath)
	{
		//��� ������ ����
		while ($rw = mysql_fetch_row($ath))
		{
			//� ��� ��������� ������ ����?
			if (($rw[3] == $category)&&($rw[2] == $forum))
			{
				//�������� ����� ���������
				$path = "forum/".getfrom('category', $category, 'forum_categories', 'folder')."/".getfrom('forum', $forum, 'forum_forums', 'folder')."/".$rw[1];

				//�������� ������ ���� ������� �� ���������, ������� �� ���������� � ������� ���������� ������
				$dir_rec= dir($path);
				while ($entry = $dir_rec->read())
				{
				   if (substr($entry,0,3)=="rec")
				  {
					   //�������� ����� ���������� ���������
					   $names[$i]=trim(substr($entry,4));
					   (int)$msgtime = $names[$i];
		
						//���� ��� ������, ��� ��� ��������� ����, �� ������ ���� ��� ���� �����
						if ($msgtime > $lasttime)
						{
							$one = 1;
						}
					   $count++;
			      } //rec
				} //While $entry
				$dir_rec->close();
			}
		} //While $rw
	}
	
	//���������� ����������
	$flag = $count;

	//���� ����� ���, �� �������� ���� �� -1
	if ($one == 0)
	{
		$flag = $flag * (-1);
	}

	//���������� ���������
	return $flag;
}

//������� ���������
if ($message == 1)
{
	?>
		<script>
			window.open('sendmail.php', null,'toolbar=no, location=no, menubar=no,scrollbars=no,width=656,height=480');
		</script>
	<?
}

//�������� ����� ��������� (������ �������������)
if (($newpart == 1)&&(isadmin($lg) == 1))
{
	echo("<form action='forum.php' method=post>");
	echo("<center><table border=1 width=80% cellpadding=0 cellspacing=0>");	
	echo("<tr><td colspan=2 align=center>�������� ����� ���������</td></tr>");
	echo("<tr><td width=40%>�������� ���������</td><td><input type='text' name='cname' style='background:#000099; font-WEIGHT: BOLD; COLOR: white; cursor: hand; list-style: lower-alpha; filter: Alpha(Opacity=20, FinishOpacity=100, Style=3)'></td></tr>");
	echo("<tr><td width=40%>����� ���������</td><td><input type='text' name='fname' style='background:#000099; font-WEIGHT: BOLD; COLOR: white; cursor: hand; list-style: lower-alpha; filter: Alpha(Opacity=20, FinishOpacity=100, Style=3)'></td></tr>");
	echo("<tr><td width=40%>��������� ���������</td><td>");
	sortgenerate('moder', 'users', 0);
	echo("</td></tr>");
	echo("<tr><td colspan=2 align=center><input type='submit' value='������� ���������' style='background:#000099; font-WEIGHT: BOLD; COLOR: white; cursor: hand; list-style: lower-alpha; filter: Alpha(Opacity=20, FinishOpacity=100, Style=3)'></td></tr>");
	echo("</table></center>");
	echo("<input type='hidden' name='newpart_step2' value=1>");
	echo("</form>");
	exit();
}

//������������� ��������
if (($newpart_step2 == 1)&&(isadmin($lg) == 1))
{
	//��������� ��������� � ��
	mysql_query("insert into forum_categories values('".$cname."', '".$fname."', '".$moder."');");

	//������ ������� ���������
	mkdir("forum/".$fname,0700);
	moveto('forum.php');
}

//�������� ������ �������
if (($newvol == 1)&&(($lg == $moder)||(isadmin($lg) == 1)))
{
	echo("<form action='forum.php' method=post>");
	echo("<center><table border=1 width=80% cellpadding=0 cellspacing=0>");	
	echo("<tr><td colspan=2 align=center>�������� ������ �������</td></tr>");
	echo("<tr><td width=40%>�������� �������</td><td><input type='text' name='cname' style='background:#000099; font-WEIGHT: BOLD; COLOR: white; cursor: hand; list-style: lower-alpha; filter: Alpha(Opacity=20, FinishOpacity=100, Style=3)'></td></tr>");
	echo("<tr><td width=40%>����� �������</td><td><input type='text' name='fname' style='background:#000099; font-WEIGHT: BOLD; COLOR: white; cursor: hand; list-style: lower-alpha; filter: Alpha(Opacity=20, FinishOpacity=100, Style=3)'></td></tr>");
	echo("<tr><td colspan=2 align=center><input type='submit' value='������� ���������' style='background:#000099; font-WEIGHT: BOLD; COLOR: white; cursor: hand; list-style: lower-alpha; filter: Alpha(Opacity=20, FinishOpacity=100, Style=3)'></td></tr>");
	echo("</table></center>");
	//������� ���������� � ��������� ����
	echo("<input type='hidden' name='newvol_step2' value=1>");
	//��������� ���������� ���������
	echo("<input type='hidden' name='moder' value='".$moder."'>");
	//������� �������� ���������
	echo("<input type='hidden' name='directory' value='".$volinpart."'>");
	echo("</form>");
	exit();
}

//��������� �������, ��������� �� ���� ������
if (($newvol_step2 == 1)&&(isadmin($lg) == 1))
{
	//��������� ��������� � ��
	mysql_query("insert into forum_forums values('".$cname."', '".$fname."', '".$directory."');");

	//������ ������� ���������
	mkdir("forum/".$directory."/".$fname,0700);
	moveto('forum.php');
}

//������������� �������� ����� ����
if (($newsubject == 1)&&(finduser($lg, $pw) == 1)&&(!empty($cname)))
{
	//��������� ��������� � ��
	mysql_query("insert into forum_subjects values('".$cname."', '".$cname."', '".$forum."', '".$category."', '0', '".$lg."', '1');");

	//������ ������� ����
	$path = "forum/".getfrom('category', $category, 'forum_categories', 'folder')."/".getfrom('forum', $forum, 'forum_forums', 'folder')."/".$cname;
	mkdir($path,0700);
	moveto('forum.php?category='.$category."&forum=".$forum."&subject=".$cname);
}

//�������� ���� �����������
if (($closesubject == 1)&&(($lg == getfrom('categories', $category, 'forum_categories', 'moderator'))||(isadmin($lg) == 1)))
{
	setto('subject', $subject, 'forum_subjects', 'closed', '1');
	moveto("forum.php?category=".$category."&forum=".$forum);
}

//�������� ���� �����������
if (($opensubject == 1)&&(($lg == getfrom('categories', $category, 'forum_categories', 'moderator'))||(isadmin($lg) == 1)))
{
	setto('subject', $subject, 'forum_subjects', 'closed', '0');
	moveto("forum.php?category=".$category."&forum=".$forum);
}

//�������� ���� �����������
if (($erasesubject == 1)&&(($lg == getfrom('categories', $category, 'forum_categories', 'moderator'))||(isadmin($lg) == 1)))
{
	$path = "forum/".getfrom('category', $category, 'forum_categories', 'folder')."/".getfrom('forum', $forum, 'forum_forums', 'folder')."/".$subject;

	//�������� ������ ���� ������� �� ��������� � ������� ���
	$dir_rec= dir($path);
	$i = 0;
	while ($entry = $dir_rec->read())
	{
	   if (substr($entry,0,3)=="rec")
	  {
	      $names[$i]=trim(substr($entry,4));
		  $name = $path."/rec.".$names[$i];
		  unlink ($name);
	      $i++;
      }
	}
	$dir_rec->close();
	$count = $i;
	
	//������� ���� ����������
	rmdir($path);

	//������� ������ �� ��
	delfrom('subject', $subject, 'forum_subjects');

	//������������ � �����
	moveto("forum.php?category=".$category."&forum=".$forum);
}

//�������� ����� �� ����
if ($postreply == 1)
{
	//� �� ������� �� ����?
	if ((getfrom('subject', $subject, 'forum_subjects', 'closed') == 0)&&(getfrom('subject', $subject, 'forum_subjects', 'category') == $category)&&(getfrom('subject', $subject, 'forum_subjects', 'forum') == $forum))
	{
		//��������� ������� ������� ������
		?>
		<script language=JavaScript>
		function DoSmile(Code) 
		{
			document.forms[0].message.value = document.forms[0].message.value + Code;
			document.forms[0].message.focus();
			return;
		}
		</script>
		<?

		//������ ����� ����
		echo("<form name=data action='forum.php' method=post>");
		echo("<center><table border=1 width=80% cellpadding=0 cellspacing=0>");	
		echo("<tr><td align=center>����� �� ��������� ".$subject."</td></tr>");
		echo("<tr><td align=center>���� ���������:<br><textarea name='message' cols=70 rows=15 style='background:#000099; font-WEIGHT: BOLD; COLOR: white; cursor: hand; list-style: lower-alpha; filter: Alpha(Opacity=20, FinishOpacity=100, Style=3)'></textarea></td></td></tr>");
		echo("<tr><td align=center>");
		?>

		<!--  ��������� ������ -->
		<a href="javascript:DoSmile(':)');"><img src=images/smiles/icon_biggrin.gif border=0></a>
		<a href="javascript:DoSmile(':(');"><img src=images/smiles/icon_cry.gif border=0></a>
		<a href="javascript:DoSmile(':)');"><img src=images/smiles/icon_eek.gif border=0></a>
		<a href="javascript:DoSmile('8)');"><img src=images/smiles/icon_wink.gif border=0></a>
		<a href="javascript:DoSmile(';0');"><img src=images/smiles/icon_surprised.gif border=0></a>
		<a href="javascript:DoSmile(':/');"><img src=images/smiles/icon_confused.gif border=0></a>
		<a href="javascript:DoSmile(':0');"><img src=images\smiles\icon_cool.gif border=0>
		<a href="javascript:DoSmile('>:');"><img src=images\smiles\icon_evil.gif border=0>
		<a href="javascript:DoSmile('8^');"><img src=images\smiles\icon_lol.gif border=0>
		<a href="javascript:DoSmile('/:');"><img src=images\smiles\icon_mad.gif border=0>
		<a href="javascript:DoSmile(':]');"><img src=images\smiles\icon_mrgreen.gif border=0>
		<a href="javascript:DoSmile(':|');"><img src=images\smiles\icon_neutral.gif border=0>
		<a href="javascript:DoSmile(':>');"><img src=images\smiles\icon_razz.gif border=0>
		<a href="javascript:DoSmile('<:');"><img src=images\smiles\icon_redface.gif border=0>
		<a href="javascript:DoSmile(':?');"><img src=images\smiles\icon_rolleyes.gif border=0>
		<a href="javascript:DoSmile(':!');"><img src=images\smiles\icon_twisted.gif border=0>
		<?

		//����� ������ � ������ "�������"
		echo("</td></tr>");
		echo("<tr><td colspan=2 align=center><input type='submit' value='������� �����' style='background:#000099; font-WEIGHT: BOLD; COLOR: white; cursor: hand; list-style: lower-alpha; filter: Alpha(Opacity=20, FinishOpacity=100, Style=3)'></td></tr>");
		echo("</table></center>");

		//������� ���������� � ��������� ����
		echo("<input type='hidden' name='postreply_step2' value=1>");

		//��������� �������� ���������
		echo("<input type='hidden' name='category' value='".$category."'>");

		//������� �������� �������
		echo("<input type='hidden' name='forum' value='".$forum."'>");

		//�������� �������� ����
		echo("<input type='hidden' name='subject' value='".$subject."'>");
		echo("</form>");
		exit();
	}
}

//���������������� ���������� �����
if ($postreply_step2 == 1)
{
	//���������� ���� � ����
	$path = "forum/".getfrom('category', $category, 'forum_categories', 'folder')."/".getfrom('forum', $forum, 'forum_forums', 'folder')."/".$subject;

	//������������ ��������� � ������
	$msg[0] = $message;
	for ($i = 0; $i < strlen($msg[0]); $i++)
	{

		//�������?
		$yes = 0;

		//������
		if (substr($msg[0], $i, 2) == ":)")
		{
			$txt = $txt."<img src=images/smiles/icon_biggrin.gif>";
			$yes = 1;
			$i++;
		}

		//������
		if (substr($msg[0], $i, 2) == ":(")
		{
			$txt = $txt."<img src=images/smiles/icon_cry.gif>";
			$yes = 1;
			$i++;
		}

		//����
		if (substr($msg[0], $i, 2) == "8)")
		{
			$txt = $txt."<img src=images/smiles/icon_eek.gif>";
			$yes = 1;
			$i++;
		}

		//�����������
		if (substr($msg[0], $i, 2) == ";)")
		{
			$txt = $txt."<img src=images/smiles/icon_wink.gif>";
			$yes = 1;
			$i++;
		}

		//��� ������ + ������
		if (substr($msg[0], $i, 2) == ";0")
		{
			$txt = $txt."<img src=images/smiles/icon_surprised.gif>";
			$yes = 1;
			$i++;
		}

		//���������
		if (substr($msg[0], $i, 2) == ":/")
		{
			$txt = $txt."<img src=images/smiles/icon_confused.gif>";
			$yes = 1;
			$i++;
		}

		//�����
		if (substr($msg[0], $i, 2) == ":0")
		{
			$txt = $txt."<img src=images/smiles/icon_cool.gif>";
			$yes = 1;
			$i++;
		}

		//��������
		if (substr($msg[0], $i, 2) == ">:")
		{
			$txt = $txt."<img src=images/smiles/icon_evil.gif>";
			$yes = 1;
			$i++;
		}

		//���
		if (substr($msg[0], $i, 2) == "8^")
		{
			$txt = $txt."<img src=images/smiles/icon_lol.gif>";
			$yes = 1;
			$i++;
		}

		//�����������
		if (substr($msg[0], $i, 2) == "/:")
		{
			$txt = $txt."<img src=images/smiles/icon_mad.gif>";
			$yes = 1;
			$i++;
		}

		//������ ������
		if (substr($msg[0], $i, 2) == ":]")
		{
			$txt = $txt."<img src=images/smiles/icon_mrgreen.gif>";
			$yes = 1;
			$i++;
		}

		//�����������
		if (substr($msg[0], $i, 2) == ":|")
		{
			$txt = $txt."<img src=images/smiles/icon_neutral.gif>";
			$yes = 1;
			$i++;
		}

		//�� �����
		if (substr($msg[0], $i, 2) == ":>")
		{
			$txt = $txt."<img src=images/smiles/icon_razz.gif>";
			$yes = 1;
			$i++;
		}

		//������� ���� (��������, ����������)
		if (substr($msg[0], $i, 2) == "<:")
		{
			$txt = $txt."<img src=images/smiles/icon_redface.gif>";
			$yes = 1;
			$i++;
		}

		//���������
		if (substr($msg[0], $i, 2) == ":?")
		{
			$txt = $txt."<img src=images/smiles/icon_rolleyes.gif>";
			$yes = 1;
			$i++;
		}

		//�������
		if (substr($msg[0], $i, 2) == ":!")
		{
			$txt = $txt."<img src=images/smiles/icon_twisted.gif>";
			$yes = 1;
			$i++;
		}

		//������, ������ ��������� ������
		if ($yes == 0)
		{
			$txt = $txt.$msg[0][$i];
		}
	}

	//������ ��������� ��������������� � ���� � ����������� ������� � ����
	$file = fopen($path."/rec.".time(), "w+");
	fputs($file, $lg."\n");
	fputs($file, $txt);
	fclose ($file);

	//������������ � �����
	moveto("forum.php?category=".$category."&forum=".$forum."&subject=".$subject);
}

//������� ����
if (($erasepost == 1)&&(($lg == getfrom('categories', $category, 'forum_categories', 'moderator'))||(isadmin($lg) == 1)))
{
	$file = "forum/".getfrom('category', $category, 'forum_categories', 'folder')."/".getfrom('forum', $forum, 'forum_forums', 'folder')."/".$subject."/rec.".$num;

	//������� 
	unlink($file);
	moveto("forum.php?category=".$category."&forum=".$forum."&subject=".$subject);
}

//===============
//����� ���� ������
//===============

//����� �������
echo("<table border=1 width=100% cellpadding=0 cellspacing=0>");
echo("<tr><td colspan=5 align=center><b>����������� ����� ���� Native Land</b></td></tr>");
echo("<tr><td align=center><br><form action='forum.php' method=post><input type='submit' value='����� ���������' style='background:#000099; font-WEIGHT: BOLD; COLOR: white; cursor: hand; list-style: lower-alpha; filter: Alpha(Opacity=20, FinishOpacity=100, Style=3)'><input type='hidden' name='newpart' value=1></form></td><td align=center><br><form action='forum.php' method=post><input type='submit' value='������� ������������ ���������' style='background:#000099; font-WEIGHT: BOLD; COLOR: white; cursor: hand; list-style: lower-alpha; filter: Alpha(Opacity=20, FinishOpacity=100, Style=3)'><input type='hidden' name='message' value=1></form></td><td align=center><br><form action='forum.php' method=post><input type='submit' value='� ������' style='background:#000099; font-WEIGHT: BOLD; COLOR: white; cursor: hand; list-style: lower-alpha; filter: Alpha(Opacity=20, FinishOpacity=100, Style=3)'></form></td><td align=center><br><form action='game.php?action=1' method=post><input type='submit' value='� ����' style='background:#000099; font-WEIGHT: BOLD; COLOR: white; cursor: hand; list-style: lower-alpha; filter: Alpha(Opacity=20, FinishOpacity=100, Style=3)'></form></td></tr>");
echo("<tr><td colspan=5 align=center>");

//���� ������� ����, ��������� � �����, ���������� ��
if ((!empty($category))&&(!empty($forum)))
{
	//���� ������������ ����� ������ ����� � ����, �� ��������� ��� ����
	if (!empty($subject))
	{
		//���������� ����� ���������
		echo("<table border=1 width=100% cellpadding=0 cellspacing=0>");

		//��������� �����
		echo("<tr>");
			//1) ���� ���� �� �������, �� ���������� ������ "������� �����"
			if (getfrom('subject', $subject, 'forum_subjects', 'closed') == '0')
			{
				echo("<td align=center><br><form action='forum.php' method=post><input type='submit' value='������� �����' style='background:#000099; font-WEIGHT: BOLD; COLOR: white; cursor: hand; list-style: lower-alpha; filter: Alpha(Opacity=20, FinishOpacity=100, Style=3)'><input type='hidden' name='postreply' value=1><input type='hidden' name='category' value='".$category."'><input type='hidden' name='forum' value='".$forum."'><input type='hidden' name='subject' value='".$subject."'></form></td>");
			}

			//2) ������ ��������� ����� � ���� ������
			echo("<td align=center><br><form action='forum.php?category=".$category."&forum=".$forum."' method=post><input type='submit' value='��������� � ������ ".$forum."' style='background:#000099; font-WEIGHT: BOLD; COLOR: white; cursor: hand; list-style: lower-alpha; filter: Alpha(Opacity=20, FinishOpacity=100, Style=3)'></form></td>");

		//����� ��������� �����
		echo("</tr>");

		//����� ������ ��� ���� ������� �� ���������
		echo("<tr><td align=center colspan=4>");

		//�������� ������ ���� ���������
		$path = "forum/".getfrom('category', $category, 'forum_categories', 'folder')."/".getfrom('forum', $forum, 'forum_forums', 'folder')."/".$subject;
		$dir_rec= dir($path);
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

		//������� ��� ���������. ������ � ����� �������. ����� ����� � ������, ������ ���������
		for ($i = 0; $i < $count; $i++)
		{
			//�������� ��� �����
		   $entry = $names[$i];

		   //������ ������� ��� �����
		   echo("<table border=1 width=100% cellpadding=0 cellspacing=0>");

		   //������ ��� ���������� ��������
		   if (($lg == getfrom('category', $category, 'forum_categories', 'moderator'))||(isadmin($lg) == 1))
			{
			   echo("<tr><td align=right colspan=2><form action='forum.php' method=post><input type='hidden' name='num' value=".$entry."><input type='hidden' name='category' value='$category'><input type='hidden' name='forum' value='$forum'><input type='hidden' name='subject' value='$subject'><input type='hidden' name='erasepost' value=1><br><input type='submit' value='�������' style='background:#000099; font-WEIGHT: BOLD; COLOR: white; cursor: hand; list-style: lower-alpha; filter: Alpha(Opacity=20, FinishOpacity=100, Style=3)'></form></td></tr>");
			}
		   
		   //������ ���� � ���������� �� �����
		   $data = fopen($path."/rec.".$entry, "r");
		   $who =trim(fgets($data, 255));
		   $avatar = 'images/photos/'.getdata($who, 'inf', 'fld1');
		   echo("<tr><td align=center width=35%>".$who."<br>(".getdata($who, 'hero', 'name').")<br><img src='".$avatar."' width=150 height=200></td><td align=center>");
		   while (!feof($data))
			{
			   echo (fgets($data, 255)."<br>");
			}
			fclose ($data);
			echo("</td></tr></table>");
		}

		//����� ������� �����
		echo("</td></tr>");
		echo("</table>");
		exit();
	}

	//������ ����� ������� ��� ���
	echo("<table border=1 width=100% cellpadding=0 cellspacing=0>");
	echo("<tr><td align=center colspan=5><br><form action='forum.php' method=post><input type='text' name='cname' style='background:#000099; font-WEIGHT: BOLD; COLOR: white; cursor: hand; list-style: lower-alpha; filter: Alpha(Opacity=20, FinishOpacity=100, Style=3)'>&nbsp;<input type='submit' value='����� ����' style='background:#000099; font-WEIGHT: BOLD; COLOR: white; cursor: hand; list-style: lower-alpha; filter: Alpha(Opacity=20, FinishOpacity=100, Style=3)'><input type='hidden' name='newsubject' value=1><input type='hidden' name='category' value='".$category."'><input type='hidden' name='forum' value='".$forum."'></form></td></tr>");
	echo("<tr><td align=center>����</td><td align=center>�����</td><td align=center>�����</td><td align=center>������</td></tr>");

	//���������� ����������
	$moderator = getfrom('category', $category, 'forum_categories', 'moderator');

	//���������� ��� ����
	$ath = mysql_query("select * from forum_subjects;");
	if ($ath)
	{
		//��� ������ ����
		while ($rw = mysql_fetch_row($ath))
		{
			//���� ���� �� �������� ������ � ���������
			if (($rw[2] == $forum)&&($rw[3] == $category))
			{
				//������ ������� ���� ��� ����������
				if (($lg == $moderator)||(isadmin($lg) == 1))
				{
					//���� ���� �������, �� ������ "�������"
					if ($rw[4] == '0')
					{
						echo("<tr><td align=center><a href='forum.php?category=".$category."&forum=".$forum."&subject=".$rw[0]."'>".$rw[0]."</a></td><td align=center>".$rw[5]."</td><td align=center><br><form action='forum.php' method=post><input type='submit' value='�������' style='background:#000099; font-WEIGHT: BOLD; COLOR: white; cursor: hand; list-style: lower-alpha; filter: Alpha(Opacity=20, FinishOpacity=100, Style=3)'><input type='hidden' name='closesubject' value=1><input type=hidden name='subject' value='".$rw[0]."'><input type=hidden name='forum' value='".$forum."'><input type=hidden name='category' value='".$category."'></form></td><td align=center><br><form action='forum.php' method=post><input type='submit' value='�������' style='background:#000099; font-WEIGHT: BOLD; COLOR: white; cursor: hand; list-style: lower-alpha; filter: Alpha(Opacity=20, FinishOpacity=100, Style=3)'><input type='hidden' name='erasesubject' value=1><input type=hidden name='subject' value='".$rw[0]."'><input type=hidden name='forum' value='".$forum."'><input type=hidden name='category' value='".$category."'></form></td></tr>");
					}
					else //�������� ����
					{
						echo("<tr><td align=center><a href='forum.php?category=".$category."&forum=".$forum."&subject=".$rw[0]."'>".$rw[0]."</a></td><td align=center>".$rw[5]."</td><td align=center><br><form action='forum.php' method=post><input type='submit' value='�������' style='background:#000099; font-WEIGHT: BOLD; COLOR: white; cursor: hand; list-style: lower-alpha; filter: Alpha(Opacity=20, FinishOpacity=100, Style=3)'><input type='hidden' name='opensubject' value=1><input type=hidden name='subject' value='".$rw[0]."'><input type=hidden name='forum' value='".$forum."'><input type=hidden name='category' value='".$category."'></form></td><td align=center><br><form action='forum.php' method=post><input type='submit' value='�������' style='background:#000099; font-WEIGHT: BOLD; COLOR: white; cursor: hand; list-style: lower-alpha; filter: Alpha(Opacity=20, FinishOpacity=100, Style=3)'><input type='hidden' name='erasesubject' value=1><input type=hidden name='subject' value='".$rw[0]."'><input type=hidden name='forum' value='".$forum."'><input type=hidden name='category' value='".$category."'></form></td></tr>");
					}
				}
				else //��� ���� ���������
				{
					//��� ��������� (����������� ��� ��� ��� ������ ��� �� ������)
					$count = messages($category, $forum);

					//��� ���������
					if ($count == 0)
					{
						$type = "empty";
					}

					//����� ����
					if ($count > 0)
					{
						$type = "newmessage";
					}

					//����� ���
					if ($count < 0)
					{
						$type = "oldmessage";
						$count = $count * (-1);
					}

					//���������� ������
					if ($rw[6] == 1)
					{
						$status = "�������";
					}
					else
					{
						$status = "�������";
					}

					//������� ���� ����
					echo("<tr><td align=center><a href='forum.php?category=".$category."&forum=".$forum."&subject=".$rw[0]."'>".$rw[0]."</a></td><td align=center>".$rw[5]."</td><td align=center>".$status."</td><td align=center><img src='images/icons/".$type.".ico'></td></tr>");
				} //��� ���� ���������
			} //
		}
	}

	//����� ������� ���
	echo("</table>");
	exit();
}

//���������� ��� �����, ���� �� ������� ��������� � �����
	//�������� ������ ��������� �� ��
	$ath = mysql_query("select * from forum_categories;");
	if ($ath)
	{
		//��� ������ ���������
		while ($rw = mysql_fetch_row($ath))
		{
			//������ ���������
			echo("<br><table border=1 width=98% cellpadding=0 cellspacing=0>");
			echo("<tr><td width=60% align=center><h2>".$rw[0]."</h2></td><td align=center><h3>������������: ".$rw[2]."</h3></td>");

			//������ ���� ��������� (������ ��� ����������)
			if (($lg == $rw[2])||(isadmin($lg) == 1))
			{
				echo("<td align=center><br><form action='forum.php' method=post><input type='submit' value='����� ������' style='background:#000099; font-WEIGHT: BOLD; COLOR: white; cursor: hand; list-style: lower-alpha; filter: Alpha(Opacity=20, FinishOpacity=100, Style=3)'><input type='hidden' name='newvol' value=1><input type='hidden' name='moder' value='".$rw[2]."'><input type='hidden' name='volinpart' value='".$rw[1]."'></form></td>");
			}
			echo("</tr>");

				//�������� ������ ������� ��� ���������
				echo("<tr><td colspan=3>");
				$frm = mysql_query("select * from forum_forums;");
				if ($frm)
				{
					//��� ������� ������
					while ($rm = mysql_fetch_row($frm))
					{
						//���� ���� ����� ��������� � ������� ���������, �� ������ ������
						if ($rm[2] == $rw[1])
						{
							//��� ��������� (����������� ��� ��� ��� ������ ��� �� ������)
							$count = messages($rw[0], $rm[0]);

							//��� ���������
							if ($count == 0)
							{
								$type = "empty";
							}

							//����� ����
							if ($count > 0)
							{
								$type = "newmessage";
							}

							//����� ���
							if ($count < 0)
							{
								$type = "oldmessage";
								$count = $count * (-1);
							}

							//������� ���������
							echo("<table border=1 width=98% cellpadding=0 cellspacing=0>");
							echo("<tr><td align=center width=><img src='images/icons/".$type.".ico' border=0></td><td width=80%><a href='forum.php?category=".$rw[0]."&forum=".$rm[0]."'><h3><dd>".$rm[0]."</h3></a></td><td align=center width=20%>���������: ".$count."</td></tr>");
							echo("</table>");
						}
					}
				}
				echo("</td></tr>");
			
			//����� ���������
			echo("</table>");
		}
	}

//����� �������� �������
echo("</td></tr>");
echo("</table>");
?>