<?php
/* ************************************************************
Copyright (C) 2008 - 2010 by Xander Groesbeek (CompactCMS.nl)
Revision:	CompactCMS - v 1.4.1
	
This file is part of CompactCMS.

CompactCMS is free software: you can redistribute it and/or modify
it under the terms of the GNU General Public License as published by
the Free Software Foundation, either version 3 of the License, or
(at your option) any later version.

CompactCMS is distributed in the hope that it will be useful,
but WITHOUT ANY WARRANTY; without even the implied warranty of
MERCHANTABILITY or FITNESS FOR A PARTICULAR PURPOSE.  See the
GNU General Public License for more details.

A reference to the original author of CompactCMS and its copyright
should be clearly visible AT ALL TIMES for the user of the back-
end. You are NOT allowed to remove any references to the original
author, communicating the product to be your own, without written
permission of the original copyright owner.

You should have received a copy of the GNU General Public License
along with CompactCMS. If not, see <http://www.gnu.org/licenses/>.
	
> Contact me for any inquiries.
> E: Xander@CompactCMS.nl
> W: http://community.CompactCMS.nl/forum
************************************************************ */

// Include general configuration
require_once(dirname(dirname(__FILE__)).'/sitemap.php');

// If session already exists
if(isset($_SESSION['ccms_userID']) && isset($_SESSION['ccms_userName'])) {
	header("Location: ../../admin/index.php");
	exit();
}

// Check for ./install directory
if(is_dir('../../_install/')) {
	die('<strong>Security risk: the installation directory is still present.</strong><br/>Either first <a href="../../_install/">run the installer</a>, or remove the <em>./_install</em> directory, before accessing <a href="../../admin/">the back-end</a>.');
}

// Do authentication
if(isset($_POST['submit']) && $_SERVER['REQUEST_METHOD']=="POST") 
{                               
	$userName = mysql_real_escape_string($_POST['userName']);
	$userPass = mysql_real_escape_string($_POST['userPass']).$cfg['authcode'];

	if(empty($userName) && empty($userPass)){
		$_SESSION['logmsg'] = $ccms['lang']['login']['nodetails'];
	} elseif(empty($userName)){
		$_SESSION['logmsg'] = $ccms['lang']['login']['nouser'];
	} elseif(empty($userPass)){
		$_SESSION['logmsg'] = $ccms['lang']['login']['nopass'];
	} else{
		$matchSql 		= "SELECT * FROM `".$cfg['db_prefix']."users` WHERE userName = '".$userName."' AND userPass = '".md5($userPass)."' AND userActive = '0'";
		$matchResult 	= mysql_query($matchSql);
		$matchNumRows 	= mysql_num_rows($matchResult);
			
		if($matchNumRows>0){
			$_SESSION['logmsg'] = $ccms['lang']['login']['notactive'];
		} else{
			// Select statement
			$sql 	= "SELECT * FROM `".$cfg['db_prefix']."users` WHERE userName = '".$userName."' AND userPass = '".md5($userPass)."' AND userActive = '1'";
			$result	= mysql_query($sql);
			$row 	= mysql_fetch_assoc($result);
			
			// If no match: count attempt and show error
			if($userName != $row['userName'] && md5($userPass) != $row['userPass'])
			{
				$_SESSION['logmsg'] = $ccms['lang']['login']['nomatch'];

			// If all checks are okay
			} 
			elseif($userName == $row['userName'] && md5($userPass) == $row['userPass'] && $row['userActive'] > 0) 
			{
				// Update latest login date
				if(mysql_query("UPDATE `".$cfg['db_prefix']."users` SET userLastlog='".date('Y-m-d G:i:s')."' WHERE userID=".$row['userID'])) 
				{
					// Set system wide session variables
					$_SESSION['ccms_userID']	= $row['userID'];
					$_SESSION['ccms_userName']	= $row['userName'];
					$_SESSION['ccms_userFirst']	= $row['userFirst'];
					$_SESSION['ccms_userLast']	= $row['userLast'];
					$_SESSION['ccms_userLevel']	= $row['userLevel'];
					
					// Return functions result
					unset($_SESSION['logmsg']);
					header("Location: ../../admin/index.php");
					exit();
				}
			}
		}
	}
}
?>
<!DOCTYPE html PUBLIC "-//W3C//DTD XHTML 1.0 Strict//EN" "http://www.w3.org/TR/xhtml1/DTD/xhtml1-strict.dtd">
<html xmlns="http://www.w3.org/1999/xhtml" xml:lang="<?php echo $cfg['language']; ?>">
<html>
	<head>
		<meta http-equiv="Content-Type" content="text/html; charset=utf-8" />
		<title>CompactCMS Administration</title>
		<meta name="description" content="CompactCMS administration. CompactCMS is a light-weight and SEO friendly Content Management System for developers and novice programmers alike." />
		<link rel="stylesheet" type="text/css" href="../../admin/img/styles/base.css,layout.css,sprite.css" />
	</head>
<body>

<div id="login-wrapper" class="container">
	<div id="help" class="span-8 colborder">
		<div id="logo" class="sprite logo"><h1>CompactCMS administration</h1></div>
		<span class="ss_sprite ss_door_open">&#160;</span><h2 style="display:inline;"><?php echo $ccms['lang']['login']['login']; ?></h2>
		<?php echo $ccms['lang']['login']['welcome'];?>
	</div>
	
	<div id="login" class="span-9">
		<?php if(isset($_SESSION['logmsg'])||!empty($_SESSION['logmsg'])) { ?>
			<div class="loginMsg"><?php echo (isset($_SESSION['logmsg'])&&!empty($_SESSION['logmsg'])?$_SESSION['logmsg']:null);?></div>
		<?php } ?>
		<p>&#160;</p>
		<form id="loginFrm" name="loginFrm" class="clear" action="./auth.inc.php" method="post">
			<label for="userName"><?php echo $ccms['lang']['login']['username']; ?></label><input type="text" class="alt title" autofocus placeholder="username" name="userName" style="width:300px;" value="<?php echo (!empty($_POST['userName'])?$_POST['userName']:null);?>" id="userName" />
			<br class="clear"/>
			<label for="userPass"><?php echo $ccms['lang']['login']['password']; ?></label><input type="password" class="title" name="userPass" style="width:300px;" value="" id="userPass" />
			
			<p class="span-8 right">
				<button name="submit" type="submit"><span class="ss_sprite ss_lock_go"><?php echo $ccms['lang']['login']['login']; ?></span></button>
			</p>
		</form>
	</div>
</div>
<p class="quiet small" style="text-align:center;">&copy; 2010 <a href="http://www.compactcms.nl" title="Maintained with CompactCMS.nl">CompactCMS.nl</a></p>

</body>
</html>