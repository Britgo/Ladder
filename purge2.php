<?php
//   Copyright 2016 John Collins

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

include 'php/session.php';
include 'php/checklogged.php';
include 'php/opendatabase.php';
include 'php/club.php';
include 'php/rank.php';
include 'php/player.php';

$nint = $_POST["nint"];
if (strlen($nint) == 0) {
    include 'php/wrongentry.php';
    exit(0);
}
$intname = $_POST['interval'];
$ret = mysql_query("DELETE FROM player WHERE lastgame<DATE_SUB(NOW(),INTERVAL $nint $intname)");
if (!$ret)  {
	$mess = mysql_error();
	print <<<EOT
<html>
<head>
<title>Cannot delete players</title>
<link href="/bgaladder-style.css" type="text/css" rel="stylesheet"></link>
</head>
<body>
<h1>Cannot delete players</h1>
<p>Sorry something has gone wrong trying to delete players, the error was $mess.</p>
<p>Please start again from the top by <a href="index.php">clicking here</a>.</p>
</body>
</html>

EOT;
	exit(0);
}
$naff = mysql_affected_rows();
if  ($naff != 0)  {
	$Plist = Player::list_players();
	$Nind = 1000.0;
	foreach ($Plist as $p)  {
		mysql_query("UPDATE player SET posn=$Nind WHERE {$p->queryof()}");
		$Nind += 1000.0;
	}
}
?>
<!DOCTYPE HTML PUBLIC "-//W3C//DTD HTML 4.01//EN" "http://www.w3.org/TR/html4/strict.dtd">
<html>
<?php
$Title = "Purge Complete";
include 'php/head.php';
?>
<body>
<script language="javascript" src="webfn.js"></script>
<?php
$showadmmenu = true;
include 'php/nav.php';
?>
<h1>Purge of inactive players complete</h1>
<p>Finished adjusting parameters.
<?php
if ($naff == 0)
	print "However no players were removed.\n";
elseif ($naff == 1)
	print "One player was removed.\n";
else
	print "$naff players were removed.\n";
?>
</p>
</div>
</div>
</body>
</html>
