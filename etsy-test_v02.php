<?php
define('API_KEY', 'Enter Here');	
define('STORENAME', 'naturalbeautyjewelry');
define('STOREID', 'Add Store ID');
$ETSY_FILE_NAME = 'etsy-listings.txt';
$ETSY_LISTINGS_URL = "https://openapi.etsy.com/v2/shops/". STOREID . "/listings/active?includes=MainImage&category_path=Cuff&api_key=" . API_KEY;

// REQUEST
function request_etsy_info($url){
	$ch = curl_init($url);
	curl_setopt($ch, CURLOPT_RETURNTRANSFER, true);
	$response_body = curl_exec($ch);
	$status = curl_getinfo($ch, CURLINFO_HTTP_CODE);
	if (intval($status) != 200) throw new Exception("HTTP $status\n$response_body");
	
	return $response_body;
}

function get_url($url) {
	$ch = curl_init();
	curl_setopt($ch,CURLOPT_URL,$url);
	curl_setopt($ch,CURLOPT_RETURNTRANSFER,1); 
	curl_setopt($ch,CURLOPT_CONNECTTIMEOUT,5);
	$content = curl_exec($ch);
	curl_close($ch);
	return $content;
}


/* gets the contents of a file if it exists, otherwise grabs and caches */
function get_content($file,$hours = 24,$url) {
	//vars
	//if (file_exists($file)) {
		$current_time = time(); 
		$expire_time = $hours * 60 * 60; 
		$file_time = filemtime($file);
	/*}else{
		print 'failure finding cached file: ' . file_exists($file) . '<br>';
		return;
	}*/
	
	/*$content = get_url($url);
	print "this: ".$content;
	return;
	*/
	//decisions, decisions
	if(file_exists($file) && ($current_time - $expire_time < $file_time)) {
		print '<br>here2<br>';
		//echo 'returning from cached file';
		return file_get_contents($file);
	} else {
		print '<br>here4<br>';
		//$content = request_etsy_info($url); // . '<br><br><!-- cached:  '.time().'-->';
		$content = get_url($url);
		file_put_contents($file,$content);
		//echo 'retrieved fresh from '.$url.':: '.$content;
		return $content;
	}
}

/* usage */
$ETSY_LISTINGS = get_content($ETSY_FILE_NAME,1,$ETSY_LISTINGS_URL);

//print 'listings: ' . $ETSY_LISTINGS. '<br><br>';

//$response = json_encode($ETSY_LISTINGS);
//$response = array();
//var_dump(json_decode($ETSY_LISTINGS, true));
$response = json_decode($ETSY_LISTINGS, true);
if(!is_array($response)){
	print 'poop';
}else{
	$i=0;
	foreach ($response['results'] as $arr){
		echo '<b>ARR ['.$i.']</b>: '.$arr['listing_id'].'<br>';
		echo 'title: '.$arr['title'].'<br>';
		echo 'description: '.$arr['description'].'<br>';
		echo 'price: '.$arr['price'].'<br>';
		echo 'category_path: ';
		foreach($arr['category_path'] as $cat){
			echo $cat.' / ';
		}
		echo '<br>';
		echo 'featured_rank: '.$arr['featured_rank'].'<br>';
		// Main Image
		echo 'MainImage: [75x75] <img src="'.$arr['MainImage']['url_75x75'].'" alt="75x75" /> // '.
			'[170x135] <img src="'.$arr['MainImage']['url_170x135'].'" alt="75x75" /> // '.
			'[570xN] <img src="'.$arr['MainImage']['url_570xN'].'" alt="570xN" /> // '.
			//'[fullxfull] <img src="'.$arr['MainImage']['url_fullxfull'].'" alt="fullxfull" /> // '.
			'<br>';
		/*foreach($arr['MainImage'] as $mai){
			echo $mai.' / ';
		}*/
		echo '<br><br>';
		$i++;
	}
	//print 'zing: '. $response['results'][0]['listing_id'];
	//print_r($response);
}
//print $response;
//print 'responder: '.$response[0];
//print 'coo: '.$wColor;
//$booboo = $response->results[0];
//print 'Honey Boo-boo Child says...' .$booboo->listing_id;
?>
