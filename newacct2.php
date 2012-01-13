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

// Clog up the works for spammers

if (isset($_POST["turnoff"]) || !isset($_POST["turnon"]))  {
	system("sleep 60");
	exit(0);
}

include 'php/opendatabase.php';
include 'php/club.php';
include 'php/rank.php';
include 'php/player.php';
include 'php/genpasswd.php';
include 'php/newaccemail.php';

function checkclash($column, $value) {
	if (strlen($value) == 0)
		return;
	$qvalue = mysql_real_escape_string($value);
	$ret = mysql_query("select $column from player where $column='$qvalue'");
	if ($ret && mysql_num_rows($ret) != 0)  {
		include 'php/nameclash.php';
		exit(0);
	}
}

$playname = $_POST["playname"];
$fuserid = $_POST["userid"];
$passw = $_POST["passw"];
$email = $_POST["email"];
$club = $_POST["club"];
$rank = $_POST["rank"];

if  (strlen($playname) == 0)  {
	$mess = "No player name given";
	include 'php/wrongentry.php';
	exit(0);
}
if  (strlen($fuserid) == 0)  {
	$mess = "No user name given";
	include 'php/wrongentry.php';
	exit(0);
}
if  (strlen($club) == 0)  {
	$mess = "No club code given";
	include 'php/wrongentry.php';
	exit(0);
}
if (strlen($email) == 0)  {
	$mess = "No email given";
	include 'php/wrongentry.php';
	exit(0);
}

//  Get player name to see what we are doing

try {
	$player = new Player($playname);
}
catch (PlayerException $e) {
   $mess = $e->getMessage();
   include 'php/wrongentry.php';
   exit(0);
}

// Check user name doesn't clash

checkclash('user', $fuserid);

// See if we know that player - we might just be creating an account for him/her

$ret = mysql_query("select first,last,user,email from player where {$player->queryof()}");
if ($ret && mysql_num_rows($ret) != 0)  {
	$row = mysql_fetch_array($ret);
	
	// If he/she already has a user id it is a mistake to use this
	
	$guserid = $row['user'];
	if (strlen($guserid) != 0)  {
		$column = "name";
		$value = $player->display_name(false);
		include 'php/nameclash.php';
		exit(0);
	}
	
	//  Update existing player to include the user id
	
	$player->Userid = $fuserid;
	$player->Club = new Club($club);
	$player->Rank = new Rank($rank);
	$player->Email = $email;
	$player->update();
}
else  {
	$player->Rank = new Rank($rank);
	$player->Club = new Club($club);
	$player->Email = $email;
	$player->Userid = $fuserid;
	$player->create();
}

// If no password specified, invent one

if (strlen($passw) == 0)
	$passw = generate_password();

$player->set_passwd($passw);
newaccemail($email, $fuserid, $passw);
?>
<!DOCTYPE HTML PUBLIC "-//W3C//DTD HTML 4.01//EN" "http://www.w3.org/TR/html4/strict.dtd">
<html>
<?php
$Title = "New account $fuserid created OK";
include 'php/head.php';
?>
<body>
<script language="javascript" src="webfn.js"></script>
<?php include 'php/nav.php';
print <<<EOT
<h1>$Title</h1>
<p>Your account $fuserid has been successfully created and you should be receiving
a confirmatory email.</p>

EOT;
?>
</div>
</div>
</body>
</html>
