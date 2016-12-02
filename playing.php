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
include 'php/params.php';

function limsg($msg, $adj) {
	print "<li>If a player $msg, ";
	if ($adj == 0)
		print "no adjustment is made to";
	elseif($adj < 0)
		printf("%.2f is subtracted from", -$adj);
	else
		printf("%.2f is added to", $adj);
	print " his or her rank.</li>\n";
}

$pars = new Params();
$pars->fetchvalues();
?>
<!DOCTYPE HTML PUBLIC "-//W3C//DTD HTML 4.01//EN" "http://www.w3.org/TR/html4/strict.dtd">
<html>
<?php
$Title = "Ladder Rules";
include 'php/head.php';
?>
<body>
<script language="javascript" src="webfn.js"></script>
<?php include 'php/nav.php'; ?>
<h1>Playing games on the ladder</h1>
<p>The ladder is mainly intended for over-the-board play although there is no reason not to include online games.
It provides a national ladder for players throughout the country and also provides for individual club
ladders at the same time, but one flexible enough to take visitors to clubs.</p>
<h2>Who can play</h2>
<p>Any two players may play at any time, either online or offline.
They should normally agree before the game starts that the result is to be entered on the ladder.
No restriction is enforced as to who can play whom, however if asked for a recommendation, players
currently not more than <?php print $pars->Maxplaces; ?> places apart on the ladder are proposed with appropriate handicaps.
Note that the player higher up the ladder may sometimes be a lower rank and take a handicap from the player lower down.</p>
<p>The game should normally be played with a number of handicap stones equal to the difference in rank (as displayed on the ladder)
between the players
<?php
if ($pars->Hcpdiff != 0) {
	if ($pars->Hcpdiff < 0)
		print "plus ";
	else
		print "less ";
	print abs($pars->Hcpdiff);
	print " stone";
	if (abs($pars->Hcpdiff) > 1)
		print "s";
}
print ", ";
?>
the positions on the ladder not being taken into account. Remember that 1K is one below 1D. AGA rules should be
used with 7.5 komi for even games and 0.5 komi otherwise (or for handicaps of 1) and using pass stones.
A 40-minute sudden death time limit is recommended but not enforced.</p>
<p>If the lower-placed (not necessarily lower-ranked) player wins the game, he or she moves up to his or
her opponent's position and the opponent and all those below down to the original position of the
winning player move down one.</p>
<p>If the lower-placed player loses the game, no change is made to the positions.</p>
<h2>Rank assignment and adjustments</h2>
<p>The ranks were initialised from the strengths in rating list, rounded to the nearest integer,
on 27 November 2016, however rank adjustments may well make these ranks diverge from the rating list.
New players are added after the lowest player of the same rank.</p>
<p>The following fractional adjustments are made to the ranks (although they are displayed rounded to the nearest integer on the ladder).</p>
<ul>
<?php
limsg("wins and moves up", $pars->Wonup);
limsg("wins but does not move (previously above the opponent)", $pars->Wonstay);
limsg("loses and goes down", $pars->Losedown);
limsg("loses but does not move (previously below the opponent)", $pars->Losestay);
?>
</ul>
<p>Players are limited to 9 dan maximum and 30 kyu minimum regardless of their performance.</p>
<h2>Accounts on the system</h2>
<p>To enter results on the ladder, please set up an account on the system using the menu item provided. The other player does not have to have an account
on the system. If your name is already on the ladder, you will need to just provide the additional details, user name,
password and email. Players on the ladder who have not got an account have their names rendered in italics.</p>
</div>
</div>
</body>
</html>
