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

class PlayerException extends Exception {}

class Player  {
	public $First;
	public $Last;
	public $Rank;
	public $Frank;			// Floating rank
	public $Club;
	public $Email;
	public $Admin;
	public $Userid;
	public $Won;
	public $Lost;
	public $Posn;
	public $Seq;

	// Construct a player object, possibly starting from various
	// versions of the name
		
	public function __construct($f = "", $l = "", $s = 0) {
		if (strlen($f) != 0)  {
			if (strlen($l) != 0) {
				$this->First = $f;
				$this->Last = $l;
			}
			elseif (preg_match("/(.*)\s+(.+)/", $f, $matches))  {
				$this->First = $matches[1];
				$this->Last = $matches[2];
			}
			else
				throw new PlayerException("Cannot parse name");
			}
			$Gotrecs = '';
			$this->Admin = 'N';
			$this->Frank = 0;
			$this->Rank = new Rank();
			$this->Seq = $s;
	}

	// Fill in the name of the player from a "get" request
		
	public function fromget($prefix = "", $htd = false) {
		$this->First = $_GET["${prefix}f"];
		$this->Last = $_GET["${prefix}l"];
		if ($htd) {
			$this->First = htmlspecialchars_decode($this->First);
			$this->Last = htmlspecialchars_decode($this->Last);
		}
		if (strlen($this->First) == 0 || strlen($this->Last) == 0)
			throw new PlayerException("Null name field"); 
	}

	// Use me to get the player we are talking about from a hidden field
	// We'll still perhaps need to get the rest
	
	public function frompost($prefix = "") {
		$this->First = $_POST["${prefix}f"];
		$this->Last = $_POST["${prefix}l"];
		if (strlen($this->First) == 0 || strlen($this->Last) == 0)
			throw new PlayerException("Null post name field"); 
	}
	
	// Use me to get details starting from userid
	
	public function fromid($id) {
		$qid = mysql_real_escape_string($id);
		$ret = mysql_query("select posn,first,last,rank,club,email,admin,wins,losses from player where user='$qid'");
		if (!$ret || mysql_num_rows($ret) == 0)
			throw new PlayerException("Unknown player userid $id");
		$row = mysql_fetch_assoc($ret);
		$this->First = $row['first'];
		$this->Last = $row['last'];
		$this->Frank = $row['rank'] + 0.0;
		$this->Rank = new Rank(round($this->Frank));
		$this->Club = new Club($row["club"]);
		$this->Email = $row["email"];
		$this->Admin = $row["admin"];
		$this->Userid = $id;
		$this->Won = $row['wins'];
		$this->Lost = $row['losses'];
		$this->Posn = $row['posn'] + 0.0;
		$this->fetchclub();		
	}

	// Generate a MySQL query from a player object
		
	public function queryof($prefix = "") {
		$qf = mysql_real_escape_string($this->First);
		$ql = mysql_real_escape_string($this->Last);
		return "${prefix}first='$qf' and ${prefix}last='$ql'";
	}

	// For when we just want the MySQL rendering of the First name
		
	public function queryfirst() {
		return mysql_real_escape_string($this->First);
	}

	// Ditto last name
		
	public function querylast() {
		return mysql_real_escape_string($this->Last);
	}

	// For packaging up a name as a search string
		
	public function urlof() {
		$f = urlencode($this->First);
		$l = urlencode($this->Last);
		return "f=$f&l=$l";
	}

	// For packaging up a name in a selection field
		
	public function selof() {
		$f = $this->First;
		$l = $this->Last;
		return "$f:$l";
	}

	// For undoing the above
		
	public function fromsel($pl) {
		if  (!preg_match("/(.*):(.*)/", $pl, $matches))
			throw new PlayerException("Invalid player selection");
		$this->First = $matches[1];
		$this->Last = $matches[2];
	}

	// Get the rest of the details having got the name
		
	public function fetchdets() {
		$q = $this->queryof();
		$ret = mysql_query("select posn,rank,club,email,admin,user,wins,losses from player where $q");
		if (!$ret)
			throw new PlayerException("Cannot read database for player $q");
		if (mysql_num_rows($ret) == 0)
			throw new PlayerException("Cannot find player");
		$row = mysql_fetch_assoc($ret);
		$this->Frank = $row['rank'] + 0.0;
		$this->Rank = new Rank(round($this->Frank));
		$this->Club = new Club($row["club"]);
		$this->Email = $row["email"];
		$this->Admin = $row["admin"];
		$this->Userid = $row["user"];
		$this->Won = $row['wins'];
		$this->Lost = $row['losses'];
		$this->Posn = $row['posn'] + 0.0;
		$this->fetchclub();		
	}

