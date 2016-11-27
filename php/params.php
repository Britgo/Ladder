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

class ParamException extends Exception {}

class Params  {
	public $Wonup;				//  Amount to add to rank if winning and going up
	public $Wonstay;			//  Amount to add to rank if winning but staying
	public $Losedown;			//  Amount to ADD to rank (possibly -ve) if losing and going down
	public $Losestay;			//  Amount to ADD to rank (possibly -ve) if losing and staying)
	public $Hcpdiff;			//  Amount to take off rank difference to get handicap
	public $Maxplaces;		//  Maximum number of places up the ladder to challenge

	public function __construct() {
		$this->Wonup = 0.3;
		$this->Wonstay = 0.1;
		$this->Losedown = -0.3;
		$this->Losestay = -0.2;
		$this->Hcpdiff = 1;
		$this->Maxplaces = 10;
	}
	
	public function fetchvalues() {
		$ret = mysql_query("select sc,val from params");
		if (!$ret)
			throw new ParamException(mysql_error());
		while ($row = mysql_fetch_assoc($ret)) {
			$v = $row["val"];
			switch ($row["sc"])  {
			case 'wu':
				$this->Wonup = $v;
				break;
			case 'ws':
				$this->Wonstay = $v;
				break;
			case 'ld':
				$this->Losedown = $v;
				break;
			case 'ls':
				$this->Losestay = $v;
				break;
			case 'hd':
				$this->Hcpdiff = round($v);
				break;
			case 'mp';
				$this->Maxplaces = round($v);
				break;
			}
		}
	}
	
	public function putvalues() {
		if (!mysql_query("delete from params"))
			throw new ParamException(mysql_error());
		mysql_query("insert into params (sc,val) values ('wu', $this->Wonup)");
		mysql_query("insert into params (sc,val) values ('ws', $this->Wonstay)");
		mysql_query("insert into params (sc,val) values ('kd', $this->Losedown)");
		mysql_query("insert into params (sc,val) values ('ls', $this->Losestay)");
		mysql_query("insert into params (sc,val) values ('hd', $this->Hcpdiff)");
		mysql_query("insert into params (sc,val) values ('mp', $this->Maxplaces)");
	}
}
?>
