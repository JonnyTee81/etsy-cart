<?php
define('API_KEY', 'Enter Here');	
define('STORENAME', 'naturalbeautyjewelry');
define('STOREID', 'Add Store ID');
$ETSY_FILE_NAME = 'etsy-listings.txt';
$ETSY_LISTINGS_URL = "https://openapi.etsy.com/v2/shops/". STOREID . "/listings/active?includes=MainImage&category_path=Cuff&api_key=" . API_KEY;

// REQUEST
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
	$current_time = time(); 
	$expire_time = $hours * 60 * 60; 
	$file_time = filemtime($file);
	
	//decisions, decisions
	if(file_exists($file) && ($current_time - $expire_time < $file_time)) {
		//echo 'returning from cached file';
		return file_get_contents($file);
	} else {
		$content = get_url($url);
		file_put_contents($file,$content);
		return $content;
	}
}

function output_listings($response){
	$html_output;
	$i=0;
	foreach ($response['results'] as $arr){
		$html_output = '<b>ARR ['.$i.']</b>: '.$arr['listing_id'].'<br>';
		$html_output .= 'title: '.$arr['title'].'<br>';
		$html_output .= 'description: '.$arr['description'].'<br>';
		$html_output .= 'price: '.$arr['price'].'<br>';
		$html_output .= 'category_path: ';
		foreach($arr['category_path'] as $cat){
			$html_output .= $cat.' / ';
		}
		$html_output .= '<br>';
		$html_output .= 'featured_rank: '.$arr['featured_rank'].'<br>';
		// Main Image
		$html_output .= 'MainImage: [75x75] <img src="'.$arr['MainImage']['url_75x75'].'" alt="75x75" /> // '.
			'[170x135] <img src="'.$arr['MainImage']['url_170x135'].'" alt="75x75" /> // '.
			'[570xN] <img src="'.$arr['MainImage']['url_570xN'].'" alt="570xN" /> // '.
			//'[fullxfull] <img src="'.$arr['MainImage']['url_fullxfull'].'" alt="fullxfull" /> // '.
			'<br>';
		$html_output .= '<br><br>';
		$i++;
	}
	return $html_output;
}

function output_all_listing($response){
	$html_output = '';
	
	foreach ($response['results'] as $arr){
		$html_output .= '<li>';
		$html_output .= '<a href="#?'.$arr['listing_id'].'" alt="Add to Cart" >';
		$html_output .= '<img src="'.$arr['MainImage']['url_170x135'].'" alt="75x75" />';
		$html_output .= '<div><span class="list_title">'.$arr['title'].'</span>';
		//$html_output .= '<div><span class="list_title">'.$arr['title'].'<br />'.$arr['price'].'</span></div>';
		$html_output .= '<span class="list_price">'.$arr['price'].'</span></div>';
		$html_output .= '</a></li>';
	}
	return $html_output;
}

/* usage */
$ETSY_LISTINGS = get_content($ETSY_FILE_NAME,1,$ETSY_LISTINGS_URL);
//output_listings(json_decode($ETSY_LISTINGS, true));
	
?>
<!DOCTYPE html>
<html lang="en">
    <head>
		<meta charset="UTF-8" />
        <meta http-equiv="X-UA-Compatible" content="IE=edge,chrome=1"> 
        <title>Etsy Product Rendering Test</title>
        <meta name="viewport" content="width=device-width, initial-scale=1.0"> 
        <meta name="description" content="Show all products from Natural Beauty Jewelry" />
        <meta name="keywords" content="hover, css3, jquery, effect, direction, aware, depending, thumbnails" />
        <meta name="author" content="Codrops" />
        <link rel="shortcut icon" href="../favicon.ico"> 
		<link href='http://fonts.googleapis.com/css?family=Alegreya+SC:700,400italic' rel='stylesheet' type='text/css' />
        <link rel="stylesheet" type="text/css" href="css/demo.css" />
        <link rel="stylesheet" type="text/css" href="css/style.css" />
        <script src="js/modernizr.custom.97074.js"></script>
        <noscript><link rel="stylesheet" type="text/css" href="css/noJS.css"/></noscript>
    </head>
    <body>
        <div class="container">
            <div class="codrops-top clearfix">
               Test 007
                <span class="right">

                </span>
            </div>
			<header class="clearfix">
				<span>Etsy Product Rendering Test</span>
				<h1>Show all products from Natural Beauty Jewelry</h1>
				<p>Products will be pulled from the <a href="https://www.etsy.com/shop/naturalbeautyjewelry">Natural Beauty Jewelry Etsy site </a> via the Etsy API 
					and written to a text file. Moving from one thumb to the other will immediately trigger the sliding of the info box. Test is responsive.</p>
			</header>
			<section>
				<ul id="da-thumbs" class="da-thumbs">
					<?php echo output_all_listing(json_decode($ETSY_LISTINGS, true)); ?>
				</ul>
			</section>
        </div>
		<script type="text/javascript" src="http://ajax.googleapis.com/ajax/libs/jquery/1.8.3/jquery.min.js"></script>
		<script type="text/javascript" src="js/jquery.hoverdir.js"></script>	
		<script type="text/javascript">
			$(function() {
				$(' #da-thumbs > li ').each( function() { $(this).hoverdir(); } );
				
			});
		</script>
    </body>
</html>

