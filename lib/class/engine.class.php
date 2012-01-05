<?php

/* make sure no-one can run anything here if they didn't arrive through 'proper channels' */
if(!defined("COMPACTCMS_CODE")) { die('Illegal entry point!'); } /*MARKER*/

/* ************************************************************
Copyright (C) 2008 - 2010 by Xander Groesbeek (CompactCMS.nl)
Revision:    CompactCMS - v 1.4.2

This external class is part of CompactCMS

Copyright (C) 2005 - 2009 for STP Stefan Reich / Tobi Schulz
Project: http://www.script.gr/sc/scripts/STP/

Simple Template Parser is free software; you can redistribute it
and/or modify it under the terms of the GNU General Public License
as published by the Free Software Foundation; either version 2 of
the License, or (at your option) any later version.

STP is distributed in the hope that it will be useful, but
WITHOUT ANY WARRANTY; without even the implied warranty of
MERCHANTABILITY or FITNESS FOR A PARTICULAR PURPOSE. See the GNU
General Public License for more details.

You should have received a copy of the GNU General Public License
along with CompactCMS. If not, see <http://www.gnu.org/licenses/>.
************************************************************ */

/*MARKER*/require_once(BASE_PATH . '/lib/includes/htmLawed/htmLawed.php');

class ccmsParser
{
	// Do NOT call variables from the outside ! (Use the public methods instead.)
	protected $params = array();
	protected $blocked_params = array();
	protected $paramObject = null;
	protected $template = null;
	protected $output = null;
	protected $includePath = '';
	protected $nestingLevel = 0;

	//########################################################
	//################## Internal Functions ##################
	//########################################################

	/**
	 * Strip leading and trailing whitespace from the given $expr string.
	 * Then remove any outer quotes (when such delimiters may be expected: $may_be_quoted==true)
	 * or '|' or '(...)' delimiters.
	 *
	 * Return the resulting string.
	 */
	protected function trim_expression($expr, $may_be_quoted = false)
	{
		$expr = trim($expr);
		$cs = substr($expr, 0, 1);
		$ce = substr($expr, -1, 1);
		if ($cs == $ce && strpos(($may_be_quoted ? '|\'"' : '|'), $cs) !== false)
		{
			return substr($expr, 1, strlen($expr) - 2);
		}
		else if ($cs == '(' && $ce == ')')
		{
			return substr($expr, 1, strlen($expr) - 2);
		}
		return $expr;
	}

	/**
	 * Function to check for conditions in IF Tags
	 *
	 * Possible conditions:
	 *
	 *   gt = greater (Numbers only)
	 *   ge = greater or equal (Numbers only)
	 *   lt = lower (Numbers only)
	 *   le = lower or equal (Numbers only)
	 *   eq = equal (Number or string)
	 *   ne = not equal (Number or string)
	 *   lk = exists in string (String only, functions like the
	 *        SQL LIKE '%string%'
	 *
	 * $value = value to check condition against
	 */
	protected function checkCondition($value, $condition)
	{
		if (preg_match("/^(\S+)\s+(\S.*)$/", $condition, $cond))
		{
			$chh = $cond[1];
			$wert = $this->trim_expression($cond[2], true);
			switch ($chh)
			{
			case 'gt': return ($value >  $wert);
			case 'ge': return ($value >= $wert);
			case 'lt': return ($value <  $wert);
			case 'le': return ($value <= $wert);
			case 'eq': return ($value == $wert);
			case 'ne': return ($value != $wert);
			case 'lk': return preg_match($wert, $value);
			default:
				throw new Exception('ERROR: incorrect condition syntax: unknown comparator in "' . htmlspecialchars(substr($condition, 0, 250), ENT_QUOTES, 'UTF-8') . '"');
			}
		}
		else
		{
			throw new Exception('ERROR: incorrect condition syntax: cannot parse "' . htmlspecialchars(substr($condition, 0, 250), ENT_QUOTES, 'UTF-8') . '"');
		}
	}

