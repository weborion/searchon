<?php

/**
 * Yelp API v2.0 code sample.
 *
 * This program demonstrates the capability of the Yelp API version 2.0
 * by using the Search API to query for businesses by a search term and location,
 * and the Business API to query additional information about the top result
 * from the search query.
 * 
 * Please refer to http://www.yelp.com/developers/documentation for the API documentation.
 * 
 * This program requires a PHP OAuth2 library, which is included in this branch and can be
 * found here:
 *      http://oauth.googlecode.com/svn/code/php/
 * 
 * Sample usage of the program:
 * `php sample.php --term="bars" --location="San Francisco, CA"`
 */

// Enter the path that the oauth library is in relation to the php file
require_once('lib/OAuth.php');

// Set your OAuth credentials here  
// These credentials can be obtained from the 'Manage API Access' page in the
// developers documentation (http://www.yelp.com/developers)
$CONSUMER_KEY = "WtICDC3CBltvH0keZbd7iA";
$CONSUMER_SECRET = "n0FJbF0tuQ0hOFpMtEivLjaA-88";
$TOKEN = "O0IG-LrndC13s6UtLSmJKXaXu4X3DXOg";
$TOKEN_SECRET = "QNOP0khp5tfcfRPsU5Nm5yTwbOk";


$API_HOST = 'api.yelp.com';
$DEFAULT_TERM = 'dinner';
$DEFAULT_LOCATION = 'Washington, DC';
$SEARCH_LIMIT = 3;
$SEARCH_PATH = '/v2/search/';
$BUSINESS_PATH = '/v2/business/';


/** 
 * Makes a request to the Yelp API and returns the response
 * 
 * @param    $host    The domain host of the API 
 * @param    $path    The path of the APi after the domain
 * @return   The JSON response from the request      
 */
function request($host, $path) {
    $unsigned_url = "http://" . $host . $path;

    // Token object built using the OAuth library
    $token = new OAuthToken($GLOBALS['TOKEN'], $GLOBALS['TOKEN_SECRET']);

    // Consumer object built using the OAuth library
    $consumer = new OAuthConsumer($GLOBALS['CONSUMER_KEY'], $GLOBALS['CONSUMER_SECRET']);

    // Yelp uses HMAC SHA1 encoding
    $signature_method = new OAuthSignatureMethod_HMAC_SHA1();

    $oauthrequest = OAuthRequest::from_consumer_and_token(
        $consumer, 
        $token, 
        'GET', 
        $unsigned_url
    );
    
    // Sign the request
    $oauthrequest->sign_request($signature_method, $consumer, $token);
    
    // Get the signed URL
    $signed_url = $oauthrequest->to_url();
    
    // Send Yelp API Call
    $ch = curl_init($signed_url);
    curl_setopt($ch, CURLOPT_RETURNTRANSFER, true);
    curl_setopt($ch, CURLOPT_HEADER, 0);
    $data = curl_exec($ch);
    curl_close($ch);
    
    return $data;
}

/**
 * Query the Search API by a search term and location 
 * 
 * @param    $term        The search term passed to the API 
 * @param    $location    The search location passed to the API 
 * @return   The JSON response from the request 
 */
function search($term, $location,$latlonstring) {
    $url_params = array();
    
    $url_params['term'] = $term ?: $GLOBALS['DEFAULT_TERM'];
    $url_params['location'] = $location?: $GLOBALS['DEFAULT_LOCATION'];
    $url_params['limit'] = $GLOBALS['SEARCH_LIMIT'];
    $url_params['cll'] = $latlonstring ? $latlonstring : "";
//cll=latitude,longitude
    $search_path = $GLOBALS['SEARCH_PATH'] . "?" . http_build_query($url_params);
    //echo $search_path;exit;
    
    return request($GLOBALS['API_HOST'], $search_path);
}

/**
 * Query the Business API by business_id
 * 
 * @param    $business_id    The ID of the business to query
 * @return   The JSON response from the request 
 */
function get_business($business_id) {
    $business_path = $GLOBALS['BUSINESS_PATH'] . $business_id;
    
    return request($GLOBALS['API_HOST'], $business_path);
}

/**
 * Queries the API by the input values from the user 
 * 
 * @param    $term        The search term to query
 * @param    $location    The location of the business to query
 */
function query_api($term, $location,$cll) {   
    //return search($term, $location,$cll);
    /*$response = json_decode(search($term, $location,$cll));
    
    $business_id = $response->businesses[0]->id;
    
    print sprintf(
        "%d businesses found, querying business info for the top result \"%s\"\n\n",         
        count($response->businesses),
        $business_id
    );
    
    $response = get_business($business_id);
    
    print sprintf("Result for business \"%s\" found:\n", $business_id);
    print "$response\n";*/
    
    $response = json_decode(search($term, $location,$cll));
    //echo "<pre>".print_r(count($response->businesses),true)."</pre>";exit;
    $return_html="<ul class='news-items'>";
    $rec_cnt=0;
    if(count($response->businesses) > 0)
    {
	    foreach($response->businesses as $business)
	    {
	    $return_html.="<li>
	
	                  <div class='news-item-date'> <span class='news-item-day'><img src='".$business->image_url."'  height='70' width='70'/></span><span class='news-item-month'><img src='".$business->rating_img_url."' /></span> </div>
	                  <div class='news-item-detail'>".$business->name."<a href='".$business->url."' class='news-item-title' target='_blank'></a>
	                   <p class='news-item-preview'>".$business->snippet_text."</p>
	                  </div>
	
	                </li>";
	                $rec_cnt++;
	                
	    }
     }    
    else
    {
    	$return_html.="<li>No Search Results to show</li>";
    
    }
    $return_html.="</ul>";
    
    return $return_html;
}
function query_api_map($term,$location,$cll) {   
   //echo "<pre>".print_r(search($term, $location,$cll),true)."</pre>";exit;
    $response = json_decode(search($term, $location,$cll));
    //echo "<pre>".print_r($response,true)."</pre>";exit;
    $pins=array();
    if(count($response->businesses) > 0)
    {
	    foreach($response->businesses as $business)
	    {
	    //var_dump($business->location->coordinate->latitude);
	    //var_dump($business->location->address[0]);
	  $pins[]=array($business->name,$business->location->coordinate->latitude,$business->location->coordinate->longitude,$business->location->address['0']);
	    }
	}    
    return json_encode($pins);
}
$term= $_GET['term'];$location=$_GET['location'];$cll=$_GET['cll'];
if($_GET['type'] == "map")
{
	echo query_api_map($term, $location,$cll);
	exit(0);
}


/**
 * User input is handled here 
 */
/*$longopts  = array(
    "term::",
    "location::",
);
    
$options = getopt("", $longopts);

$term = $options['term'] ?: '';
$location = $options['location'] ?: '';*/



echo query_api($term, $location,$cll);
