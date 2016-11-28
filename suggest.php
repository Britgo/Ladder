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
include 'php/params.php';

$Pars = new Params();
$Pars->fetchvalues();

try  {
	$mydets = new Player();
	$mydets->fromid($userid);
	$Playlist = Player::list_players();
	foreach ($Playlist as $p) {
		if ($p->is_same($mydets))  {
			$mydets->Seq = $p->Seq;
			break;
		}
	}
	if ($mydets->Seq <= 0)
		throw new PlayException("Sequence not found");
	$minseq = max(1, $mydets->Seq - $Pars->Maxplaces);
	$maxseq = min($mydets->Seq + $Pars->Maxplaces, count($Playlist)+1);
	
	$valid = array();
	for ($n = $minseq-1;  $n < $maxseq;  $n++)  {
		$p = $Playlist[$n];
		if ($p->is_same($mydets))
			continue;
		$p->fetchdets();
		array_push($valid, $p);
	}
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

$Clublist = Club::listclubs();

?>
<!DOCTYPE HTML PUBLIC "-//W3C//DTD HTML 4.01//EN" "http://www.w3.org/TR/html4/strict.dtd">
<html>
<?php
$Title = "Suggested opponents";
include 'php/head.php';
?>
<body onload="javascript:filltab()">
<script language="javascript" src="webfn.js"></script>
<script language="javascript">
var playerlist = new Array();
<?php
$cv = count($valid);
print <<<EOT
// $minseq to $maxseq $cv

EOT;
$myrank = $mydets->Rank->Rankvalue;
foreach ($valid as $p) {
	$hisrank = $p->Rank->Rankvalue;
	$stones = max(0, abs($myrank - $hisrank) - $Pars->Hcpdiff);
	if ($stones == 0)
		$game = "Nigiri 7.5 Komi";
	else  {
		if ($myrank < $hisrank)
			$game = "Black";
		else
			$game == "White";
		if ($stones == 1)
			$game .= " No Komi";
		else
			$game .= " $stones stones";
	}
	print <<<EOT
playerlist.push({seq:"{$p->Seq}", first:"{$p->display_first()}",
	last:"{$p->display_last()}", rank:"{$p->display_rank()}",
	club:"{$p->Club->Name}", game: "$game"});

EOT;
}
?>
function filloutrow(tbod, pl, i) {
	var rownode = tbod.insertRow(i);
	var cellnode = rownode.insertCell(0);
	var text = document.createTextNode(pl.seq);
	cellnode.appendChild(text);
	cellnode = rownode.insertCell(1);
	text = document.createTextNode(pl.first + " " + pl.last);
	cellnode.appendChild(text);
	cellnode = rownode.insertCell(2);
	text = document.createTextNode(pl.rank);
	cellnode.appendChild(text);
	cellnode = rownode.insertCell(3);
	text = document.createTextNode(pl.club);
	cellnode.appendChild(text);
	cellnode = rownode.insertCell(4);
	text = document.createTextNode(pl.game);
	cellnode.appendChild(text);
}

function filltab() {

	// First clear the existing stuff
	
	var plbod = document.getElementById('plbody');
	while (plbod.rows.length != 0)
		plbod.deleteRow(0);
		
	// See if we want the lot or just a club
	
	var selfm = document.selform.clubsel;
	if (selfm.selectedIndex <= 0)  {
		for (i in playerlist)
			filloutrow(plbod, playerlist[i], i);
	}
	else {
		var i;
		var ch = new Array();
		var clopts = selfm.options;
		for (i = 0;  i < clopts.length;  i++)
			if (clopts[i].selected)
				ch[clopts[i].value] = true;
		var pp = 0;
		for (i in playerlist) {
			if (ch[playerlist[i].club])  {
				filloutrow(plbod, playerlist[i], pp);
				pp++;
			}
		}
	}
}
</script>
<?php
$hasfoot = false;
include 'php/nav.php'; ?>
<h1>Suggested opponents</h1>
<p>Select just for club (hold down Ctrl key and click to select more than one club):
<form name="selform">
<select name="clubsel" size="5" multiple onchange="filltab();">
<option selected="selected">(None)</option>
<?php
$ca = array();
foreach ($Clublist as $c) {
	print <<<EOT
<option>{$c->display_name()}</option>

EOT;
}
?>
</select>
</form></p>
<table class="membpick" id="pltab">
<thead>
<tr>
<th>Posn</th>
<th>Name</th>
<th>Rank</th>
<th>Club</th>
<th>Game Type</th>
</tr>
</thead>
<tbody id="plbody">
</tbody>
</table>
</div>
</div>
</body>
</html>