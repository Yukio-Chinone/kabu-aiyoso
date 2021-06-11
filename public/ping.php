<?php

//更新Pingの送信先
$server = 'http://pingoo.jp/ping/';

//weblogUpdates.ping のXML-RPCのリクエストを作る
$content = xmlrpc_encode_request(
    'weblogUpdates.ping',
    // 4つ目の引数の"カテゴリ"は省略してよい
    array('AIの株価予想', 'https://kabu.aiyoso.com', 'https://kabu.aiyoso.com'),
    array('encoding' => 'UTF-8')
);

//HTTPコンテキスト [http://www.php.net/manual/ja/context.http.php] 参照
$options = array('http' => array(
    'method' => 'POST',
    'header' => 'Content-type: text/xml' . "\r\n"
        . 'Content-length: ' . strlen($content),
    'content' => $content
));
$context = stream_context_create($options);

//リクエスト送信
// HTTPレスポンスヘッダ格納配列
$http_response_header = array();

$res = file_get_contents($server, false, $context);

if (false === $res) {
    echo "送信失敗";
    $err = "weblogUpdates.ping failed. : ";
    if (0 < count($http_response_header)) {
        // エラーレスポンスあり (404, 500, ...)
        $err = $http_response_header[0]; // 0:Status-Line
    } else {
        // タイムアウト or 送信先サーバーなし
        $err = "Timeout or Unknown server";
    }
    echo $err;

} else {
    echo "送信成功";
    echo $res;
}