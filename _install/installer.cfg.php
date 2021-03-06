<?php
/**
 * Copyright (C) 2008 - 2010 by Xander Groesbeek (CompactCMS.nl)
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
if(!defined("COMPACTCMS_CODE")) { die('Illegal entry point!'); } /*MARKER*/

// Define default location
if (!defined('BASE_PATH')) die('BASE_PATH not defined!');



/*
 development tweakables:
 
 - whether or not to actually RUN those queries we generate
 
 - whether or not we'd want to see those queries dumped to screen or not (no matter if they've been executed or not)
 
 To tweak, edit the true/false values in the define() expressions below.
 */
define('WRITE_CFG_FILES_TO_DISK', true);
define('EXECUTE_QUERIES', true);
define('DUMP_QUERIES_N_STUFF_IN_DEVMODE', false || !EXECUTE_QUERIES);


?>