<?php

/*
 * part of CompactCMS
 */


/* make sure no-one can run anything here if they didn't arrive through 'proper channels' */
if(!defined("COMPACTCMS_CODE")) { die('Illegal entry point!'); } /*MARKER*/



if ($cfg['IN_DEVELOPMENT_ENVIRONMENT'])
{
	/* always flush cached data at the start of each invocation -- which always passes through here, at least. */
	clearstatcache();
}



define('MENU_TARGET_COUNT', 5); // CCMS supports 5 menu 'destinations'

if (!defined('BASE_PATH'))
{
	$base = str_replace('\\','/',dirname(dirname(dirname(__FILE__))));
	define('BASE_PATH', $base);
}


/*MARKER*/require_once(BASE_PATH . '/lib/class/exception_ajax.php');

/*MARKER*/require_once(BASE_PATH . '/lib/includes/email-validator/EmailAddressValidator.php');

/*MARKER*/require_once(BASE_PATH . '/lib/includes/htmLawed/htmLawed.php');





/**
 * Return TRUE when one string matches the 'tail' of the other string
 */
function strmatch_tail($a, $b)
{
	if (strlen($a) < strlen($b))
	{
		$tmp = $a;
		$a = $b;
		$b = $tmp;
	}

	$idx = strpos($a, $b);
	if ($idx === false)
		return false;
	return ($idx + strlen($b) == strlen($a));
}



/**
 * Remove leading zeroes from the given string.
 *
 * When the string is ALL ZEROES, keep the last one.
 *
 * When the string is empty, return the string "0" instead.
 * Hence this function acts like a string->integer->string comverter, as
 * empty strings equal zero as well as "0" strings do.
 *
 * @remark This function is used, among other things, in various spots to provide backwards compatibility
 *         with older CCMS releases which had 'zerofill'ed numeric columns in
 *         the database.
 */
function rm0lead($str)
{
	$rv = ltrim(strval($str), '0');
	if (empty($rv))
		return '0';
	return $rv;
}




/**
 * Convert any string input to a US-ASCII limited character set with a few common conversions included.
 *
 * Use this function for filtering any input which doesn't need the full UTF8 range. Most useful as a preprocessor for further
 * security-oriented input filters.
 *
 * Code ripped from function pagetitle($data, $options = array()) in the 'fancyupload' PHP module.
 */
function str2USASCII($src)
{
	static $regex;

	if (!$regex)
	{
		$regex = array(
			explode(' ', 'Æ æ Œ œ ß Ü ü Ö ö Ä ä À Á Â Ã Ä Å &#260; &#258; Ç &#262; &#268; &#270; &#272; Ð È É Ê Ë &#280; &#282; &#286; Ì Í Î Ï &#304; &#321; &#317; &#313; Ñ &#323; &#327; Ò Ó Ô Õ Ö Ø &#336; &#340; &#344; Š &#346; &#350; &#356; &#354; Ù Ú Û Ü &#366; &#368; Ý Ž &#377; &#379; à á â ã ä å &#261; &#259; ç &#263; &#269; &#271; &#273; è é ê ë &#281; &#283; &#287; ì í î ï &#305; &#322; &#318; &#314; ñ &#324; &#328; ð ò ó ô õ ö ø &#337; &#341; &#345; &#347; š &#351; &#357; &#355; ù ú û ü &#367; &#369; ý ÿ ž &#378; &#380;'),
			explode(' ', 'Ae ae Oe oe ss Ue ue Oe oe Ae ae A A A A A A A A C C C D D D E E E E E E G I I I I I L L L N N N O O O O O O O R R S S S T T U U U U U U Y Z Z Z a a a a a a a a c c c d d e e e e e e g i i i i i l l l n n n o o o o o o o o r r s s s t t u u u u u u y y z z z'),
		);

		//$regex[0][] = '"';
		//$regex[0][] = "'";
	}

	$src = strval($src); // force cast to string before we do anything

	// US-ASCII-ize known characters...
	$src = str_replace($regex[0], $regex[1], $src);
	// replace any remaining non-ASCII chars...
	$src = preg_replace('/([^ -~])+/', '~', $src);

	return trim($src);
}

/**
Convert any input text ($src) to a decent identifier which can serve as variable name and/or filename.

Hence, any characters in the input text which are not suitable to both situations are replaced by the
ubiquitous underscore character. Any runs of multiple occurrences of that one are reduced to a single
one each.

When the transformation would produce an identifier longer than a specified number of characters
(default: 32), it is forcibly shortened to that length and the last 8 characters are replaced by the
characters produced by the hash of the input text in order to deliver an identifier with quite
tolerable uniqueness guarantees.
*/
function str2VarOrFileName($src, $extra_accept_set = '', $accept_leading_minus = false, $max_outlen = 32)
{
	static $regex4var;

	if (!$regex4var)
	{
		$regex4var = array(
			explode(' ', '&amp; & +'),
			explode(' ', '_n_ _n_ _n_'),
		);

		$regex4var[0][] = '"';
		$regex4var[0][] = "'";
	}

	$dst = str2USASCII($src);

	$dst = str_replace($regex4var[0], $regex4var[1], $dst);

	$dst = preg_replace('/(?:[^\-A-Za-z0-9_' . $extra_accept_set . ']|_)+/', '_', $dst);
	// reduce series of underscores to a single one:
	$dst = preg_replace('/_+/', '_', $dst);

	// remove leading and trailing underscores (which may have been whitespace or other stuff before)
	// except... we have directories which start with an underscore. So I guess a single
	// leading underscore should be okay. And so would a trailing underscore...
	//$dst = trim($dst, '_');

	// We NEVER tolerate a leading dot:
	$dst = preg_replace('/^\.+/', '', $dst);
	if (!$accept_leading_minus)
	{
		$dst = preg_replace('/^-+/', '', $dst);
	}
	
	if ($max_outlen < strlen($dst))
	{
		$h = md5($src);
		$tl = min(32, intval(($max_outlen + 3) / 4)); // round up tail len (the hash-replaced bit), so for very small sizes it's still > 0
		$dst = substr($dst, 0, $max_outlen - $tl) . substr($h, -$tl);
	}
	
	return $dst;
}

/*
 * moved here from tiny_mce_gzip.php; augmented to accept '.', NOT accepting '_' as it's a wildcard in SQL
 */

/**
 * Return the value of a $_GET[] entry (or $def if the entity doesn't exist), stripped of
 * anything that can adversily affect
 * - SQL queries (anti-SQL injection)
 * - HTML output (anti-XSS)
 * - filenames (including UNIX 'hidden' files, which start with a dot '.')
 *
 * Accepted/passed set of characters are, specified as a regex:
 *
 * [0-9A-Za-z\-][0-9A-Za-z,.\-]*[0-9A-Za-z]
 *
 * As such, this is very good filter for numeric values, alphanumeric 'id's and filenames.
 */
function getGETparam4IdOrNumber($name, $def = null)
{
	if (!isset($_GET[$name]))
		return $def;

	return filterParam4IdOrNumber(rawurldecode($_GET[$name]), $def);
}

function getPOSTparam4IdOrNumber($name, $def = null)
{
	if (!isset($_POST[$name]))
		return $def;

	return filterParam4IdOrNumber($_POST[$name], $def);
}

function getREQUESTparam4IdOrNumber($name, $def = null)
{
	if (!isset($_REQUEST[$name]))
		return $def;

	return filterParam4IdOrNumber(rawurldecode($_REQUEST[$name]), $def);
}

function filterParam4IdOrNumber($value, $def = null)
{
	if (!isset($value))
		return $def;

	$value = strval($value); // force cast to string before we do anything

	// see if the value is a valid integer (plus or minus); only then do we accept a leading minus.
	$numval = intval($value);
	if ($numval == 0 || strval($numval) != $value)
	{
		// no full match for the integer check, so this is a string and we don't tolerate leading minus.
		$value = str2VarOrFileName($value);
	}
	else
	{
		$value = strval($numval);
	}
	return $value;
}















function getGETparam4Filename($name, $def = null)
{
	if (!isset($_GET[$name]))
		return $def;

	return filterParam4Filename(rawurldecode($_GET[$name]), $def);
}

function getPOSTparam4Filename($name, $def = null)
{
	if (!isset($_POST[$name]))
		return $def;

	return filterParam4Filename($_POST[$name], $def);
}

/**
 * As filterParam4IdOrNumber(), but also accepts '_' underscores and '.' dots, but NOT at the start or end of the filename!
 */
function filterParam4Filename($value, $def = null)
{
	if (!isset($value))
		return $def;

	$value = str2VarOrFileName($value, '~\.');

	return $value;
}






function getGETparam4CommaSeppedFullFilePaths($name, $def = null)
{
	if (!isset($_GET[$name]))
		return $def;

	return filterParam4CommaSeppedFullFilePaths(rawurldecode($_GET[$name]), $def);
}

function getPOSTparam4CommaSeppedFullFilePaths($name, $def = null)
{
	if (!isset($_POST[$name]))
		return $def;

	return filterParam4CommaSeppedFullFilePaths($_POST[$name], $def);
}

/**
 * As filterParam4FullFilePath(), but also accepts a 'comma' separator
 */
function filterParam4CommaSeppedFullFilePaths($value, $def = null)
{
	if (!isset($value))
		return $def;

	$fns = explode(',', strval($value));
	if (!is_array($fns))
	{
		return $def;
	}
	for ($i = count($fns); $i-- > 0; )
	{
		$fns[$i] = filterParam4FullFilePath($fns[$i], '');
	}

	return implode(',', $fns);
}




function getGETparam4FullFilePath($name, $def = null, $accept_parent_dotdot = false)
{
	if (!isset($_GET[$name]))
		return $def;

	return filterParam4FullFilePath(rawurldecode($_GET[$name]), $def, $accept_parent_dotdot);
}

function getPOSTparam4FullFilePath($name, $def = null, $accept_parent_dotdot = false)
{
	if (!isset($_POST[$name]))
		return $def;

	return filterParam4FullFilePath($_POST[$name], $def, $accept_parent_dotdot);
}

/**
 * As filterParam4Filename(), but also accepts '/' directory separators
 *
 * When $accept_parent_dotdot is TRUE, only then does this filter
 * accept '../' directory parts anywhere in the path.
 *
 * WARNING: setting $accept_parent_dotdot = TRUE can be VERY DANGEROUS
 *          without further checking the result whether it's trying to
 *          go places we don't them to go!
 *
 *          Be vewey vewey caweful!
 *
 *          Just to give you an idea:
 *            ../../../../../../../../../../../../etc/passwords
 *          would be LEGAL *AND* VERY DANGEROUS if the accepted path is not
 *          validated further upon return from this function!
 */
