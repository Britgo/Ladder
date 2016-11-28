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
include 'php/params.php';
include 'php/club.php';
include 'php/rank.php';
include 'php/player.php';

function promodemo($pl, $diff)
{
	if ($diff == 0)
		reeturn;
	print "<p>{$pl->display_name()} has now been ";
	if ($diff < 0)
		print "demoted ($diff)";
	else
		print "promoted ($diff)";
	print " to {$pl->display_rank()}.</p>\n";
}

$player1 = new Player();
$player2 = new Player();

try  {
	$player1->fromsel($_POST["pl1"]);
	$player2->fromsel($_POST["pl2"]);
	$player1->fetchdets();
	$player2->fetchdets();
}
catch (PlayerException $e)  {
	print <<<EOT
<html>
<head>
<title>Trouble with player details</title>
<link href="/bgaladder-style.css" type="text/css" rel="stylesheet"></link>
</head>
<body>
<h1>Trouble fetching player details</h1>
<p>Sorry something has gone wrong with your player detail posting.</p>
<p>Please start again from the top by <a href="index.php">clicking here</a>.</p>
</body>
</html>

EOT;
	exit(0);
}
if ($player1->is_same($player2)) {
	print <<<EOT
<html>
<head>
<title>Duplicated players</title>
<link href="/bgaladder-style.css" type="text/css" rel="stylesheet"></link>
</head>
<body>
<h1>Duplicated players</h1>
<p>Sorry something has gone wrong with your player detail posting. The players are the same.</p>
<p>Please start again from the top by <a href="index.php">clicking here</a>.</p>
</body>
</html>

EOT;
	exit(0);
}
$Params = new Params();
$Params->fetchvalues();

$rtype = $_POST["result"];

if  ($rtype == 'w')  {
	$winner = $player1;
	$loser = $player2;
}
else  {
	$winner = $player2;
	$loser = $player1;
}

if ($winner->Posn > $loser->Posn)  {
	$moved = $winner->display_name();
	$promo = $winner->accwin($Params, true);
	$demo = $loser->accloss($Params, true);
	$winner->updposn(($loser->prevposn() + $loser->Posn) / 2.0);
}
else  {
	$moved = "";
	$promo = $winner->accwin($Params, false);
	$demo = $loser->accloss($Params, false);
}
?>
<!DOCTYPE HTML PUBLIC "-//W3C//DTD HTML 4.01//EN" "http://www.w3.org/TR/html4/strict.dtd">
<html>
<?php
$Title = "Game Result Added";
include 'php/head.php';
?>
<body class="il">
<script language="javascript" src="webfn.js"></script>
<?php include 'php/nav.php'; ?>
<h1>Add Game Result</h1>
<p>
Finished adding result for Game between
<?php
$winname = $winner->display_name();
print <<<EOT
{$player1->display_name()} and {$player2->display_name()} as a win for $winname.

EOT;
if (strlen($moved) != 0)
	print <<<EOT
<p>$moved has been moved up the ladder.</p>

EOT;
promodemo($winner, $promo);
promodemo($losser, $demo);
?>
<p>Click <a href="index.php">here</a> to see the ladder now.</p>
</div>
</div>
</body>
</html>
