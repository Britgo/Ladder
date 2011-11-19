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
Each field may be a number.
</p>
<p>Please note there is no real check! Only enter sensible values!!!</p>
<form action="adjparam2.php" method="post" enctype="application/x-www-form-urlencoded" name='pform'>
<table>
<tr><th>Function</th><th>Value</th></tr>
<?php
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
numselect("Max rank difference to enter ladder", "md", $pars->Maxdiff, 1, 9);
numselect("Consecutive wins to raise rank", "wt", $pars->Wont, 1, 20);
numselect("Consecutive losses to lower rank", "lt", $pars->Losst, 1, 20);
?>
</table>
<p><input type="submit" name="Sub" value="Click Here"> when ready.</p>
</form>
</div>
</div>
</body>
</html>