function filterParam4FullFilePath($value, $def = null, $accept_parent_dotdot = false)
{
	if (!isset($value))
		return $def;

	$fns = explode('/', strval($value));
	if (!is_array($fns))
	{
		return $def;
	}
	for ($i = count($fns); $i-- > 0; )
	{
		$fns[$i] = filterParam4Filename($fns[$i], '');
		if ($i > 0 && $i < count($fns) - 1 && empty($fns[$i]))
		{
			return $def; // illegal path specified!
		}
		if ($fns[$i] == ".." && !$accept_parent_dotdot)
		{
			return $def; // illegal path specified!
		}
	}

	return implode('/', $fns);
}




function getGETparam4boolYN($name, $def = null)
{
	if (!isset($_GET[$name]))
		return $def;

	return filterParam4boolYN(rawurldecode($_GET[$name]), $def);
}

function getPOSTparam4boolYN($name, $def = null)
{
	if (!isset($_POST[$name]))
		return $def;

	return filterParam4boolYN($_POST[$name], $def);
}

/**
 * Accepts any boolean value: as any number, T[rue]/F[alse] or Y[es]/N[o]
 */
function filterParam4boolYN($value, $def = null)
{
	$rv = filterParam4boolean($value, null);
	if ($rv === true)
	{
		return 'Y';
	}
	else if ($rv === false)
	{
		return 'N';
	}
	return $def;
}




function getGETparam4boolean($name, $def = null)
{
	if (!isset($_GET[$name]))
		return $def;

	return filterParam4boolean(rawurldecode($_GET[$name]), $def);
}

function getPOSTparam4boolean($name, $def = null)
{
	if (!isset($_POST[$name]))
		return $def;

	return filterParam4boolean($_POST[$name], $def);
}

/**
 * Accepts any boolean value: as any number, T[rue]/F[alse] or Y[es]/N[o]
 */
function filterParam4boolean($value, $def = null)
{
	if (!isset($value))
		return $def;

	$value = trim(strval($value)); // force cast to string before we do anything
	if (empty($value))
		return $def;

	// see if the value is a valid integer (plus or minus)
	$numval = intval($value);
	if (strval($numval) !== $value)
	{
		// no full match for the integer check, so this is a string hence we must check the text-based boolean values here:
		switch (strtoupper(substr($value, 0, 1)))
		{
		case 'T':
		case 'Y':
			return true;

		case 'F':
		case 'N':
			return false;

		default:
			return $def;
		}
	}
	else
	{
		return ($numval != 0);
	}
	return $def;
}




function getGETparam4Number($name, $def = null)
{
	if (!isset($_GET[$name]))
		return $def;

	return filterParam4Number(rawurldecode($_GET[$name]), $def);
}

function getPOSTparam4Number($name, $def = null)
{
	if (!isset($_POST[$name]))
		return $def;

	return filterParam4Number($_POST[$name], $def);
}

/**
 * Accepts any number
 */
function filterParam4Number($value, $def = null)
{
	if (!isset($value))
		return $def;

	$value = trim(strval($value)); // force cast to string before we do anything
	if (empty($value))
		return $def;

	// see if the value is a valid integer (plus or minus)
	$value = rm0lead($value);
	$numval = (is_numeric($value)?intval($value):null);
	if (strval($numval) !== $value)
	{
		// no full match for the integer check, so this is a non-numeric string:
		return $def;
	}
	else
	{
		return $numval;
	}
}





function getGETparam4DisplayHTML($name, $def = null)
{
	if (!isset($_GET[$name]))
		return $def;

	return filterParam4DisplayHTML(rawurldecode($_GET[$name]), $def);
}

function getPOSTparam4DisplayHTML($name, $def = null)
{
	if (!isset($_POST[$name]))
		return $def;

	return filterParam4DisplayHTML($_POST[$name], $def);
}

/**
 * Accepts any non-aggressive HTML
 */
function filterParam4DisplayHTML($value, $def = null)
{
	if (!isset($value))
		return $def;

	$value = trim(strval($value)); // force cast to string before we do anything
	if (empty($value))
		return $def;

	// use HTMLpurifier to strip undesirable content. sanitize.inc.php is not an option as it's a type of blacklist filter and we WANT a whitelist approach for future-safe processing.

	// convert the input to a string which can be safely printed as HTML; no XSS through JS or 'smart' use of HTML tags:
if (0)
{
	$value = htmlentities($value, ENT_NOQUOTES, 'UTF-8');
}
else
{
	$config = array(
				'safe' => 1
				// , 'elements' => 'a, em, strong'
				);
	$value = htmLawed($value, $config);
}

	return $value;
}





function getGETparam4RAWHTML($name, $def = null)
{
	if (!isset($_GET[$name]))
		return $def;

	return filterParam4RAWHTML(rawurldecode($_GET[$name]), $def);
}

function getPOSTparam4RAWHTML($name, $def = null)
{
	if (!isset($_POST[$name]))
		return $def;

	return filterParam4RAWHTML($_POST[$name], $def);
}

/**
 * Accepts any non-aggressive HTML
 */
function filterParam4RAWHTML($value, $def = null)
{
	if (!isset($value))
		return $def;

	$value = trim(strval($value)); // force cast to string before we do anything
	if (empty($value))
		return $def;

	// use HTMLpurifier to strip undesirable content. sanitize.inc.php is not an option as it's a type of blacklist filter and we WANT a whitelist approach for future-safe processing.

	// convert the input to a string which can be safely printed as HTML; no XSS through JS or 'smart' use of HTML tags:
	$config = array(
				'safe' => 1
				// , 'elements' => 'a, em, strong'
				);
	$value = htmLawed($value, $config);

	return $value;
}













function getGETparam4RAWCONTENT($name, $def = null)
{
	if (!isset($_GET[$name]))
		return $def;

	return filterParam4RAWCONTENT(rawurldecode($_GET[$name]), $def);
}

function getPOSTparam4RAWCONTENT($name, $def = null)
{
	if (!isset($_POST[$name]))
		return $def;

	return filterParam4RAWCONTENT($_POST[$name], $def);
}

/**
 * Accepts ANY CONTENT
 */
function filterParam4RAWCONTENT($value, $def = null)
{
	if (!isset($value))
		return $def;

	$value = strval($value); // force cast to string before we do anything
	if (empty($value))
		return $def;

	return $value;
}













function getGETparam4Email($name, $def = null)
{
	if (!isset($_GET[$name]))
		return $def;

	return filterParam4Email(rawurldecode($_GET[$name]), $def);
}

function getPOSTparam4Email($name, $def = null)
{
	if (!isset($_POST[$name]))
		return $def;

	return filterParam4Email($_POST[$name], $def);
}

/**
 * Accepts any valid email address.
 *
 * Uses the email validator from:
 *
 *   http://code.google.com/p/php-email-address-validation/
 *
 */
function filterParam4Email($value, $def = null)
{
	if (!isset($value))
		return $def;

	$value = trim(strval($value)); // force cast to string before we do anything
	if (empty($value))
		return $def;

	$validator = new EmailAddressValidator;
	if ($validator->check_email_address($value))
	{
		// Email address is technically valid
		return $value;
	}
	return $numval;
}









function getGETparam4HumanName($name, $def = null)
{
	if (!isset($_GET[$name]))
		return $def;

	return filterParam4HumanName(rawurldecode($_GET[$name]), $def);
}

function getPOSTparam4HumanName($name, $def = null)
{
	if (!isset($_POST[$name]))
		return $def;

	return filterParam4HumanName($_POST[$name], $def);
}

/**
 * Accepts any text, just as long as it doesn't come with HTML tags
 */
function filterParam4HumanName($value, $def = null)
{
	if (!isset($value))
		return $def;

	$value = trim(strval($value)); // force cast to string before we do anything
	if (empty($value))
		return $def;

	return htmlentities($value, ENT_NOQUOTES, 'UTF-8');
}









function getGETparam4EmailSubjectLine($name, $def = null)
{
	if (!isset($_GET[$name]))
		return $def;

	return filterParam4EmailSubjectLine(rawurldecode($_GET[$name]), $def);
}

function getPOSTparam4EmailSubjectLine($name, $def = null)
{
	if (!isset($_POST[$name]))
		return $def;

	return filterParam4EmailSubjectLine($_POST[$name], $def);
}

/**
 * Accepts any text except HTML specials cf. RFC2047
 *
 * Is NOT suitable for direct display within a HTML context (i.e. on a page showing some
 * sort of feedback after you've entered a mail through a form, etc.); apply
 *   htmlspecialchars()
 * before you do so!
 */
function filterParam4EmailSubjectLine($value, $def = null)
{
	if (!isset($value))
		return $def;

	$value = trim(strval($value)); // force cast to string before we do anything
	if (empty($value))
		return $def;

	// TODO: real RFC2047 filter.
	$value = str2USASCII($value);
	$value = str_replace('=', '~', $value);

	return $value;
}




function getGETparam4EmailBody($name, $def = null)
{
	if (!isset($_GET[$name]))
		return $def;

	return filterParam4EmailBody(rawurldecode($_GET[$name]), $def);
}

function getPOSTparam4EmailBody($name, $def = null)
{
	if (!isset($_POST[$name]))
		return $def;

	return filterParam4EmailBody($_POST[$name], $def);
}

/**
 * Accepts any text; ready it for HTML display ~ HTML email
 */
function filterParam4EmailBody($value, $def = null)
{
	if (!isset($value))
		return $def;

	$value = trim(strval($value)); // force cast to string before we do anything
	if (empty($value))
		return $def;

	// TODO: real email message body filter?
	return htmlspecialchars($value, ENT_COMPAT, 'UTF-8');
}











function getGETparam4URL($name, $def = null)
{
	if (!isset($_GET[$name]))
		return $def;

	return filterParam4URL(rawurldecode($_GET[$name]), $def);
}

function getPOSTparam4URL($name, $def = null)
{
	if (!isset($_POST[$name]))
		return $def;

	return filterParam4URL($_POST[$name], $def);
}

/**
 * Accepts any 'fully qualified' URL, i.e. proper domain name, etc.
 */
function filterParam4URL($value, $def = null)
{
	if (!isset($value))
		return $def;

	$value = trim(strval($value)); // force cast to string before we do anything
	if (empty($value))
		return $def;

	if (!regexUrl($value, true)) // the ENTIRE string must be a URL, nothing else allowed 'at the tail end'!
		return $def;

	return $value;
}





function getGETparam4DateTime($name, $def = null)
{
	if (!isset($_GET[$name]))
		return $def;

	return filterParam4DateTime(rawurldecode($_GET[$name]), $def);
}

function getPOSTparam4DateTime($name, $def = null)
{
	if (!isset($_POST[$name]))
		return $def;

	return filterParam4DateTime($_POST[$name], $def);
}

/**
 * Accepts a date/time stamp
 */
function filterParam4DateTime($value, $def = null)
{
	if (!isset($value))
		return $def;

	$value = trim(strval($value)); // force cast to string before we do anything
	if (empty($value))
		return $def;

	$dt = strtotime($value);
	/*
	 * time == 0 ~ 1970-01-01T00:00:00 is considered an INVALID date here,
	 * because it can easily result from parsing arbitrary input representing
	 * the date eqv. of zero(0)...
	 *
	 * time == -1 was the old error signaling return code (pre-PHP 5.1.0)
	 */
	if (!is_int($dt) || $dt <= 0)
	{
		return $def;
	}
	return $dt;
}








