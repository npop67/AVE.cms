<?php

	// Проверка
	if (! defined('BASE_DIR'))
		exit('Access denied');

	/**
	 * This source file is part of the AVE.cms. More information,
	 * documentation and tutorials can be found at http://www.ave-cms.ru
	 *
	 * @package      AVE.cms
	 * @file         system/helpers/debug.php
	 * @author       @
	 * @copyright    2007-2015 (c) AVE.cms
	 * @link         http://www.ave-cms.ru
	 * @version      4.0
	 * @since        $date$
	 * @license      license GPL v.2 http://www.ave-cms.ru/license.txt
	*/

	class Debug {

		protected static $time = array();

		protected static $memory = array();


		public function __construct()
		{
			//
		}


		/**
		 * Функция для вывода переменной (для отладки)
		 *
		 * @param mixed $var любая переменная
		 * @param bool  $exit
		 * @param null  $bg
		 * @param bool  $echo
		 *
		 * @return false|null|string|string[]
		 */
		public static function _echo($var, $exit = false, $bg = null, $echo = true)
		{
			$code = '';

			$backtrace = debug_backtrace();

			$backtrace = $backtrace[0];

			if (preg_match('/([^\(]*)\((.*)\)/i', $backtrace['file']))
			{
				preg_match('/([^\(]*)\((.*)\)/i', $backtrace['file'], $match);
				$file = $match[1];
			}

			$fh = fopen((isset($file)
				? $file
				: $backtrace['file']), 'r');

			$line = 0;

			while (++$line <= $backtrace['line'])
				$code = fgets($fh);

			fclose($fh);

			preg_match('/' . __FUNCTION__ . '\s*\((.*)\)\s*;/u', $code, $name);

			unset ($code, $backtrace);

			ob_start();

			var_dump($var);

			$var_dump = ob_get_contents();

			$var_dump = preg_replace('/=>(\s+|\s$)/', ' => ', $var_dump);

			$var_dump = htmlspecialchars($var_dump);

			$var_dump = preg_replace('/(=&gt;)/', '<span style="color: #ff8c00;">$1</span>', $var_dump);

			ob_end_clean();

			if (! empty($name))
			{
				$fn_name = explode(',', $name[1]);
				$fn_name = array_shift($fn_name);
			}
			else
				$fn_name = 'EVAL';

			if (! $bg)
				$bg = '43648c';

			$var_dump = '
				<style>
					.debug_bg {
						margin: 20px;
						border: 1px solid #d9d9d9;
						background-color: #f1efef;
						border-radius: 5px;
						box-shadow: 0 0 3px rgba(0, 0, 0, 0.1);
						font-family: "Consolas", Verdana, Arial;
						font-size: 11px;
					}
					.debug_top {
						color: #ffffff;
						font-size: 15px;
						font-weight: bold;
						padding-left: 20px;
						padding-top: 10px;
						padding-bottom: 10px;
						text-shadow: 0 1px 1px rgba(0, 0, 0, 0.75);
						background-color: #' . $bg . ';
						background-repeat: repeat-x;
						border-bottom: 1px solid #ffffff;
					}
					.debug_box {
						margin: 10px;
						padding: 4px;
						background-color: #efeded;
						border: 1px solid #dedcdc;
					}
				</style>
				<div class="debug_bg">
					<div class="debug_top">
						var_dump(<strong>' . trim($fn_name) . '</strong>)
					</div>
					'.self::_trace().'
					<div class="debug_box">
						<pre style="background:#f5f5f5; color: #000; margin: 0; padding: 5px; border: 0; font-size: 11px; font-family: Consolas, Verdana, Arial;">'
						. $var_dump .
						'</pre>
					</div>
				</div>
			';

			if (! $echo)
				return $var_dump;

			echo $var_dump;

			if ($exit)
				exit;
		}


		/**
		 * Функция для вывода переменной (для отладки)
		 *
		 * @param mixed $var любая переменная
		 * @param bool  $exit
		 * @param null  $bg
		 * @param bool  $echo
		 *
		 * @return null|string|string[]
		 */
		public static function _print($var, $exit = false, $bg = null, $echo = true)
		{
			$code = '';

			$backtrace = debug_backtrace();

			$backtrace = $backtrace[0];

			if (preg_match('/([^\(]*)\((.*)\)/i', $backtrace['file']))
			{
				preg_match('/([^\(]*)\((.*)\)/i', $backtrace['file'], $match);
				$file = $match[1];
			}

			$fh = fopen((isset($file)
				? $file
				: $backtrace['file']), 'r');

			$line = 0;

			while (++$line <= $backtrace['line'])
				$code = fgets($fh);

			fclose($fh);

			preg_match('/' . __FUNCTION__ . '\s*\((.*)\)\s*;/u', $code, $name);

			ob_start();

			print_r($var);

			$var_dump = htmlspecialchars(ob_get_contents());

			$var_dump = preg_replace('/(=&gt;)/', '<span style="color: #FF8C00;">$1</span>', $var_dump);

			ob_end_clean();

			if (! empty($name))
			{
				$fn_name = explode(',', $name[1]);
				$fn_name = array_shift($fn_name);
			}
			else
				$fn_name = 'EVAL';

			if (! $bg)
				$bg = '4e5665';

			$var_dump = '
				<style>
					.debug_bg {
						margin: 20px;
						border: 1px solid #d9d9d9;
						background-color: #f1efef;
						border-radius: 5px;
						box-shadow: 0px 0px 3px rgba(0, 0, 0, 0.1);
						font-family: Consolas, Verdana, Arial;
						font-size: 11px;
					}
					.debug_top {
						color: #ffffff;
						font-size: 15px;
						font-weight: bold;
						padding-left: 20px;
						padding-top: 10px;
						padding-bottom: 10px;
						text-shadow: 0 1px 1px rgba(0, 0, 0, 0.75);
						background-color: #'.$bg.';
						background-repeat: repeat-x;
						border-bottom: 1px solid #ffffff;
					}
					.debug_box {
						margin: 10px;
						padding: 4px;
						background-color: #efeded;
						border: 1px solid #dedcdc;
					}
				</style>
				<div class="debug_bg">
					<div class="debug_top">
						print_r(<strong>' . trim($fn_name) . '</strong>)
					</div>
					'.self::_trace().'
					<div class="debug_box">
						<pre style="background:#f5f5f5; color: #000; margin: 0; padding: 5px; border: 0; font-size: 11px; font-family: Consolas, Verdana, Arial;">'
						. $var_dump .
						'</pre>
					</div>
				</div>
			';

			if (! $echo)
				return $var_dump;

			echo $var_dump;

			if ($exit)
				exit;
		}


		/**
		 * Функция для вывода переменной (для экспорта)
		 *
		 * @param mixed $var любая переменная
		 * @param bool  $exit
		 * @param null  $bg
		 * @param bool  $echo
		 *
		 * @return string
		 */
		public static function _exp($var, $exit = false, $bg = null, $echo = true)
		{
			$code = '';

			$backtrace = debug_backtrace();

			$backtrace = $backtrace[0];

			if (preg_match('/([^\(]*)\((.*)\)/i', $backtrace['file']))
			{
				preg_match('/([^\(]*)\((.*)\)/i', $backtrace['file'], $match);
				$file = $match[1];
			}

			$fh = fopen((isset($file)
				? $file
				: $backtrace['file']), 'r');

			$line = 0;

			while (++$line <= $backtrace['line'])
				$code = fgets($fh);

			fclose($fh);

			preg_match('/' . __FUNCTION__ . '\s*\((.*)\)\s*;/u', $code, $name);

			ob_start();

			var_export($var);

			if (! empty($name))
			{
				$fn_name = explode(',', $name[1]);
				$fn_name = array_shift($fn_name);
			}
			else
				$fn_name = 'EVAL';

			if (! $bg)
				$bg = 'ccc';

			$var_export = htmlspecialchars(ob_get_contents());

			$var_export = preg_replace('/(=&gt;)/', '<span style="color: #FF8C00;">$1</span>', $var_export);

			ob_end_clean();

			$var_dump = '
				<style>
					.debug_bg {
						margin: 20px;
						border: 1px solid #d9d9d9;
						background-color: #f1efef;
						border-radius: 5px;
						box-shadow: 0px 0px 3px rgba(0, 0, 0, 0.1);
						font-family: Consolas, Verdana, Arial;
						font-size: 11px;
					}
					.debug_top {
						color: #ffffff;
						font-size: 15px;
						font-weight: bold;
						padding-left: 20px;
						padding-top: 10px;
						padding-bottom: 10px;
						text-shadow: 0 1px 1px rgba(0, 0, 0, 0.75);
						background-color: #'.$bg.';
						background-repeat: repeat-x;
						border-bottom: 1px solid #ffffff;
					}
					.debug_box {
						margin: 10px;
						padding: 4px;
						background-color: #efeded;
						border: 1px solid #dedcdc;
					}
				</style>
				<div class="debug_bg">
					<div class="debug_top">
						var_export(<strong>' . trim($fn_name) . '</strong>)
					</div>
					'.self::_trace().'
					<div class="debug_box">
						<pre style="background:#f5f5f5; color: #000; margin: 0; padding: 5px; border: 0; font-size: 11px; font-family: Consolas, Verdana, Arial;">'
						. $var_export .
						'</pre>
					</div>
				</div>
			';

			if (! $echo)
				return $var_dump;

			echo $var_dump;

			if ($exit)
				exit;
		}


		/**
		 * Функция для вывода переменной (для отладки)
		 *
		 * @param mixed $var  любая переменная
		 * @param bool  $exit true - остановливает дальнейшее выполнение скрипта, false - продолжает выполнять скрипт
		 * @param null  $bg
		 * @param bool  $echo
		 *
		 * @return false|string
		 */
		public static function _html($var, $exit = false, $bg = null, $echo = true)
		{
			$code = '';

			$backtrace = debug_backtrace();

			$backtrace = $backtrace[0];

			if (preg_match('/([^\(]*)\((.*)\)/i', $backtrace['file']))
			{
				preg_match('/([^\(]*)\((.*)\)/i', $backtrace['file'], $match);
				$file = $match[1];
			}

			$fh = fopen((isset($file)
				? $file
				: $backtrace['file']), 'r');

			$line = 0;

			while (++$line <= $backtrace['line'])
				$code = fgets($fh);

			fclose($fh);

			preg_match('/' . __FUNCTION__ . '\s*\((.*)\)\s*;/u', $code, $name);

			ob_start();

			var_export($var);

			if (! empty($name))
			{
				$fn_name = explode(',', $name[1]);
				$fn_name = array_shift($fn_name);
			}
			else
				$fn_name = 'EVAL';

			if (! $bg)
				$bg = '43648c';

			$var_dump = ob_get_contents();

			ob_end_clean();

			$var_dump = '
				<style>
					.debug_bg {
						margin: 20px;
						border: 1px solid #d9d9d9;
						background-color: #f1efef;
						border-radius: 5px;
						box-shadow: 0px 0px 3px rgba(0, 0, 0, 0.1);
						font-family: Consolas, Verdana, Arial;
						border-radius: 3px;
						font-size: 11px;
					}
					.debug_top {
						color: #ffffff;
						font-size: 15px;
						font-weight: bold;
						padding-left: 20px;
						padding-top: 10px;
						padding-bottom: 10px;
						text-shadow: 0 1px 1px rgba(0, 0, 0, 0.75);
						background-color: #'.$bg.';
						background-repeat: repeat-x;
						border-bottom: 1px solid #ffffff;
					}
					.debug_box {
						margin: 10px;
						padding: 4px;
						background-color: #efeded;
						border: 1px solid #dedcdc;
					}
				</style>
				<div class="debug_bg">
					<div class="debug_top">
						var_export(<strong>' . trim($fn_name) . '</strong>)
					</div>
					'.self::_trace().'
					<div class="debug_box">
						<pre style="background:#f5f5f5; color: #000; margin: 0; padding: 5px; border: 0; font-size: 11px; font-family: Consolas, Verdana, Arial;">'
						. htmlentities($var_dump, ENT_QUOTES) .
						'</pre>
					</div>
				</div>
			';

			if (! $echo)
				return $var_dump;

			echo $var_dump;

			if ($exit)
				exit;
		}


		/**
		 * Функция для записи переменной в файл (для отладки)
		 *
		 * @param mixed $var  любая переменная
		 * @param bool  $exit true - остановливает дальнейшее выполнение скрипта, false - продолжает выполнять скрипт
		 * @param null  $bg
		 * @param bool  $append
		 */
		public static function _dump($var, $exit = false, $bg = null, $append = true)
		{
			$code = '';

			$backtrace = debug_backtrace();

			$backtrace = $backtrace[0];

			if (preg_match('/([^\(]*)\((.*)\)/i', $backtrace['file']))
			{
				$file = preg_match('/([^\(]*)\((.*)\)/i', $backtrace['file'], $match);
				$file = $match[1];
			}

			$fh = fopen((isset($file)
				? $file
				: $backtrace['file']), 'r');

			$line = 0;

			while (++$line <= $backtrace['line'])
				$code = fgets($fh);

			fclose($fh);

			preg_match('/' . __FUNCTION__ . '\s*\((.*)\)\s*;/u', $code, $name);

			ob_start();

			var_dump($var);

			$var_dump = ob_get_contents();

			$var_dump = preg_replace('/=>(\s+|\s$)/', ' => ', $var_dump);

			$var_dump = htmlspecialchars($var_dump);

			$var_dump = preg_replace('/(=&gt; )+([a-zA-Z]+\(\d+\))/', '$1<span style="color: #FF8C00;">$2</span>', $var_dump);

			ob_end_clean();

			if (! empty($name))
			{
				$fn_name = explode(',', $name[1]);
				$fn_name = array_shift($fn_name);
			}
			else
				$fn_name = 'EVAL';

			if (! $bg)
			{
				$br = '2a5885';
				$bg = '43648c';
			}
			else
				{
					$br = $bg;
				}

			$var_dump = '
				<style>
					.debug_bg {
						margin: 20px;
						border: 1px solid #d9d9d9;
						background-color: #f1efef;
						border-radius: 5px;
						box-shadow: 0px 0px 3px rgba(0, 0, 0, 0.1);
						font-family: Consolas, Verdana, Arial;
						font-size: 11px;
					}
					.debug_top {
						color: #ffffff;
						font-size: 15px;
						font-weight: bold;
						padding-left: 20px;
						padding-top: 10px;
						padding-bottom: 10px;
						text-shadow: 0 1px 1px rgba(0, 0, 0, 0.75);
						background-color: #'.$bg.';
						background-repeat: repeat-x;
						border-bottom: 1px solid #ffffff;
					}
					.debug_box {
						margin: 10px;
						padding: 4px;
						background-color: #efeded;
						border: 1px solid #dedcdc;
					}
				</style>
				<div class="debug_bg">
					<div class="debug_top">
						var_dump(<strong>' . trim($fn_name) . '</strong>)
					</div>
					'.self::_trace().'
					<div class="debug_box">
						<pre style="background:#f5f5f5; color: #000; margin: 0; padding: 5px; border: 0; font-size: 11px; font-family: Consolas, Verdana, Arial;">'
						. $var_dump .
						'</pre>
					</div>
				</div>
			';

			if ($append)
				file_put_contents(BASE_DIR . '/debug.html', $var_dump, FILE_APPEND);
			else
				file_put_contents(BASE_DIR . '/debug.html', $var_dump);

			if ($exit)
				exit;
		}


		/**
		 * Функция для трейсинга дебаггера
		 *
		 * @param
		 * @return string
		 */
		public static function _trace()
		{
			$bt = debug_backtrace();

			$trace = $bt[1];

			$line = $trace['line'];

			$file = $trace['file'];

			//$function = $trace['function'];

			$class = (isset($bt[2]['class'])
				? $bt[2]['class']
				: 'None');

			if (isset($bt[2]['class']))
				$type = $bt[2]['type'];
			else
				$type = 'Unknow';

			$function = isset($bt[2]['function'])
				? $bt[2]['function']
				: 'None';

			return sprintf('<div class="debug_box">Class: <strong>%s</strong> | Type: <strong>%s</strong> | Function: <strong>%s</strong></div><div class="debug_box">File: <strong>%s</strong> line <strong>%s</strong></div>', $class, $type, $function, $file, $line);
		}


		/**
		 * Функция отвечает за начало таймера
		 *
		 * @param string $name любая переменная (ключ массива)
		 */
		public static function startTime($name = '')
		{
			Debug::$time[$name] = microtime(true);
		}


		/**
		 * Функция отвечает за окончание таймера
		 *
		 * @param string $name любая переменная (ключ массива)
		 *
		 * @return string
		 */
		public static function endTime($name = '')
		{
			if (isset(Debug::$time[$name]))
				return sprintf("%01.4f", microtime(true) - Debug::$time[$name]) . ' sec';
		}


		/**
		 * Функция отвечает за начало подсчета используеой памяти
		 *
		 * @param string $name любая переменная (ключ массива)
		 */
		public static function startMemory($name = '')
		{
			Debug::$memory[$name] = memory_get_usage();
		}


		/**
		 * Функция отвечает за окончание подсчета используемой памяти
		 *
		 * @param string $name любая переменная (ключ массива)
		 * @return string
		 */
		public static function endMemory($name = '')
		{
			if (isset(Debug::$memory[$name]))
				return Debug::formatSize(memory_get_usage() - Debug::$memory[$name]);
		}


		/**
		 * Форматированный вывод размера
		 *
		 * @param int $size размер
		 * @return string нормированный размер с единицой измерения
		 */
		public static function formatSize($size)
		{
			if ($size >= 1073741824)
				$size = round($size / 1073741824 * 100) / 100 . ' Gb';
			elseif ($size >= 1048576)
				$size = round($size / 1048576 * 100) / 100 . ' Mb';
			elseif ($size >= 1024)
				$size = round($size / 1024 * 100) / 100 . ' Kb';
			else
				$size = $size . ' b';

			return $size;
		}


		/**
		 * Форматированный вывод чисел
		 *
		 * @param int $number число
		 * @param int $decimal
		 * @param string $after
		 * @param string $thousand
		 * @return string
		 */
		public static function numFormat($number, $decimal = 0, $after = ',', $thousand= '.')
		{
			if ($number)
				return number_format($number, $decimal, $after, $thousand);

			return '';
		}


		/**
		 * @param      $header
		 * @param      $body
		 * @param      $caller
		 * @param bool $exit
		 */
		public static function _errorSql ($header, $body, $caller, $exit = false)
		{
			//
		}


		/**
		 * Вывод статистики
		 *
		 * @param null $type
		 *
		 * @return int|null|string
		 */
		public static function  getStatistic ($type = null)
		{
			global $AVE_DB;

			$stat = null;

			switch ($type)
			{
				case 'time':
					$stat = number_format(microtime_diff(START_MICROTIME, microtime()), 3, ',', ' ');
					break;

				case 'memory':
					$stat = Debug::formatSize(memory_get_usage() - START_MEMORY);
					break;

				case 'peak':
					$stat = Debug::formatSize(memory_get_peak_usage());
					break;

				case 'sqlcount':
					$stat = $AVE_DB->DBProfilesGet('count');
					break;

				case 'sqltrace':
					$stat = count($AVE_DB->_query_list);
					break;

				case 'sqltime':
					$stat = $AVE_DB->DBProfilesGet('time');
					break;

				case 'get':
					$stat = self::_stat_get('get');
					break;

				case 'post':
					$stat = self::_stat_get('post');
					break;

				case 'request':
					$stat = self::_stat_get('request');
					break;

				case 'session':
					$stat = self::_stat_get('session');
					break;

				case 'server':
					$stat = self::_stat_get('server');
					break;

				case 'globals':
					$stat = self::_stat_get('globals');
					break;
			}

			return $stat;
		}


		/**
		 * @param string $type
		 *
		 * @return false|null|string|string[]
		 */
		public static function _stat_get($type = 'get')
		{
			ob_start();
			if ($type == 'get')
				var_dump($_GET);
			else if ($type == 'post')
				var_dump($_POST);
			else if ($type == 'request')
				var_dump($_REQUEST);
			else if ($type == 'session')
				var_dump($_SESSION);
			else if ($type == 'server')
				var_dump($_SERVER);
			else if ($type == 'globals')
				var_dump($GLOBALS);
			$stat = ob_get_contents();
			$stat = preg_replace('/=>(\s+|\s$)/', ' => ', $stat);
			$stat = htmlspecialchars($stat);
			$stat = preg_replace('/(=&gt;)/', '<span style="color: #FF8C00;">$1</span>', $stat);
			$stat = '<pre style="background:#f5f5f5; color: #000; margin: 0; padding: 5px; border: 0; font-size: 11px; font-family: Consolas, Verdana, Arial;">'. $stat .'</pre>';
			ob_end_clean();

			return $stat;
		}


		/**
		 * @return string
		 */
		public static function displayInfo ()
		{
			global $AVE_DB;

			$out = PHP_EOL;
			$out .= '<link rel="stylesheet" href="/lib/debug/debug.css" />';
			$out .= PHP_EOL;
			$out .= '<script>window.jQuery || document.write(\'<script src="https://cdnjs.cloudflare.com/ajax/libs/jquery/3.2.1/jquery.min.js">\x3C/script>\')</script>';
			$out .= PHP_EOL;
			$out .= '<script src="/lib/debug/debug.js"></script>';
			$out .= PHP_EOL;
			$out .= '
				<div id="debug-panel">
					<div class="debug-wrapper">
					<div id="debug-panel-legend" class="legend">
						<span>Debug console</span>
						<a id="debugArrowMinimize" class="debugArrow" href="javascript:void(0)" title="Minimize" onclick="javascript:appTabsHide()">&times;</a>
						<span>
							<a id="tabGeneral" href="javascript:void(\'General\')" onclick="javascript:appExpandTabs(\'auto\', \'General\')">General</a>
							<a id="tabParams" href="javascript:void(\'Params\')" onclick="javascript:appExpandTabs(\'auto\', \'Params\')">Params</a>
							<a id="tabGlobals" href="javascript:void(\'Globals\')" onclick="javascript:appExpandTabs(\'auto\', \'Globals\')">Globals</a>
							<a id="tabQueries" href="javascript:void(\'Queries\')" onclick="javascript:appExpandTabs(\'auto\', \'Queries\')">SQL Queries (' . self::getStatistic('sqlcount') . ')</a>
							<a id="tabSqlTrace" href="javascript:void(\'SqlTrace\')" onclick="javascript:appExpandTabs(\'auto\', \'SqlTrace\')">SQL Trace (' . self::getStatistic('sqltrace') . ')</a>
						</span>
					</div>
			';
			$out .= PHP_EOL;
			$out .= '<div id="contentGeneral" class="items" style="display: none">' . PHP_EOL;
			$out .= 'Time generation: ' . self::getStatistic('time') . ' sec';
			$out .= '<br>';
			$out .= 'Memory usage: ' . self::getStatistic('memory');
			$out .= '<br>';
			$out .= 'Memory peak usage: ' . self::getStatistic('peak');
			$out .= '<br>';
			$out .= 'SQL Queries: ' . $AVE_DB->DBProfilesGet('count') . ' for ' . $AVE_DB->DBProfilesGet('time') . ' sec';
			$out .= '</div>';

			$out .= PHP_EOL;
			$out .= '<div id="contentParams" class="items" style="display: none">' . PHP_EOL;
			$out .= 'GET:';
			$out .= self::getStatistic('get');
			$out .= '<br>';
			$out .= 'POST:';
			$out .= self::getStatistic('post');
			$out .= '<br>';
			$out .= 'REQUEST:';
			$out .= self::getStatistic('request');
			$out .= '<br>';
			$out .= 'SESSION:';
			$out .= self::getStatistic('session');
			$out .= '<br>';
			$out .= 'SERVER:';
			$out .= self::getStatistic('server');
			$out .= '</div>';

			$out .= PHP_EOL;
			$out .= '<div id="contentGlobals" class="items" style="display: none">' . PHP_EOL;
			$out .= self::getStatistic('globals');
			$out .= '</div>';

			$out .= PHP_EOL;
			$out .= '<div id="contentQueries" class="items" style="display: none">' . PHP_EOL;
			$out .= $AVE_DB->DBProfilesGet('list');
			$out .= '</div>';

			$out .= PHP_EOL;
			$out .= '<div id="contentSqlTrace" class="items" style="display: none">' . PHP_EOL;
			$out .= $AVE_DB->showAllQueries();
			$out .= '</div>';

			$out .= PHP_EOL;
			$out .= '</div>';

			return $out;
		}
	}
?>