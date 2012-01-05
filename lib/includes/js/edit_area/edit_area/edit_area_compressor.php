<?php #! /usr/bin/php
//; /usr/bin/php $0 $@ ; exit 0;

/*  ^ can't use the shebang trick here as that would produce one more line
	of output when this file is run from a webserver, so we take the second-best
	approach, which is to favor the webserver and tolerate a few sh/bash error
	reports while it'll start the PHP CLI for us after all.

	Execute from the commandline (bash) like this, for example:

	$ ./edit_area_compressor.php plugins=1 compress=1


	Don't bother about these error reports then:

	./edit_area_compressor.php: line 1: ?php: No such file or directory
	./edit_area_compressor.php: line 2: //: is a directory
	PHP Deprecated:  Comments starting with '#' are deprecated in /etc/php5/cli/conf.d/idn.ini on line 1 in Unknown on line 0

	The latter shows up on Ubuntu 10.04 installs; again not to bother. The first two
	errors are due to our hack to help bash/sh kickstart the PHP interpreter after all,
	which is a bit convoluted as the shebang trick cannot happen for it would output
	the shebang line with the generated JavaScript when the script is executed by
	a web server. We don't that to happen, so we tolerate the error reports when running
	in a UNIX shell environment.
*/

	/******
	 *
	 *  EditArea PHP compressor
	 *  Developed by Christophe Dolivet
	 *  Released under LGPL, Apache and BSD licenses
	 *  v1.1.3 (2007/01/18)
	 *
	******/

if (0) // if (1) when you need to diagnose your environment
{
	echo "_SERVER:\n";
	var_dump($_SERVER);
	echo "_ENV:\n";
	var_dump($_ENV);
	echo "ours:\n";
	echo "\n argv[0] = " . (empty($argv[0]) ? "(NULL)" : $argv[0]);
	echo "\n SHELL = " . (!array_key_exists('SHELL', $_ENV) ? "(NULL)" : $_ENV['SHELL']);
	echo "\n SESSIONNAME = " . (!array_key_exists('SESSIONNAME', $_SERVER) ? "(NULL)" : $_SERVER['SESSIONNAME']);
	echo "\n HTTP_HOST = " . (!array_key_exists('HTTP_HOST', $_SERVER) ? "(NULL)" : $_SERVER['HTTP_HOST']);
	echo "\n QUERY_STRING = " . (!array_key_exists('QUERY_STRING', $_SERVER) ? "(NULL)" : $_SERVER['QUERY_STRING']);
	echo "\n REQUEST_METHOD = " . (!array_key_exists('REQUEST_METHOD', $_SERVER) ? "(NULL)" : $_SERVER['REQUEST_METHOD']);
	die();
}

