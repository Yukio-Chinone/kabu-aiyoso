<?php

// 送信先の設定
$pingServers = array();
$pingServers[] = "http://pingoo.jp/ping/";
$pingServers[] = "https://ping.blogmura.com/xmlrpc/d2t3rob8vf8p/";
$pingServers = array_unique($pingServers);

// 自分のサイト名
// (日本語名が使用できない送信先サーバがあります)
$siteName = "AIの株価予想";

// 自分のサイトURL(RSSファイルのリンクがあるページ)
$siteUrl = "https://kabu.aiyoso.com";

// 送信するweblogUpdates.pingのXMLデータ
$postData = '<?xml version="1.0" encoding="UTF-8"?>
<methodCall>
<methodName>weblogUpdates.ping</methodName>
<params>
<param><value>' . $siteName . '</value></param>
<param><value>' . $siteUrl . '</value></param>
</params>
</methodCall>
';

$headers = array(
    'Content-Type: application/xml',
    'Content-Length: ' . strlen($postData)
);

$context = stream_context_create(
    array(
        'http' => array(
            'method' => 'POST',
            'header' => implode("\r\n", $headers),
            'content' => $postData
        )
    )
);

foreach ($pingServers as $pingServer) {
    $http_response_header = null;
    $response = @file_get_contents($pingServer, false, $context);
    echo $response;
    sleep(1);
}