	/**
	 * colorSet(string $colorstring)
	 *
	 * Enables changing Colors f.e. in table rows
	 * initiating an array with the color values
	 * as given by the template in form
	 * {%ATTR COLOR1,COLOR2,COLOR3...%}
	 */
	protected function colorSet($colorstring)
	{
		$this->colors = explode(',', $colorstring);
		$this->colorindex = 0;
	}

	/**
	 * prints out the current value of array    $this->colors
	 * Steps to next index or 0 if end is reached
	 */
	protected function colorChange()
	{
		$currentColor = $this->colors[$this->colorindex];
		$this->colorindex = ($this->colorindex == (count($this->colors) - 1)) ? 0 : $this->colorindex + 1;
		return $currentColor;
	}

	/**
	 * Splits the template $str into parts, returns the resulting array of elements (chunks).
	 * Afterwards each element contains either
	 * - Only text / content (no template parser tags or commands in here)
	 * - exactly one parser tag
	 */
	protected function splitTemplate($str)
	{
		$sicherheitscounter = 0;
		$template = explode('{%', $str);
		$to = count($template);
		//echo "<p>len = $to</p>\n";

		// only process those entries which started with the '{%' marker:
		for($i = 1; $i < $to; $i++)
		{
			//echo "<p>len = $to / i = $i</p>\n";
			$s = htmlspecialchars(substr($template[$i], 0, 250), ENT_QUOTES, 'UTF-8');
			//echo "<p>chunk = '$s'</p>\n";
			$p = explode('%}', $template[$i]);
			$len = count($p);
			if ($len != 2)
			{
				if (empty($p[0]))
				{
					// we accept 'unmatched' %} in the second part -- when the first part is an empty element!
					$p = explode('%}', $template[$i], 2);
				}
				else
				{
					throw new Exception('WARNING: unterminated tag: "' . htmlspecialchars(substr($template[$i], 0, 250), ENT_QUOTES, 'UTF-8') . '"');
				}
			}

			// DELAY execution of %INCLUDE and %ATTR operations: they may not be needed when they're inside a IF/ELSE branch when it is skipped!
			$p[0] = '{%' . $p[0] . '%}';

			array_splice($template, $i, 1, $p);
			$to++;
			$i++; // skip second part as that does not carry any (yet unexpanded/exploded) vars anyway!
		}

		return $template;
	}

	/**
	 * loads an Include file and returns its contents
	 */
	protected function loadInclude($name)
	{
		if (substr($name, 0, 1) != '/')
		{
			$name = $this->includePath . $name;
		}
		return file_get_contents($name);
	}

