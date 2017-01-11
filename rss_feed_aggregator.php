<?php
/*
============================================================================================
Filename: 
---------
rss_feed_aggregator.php

Description: 
------------
This PHP file provides functions that will get the input about different RSS feed
providers and then read the requested number of RSS feeds from those providers.

This program requires an input file (rss_feed_sources.xml) to be present in the
same directory where this PHP file is executed from. This XML file should contain
information about a list of RSS feed sources. This input file can be customized as needed.

Author:
-------
Senthil Nathan (sen@us.ibm.com)

License:
--------
This code (from IBM developerWorks) is made available free of charge with the rights to use,
copy, modify, merge, publish and distribute. This Software shall be used for Good, not Evil.

First Created on:
-----------------
Jan/01/2008

Last Modified on:
-----------------
Jan/02/2008
============================================================================================
*/
require_once("rss_feed_reader.php");

// XML filename from where details about RSS feed providers are read from.
define ("RSS_FEED_SOURCES_FILE_NAME", "rss_feed_sources.xml");
// Directory name where the feed results will be stored.
define ("RSS_FEED_RESULTS_DIRECTORY", "feed_results");
// Suffix part of the result file name. A 3 digit number will be
// prepended to this.
define ("RSS_FEED_RESULTS_FILE_NAME_SUFFIX", "_rss_feed_items.txt");
// File attribute.
define ("FILE_CREATE_WRITE_FLAG", "x");
// A newline character
define ("NEW_LINE", "\n");
// Label name for the feed provider sequence in the input file.
define ("FEED_PROVIDER_SEQUENCE_NUMBER", "Feed Provider Sequence Number: ");
// Label name for the feed provider l.
define ("RSS_FEED_PROVIDER_NAME", "RSS Feed Provider: ");
// Label name for the number of feeds received.
define ("RECEIVED_RSS_FEEDS_CNT", "Number of RSS feed items received: ");
// RSS feed item separator line.
define ("FEED_ITEM_SEPARATOR_LINE", 
	"==================================================================");
// Label name for feed item number
define ("FEED_ITEM_SEQUENCE_NUMBER", "Feed Item: ");
// Label name for Title
define ("FEED_ITEM_TITLE", "Title: ");
// Label name for URL
define ("FEED_ITEM_URL", "URL: ");
// Label name for Description
define ("FEED_ITEM_DESCRIPTION", "Description: ");
/*
============================================================================================
Function name:
--------------
get_list_of_rss_providers

First created on: 
-----------------
Jan/01/2008

Last modified on: 
-----------------
Jan/02/2008

Function input parameter(s):
----------------------------
1) Feed sources input filename.

Function return value:
----------------------
It returns the contents of the XML input file as a String.

Description:
------------
This function will attempt to read the contents of an XML file (rss_feed_sources.xml).
This XML file is expected to be in the same directory from where this program is
executed. It will read the entire file and store the file contents in a string and
return that string.
============================================================================================
*/
function get_list_of_rss_feed_sources($input_xml_file) {
	//Read the XML contents from the input file.
	file_exists($input_xml_file) or die('Could not find file ' . $input_xml_file);
	$xml_string_contents = file_get_contents($input_xml_file); 
	// Return the XML contents now to the caller.
	return($xml_string_contents);
} // End of function get_list_of_rss_feed_sources

