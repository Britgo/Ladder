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
$wu = $_POST["wu"];
if (strlen($wu) == 0) {
    include 'php/wrongentry.php';
    exit(0);
}
$pars = new Params();
$pars->fetchvalues();
$pars->Wonup = $wu + 0.0;
$pars->Wonstay = $_POST["ws"] + 0.0;
$pars->Losedown = $_POST["ld"] + 0.0;
$pars->Losestay = $_POST["ls"] + 0.0;
$pars->Hcpdiff = $_POST["hd"] + 0;
$pars->Maxplaces = $_POST["mp"] + 0;
$pars->putvalues();
?>
<!DOCTYPE HTML PUBLIC "-//W3C//DTD HTML 4.01//EN" "http://www.w3.org/TR/html4/strict.dtd">
<html>
<?php
$Title = "Adjustment of Parameters Complete";
include 'php/head.php';
?>
<body>
<script language="javascript" src="webfn.js"></script>
<?php
$showadmmenu = true;
include 'php/nav.php';
?>
<h1>Adjusting parameters Complete</h1>
<p>Finished adjusting parameters.</p>
</div>
</div>
</body>
</html>