	/**
	 * calls a variable or constant
	 *
	 * supports nested references to, for example, fetch values from a multidimensional variable array,
	 * by delimiting the subsequent indices with a ':' colon like
	 *
	 * {%lang:backend:gethelp%}
	 */
	protected function getvar(&$vars, $var)
	{
		if ($var == "ATTR")
			return $this->colorChange();
		elseif (array_key_exists($var, $vars))
			return $vars[$var];
		elseif ($this->paramObject)
			return $this->paramObject->getVar($var);
		elseif (preg_match('/^G_/', $var) && defined($var))
			return constant($var);
		else
		{
			$v = explode(':', $var, 2);
			if (is_array($v) && count($v) == 2 && array_key_exists($v[0], $vars))
			{
				return $this->getvar($vars[$v[0]], $v[1]);
			}
			/* else: postprocessing required --> '!'-bang postfixed variable reference? */
			$v = explode('!', $var, 2);
			if (is_array($v) && count($v) == 2 && array_key_exists($v[0], $vars))
			{
				$rv = $this->getvar($vars, $v[0]);
				/*
				Note: we extract the argument list as a simple, single regex since this
					  regex only returns the last argument matching the (....)* section of
					  that regex:

				*/
				$pm = preg_match('/^(\w+)(\s*\(\s*(.*)\s*\))?$/s', trim($v[1]), $fbits);
				if (!$pm)
				{
					throw new Exception('regex match failure for "' . $v[1] . '"');
				}
				if (count($fbits) > 3)
				{
					$argset = explode(',', $fbits[3]);
					// ditch the full arguments match itself
					array_pop($fbits);
					// and replace it with each of the arguments individually
					$quote_pending = false;
					$quoted_str = null;
					foreach ($argset as $v)
					{
						$vt = trim($v);
						$c = substr($vt, 0, 1);
						switch ($quote_pending ? $quote_pending . $quote_pending : $c)
						{
						case "'":
						case '"':
							// starting quote: remove quotes around text
							if (substr($vt, -1) == $c)
							{
								$v = substr($vt, 1, strlen($vt) - 2);
							}
							else
							{
								/*
								When not terminated, then we exploded a comma inside a delimited string: merge back together again.

								Of course, we don't know anymore whether it was one or two comma's, but we'll assume ',,' is not
								gonna happen in any parameter.
								*/
								$quote_pending = $c;
								$quoted_str = substr($v, strpos($v, $c));
								continue;
							}
							break;

						case "''":
						case '""':
							// a string, which had one comma in it at least:
							if (substr($vt, -1) == $c)
							{
								// we reached the end!
								$quoted_str .= ',' . substr($v, 0, strrpos($v, $c));
								$v = $quoted_str;
								$quoted_str = null;
							}
							else
							{
								// a piece in the middle; multiple comma's in here apparently
								$quoted_str .= ',' . $v;
								continue;
							}
							break;

						default:
							// maybe unquoted string?
							$v = $this->trim_expression($v, true);
							break;
						}
						$fbits[] = $v;
					}
					// now arguments start at index [3] ...
				}

				switch ($fbits[1])
				{
				default:
				case 'quoteprotect':
					/* data must have its quotes transformed to HTML entities! */
					//echo "<p>var = $var</p>\n";
					return htmlspecialchars($rv, ENT_QUOTES, 'UTF-8');

				case 'protect4attr':
					/* make data suitable for an attribute value: strip tags and encode quotes! */
					$config = array(
								'safe' => 1,
								'elements' => '-*',
								'keep_bad' => 6
								);
					$rv = htmLawed($rv, $config);
					$rv = trim(preg_replace('/\s+/', ' ', $rv));
					if (count($fbits) > 3)
					{
						// reduce the data string to a maximum length; clip at the last whitespace when this is required.
						$maxlen = intval($fbits[3]);
						$clip_on_ws = (count($fbits) > 4 ? $fbits[4] : false);
						if ($maxlen > 0)
						{
							if ($clip_on_ws == 'true' || $clip_on_ws == 1)
							{
								$rv = substr($rv, 0, $maxlen + 1);
								/*
								 * since all whitespace has been transformed to regular spaces, we can simply look for
								 * the last space in there.
								 *
								 * NOTE: we previously clipped to MAXLEN PLUS ONE so this subsequent check for last
								 *       whitespace position can still produce the MAXLEN position when a space is
								 *       positioned there! This prevent undue length reduction of the result.
								 */
								$lwspos = strrpos($rv, ' ');
								if ($lwspos > 0)
								{
									$rv = substr($rv, 0, $lwspos);
								}
							}
							else
							{
								$rv = substr($rv, 0, $maxlen);
							}
						}
						// else: do not clip
					}
					return htmlspecialchars($rv, ENT_QUOTES, 'UTF-8');

				case 'css_files':
					/*
					  produce a series of (possibly merged!) CSS file loading <link ...> statements: extract optional
					  media type, etc. from the entries themselves.
					 */
					$merge = false;
					if (count($fbits) > 3)
					{
						$merge = ($fbits[3] == 'combine');
					}

					asort($rv); // sort on /value/: lowest number has priority

					$combined_basepath = null;
					$combined_path = null;
					$css_files = array();

					foreach($rv as $cssfkey => $prio)
					{
						// to merge, all URLs must have the same start AND no URL should have a query section!
						if ($merge && !empty($combined_basepath) && 0 === strpos($cssfkey, $combined_basepath) && false === strpos($cssfkey, '?') && false === strpos($cssfkey, '>'))
						{
							$combined_path .= ',' . substr($cssfkey, strlen($combined_basepath));
							continue;
						}
						// when we get here, the previous combo is done: dump it:
						if (!empty($combined_path))
						{
							$f = explode('>', $combined_path);
							if (!isset($f[1])) $f[1] = '';
							$css_files[] = '<link rel="stylesheet" type="text/css" href="' . rtrim($f[0]) . '" charset="utf-8" ' . $f[1] . '/>';
						}

						// now determine new basepath, etc.:
						$combined_basepath = null;

						$pos = strrpos($cssfkey, '/');
						if ($pos !== false && false === strpos($cssfkey, '?'))
						{
							$combined_basepath = substr($cssfkey, 0, $pos + 1);
						}
						$combined_path = $cssfkey;
					}

					if (!empty($combined_path))
					{
						$f = explode('>', $combined_path);
						if (!isset($f[1])) $f[1] = '';
						$css_files[] = '<link rel="stylesheet" type="text/css" href="' . rtrim($f[0]) . '" charset="utf-8" ' . $f[1] . '/>';
					}

					return implode("\n", $css_files);

				case 'js_files':
					/*
					  produce a series of (possibly merged!) JS file lazy-loading JS array entries.
					 */
					$merge = false;
					if (count($fbits) > 3)
					{
						$merge = ($fbits[3] == 'combine');
					}

					asort($rv); // sort on /value/: lowest number has priority

					$combined_basepath = null;
					$combined_path = null;
					$js_files = array();

					foreach($rv as $cssfkey => $prio)
					{
						if ($merge && !empty($combined_basepath) && 0 === strpos($cssfkey, $combined_basepath))
						{
							$combined_path .= ',' . substr($cssfkey, strlen($combined_basepath));
							continue;
						}
						// when we get here, the previous combo is done: dump it:
						if (!empty($combined_path))
						{
							$js_files[] = '"' . $combined_path . '"';
						}

						// now determine new basepath, etc.:
						$combined_basepath = null;

						$pos = strrpos($cssfkey, '/');
						if ($pos !== false)
						{
							$combined_basepath = substr($cssfkey, 0, $pos + 1);
						}
						$combined_path = $cssfkey;
					}

					if (!empty($combined_path))
					{
						$js_files[] = '"' . $combined_path . '"';
					}

					return implode(",\n", $js_files);

				case 'implode':
					/*
					 * implode an array of text chunks, using the specified interjection string, just like PHP implode() itself.
					 *
					 * Parameters:
					 *
					 *   merge string: placed between two entries in the array
					 *
					 *   leadin string: placed before the first entry in the array
					 *
					 *   leadout string: placed after the last entry in the array
					 */
					$merge = "\n\n";
					if (count($fbits) > 3)
					{
						$merge = $fbits[3];
					}
					$leadin = '';
					if (count($fbits) > 4)
					{
						$leadin = $fbits[4];
					}
					$leadout = '';
					if (count($fbits) > 5)
					{
						$leadout = $fbits[5];
					}

					return $leadin . implode($merge, $rv) . $leadout;
				}
			}
			return '';
		}
	}