	// Get more info about the club
		
	public function fetchclub() {
		try {
			$this->Club->fetchdets();
		}
		catch (ClubException $e) {
			// If unknown club set to No club
			$this->Club = new Club('xxx');
			$this->Club->fetchdets();
		}
	}

	// Are we talking about same player
		
	public function is_same($pl) {
		return $this->First == $pl->First && $this->Last == $pl->Last;
	}

	// Prepare first name for display
		
	public function display_first() {
		return htmlspecialchars($this->First);
	}

	// Prepare last name for display
	
	public function display_last() {
		return htmlspecialchars($this->Last);
	}

	// Display whole name
		
	public function display_name() {
		$f = $this->First;
		$l = $this->Last;
		return htmlspecialchars("$f $l");
	}
	
	// Display initials
	
	public function display_initials() {
		return  substr($this->First, 0, 1) . substr($this->Last, 0, 1);
	}

	// Display rank in standard format
		
	public function display_rank() {
		return $this->Rank->display();
	}

	// Get initial letter of last name
		
	public function get_initial() {
		return strtoupper(substr($this->Last, 0, 1));
	}

	// Get initial letter of club name
		
	public function get_club_initial() {
		return strtoupper(substr($this->Club->Name, 0, 1));
	}

	// Display user id
		
	public function display_userid($wminus=1) {
		if ($wminus && strlen($this->Userid) == 0)
			return "-";
		return htmlspecialchars($this->Userid);
	}
	
	public function userid_url() {
		if (strlen($this->Userid) == 0)
			return "";
		return urlencode($this->Userid);
	}

	// Get password
		
	public function get_passwd() {
		$ret = mysql_query("select password from player where {$this->queryof()}");
		if (!$ret || mysql_num_rows($ret) == 0)
			return  "";
		$row = mysql_fetch_array($ret);
		return $row[0];	
	}

	// Get password for "display"
		
	public function disp_passwd() {
		return htmlspecialchars($this->get_passwd());
	}

	// Set password
		
	public function set_passwd($pw)  {
		$qpw = mysql_real_escape_string($pw);
		mysql_query("update player set password='$qpw' where {$this->queryof()}");
	}
	
	// Display email address
	
	public function display_email_link() {
		if (strlen($this->Email) == 0)
			return "";
		$m = htmlspecialchars($this->Email);
		return "<a href=\"mailto:$m\">$m</a>";
	}
		
	public function display_email_nolink() {
		return htmlspecialchars($this->Email);
	}

	// Identify player as hidden item in a form
		
	public function save_hidden($prefix = "") {
		$f = $this->First;
		$l = $this->Last;
		return "<input type=\"hidden\" name=\"${prefix}f\" value=\"$f\"><input type=\"hidden\" name=\"${prefix}l\" value=\"$l\">";
	}

	// Display club as a selection
		
	public function clubopt() {
		$clubs = Club::listclubs();
		print "<select name=\"club\">\n";
		foreach ($clubs as $club) {
			$code = $club->Code;
			$name = $club->Name;
			if ($code == $this->Club->Code)
				print "<option value=\"$code\" selected>$name</option>\n";
			else
				print "<option value=\"$code\">$name</option>\n";
		}
		print "</select>\n";
	}

	// Display rank as a selection
		
	public function rankopt($suff="") {
		$this->Rank->rankopt($suff);
	}

	// Display admin priv as a selection
		
	public function adminopt() {
		print "<select name=\"admin\">\n";
		$poss = array('N', 'A', 'SA');
		foreach ($poss as $pa) {
			if ($this->Admin == $pa)
				print "<option selected>$pa</option>\n";
			else
				print "<option>$pa</option>\n";
		}
		print "</select>\n";	
	}
	
	public function create() {
		$qfirst = mysql_real_escape_string($this->First);
		$qlast = mysql_real_escape_string($this->Last);
		$qclub = mysql_real_escape_string($this->Club->Code);
		$quser = mysql_real_escape_string($this->Userid);
		$qadmin = mysql_real_escape_string($this->Admin);
		$qemail = mysql_real_escape_string($this->Email);
		$r = $this->Rank->Rankvalue;
		// Find a position slot after all players of the same rank
		$ret = mysql_query("select posn from player where rank>=$r order by rank,posn desc limit 1");
		if (!$ret || mysql_num_rows($ret) == 0)  {
			$prev = 0.0;
		}
		else  {
			$row = mysql_fetch_array($ret);
			$prev = $row[0] + 0.0;
		}
		$ret = mysql_query("select posn from player where posn>$prev limit 1");
		if (!$ret || mysql_num_rows($ret) == 0)  {
			$next = $prev + 1000.0;
		}
		else  {
			$row = mysql_fetch_array($ret);
			$next = $row[0] + 0.0;
		}
		$posn = ($next + $prev) / 2.0;
		mysql_query("insert into player (first,last,rank,club,user,email,admin,posn) values ('$qfirst','$qlast',$r,'$qclub','$quser','$qemail','$qadmin',$posn)");
	}
	