function getGETparam4MathExpression($name, $def = null)
{
	if (!isset($_GET[$name]))
		return $def;

	return filterParam4MathExpression(rawurldecode($_GET[$name]), $def);
}

function getPOSTparam4MathExpression($name, $def = null)
{
	if (!isset($_POST[$name]))
		return $def;

	return filterParam4MathExpression($_POST[$name], $def);
}

/**
 * Accepts any ASCII which might befit a conditional expression. In short: we accept the entire ASCII range from SPACE to 126(dec).
 */
function filterParam4MathExpression($value, $def = null)
{
	if (!isset($value))
		return $def;

	$value = trim(strval($value)); // force cast to string before we do anything
	if (empty($value))
		return $def;

	$value = preg_replace('/\s/s', ' ', $value); // any whitespace becomes SPACE: this way we get rid of CR LF (using the /s regex option!)
	$value = str2USASCII($value);

	return $value;
}









/*
	public static function pagetitle($data, $options = array()){
		static $regex;
		if (!$regex){
			$regex = array(
				explode(' ', 'Æ æ Œ œ ß Ü ü Ö ö Ä ä À Á Â Ã Ä Å &#260; &#258; Ç &#262; &#268; &#270; &#272; Ð È É Ê Ë &#280; &#282; &#286; Ì Í Î Ï &#304; &#321; &#317; &#313; Ñ &#323; &#327; Ò Ó Ô Õ Ö Ø &#336; &#340; &#344; Š &#346; &#350; &#356; &#354; Ù Ú Û Ü &#366; &#368; Ý Ž &#377; &#379; à á â ã ä å &#261; &#259; ç &#263; &#269; &#271; &#273; è é ê ë &#281; &#283; &#287; ì í î ï &#305; &#322; &#318; &#314; ñ &#324; &#328; ð ò ó ô õ ö ø &#337; &#341; &#345; &#347; š &#351; &#357; &#355; ù ú û ü &#367; &#369; ý ÿ ž &#378; &#380;'),
				explode(' ', 'Ae ae Oe oe ss Ue ue Oe oe Ae ae A A A A A A A A C C C D D D E E E E E E G I I I I I L L L N N N O O O O O O O R R S S S T T U U U U U U Y Z Z Z a a a a a a a a c c c d d e e e e e e g i i i i i l l l n n n o o o o o o o o r r s s s t t u u u u u u y y z z z'),
			);

			$regex[0][] = '"';
			$regex[0][] = "'";
		}

		$data = trim(substr(preg_replace('/(?:[^A-z0-9]|_|\^)+/i', '_', str_replace($regex[0], $regex[1], $data)), 0, 64), '_');
		return !empty($options) ? self::checkTitle($data, $options) : $data;
	}

*/



// GENERAL FUNCTIONS ==
/**
 * Test whether the filter regex (for URL detection) matches the given $data value. Return
 * TRUE if so.
 *
 * This is used to see if there's a URL specified in the page description.
 */
function regexUrl($data, $match_entire_string = false)
{
	$regex = "((https?|ftp)\:\/\/)?"; // SCHEME
	$regex .= "([a-z0-9+!*(),;?&=\$_.-]+(\:[a-z0-9+!*(),;?&=\$_.-]+)?@)?"; // User and Pass
	$regex .= "([a-z0-9-.]*)\.([a-z]{2,3})"; // Host or IP
	$regex .= "(\:[0-9]{2,5})?"; // Port
	$regex .= "(\/([a-z0-9+\$_-]\.?)+)*\/?"; // Path
	$regex .= "(\?[a-z+&\$_.-][a-z0-9;:@&%=+\/\$_.-]*)?"; // GET Query
	$regex .= "(#[a-z_.-][a-z0-9+\$_.-]*)?"; // Anchor

	if(preg_match('/^' . $regex . ($match_entire_string ? '$' : '') . '/i', $data))
	{
		return true;
	}
	return false;
}



function DetermineTemplateName($name = null, $printing = 'N')
{
	global $cfg, $ccms;

	if (!empty($name))
	{
		$name = $name . ($printing != 'Y' ? '' : '/print');

		// Set the template variable for current page
		$templatefile = BASE_PATH . '/lib/templates/' . $name . '.tpl.html';

		// Check whether template exists, specify default or throw "no templates" error.
		if(is_file($templatefile))
		{
			return $name;
		}
	}

	if(is_array($ccms['template_collection']) && count($ccms['template_collection']) > 0)
	{
		// pick default template
		$name = $ccms['template_collection'][0] . ($printing != 'Y' ? '' : '/print');

		// Set the template variable for current page
		$templatefile = BASE_PATH . '/lib/templates/' . $name . '.tpl.html';

		// Check whether template exists, specify default or throw "no templates" error.
		if(is_file($templatefile))
		{
			return $name;
		}
	}

	// for printing ONLY, see if the 'ccms' template exists anyway.
	if ($printing == 'Y')
	{
		$name = 'ccms/print';

		// Set the template variable for current page
		$templatefile = BASE_PATH . '/lib/templates/' . $name . '.tpl.html';

		// Check whether template exists, specify default or throw "no templates" error.
		if(file_exists($templatefile))
		{
			return $name;
		}
	}

	die($ccms['lang']['system']['error_notemplate']);
}


/**
 * Determine how the PHP interpreter was invoked: cli/cgi/fastcgi/server,
 * where 'server' implies PHP is part of a webserver in the form of a 'module' (e.g. mod_php5) or similar.
 *
 * This information is used, for example, to decide the correct way to send the 'respose header code':
 * see send_response_status_header().
 */
function get_interpreter_invocation_mode()
{
	global $_ENV;
	global $_SERVER;

	/*
	 * see
	 *
	 * http://nl2.php.net/manual/en/function.php-sapi-name.php
	 * http://stackoverflow.com/questions/190759/can-php-detect-if-its-run-from-a-cron-job-or-from-the-command-line
	 */
	$mode = "server";
	$name = php_sapi_name();
	if (preg_match("/fcgi/", $name) == 1)
	{
		$mode = "fastcgi";
	}
	else if (preg_match("/cli/", $name) == 1)
	{
		$mode = "cli";
	}
	else if (preg_match("/cgi/", $name) == 1)
	{
		$mode = "cgi";
	}

	/*
	 * check whether POSIX functions have been compiled/enabled; xampp on Win32/64 doesn't have the buggers! :-(
	 */
	if (function_exists('posix_isatty'))
	{
		if (posix_isatty(STDOUT))
		{
			/* even when seemingly run as cgi/fastcgi, a valid stdout TTY implies an interactive commandline run */
			$mode = 'cli';
		}
	}

	if (!empty($_ENV['TERM']) && empty($_SERVER['REMOTE_ADDR']))
	{
		/* even when seemingly run as cgi/fastcgi, a valid stdout TTY implies an interactive commandline run */
		$mode = 'cli';
	}

	return $mode;
}


/**
 * Performs the correct way of transmitting the response status code header: PHP header() must be invoked in different ways
 * dependent on the way the PHP interpreter has been invoked.
 *
 * See also:
 *
 * http://nl2.php.net/manual/en/function.header.php
 */
function send_response_status_header($response_code)
{
	$mode = get_interpreter_invocation_mode();
	switch ($mode)
	{
	default:
	case 'fcgi':
		header('Status: ' . $response_code, true, $response_code);
		break;

	case 'server':
		header('HTTP/1.0 ' . $response_code . ' ' . get_response_code_string($response_code), true, $response_code);
		break;
	}
}


/**
 * Return TRUE when the given code is a valid HTTP response code.
 */
function is_http_response_code($response_code)
{
	$response_code = intval($response_code);
	switch ($response_code)
	{
	case 100:   // RFC2616 Section 10.1.1: Continue
	case 101:   // RFC2616 Section 10.1.2: Switching Protocols
	case 200:   // RFC2616 Section 10.2.1: OK
	case 201:   // RFC2616 Section 10.2.2: Created
	case 202:   // RFC2616 Section 10.2.3: Accepted
	case 203:   // RFC2616 Section 10.2.4: Non-Authoritative Information
	case 204:   // RFC2616 Section 10.2.5: No Content
	case 205:   // RFC2616 Section 10.2.6: Reset Content
	case 206:   // RFC2616 Section 10.2.7: Partial Content
	case 300:   // RFC2616 Section 10.3.1: Multiple Choices
	case 301:   // RFC2616 Section 10.3.2: Moved Permanently
	case 302:   // RFC2616 Section 10.3.3: Found
	case 303:   // RFC2616 Section 10.3.4: See Other
	case 304:   // RFC2616 Section 10.3.5: Not Modified
	case 305:   // RFC2616 Section 10.3.6: Use Proxy
	case 307:   // RFC2616 Section 10.3.8: Temporary Redirect
	case 400:   // RFC2616 Section 10.4.1: Bad Request
	case 401:   // RFC2616 Section 10.4.2: Unauthorized
	case 402:   // RFC2616 Section 10.4.3: Payment Required
	case 403:   // RFC2616 Section 10.4.4: Forbidden
	case 404:   // RFC2616 Section 10.4.5: Not Found
	case 405:   // RFC2616 Section 10.4.6: Method Not Allowed
	case 406:   // RFC2616 Section 10.4.7: Not Acceptable
	case 407:   // RFC2616 Section 10.4.8: Proxy Authentication Required
	case 408:   // RFC2616 Section 10.4.9: Request Time-out
	case 409:   // RFC2616 Section 10.4.10: Conflict
	case 410:   // RFC2616 Section 10.4.11: Gone
	case 411:   // RFC2616 Section 10.4.12: Length Required
	case 412:   // RFC2616 Section 10.4.13: Precondition Failed
	case 413:   // RFC2616 Section 10.4.14: Request Entity Too Large
	case 414:   // RFC2616 Section 10.4.15: Request-URI Too Large
	case 415:   // RFC2616 Section 10.4.16: Unsupported Media Type
	case 416:   // RFC2616 Section 10.4.17: Requested range not satisfiable
	case 417:   // RFC2616 Section 10.4.18: Expectation Failed
	case 500:   // RFC2616 Section 10.5.1: Internal Server Error
	case 501:   // RFC2616 Section 10.5.2: Not Implemented
	case 502:   // RFC2616 Section 10.5.3: Bad Gateway
	case 503:   // RFC2616 Section 10.5.4: Service Unavailable
	case 504:   // RFC2616 Section 10.5.5: Gateway Time-out
	case 505:   // RFC2616 Section 10.5.6: HTTP Version not supported
/*
	case 102:
	case 207:
	case 418:
	case 419:
	case 420:
	case 421:
	case 422:
	case 423:
	case 424:
	case 425:
	case 426:
	case 506:
	case 507:
	case 508:
	case 509:
	case 510:
*/
		return true;

	default:
		return false;
	}
}