	protected function findEndOfIF($j, $to, $var, $tag)
	{
		$nest = 1;
		while ($j < $to)
		{
			if (preg_match("|^{%IF |", $this->template[$j]))
			{
				++$nest;
				//echo "nest+ $nest: ".$this->template[$j]."<br>";
			}
			elseif (preg_match("|^{%/IF[\s%]|", $this->template[$j]))
			{
				--$nest;
				//echo "nest- $nest: ".$this->template[$j]."<br>";
				if ($nest == 0)
					break;
			}
			++$j;
		}
		//while ($j < $to && !preg_match("|{%/IF !?$var%}|", $this->template[$j])) ++$j;

		if ($j >= $to)
		{
			throw new Exception('WARNING: "' . htmlspecialchars(substr($tag, 0, 250), ENT_QUOTES, 'UTF-8') . '" not closed');
		}

		return $j;
	}

	protected function findEndOfFOR($j, $to, $var, $tag)
	{
		$nest = 1;
		while ($j < $to)
		{
			if (preg_match("|^{%FOR |", $this->template[$j]))
			{
				++$nest;
				//echo "nest+ $nest: ".$this->template[$j]."<br>";
			}
			elseif (preg_match("|^{%/FOR[ %]|", $this->template[$j]))
			{
				--$nest;
				//echo "nest- $nest: ".$this->template[$j]."<br>";
				if ($nest == 0)
					break;
			}
			++$j;
		}

		if ($j >= $to)
		{
			throw new Exception('WARNING: "' . htmlspecialchars(substr($tag, 0, 250), ENT_QUOTES, 'UTF-8') . '" not closed');
		}

		return $j;
	}