	// Update player record of name
		
	public function updatename($newp) {
		$qfirst = mysql_real_escape_string($newp->First);
		$qlast = mysql_real_escape_string($newp->Last);
		mysql_query("update player set first='$qfirst',last='$qlast' where {$this->queryof()}");
		// Any games as winner
		mysql_query("update game set wfirst='$qfirst',wlast='$qlast' where {$this->queryof('w')}");
		// And as loser
		mysql_query("update game set lfirst='$qfirst',llast='$qlast' where {$this->queryof('l')}");
		$this->First = $newp->First;
		$this->Last = $newp->Last;
	}
	
	// Update player record
	
	public function update() {
		$qclub = mysql_real_escape_string($this->Club->Code);
		$quser = mysql_real_escape_string($this->Userid);
		$qadmin = mysql_real_escape_string($this->Admin);
		$qemail = mysql_real_escape_string($this->Email);
		$r = $this->Rank->Rankvalue;
		if ($r == round($this->Frank))		// Keeping fractional value
			$r = $this->Frank;
		mysql_query("update player set club='$qclub',user='$quser',admin='$qadmin',email='$qemail',rank=$r where {$this->queryof()}");
	}

	public function updrank($r) {
		$this->Rank->Rankvalue = round($r);
		$this->Frank = $r;
		$w = $this->Won;
		$l = $this->Lost;
		if  (!mysql_query("update player set rank=$r,wins=$w,losses=$l,lastgame=now() where {$this->queryof()}"))
			throw new PlayerException(mysql_error());
	}
	
	public function updposn($p) {
		$this->Posn = $p;
		mysql_query("update player set posn=$p where {$this->queryof()}");
	}
	
	public function prevposn() {
		$ret = mysql_query("select posn from player where posn<{$this->Posn} order by posn desc limit 1");
		if (!$ret || mysql_num_rows($ret) == 0)
			return 0.0;
		$row = mysql_fetch_array($ret);
		return $row[0] + 0.0;
	}
	
	public function checklimrank() {
		if ($this->Frank > 8.0)
			$this->Frank = 8.0;
		elseif ($this->Frank < -30.0)
			$this->Frank = -30.0;
	}
	
	public function accwin($pars, $moving = false) {
		$origrank = $this->Rank->Rankvalue;
		if ($moving)
			$newrank = $this->Frank + $pars->Wonup;
		else
			$newrank = $this->Frank + $pars->Wonstay;
		$this->checklimrank();
		$this->Won++;
		$this->updrank($newrank);
		return $this->Rank->Rankvalue - $origrank;
	}
	
	public function accloss($pars, $moving = false) {
		$origrank = $this->Rank->Rankvalue;
		if ($moving)
			$newrank = $this->Frank + $pars->Losedown;
		else
			$newrank = $this->Frank + $pars->Losestay;
		$this->checklimrank();
		$this->Lost++;
		$this->updrank($newrank);
		return $this->Rank->Rankvalue - $origrank;
	}

	// List all players in specified order
	// Don't get details for now.

	public static function list_players($order = "posn,last,first,rank desc") {
		$ret = mysql_query("select first,last from player order by $order");
		$result = array();
		if ($ret) {
			$seq = 1;
			while ($row = mysql_fetch_assoc($ret))  {
				array_push($result, new player($row['first'], $row['last'], $seq));
				$seq++;
			}
		}
		return $result;
	}

	public static function list_admins() {
		$ret = mysql_query("select first,last from player where admin!='N'");
		$result = array();
		if ($ret) {
			while ($row = mysql_fetch_assoc($ret))
				array_push($result, new player($row['first'], $row['last']));
		}
		foreach ($result as $p)
			$p->fetchdets();
		return $result;
	}

	public static function list_userids() {
		$ret = mysql_query("select user from player where length(user)>0 order by user");
		$result = array();
		if ($ret) {
			while ($row = mysql_fetch_array($ret))
				array_push($result, $row[0]);
		}
		return  $result;
	}

	// List of all ranks people are

	public static function list_player_ranks() {
		$ret = mysql_query("select rank from player group by rank order by rank desc");
		$result = array();
		if  ($ret)  {
			while ($row = mysql_fetch_array($ret)) {
				array_push($result, $row[0]);
			}
		}
		return $result;
	}
}
	
?>
