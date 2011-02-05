<?php
/* ************************************************************
Copyright (C) 2010-2011 - Ger Hobbelt
Revision:   CompactCMS - v 1.4.2

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

/* make sure no-one can run anything here if they didn't arrive through 'proper channels' */
if(!defined("COMPACTCMS_CODE")) { define("COMPACTCMS_CODE", 1); } /*MARKER*/

// Set current && additional step
//$nextstep = getPOSTparam4IdOrNumber('do');

//$may_upgrade = (!empty($_SESSION['variables']['may_upgrade']) && $_SESSION['variables']['may_upgrade'] != false); 
//$do_upgrade = (!empty($_SESSION['variables']['do_upgrade']) && $_SESSION['variables']['do_upgrade'] != false); 



function perform_upgrade($db, $log, $errors, $sqldump)
{
	global $cfg;
	
	$sql = file_get_contents(BASE_PATH.'/media/files/ccms-restore/compactcms-sqldump.sql');
	$sql = preg_replace('/\\bccms_\\B/', $_SESSION['variables']['db_prefix'], $sql); // all tables here-in will get the correct prefix: we're doing a restore, so we have this info from the config.inc.php file, but we may have changed our setup in the install run just before!
	$sql = preg_replace("/'admin', '[0-9a-f]{32}'/", "'admin', '".md5($_SESSION['variables']['userPass'].$_SESSION['variables']['authcode'])."'", $sql);
	// note that the passwords for the other users in the backup may be invalid IFF you changed the authcode!
	$sql = str_replace("\r\n", "\n", $sql);
	
	// Execute per sql piece: 
	$currently_in_sqltextdata = false;
	$query_so_far = '';
	$queries = explode(";\n", $sql);
	foreach($queries as $tok)
	{
		// filter query: remove comment lines, then see if there's anything left to BE a query...
		$lines = array_filter(explode("\n", $tok), "is_a_sql_query_piece");
		if ($currently_in_sqltextdata)
		{
			/*
			 * MySQL supports multiline texts in queries; apparently we have a text here which has a line ending with a semicolon :-(
			 * 
			 * We can only be certain it's a b0rked query by the time we've reached the very end of the SQL file!
			 */
			$query_so_far .= implode("\n", $lines) . ";\n";
			continue;
		}
			
		$tok = trim($query_so_far . implode("\n", $lines));
		$query_so_far = '';

		if (empty($tok))
			continue;

		/*
		 * - ignore 'DROP TABLE' queries
		 * 
		 * - process 'CREATE TABLE' queries by REPLACING them with 'TRUNCATE TABLE' queries; 
		 *   after all, they will soon be followed up with INSERT INTO queries and we don't 
		 *   want the 'fresh install' records to linger in there when performing 
		 *   an upgrade/restore.
		 *   
		 *   NOTE that SQL dumps since 1.4.2 (rev. 2011/01/11) do contain their own TRUNCATE TABLE
		 *   statements, and we do know that is so, but here we wish to be as backwards compatible
		 *   as humanly possible. Besides a dual TRUNCATE TABLE doesn't hurt, so we don't filter
		 *   those TRUNCATE statements when they exist in the original SQL script.
		 */
		if (preg_match('/DROP\s+TABLE/i', $tok))
			continue;
			
		if (preg_match('/CREATE\s+TABLE\s+(IF\s+NOT\s+EXISTS\s+)?`?([a-zA-Z0-9_\-]+)`?\s+\(/is', $tok, $matches))
		{
			if (!$cfg['IN_DEVELOPMENT_ENVIRONMENT'])
			{
				$results = $db->TruncateTable($matches[2]);
				if ($results == false)
				{
					$errors[] = 'Error: executing query: ' . $db->GetLastSQL();
					$errors[] = $db->Error();
					$err++;
				}
			}
			else
			{
				$sqldump[] = "Execute query:\n---------------------------------------\nTRUNCATE TABLE `" . $matches[2] . "`\n---------------------------------------\n";
			}
		}
		else
		{
			if (!$cfg['IN_DEVELOPMENT_ENVIRONMENT'])
			{
				$results = $db->Query($tok);
				if ($results == false)
				{
					$errors[] = 'Error: executing query: ' . $tok;
					$errors[] = $db->Error();
					$err++;
				}
			}
			else
			{
				$sqldump[] = "Execute query:\n---------------------------------------\n" . $tok . "\n---------------------------------------\n";
			}
		}
	}
	
	if ($currently_in_sqltextdata)
	{
		echo "<pre>B0rked on query:\n".$query_so_far."\n---------------------------------\n";
		die();
	}
	
	/*
	For older versions' SQL scripts, we need to perform a couple of conversions before we can call the upgrade a success:
	
	- named pageID fields should be converted to numeric page_id fileds and then dropped!
	
	- the lightboxes should be imported into the database, as those were flatfile items before.
	*/

/*	
	UPDATE ccms_cfgnews AS n SET page_id = ( SELECT page_id FROM ccms_pages AS p WHERE n.pageID = p.urlpage );
	UPDATE ccms_modnews AS n SET page_id = ( SELECT page_id FROM ccms_pages AS p WHERE n.pageID = p.urlpage );
	UPDATE ccms_cfgcomment AS n SET page_id = ( SELECT page_id FROM ccms_pages AS p WHERE n.pageID = p.urlpage );
	UPDATE ccms_modcomment AS n SET page_id = ( SELECT page_id FROM ccms_pages AS p WHERE n.pageID = p.urlpage );

	ALTER TABLE `ccms_cfgnews` DROP `pageID`; 
	ALTER TABLE `ccms_modnews` DROP `pageID`; 
	ALTER TABLE `ccms_cfgcomment` DROP `pageID`; 
	ALTER TABLE `ccms_modcomment` DROP `pageID`; 
*/
	
	
	// on success, return 0	
	return 0; // Database structure and data successfully imported
}
	
?>