	/**
	 * works its way through the entries $this->template[$from] until $this->template[$to-1]
	 * using the parameters $vars and appends the result to $this->output
	 * $enable: Output mode: <=0 : disabled; >0 : active; <0 : disabled, to be enabled with ELSE
	 */
	protected function process($from, $to, $vars, $enable = 1)
	{
		$lvl = $this->nestingLevel;
		//echo " <h3>process: $from .. $to (enable = $enable) @ $lvl</h3>\n ";

		$this->nestingLevel++;
		if ($this->nestingLevel > 16)
		{
			throw new Exception("INTERNAL ERROR: maximum recursive invocation level reached! ($from)..($to)");
		}

		for ($i = $from; $i < $to; $i++)
		{
			$p = $this->template[$i];
			$len = strlen($p);
			//echo '<p>['.$i.'] (' . $len . '): <code>' . htmlspecialchars(substr($p, 0, 250), ENT_COMPAT, 'UTF-8') . "</code></p>\n";
			if ($enable > 0 && preg_match('/^{%FOR (.*)%}$/', $p, $matches))
			{
				// find ends of FOR tags
				$var = $matches[1];
				$value = $this->getvar($vars, $var);
				++$i;
				$j = $this->findEndOfFOR($i, $to, $var, $p);

				// call process() recursively for each line
				if (is_array($value))
				{
					foreach ($value as $row)
					{
						if (!is_array($row))
						{
							$row = array('ROW' => $row);
						}

						$new_j = $this->process($i, $j, $row + $vars, 1);
						$to += $new_j - $j;
						$j = $new_j;
					}
				}

				// Proceed after closing tag
				$i = $j;
			}
			elseif ($enable > 0 && preg_match('/^{%IF\s+(!?)(.*)?%}$/', $p, $matches))
			{
				// Split tag
				$neg = $matches[1];
				$var = trim($matches[2]);
				$cond = null;
				if (preg_match('/^(\S+)\s+(\S.*)$/', $var, $matches))
				{
					$var = $matches[1];
					$cond = $this->trim_expression($matches[2]);
				}
				$value = $this->getvar($vars, $var);
				if ($cond) $value = $this->checkCondition($value, $cond);
				if ($neg) $value = !$value;

				++$i;
				$j = $this->findEndOfIF($i, $to, $var, $p);

				// call process() recursively if variable is set
				$new_j = $this->process($i, $j, $vars, $value ? 1 : -1);
				$to += $new_j - $j;
				$j = $new_j;

				// Proceed after closing tag
				$i = $j;
			}
			elseif ($p == "{%ELSE%}")
			{
				$enable = -$enable;
			}
			elseif ($enable > 0 && preg_match('/^{%INCLUDE\s+(\S.*)%}$/', $p, $matches))
			{
				$path = $this->trim_expression($matches[1]);
				$incstr = $this->loadInclude($path);
				$a = $this->splitTemplate($incstr);
				array_splice($this->template, $i, 1, $a);
				$len = count($a);
				$to += $len - 1; // one entry removed; replaced by count($a) new ones
				$j = $i + $len;
				$new_j = $this->process($i, $j, $vars, $enable);
				$to += $new_j - $j;
				$j = $new_j;

				// Proceed with the next chunk
				$i = $j - 1;
			}
			elseif ($enable > 0 && preg_match('/^{%ATTR\s+(\S.*)%}$/', $p, $matches))
			{
				$this->colorSet($this->trim_expression($matches[1]));
			}
			elseif ($enable > 0 && preg_match('/^{%(.*)%}$/', $p, $matches))
			{
				/*
				 * texts can be processed recursively.
				 *
				 * This creates a security issue when fields are self-referential, which
				 * happens, for example, when a malicious commenter would include
				 * '{%comment%}' as part of his comment -- IFF the comments weren't
				 * loaded through AJAX/JS, bypassing this engine!
				 *
				 * The point?
				 *
				 * Watch out for recursive {%tag%} self-references: they will make the
				 * engine run until it hits the edge.
				 *
				 * print variable value by replacing the tag with its value (split up
				 * in chunks), then rewinding the index by -1, so this template entry
				 * will be hit another time, now with altered content
				 *
				 * The way we resolve this 'recursive substitution' issue here is
				 * by temporarily replacing the variable being replaced with another
				 * value, restoring the original value when we've processed all content
				 * which was produced by the variable substitution process.
				 */
				if (!empty($matches[1]))
				{
					if (array_key_exists($matches[1], $this->blocked_params))
					{
						// we're recursively expanding this bugger: DON'T --> keep as a literal value.

						// Regular text
						$this->append($p);
					}
					else
					{
						$this->blocked_params[$matches[1]] = true;

						$a = $this->splitTemplate(strval($this->getvar($vars, $matches[1])));
						array_splice($this->template, $i, 1, $a);
						$len = count($a);
						$to += $len - 1; // one entry removed; replaced by count($a) new ones
						$j = $i + $len;
						$new_j = $this->process($i, $j, $vars, $enable);
						$to += $new_j - $j;
						$j = $new_j;

						unset($this->blocked_params[$matches[1]]);

						// Proceed with the next chunk
						$i = $j - 1;
					}
				}
				else
				{
					// empty {%%}: a special purpose 'escape' used to produce the 'magic' string itself: '{%'
					$this->append('{%');
				}
			}
			else if ($enable > 0)
			{
				// Regular text
				$this->append($p);
			}
		}

		$this->nestingLevel--;
		// return (possibly adjusted) end index so caller can adjust theirs.
		return $to;
	}

