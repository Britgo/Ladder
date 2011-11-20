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
// This is a bit of a Bodge for now
$player->Club = new Club('NoC');
?>
<!DOCTYPE HTML PUBLIC "-//W3C//DTD HTML 4.01//EN" "http://www.w3.org/TR/html4/strict.dtd">
<html>
<?php
$Title = "New Player";
include 'php/head.php';
?>
<body>
<script language="javascript" src="webfn.js"></script>
<script language="javascript">
function formvalid()
{
      var form = document.playform;
      if  (!nonblank(form.playname.value))  {
         alert("No player name given");
         return false;
      }
      if  (!nonblank(form.userid.value))  {
         alert("No userid given");
         return false;
      }
		return true;
}
</script>
<?php
$showadmmenu = true;
include 'php/nav.php';
?>
<h1>Add Player</h1>
<p>Please set up the details of the player as required using the form below.</p>
<form name="playform" action="updindplayer2.php" method="post" enctype="application/x-www-form-urlencoded" onsubmit="javascript:return formvalid();">
<table cellpadding="2" cellspacing="5" border="0">
<tr><td>Player Name</td><td><input type="text" name="playname"></td></tr>
<tr><td>Club</td><td><?php $player->clubopt(); ?></td></tr>
<tr><td>Rank</td><td><?php $player->rankopt(); ?></td></tr>
<tr><td>Userid</td><td><input type="text" name="userid"></td></tr>
<tr><td>Password</td><td><input type="password" name="passw"></td></tr>
<tr><td>Email</td><td><input type="text" name="email"></td></tr>
<tr><td>Admin Privs</td><td><?php $player->adminopt(); ?></td></tr>
<tr><td colspan="2" align="center"><input type="submit" name="subm" value="Add Player"></td></tr>
</table>
</form>
</div>
</div>
</body>
</html>