/*
only run the compressor in here when executed from the commandline; otherwise we'll only
define the compressor class and wait for the other code out there to call us:

Tests turn out that $_SERVER['SESSIONNAME'] == 'Console' only on Windows machines, while
UNIX boxes don't need to present $_ENV['SHELL']. Hence this check to see whether we're
running from a console or crontab:

- argv[0] WILL be set when run from the command line (it should list our PHP file)
- $_SERVER['HTTP_HOST'] does NOT EXIST when run from the console
- $_SERVER['QUERY_STRING'] does NOT EXIST when run from the console (it may very well be EMPTY when run by the web server!)
- $_SERVER['REQUEST_METHOD'] does NOT EXIST when run from the console

Supported arguments when run from the commandline:

plugins           - generate the 'full_with_plugins' version instead of the 'full' one

you can also override any of the $param[] items like so, for example:

debug=0           - equals $params['debug'] = false;

debug=1           - equals $params['debug'] = true;

*/
if (!empty($argv[0]) && stristr($argv[0], '.php') !== false &&
	!array_key_exists('HTTP_HOST', $_SERVER) &&
	!array_key_exists('QUERY_STRING', $_SERVER) &&
	!array_key_exists('REQUEST_METHOD', $_SERVER))
{
	// CONFIG
	$param['cache_duration'] = 3600 * 24 * 10;      // 10 days util client cache expires
	$param['compress'] = false;                 // Enable the code compression, should be activated but it can be useful to deactivate it for easier error diagnostics (true or false)
	$param['debug'] = false;                        // Enable this option if you need debugging info
	$param['use_disk_cache'] = true;                // If you enable this option gzip files will be cached on disk.
	$param['use_gzip']= false;                      // Enable gzip compression
	$param['plugins'] = true; // isset($_GET['plugins']);    Include plugins in the compressed/flattened JS output.
	$param['echo2stdout'] = false;                  // Output generated JS to stdout; alternative is to store it in the object for later retrieval.
	$param['include_langs_and_syntaxes'] = true;    // Set to FALSE for backwards compatibility: do not include the language files and syntax definitions in the flattened output.
	// END CONFIG

	for ($i = 1; $i < $argc; $i++)
	{
		$arg = explode('=', $argv[$i], 2);
		$param[$arg[0]] = (isset($arg[1]) ? intval($arg[1]) : true);
	}
	$param['running_from_commandline'] = true;          // UNSET or FALSE when executed from a web server
	$param['verbose2stdout'] = !$param['echo2stdout'];  // UNSET or FALSE when executed from a web server

	if (!empty($param['verbose2stdout']))
	{
		echo "\nEditArea Compressor:\n";
		echo "Settings:\n";
		foreach($param as $key => $value)
		{
			echo sprintf("  %30s: %d\n", $key, $value);
		}
	}

	$compressor = new Compressor($param);
}

	class Compressor
	{
		//function compressor($param)
		//{
		//  $this->__construct($param);
		//}
		//
		//--> Strict Standards: Redefining already defined constructor for class Compressor

		function __construct($param)
		{
			$this->datas = false;
			$this->gzip_datas = false;
			$this->generated_headers = array();

			$this->start_time= $this->get_microtime();
			$this->file_loaded_size=0;
			$this->param= $param;
			$this->script_list="";
			$this->path= str_replace('\\','/',dirname(__FILE__)).'/';
			if($this->param['plugins'])
			{
				if (!empty($this->param['verbose2stdout'])) echo "\n\n\nGenerating output with plugins included\n";
				$this->load_all_plugins= true;
				$this->full_cache_file= $this->path."edit_area_full_with_plugins.js";
				$this->gzip_cache_file= $this->path."edit_area_full_with_plugins.gz";
			}
			else
			{
				if (!empty($this->param['verbose2stdout'])) echo "\n\n\nGenrating output WITHOUT plugins\n";
				$this->load_all_plugins= false;
				$this->full_cache_file= $this->path."edit_area_full.js";
				$this->gzip_cache_file= $this->path."edit_area_full.gz";
			}

			$this->check_gzip_use();
			$this->send_headers();
			$this->check_cache();
			$this->load_files();
			$this->send_datas();
		}


		/**
		 * Return the HTTP headers associated with the compressed/flattened output file as an array of header lines.
		 */
		public function get_headers()
		{
			return $this->generated_headers;
		}

		/**
		 * Return the generated flattened JavaScript as a string.
		 *
		 * Return FALSE on error.
		 */
		public function get_flattened()
		{
			return $this->datas;
		}

		/**
		 * Return the generated flattened and GZIPped JavaScript (as output by gzencode()).
		 *
		 * Return FALSE on error or when GZIPped JavaScript has not been generated.
		 */
		public function get_flattened_gzipped()
		{
			return $this->gzip_datas;
		}

		private function send_header1($headerline)
		{
			$this->generated_headers[] = $headerline;
			if ($this->param['echo2stdout'] && !headers_sent())
			{
				header($headerline);
			}
		}

		private function send_headers()
		{
			$this->send_header1("Content-type: text/javascript; charset: UTF-8");
			$this->send_header1("Vary: Accept-Encoding"); // Handle proxies
			$this->send_header1(sprintf("Expires: %s GMT", gmdate("D, d M Y H:i:s", time() + intval($this->param['cache_duration']))) );
			if($this->use_gzip)
			{
				$this->send_header1("Content-Encoding: ".$this->gzip_enc_header);
			}
		}

		private function check_gzip_use()
		{
			$encodings = array();
			$desactivate_gzip=false;

			if (isset($_SERVER['HTTP_ACCEPT_ENCODING']))
			{
				$encodings = explode(',', strtolower(preg_replace("/\s+/", "", $_SERVER['HTTP_ACCEPT_ENCODING'])));
			}

			// deactivate gzip for IE version < 7
			if (!isset($_SERVER['HTTP_USER_AGENT']))
			{
				// run from the commandline: do NOT use gzip
				$desactivate_gzip=true;
			}
			else if(preg_match("/(?:msie )([0-9.]+)/i", $_SERVER['HTTP_USER_AGENT'], $ie))
			{
				if($ie[1]<7)
					$desactivate_gzip=true;
			}

			// Check for gzip header or northon internet securities
			if (!$desactivate_gzip && $this->param['use_gzip'] && (in_array('gzip', $encodings) || in_array('x-gzip', $encodings) || isset($_SERVER['---------------'])) && function_exists('ob_gzhandler') && !ini_get('zlib.output_compression')) {
				$this->gzip_enc_header= in_array('x-gzip', $encodings) ? "x-gzip" : "gzip";
				$this->use_gzip=true;
				$this->cache_file=$this->gzip_cache_file;
			}else{
				$this->use_gzip=false;
				$this->cache_file=$this->full_cache_file;
			}
		}

		private function check_cache()
		{
			// Only gzip the contents if clients and server support it
			if ($this->param['use_disk_cache'] && file_exists($this->cache_file) && empty($this->param['running_from_commandline'])) {
				// check if cache file must be updated
				$cache_date=0;
				if ($dir = opendir($this->path)) {
					while (($file = readdir($dir)) !== false) {
						if(is_file($this->path.$file) && $file!="." && $file!="..")
							$cache_date= max($cache_date, filemtime($this->path.$file));
					}
					closedir($dir);
				}
				if($this->load_all_plugins){
					$plug_path= $this->path."plugins/";
					if (($dir = @opendir($plug_path)) !== false)
					{
						while (($file = readdir($dir)) !== false)
						{
							if ($file !== "." && $file !== "..")
							{
								if(is_dir($plug_path.$file) && file_exists($plug_path.$file."/".$file.".js"))
									$cache_date= max($cache_date, filemtime($plug_path.$file."/".$file.".js")); // fix for: http://sourceforge.net/tracker/?func=detail&aid=2932086&group_id=164008&atid=829999
							}
						}
						closedir($dir);
					}
				}

				if(filemtime($this->cache_file) >= $cache_date){
					// if cache file is up to date
					$last_modified = gmdate("D, d M Y H:i:s",filemtime($this->cache_file))." GMT";
					if (isset($_SERVER["HTTP_IF_MODIFIED_SINCE"]) && strcasecmp($_SERVER["HTTP_IF_MODIFIED_SINCE"], $last_modified) === 0)
					{
						header("HTTP/1.1 304 Not Modified");
						header("Last-modified: ".$last_modified);
						header("Cache-Control: Public"); // Tells HTTP 1.1 clients to cache
						header("Pragma:"); // Tells HTTP 1.0 clients to cache
					}
					else
					{
						header("Last-modified: ".$last_modified);
						header("Cache-Control: Public"); // Tells HTTP 1.1 clients to cache
						header("Pragma:"); // Tells HTTP 1.0 clients to cache
						header('Content-Length: '.filesize($this->cache_file));
						echo file_get_contents($this->cache_file);
					}
					die;
				}
			}
			return false;
		}

		private function load_files()
		{
			$loader= $this->get_content("edit_area_loader.js")."\n";

			// get the list of other files to load
			$loader= preg_replace("/(t\.scripts_to_load=\s*)\[([^\]]*)\];/e"
						, "\$this->replace_scripts('script_list', '\\1', '\\2')"
						, $loader);

			$loader= preg_replace("/(t\.sub_scripts_to_load=\s*)\[([^\]]*)\];/e"
						, "\$this->replace_scripts('sub_script_list', '\\1', '\\2')"
						, $loader);

			// [i_a] the fix for various browsers' show issues is to flatten EVERYTHING into a single file, i.e. also the language and reg_syntax files: the load_script() and other lazyload-ing bits of code in edit_area are somehow buggy and thus circumvented.

			// replace syntax definition names
			$syntax_defs = '';
			$reg_path= $this->path."reg_syntax/";
			$a_displayName  = array();
			$a_Name = array();
			if (($dir = @opendir($reg_path)) !== false)
			{
				while (($file = readdir($dir)) !== false)
				{
					if( $file !== "." && $file !== ".." && substr($file, -3) == '.js' )
					{
						$jsContent  = $this->file_get_contents( $reg_path.$file );
						if( preg_match( '@(\'|")DISPLAY_NAME\1\s*:\s*(\'|")(.*)\2@', $jsContent, $match ) )
						{
							$a_displayName[] = "'". substr($file, 0, strlen($file) - 3) ."':'". htmlspecialchars( $match[3], ENT_QUOTES, 'UTF-8') ."'";
						}
						if( preg_match( '@editAreaLoader\.load_syntax\[(\'|")([^\'"]+)\1\]\s*=@', $jsContent, $match ) )
						{
							$a_Name[] = htmlspecialchars( $match[2], ENT_QUOTES, 'UTF-8');
						}
						$syntax_defs .= $jsContent . "\n";
						// and add 'marked as loaded' code to that as well:
						$syntax_defs .= "editAreaLoader.loadedFiles[editAreaLoader.baseURL + 'reg_syntax/' + '" . $file . "'] = true;\n\n\n";
					}
				}
				closedir($dir);
			}
			$loader = str_replace( '/*syntax_display_name_AUTO-FILL-BY-COMPRESSOR*/', implode( ",", $a_displayName ), $loader );
			$loader = str_replace( '/*syntax_name_AUTO-FILL-BY-COMPRESSOR*/', implode( ",", $a_Name ), $loader );

			// collect languages
			$language_defs = '';
			$lang_path= $this->path."langs/";
			if (($dir = @opendir($lang_path)) !== false)
			{
				while (($file = readdir($dir)) !== false)
				{
					if( $file !== "." && $file !== ".." && substr($file, -3) == '.js' )
					{
						$jsContent  = $this->file_get_contents( $lang_path.$file );
						$language_defs .= $jsContent . "\n";
						// and add 'marked as loaded' code to that as well:
						$language_defs .= "editAreaLoader.loadedFiles[editAreaLoader.baseURL + 'langs/' + '" . $file . "'] = true;\n\n\n";
					}
				}
				closedir($dir);
			}

			$this->datas= $loader;
			$this->compress_javascript($this->datas);

			// load other scripts needed for the loader
			preg_match_all('/"([^"]*)"/', $this->script_list, $match);
			foreach($match[1] as $key => $value)
			{
				$content= $this->get_content(preg_replace("/\\|\//i", "", $value).".js");
				$this->compress_javascript($content);
				$this->datas.= $content."\n";
			}
			//$this->datas);
			//$this->datas= preg_replace('/(( |\t|\r)*\n( |\t)*)+/s', "", $this->datas);

			// improved compression step 1/2
			if($this->param['compress'])
			{
				$this->datas= preg_replace(array("/(\b)EditAreaLoader(\b)/", "/(\b)editAreaLoader(\b)/", "/(\b)editAreas(\b)/"), array("EAL", "eAL", "eAs"), $this->datas);
				//$this->datas= str_replace(array("EditAreaLoader", "editAreaLoader", "editAreas"), array("EAL", "eAL", "eAs"), $this->datas);
				$this->datas.= "var editAreaLoader= eAL;var editAreas=eAs;EditAreaLoader=EAL;";
			}

			// load sub scripts
			$sub_scripts="";
			$sub_scripts_list= array();
			preg_match_all('/"([^"]*)"/', $this->sub_script_list, $match);
			foreach($match[1] as $value){
				$sub_scripts_list[]= preg_replace("/\\|\//i", "", $value).".js";
			}

			if($this->load_all_plugins){
				// load plugins scripts
				$plug_path= $this->path."plugins/";
				if (($dir = @opendir($plug_path)) !== false)
				{
					while (($file = readdir($dir)) !== false)
					{
						if ($file !== "." && $file !== "..")
						{
							if(is_dir($plug_path.$file) && file_exists($plug_path.$file."/".$file.".js"))
								$sub_scripts_list[]= "plugins/".$file."/".$file.".js";
						}
					}
					closedir($dir);
				}
			}

			foreach($sub_scripts_list as $value){
				$sub_scripts.= $this->get_javascript_content($value);
			}
			// improved compression step 2/2
			if($this->param['compress'])
			{
				$sub_scripts= preg_replace(array("/(\b)editAreaLoader(\b)/", "/(\b)editAreas(\b)/", "/(\b)editArea(\b)/", "/(\b)EditArea(\b)/"), array("eAL", "eAs", "eA", "EA"), $sub_scripts);
			//  $sub_scripts= str_replace(array("editAreaLoader", "editAreas", "editArea", "EditArea"), array("eAL", "eAs", "eA", "EA"), $sub_scripts);
				$sub_scripts.= "var editArea= eA;EditArea=EA;";
			}

			// add the scripts
		//  $this->datas.= sprintf("editAreaLoader.iframe_script= \"<script type='text/javascript'>%s</script>\";\n", $sub_scripts);


			// add the script and use a last compression
			if( $this->param['compress'] )
			{
				$last_comp  = array( 'Á' => 'this',
								 'Â' => 'textarea',
								 'Ã' => 'function',
								 'Ä' => 'prototype',
								 'Å' => 'settings',
								 'Æ' => 'length',
								 'Ç' => 'style',
								 'È' => 'parent',
								 'É' => 'last_selection',
								 'Ê' => 'value',
								 'Ë' => 'true',
								 'Ì' => 'false'
								 /*,
									'Î' => '"',
								 'Ï' => "\n",
								 'À' => "\r"*/);
			}
			else
			{
				$last_comp  = array();
			}

			$js_replace= '';
			foreach( $last_comp as $key => $val )
				$js_replace .= ".replace(/". $key ."/g,'". str_replace( array("\n", "\r"), array('\n','\r'), $val ) ."')";

			$this->datas.= sprintf("editAreaLoader.iframe_script= \"<script type='text/javascript'>%s</script>\"%s;\n",
								str_replace( array_values($last_comp), array_keys($last_comp), $sub_scripts ),
								$js_replace);

			if($this->load_all_plugins)
				$this->datas.="editAreaLoader.all_plugins_loaded=true;\n";


			// load the template
			$this->datas.= sprintf("editAreaLoader.template= \"%s\";\n", $this->get_html_content("template.html"));
			// load the css
			$this->datas.= sprintf("editAreaLoader.iframe_css= \"<style>%s</style>\";\n", $this->get_css_content("edit_area.css"));

			// load the syntaxes and languages as well:
			if ($this->param['include_langs_and_syntaxes'])
			{
				// make sure the syntax and/or language files are NOT nuked in the process so act conservatively when compressing:
				$this->compress_javascript($syntax_defs, false);
				$this->datas.= $syntax_defs;
				$this->compress_javascript($language_defs, false);
				$this->datas.= $language_defs;
			}

		//  $this->datas= "function editArea(){};editArea.prototype.loader= function(){alert('bouhbouh');} var a= new editArea();a.loader();";

		}

		private function send_datas()
		{
			if($this->param['debug']){
				$header=sprintf("/* USE PHP COMPRESSION\n");
				$header.=sprintf("javascript size: based files: %s => PHP COMPRESSION => %s ", $this->file_loaded_size, strlen($this->datas));
				if($this->use_gzip){
					$gzip_datas=  gzencode($this->datas, 9, FORCE_GZIP);
					$header.=sprintf("=> GZIP COMPRESSION => %s", strlen($gzip_datas));
					$ratio = round(100 - strlen($gzip_datas) / $this->file_loaded_size * 100.0);
				}else{
					$ratio = round(100 - strlen($this->datas) / $this->file_loaded_size * 100.0);
				}
				$header.=sprintf(", reduced by %s%%\n", $ratio);
				$header.=sprintf("compression time: %s\n", $this->get_microtime()-$this->start_time);
				$header.=sprintf("%s\n", implode("\n", $this->infos));
				$header.=sprintf("*/\n");
				$this->datas= $header.$this->datas;
			}
			$mtime= time(); // ensure that the 2 disk files will have the same update time
			// generate gzip file and cache it if using disk cache
			if($this->use_gzip){
				$this->gzip_datas= gzencode($this->datas, 9, FORCE_GZIP);
				if($this->param['use_disk_cache'])
					$this->file_put_contents($this->gzip_cache_file, $this->gzip_datas, $mtime);
			}

			// generate full js file and cache it if using disk cache
			if($this->param['use_disk_cache'])
			{
				if (!empty($this->param['verbose2stdout'])) echo "written to file: " . $this->full_cache_file . "\n";
				$this->file_put_contents($this->full_cache_file, $this->datas, $mtime);
			}

			// generate output
			if ($this->param['echo2stdout'])
			{
				if($this->use_gzip)
					echo $this->gzip_datas;
				else
					echo $this->datas;
			}

//          die;
		}

		private function get_content($end_uri)
		{
			$end_uri=preg_replace("/\.\./", "", $end_uri); // Remove any .. (security)
			$file= $this->path.$end_uri;
			if(file_exists($file)){
				$this->infos[]=sprintf("'%s' loaded", $end_uri);
				/*$fd = fopen($file, 'rb');
				$content = fread($fd, filesize($file));
				fclose($fd);
				return $content;*/
				return $this->file_get_contents($file);
			}else{
				$this->infos[]=sprintf("'%s' not loaded", $end_uri);
				return "";
			}
		}

		private function get_javascript_content($end_uri)
		{
			$val=$this->get_content($end_uri);

			$this->compress_javascript($val);
			$this->prepare_string_for_quotes($val);
			return $val;
		}

		private function compress_javascript(&$code, $apply_optimistic_rules = true)
		{
			if($this->param['compress'])
			{
				// remove all comments
				//  (\"(?:[^\"\\]*(?:\\\\)*(?:\\\"?)?)*(?:\"|$))|(\'(?:[^\'\\]*(?:\\\\)*(?:\\'?)?)*(?:\'|$))|(?:\/\/(?:.|\r|\t)*?(\n|$))|(?:\/\*(?:.|\n|\r|\t)*?(?:\*\/|$))
				$code= preg_replace("/(\"(?:[^\"\\\\]*(?:\\\\\\\\)*(?:\\\\\"?)?)*(?:\"|$))|(\'(?:[^\'\\\\]*(?:\\\\\\\\)*(?:\\\\\'?)?)*(?:\'|$))|(?:\/\/(?:.|\r|\t)*?(\n|$))|(?:\/\*(?:.|\n|\r|\t)*?(?:\*\/|$))/s", "$1$2$3", $code);
				// remove line return, empty line and tabulation
				$code= preg_replace('/(( |\t|\r)*\n( |\t)*)+/s', " ", $code);
				// add line break before "else" otherwise navigators can't manage to parse the file
				if ($apply_optimistic_rules)
				{
					$code= preg_replace('/(\b(else)\b)/', "\n$1", $code);
				}
				// remove unnecessary spaces
				$code= preg_replace('/( |\t|\r)*(;|\{|\}|=|==|\-|\+|,|\(|\)|\|\||&\&|\:)( |\t|\r)*/', "$2", $code);
			}
		}

		private function get_css_content($end_uri){
			$code=$this->get_content($end_uri);
			// remove comments
			$code= preg_replace("/(?:\/\*(?:.|\n|\r|\t)*?(?:\*\/|$))/s", "", $code);
			// remove spaces
			$code= preg_replace('/(( |\t|\r)*\n( |\t)*)+/s', "", $code);
			// remove spaces
			$code= preg_replace('/( |\t|\r)?(\:|,|\{|\})( |\t|\r)+/', "$2", $code);

			$this->prepare_string_for_quotes($code);
			return $code;
		}

		private function get_html_content($end_uri){
			$code=$this->get_content($end_uri);
			//$code= preg_replace('/(\"(?:\\\"|[^\"])*(?:\"|$))|' . "(\'(?:\\\'|[^\'])*(?:\'|$))|(?:\/\/(?:.|\r|\t)*?(\n|$))|(?:\/\*(?:.|\n|\r|\t)*?(?:\*\/|$))/s", "$1$2$3", $code);
			$code= preg_replace('/(( |\t|\r)*\n( |\t)*)+/s', " ", $code);
			$this->prepare_string_for_quotes($code);
			return $code;
		}

		private function prepare_string_for_quotes(&$str){
			// prepare the code to be putted into quotes
			/*$pattern= array("/(\\\\)?\"/", '/\\\n/'   , '/\\\r/'  , "/(\r?\n)/");
			$replace= array('$1$1\\"', '\\\\\\n', '\\\\\\r' , '\\\n"$1+"');*/
			$pattern= array("/(\\\\)?\"/", '/\\\n/' , '/\\\r/'  , "/(\r?\n)/");
			if($this->param['compress'])
				$replace= array('$1$1\\"', '\\\\\\n', '\\\\\\r' , '\n');
			else
				$replace= array('$1$1\\"', '\\\\\\n', '\\\\\\r' , "\\n\"\n+\"");
			$str= preg_replace($pattern, $replace, $str);
		}

		private function replace_scripts($var, $param1, $param2)
		{
			$this->$var=stripslashes($param2);
			return $param1."[];";
		}

		/* for php version that have not thoses functions */
		private function file_get_contents($file)
		{
			$fd = fopen($file, 'rb');
			$content = fread($fd, filesize($file));
			fclose($fd);
			$this->file_loaded_size+= strlen($content);
			return $content;
		}

		private function file_put_contents($file, &$content, $mtime=-1)
		{
			if($mtime==-1)
				$mtime=time();
			$fp = @fopen($file, 'wb');
			if ($fp) {
				fwrite($fp, $content);
				fclose($fp);
				touch($file, $mtime);
				return true;
			}
			return false;
		}

		private function get_microtime()
		{
		   list($usec, $sec) = explode(" ", microtime());
		   return ((float)$usec + (float)$sec);
		}
	}
?>