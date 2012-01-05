<?php
 /**
 * Copyright (C) 2010 by Ger Hobbelt (hebbut.net)
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
 * > Contact: google and you got me.
**/


/**
 * A class which is used to manage global permissions (i.e. the permissions
 * stored in the XXX_cfgpermissions database table).
 */
class CcmsGlobalPermissions
{
	protected $permissions = null;
	protected $changed = null;

	public function __construct($db = null, $table_prefix = '')
	{
		if (!empty($db))
		{
			$this->LoadPermissions($db, $table_prefix);
		}
	}

	// Load permissions
	private function LoadPermissions($db, $table_prefix)
	{
		$recs = $db->SelectArray($table_prefix . 'cfgpermissions');
		if (empty($recs)) throw new Exception(__CLASS__ . ": INTERNAL ERROR: 1 permission record MUST exist!\n" . $db->MyDyingMessage(), 666, $this);

		$this->permissions = array();
		foreach($recs as $rec)
		{
			// one record: key + value
			$this->permissions[$rec['name']] = intval($rec['value']);
		}
		$this->changed = null;
	}

	// Save permissions
	public function SavePermissions($db, $table_prefix, $exception_on_error = true)
	{
		if (is_array($this->changed))
		{
			foreach($this->changed as $key => $value)
			{
				if (!$value || empty($key)) throw new Exception(__CLASS__ . ": INTERNAL ERROR: " . $ccms['lang']['system']['error_forged'] . ' (' . __FILE__ . ', ' . __LINE__ . ')' );

				$values = array();
				$values['value'] = MySQL::SQLValue($this->get($key), MySQL::SQLVALUE_NUMBER);
				$criteria = array();
				$criteria['name'] = MySQL::SQLValue($key, MySQL::SQLVALUE_TEXT);

				if(!$db->UpdateRow($table_prefix . 'cfgpermissions', $values, $criteria))
				{
					if ($exception_on_error)
					{
						throw new Exception(__CLASS__ . ": INTERNAL ERROR: " . $db->MyDyingMessage());
					}
					else
					{
						return false;
					}
				}
			}
		}
		return true;
	}

	public function get($perm_name)
	{
		if (array_key_exists($perm_name, $this->permissions))
		{
			return $this->permissions[$perm_name];
		}
		return false;
	}

	public function set($perm_name, $new_value)
	{
		$new_value = intval($new_value);

		// only change the permission when it's a real change!
		if (!array_key_exists($perm_name, $this->permissions) || $this->permissions[$perm_name] != $new_value)
		{
			$this->permissions[$perm_name] = $new_value;

			if (!is_array($this->changed))
			{
				$this->changed = array();
			}
			$this->changed[$perm_name] = true;
		}
	}

	/**
	 * Return TRUE when the given user level is cleared for this operation, FALSE otherwise.
	 *
	 * Assumes a permission level setting range of 0=disabled, 1=any authenticated user ... 4=admin only
	 *
	 * Rules which are checked:
	 *
	 * - (when TRUE) admins are allowed full access, UNLESS the permission state is 'DISABLED' (default: N.A. ~ FALSE)
	 *
	 * - (when TRUE) allow when this is the owner we're talking about (default: N.A. ~ FALSE)
	 *
	 * - allow when the user level is equal or above the required user level
	 */
	public function is_level_okay($perm_name, $user_level = 1, $is_owner = false, $always_allow_admins = false)
	{
		$user_level = intval($user_level);
		if ($is_owner || ($always_allow_admins && $user_level >= 4))
			return true;

		$reqd_level = $this->get($perm_name);

		return ($reqd_level > 0 && $user_level >= $reqd_level);
	}
}


?>