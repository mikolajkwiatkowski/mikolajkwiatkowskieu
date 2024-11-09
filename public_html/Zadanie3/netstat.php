<?php

/**
 * netstat.php - Show online status of hosts and services
 *
 * This script is intended to provide a simplified, easily comprehensible
 * and aesthetically pleasing overview of the online status of hosts and
 * services. Checks are done in real-time but they only check whether
 * a port is open (which might be sufficient if your hosts are monitored
 * by full-blown monitoring tools anyway and all you want is a simple
 * interface for e.g. users or clients).
 *
 * Requirements: fsockopen(), for ICMP pings also exec()
 *
 * (License + History: see also end of file)
 *
 * @author     Andreas Schamanek <https://andreas.schamanek.net>
 * @license    GPL <https://www.gnu.org/licenses/gpl.html>
 * @copyright  (c) 2009-2019 Andreas Schamanek
 *
 */
$version = '<a href="https://fam.tuwien.ac.at/~schamane/sysadmin/netstat/">netstat.php</a> '
	. 'by <a href="https://andreas.schamanek.net/">Andreas Schamanek</a> '
	. '~ v0.16 ~ 2019-01-28 ~ '
	. '<a href="https://www.gnu.org/licenses/gpl.html">GPL licensed</a>';

// below we set up some silly defaults; it is recommended to save your
// own settings in $configfile; if readable it will override our defaults;
// for a list of configuration variables see the readme.
$configfile = 'netstat.conf.php';

// title (as in HTML) and headline of the page
$title = "Status sieci appmakerslab.eu";
$headline = $title;

// including $configfile if available
if (file_exists($configfile) && is_readable($configfile)) include($configfile);

// all configuration variables are set only if not already set
function defaults(&$var, $value) {
	isset($var) || $var = $value;
}

// Report no PHP errors (to be safe we include this very early)
defaults($error_reporting, 0); error_reporting($error_reporting);

// if $alertfile exists the contents will be included()/shown (use HTML!)
defaults($alertfile, 'netstat.txt');

// checks (use pipes (|) with care ;)
//   syntax: host or IP to check | port | description
//     if $port = 'ping' an ICMP ping will be executed
//     if $port = 'headline' $host is printed as a headline
defaults($checks, array(
    'Strona mikolajkwiatkowski.eu |headline',
    'mikolajkwiatkowski.eu |  80 | WWW server (port 80)',
    'mikolajkwiatkowski.eu | 443 | WWW server (SSL, port 443)',
    'localhost       |  3306 | MySQL server (port 3306)',
    'mikolajkwiatkowski.eu      |  22 | SSH server (port 22)',
    'mikolajkwiatkowski.eu      |  25 | SMTP server (port 25)',
    'mikolajkwiatkowski.eu      | 110 | POP3 server (port 110)',
    'mikolajkwiatkowski.eu      |  21 | FTP server (port 21)',
));


// exec commands for ping: -l3 (preload) is recommended but
//defaults($ping_command, 'ping -l3 -c3 -w1 -q'); // might not work everywhere
defaults($ping_command, 'ping -c3 -w1 -q');
defaults($ping6_command, 'ping6 -c3 -w1 -q');

// fsockopen timeout; might need adjustment depending on network
defaults($timeout, 4);

// show a very simple progress indicator (requires Javascript)
// may be disabled also by adding '?noprogress' to the script's URL
defaults($progressindicator, true);

// diagnostics and errors are added by default as title attributes
// they are shown by browsers in tooltips upon mouse over
// with ?showdiagnostics, ?diags or $showdiagnostics=true diagnostics
// will be shown in their own table rows
defaults($showdiagnostics, false);

// output buffering can be a PITA (see below), in some cases it can help
// to send "garbage" to get browsers to display the first parts of a page
// (like the title + progress indicator) while the rest is still loading.
// consider $bufferfill="<!-- ".str_repeat('.',3333)." -->\n";
defaults($bufferfill,'');

// strings for online and offline (by default these are used for CSS, too)
defaults($online, 'Online');
defaults($offline, 'Offline');

