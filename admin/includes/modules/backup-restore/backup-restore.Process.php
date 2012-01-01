<?php
 /**
 * Copyright (C) 2008 - 2010 by Xander Groesbeek (CompactCMS.nl)
 *
 * @package CompactCMS.nl
 * @license GNU General Public License v3
 *
 * This file is part of CompactCMS.
 *
 * CompactCMS is free software: you can redistribute it and/or modify
 * it under the terms of the GNU General Public License as published by
 * the Free Software Foundation, either version 3 of the License, or
 * (at your option) any later version.
 *
 * CompactCMS is distributed in the hope that it will be useful,
 * but WITHOUT ANY WARRANTY; without even the implied warranty of
 * MERCHANTABILITY or FITNESS FOR A PARTICULAR PURPOSE.  See the
 * GNU General Public License for more details.
 *
 * A reference to the original author of CompactCMS and its copyright
 * should be clearly visible AT ALL TIMES for the user of the back-
 * end. You are NOT allowed to remove any references to the original
 * author, communicating the product to be your own, without written
 * permission of the original copyright owner.
 *
 * You should have received a copy of the GNU General Public License
 * along with CompactCMS. If not, see <http://www.gnu.org/licenses/>.
 *
 * > Contact me for any inquiries.
 * > E: Xander@CompactCMS.nl
 * > W: http://community.CompactCMS.nl/forum
**/

/* make sure no-one can run anything here if they didn't arrive through 'proper channels' */
if(!defined("COMPACTCMS_CODE")) { define("COMPACTCMS_CODE", 1); } /*MARKER*/

/*
We're only processing form requests / actions here, no need to load the page content in sitemap.php, etc.
*/
if (!defined('CCMS_PERFORM_MINIMAL_INIT')) { define('CCMS_PERFORM_MINIMAL_INIT', true); }


// Compress all output and coding
header('Content-type: text/html; charset=UTF-8');

// Define default location
if (!defined('BASE_PATH'))
{
	$base = str_replace('\\','/',dirname(dirname(dirname(dirname(dirname(__FILE__))))));
	define('BASE_PATH', $base);
}

// Include general configuration
/*MARKER*/require_once(BASE_PATH . '/lib/sitemap.php');


class FbX extends CcmsAjaxFbException {}; // nasty way to do 'shorthand in PHP -- I do miss my #define macros! :'-|

// Some security functions


/* make darn sure only authenticated users can get past this point in the code */
if(empty($_SESSION['ccms_userID']) || empty($_SESSION['ccms_userName']) || !checkAuth())
{
	// this situation should've caught inside sitemap.php-->security.inc.php above! This is just a safety measure here.
	die_with_forged_failure_msg(__FILE__, __LINE__); // $ccms['lang']['auth']['featnotallowed']
}


// Prevent PHP warning by setting default (null) values
$do_action = getGETparam4IdOrNumber('action');


// Include back-up functions
/*MARKER*/require_once('./functions.php');





/**
 *
 * Create requested backup archive
 *
 */
