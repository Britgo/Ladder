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
include 'php/opendatabase.php';
include 'php/club.php';
include 'php/rank.php';
include 'php/player.php';

$Clublist = Club::listclubs();
$Playlist = Player::list_players();
?>
<!DOCTYPE HTML PUBLIC "-//W3C//DTD HTML 4.01//EN" "http://www.w3.org/TR/html4/strict.dtd">
<html>
<?php
$Title = "British Go Association Ladder";
include 'php/head.php';
?>
<body onload="javascript:filltab()">
<script language="javascript" src="webfn.js"></script>
<script language="javascript">
var playerlist = new Array();
<?php
foreach ($Playlist as $p) {
	$p->fetchdets();
	print <<<EOT
playerlist.push({seq:"{$p->Seq}", first:"{$p->display_first()}",
	last:"{$p->display_last()}", rank:"{$p->display_rank()}",
	club:"{$p->Club->Name}", userid:"{$p->Userid}", wins:{$p->Won}, losses:{$p->Lost}});

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
	if (pl.userid.length == 0)
		cellnode.style.fontStyle = "italic";
	cellnode = rownode.insertCell(2);
	text = document.createTextNode(pl.rank);
	cellnode.appendChild(text);
	cellnode = rownode.insertCell(3);
	text = document.createTextNode(pl.club);
	cellnode.appendChild(text);
	cellnode = rownode.insertCell(4);
	text = document.createTextNode(pl.wins);
	cellnode.appendChild(text);
	cellnode.style.textAlign = 'right';
	cellnode = rownode.insertCell(5);
	text = document.createTextNode(pl.losses);
	cellnode.appendChild(text);
	cellnode.style.textAlign = 'right';
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
$hasfoot = true;
include 'php/nav.php'; ?>
<h1>British Go Association Ladder</h1>
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
<th>Wins</th>
<th>Losses</th>
</tr>
</thead>
<tbody id="plbody">
</tbody>
</table>
</div>
</div>
<div id="Footer">
<div class="innertube">
<hr>
<p class="note">
This website was designed, authored and programmed by
<a href="http://www.john.collins.name" target="_blank">John Collins</a>.
</p>
<?php
$dat = date("Y");
print <<<EOT
<p class="note">Copyright &copy; John Collins 2009-$dat. Licensed under

EOT;
?>
<a href="http://www.gnu.org/licenses/">GPL v3</a>.</p>
</div>
</div>
</body>
</html>