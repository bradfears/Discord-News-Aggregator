<?php

# see the README.md on github for information on how to gather these values
# https://github.com/bradfears/Discord-News-Aggregator/blob/main/README.md

# you may also choose to define these variables as environment variables rather than hard-coding them
# https://w3schools.in/php/environment-variables

$channel_id = "";
$webhook_url = "";
$auth_code = "";
$news_api_key = "";
$news_language = "en"; # see https://newsdata.io/docs for other language strings
$news_query = "Overwatch 2"; # value will be url encoded below

# refer to https://newsdata.io/docs for any additional parameters
# note that you can also use q= rather than qInTitle= for a wider search scope
$response = file_get_contents("http://newsdata.io/api/1/news?apikey=$news_api_key&qInTitle=". urlencode($news_query) ."&language=$news_language");

$decoded_json = json_decode($response, true);
$obj = $decoded_json['results'];

foreach($obj as $result) {
    $title = $result['title'];
    $title = str_replace("’", "'", $title);
    $title = str_replace("‘", "'", $title);
    
    $link = $result['link'];

    $description = $result['description'];
    $description = str_replace("’", "'", $description);
    $description = str_replace("‘", "'", $description);

    $pubDate = $result['pubDate'];

    $content = "[$title](<$link>)\n$description\nPublished: $pubDate"; # markdown

	$webhookurlGETmessages = "https://discord.com/api/v9/channels/$channel_id/messages?limit=50";
	$webhookurlPOSTmessages = $webhook_url;
	
	$headers = [ "Content-Type: application/json; charset=utf-8", "authorization: $auth_code" ];
	$POST = [ 'username' => 'News', 'content' => $content ];

	$chGET = curl_init();
	curl_setopt($chGET, CURLOPT_URL, $webhookurlGETmessages);	
	curl_setopt($chGET, CURLOPT_HTTPHEADER, $headers);
	curl_setopt($chGET, CURLOPT_RETURNTRANSFER, true);
	curl_setopt($chGET, CURLOPT_SSL_VERIFYPEER, false);	
	$response   = curl_exec($chGET);
	
	$pos = strpos($response, $link);
	if ($pos === false) {

		$chPOST = curl_init();
		curl_setopt($chPOST, CURLOPT_URL, $webhookurlPOSTmessages);
		curl_setopt($chPOST, CURLOPT_POST, true);
		curl_setopt($chPOST, CURLOPT_HTTPHEADER, $headers);
		curl_setopt($chPOST, CURLOPT_RETURNTRANSFER, true);
		curl_setopt($chPOST, CURLOPT_SSL_VERIFYPEER, false);
		curl_setopt($chPOST, CURLOPT_POSTFIELDS, json_encode($POST));
		$response   = curl_exec($chPOST);

		break;
	}	

}

?>