<?php
/*
============================================================================================
Filename: 
---------
rss_feed_reader.php

Description: 
------------
This PHP file provides functions that will read the RSS feeds available at a given URL.
It uses the CURL and SimpleXML PHP library functions to perform this task.

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
Feb/15/2008
============================================================================================
*/

/*
============================================================================================
Function name:
--------------
perform_curl_operation

First created on: 
-----------------
Jan/01/2008

Last modified on: 
-----------------
Jan/02/2008

Function input parameter(s):
----------------------------
1) A string reference to the remote URL

Function return value:
----------------------
It returns the CURL result (content obtained from the remote URL) to the caller.

Description:
------------
This function initializes the CURL session and then sets the required CURL options.
It assigns CURL options such as URL, HEADER, FOLLOWLOCATION and RETURNTRANSFER.
Then it executes the curl operation and waits for it to complete. While it is
waiting, the CURL will be collecting the response data arriving from the remote URL. 
When the curl operation is completed, it does a the CURL cleanup.
============================================================================================
*/
function perform_curl_operation(& $remote_url) {
	$remote_contents = "";
	$empty_contents = "";

	// Initialize a cURL session and get a handle.
  	$curl_handle = curl_init();

  	// Do we have a cURL session?
  	if ($curl_handle) {
		// Set the required CURL options that we need.
	  	// Set the URL option.
	  	curl_setopt($curl_handle, CURLOPT_URL, $remote_url);
	  	// Set the HEADER option. We don't want the HTTP headers in the output.
	  	curl_setopt($curl_handle, CURLOPT_HEADER, false);
	  	// Set the FOLLOWLOCATION option. We will follow if location header is present.
	  	curl_setopt($curl_handle, CURLOPT_FOLLOWLOCATION, true);
	  	// Instead of using WRITEFUNCTION callbacks, we are going to receive the
	  	// remote contents as a return value for the curl_exec function.
	  	curl_setopt($curl_handle, CURLOPT_RETURNTRANSFER, true);
	  
	  	// Try to fetch the remote URL contents.
	  	// This function will block until the contents are received.
	  	$remote_contents = curl_exec($curl_handle);
	  	// Do the cleanup of CURL.
	  	curl_close($curl_handle);
	  
	  	// Check the CURL result now.
	  	if ($remote_contents != false) {
	  		return($remote_contents);
	  	} else {
	  		return($empty_contents);
	  	} // End of if ($remote_contents != false)  	
  	} else {
 		// Unable to initialize cURL.
 		// Without it, we can't do much here.
  		return($empty_contents);
  	} // End of if ($curl_handle)
} // End of function perform_curl_operation

/*
============================================================================================
Function name:
--------------
get_rss_feeds

First created on: 
-----------------
Jan/01/2008

Last modified on: 
-----------------
Jan/02/2008

Function input parameter(s):
----------------------------
1) A string reference to the RSS Provider name
2) A string reference to the RSS Provider URL
3) A reference to an integer value that tells how manay RSS items to be obtained

Function return value:
----------------------
a) In case of any errors, this function will return an empty array.

b) On success, it returns a nested array with the following values in it.

a[0] = RSS feed provider name
a[1] = Number of feed items sent in the output tuple
a[2] = (rss_feed_title_array) An array of RSS feed item titles 
a[3] = (rss_feed_url_array) An array of RSS feed item URLs
a[4] = (rss_feed_description_array) An array of RSS feed item descriptions

Description:
------------
This function will attempt to connect to the feed provider, 
fetch the RSS feed XML file and then parse the feed items 
(0 to maximum number of feed items required by the user).
If everything goes well, this function will return an 
output array as shown above.

Contents in each index of th three arrays a[2], a[3] and a[4] put together 
will provide all the information related to one particular RSS feed item 
present in the received RSS XML. 
For example: Information in rss_feed_title[0], 
rss_feed_url[0] and rss_feed_description[0] correspond to the 
first RSS feed item in the received RSS XML content i.e.

<item>
  <title>...</title>
  <link>...</link>
  <description>...</description>
</item>
============================================================================================
*/
function get_rss_feeds(& $rss_provider_name, & $rss_provider_url, & $max_rss_items_required) {	
	// Check if the max_rss_items_required is 0
	if ($max_rss_items_required <= 0) {
		// Return an empty array.
		$empty_array = array();
		return($empty_array); 			
	} // End of if ($max_rss_items_required <= 0)
	
	// Let us go ahead and fetch the RSS contents from the given RSS provider.
	$received_rss_feeds = perform_curl_operation($rss_provider_url);
	
	// At times, if the XML data is not properly utf8 encoded,
	// it possibly could fail in parsing. Let us encode it properly.
	$received_rss_feeds = utf8_encode($received_rss_feeds);	
	
	// Is it empty?
	if (empty($received_rss_feeds)) {
		// Return an empty array.
		$empty_array = array();
		return($empty_array); 	
	} // End of if (empty($received_rss_feeds))
	
	// We have a non-empty result from the RSS feed provider.
	// Create three empty arrays to hold the values from the received rss feed items.
	$rss_feed_title_array = array();
	$rss_feed_url_array = array();
	$rss_feed_description_array = array();

	// We can now parse the individual RSS feed items.
	$parser_result = parse_rss_feed_xml($received_rss_feeds, $max_rss_items_required,
		$rss_feed_title_array, $rss_feed_url_array, $rss_feed_description_array);
	
	// Check if we were able to parse the RSS feed XML content.
	if ($parser_result == true) {
		// We have successfully parsed the RSS feed results.
		// Create an array and fill it with the results as 
		// described in the function description comments above.
		$result_array = array();
		// Send the rss provider name.
		$result_array[0] = $rss_provider_name;
		// Tell how many rss feed items are being returned. 
		$result_array[1] = sizeof($rss_feed_title_array);
		// Send the array containing different RSS feed titles.
		$result_array[2] = $rss_feed_title_array;
		// Send the array containing different RSS feed URLs.
		$result_array[3] = $rss_feed_url_array;
		// Send the array containing different RSS feed descriptions.
		$result_array[4] = $rss_feed_description_array;
		// Return the result array now.
		return($result_array);		
	} else {
		// We were not successful in parsing the RSS feed items.
		// Return an empty array as the result.
		$empty_array = array();
		return($empty_array); 			
	} // End of if ($parser_result == true)
} // End of function get_rss_feeds