	/**
	 * Prints PHP code to the output page
	 */
	protected function CheckPHP($text)
	{
		// see also:
		//     http://nl3.php.net/manual/en/function.eval.php (comments)
		$short_tag_mode = (ini_get('short_open_tag') > 0);

		$trackerr_old = ini_set('track_errors', true);
		$php_errormsg = '';
		
		$template_engine = $this; // may be accessed within the eval() code
		if ($short_tag_mode)
		{
			$rv = @eval(' ?>'.$text.'<? ');
		}
		else
		{
			$rv = @eval(' ?>'.$text.'<?php ');
		}
		
		if ($rv === false)
		{
			die("<pre>Error in CheckPHP: '$php_errormsg' for PHP code:\n" . htmlentities($text, ENT_NOQUOTES) . "\n");
		}			
		ini_set('track_errors', $trackerr_old);
		
		return $rv;
	}

	protected function append($text)
	{
		if (is_array($this->output))
		{
			$this->output[] = $text;
		}
		else
		{
			echo $text;
		}
	}

	//########################################################
	//################### PUBLIC FUNCTIONS ###################
	//########################################################

	/**
	 * constructor
	 */
	public function __construct()
	{
	}

	/**
	 * Returns all set parameters
	 */
	public function getParams()
	{
		return $this->params;
	}

