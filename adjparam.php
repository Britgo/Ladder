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
?>
<!DOCTYPE HTML PUBLIC "-//W3C//DTD HTML 4.01//EN" "http://www.w3.org/TR/html4/strict.dtd">
<html>
<?php
$Title = "Adjustment of Parameters";
include 'php/head.php';
?>
<body>
<script language="javascript" src="webfn.js"></script>
<?php
$showadmmenu = true;
include 'php/nav.php';
?>
<h1>Adjusting parameters</h1>
<p>
Use the following form to adjust the parameters used to regulate the ladder.
Each field may be a number, in the case of rank adjustments a fractional number, probably negative in the case of loss adjustments.
</p>
<p>Please note there is no real check! Only enter sensible values!!!</p>
<form action="adjparam2.php" method="post" enctype="application/x-www-form-urlencoded" name='pform'>
<table>
<tr><th>Function</th><th>Value</th></tr>
<?php
function floatselect($descr, $fname, $curr)  {
	print <<<EOT
<tr>
	<td>$descr</td>
	<td><input type="text" name="$fname" value="$curr" size="10" maxlength="10"></td>
</tr>

EOT;
}
function numselect($descr, $selname, $curr, $min, $max)  {
	print <<<EOT
<tr><td>$descr</td>
<td><select name="$selname">

EOT;
	for ($n = $min;  $n <= $max;  $n++)
   	if ($n == $curr)
    		print <<<EOT
<option selected>$n</option>

EOT;
		else 
			print <<<EOT
<option>$n</option>

EOT;
	print <<<EOT
</select></td></tr>

EOT;
}
$pars = new Params();
$pars->fetchvalues();
floatselect("Fractional amount to add to rank if won game and going up", "wu", $pars->Wonup);
floatselect("Fractional amount to add to rank if won game but not moving", "ws", $pars->Wonstay);
floatselect("Fractional amount to ADD to rank if lost game and going down", "ld", $pars->Losedown);
floatselect("Fractional amount to ADD to rank if lost game but not moving", "ls", $pars->Losestay);
numselect("Number of stones to take off rank difference to get handicap", "hd", $pars->Hcpdiff, 0, 20);
numselect("Maximum number of places between plaeys in challenges", "mp", $pars->Maxplaces, 1, 200);
?>
</table>
<p><input type="submit" name="Sub" value="Click Here"> when ready.</p>
</form>
</div>
</div>
</body>
</html>