/*
============================================================================================
Function name:
--------------
store_rss_feed_results

First created on: 
-----------------
Jan/01/2008

Last modified on: 
-----------------
Jan/02/2008

Function input parameter(s):
----------------------------
1) An integer value for the file sequence number.
2) An array containing all the received RSS feeds from a feed source.

The second parameter is a nested array whose format is shown below.
a[0] = RSS feed provider name
a[1] = Number of feed items sent in the output tuple
a[2] = (rss_feed_title_array) An array of RSS feed item titles 
a[3] = (rss_feed_url_array) An array of RSS feed item URLs
a[4] = (rss_feed_description_array) An array of RSS feed item descriptions

Contents in each index of the three arrays a[2], a[3] and a[4] put together 
will provide all the information related to one particular RSS feed item 
present in the received RSS XML. 
For example: Information in rss_feed_title[0], 
rss_feed_url[0] and rss_feed_description[0] combined together 
corresponds to the first RSS feed item in the received 
RSS XML content i.e.

<item>
  <title>...</title>
  <link>...</link>
  <description>...</description>
</item>

Function return value:
----------------------
None

Description:
------------
This function will write the RSS feed results received from a feed source into a
results file.
============================================================================================
*/
function store_rss_feed_results($file_sequence_number, $result_array) {
	// Let us first check if a subdirectory named "feed_results" exists.
	if (file_exists(RSS_FEED_RESULTS_DIRECTORY) == false) {
		// Directory doesn't exist. Create it now.
		mkdir(RSS_FEED_RESULTS_DIRECTORY);		
	} // End of if (file_exists(RSS_FEED_RESULTS_DIRECTORY) == false)
	
	// Form the file name.
	$result_file_name = sprintf("%s/%03d%s", RSS_FEED_RESULTS_DIRECTORY,
		$file_sequence_number, RSS_FEED_RESULTS_FILE_NAME_SUFFIX);
	
	// If this file already exists from previous runs, simply delete it.
	// We will overwrite it with the latest feed data.
	if (file_exists($result_file_name) == true) {
		unlink($result_file_name);
	}

	// Create and open the file.
	$handle = fopen($result_file_name, FILE_CREATE_WRITE_FLAG);
	
	if ($handle == false) {
		// File creation failed. Return now.
		return;
	} 
	
	// We can start writing the received RSS feeds into this file.
	// Write the Feed provider sequence number.
	$feed_provider_number = FEED_PROVIDER_SEQUENCE_NUMBER . 
		$file_sequence_number . NEW_LINE;
	fwrite($handle, $feed_provider_number);
	// Write the Feed provider name.
	$feed_provider_name = RSS_FEED_PROVIDER_NAME . $result_array[0] . NEW_LINE;
	fwrite($handle, $feed_provider_name);
	// Write the number of feed items received.
	$number_of_received_rss_feeds = RECEIVED_RSS_FEEDS_CNT . 
		$result_array[1] . NEW_LINE;
	fwrite($handle, $number_of_received_rss_feeds);
	
	$rss_feed_title_array = $result_array[2];
	$rss_feed_url_array = $result_array[3];
	$rss_feed_description_array = $result_array[4];
	
	// Stay in a loop and write the title, URL and Description.
	for($cnt=0; $cnt < sizeof($rss_feed_title_array); $cnt++) {
		$feed_item_separator = FEED_ITEM_SEPARATOR_LINE . NEW_LINE;
		fwrite($handle, $feed_item_separator);
		$feed_item_sequence_number = FEED_ITEM_SEQUENCE_NUMBER . 
			($cnt+1) . NEW_LINE;
		fwrite($handle, $feed_item_sequence_number);
		$feed_item_title = FEED_ITEM_TITLE . 
			$rss_feed_title_array[$cnt] . NEW_LINE;
		fwrite($handle, $feed_item_title);
		$feed_item_url = FEED_ITEM_URL .
			$rss_feed_url_array[$cnt] . NEW_LINE;
		fwrite($handle, $feed_item_url);
		$feed_item_description = FEED_ITEM_DESCRIPTION . NEW_LINE .
			$rss_feed_description_array[$cnt] . NEW_LINE;
		fwrite($handle, $feed_item_description);		
	} // End of for($cnt=0; $cnt < sizeof($rss_feed_title_array), $cnt++)

	$feed_item_separator = FEED_ITEM_SEPARATOR_LINE . NEW_LINE;
	fwrite($handle, $feed_item_separator);	
	fclose($handle);
} // End of function store_rss_feed_results