// print date and/or time (leave empty to show no timestamp)
defaults($datetime, 'l, F j, Y, H:i:s T');

// RSS alert feed
defaults($rssfeed, true); // enable/disable RSS feed
// URL of RSS feed
defaults($rssfeedurl, '//'.$_SERVER['HTTP_HOST'].$_SERVER['SCRIPT_NAME'].'?rss');
// RSS feed title
defaults($rsstitle, "RSS alert feed of $title");
// RSS header e.g. to include in $htmlheader; set to '' to offer no RSS
defaults($rssheader, '<link rel="alternate" type="application/rss+xml" '."title=\"$rsstitle\" href=\"$rssfeedurl\" />");
// RSS alert link (might point e.g. to your network status homepage)
defaults($rsslink, '//'.$_SERVER['HTTP_HOST'].$_SERVER['SCRIPT_NAME'].'?noprogress');
// RSS date and/or time format (here we use a ISO 8601 format)
defaults($rssdatetime, 'o-m-d H:i:s T');

// HTML/page header (_tmp variable just used for readability)
$htmlheader_tmp = <<<EOH
<!doctype html><html>
<head>
<title>$title</title>
<meta charset="utf-8">
<meta http-equiv="Refresh" content="399">
<meta name="description" content="Online status of hosts and services provided by netstat.php" />
$rssheader
<meta name="viewport" content="width=device-width; initial-scale=1.0">
<style type="text/css"><!--
body { font-family: Verdana, "Lucida Sans", Arial, Helvetica, sans-serif; font-size: 87%; }
html>body { font-size: 14px; /* for FF */ }
#container { width: 37em; margin: 0 auto; position: relative; }
.datetime { font-size: 87%; font-weight: bolder; text-align: center; margin-bottom: 2em; }
.version { font-size: 73%; text-align: center; color: black; background: white; }
.version a { font-weight: bolder; color: black; text-decoration: none; }
h1 { color: #500000; border-bottom: 1px solid #999999; text-align: center; margin-bottom: 1em; margin-top: 2em; }
#alert { border: 1px solid red; padding: 0.2em 1.5em; margin: 1em 0; }
#progress { position: fixed; top: 0; left: 0; background: orange; color: black; padding: 0.2em 1em 0.2em 1em; }
.status_table { border: 1px solid #333333; border-collapse: collapse; width: 100%; }
.status_table td { color: #333333; border: 1px solid #444444; padding: 0.3em; }
.status_table td.headline { font-weight: bolder; background-color: #CFCCCC; padding: 0.4em 0.4em 0.3em 1.5em; }
.hidden { display: none !important; }
.$online { background-color: #D9FFB3; padding-left: 0.8em !important; }
.$offline { background-color: #FFB6B6; padding-left: 0.8em !important; }
.diagnostics { font-size: 84%; }
td.diagnostics { background-color: #EEE; padding-left: 1em; }
@media only screen and (max-width: 600px) {
#alert { padding: 0.2em 0.4em; }
#container { width: 100%; }
h1 { font-size: 150%; line-height: 1.2em; margin: .7em 0 .6em 0; }
.status_table td.headline { padding-left: 0.3em; }
}
--></style>
</head>
<body>
<div id="container">
EOH;
// end of $htmlheader_tmp
defaults($htmlheader, $htmlheader_tmp);

// HTML/page footer
defaults($htmlfooter, "</div>\n</body>\n</html>");

// ------------------------------------------------- main part of script

// if RSS feed is requested send $alertfile and quit
if ((isset($_REQUEST['rss']) && ($rssfeed))
	|| (isset($argv[1]) && $argv[1] == 'rss'))
{
	header("Content-Type: application/rss+xml");
	echo "<?xml version=\"1.0\" encoding=\"UTF-8\" ?><rss version=\"2.0\" xmlns:atom=\"http://www.w3.org/2005/Atom\">\n";
	echo "<channel>\n<atom:link href=\"$rssfeedurl\" rel=\"self\" type=\"application/rss+xml\" />\n";
	echo "<title>$rsstitle</title>\n<link>$rsslink</link>\n";
	echo "<description>$rsstitle</description>\n";
	echo "<language>en</language>\n";
	if (file_exists($alertfile) && is_readable($alertfile))
	{
		echo "<item>\n<title>Alert ".date($rssdatetime, filemtime($alertfile))
			. " for $rsstitle</title>\n";
		echo '<pubDate>'.date("r", filemtime($alertfile))."</pubDate>\n";
		echo "<link>$rsslink</link>\n";
		echo '<guid isPermaLink="false">#'.date("Ymd\THi", filemtime($alertfile))."</guid>\n";
		echo '<description><![CDATA[';
		@include($alertfile);
		echo "]]></description>\n</item>\n";
	}
	echo "</channel>\n</rss>\n";
	exit;
} else if (isset($_REQUEST['rss'])) {
	// if RSS was requested even though it was disabled
	exit;
}

// output HTML/page header
echo $htmlheader;

// headline, date and time and start of table
echo "<h1>$headline</h1>\n";
if ($datetime) echo '<p class="datetime">as of ' . date($datetime) . "</p>\n";

// show the contents of $alertfile if it is readable and larger than 7 bytes
if (file_exists($alertfile))
{
	clearstatcache(true, $alertfile);
	if ((is_readable($alertfile) && (filesize($alertfile) > 7)))
	{
		echo "<div id=\"alert\">\n";
		@include($alertfile);
		echo "</div>\n";
	}
}

// show a simple progress indicator
if (isset($_REQUEST['noprogress'])
	|| (isset($argv[1]) && $argv[1] == 'noprogress'))
{
	$progressindicator = false;
}
if ($progressindicator)
{
	echo '<script type="text/javascript">
    document.write("<div id=\"progress\">Checks in progress ...</div>");'
		. "</script>\n";
}

// flush output buffers (if any)/send content to browser
// note that this won't work if output is buffered by filters, proxies
// etc. of is gzip'ed; cf. https://stackoverflow.com/q/4191385/196133
// + https://stackoverflow.com/q/3445222/196133
// + https://stackoverflow.com/q/4481235/196133
echo $bufferfill;
@ob_flush(); @flush();

// $showdiagnostics is true w/ ?diags, and false with ?diags=off
if (isset($_GET['showdiagnostics']) || isset($_GET['diags']))
{
	if ($_GET['showdiagnostics'] == 'off' || $_GET['diags'] == 'off')
	{
		$showdiagnostics = false;
	} else {
		$showdiagnostics = true;
	}
}

echo "<table class=\"status_table\">\n";

// main loop of checks
foreach ($checks as $check)
{
	$status = $offline;  // default state
	$diagnostics = '';   // mouse-over for tooltips
	$output = true;      // print a line or print no line
	list($host,$port,$description) = explode('|',"$check||"); // the 2 extra '|'s are to avoid notices about undefined offsets
	$host = trim($host);
	$port = trim($port);

	switch ($port)
	{
		case '': // ignore lines with empty or no "ports", and ignore ...
		case (substr($port,0,1)=='-'): // negative ports, '-ping', and
			// any "port" starting with '-' is considered a disabled check
			$output = false; break;
		case 'headline': // print a headline within the status table
			// we enclose it with invisible <br>== == for nicer text output
			echo '<tr><td class="headline" colspan="2">'
				. '<span class="hidden">&nbsp;<br />==&nbsp;</span>'
				. $host
				. '<span class="hidden">&nbsp;==</span>'
				. "</td></tr>\n";
			$output = false; break;
		case 'ping': // do an ICMP ping
			$ping=exec("$ping_command $host",$pingoutput,$pingreturn);
		// Continues on into ping6 as they share all but the command.
		case 'ping6': // do an ICMP IPv6 ping
			if (!isset($ping))
			{
				$ping=exec("$ping6_command $host",$pingoutput,$pingreturn);
			}
			if(strlen($ping)>10)
			{
				// strlen($ping)>10 works around a bug in Debian ping (", pipe 3")
				// https://bugs.debian.org/456192
				$status = $online; $diagnostics = "$ping :: $pingreturn";
			}
			else $diagnostics = "$ping :: $pingreturn";
			// uncomment this if you want the full output as HTML comment
			//echo "\n<!-- "; print_r($pingoutput); echo "-->\n";
			//unset($pingoutput);
			// *nix ping command's return value meanings:
			// 0: all OK; 1: an error occured; 2: host unknown
			unset($ping);
			break;
		default: // look if a TCP connection to port can be opened
			$time_start = microtime(true);
			$fp = @fsockopen($host, $port, $errno, $errstr, $timeout);
			$time_end = microtime(true);
			$time = number_format(($time_end - $time_start)*1000,1);

			if ($fp)
			{
				// fsockopen worked, service is online
				$status = $online;
				$diagnostics = "$time ms";
				fclose($fp);
			}
			else if ($errno<0) { $diagnostics = "errno=$errno; Host unknown?"; }
			else { $diagnostics = $errstr; }
	}

	// output results
	if ($output)
	{
		$diagnostics = htmlspecialchars($diagnostics);
		if ($showdiagnostics && $diagnostics)
		{
			if ($status == $online && ($port != 'ping' && $port != 'ping6'))
			{
				echo "<tr><td>$description</td><td class=\"$status\">$status <span class=\"diagnostics\">($diagnostics)</span></td></tr>\n";
			} else {
				echo "<tr><td>$description</td><td class=\"$status\">$status</td></tr>\n";
				echo "<tr><td class=\"$status diagnostics\" colspan=\"2\">∵ $diagnostics</td></tr>\n";
			}
		} else {
			echo "<tr><td>$description</td><td class=\"$status\" title=\"$diagnostics\">$status</td></tr>\n";
		}
	}

	// flush output buffers (if any)/send content to browser
	@ob_flush(); @flush();
}

echo "</table>\n";

// make progress indicator disappear by means of Javascript
if ($progressindicator) {
	echo <<<EOT
<script>
progressindicator = document.getElementById("progress");
progressindicator.innerHTML = "Checks finished";
progressindicator.style.visibility = 'hidden';
</script>
EOT;
}

// output $version and HTML/page footer
if (!empty($version)) echo "<p class=\"version\">$version</p>\n";
echo "$htmlfooter\n";

/*
 * License
 *
 * This script is free software; you can redistribute it and/or modify it
 * under the terms of the GNU General Public License as published by
 * the Free Software Foundation; either version 2 of the License, or
 * (at your option) any later version.
 *
 * This script is distributed in the hope that it will be useful,
 * but WITHOUT ANY WARRANTY; without even the implied warranty of
 * MERCHANTABILITY or FITNESS FOR A PARTICULAR PURPOSE.  See the
 * GNU General Public License for more details.
 *
 * You should have received a copy of the GNU General Public License
 * along with this script; if not, write to the
 * Free Software Foundation, Inc., 59 Temple Place, Suite 330,
 * Boston, MA  02111-1307  USA

 * History
 *
 * 0.09 * 2009-10-26  first buggy "punish me" alpha release candidate
 * 0.10 * 2009-11-07  added stopwatch (time diagnostics) for online services
 * 0.11 * 2009-11-09  added RSS feed option for alerts
 * 0.12 * 2009-11-12  cleaned up and simplified settings mechanism
 * 0.13 * 2009-11-18  $alertfile is only included if larger than 2 bytes
 * 0.14 * 2009-12-05  default CSS code change to improve font size scaling
 * 0.15 * 2012-08-04  added ping6 (suggested by Todd Johnson; thanks!)
 * 0.15 * 2012-08-05  converted to HTML5, improved progress indicator, ...
 * 0.16 * 2012-08-13  removed @ from include($configfile)
 * 0.16 * 2014-04-25  charset UTF-8; viewport; added RSS guid+atom links
 * 0.16 * 2015-12-07  updated copyright date + URLs
 * 0.16 * 2019-01-22  buffering workarounds, responsive CSS, various tweaks
 * 0.16 * 2019-01-28  added $showdiagnostics to print errors in extra rows
 *
 */