/**
 * Return the HTTP response code string for the given response code
 */
function get_response_code_string($response_code)
{
	$response_code = intval($response_code);
	switch ($response_code)
	{
	case 100:   return "RFC2616 Section 10.1.1: Continue";
	case 101:   return "RFC2616 Section 10.1.2: Switching Protocols";
	case 200:   return "RFC2616 Section 10.2.1: OK";
	case 201:   return "RFC2616 Section 10.2.2: Created";
	case 202:   return "RFC2616 Section 10.2.3: Accepted";
	case 203:   return "RFC2616 Section 10.2.4: Non-Authoritative Information";
	case 204:   return "RFC2616 Section 10.2.5: No Content";
	case 205:   return "RFC2616 Section 10.2.6: Reset Content";
	case 206:   return "RFC2616 Section 10.2.7: Partial Content";
	case 300:   return "RFC2616 Section 10.3.1: Multiple Choices";
	case 301:   return "RFC2616 Section 10.3.2: Moved Permanently";
	case 302:   return "RFC2616 Section 10.3.3: Found";
	case 303:   return "RFC2616 Section 10.3.4: See Other";
	case 304:   return "RFC2616 Section 10.3.5: Not Modified";
	case 305:   return "RFC2616 Section 10.3.6: Use Proxy";
	case 307:   return "RFC2616 Section 10.3.8: Temporary Redirect";
	case 400:   return "RFC2616 Section 10.4.1: Bad Request";
	case 401:   return "RFC2616 Section 10.4.2: Unauthorized";
	case 402:   return "RFC2616 Section 10.4.3: Payment Required";
	case 403:   return "RFC2616 Section 10.4.4: Forbidden";
	case 404:   return "RFC2616 Section 10.4.5: Not Found";
	case 405:   return "RFC2616 Section 10.4.6: Method Not Allowed";
	case 406:   return "RFC2616 Section 10.4.7: Not Acceptable";
	case 407:   return "RFC2616 Section 10.4.8: Proxy Authentication Required";
	case 408:   return "RFC2616 Section 10.4.9: Request Time-out";
	case 409:   return "RFC2616 Section 10.4.10: Conflict";
	case 410:   return "RFC2616 Section 10.4.11: Gone";
	case 411:   return "RFC2616 Section 10.4.12: Length Required";
	case 412:   return "RFC2616 Section 10.4.13: Precondition Failed";
	case 413:   return "RFC2616 Section 10.4.14: Request Entity Too Large";
	case 414:   return "RFC2616 Section 10.4.15: Request-URI Too Large";
	case 415:   return "RFC2616 Section 10.4.16: Unsupported Media Type";
	case 416:   return "RFC2616 Section 10.4.17: Requested range not satisfiable";
	case 417:   return "RFC2616 Section 10.4.18: Expectation Failed";
	case 500:   return "RFC2616 Section 10.5.1: Internal Server Error";
	case 501:   return "RFC2616 Section 10.5.2: Not Implemented";
	case 502:   return "RFC2616 Section 10.5.3: Bad Gateway";
	case 503:   return "RFC2616 Section 10.5.4: Service Unavailable";
	case 504:   return "RFC2616 Section 10.5.5: Gateway Time-out";
	case 505:   return "RFC2616 Section 10.5.6: HTTP Version not supported";
/*
	case 102:   return "Processing";  // http://www.askapache.com/htaccess/apache-status-code-headers-errordocument.html#m0-askapache3
	case 207:   return "Multi-Status";
	case 418:   return "I'm a teapot";
	case 419:   return "unused";
	case 420:   return "unused";
	case 421:   return "unused";
	case 422:   return "Unproccessable entity";
	case 423:   return "Locked";
	case 424:   return "Failed Dependency";
	case 425:   return "Node code";
	case 426:   return "Upgrade Required";
	case 506:   return "Variant Also Negotiates";
	case 507:   return "Insufficient Storage";
	case 508:   return "unused";
	case 509:   return "unused";
	case 510:   return "Not Extended";
*/
	default:   return rtrim("Unknown Response Code " . $response_code);
	}
}



/*
 * http://nadeausoftware.com/node/79
 */
function path_remove_dot_segments($path)
{
	// make sure the split is still safe when the 'path' is either a Windows path or a URL:
	$root = '';
	$q = '';
	$count = preg_match('/^([^:]+)(:\/+)([^?]*)(\?.*)$/s', $path, $matches);
	if ($count)
	{
		$root = $matches[1] . $matches[2];
		$path = $matches[3];
		$q = $matches[4];
	}

	// multi-byte character explode
	$inSegs  = preg_split('!/!u', $path);
	$outSegs = array();
	foreach ($inSegs as $seg)
	{
		if ($seg == '' || $seg == '.')
			continue;
		if ($seg == '..')
			array_pop($outSegs);
		else
			array_push($outSegs, $seg);
	}
	$outPath = implode('/', $outSegs);
	if ($path[0] == '/')
		$outPath = '/' . $outPath;
	// // compare last multi-byte character against '/'
	// if ($outPath != '/' && (mb_strlen($path)-1) == mb_strrpos($path, '/', 'UTF-8'))
	//     $outPath .= '/';
	return $root . $outPath . $q;
}



/**
 * Convert any path (absolute or relative) to a fully qualified URL
 */
function makeAbsoluteURI($path)
{
	$reqpage = filterParam4FullFilePath($_SERVER["PHP_SELF"]);

	$page = array();
	if (strpos($path, '://'))
	{
		if (strpos($path, '?') === false || strpos($path, '://') < strpos($path, '?'))
		{
			/*
			 * parse_url can only parse URLs, not relative paths.
			 *
			 * http://bugs.php.net/report.php?manpage=function.parse-url#Notes
			 */
			$page = parse_url($path);
			if (isset($page[PHP_URL_SCHEME]))
				return $path;

			/*
			 * We do NOT accept 'URL's like
			 *
			 *   www.example.com/path.ext
			 *
			 * as input: we treat the entire string as a path (and a relative one at that)!
			 */
		}
	}

	/*
	 * Expect input which is a subset of
	 *
	 *   /path/file.exe?query#fragment
	 *
	 * with either absolute or relative path/file.ext as the mandatory part.
	 */
	$idx = strpos($path, '?');
	if ($idx !== false)
	{
		$page[PHP_URL_PATH] = substr($path, 0, $idx);

		$path = substr($path, $idx + 1);
		$idx = strpos($path, '#');
		if ($idx !== false)
		{
			$page[PHP_URL_QUERY] = substr($path, 0, $idx);
			$page[PHP_URL_FRAGMENT] = substr($path, $idx + 1);
		}
		else
		{
			$page[PHP_URL_QUERY] = $path;
		}
	}
	else
	{
		$page[PHP_URL_PATH] = $path;
	}
	$path = $page[PHP_URL_PATH];

	if (strpos($path, '/') === 0)
	{
		//already absolute
	}
	else
	{
		/*
		 * Convert relative path to absolute by prepending the current request path
		 * (which is absolute) and a '../' basedir-similar.
		 *
		 * This way also provides for relative paths which don't start with './' but
		 * simply say something like
		 *   relpath/file.ext
		 * which will produce a dotted absolute path like this:
		 *   /current_request_path/reqfile.php/../relpath/file.ext
		 * which is fine: the ../ will remove the reqfile.php component and we're left
		 * with a neatly formatted absolute path!
		 */
		$page[PHP_URL_PATH] = $_SERVER['PHP_SELF'] . '/../' . $path;
	}
	$page[PHP_URL_PATH] = path_remove_dot_segments($page[PHP_URL_PATH]);

	// fill in the holes... assume defaults from the current request.
	if (empty($page[PHP_URL_SCHEME]))
	{
		if ((!empty($_SERVER['HTTPS']) && $_SERVER['HTTPS'] !== 'off')
			|| strpos(strtolower($_SERVER['SERVER_PROTOCOL']), 'https') === 0
			|| intval($_SERVER["SERVER_PORT"]) == 443)
		{
			$page[PHP_URL_SCHEME] = 'https';
		}
		else
		{
			$page[PHP_URL_SCHEME] = 'http';
		}
	}
	if (empty($page[PHP_URL_HOST]))
	{
		$page[PHP_URL_HOST] = $_SERVER["SERVER_NAME"];
	}
	if (empty($page[PHP_URL_PORT]))
	{
		/*
		Only set the port number when it is non-standard:
		*/
		$portno = intval($_SERVER["SERVER_PORT"]);
		if ($portno != 0
			&& ($page[PHP_URL_SCHEME] == 'http' && $portno == 80)
			&& ($page[PHP_URL_SCHEME] == 'https' && $portno == 443))
		{
			$page[PHP_URL_PORT] = $portno;
		}
	}

	$url = '';
	if(!empty($page[PHP_URL_SCHEME]))
	{
		$url = $page[PHP_URL_SCHEME] . '://';
	}
	if(!empty($page[PHP_URL_USER]))
	{
		$url .= $page[PHP_URL_USER];
		if(!empty($page[PHP_URL_PASS]))
		{
			$url .= ':' . $page[PHP_URL_PASS];
		}
		$url .= '@';
	}
	if(!empty($page[PHP_URL_HOST]))
	{
		$url .= $page[PHP_URL_HOST];
	}
	$url .= $page[PHP_URL_PATH];
	if (!empty($page[PHP_URL_QUERY]))
	{
		$url .= '?' . $page[PHP_URL_QUERY];
	}
	if (!empty($page[PHP_URL_FRAGMENT]))
	{
		$url .= '#' . $page[PHP_URL_FRAGMENT];
	}
	return $url;
}





/**
 * Convert an absolute HTTP path to a 'real path' on physical disc
 *
 * notes: implies invocation of realpath().
 */
function cvt_abs_http_path2realpath($http_base, $site_rootdir, $real_basedir)
{
	if (substr($http_base, 0, strlen($site_rootdir)) == $site_rootdir)
	{
		$http_base = substr($http_base, strlen($site_rootdir));
		if ($http_base[0] == '/')
			$http_base = substr($http_base, 1);

		$rp = $real_basedir;
		if (substr($rp, -1, 1) != '/')
			$rp .= '/';

		return realpath($rp . $http_base);
	}
	else
	{
		/*
		 * path outside the CCMS 'root'; this MAY be allowed when CCMS is in a subdir itself.
		 *
		 * Anyway, let realpath and the caller cope with the security issues that may stem from this.
		 */
		return realpath($http_base);
	}
}




/**
 * convert a UNIX path to an URL encoded string, i.e. perform rawurlencode on each of the path elements,
 * so that spaces and other noncompliant characters can be placed in the path.
 *
 * Recognizes these spacial characters: {':', '/'}
 */
function path2urlencode($path, $specialcharset = ':/')
{
	if (empty($path)) return '';

	$dst = '';
	$len = strlen($path);
	for ($i = 0; $i < $len; $i++)
	{
		$c = $path[$i];
		if (strpos($specialcharset, $c) !== false)
		{
			$dst .= $c;
		}
		else
		{
			$dst .= rawurlencode($c);
		}
	}
	return $dst;
}



