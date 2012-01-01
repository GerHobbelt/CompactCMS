<?php
/* ************************************************************
Copyright (C) 2008 - 2010 by Xander Groesbeek (CompactCMS.nl)
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
if(!defined("COMPACTCMS_CODE")) { die('Illegal entry point!'); } /*MARKER*/


/*
  MySQL database backup class, version 1.0.0
  Written by Rochak Chauhan
  Released under GNU Public license
*/

class createZip
{
	protected $compressedData = array();
	protected $centralDirectory = array(); // central directory
	protected $endOfCentralDirectory = "\x50\x4b\x05\x06\x00\x00\x00\x00"; //end of Central directory record
	protected $oldOffset = 0;

	protected $fd = null;
	protected $flushed_data_len = 0;
	protected $last_flush_len = 0;		// prevent (failing) flush attempts for every little file added once the threshold has been surpassed
	protected $flushSizeThreshold = 1048576;  // flush data every 1Mbyte into the ZIP file
	
	public function __construct($file_handle)
	{
		if (!empty($file_handle))
		{
			$this->fd = $file_handle;
		}
	}
	
	/**
	 * Function to create the directory where the file(s) will be unzipped
	 *
	 * @param $directoryName string
	 *
	 */

	public function addDirectory($directoryName) 
	{
		$directoryName = str_replace("\\", "/", $directoryName);

		$feedArrayRow = "\x50\x4b\x03\x04";
		$feedArrayRow .= "\x0a\x00";
		$feedArrayRow .= "\x00\x00";
		$feedArrayRow .= "\x00\x00";
		$feedArrayRow .= "\x00\x00\x00\x00";

		$feedArrayRow .= pack("V",0);
		$feedArrayRow .= pack("V",0);
		$feedArrayRow .= pack("V",0);
		$feedArrayRow .= pack("v", strlen($directoryName) );
		$feedArrayRow .= pack("v", 0 );
		$feedArrayRow .= $directoryName;

		$feedArrayRow .= pack("V",0);
		$feedArrayRow .= pack("V",0);
		$feedArrayRow .= pack("V",0);

		$this->compressedData[] = $feedArrayRow;

		$newOffset = strlen(implode("", $this->compressedData)) + $this->flushed_data_len;

		$addCentralRecord = "\x50\x4b\x01\x02";
		$addCentralRecord .="\x00\x00";
		$addCentralRecord .="\x0a\x00";
		$addCentralRecord .="\x00\x00";
		$addCentralRecord .="\x00\x00";
		$addCentralRecord .="\x00\x00\x00\x00";
		$addCentralRecord .= pack("V",0);
		$addCentralRecord .= pack("V",0);
		$addCentralRecord .= pack("V",0);
		$addCentralRecord .= pack("v", strlen($directoryName) );
		$addCentralRecord .= pack("v", 0 );
		$addCentralRecord .= pack("v", 0 );
		$addCentralRecord .= pack("v", 0 );
		$addCentralRecord .= pack("v", 0 );
		$ext = "\x00\x00\x10\x00";
		$ext = "\xff\xff\xff\xff";
		$addCentralRecord .= pack("V", 16 );

		$addCentralRecord .= pack("V", $this->oldOffset );
		$this->oldOffset = $newOffset;

		$addCentralRecord .= $directoryName;

		$this->centralDirectory[] = $addCentralRecord;
	}

