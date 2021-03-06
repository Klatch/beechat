<?php
	/**
	 * Beechat
	 * 
	 * @package beechat
	 * @license http://www.gnu.org/licenses/old-licenses/gpl-2.0.html GNU Public License version 2
	 * @author Beechannels <contact@beechannels.com>
	 * @copyright Beechannels 2007-2010
	 * @link http://beechannels.com/
	 */
	
	header('Content-type: application/json');
	gatekeeper();
	
	$user = $_SESSION['user'];
	$t = array('username' => $user->username,
		   'password' => $user->password);
	
	echo json_encode($t);
	
	exit();
?>
