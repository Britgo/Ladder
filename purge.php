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
?>
<!DOCTYPE HTML PUBLIC "-//W3C//DTD HTML 4.01//EN" "http://www.w3.org/TR/html4/strict.dtd">
<html>
<?php
$Title = "Remove inactive players";
include 'php/head.php';
?>
<body>
<script language="javascript" src="webfn.js"></script>
<?php
$showadmmenu = true;
include 'php/nav.php';
?>
<h1>Removing inactive players</h1>
<p>Use the following form to remove players who have not played for some time.</p>
<form action="purge2.php" method="post" enctype="application/x-www-form-urlencoded" name='pform'>
<p>Remove players who have not played for
<select name="nint">
<option value="1">1</option>
<option value="2">2</option>
<option value="3">3</option>
<option value="4">4</option>
<option value="5">5</option>
<option value="6" selected>6</option>
<option value="7">7</option>
<option value="8">8</option>
<option value="9">9</option>
<option value="10">10</option>
</select>
<select name="interval">
<option value="day">Days</option>
<option value="week">Weeks</option>
<option value="month" selected>Months</option>
<option value="year">Year</option>
</select></p>
<p><input type="submit" name="Sub" value="Click Here"> when ready.</p>
</form>
</div>
</div>
</body>
</html>