	/**
	 * Function to add file(s) to the specified directory in the archive
	 *
	 * @param $directoryName string
	 *
	 */
	public function addFile($data, $directoryName)
	{
		$directoryName = str_replace("\\", "/", $directoryName);

		$feedArrayRow = "\x50\x4b\x03\x04";
		$feedArrayRow .= "\x14\x00";
		$feedArrayRow .= "\x00\x00";
		$feedArrayRow .= "\x08\x00";
		$feedArrayRow .= "\x00\x00\x00\x00";

		$uncompressedLength = strlen($data);
		$compression = crc32($data);
		$gzCompressedData = gzcompress($data);
		$gzCompressedData = substr( substr($gzCompressedData, 0, strlen($gzCompressedData) - 4), 2);
		$compressedLength = strlen($gzCompressedData);
		$feedArrayRow .= pack("V",$compression);
		$feedArrayRow .= pack("V",$compressedLength);
		$feedArrayRow .= pack("V",$uncompressedLength);
		$feedArrayRow .= pack("v", strlen($directoryName) );
		$feedArrayRow .= pack("v", 0 );
		$feedArrayRow .= $directoryName;

		$feedArrayRow .= $gzCompressedData;

		$feedArrayRow .= pack("V",$compression);
		$feedArrayRow .= pack("V",$compressedLength);
		$feedArrayRow .= pack("V",$uncompressedLength);

		$this->compressedData[] = $feedArrayRow;

		$newOffset = strlen(implode("", $this->compressedData)) + $this->flushed_data_len;

		$addCentralRecord = "\x50\x4b\x01\x02";
		$addCentralRecord .="\x00\x00";
		$addCentralRecord .="\x14\x00";
		$addCentralRecord .="\x00\x00";
		$addCentralRecord .="\x08\x00";
		$addCentralRecord .="\x00\x00\x00\x00";
		$addCentralRecord .= pack("V",$compression);
		$addCentralRecord .= pack("V",$compressedLength);
		$addCentralRecord .= pack("V",$uncompressedLength);
		$addCentralRecord .= pack("v", strlen($directoryName) );
		$addCentralRecord .= pack("v", 0 );
		$addCentralRecord .= pack("v", 0 );
		$addCentralRecord .= pack("v", 0 );
		$addCentralRecord .= pack("v", 0 );
		$addCentralRecord .= pack("V", 32 );

		$addCentralRecord .= pack("V", $this->oldOffset );
		$this->oldOffset = $newOffset;

		$addCentralRecord .= $directoryName;

		$this->centralDirectory[] = $addCentralRecord;
		
		if ($newOffset >= $this->flushSizeThreshold + $this->last_flush_len)
		{
			$this->last_flush_len = $newOffset;
			return $this->flushZippedData();
		}	
		return $compressedLength;
	}

	/**
	 * Function to return the zip file
	 *
	 * @return zipfile (archive)
	 */
	public function getZippedfile() 
	{
		$data = implode("", $this->compressedData);
		$controlDirectory = implode("", $this->centralDirectory);

		return
			$data.
			$controlDirectory.
			$this->endOfCentralDirectory.
			pack("v", sizeof($this->centralDirectory)).
			pack("v", sizeof($this->centralDirectory)).
			pack("V", strlen($controlDirectory)).
			pack("V", strlen($data) + $this->flushed_data_len).
			"\x00\x00";
	}

	/**
	 * Flush ZIP data in memory to file/disk as much as possible.
	 */
	public function flushZippedData()
	{
		$data = implode("", $this->compressedData);
		if (!empty($this->fd))
		{
			$out = fwrite($this->fd, $data);
			if ($out)
			{
				$this->flushed_data_len += strlen($data);
				$this->compressedData = array();
			}
			return $out;
		}
		return false;
	}
	
	/**
	 * Save the constructed ZIP file to disk.
	 */
	public function saveZipFile()
	{
		$data = $this->getZippedfile();
		if (!empty($this->fd))
		{
			$out = fwrite($this->fd, $data);
			if ($out)
			{
				$this->flushed_data_len = 0;
				$this->compressedData = array();
				$this->centralDirectory = array();
			}
			return $out;
		}
		return false;
	}
}







function directoryToArray($directory, $recursive, $regex_to_match = null)
{
	$array_items = array();
	if ($handle = opendir($directory))
	{
		if (substr($directory, -1, 1) != '/')
		{
			$directory .= '/';
		}
		while (false !== ($file = readdir($handle)))
		{
			if ($file != "." && $file != "..")
			{
				$path = $directory . $file;
				if (is_dir($path))
				{
					if($recursive)
					{
						$subarr = directoryToArray($path, $recursive, $regex_to_match);
						// do not include empty subdirectories
						if (count($subarr) > 0)
						{
							$array_items = array_merge($array_items, array($path), $subarr);
						}
					}
				}
				else
				{
					if (empty($regex_to_match) || preg_match($regex_to_match, $path))
					{
						$array_items[] = $path;
					}
				}
			}
		}
		closedir($handle);
	}
	return $array_items;
}

?>