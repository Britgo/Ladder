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

include 'php/session.php';
include 'php/checklogged.php';
include 'php/opendatabase.php';
include 'php/club.php';
include 'php/rank.php';
include 'php/player.php';

function checkname($newplayer) {
	$ret = mysql_query("select first,last from player where {$newplayer->queryof()}");
	if ($ret && mysql_num_rows($ret) != 0)  {
		$column = "name";
		$value = $newplayer->display_name();
		include 'php/nameclash.php';
		exit(0);
	}
}

$playname = $_POST["playname"];
$email = $_POST["email"];
$club = $_POST["club"];
$rank = $_POST["rank"];
$passw = $_POST["passw"];

try {
	$origplayer = new Player();
	$origplayer->frompost();
	$origplayer->fetchdets();
}
catch (PlayerException $e) {
	$mess = $e->getMessage();
	include 'php/wrongentry.php';
	exit(0);
}
	
// Check name changes and perform update if applicable
// Note that the "updatename" function does any consequent
// updates

$chname = false;
$newplayer = new Player($playname);
if  (!$origplayer->is_same($newplayer))  {
	checkname($newplayer);
	$origplayer->updatename($newplayer);
	$chname = true;
}
	
$origplayer->Rank = new Rank($rank);
$origplayer->Club = new Club($club);
$origplayer->Email = $email;
$origplayer->update();
if (strlen($passw) != 0  &&  $passw != $origplayer->get_passwd())
	$origplayer->set_passwd($passw);
?>
<!DOCTYPE HTML PUBLIC "-//W3C//DTD HTML 4.01//EN" "http://www.w3.org/TR/html4/strict.dtd">
<html>
<?php
$Title = "Player details updated OK";
include 'php/head.php';
print <<<EOT
<body>
<script language="javascript" src="webfn.js"></script>

EOT;
include 'php/nav.php';
print <<<EOT
<h1>$Title</h1>
<p>$Title.</p>

EOT;
if ($chname)
	print <<<EOT
<p>As you changed your name, you should probably logout and log back in again using the
menu on the left. This will reset any "cookies" with your original name in.</p>

EOT;
?>
</div>
</div>
</body>
</html>
