#! /usr/bin/perl

# Initialise BGA ladder from current rating list database

use POSIX;
use DBD::mysql;

# First open the rating list DB

$RL_database = DBI->connect("DBI:mysql:ratinglist:britgo.org", "rluser", "Get Ratings");

# Get the last shodan rating and one stone from the calibration

$sfh = $RL_database->prepare("SELECT shodan,onestone FROM calibration ORDER BY cdate DESC LIMIT 1");
$sfh->execute;
if (my @row = $sfh->fetchrow_array) {
	$shodan = $row[0];
	$onestone = $row[1];
}

$sfh = $RL_database->prepare("SELECT first,last,rating,club,email FROM player WHERE suppress=0 AND since >= DATE_SUB(CURRENT_DATE, INTERVAL 2 YEAR) ORDER BY rating desc,last");
$sfh->execute;

$Lad_database = DBI->connect("DBI:mysql:ladder:britgo.org", "ladder-acc", "bga\@ladder\@pw");
$sfhl = $Lad_database->prepare("DELETE FROM player");
$sfhl->execute;

$posn = 1000;
while (my @row = $sfh->fetchrow_array)  {
	my ($first,$last,$rating,$clubc,$email) = @row;
   my $strength = ($rating - $shodan) / $onestone;
   if ($strength >= 0)  {
   	$strength = POSIX::floor($strength + 0.5);
   }
   else {
   	$strength = POSIX::ceil($strength - 0.5);
   }
   my $qfirst = $Lad_database->quote($first);
   my $qlast = $Lad_database->quote($last);
   my $qclub = $Lad_database->quote($clubc);
   my $qemail = $Lad_database->quote($email);
   my $e = $Lad_database->prepare("INSERT INTO player (first,last,club,rank,posn,email) VALUES ($qfirst,$qlast,$qclub,$strength,$posn,$qemail)");
	$e->execute;
	$posn += 1000;
 }
 $sfh = $Lad_database->prepare("UPDATE player SET user='jmc',password='Curry',admin='SA',email='jmc\@toad.me.uk' where first='John' and last='Collins'");
 $sfh->execute;
 
 