	/**
	 * Returns ONE set parameter
	 */
	public function getParam($name)
	{
		return $this->params[$name];
	}

	/**
	 * Sets one parameter
	 */
	public function setParam($name, $value)
	{
		$this->params[$name] = $value;
	}

	/**
	 * sets several parameters at once
	 *
	 * accepts an array or an object that supports the method getVar($name)
	 */
	public function setParams(&$params, $no_overwrite = false)
	{
		if (is_array($params))
		{
			if ($no_overwrite)
			{
				$this->params = array_merge($params, $this->params);
			}
			else
			{
				// when entries already exist, the new will overwrite those:
				$this->params = array_merge($this->params, $params);
			}
			return true;
		}
		elseif (is_object($params) && method_exists($params, 'getVar'))
		{
			$this->paramObject = $params;
			return true;
		}
		return false;
	}

	/**
	 * Deletes all parameters (no argument)
	 * or a list of parameters from an array
	 * (the parameters can be keys or values)
	 */
	public function clearParams($array = 'all')
	{
		if ($array == 'all')
		{
			$this->params = array();
			$this->paramObject = null;
		}
		else
		{
			foreach ($array as $k => $v)
			{
				unset($this->params[$k]);
				unset($this->params[$v]);
			}
		}
	}

	/**
	 * Deletes one parameter
	 */
	public function clearParam($name)
	{
		unset($this->params[$name]);
	}

	/**
	 * Assembles a template from a frame document and fragments
	 */
	public function assemble($frame, $frags, $leadin = '', $leadout = '')
	{
		if (!is_file($frame))
		{
			throw new Exception('Template : Frame Document not found: "' . htmlspecialchars(substr($frame, 0, 250), ENT_QUOTES, 'UTF-8') . '"');
		}
		$idx = strrpos($frame, '/');
		if (empty($this->includePath) && $idx !== false)
		{
			$this->includePath = substr($frame, 0, $idx + 1);
		}

		$tmpl = file_get_contents($frame);

		if (is_array($frags))
		{
			// allow nested expansion of fragments:
			for ($rounds = 16; $rounds > 0; $rounds--)
			{
				$subst_done = false;

				foreach ($frags as $fragname => $fragpath)
				{
					$cmd = "|<!--INSERT_$fragname-->|";
					if (preg_match($cmd, $tmpl))
					{
						if (substr($fragpath, 0, 1) != '/')
						{
							$fragpath = $this->includePath . $fragpath;
						}

						if (!is_file($fragpath))
						{
							throw new Exception('Template : Fragment Document not found: "' . htmlspecialchars(substr($fragpath, 0, 250), ENT_QUOTES, 'UTF-8') . '"');
						}
						$tmpl = preg_replace($cmd, file_get_contents($fragpath), $tmpl);
						$subst_done = true;
					}
				}

				if (!$subst_done)
					break;
			}
			if ($rounds <= 0)
			{
				throw new Exception('Template : Too many nested fragment expansion levels for Fragment Document: "' . htmlspecialchars(substr($frame, 0, 250), ENT_QUOTES, 'UTF-8') . '"');
			}
		}

		$this->template = $this->splitTemplate($leadin . file_get_contents($tmpl) . $leadout);
	}