if ($do_action == 'backup')
{
	FbX::SetFeedbackLocation('backup-restore.Manage.php');
	$fd = false;
	try
	{
		$current_user = '-' . preg_replace('/[^a-zA-Z0-9\-]/', '_', $_SESSION['ccms_userFirst'] . '_' . $_SESSION['ccms_userLast']);

		/*
		 * Also backup the config php file: that one contains critical data, such as
		 * the auth code, without which, the backup is not complete: the authcode is
		 * required to ensure the MD5 hashes stored in the DB per user are still valid
		 * when the backup is restored at an unfortunate moment later in time.
		 */
		$configBackup       = array(
								 array('dir' => '../../../../content/')
								,array('tree' => '../../../../lib/templates/')
								,array('file' => '../../../../lib/config.inc.php')
								,array('tree' => '../../../../media/albums/:/\.txt$/i')
								//,array('tree' => '../../../../media/albums/:/\/_thumbs\//i')
							);
		$configBackupDir    = '../../../../media/files/';
		$backupName         = date('Ymd_His').'-data'.$current_user.'.zip';
		
		$file_collection = array();
		$listfile = $configBackupDir . $backupName . '.list';

		$fileName = $configBackupDir . $backupName;
		$fd = @fopen($fileName, 'wb');
		if (!$fd)
		{
			throw new FbX($ccms['lang']['system']['error_openfile'] . ": " . $fileName);
		}
		else
		{
			$createZip = new createZip($fd);
			
			if (isset($configBackup) && is_array($configBackup) && count($configBackup) > 0)
			{
				foreach ($configBackup as $elem)
				{
					foreach($elem as $scantype => $spec)
					{
						$pathspec = explode(':', $spec);
						$dir = $pathspec[0];
						$regex_to_match = (count($pathspec) > 1 ? $pathspec[1] : null);

						/*
						 strip off the relative-path-to-root so we're left with the full, yet relative, path.

						 Handy when restoring data: extract zip equals (almost) done.
						*/
						$basename = substr($dir, strlen('../../../../'));
						if (is_file($dir))
						{
							if ($scantype == 'file')
							{
								$file_collection[] = array('file' => $basename);
								
								// kick the PHP watchdog and make sure we can run for a long time at the same time:
								set_time_limit(0);
									
								$fileContents = file_get_contents($dir);
								if (false === $createZip->addFile($fileContents,$basename))
								{
									throw new FbX($ccms['lang']['system']['error_write'] . ": " . $fileName);
								}
							}
						}
						else
						{
							if ($scantype != 'file')
							{
								$basename .= (substr($basename, -1, 1) != '/' ? '/' : '');
								//$file_collection[] = array('dir' => $basename);
								$createZip->addDirectory($basename);
								
								$files = directoryToArray($dir, ($scantype == 'tree'), $regex_to_match);
								/*
								 * opendir+readdir deliver the file set in arbitrary order.
								 *
								 * In order for this code to work, we'll need a known order of the files.
								 */
								sort($files, SORT_STRING);

								foreach ($files as $file)
								{
									$zipPath = explode($dir,$file);
									$zipPath = $zipPath[1];
									if (is_dir($file))
									{
										//$file_collection[] = array('dir' => $basename . $zipPath);
										$createZip->addDirectory($basename . $zipPath);
									}
									else
									{
										$file_collection[] = array('file' => $basename . $zipPath);

										// kick the PHP watchdog and make sure we can run for a long time at the same time:
										set_time_limit(0);
											
										$fileContents = file_get_contents($file);
										if (false === $createZip->addFile($fileContents, $basename . $zipPath))
										{
											throw new FbX($ccms['lang']['system']['error_write'] . ": " . $fileName);
										}
									}
								}
							}
						}
					}
				}
			}

			$sqldump = $db->Dump();
			if ($sqldump === false)
			{
				throw new FbX($db->MyDyingMessage());
			}
			else
			{
				/*
				 * And make sure we 'position' the .sql file just right for a subsequent
				 * restore through our installer/wizard: to make that happen it has to live
				 * in the /_docs/ directory.
				 */
				$createZip->addDirectory('media');
				$createZip->addDirectory('media/files');
				$createZip->addDirectory('media/files/ccms-restore');
				$createZip->addFile($sqldump, 'media/files/ccms-restore/compactcms-sqldump.sql');
			}

			$out = $createZip->saveZipFile();
			if ($out === false)
			{
				throw new FbX($ccms['lang']['system']['error_write'] . ": " . $fileName);
			}
			fclose($fd);
			$fd = false;
		}
		
		@file_put_contents($listfile, str_replace('","', "\",\n  \"", json_encode($file_collection)));
		
		/*
		 * To facilitate the auto-upgrade path we copy the current config.inc.php
		 * and write the SQL dump to another location:
		 *   /media/files/ccms-restore/config.inc.php
		 *   /media/files/ccms-restore/compactcms-sqldump.sql
		 * These files will be picked up by the installer/wizard to perform an
		 * automated upgrade when the admin so desires.
		 *
		 * Note the comment in /_install/index.php: the SQL DUMP must be the
		 * VERY LAST FILE WRITTEN during the backup action (as we depend on
		 * its 'last modified' timestamp to be the latest thing in the
		 * content/media zone!
		 */
		if (!file_exists(BASE_PATH . '/media/files/ccms-restore'))
		{
			@mkdir(BASE_PATH . '/media/files/ccms-restore', fileperms(BASE_PATH . '/media/files'));
		}

		$cfgfile = BASE_PATH . '/lib/config.inc.php';
		$fileContents = file_get_contents($cfgfile);
		if (!$fileContents)
		{
			throw new FbX($ccms['lang']['system']['error_openfile'] . ": " . $cfgfile);
		}
		$cfgfile = BASE_PATH . '/media/files/ccms-restore/config.inc.php';
		$fd = @fopen($cfgfile, 'w');
		if (!$fd)
		{
			throw new FbX($ccms['lang']['system']['error_openfile'] . ": " . $cfgfile);
		}
		else
		{
			$out = fwrite($fd, $fileContents);
			if (!$out)
			{
				throw new FbX($ccms['lang']['system']['error_write'] . ": " . $cfgfile);
			}
			fclose($fd);
		}

		if ($sqldump !== false)
		{
			$sqldumpfile = BASE_PATH . '/media/files/ccms-restore/compactcms-sqldump.sql';
			$fd = @fopen($sqldumpfile, 'w');
			if (!$fd)
			{
				throw new FbX($ccms['lang']['system']['error_openfile'] . ": " . $sqldumpfile);
			}
			else
			{
				$out = fwrite($fd, $sqldump);
				if (!$out)
				{
					throw new FbX($ccms['lang']['system']['error_write'] . ": " . $sqldumpfile);
				}
				fclose($fd);
			}
		}
		// else: error has already been registered before, no sweat, mate!

		//send_response_status_header(307); 
		$msg = $ccms['lang']['backend']['newfilecreated'] . ', <a href="../../../../media/files/' . $backupName . '">' . strtolower($ccms['lang']['backup']['download']).'</a>.';
		//header('Location: ' . makeAbsoluteURI('./backup-restore.Manage.php?status=notice&msg='.rawurlencode($msg)));
		echo '<p>' . $msg . '</p>';
		exit();
		//	throw new FbX($ccms['lang']['auth']['featnotallowed']);
	}
	catch (CcmsAjaxFbException $e)
	{
		if ($fd)
		{
			fclose($fd);
		}
		$e->croak();
	}
}






// when we get here, an illegal command was fed to us!
die_with_forged_failure_msg(__FILE__, __LINE__);

?>