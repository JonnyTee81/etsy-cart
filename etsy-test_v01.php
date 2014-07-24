<?php
define('API_KEY', 'yegs7brnmnmqb72d41s7kc5m');	
define('STORENAME', 'naturalbeautyjewelry');
define('STOREID', '5259053');

// REQUEST
// Make sure you define API_KEY to be your unique, registered key
//$url = "https://openapi.etsy.com/v2/users/". STORENAME . "?api_key=" . API_KEY;
//$url = "https://openapi.etsy.com/v2/shops/". STORENAME . "?includes=Listings:active&limit=5&api_key=" . API_KEY;
$url = "https://openapi.etsy.com/v2/shops/". STOREID . "/listings/active?includes=MainImage&category_path=Cuff&api_key=" . API_KEY;
$ch = curl_init($url);
curl_setopt($ch, CURLOPT_RETURNTRANSFER, true);
$response_body = curl_exec($ch);
$status = curl_getinfo($ch, CURLINFO_HTTP_CODE);
if (intval($status) != 200) throw new Exception("HTTP $status\n$response_body");

// RESPONSE
$response = json_decode($response_body);
$booboo = $response->results[0];
/*
if (!isset($booboo->login_name)) {
    throw new RuntimeException("User Resource doesn't have field login_name");
    print "error 1\n";
}
if (!isset($booboo->feedback_info->score)) {
    throw new RuntimeException("User Resource doesn't have field feedback_info['score']");
    print "error 2\n";
}
print "User $user->login_name has a feedback score of {$user->feedback_info->score}\n";
*/
//return JSON array
//print $response_body;
//print json_encode($booboo);
//echo json_encode($booboo, JSON_PRETTY_PRINT);
print $booboo->listing_id;
print '<br>';


?>
