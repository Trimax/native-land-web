<?
  //��������� �����
  $lg = $name;
  $pw = $pass;

  //���� � ����
  if (finduser($lg, $pw) != 1)
  {
	  moveto('index.php');
  }

  //����� �� � �����
  FromBattle($lg);

  //������� ������� ��������
  function DropItem($Login, $Pass, $Cell, $Number)
  {
    //���� ����� ���� �������
    if ($Number != 0)
    {
      echo ("<form action='event.php' method=post>");
      echo ("<input type=hidden name='login' value='$Login'>");
      echo ("<input type=hidden name='pass' value='$Pass'>");
      echo ("<input type=hidden name='action' value=1>");
      echo ("<input type=hidden name='cell' value=$Cell>");
      echo ("<input type=hidden name='numb' value=$Number>");
      echo ("<input type=hidden name='Back' value=80>");
      ?>
        <input type=submit value=' ��������� ' style='background:#000099; font-WEIGHT: BOLD; COLOR: white; cursor: hand; list-style: lower-alpha; filter: Alpha(Opacity=20, FinishOpacity=100, Style=3)'>
        </form>
      <?
    }
  }

  //������� ��������� ��������
  function UseItem($Login, $Pass, $Cell, $Number)
  {
    //���� ����� ���� �������
    if ($Number != 0)
    {
      echo ("<form action='event.php' method=post>");
      echo ("<input type=hidden name='login' value='$Login'>");
      echo ("<input type=hidden name='pass' value='$Pass'>");
      echo ("<input type=hidden name='action' value=2>");
      echo ("<input type=hidden name='cell' value=$Cell>");
      echo ("<input type=hidden name='numb' value=$Number>");
      echo ("<input type=hidden name='Back' value=80>");
      ?>
        <input type=submit value=' ����� ' style='background:#000099; font-WEIGHT: BOLD; COLOR: white; cursor: hand; list-style: lower-alpha; filter: Alpha(Opacity=20, FinishOpacity=100, Style=3)'>
        </form>
      <?
    }
  }

  //������� �������� ��������
  function GiveItem($Login, $Pass, $Cell, $Number)
  {
    //���� ����� ���� �������
    if ($Number != 0)
    {
      echo ("<form action='event.php' method=post>");
      echo ("<input type=hidden name='login' value='$Login'>");
      echo ("<input type=hidden name='pass' value='$Pass'>");
      echo ("<input type=hidden name='action' value=3>");
      echo ("<input type=hidden name='cell' value=$Cell>");
      echo ("<input type=hidden name='numb' value=$Number>");
      echo ("<input type=hidden name='Back' value=80>");
      indexuserlist('recepient');
      ?>
        <input type=submit value=' �������� ' style='background:#000099; font-WEIGHT: BOLD; COLOR: white; cursor: hand; list-style: lower-alpha; filter: Alpha(Opacity=20, FinishOpacity=100, Style=3)'>
        </form>
      <?
    }
  }

  //������� ������� � ������
?>
<center>
<h2>���������</h2>
<table border=1 cellspacing=0 cellpadding=0 width=50%>
<tr>
  <td align=center width=0%>
  <table border=1 cellspacing=0 cellpadding=0 width=50%>
  <?
    for($i = 1; $i <= 4; $i++)
    {
      echo("\t<tr>\n");
      for ($j = 1; $j <= 4; $j++)
      {
        //����� ������
        $Num = ($i - 1) * 4 + $j;

        //����� ��������
        $Number = getfrom('login', $lg, 'inventory', 'inv'.$Num);

        //�������
        if ($Number != 0)
          $Image = "<img border=0 src='".getimg($Number)."' alt='".getinfo($Number)."'>";
        else
          $Image = "<img border=0 src='images/weapons/null/weapon.jpg'>";

        //������
        echo("\t\t<td><center><a href='game.php?CellNum=".$Num."&action=80'>$Image<br>");
        echo("</a></center></td>\n");
      }
      echo("\t</tr>\n");
    }
  ?>
  </table>
  </td>
  <!-- ���� -->
  <td align = center>
  <?
    //����� ����
    $Cell   = $temptxt;
    if ($Cell > 16)
      $Cell = 16;
    if ($Cell < 1)
      $Cell = 1;
    $Number = getfrom('login', $lg, 'inventory', 'inv'.$Cell);
    $Name   = getfrom('num', $Number, 'allitems', 'name');

    //���� ���� ����
    if ($Number != 0)
    {
      //�������� ����
      echo("<b>$Name</b><br><img border=0 src='".getimg($Number)."' alt='".getinfo($Number)."'>");

      //��������...
        //���������
        DropItem($lg, $pw, $Cell, $Number);
        //�����
        UseItem($lg, $pw, $Cell, $Number);
        //��������
        GiveItem($lg, $pw, $Cell, $Number);
    }
  ?>
  </td>
  </tr>
</table>
</center>