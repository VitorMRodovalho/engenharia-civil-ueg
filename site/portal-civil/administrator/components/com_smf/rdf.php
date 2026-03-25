<?php
/**
* @version $Id: rdf.php,v 1.0 2005/03/28 01:13:25 Cowboy1015 Exp $
* @package com_Joomla_smf
* @copyright (C) JoomlaHacks.com
* @license http://www.gnu.org/copyleft/gpl.html GNU/GPL
* Visit JoomlaHacks.com for more Joomla hacks!
*/

class rdf {

	function createRDF($url, $timeToLive) {
  		global $mosConfig_absolute_path;

  		$basename = str_replace("?","&", $mosConfig_absolute_path."/cache/".basename($url)) ;

  		if (!file_exists($basename)) {
  			touch($basename, ($timeToLive+1)); // Make it older then the time to live...
  			chmod($basename, 0777) ;
  		}
  	}

	// This function will read the data in from the passed in URL if it is required.
  	function getRDF($url, $timeToLive) {
  		global $mosConfig_absolute_path;
  		$timeToLive *= 60; // convert timeToLive into secs
  		$basename = str_replace("?","&", $mosConfig_absolute_path."/cache/".basename($url)) ; // Get the basename of URL for the cached filename. We do this here, cos we need to do it more than once

  		$this->createRDF($url, $timeToLive);

  		$timestamp = filemtime($basename); // Get the timestamp of the file.
  		$age = (time() - $timestamp); // Work out how old the file is that we have already..

		// If the file is too old, then we need to refresh it from the URL
  		if($age > $timeToLive) {
  			$ip = gethostbyname(substr($url,strpos($url,'www'),strpos($url,'.com')-3));
  			if ($ip == substr($url,strpos($url,'www'),strpos($url,'.com')-3)) {
  				return false;
  			}
  			$rdfHandle = fopen($url,"r"); // Open the RDF file for reading
  			$rdfData = fread($rdfHandle, 64000) ; // Read in the RDF data. 64K limit on filesize, should be enough.
 			fclose($rdfHandle); // Close the Data feed

  			// OK there is more recent news, so rewrite the cached news file..
  			$localFile = fopen($basename, "w") ; // Open the local file for writing
  			fwrite($localFile, $rdfData ) ; // Pump in all the data into the file.
  			fclose($localFile) ; // Close the local file after writing to it
  		} // end IF
  		return true;
  	} // end getRDF

	// Removes spurious tags from the link that we don't want
  	function formatLink($item) {
 		$link = ereg_replace(".*<link>","",$item); // Remove the leading <link> tags
  		$link = ereg_replace("</link>.*","",$link); // Remove the closing </link> tags
  		$title = ereg_replace(".*<title>","",$item); // Remove the leading <title> tags
  		$title = ereg_replace("</title>.*","",$title); // Remove the closing </title> tags

  		if ($title) // If we got anything left after all that trimming...
  					// Choose how you want the link formatted here... This has no underline, and opens in a new window...
  			echo "<a href=\"$link\" style=\"text-decoration:none\"
  				target=\"_blank\">$title</a><br>";
  	} // end formatLink

  	function displayRDF($rdf, $randomise = True, $numLinks = 3, $title) {
  		global $mosConfig_absolute_path;
  		$rdf = $mosConfig_absolute_path."/cache/".$rdf;
  		$localFile = fopen($rdf, "r"); 	// OK open up the local rdf file for reading
  		clearstatcache() ; 				// Clear out the file size cache
  		if (filesize($rdf) <=0) {
  			return false;
  		}
  		$rdfData = fread($localFile, filesize($rdf)); // Read in the data to memory
  		fclose($localFile); 			// Close down the open file.

  		// Get rid of all spurious leading and closing rdf data from the data in memory
  		$rdfData = ereg_replace("<\?xml.*/image>","",$rdfData);
  		$rdfData = ereg_replace("</rdf.*","",$rdfData);
  		$rdfData = ereg_replace("[\r,\n]", "", $rdfData);
  		$rdfData = chop($rdfData); 	// Strip any whitespace from the end of the string

  		$rdfArray = explode("</item>",$rdfData); // Split up the string into an array to make it more manageable
  		$max = sizeof($rdfArray); 				// See how many items we have got

		// Echo the font formatting... This is just HTML to make it look a little pretty
  		if ($max > 1) {
  			echo "<font face=\"verdana, arial, helvetica\" size=\"1\">
  			<list> " ;

			// We need to do different stuff if we are to randomise the links...
  			// The links will be randomised so we want a different message to the user....
  			// The max -1 is to compensate for the 0 indexed array structure..
  			if ($randomise) {
 				echo "Displaying $numLinks (of " . ($max-1) . ") random headlines from $rdf... Updated every 30 minutes.. Refresh for some more!<br>";
  				$links = array_rand( $rdfArray, $numLinks ) ; // OK select the keys of the links at random from the array..
  				$upperLimit = $numLinks ; // Set this to the number of links to be displayed
			}
			else {
  				echo "<b>".$title."</b>" ;
  				$links = array_keys($rdfArray) ; // Give the keys to be displayed all the links we have parsed..
  				$upperLimit = $max ; // Set the upper Limit to be all of the headlines
  			}

  			// Display the links...
  			for ($i = 0 ; $i < $upperLimit ; $i++ )
  				$this->formatLink($rdfArray[$links[$i]]);

  			// Close the font formatting like a good html coder ;)

  			echo "</font>" ;
  		}
  		else {
  			echo "" ;
  	 	}
  	 	return true;
  	}

}
?>