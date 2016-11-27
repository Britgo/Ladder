<?php
//   Copyright 2011 John Collins

//   This program is free software: you can redistribute it and/or modify
//   it under the terms of the GNU General Public License as published by
//   the Free Software Foundation, either version 3 of the License, or
//   (at your option) any later version.

//   This program is distributed in the hope that it will be useful,
//   but WITHOUT ANY WARRANTY; without even the implied warranty of
//   MERCHANTABILITY or FITNESS FOR A PARTICULAR PURPOSE.  See the
//   GNU General Public License for more details.

//   You should have received a copy of the GNU General Public License
//   along with this program.  If not, see <http://www.gnu.org/licenses/>.
?>
<!DOCTYPE HTML PUBLIC "-//W3C//DTD HTML 4.01//EN" "http://www.w3.org/TR/html4/strict.dtd">
<html>
<?php
include 'php/opendatabase.php';
include 'php/club.php';
include 'php/rank.php';
include 'php/player.php';
$Title = "Apply for new account";
include 'php/head.php';
?>
<body>
<script language="javascript" src="webfn.js"></script>
<script language="javascript">
var idlist = new Array();
<?php
$ulist = Player::list_userids();
foreach ($ulist as $u) {
	print <<<EOT
idlist['$u'] = 1;

EOT;
}
?>
function formvalid()
{
      var form = document.playform;
      if (form.turnoff.checked) {
      	alert("You didn't turn off the non-spammer box");
      	return false;
      }
      if (!form.turnon.checked) {
      	alert("You didn't turn on the non-spammer box");
      	return false;
      }
      if  (!nonblank(form.playname.value))  {
         alert("No player name given");
         return false;
      }
      var uid = form.userid.value;
      if  (!/^\w+$/.test(uid))  {
      	alert("No valid userid given");
      	return  false;
      }
      if  (idlist[uid])  {
      	alert("Userid " + uid + " is already defined");
      	return  false;
      }
      if  (!nonblank(form.email.value))  {
      	alert("No email address given");
      	return  false;
      }
		return true;
}
</script>
<?php include 'php/nav.php'; ?>
<h1>Apply for new account on ladder</h1>
<p>Please use the form below to apply for an account on the ladder system.
You will need an account to record results for yourself or other people.</p>
<p>You can use this form if your name is mentioned on the ladder to give yourself an
account and password, or you can add yourself to the ladder here.</p>
<p>If you add yourself to the ladder, you will be put just after the lowest person with the
same rank as yourself.</p>
<p><b>Please</b> don't try to create multiple accounts under different or slightly different names however
bad your playing record is! If you have forgotten your password, select the "remind
password" entry.</p>
<p>Please note that email addresses are
<b>not</b> published anywhere.</p>
<form name="playform" action="newacct2.php" method="post" enctype="application/x-www-form-urlencoded" onsubmit="javascript:return formvalid();">
<table cellpadding="2" cellspacing="5" border="0">
<tr><td>Player Name</td>
<td><input type="text" name="playname"></td></tr>
<tr><td>League Userid (initials/KGS name acceptable)</td>
<td><input type="text" name="userid"></td></tr>
<tr><td>Password (leave blank to let system set it)</td>
<td><input type="password" name="passw"></td></tr>
<tr><td>Email (must have)</td>
<td><input type="text" name="email"></td></tr>
<tr><td>Club (i.e. face-to-face)</td>
<td>
<?php
$player = new Player();
$player->Club = new Club('NoC');
$player->clubopt();
print <<<EOT
</td></tr>
<tr><td>Rank</td><td>
EOT;
$player->rankopt();
print "</td></tr>\n";
?>
<tr><td colspan=2><input type="checkbox" name="turnoff" checked>
&lt;&lt; Because I'm not a spammer I'm turning this off and this on &gt;&gt;
<input type="checkbox" name="turnon"></td></tr>
</table>
<p>
<input type="submit" name="subm" value="Create Account">
</p>
</form>
</div>
</div>
</body>
</html>