/**
 * Convert any text (including any HTML) to a legible bit of text to act as part of a URL
 */
function cvt_text2legibleURL($text)
{
	// Limited characters
	static $special_chars = array("#","$","%","@","^","&","*","!","~","‘","\"","’","'","=","?","/","[","]","(",")","|","<",">",";","\\",",");

	$text = trim(strip_tags($text));
	$a1 = strtolower(str2USASCII($text));
	// Filter spaces, non-file characters and account for UTF-8
	$a1 = str_replace($special_chars, "", $a1);
	$a1 = trim(str_replace(array(' ', '~'),'-',$a1), '-');

	if (empty($a1))
	{
		// the alternative is URLencoding the whole shebang!
		$a1 = strtolower($text);
		// Filter spaces, non-file characters and account for UTF-8
		$a1 = str_replace($special_chars, "", $a1);
		$a1 = trim(str_replace(array(' ', '~'),'-',$a1), '-');
	}

	return rawurlencode($a1);
}



/**
 * Append pieces of a path together. Each part (argument) is treated as a directory or filename/filepath, so an extra '/' is
 * included between elements, where necessary (i.e. if the previous element didn't end on a '/').
 *
 * NOTES:
 *
 * Path elements get their Windows '\\' converted to '/' for free.
 *
 * Return FALSE when the argument list is empty
 *
 *
 * SECURITY NOTICE / WARNING:
 *
 * 1) if you suspect read attacks like fetching '/etc/passwd' might arrive at the doorstep
 *    of this one, then MAKE SURE to check the acceptability of the generated path!
 *
 * 2) does *NOT* clean up the generated path!
 *
 */
function merg_path_elems( /* ... */ )
{
	if(func_num_args() == 0)
	{
		return false;
	}

	/*                                                                                                                       \    |    |
	 * PHP 5.2.x and before:                                                                                                    \ """""""""  /
	 *                                                                                                                  \  """""""'""""/
	 * See  http://nl2.php.net/manual/en/function.func-get-arg.php                                                            \""""'"|'"""""----
	 * where the NOTES section says:                                                                                          "\""'""|""'"""
	 *                                                                                                              -----"""\""'"""""""\
	 *                                                                                                                  """'""'""'"""   \
	 *  Because this function depends on the current scope to determine parameter details, it cannot         e@@@@@@@@@^"""'"""""'""      \
	 *  be used as a function parameter in versions prior to 5.3.0. If this value must be passed, the    _@@@@@@@@@@@  ee""""e"""".@e
	 *  results should be assigned to a variable, and that variable should be passed.                  _e@@@@@@@@@@@@@@@@@@@@@@@@@@@@@
	 *                                                                                                  @@@@@@"@~~~~~~~~@@@.@@~~~~~~.@@e
	 *                                                                                                  @ @@@@.@     . ..@@@@ .    . ..@
	 * NOW I recall why I hated PHP! !@#$%^&*!                                                            @ @@@@."   ... ..@@@@e.   . ...@
	 *                                                                                                  @."@@@@@eeeeeeee@@~ ~@@eeeeee@@@
	 *                                              [Ger Hobbelt]                                       @e.@@@@@@@@@@@@@@ | @@@@@@@@@'
	 *                                                                                                  @eeeeeee@@@@@@@[ : ]@@@@@'
	 *                                                                                                      "'"""@@@@@@@::@::@@@@@
	 *                                                                                                      '"""" @@@@@@@@@@@@@@@@@
	 *                                                                                                  ""'"   v@@@@@@@@@v@@@v@@
	 *                                                                                                  "'"      V  VV  V  V    V
	 */
	$arg = func_get_arg(0);
	$path = str_replace("\\", '/', $arg);

	for($i = 1; $i < func_num_args(); $i++)
	{
		$arg = func_get_arg($i);
		$part = str_replace("\\", '/', $arg);
		if (empty($part)) continue;

		if (substr($path, -1, 1) != '/') $path .= '/';
		// strip off leading '/' of any subpart!
		if ($part[1] == '/') $part = substr($part, 1);

		$path .= $part;
	}

	// return path_remove_dot_segments($path);
	return $path;
}




/**
 * pathinfo with UTF-8 support.
 *
 * See also / derived from:
 *   http://nl.php.net/manual/en/function.pathinfo.php
 */
function pathinfo_utf($path)
{
	$path = str_replace('\\', '/', $path);
	if (strpos($path, '/') !== false)
		$basename = end(explode('/', $path));
	else
		return false;
	if (empty($basename))
		return false;

	$dirname = substr($path, 0, strlen($path) - strlen($basename) - 1);

	if (strpos($basename, '.') !== false)
	{
		$extension = end(explode('.', $path));
		$filename = substr($basename, 0, strlen($basename) - strlen($extension) - 1);
	}
	else
	{
		$extension = '';
		$filename = $basename;
	}

	return array
		(
			'dirname' => $dirname,
			'basename' => $basename,
			'extension' => $extension,
			'filename' => $filename
		);
}





/**
 * Like die($error) but this one redirect the client to the login page if that's possible.
 *
 * There the error message will be diplayed at the top of the screen, while you can enter
 * your credentials.
 *
 * The default error message is 'feature not allowed'.
 *
 * The default
 *
 * NOTE:
 *   This function turned out to be necessary while testing the lightbox upload facility
 *   with the new, stricter session checking: the simple 'feature not allowed' message
 *   may be nice and dandy, but it essentially blocked us from easily logging in. Only
 *   when we entered the login page (lib/includes/auth.inc.php) did we get a chance to
 *   enter the site again -- because our existing session was destroyed as part of the
 *   failed SID/SIDCHK test.
 *   That doesn't make the test faulty, on the contrary, but we'd rather not get a DoS
 *   this easily. ;-)
 */
function die_and_goto_url($url = null, $msg = null)
{
	global $cfg, $ccms;

	if (empty($msg))
	{
		$msg = $ccms['lang']['auth']['featnotallowed'];
	}
	if (empty($url))
	{
		$url = $cfg['rootdir'] . 'lib/includes/auth.inc.php';
	}
	header('Location: ' . makeAbsoluteURI($url . (strpos($url, '?') > 0 ? '&' : '?') .'status=error&msg=' . rawurlencode($msg)));
	exit();
}






/*---------------------------------------------------------------------
 * See also
 *
 *   http://nl2.php.net/manual/en/function.glob.php
 */


/**
 * Prepends $string to each element of $array
 * If $deep is true, will indeed also apply to sub-arrays
 * @author BigueNique AT yahoo DOT ca
 * @since 080324
 */
function array_prepend($array, $string, $deep=false)
{
	if (empty($array) || empty($string))
		return $array;
	foreach($array as $key => $element)
	{
		if(is_array($element))
		{
			if($deep)
			{
				$array[$key] = array_prepend($element,$string,$deep);
			}
			else
			{
				trigger_error('array_prepend: array element',E_USER_WARNING);
			}
		}
		else
		{
			$array[$key] = $string.$element;
		}
	}
	return $array;
}

/**
 * A better "fnmatch" alternative for windows that converts a fnmatch
 * pattern into a preg one. It should work on PHP >= 4.0.0.
 * @author soywiz at php dot net
 * @since 17-Jul-2006 10:12
 */
if (!function_exists('fnmatch'))
{
	function fnmatch($pattern, $string)
	{
		return preg_match('/^' . strtr(addcslashes($pattern, '\\.+^$(){}=!<>|'), array('*' => '.*', '?' => '.?')) . '$/i', $string);
	}
}


/**#@+
 * Extra GLOB constant for safe_glob()
 */
define('GLOB_NODIR',256);
define('GLOB_PATH',512);
define('GLOB_NODOTS',1024);
define('GLOB_RECURSE',2048);
/**#@-*/

/**
 * A safe empowered glob().
 *
 * Function glob() is prohibited on some server (probably in safe mode)
 * (Message "Warning: glob() has been disabled for security reasons in
 * (script) on line (line)") for security reasons as stated on:
 * http://seclists.org/fulldisclosure/2005/Sep/0001.html
 *
 * safe_glob() intends to replace glob() using readdir() & fnmatch() instead.
 * Supported flags: GLOB_MARK, GLOB_NOSORT, GLOB_ONLYDIR
 * Additional flags: GLOB_NODIR, GLOB_PATH, GLOB_NODOTS, GLOB_RECURSE
 * (not original glob() flags)
 *
 * @author BigueNique AT yahoo DOT ca
 * @updates
 * - 080324 Added support for additional flags: GLOB_NODIR, GLOB_PATH,
 *   GLOB_NODOTS, GLOB_RECURSE
 */
function safe_glob($pattern, $flags = 0)
{
	$split=explode('/',str_replace('\\','/',$pattern));
	$mask=array_pop($split);
	$path=implode('/',$split);
	if (($dir=opendir($path))!==false)
	{
		$glob=array();
		while(($file=readdir($dir))!==false)
		{
			// Recurse subdirectories (GLOB_RECURSE); speedup: no need to sort the intermediate results
			if (($flags&GLOB_RECURSE) && is_dir($file) && (!in_array($file,array('.','..'))))
			{
				$glob = array_merge($glob, array_prepend(safe_glob($path.'/'.$file.'/'.$mask, $flags | GLOB_NOSORT), ($flags&GLOB_PATH?'':$file.'/')));
			}
			// Match file mask
			if (fnmatch($mask,$file))
			{
				if ( ( (!($flags&GLOB_ONLYDIR)) || is_dir($path.'/'.$file) )
				&& ( (!($flags&GLOB_NODIR)) || (!is_dir($path.'/'.$file)) )
				&& ( (!($flags&GLOB_NODOTS)) || (!in_array($file,array('.','..'))) ) )
				{
					$glob[] = ($flags&GLOB_PATH?$path.'/':'') . $file . (($flags&GLOB_MARK) && is_dir($path.'/'.$file) ? '/' : '');
				}
			}
		}
		closedir($dir);
		if (!($flags&GLOB_NOSORT)) sort($glob);
		return $glob;
	}
	else
	{
		return false;
	}
}






/**
 * Derived from legolas558 d0t users dot sf dot net comments at
 *   http://nl2.php.net/manual/en/function.is-writable.php
 *
 * ---
 *
 * Detect whether a file or directory is writable for the current user.
 *
 * Will work despite the Windows ACLs bug:
 * see http://bugs.php.net/bug.php?id=27609
 * see http://bugs.php.net/bug.php?id=30931
 */
function is_writable_ex($path)
{
	if (is_dir($path))
	{
		// try to write a temp file in this directory
		$t = substr($path, -1, 1);
		if ($t != '/' && $t != '\\')
		{
			$path .= '/';
		}
		do
		{
			$filepath = $path . uniqid(mt_rand()).'.tmp';
		} while (@file_exists($filepath));

		$path = $filepath;
		$f = @fopen($path, 'w');
		if ($f===false)
		{
			return false;
		}
		fclose($f);
		unlink($path);
		return true;
	}
	if (is_file($path))
	{
		// check file for read/write capabilities
		$f = @fopen($path, 'r+');
		if ($f===false)
			return false;
		fclose($f);
		return true;
	}
	return false;
}







