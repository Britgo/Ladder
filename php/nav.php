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

$classid = "Nav";
$contentid = "Content";
if (isset($hasfoot))  {
	$classid = "Navwf";
	$contentid = "Contentwf";
}

print <<<EOT
<div id="$classid">

EOT;
?>
<div class="innertube">
<a href="http://www.britgo.org">
<img src="images/gohead12.gif" width="133" height="47" alt="BGA Logo" border="0" hspace="0" vspace="0"></a>
<table>
<tr><td><a href="index.php">Ladder Home</a></td></tr>
<tr><td><a href="playing.php">Rules</a></td></tr>
<?php
if ($logged_in) {
	print <<<EOT
<tr><td><a href="suggest.php" class="il">Suggest game</a></td></tr>
<tr><td><a href="addresult.php" class="il">Add Result</a></td></tr>

EOT;
	if ($admin)  {
		print <<<EOT
<tr><td><a href="admin.php" class="memb">Admin menu</a></td></tr>

EOT;
		if (isset($showadmmenu))  {
			print <<<EOT
<tr><td class="subind"><a href="newclub.php" class="memb">New club</a></td></tr>
<tr><td class="subind"><a href="newplayer.php" class="memb">New player</a></td></tr>
<tr><td class="subind"><a href="clubupd.php" class="memb">Update clubs</a></td></tr>
<tr><td class="subind"><a href="playupd.php" class="memb">Update players</a></td></tr>
<tr><td class="subind"><a href="rempw.php" class="memb">Remind password</a></td></tr>
<tr><td class="subind"><a href="adjparam.php" class="memb">Adjust parameters</a></td></tr>
<tr><td class="subind"><a href="purge.php" class="memb">Remove inactive players</a></td></tr>
EOT;
		}
	}
	$qu = htmlspecialchars($username);
	print <<<EOT
<tr><td><a href="ownupd.php">Update account</a></td></tr>
<tr><td><a href="logout.php">Logout<br>$qu</a></td></tr>

EOT;
}
?>
</table>
<?php
if (!$logged_in)  {
	if (isset($_COOKIE['user_id']))
		$userid = $_COOKIE['user_id'];
	print <<<EOT
<form name="lifm" action="login.php" method="post" enctype="application/x-www-form-urlencoded">
<p>Userid:<input type="text" name="user_id" id="user_id" value="$userid" size="10"></p>
<p>Password:<input type="password" name="passwd" size="10"></p>
<p><input type="submit" value="Login"></p>
</form>
<p><a href="javascript:lostpw();">Lost password?</a></p>
<p><a href="newacct.php">Create account</a></p>

EOT;
}
print <<<EOT
</div>
</div>
<div id="$contentid">
<div class="innertube">

EOT;
?>
