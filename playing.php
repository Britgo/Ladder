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
<p>The ladder is mainly intended for over-the-board play although online games are not prohibited.
It provides a national ladder for players throughout the country and also provides for individual club
ladders at the same time, but one flexible enough to take visitors to clubs.</p>
<p>Any player may challenge a player higher up the ladder, provided that the difference in ranks between
the players is <?php print $pars->Maxdiff; ?> or less. Note that the player higher up the ladder may
sometimes be a lower rank.</p>
<p>The game should normally be played with a number of handicap stones equal to the difference in rank
between the players, the positions on the ladder not being taken into account. AGA rules should be
used with 7.5 komi for even games and 0.5 komi otherwise and using pass stones.
A 40-minute sudden death time limit is recommended but not enforced.</p>
<p>If the lower-placed (not necessarily lower-ranked) player wins the game, he or she moves up to his or
her opponent's position and the opponent and all those below down to the original position of the
winning player move down one.</p>
<p>If the lower-placed player loses the game, no change is made to the positions.</p>
<h2>Rank assignment and adjustments</h2>
<p>The ranks were initialised from the strengths in rating list, rounded to the nearest integer,
in November 2011.
New players are added after the lowest player of the same rank.</p>
<p>After <?php print $pars->Wont; ?> consecutive wins, a player will be promoted to the next rank up to
a maximum of 8 Dan regardless of how many movements he or she made up the ladder.</p>
<p>After <?php print $pars->Losst; ?> consecutive losses, a player will be demoted to the previous rank down to
a minimum of 30 Kyu regardless of how many movements he or she made down the ladder.</p>
</div>
</div>
</body>
</html>