function recrmdir($dir) 
{
	if (is_dir($dir)) 
	{
		$objects = scandir($dir);
		
		foreach ($objects as $object) 
		{
			if ($object != "." && $object != "..") 
			{
				if (is_dir($dir."/".$object)) 
				{
					recrmdir($dir."/".$object); 
				}
				else 
				{
					@unlink($dir."/".$object);
				}
			}
		}
		reset($objects);
		@rmdir($dir);
	} 
	return true;
}










/**
 * Return an array:
 *
 * ["name"] - friendly local name for the language
 * ["lang"] - language code - 2 characters
 * ["locale"] - locale code - 3 characters
 */
function setLanguage($language)
{
	switch ($language)
	{
	default:    /* fallback: English */
	case 'en':
	case 'eng':
		$language = 'en'; $locale = 'eng';
		$name = "English";
		break;
	case 'de':
	case 'deu':
		$language = 'de'; $locale = 'deu';
		$name = "Deutsch";
		break;
	case 'it':
	case 'ita':
		$language = 'it'; $locale = 'ita';
		$name = "italiano";
		break;
	case 'nl':
	case 'nld':
		$language = 'nl'; $locale = 'nld';
		$name = "Nederlands";
		break;
	case 'ru':
	case 'rus':
		$language = 'ru'; $locale = 'rus';
		$name = "русский";
		break;
	case 'sv':
	case 'sve':
		$language = 'sv'; $locale = 'sve';
		$name = "svenska";
		break;
	case 'fr':
	case 'fra':
		$language = 'fr'; $locale = 'fra';
		$name = "français";
		break;
	case 'es':
	case 'esp':
		$language = 'es'; $locale = 'esp';
		$name = "español (castellano)";
		break;
	case 'pt':
	case 'por':
		$language = 'pt'; $locale = 'por';
		$name = "Português";
		break;
	case 'tr':
	case 'tur':
		$language = 'tr'; $locale = 'tur';
		$name = "Türk";
		break;
	case 'zh': /* Chinese ('simplified' is assumed as we only support 2-char codes here... */
	case 'zho':
	case 'chi':
		$language = 'zh'; $locale = 'zho';
		$name = "中文";
		break;
	case 'ar':
	case 'ara':
		$language = 'ar'; $locale = 'ara';
		$name = "العربية";
		break;
	case 'ja':
	case 'jpn':
		$language = 'ja'; $locale = 'jpn';
		$name = "日本";
		break;
	case 'ko':
	case 'kor':
		$language = 'ko'; $locale = 'kor';
		$name = "한국어";
		break;
	case 'hi': /* Hindi: India */
	case 'hin':
		$language = 'hi'; $locale = 'hin';
		$name = "हिन्दी";
		break;
	case 'pl':
	case 'pol':
		$language = 'pl'; $locale = 'pol';
		$name = "Polska";
		break;
	}

	return array('name' => $name, 'lang' => $language, 'locale' => $locale);
}



/*
 * You may specify a 2-char language code OR a 3-char 'locale' code
 * to set up the proper language files and settings.
 *
 * See also ISO639-2, e.g. http://www.loc.gov/standards/iso639-2/php/code_list.php
 *
 * Further info can be gathered at these locations:
 *
 *   http://en.wikipedia.org/wiki/Locale
 *   http://en.wikipedia.org/wiki/BCP_47
 *   http://tools.ietf.org/rfc/bcp/bcp47.txt
 *   http://www.w3.org/International/articles/language-tags/Overview.en.php
 *   http://www.loc.gov/standards/iso639-2/php/code_list.php
 *   http://www.science.co.il/language/locale-codes.asp
 *
 * and for /country codes/ (which we don't use here!) there's ISO3166:
 *
 *   http://en.wikipedia.org/wiki/ISO_3166-1_alpha-2
 */
function SetUpLanguageAndLocale($language, $only_set_cfg_array = false)
{
	global $cfg;
	global $ccms; // <-- this one will be augmented by the (probably) loaded language file(s).

	// Translate 2 character code to setlocale compliant code
	//
	// ALSO fix the 2-char language code if it is unknown (security + consistancy measure)!
	$a = setLanguage($language);
	$language = $a['lang'];
	$locale = $a['locale'];

	// Either select the specified language file or fall back to English
	$langfile = BASE_PATH . '/lib/languages/'.$language.'.inc.php';
	if(is_file($langfile))
	{
		// only load language files when the current language has not been loaded just before.
		if (!isset($cfg['language']) || $language !== $cfg['language'])
		{
			/*MARKER*/require($langfile);
		}
	}
	else
	{
		$language = 'en';
		$locale = 'eng';
		// only load language files when the current language has not been loaded just before.
		if ($language !== $cfg['language'])
		{
			/*MARKER*/require(BASE_PATH . '/lib/languages/en.inc.php');
		}
	}

	if (!$only_set_cfg_array)
	{
		// Set local for time, currency, etc
		setlocale(LC_ALL, $locale);
	}


	// core language
	$mce_langfile = array();
	$mce_langfile[] = BASE_PATH . '/lib/includes/js/tiny_mce/langs/'.$language.'.js';
	if (0)
	{
		// themes language
		$dirlist = safe_glob(BASE_PATH . '/lib/includes/js/tiny_mce/themes/*', GLOB_NODOTS | GLOB_PATH | GLOB_ONLYDIR);
		foreach($dirlist as $dir)
		{
			$mce_langfile[] = $dir . '/langs/'.$language.'.js';
			$mce_langfile[] = $dir . '/langs/'.$language.'_dlg.js';
		}
		// plugins language
		$dirlist = safe_glob(BASE_PATH . '/lib/includes/js/tiny_mce/plugins/*', GLOB_NODOTS | GLOB_PATH | GLOB_ONLYDIR);
		foreach($dirlist as $dir)
		{
			$mce_langfile[] = $dir . '/langs/'.$language.'.js';
			$mce_langfile[] = $dir . '/langs/'.$language.'_dlg.js';
		}
	}
	$mce_has_lang = true;
	foreach($mce_langfile as $file)
	{
		if (!is_file($file))
		{
			$mce_has_lang = false;
			break;
		}
	}

	if ($mce_has_lang)
	{
		$cfg['tinymce_language'] = 'en'; // $language;   -- for some reason, tinyMCE fails to load when anything other than English is specified. Some obscure crash inside a !@#$%^&* eval() in there. :-(((
	}
	else
	{
		$cfg['tinymce_language'] = 'en';
	}

	$editarea_langfile = BASE_PATH . '/lib/includes/js/edit_area/langs/'.$language.'.js';
	if (is_file($editarea_langfile))
	{
		$cfg['editarea_language'] = $language;
	}
	else
	{
		$cfg['editarea_language'] = 'en';
	}

	$fancyupload_langfile = BASE_PATH . '/lib/includes/js/fancyupload/Language/Language.'.$language.'.js';
	if (is_file($fancyupload_langfile))
	{
		$cfg['fancyupload_language'] = $language;
	}
	else
	{
		$cfg['fancyupload_language'] = 'en';
	}

	$cfg['language'] = $language;
	$cfg['locale'] = $locale;
//echo "<pre>";
//var_dump($cfg);
//echo "</pre>\n";
	return $language;
}


/**
 * Collect the available languages (translations) and return those in an array.
 */
function GetAvailableLanguages()
{
	$sl = array();
	if ($handle = opendir(BASE_PATH . '/lib/languages'))
	{
		while (false !== ($file = readdir($handle)))
		{
			// Filter out irrelevant files && dirs
			if ($file != "." && $file != ".." && strmatch_tail($file, ".inc.php"))
			{
				$f = substr($file,0,strpos($file, '.'));
				$sl[$f] = setLanguage($f);
				// making sure language support is indeed in sync in code and definition files:
				if ($sl[$f]['lang'] != $f)
				{
					die("CCMS code has not been updated to support language: language code=" . $f);
				}
			}
		}
	}
	return $sl;
}








/**
 * Return the list of fields as indicated by the 'ordercode' parameter
 * as an array.
 *
 * Can, for example, be used to pass this set in the 'ordering' argument for any SQL query.
 *
 * @param $ordercode   A character series determining the generated field order:
 *
 *                     Code        Field Name
 *
 *                     F           urlpage
 *                     T           pagetitle
 *                     S           subheader
 *                     D           description
 *                     P           printable
 *                     A           published
 *                     C           iscoding
 *                     H           islink
 *                     I           menu_id
 *                     1           toplevel
 *                     2           sublevel
 *                     M           module
 *                     L           variant
 *                     0           page_id
 */
function cvt_ordercode2list($ordercode)
{
	$dlorder = array();
	$ordermask = 0x3FFF; // FTSDPACHIM12L0: 14 bits
	for ($i = 0; $i < strlen($ordercode); $i++)
	{
		$c = $ordercode[$i];

		switch (strtoupper($c))
		{
		case 'F':
			if ($ordermask & 0x0001)
			{
				$ordermask &= ~0x0001;
				$dlorder[] = 'urlpage';
			}
			break;

		case 'T':
			if ($ordermask & 0x0002)
			{
				$ordermask &= ~0x0002;
				$dlorder[] = 'pagetitle';
			}
			break;

		case 'S':
			if ($ordermask & 0x0004)
			{
				$ordermask &= ~0x0004;
				$dlorder[] = 'subheader';
			}
			break;

		case 'D':
			if ($ordermask & 0x0008)
			{
				$ordermask &= ~0x0008;
				$dlorder[] = 'description';
			}
			break;

		case 'P':
			if ($ordermask & 0x0010)
			{
				$ordermask &= ~0x0010;
				$dlorder[] = 'printable';
			}
			break;

		case 'A':
			if ($ordermask & 0x0020)
			{
				$ordermask &= ~0x0020;
				$dlorder[] = 'published';
			}
			break;

		case 'C':
			if ($ordermask & 0x0040)
			{
				$ordermask &= ~0x0040;
				$dlorder[] = 'iscoding';
			}
			break;

		case 'H':
			if ($ordermask & 0x0080)
			{
				$ordermask &= ~0x0080;
				$dlorder[] = 'islink';
			}
			break;

		case 'I':
			if ($ordermask & 0x0100)
			{
				$ordermask &= ~0x0100;
				$dlorder[] = 'menu_id';
			}
			break;

		case '1':
			if ($ordermask & 0x0200)
			{
				$ordermask &= ~0x0200;
				$dlorder[] = 'toplevel';
			}
			break;

		case '2':
			if ($ordermask & 0x0400)
			{
				$ordermask &= ~0x0400;
				$dlorder[] = 'sublevel';
			}
			break;

		case 'M':
			if ($ordermask & 0x0800)
			{
				$ordermask &= ~0x0800;
				$dlorder[] = 'module';
			}
			break;

		case 'L':
			if ($ordermask & 0x1000)
			{
				$ordermask &= ~0x1000;
				$dlorder[] = 'variant';
			}
			break;

		case '0':
			if ($ordermask & 0x2000)
			{
				$ordermask &= ~0x2000;
				$dlorder[] = 'page_id';
			}
			break;

		default:
			// should never get here...
			break;
		}
	}
	return $dlorder;
}