/*
============================================================================================
Function name:
--------------
aggregate_rss_feeds

First created on: 
-----------------
Jan/01/2008

Last modified on: 
-----------------
Jan/02/2008

Function input parameter(s):
----------------------------
1) It takes an optional feed sources input filename as input parameter.
   If no filename is given as a parameter, it will use a default filename.

Function return value:
----------------------
None

Description:
------------
This function will read the RSS providers' names from an input XML file and then
it will iterate in a loop to fetch the required number of the latest RSS feeds from
each feed source. For all the RSS feeds fetched from a feed provider, it will store 
them in a file named using the sequence number of the order in which a feed source is
specified in the input XML file.

For the sake of simplicity, this program reads the feed source information from an 
input file and writes the feed results to an output file. However, real-life needs
may dictate different forms of input and output such as database, web forms etc.
It is easy to support those input and output forms using PHP.
============================================================================================
*/
function aggregate_rss_feeds($input_xml_file = RSS_FEED_SOURCES_FILE_NAME) {	
	// Declare a variable to track the current 
	// RSS feed source being processed.
	$feed_source_sequence_number = 0;
	// Let us get the list of RSS feed sources.
	// In our case, we will read them from an input file.
	$xml_string_contents = get_list_of_rss_feed_sources($input_xml_file);
	
	/*
	We will tap into the elegance of the PHP SimpleXML API to
	parse these RSS feeds encoded in XML format.	
	*/
	// To begin with, load the XML string to get a SimpleXML object representation.
	$xml = simplexml_load_string($xml_string_contents);
	
	// Is it a valid XML document.
	if ($xml == false) {
		print ("Sorry. Your RSS feed sources input file contains invalid data.\n");
		// XML parsing error. Return now.
		return;
	} // End of if ($xml == false)	
	
	print ("\n");
	
	/*
	Stay in a loop and get the RSS feeds from each source.
	The document root element of the input xml file is <ListOfRssFeedSources>
	Under the root element, we will have one or more blocks of data with the
	following format.
	
	<RssFeedSourceInfo>
          	<rssFeedProviderName>....</rssFeedProviderName>
     	    <rssFeedProviderUrl>....</rssFeedProviderUrl>
    	    <maximumRssItemsToBeReturned>....</maximumRssItemsToBeReturned>
 	</RssFeedSourceInfo>	
 	
 	We are going to iterate over all the <RssFeedSourceInfo> elements.
	*/
	foreach ($xml->RssFeedSourceInfo as $feed_source) {
		// Read the details about the next feed source from the input file.
		$feed_source_sequence_number++;		
		$rss_provider_name = trim(strval($feed_source->rssFeedProviderName));
		$rss_provider_url = trim(strval($feed_source->rssFeedProviderUrl));
		$max_rss_items_required	= trim(strval($feed_source->maximumRssItemsToBeReturned));	
		print ("Getting RSS feeds from $rss_provider_name ...\n");
		
		// Go and get the RSS feeds now from this feed source.
		$rss_feeds_result_array = 
			get_rss_feeds($rss_provider_name, $rss_provider_url, $max_rss_items_required);
			
		if (empty($rss_feeds_result_array) == false) {
			// We will store only if we receive one or more RSS feed results.
			// The result array format is explained in the store function called below.
			store_rss_feed_results($feed_source_sequence_number, 
				$rss_feeds_result_array);
		} // End of if (empty($rss_feeds_result_array) == false)
	} // End of foreach ($xml->RssFeedSourceInfo as $feed_source)
	
	print ("\nFinished getting RSS feeds from $feed_source_sequence_number feed sources.\n\n");
	print ("You can view the received feed items in the .\feed_results directory.\n\n");
	print ("Feeds from each active feed source are stored in separate files.\n\n");
	print ("These files are named NNN_rss_feed_items.txt, where NNN corresponds to\n" .
	       "the sequence number of the order in which the feed source is\n" .
	       "listed in your $input_xml_file file.\n");
} // End of function aggregate_rss_feeds

// Program execution starts here.
$feed_sources_xml_file = null;

// Read the optional input filename from the command line if it is given.
// Syntax: php -f rss_feed_aggregator.php my_feed_sources.xml
// If there is no input file specified as a command-line argument, then
// it will try to open a default file name called rss_feed_sources.xml
// In PHP, argv[0] will have the name of the PHP program being run.
// argv[1] and above will have the command-line arguments.
if ($argc >= 2) {
	$feed_sources_xml_file = $argv[1];
} // End of if ($argc >= 2)

// Let us put the feed aggregator to work now.
if ($feed_sources_xml_file == null) {
	// No input xml file specified. We will use a default file name.
	aggregate_rss_feeds();
} else {
	// We will use the user-specified feed sources input file.
	aggregate_rss_feeds($feed_sources_xml_file);
}
?>