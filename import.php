#!/usr/bin/php
<?php
 
$jabber_domain = 'duuit.com';
 
$dbh_elgg = null;
$dbh_ejabberd = null;
 
$dsn_elgg = 'mysql:dbname=duuit;host=localhost';
$dsn_ejabberd = 'mysql:dbname=ejabberd;host=localhost';
 
$user = 'root';
$password = 'ENTERPASS';
 
$relationship_type = 'friend';
 
try {
  $dbh_elgg = new PDO($dsn_elgg, $user, $password);
 
  $sql = 'SELECT guid, name, username FROM beeprod_users_entity';
  $sth = $dbh_elgg->prepare($sql);
  $sth->execute();
 
  $users = array();
  while ($row = $sth->fetch(PDO::FETCH_ASSOC))
    $users[$row['guid']] = $row;
 
  $sql  = 'SELECT guid_one, guid_two FROM beeprod_entity_relationships ';
  $sql .= 'WHERE relationship = ?;';
  $sth = $dbh_elgg->prepare($sql);
 
  $sth->bindParam(1, $relationship_type);
  $sth->execute();
 
  $dbh_ejabberd = new PDO($dsn_ejabberd, $user, $password);
  $dbh_ejabberd->beginTransaction();
 
  while ($row = $sth->fetch(PDO::FETCH_ASSOC)) {
    $sql = 'INSERT INTO rosterusers (username, jid, nick, subscription, ask, server, type) VALUES (?, ?, ?, ?, ?, ?, ?);';
    $sth_ejabberd = $dbh_ejabberd->prepare($sql);
 
 
    $username = $users[$row['guid_one']]['username'];
    $jid = $users[$row['guid_two']]['username'] . '@' . $jabber_domain;
    $nick = $users[$row['guid_two']]['name'];
    $subscription = 'B';
    $ask = 'N';
    $server = 'N';
    $type = 'item';
 
    $sth_ejabberd->execute(array($username, $jid, $nick, $subscription, $ask, $server, $type));
 
    echo $username . ' registered ' . $jid . ' as a friend in his roster.' . "\n";
  }
 
  $dbh_ejabberd->commit();
 
  $dbh_elgg = null;
  $dbh_ejabberd = null;
} catch (PDOException $e) {
  if ($dbh_ejabberd != null)
    $dbh_ejabberd->rollBack();
  echo $e->getMessage();
}
?>