/*
============================================================================================
Function name:
--------------
parse_rss_feed_xml

First created on: 
-----------------
Jan/01/2008

Last modified on: 
-----------------
Jan/02/2008

Function input parameter(s):
----------------------------
1) A string reference to RSS feed XML string received from the Web
2) A integer reference to the required number feed items.
3) A reference to an array for storing the titles in the received RSS feed
4) A reference to an array for storing the URLs in the received RSS feed
5) A reference to an array for storing the descriptions in the received RSS feed

Function return value:
----------------------
This function returns true if the RSS XML parsing is successful.
Else, it returns false.

Description:
------------
This function contains the logic to parse the RSS XML
contents received from the Web. It collects all the values
for the following child elements under the parent element <item> .
a) <title>
b) <link>
c) <description>

It stores (a), (b) and (c) in the respective array references passed 
to this function, so that the caller can receive them as a 
result of calling this function.
============================================================================================
*/
function parse_rss_feed_xml(& $received_rss_feeds, & $max_rss_items_required,
& $rss_feed_title_array, & $rss_feed_url_array, & $rss_feed_description_array) {
	/*
	We will tap into the elegance of the PHP SimpleXML API to
	parse these RSS feeds encoded in XML format.
	
   	There are multiple versions of RSS out there namely 0.91, 0.92, 1.0 and 2.0
    The basic difference between these versions comes down to one of the following two formats.
    
    1) <rss><channel><item>...</item><item>...</item><item>...</item></channel></rss>
    2) <rdf><channel>...</channel><item>...</item><item>...</item><item>...</item></rdf>
    
    In format 1, <item> elements are the children of the <channel> element.
    In format 2, <item> elements are direct children of the root element <rss> or <rdf>.
    In other words, in format 2, <item> elements are siblings of the <channel> element. 
    RSS version 1.0 uses format 2, whereas all the other versions follow format 1.
    In both these formats, we are interested only in the children between <item>...</item>.
    Our parsing logic here should handle both of these formats.	
	*/	
	// To begin with load the XML string to get a SimpleXML object representation.
	$xml = simplexml_load_string($received_rss_feeds);

	// Is it a valid XML document.
	if ((is_object($xml) == false) || (sizeof($xml) <= 0)) {	
		// XML parsing error. Return now.
		return(false);
	} // End of if ((is_object($xml) == false) ...
	
	// Now we have to determine, if we have the <item> elements as the 
	// children of the <channel> element i.e. Format 1 above or
	// if we have the <item> elements as the direct children of the 
	// <rss> or <rdf> root element i.e. Format 2 above.
	$obj1 = $xml->item;
	
	if ((is_object($obj1) == false) || (sizeof($obj1) <= 0)) {
		// <item> elements are not direct children of the document root element.
		// In that case, it is not format 2. It should be as in format 1.
		// Move to the <channel> element so that that will be our new root.
		$xml = $xml->channel;
	} // End of if ((is_object($obj1) == false) ...
	
	// Check for XML validity one more time from we can parse this.
	if ((is_object($xml) == false) || (sizeof($xml) <= 0)) {
		// XML parsing error. Return now.
		return(false);
	} // End of if ((is_object($xml) == false) ...	
	
	// Initialize a variable to count the <item> elements retrieved.
	$count_of_rss_items_retrieved = 0;
	
	// Stay in a loop and collect the details from the <item> elements.
	foreach ($xml->item as $item) {
    	// At this stage, we have access to the <item> elements one at a time.
    	// We don't know how many <item> elements are there. 
    	// Let us read the title, link and description elements.
    	$rss_feed_title = trim(strval($item->title));
    	$rss_feed_url = trim(strval($item->link));
    	$rss_feed_description = trim(strval($item->description));
    	// Let us now add these values to the array references we have.
    	array_push($rss_feed_title_array, $rss_feed_title);
    	array_push($rss_feed_url_array, $rss_feed_url);
    	array_push($rss_feed_description_array, $rss_feed_description);
    	// We have to filter out specific number of <item> elements 
    	// as required by the user. Let us try to do that now.		
    	$count_of_rss_items_retrieved++;    	
    	
    	if ($count_of_rss_items_retrieved >= $max_rss_items_required) {
    		// Exit from this loop now.
    		break;
    	} // End of if ($count_of_rss_items_retrieved >= $max_rss_items_required)
	} // End of foreach ($xml->item as $item)
	
  	if ($count_of_rss_items_retrieved > 0) {
    	// At last, it turned out to be fruitful.
    	return(true);
  	} else {
    	// All the hard work didn't yield anything.
    	// Better luck next time.
    	return(false);
  	} // End of if ($count_of_rss_items_retrieved > 0)	
} // End of function parse_rss_feed_xml

// Uncomment the following code block to do a stand-alone test of this program.
/*
$rss_provider_name = "Yahoo: US Market News";
$rss_provider_url = "http://finance.yahoo.com/rss/usmarkets";
$max_rss_items_required = 12;

$rss_results_array = get_rss_feeds($rss_provider_name, $rss_provider_url, $max_rss_items_required);
var_dump($rss_results_array);
*/
?> 