/**
 * Check for authentic request ($cage=md5(SESSION_ID),$host=md5(CURRENT_HOST)) v.s. 'id' and 'host' session variable values.
 *
 * This is a basic check to protect against some forms of CSRF attacks. An extended check using the additional 'rc1'/'rc2' session
 * variables must be used to validate form submissions to ensure the transmission follows such a web form immediately.
 */
function checkAuth()
{
	/*
	 * The remaining MD5 call in here is NOT for security but to keep session storage tiny: the collected data
	 * is packed in a fixed, limited number of characters. Meanwhile, MD5 is still quite good enough to hash this
	 * very limited entropy data anyhow. No loss, just a little gain.
	 */
	$canarycage = session_id();
	$currenthost = md5($_SERVER['HTTP_HOST'] . '::' . $_SERVER['REMOTE_ADDR'] /* . '::' . $_SERVER['HTTP_USER_AGENT'] */ );

	if (!empty($_SESSION['id']) && $canarycage == $_SESSION['id']
		&& !empty($_SESSION['authcheck']) && $currenthost == $_SESSION['authcheck'])
	{
		return true;
	}
	return false;
}

function SetAuthSafety()
{
	$_SESSION['authcheck'] = md5($_SERVER['HTTP_HOST'] . '::' . $_SERVER['REMOTE_ADDR'] /* . '::' . $_SERVER['HTTP_USER_AGENT'] */ );
	$_SESSION['id'] = session_id();  // superfluous, but we'll keep this in here for now, just to be on the safe side.

	unset($_SESSION['rc1']);
	unset($_SESSION['rc2']);
}


function GenerateNewAuthCode()
{
	if (function_exists('openssl_random_pseudo_bytes'))
	{
		$bytes = openssl_random_pseudo_bytes(16, $cryptstrong);
		$code = bin2hex($bytes);
	}
	else
	{
		$code = mt_rand('12345','98765');
	}
	return $code;
}

function GenerateNewPreviewCode($page_id = null, $page_name = null, $this_run_is_checking_instead = false)
{
	global $db, $cfg;

	// grab the page record for the original entry page for this preview
	$filter = array();
	if (!empty($page_id))
	{
		$filter['page_id'] = MySQL::SQLValue($page_id, MySQL::SQLVALUE_NUMBER);
	}
	if (!empty($page_name))
	{
		$filter['urlpage'] = MySQL::SQLValue($page_name, MySQL::SQLVALUE_TEXT);
	}
	// we MUST have at least one search filter criterium by now:
	if (count($filter) < 1)
		return false;

	$pagerec = $db->SelectSingleRowArray($cfg['db_prefix'].'pages', $filter);
	if ($pagerec === false)
		return false;

	if (!$this_run_is_checking_instead)
	{
		// force 'published' to be NO to help limit the lifetime of the previewCode: see also the 'Postnatal notes' section in _docs/security_how_to_for_devs.html.
		$pagerec['published'] = 'N';
	}
	$data = implode('::', $pagerec);
	$preview_checkcode = $pagerec['page_id'] . '-' . md5($cfg['authcode'] . '::' . $data);
	return $preview_checkcode;
}


/**
 * Return FALSE when the specified preview code is invalid; otherwise return the page number encoded with the preview code.
 *
 * Note: as the page number will NEVER be zero, you can simply check for a valid preview code (if that's all you need) by
 *       comparing the function return value like this:
 *
 *         if (IsValidPreviewCode($code)) { ... }
 */
function IsValidPreviewCode($previewCode)
{
	if (empty($previewCode))
		return false;

	$code = explode('-', $previewCode, 2);
	if (!is_array($code) || count($code) != 2 || !is_numeric($code[0]))
		return false;

	$orig_page_id = $code[0];

	// regenerate the preview code as it should be and compare that one with what we've actually got:
	$sollwert = GenerateNewPreviewCode($orig_page_id, null, true);
	if ($sollwert === false)
		return false;

	return ($sollwert === $previewCode ? $orig_page_id : false);
}







function get_tinyMCE_plugin_list()
{
	/*
	 * available plugins:
	 *   advhr
	 *   advimage
	 *   advlink
	 *   advlist
	 *   autolink
	 *   autoresize
	 *   autosave
	 *   bbcode
	 *   contextmenu
	 *   directionality
	 *   emotions
	 *   example
	 *   fullpage
	 *   fullscreen
	 *   iespell
	 *   inlinepopups
	 *   insertdatetime
	 *   layer
	 *   legacyoutput
	 *   lists
	 *   media
	 *   nonbreaking
	 *   noneditable
	 *   pagebreak
	 *   paste
	 *   preview
	 *   print
	 *   save
	 *   searchreplace
	 *   spellchecker
	 *   style
	 *   tabfocus
	 *   table
	 *   template
	 *   visualchars
	 *   wordcount
	 *   xhtmlxtras
	 */
	static $mce_plugins = array(
		'advhr' => 1,
		'advimage' => 1,
		'advlink' => 1,
		'advlist' => 1,
		'autolink' => 0,
		'autoresize' => 0,
		'autosave' => 1,
		'bbcode' => 0,
		'contextmenu' => 0,
		'directionality' => 0,
		'emotions' => 0,
	    'example' => 0,
		'fullpage' => 0,
		'fullscreen' => 1,
		'iespell' => 0,
		'inlinepopups' => 1,
		'insertdatetime' => 0,
		'layer' => 0,
		'legacyoutput' => 0,
		'lists' => 1,
		'media' => 1,
		'nonbreaking' => 1,
		'noneditable' => 0,
		'pagebreak' => 1,
		'paste' => 1,
		'preview' => 1,
		'print' => 1,
		'save' => 0,
		'searchreplace' => 1,
		'spellchecker' => 1,
		'style' => 1,
		'tabfocus' => 0,
		'table' => 1,
		'template' => 0,
		'visualchars' => 1,
		'wordcount' => 0,
		'xhtmlxtras' => 0
		);

	$rv = array();
	foreach ($mce_plugins as $plugin => $in_use)
	{
		if (!$in_use) continue;

		$rv[] = $plugin;
	}
	return $rv;
}





function get_tinyMCE_button_list($plugin = null)
{
	/*
	 * available plugins:
	 *   advhr
	 *   advimage
	 *   advlink
	 *   advlist
	 *   autolink
	 *   autoresize
	 *   autosave
	 *   bbcode
	 *   contextmenu
	 *   directionality
	 *   emotions
	 *   example
	 *   fullpage
	 *   fullscreen
	 *   iespell
	 *   inlinepopups
	 *   insertdatetime
	 *   layer
	 *   legacyoutput
	 *   lists
	 *   media
	 *   nonbreaking
	 *   noneditable
	 *   pagebreak
	 *   paste
	 *   preview
	 *   print
	 *   save
	 *   searchreplace
	 *   spellchecker
	 *   style
	 *   tabfocus
	 *   table
	 *   template
	 *   visualchars
	 *   wordcount
	 *   xhtmlxtras
	 */
	static $mce_plugin_buttons = array(
		'advhr' => 'advhr',  // search for .addButton() invocations in the plugins to dig out the button names
		'advimage' => 'image',
		'advlink' => 'link',
		'advlist' => '',
		'autolink' => '',
		'autoresize' => '',
		'autosave' => 'restoredraft',
		'bbcode' => '',
		'contextmenu' => '',
		'directionality' => 'ltr,rtl',
		'emotions' => 'emotions',
	    'example' => 'example',
		'fullpage' => 'fullpage',
		'fullscreen' => 'fullscreen',
		'iespell' => 'iespell',
		'inlinepopups' => '',
		'insertdatetime' => 'insertdate,inserttime',
		'layer' => 'insertlayer,moveforward,movebackward,absolute',
		'legacyoutput' => '',
		'lists' => '',
		'media' => 'media',
		'nonbreaking' => 'nonbreaking',
		'noneditable' => 'noneditable',
		'pagebreak' => 'pagebreak',
		'paste' => 'selectall,pastetext,pasteword',
		'preview' => 'preview',
		'print' => 'print',
		'save' => 'save,cancel',
		'searchreplace' => 'search,replace',
		'spellchecker' => 'spellchecker',
		'style' => 'styleprops',
		'tabfocus' => '',
		'table' => 'table,delete_table,delete_col,delete_row,col_after,col_before,row_after,row_before,row_props,cell_props,split_cells,merge_cells',
		'template' => 'template',
		'visualchars' => 'visualchars',
		'wordcount' => '',
		'xhtmlxtras' =>'cite,acronym,abbr,del,ins,attribs',
		'.1' => 'newdocument,|,bold,italic,underline,strikethrough,|,justifyleft,justifycenter,justifyright,justifyfull',
		'.2' => 'styleselect,formatselect,fontselect,fontsizeselect,|,forecolor,forecolorpicker,backcolor,backcolorpicker',
		'.3' => 'bullist,numlist,|,outdent,indent,|,cut,copy,paste,|,undo,redo,|,link,unlink,anchor,image,|,cleanup,shortcuts,code,help',
		'.4' => 'sub,sup,|,blockquote,hr,removeformat,visualaid,|,charmap'
		);

	$active_plugins = null;
	if (is_array($plugin))
	{
		$active_plugins = $plugin;
	}
	else if (!empty($plugin))
	{
		$active_plugins = explode(',', strval($plugin));
	}
	else
	{
		$active_plugins = get_tinyMCE_plugin_list();
	}
	
	$rv = array();
	foreach ($mce_plugin_buttons as $plugin => $buttons)
	{
		if (!in_array($plugin, $active_plugins)) continue;

		$rv[$plugin] = $buttons;
	}
	return $rv;
}





