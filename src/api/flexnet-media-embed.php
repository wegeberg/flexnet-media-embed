<?php
/*
    Title: Flexnet Media Embed
    Version: 1.0, 2019-04-07
    Author: Martin Wegeberg
    Description:  A plugin for embedding media content to TinyMCE 4.
    Add Facebook, Twitter, YouTube, Vimeo, Soundcloud, Infogram

    For full example see: https://www.flexnet.dk/github/flexnet-media-embed/example/
*/
/* ----- UTILITY FUNCTIONS ----- */
if(!function_exists("assignDefaults")) {
    function assignDefaults($data, $defaults) {
        $newData = $data;
        foreach($defaults as $key=>$val) {
            $newData[$key] = isset($data[$key]) ? $data[$key] : $val;
        }
        return $newData;
    }
}
if(!function_exists("returnJson")) {
	function returnJson($params) {
		$defaults = [
			"success"	=> false,
			"error"		=> null,
			"result"	=> null,
			"debug"		=> null
		];
		$data = !empty($defaults) ? assignDefaults($params, $defaults) : $params;
		echo json_encode($data);
		exit;
	}
}
if(!function_exists("sendNoCacheHeaders")) {
	function sendNoCacheHeaders($origin = "*") {
		header("Access-Control-Allow-Origin: {$origin}");
		header('Access-Control-Allow-Methods: GET, POST, OPTIONS');
        // header("Access-Control-Allow-Headers: X-Requested-With {$_SERVER['HTTP_ACCESS_CONTROL_REQUEST_HEADERS']}");
        
        header('Access-Control-Allow-Headers: Origin, X-Requested-With, Content-Type, Accept');

		header("Content-Type: application/json");
		header("Expires: 0");
		header("Last-Modified: ".gmdate("D, d M Y H:i:s")." GMT");
		header("Cache-Control: no-store, no-cache, must-revalidate");
		header("Cache-Control: post-check=0, pre-check=0", false);
		header("Pragma: no-cache");
	}
}
/* ----- UTILITY FUNCTIONS ----- */


$showDebug = false;

$url = isset($_GET["url"]) ? trim($_GET["url"]) : null;

if(!$url) {
    sendNoCacheHeaders();
    returnJson([
        "success"   => false,
        "error"     => "URL ikke angivet",
        "result"    => null,
    ]);
}

if(substr($url, 0, 4) != "http") {
    sendNoCacheHeaders();
    returnJson([
        "success"   => false,
        "error"     => "Det ligner ikke en URL....",
        "result"    => null,
    ]);
}

$services = [
    "twitter"       => "https://publish.twitter.com/oembed?url=",
    "soundcloud"    => "https://soundcloud.com/oembed?format=js&url=",
    "facebook"      => "https://www.facebook.com/plugins/post/oembed.json/?url=",
    "infogram"      => "https://infogram.com/oembed_iframe?url=",
    "youtu.be"      => "https://www.youtube.com/oembed?url=",
    "vimeo"         => "https://vimeo.com/api/oembed.json?url="
];

$endpoint = null;
$valgtService = null;
foreach($services as $service=>$serviceUrl) {
    if(stripos($url, $service) !== false) {
        $endpoint = $serviceUrl;
        $valgtService = $service;
        break;
    }
}

if($showDebug) {
    echo "<div>endpoint: {$endpoint}{$url}</div>";
    exit;
}

if(!$endpoint) {
    sendNoCacheHeaders();
    returnJson([
        "success"   => false,
        "error"     => "Endpoint kunne ikke findes",
        "result"    => null,
    ]);
}
  
$content = file_get_contents($endpoint.$url);
if($valgtService == "soundcloud") {
    // Soundcloud leverer resultatet med '(' foran, og efterfulgt af ');'
    $content = substr($content, 1, -2);
}
$result = $content ? json_decode($content, true) : null;
if(!$result) {
    returnJson([
        "success"   => false,
        "error"     => "Indhold ikke fundet",
        "result"    => $result,
        "url"       => $endpoint.$url,
        "kode"      => $result ? htmlentities($result["html"]) : null
    ]);
}
$result["html"] = mb_convert_encoding($result["html"], 'HTML-ENTITIES', 'UTF-8');

if($showDebug) {
    returnJson([
        "success"   => false,
        "error"     => "DEBUG",
        "result"    => $result,
        "url"       => $endpoint.$url,
        "kode"      => $result ? htmlentities($result["html"]) : null
    ]);
}

sendNoCacheHeaders();
returnJson([
    "success"   => $result ? true : false,
    "error"     => $result ? null : "Der skete en fejl",
    "result"    => $result,
    "url"       => $endpoint.$url,
    "kode"      => $result ? htmlentities($result["html"]) : null
]);
?>