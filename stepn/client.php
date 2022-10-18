<?php
/**
 * 簡易データーベースクライアント
 *
 */

//-------------------------------------------
// 定数
//-------------------------------------------
require_once('common.php');

//-------------------------------------------
// 初期設定
//-------------------------------------------
// 処理開始時間
$starttime = microtime(true);

// 設定
error_reporting(E_ALL);
ob_implicit_flush();

// サーバに送信するコマンド（最後に必ず改行する）
$keyword = ($argc===2)? $argv[1] : 'アイドルマスター';
$command= "GET $keyword\n";

//-------------------------------------------
// ソケット通信
//-------------------------------------------
// ソケットを作成
if( ($socket = socket_create(AF_INET, SOCK_STREAM, SOL_TCP)) === false ) {
		echo "socket_create() failed: reason: " . socket_strerror(socket_last_error()) . "\n";
		exit;
}
// サーバに接続
if( ($result = socket_connect($socket, SERVE_ADDRESS, SERVE_PORT)) === false ) {
		echo "socket_connect() failed: reason: " . socket_strerror(socket_last_error($socket)) . "\n";
		exit;
}
// サーバにコマンドを送信
if( (socket_write($socket, $command, strlen($command))) === false ) {
		echo "socket_write() failed: reason: " . socket_strerror(socket_last_error($socket)) . "\n";
		exit;
}

//-------------------------------------------
// サーバからメッセージを受信
//-------------------------------------------
$data = '';
while ( $out = socket_read($socket, 2048) ) {
	$data .= $out;
}
socket_close($socket);

// 処理終了時間
$endtime = microtime(true);

// 受信したデータを表示
echo base64_decode($data);
echo '検索時間: '.($endtime-$starttime)."秒\n";