	/**
	 * Load a monolithic template
	 */
	public function setTemplate($tmpl, $leadin = '', $leadout = '')
	{
		if (!is_file($tmpl))
		{
			throw new Exception('Template not found: "' . htmlspecialchars(substr($tmpl, 0, 250), ENT_QUOTES, 'UTF-8') . '"');
		}
		$idx = strrpos($tmpl, '/');
		if (empty($this->includePath) && $idx !== false)
		{
			$this->includePath = substr($tmpl, 0, $idx + 1);
		}
		$this->template = $this->splitTemplate($leadin . file_get_contents($tmpl) . $leadout);
	}

	/**
	 * Sets the template content directly (not through a file)
	 */
	public function setTemplateText($text)
	{
		$this->template = $this->splitTemplate($text);
	}

	/**
	 * Parse template and return the contents
	 */
	public function parseAndReturn()
	{
		$this->blocked_params = array();
		$this->output = array();
		$this->nestingLevel = 0;
		$this->process(0, count($this->template), $this->params);
		return implode('', $this->output);
	}

	/**
	 * Parse template and ECHO the result
	 */
	public function parseAndEcho()
	{
		$this->blocked_params = array();
		$this->output = null;
		$this->nestingLevel = 0;
		return $this->process(0, count($this->template), $this->params);
	}

	/**
	 * Parse template and ECHO the result;
	 * Eval inline PHP code
	 */
	public function parseAndEchoPHP()
	{
		return $this->CheckPHP($this->parseAndReturn());
	}

	/**
	 * Parse template and save the result to the file $file
	 *
	 * Return FALSE on failure; otherwise return the number of bytes produced.
	 */
	public function parseAndSave($file)
	{
		$outf = fopen($file, 'w');
		if ($outf)
		{
			$content = $this->parseAndReturn();
			$rv = fputs($outf, $content);
			fclose($outf);
			return $rv;
		}
		return false;
	}

	/**
	 * Set include path (only 1 directory possible)
	 * Call this before setTemplate!
	 */
	public function setIncludePath($path)
	{
		$this->includePath = $path;
		if (substr($path, -1, 1) != '/') $this->includePath .= '/';
	}

	//########################################################
	//#################### STATIC FUNCTIONS ##################
	//########################################################

	/**
	 * Does everything at once: assemble, setParams and parseAndEcho
	 */
	public static function assembleAndEcho($frame, $frags, &$params, $leadin = '', $leadout = '')
	{
		$parser = new ccmsParser;
		$parser->setParams($params);
		$parser->assemble($frame, $frags, $leadin, $leadout);
		$parser->parseAndEcho();
	}

	/**
	 * Does everything at once: setTemplate, setParams and parseAndEcho
	 */
	public static function setTemplateAndEcho($tmpl, &$params, $leadin = '', $leadout = '')
	{
		$parser = new ccmsParser;
		$parser->setParams($params);
		$parser->setTemplate($tmpl, $leadin, $leadout);
		$parser->parseAndEcho();
	}

	/**
	 * Does everything at once: assemble, setParams and parseAndEchoPHP
	 */
	public static function assembleAndEchoPHP($frame, $frags, &$params, $leadin = '', $leadout = '')
	{
		$parser = new ccmsParser;
		$parser->setParams($params);
		$parser->assemble($frame, $frags, $leadin, $leadout);
		return $parser->parseAndEchoPHP();
	}

	/**
	 * Does everything at once: setTemplate, setParams and parseAndEcho
	 */
	public static function setTemplateAndEchoPHP($tmpl, &$params, $leadin = '', $leadout = '')
	{
		$parser = new ccmsParser;
		$parser->setParams($params);
		$parser->setTemplate($tmpl, $leadin, $leadout);
		return $parser->parseAndEchoPHP();
	}

} // End class ccmsParser

/**
 * a parser that uses [ ] instead of {% %}
 */
class AlternativeParser extends ccmsParser
{
	public function splitTemplate($str)
	{
		$str = preg_replace('/\[(.*?)\]/', "{%\\1%}", $str);
		return parent::splitTemplate($str);
	}
}

?>