/**
Generate the load code sections for tinyMCE:

state == 0: the lazyload filespec

state == 1: the PREinit code section

state == 2: the init() code section
*/
function generateJS4tinyMCEinit($state, $editarea_tag, $with_fancyupload = true, $js_load_callback = 'jsComplete')
{
	global $cfg;

	switch ($state)
	{
	default:
		return false;
		
	case 0:
		// pick one of these: tiny_mce_dev.js (which will lazyload all tinyMCE parts recursively) or tiny_mce_full.js (the 'flattened' tinyMCE source) - the latter is tiny_mce_src.js plus all the plugins merged in
		if ($cfg['USE_JS_DEVELOPMENT_SOURCES'])
		{
			$rv = "'" . $cfg['rootdir'] . "lib/includes/js/tiny_mce/tiny_mce_ccms.js,tiny_mce_dev.js'";
		}
		else
		{
			$rv = "'" . $cfg['rootdir'] . "lib/includes/js/tiny_mce/tiny_mce_ccms.js,tiny_mce_full.js'";
		}
		if ($with_fancyupload)
		{
			/* File uploader JS */
			$rv .= ",\n";
			$rv .= "'" . $cfg['rootdir'] . "lib/includes/js/fancyupload/dummy.js,Source/FileManager.js,";
			if ($cfg['fancyupload_language'] != 'en')
			{
				$rv .= "Language/Language.en.js,";
			}
			$rv .= "Language/Language." . $cfg['fancyupload_language'] . ".js,Source/Additions.js,Source/Uploader/Fx.ProgressBar.js,Source/Uploader/Swiff.Uploader.js,Source/Uploader.js,Source/FileManager.TinyMCE.js'";
		}
		return $rv;
	
	case 1:
		/*
		 * when loading the flattened tinyMCE JS, this is (almost) identical to invoking the lazyload-done hook 'jsComplete()';
		 * however, tinyMCE 'dev' sources (tiny_mce_dev.js) employs its own lazyload-similar system, so having loaded /that/
		 * file does /NOT/ mean that the tinyMCE editor has been loaded completely, on the contrary!
		 */
		$rv = "";
		$rv .= "tinyMCEPreInit = {\n";
		$rv .= "	  suffix: '_src'\n"; /* '_src' when you load the _src or _dev version, '' when you want to load the stripped+minified version of tinyMCE plugins */
		$rv .= "	, base: '" . $cfg['rootdir'] . "lib/includes/js/tiny_mce'\n";
		$rv .= "	, query: 'load_callback=" . $js_load_callback . "'\n"; /* specify a URL query string, properly urlescaped, to pass special arguments to tinyMCE, e.g. 'api=jquery'; must have an 'adapter' for that one, 'debug=' to add tinyMCE firebug-lite debugging code */
		$rv .= "};\n";
		return $rv;
		
	case 2:
		$rv = "";
		// var has_mocha = (parent && parent.MochaUI && (typeof parent.$ == 'function'));
		$rv .= "var dimensions;\n";
		$rv .= "var editwinwidth;\n";
		$rv .= "dimensions = window.getSize();\n";
		$rv .= "editwinwidth = dimensions.x - 20;\n";
		$rv .= "dimensions = \$('" . $editarea_tag . "').getSize();\n";
		$rv .= "editwinwidth = dimensions.x;\n";
		//$rv .= "alert('width: ' + editwinwidth + 'px');\n";
		$rv .= "\n";
		$rv .= "tinyMCE.init(\n";
		$rv .= "	{\n";
		$rv .= "		mode: 'exact',\n";
		$rv .= "		elements: '" . $editarea_tag . "',\n";
		$rv .= "		theme: 'advanced',\n";
		$rv .= "		language: '" . $cfg['tinymce_language'] ."',\n";
		$rv .= "		skin: 'o2k7',\n";
		$rv .= "		skin_variant: 'silver',\n";
		
		$pluginarr = get_tinyMCE_plugin_list();
		$pstr = implode(',', $pluginarr);
		
		$rv .= "		plugins: '" . $pstr . "',\n";
		$rv .= "		theme_advanced_toolbar_location: 'top',\n";
		
		$rv .= "		theme_advanced_buttons1 : 'fullscreen,restoredraft,print,|,bold,italic,underline,strikethrough,|,justifyleft,justifycenter,justifyright,justifyfull,|,styleselect,formatselect,fontselect,fontsizeselect',\n";
		$rv .= "		theme_advanced_buttons2 : 'cut,copy,paste,pastetext,pasteword,|,search,replace,|,bullist,numlist,|,outdent,indent,blockquote,|,undo,redo,|,link,unlink,anchor,image,cleanup,help,code,|,insertdate,inserttime,preview,|,forecolor,forecolorpicker,backcolor,backcolorpicker',\n";
		$rv .= "		theme_advanced_buttons3 : 'tablecontrols,|,hr,removeformat,visualaid,|,sub,sup,|,charmap,emotions,spellchecker,media,advhr,|,print,|,ltr,rtl',\n"; /* iespell */
		$rv .= "		theme_advanced_buttons4 : 'insertlayer,moveforward,movebackward,absolute,|,styleprops,|,cite,abbr,acronym,del,ins,attribs,|,visualchars,nonbreaking,template,pagebreak',\n";
		
		$rv .= "		theme_advanced_toolbar_align: 'left',\n";
		$rv .= "		theme_advanced_statusbar_location: 'bottom',\n";
		$rv .= "		dialog_type: 'modal',\n";
		$rv .= "		paste_auto_cleanup_on_paste: true,\n";
		$rv .= "		theme_advanced_resizing: true,\n";  /* This bugger is responsible for resizing (on init!) the edit window, due to a lingering cookie when you've used the same edit window in a browser tab and a mochaUI window */
		$rv .= "		theme_advanced_resize_horizontal : 1,\n";
		$rv .= "		theme_advanced_resizing_use_cookie : 1,\n";
		$rv .= "		theme_advanced_resize_horizontal: false,\n";
		$rv .= "		theme_advanced_resizing_min_width: 400,\n";
		$rv .= "		theme_advanced_resizing_min_height: 100,\n";
		$rv .= "		theme_advanced_resizing_max_width: editwinwidth,\n"; /* limit the width to ensure the width NEVER surpasses that of the mochaUI window, IFF we are in one... */
		$rv .= "		theme_advanced_resizing_max_height: 0xFFFF,\n";
		$rv .= "		relative_urls: true,\n";
		$rv .= "		convert_urls: false,\n";
		$rv .= "		remove_script_host: true,\n";
		$rv .= "		document_base_url: '" . $cfg['rootdir'] . "',\n";
		if($cfg['iframe'])
		{
			$rv .= "		extended_valid_elements: 'iframe[align<bottom?left?middle?right?top|class|frameborder|height|id|longdesc|marginheight|marginwidth|name|scrolling<auto?no?yes|src|style|title|width]',\n";
		}
 		$rv .= "		spellchecker_languages: '+English=en,Dutch=nl,German=de,Spanish=es,French=fr,Italian=it,Russian=ru',\n";
		if ($with_fancyupload)
		{
			$rv .= "		file_browser_callback: FileManager.TinyMCE(\n";
			$rv .= "			function(type)\n";
			$rv .= "			{\n";
			$rv .= "				return {\n"; /* ! '{' MUST be on same line as 'return' otherwise JS will see the newline as end-of-statement! */
			$rv .= "					url: '" . $cfg['rootdir'] . "lib/includes/js/fancyupload/' + (type=='image' ? 'selectImage.php' : 'manager.php'),\n";
			$rv .= "					baseURL: '" . $cfg['rootdir'] . "',\n";
			$rv .= "					assetBasePath: '" . $cfg['rootdir'] . "lib/includes/js/fancyupload/Assets',\n";
			$rv .= "					language: '" . $cfg['fancyupload_language'] . "',\n";
			$rv .= "					selectable: true,\n";
			$rv .= "					uploadAuthData: {\n";
			$rv .= "						session: 'ccms_userLevel',\n";
			$rv .= "						sid: '" . session_id() . "'\n";
			$rv .= "					}\n";
			$rv .= "				};\n";
			$rv .= "			}),\n";
		}
		$rv .= "		width: editwinwidth,\n"; // default: width in pixels
		//$rv .= "		height: '300px',\n";
		$rv .= "	});\n";
		return $rv;

		/*
		plugins : "pagebreak,style,layer,table,save,advhr,advimage,advlink,emotions,iespell,inlinepopups,insertdatetime,preview,media,searchreplace,print,contextmenu,paste,directionality,fullscreen,noneditable,visualchars,nonbreaking,xhtmlxtras,template,wordcount,advlist,autosave",

		// Theme options
		theme_advanced_buttons1 : "save,newdocument,|,bold,italic,underline,strikethrough,|,justifyleft,justifycenter,justifyright,justifyfull,styleselect,formatselect,fontselect,fontsizeselect",
		theme_advanced_buttons2 : "cut,copy,paste,pastetext,pasteword,|,search,replace,|,bullist,numlist,|,outdent,indent,blockquote,|,undo,redo,|,link,unlink,anchor,image,cleanup,help,code,|,insertdate,inserttime,preview,|,forecolor,backcolor",
		theme_advanced_buttons3 : "tablecontrols,|,hr,removeformat,visualaid,|,sub,sup,|,charmap,emotions,iespell,media,advhr,|,print,|,ltr,rtl,|,fullscreen",
		theme_advanced_buttons4 : "insertlayer,moveforward,movebackward,absolute,|,styleprops,|,cite,abbr,acronym,del,ins,attribs,|,visualchars,nonbreaking,template,pagebreak,restoredraft",
		theme_advanced_toolbar_location : "top",
		theme_advanced_toolbar_align : "left",
		theme_advanced_statusbar_location : "bottom",
		theme_advanced_resizing : true,

		// Example content CSS (should be your site CSS)
		content_css : "css/content.css",

		// Drop lists for link/image/media/template dialogs
		template_external_list_url : "lists/template_list.js",
		external_link_list_url : "lists/link_list.js",
		external_image_list_url : "lists/image_list.js",
		media_external_list_url : "lists/media_list.js",

		// Style formats
		style_formats : [
			{title : 'Bold text', inline : 'b'},
			{title : 'Red text', inline : 'span', styles : {color : '#ff0000'}},
			{title : 'Red header', block : 'h1', styles : {color : '#ff0000'}},
			{title : 'Example 1', inline : 'span', classes : 'example1'},
			{title : 'Example 2', inline : 'span', classes : 'example2'},
			{title : 'Table styles'},
			{title : 'Table row 1', selector : 'tr', classes : 'tablerow1'}
		],

		// Replace values for the template plugin
		template_replace_values : {
			username : "Some User",
			staffid : "991234"
		}



		// Patch callbacks, make them point to window.opener
		patchCallback(settings, 'urlconverter_callback');
		patchCallback(settings, 'insertlink_callback');
		patchCallback(settings, 'insertimage_callback');
		patchCallback(settings, 'setupcontent_callback');
		patchCallback(settings, 'save_callback');
		patchCallback(settings, 'onchange_callback');
		patchCallback(settings, 'init_instance_callback');
		patchCallback(settings, 'file_browser_callback');
		patchCallback(settings, 'cleanup_callback');
		patchCallback(settings, 'execcommand_callback');
		patchCallback(settings, 'oninit');

		// Set options
		delete settings.id;
		settings['mode'] = 'exact';
		settings['elements'] = 'fullscreenarea';
		settings['add_unload_trigger'] = false;
		settings['ask'] = false;
		settings['document_base_url'] = window.opener.tinyMCE.activeEditor.documentBaseURI.getURI();
		settings['fullscreen_is_enabled'] = true;
		settings['fullscreen_editor_id'] = oeID;
		settings['theme_advanced_resizing'] = false;
		settings['strict_loading_mode'] = true;
		*/
	}
}



?>