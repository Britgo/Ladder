<?php
//   Copyright 2009 John Collins

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

class ClubException extends Exception {}

class Club {
	public $Code;				// 3-letter code
	public $Name;				// Name of club
	
	public function __construct($n="") {
		if (strlen($n) != 0)
			$this->Code = $n;
	}
	
	// Get club code from a get request ?cl=xyz
	
	public function fromget() {
		$this->Code = $_GET["cl"];
		if (strlen($this->Code) == 0)
			throw new ClubException("Null club code field"); 
	}

	// Get club code from a hidden field in a form
		
	public function frompost($prefix = "") {
		$this->Code = $_POST["${prefix}cl"];
		if (strlen($this->Code) == 0)
			throw new ClubException("Null post name field"); 
	}				

	// Assemble MySQL query string from club
	
	public function queryof() {
		$qc = mysql_real_escape_string($this->Code);
		return "code='$qc'";
	}

	// Assemble Get request
		
	public function urlof() {
		$c = urlencode($this->Code);
		return "cl=$c";
	}

	// Fetch other details of club from database
		
	public function fetchdets() {
		$q = $this->queryof();
		$ret = mysql_query("select name from club where $q");
		if (!$ret)
			throw new ClubException("Cannot read database for club");
		if (mysql_num_rows($ret) == 0)
			throw new ClubException("Cannot find club");
		$row = mysql_fetch_assoc($ret);
		$this->Name = $row["name"];
	}

	public function display_code() {
		return htmlspecialchars($this->Code);
	}
	
	public function display_name() {
		return htmlspecialchars($this->Name);
	}
	

	// Save club code as hidden field in form
		
	public function save_hidden($prefix = "") {
		$c = $this->Code;
		return "<input type=\"hidden\" name=\"${prefix}cl\" value=\"$c\">";
	}

	// Generate selection option for night
		
	public function nightopt()
	{
		$nights = array("None", "Sun", "Mon", "Tue", "Wed", "Thu", "Fri", "Sat");
		print "<select name=\"night\">\n";
		for ($i = -1;  $i <= 6; $i++) {
			if ($i == $this->Night)
				print "<option value=$i selected>";
			else
				print "<option value=$i>";
			print $nights[$i+1];
			print "</option>\n";
		}
		print "</select>\n";
	}

	// Create club
		
	public function create() {
		$qcode = mysql_real_escape_string($this->Code);
		$qname = mysql_real_escape_string($this->Name);
		mysql_query("insert into club (code,name) values ('$qcode','$qname')");
	}

	// Update club
		
	public function update() {
		$qname = mysql_real_escape_string($this->Name);
		mysql_query("update club set name='$qname' where {$this->queryof()}");			
	}
}

function listclubs() {
	$result = array();
	$ret = mysql_query("select code,name from club order by name");
	if ($ret) {
		while ($row = mysql_fetch_assoc($ret)) {
			$cl = new Club($row["code"]);
			$cl->Name = $row["name"];
			array_push($result, $cl);
		}
	}
	return $result;
}
?>
