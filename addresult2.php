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
$Params = new Params();
$Params->fetchvalues();
$rd = abs($player1->Rank->Rankvalue - $player2->Rank->Rankvalue);

if ($rd > $Params->Maxdiff)  {
	print <<<EOT
<html>
<head>
<title>Too big a rank difference</title>
<link href="/bgaladder-style.css" type="text/css" rel="stylesheet"></link>
</head>
<body>
<h1>Too big a rank difference</h1>
<p>Sorry but we cannot record this game as the rank difference is too great.</p>
<p>{$player1->display_name()} has a rank of {$player1->display_rank()} whilst
{$player2->display_name()} has a rank of {$player2->display_rank()} and the maximum difference
is {$Params->Maxdiff}.</p>
<p>Please start again from the top by <a href="index.php">clicking here</a>.</p>
</body>
</html>

EOT;
	exit(0);
}	

$rtype = $_POST["result"];
$moved = "";
if ($rtype == 'w')  {
	$ppl1 = $player1->accwin($Params->Wont);
	$dpl1 = false;
	$ppl2 = false;
	$dpl2 = $player2->accloss($Params->Losst);
	if ($player1->Posn > $player2->Posn)  {
		$player1->updposn(($player2->prevposn() + $player2->Posn) / 2.0);
		$moved = $player1->display_name();
	}
}
else  {
	$ppl2 = $player2->accwin($Params->Wont);
	$dpl2 = false;
	$ppl1 = false;
	$dpl1 = $player1->accloss($Params->Losst);
	if ($player2->Posn > $player1->Posn)  {
		$player2->updposn(($player1->prevposn() + $player1->Posn) / 2.0);
		$moved = $player2->display_name();
	}
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
$winner = $rtype == 'w'? $player1->display_name(): $player2->display_name();
print <<<EOT
{$player1->display_name()} and {$player2->display_name()} as a win for $winner.

EOT;
if (strlen($moved) != 0)
	print <<<EOT
<p>$moved has been moved up the ladder.</p>

EOT;
if ($ppl1)
	print <<<EOT
<p>{$player1->display_name()} has been promoted to {$player1->display_rank()}.</p>

EOT;
if ($ppl2)
	print <<<EOT
<p>{$player2->display_name()} has been promoted to {$player2->display_rank()}.</p>

EOT;
if ($dpl1)
	print <<<EOT
<p>{$player1->display_name()} has been demoted to {$player1->display_rank()}.</p>

EOT;
if ($dpl2)
	print <<<EOT
<p>{$player2->display_name()} has been demoted to {$player2->display_rank()}.</p>

EOT;

?>
<p>Click <a href="index.php">here</a> to see the ladder now.</p>
</div>
</div>
</body>
</html>
