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

$player = new Player();
try {
	$player->fromid($userid);
}
catch (PlayerException $e) {
	print <<<EOT
<html>
<head>
<title>Unknown player</title>
<link href="bgaladder-style.css" type="text/css" rel="stylesheet"></link>
</head>
<body>
<h1>Unknown player</h1>
<p>Sorry, but player name $userid is not known.</p>
<p>Please start again from the top by <a href="index.php">clicking here</a>.</p>
</body>
</html>

EOT;
	exit(0);
}

function plsort($a, $b) {
	$d = strcasecmp($a->Club->Name, $b->Club->Name);
	if ($d != 0)
		return $d;
	$d = strcasecmp($a->Last, $b->Last);
	if ($d != 0)
		return $d;
	$d = strcasecmp($a->First, $b->First);
	if ($d != 0)
		return $d;
	return $b->Rank->Rankvalue - $a->Rank->Rankvalue;
}

function pselect($fname, $selectedp)  {
	global $playerlist;
	print <<<EOT
<select name="$fname">

EOT;
	foreach ($playerlist as $p)  {
		$ckd = "";
		if  ($p->is_same($selectedp))
			$ckd = " selected";
		print <<<EOT
<option value="{$p->selof()}"$ckd>{$p->display_name()} - {$p->Club->display_name()}</option>

EOT;
	}
}

// Sort player list by clubs
// Find possible other player

$otherplayer = new Player();

$playerlist = list_players('last,first,rank desc');
foreach ($playerlist as $p) {
	$p->fetchdets();
	if ($p->Club->Name == $player->Club->Name && !$p->is_same($player))
		$otherplayer = $p;
}
usort($playerlist, 'plsort');

?>
<!DOCTYPE HTML PUBLIC "-//W3C//DTD HTML 4.01//EN" "http://www.w3.org/TR/html4/strict.dtd">
<html>
<?php
$Title = "Add result in ladder";
include 'php/head.php';
?>
<body class="il">
<script language="javascript" src="webfn.js"></script>
<script language="javascript">
function checkform() {
	var fm = document.resf;
	if (fm.pl1.selectedIndex < 0  ||  fm.pl2.selectedIndex < 0)  {
		alert("No players selected");
		return  false;
	}
	if (fm.pl1.selectedIndex == fm.pl2.selectedIndex)  {
		alert("You must select different players");
		return  false;
	}
	return  true;
}
</script>
<?php include 'php/nav.php'; ?>
<h1>Add result in ladder</h1>
<p>Please use this form to enter ladder results. Even if they don't make any difference to ladder positions,
please enter them as cumulative wins or losses are used to reset ranks.</p>
<p>Do not enter draws (Jigo) which are ignored.</p>
<p>To enter the result, please complete the form below:
</p>
<form action="addresult2.php" method="post" enctype="multipart/form-data" name="resf" onsubmit="javascript: return checkform();">
<table cellpadding="2" cellspacing="5" border="0">
<tr><th>Player 1</th><th>Result</th><th>Player 2</th></tr>
<tr>
<td><?php pselect('pl1', $player); ?></td>
<td>
<input type="radio" name="result" value="w" checked="checked" />Beat
<br />
<input type="radio" name="result" value="l" />Lost to
</td>
<td><?php pselect('pl2', $otherplayer); ?></td>
</tr>
<tr><td colspan="3" align="center"><input type="submit" value="Enter Result"></td></tr>
</table>
</form>
</div>
</div>
</body>
</html>
