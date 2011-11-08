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
	public $Wont;
	public $Losst;
	public $Maxdiff;

	public function __construct() {
		$this->Wont = 3;
		$this->Losst = 3;
		$this->Maxdiff = 6;
	}
	
	public function fetchvalues() {
		$ret = mysql_query("select sc,val from params");
		if (!$ret)
			throw new ParamException(mysql_error());
		while ($row = mysql_fetch_assoc($ret)) {
			$v = $row["val"];
			switch ($row["sc"])  {
			case 'wt':
				$this->Wont = $v;
				break;
			case 'lt':
				$this->Losst = $v;
				break;
			case 'md':
				$this->Maxdiff = $v;
				break;
			}
		}
	}
	
	public function putvalues() {
		if (!mysql_query("delete from params"))
			throw new ParamException(mysql_error());
		mysql_query("insert into params (sc,val) values ('wt', $this->Wont)");
		mysql_query("insert into params (sc,val) values ('lt', $this->Losst)");
		mysql_query("insert into params (sc,val) values ('md', $this->Maxdiff)");
	}
}
?>
