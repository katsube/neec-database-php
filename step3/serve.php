<?php
/**
 * 簡易データーベースサーバー
 *
 * [使い方]
 *   起動
 *     $ php serve.php
 *   終了
 *     Ctrl+C
 */

//-------------------------------------------
// 定数
//-------------------------------------------
require_once('common.php');

//-------------------------------------------
// 初期設定
//-------------------------------------------
error_reporting(E_ALL);		// 全てのエラーを出力する
set_time_limit(0);				// スクリプトの実行時間を無制限にする
ob_implicit_flush();			// 自動フラッシュをオンにする

echo "Starting server...\n";

//-------------------------------------------
// インデックスをメモリ上に読み込む
//-------------------------------------------
$index = loadIndex(INDEX_FILE);
echo "  Loaded index file\n";

//-------------------------------------------
// TCPソケットを作成
//-------------------------------------------
// ソケットを作成
// https://www.php.net/manual/ja/function.socket-create
if ( ($sock = socket_create(AF_INET, SOCK_STREAM, SOL_TCP)) === false ) {
    echo "socket_create() failed: reason: " . socket_strerror(socket_last_error()) . "\n";
		exit;
}

// ソケットにIPアドレスとポート番号を関連付け
// https://www.php.net/manual/ja/function.socket-bind
if ( socket_bind($sock, SERVE_ADDRESS, SERVE_PORT) === false ) {
    echo "socket_bind() failed: reason: " . socket_strerror(socket_last_error($sock)) . "\n";
		exit;
}

// ソケットを待ち受け状態にする
// https://www.php.net/manual/ja/function.socket-listen
if ( socket_listen($sock, SERVE_BACKLOG) === false ) {
    echo "socket_listen() failed: reason: " . socket_strerror(socket_last_error($sock)) . "\n";
		exit;
}


//-------------------------------------------
// 簡易DBサーバー
//-------------------------------------------
echo "  Listening on " . SERVE_ADDRESS . ":" . SERVE_PORT . "\n";
while ( true ) {
	//------------------------------------
	// クライアントからの接続を待つ
	//------------------------------------
	if( ($msgsock = socket_accept($sock)) === false ){
		echo "socket_accept() failed: reason: " . socket_strerror(socket_last_error($sock)) . "\n";
		exit;
	}
	echo "  Client connected\n";

	//------------------------------------
	// クライアントとの接続が確立したら
	//------------------------------------
	while( true ){
		// 接続したクライアントからのメッセージを読み取り
		if( ($buff = socket_read($msgsock, 2048, PHP_NORMAL_READ)) === false ){
			echo "socket_read() failed: reason: " . socket_strerror(socket_last_error($msgsock)) . "\n";
			break;
		}
		$buff = trim($buff);
		echo "    Client message: $buff\n";

		// 処理開始時間
		$starttime = microtime(true);

		// メッセージが空の場合はもう一度
		if ( ($buff = trim($buff)) === '' ) {
				continue;
		}
		// メッセージが"GET"から始まる場合は検索
		$response = '';
		if ( preg_match('/^GET (.*)$/i', $buff, $matches) ) {
			$keyword = $matches[1];
			// インデックスに存在すれば利用
			if( array_key_exists($keyword, $index) ){
				echo "    Index Search\n";
				$response = base64_encode( searchIndex(DATA_FILE, $index, $keyword) ) . "\n";
			}
			// 存在しなければ全文検索
			else{
				echo "    Full-text Search\n";
				$response = base64_encode( searchFile(DATA_FILE, $keyword) ) . "\n";
			}
		}
		else{
			$response = 'InvalidRequest';
		}
		// 処理終了時間
		$endtime = microtime(true);
		echo '    検索時間: '.($endtime-$starttime)."秒\n";

		// クライアントにメッセージを送信
		socket_write($msgsock, $response, strlen($response));
		break;
	}
	// クライアントとの接続を切断
	socket_close($msgsock);
	echo "  Client disconnected\n";
}
echo "Stopping server...\n";
socket_close($sock);


/**
 * インデックスをロード
 *
 * @param string $file インデックスファイルのパス
 * @return array インデックス
 */
function loadIndex($file){
	$index = [ ];
	$fp = fopen($file, 'r');
	while( ($buff=fgets($fp)) !== false){
		$buff = trim($buff);
		list($word, $pos) = explode(':', $buff);
		$index[$word] = explode(',', $pos);
	}
	return($index);
}


/**
 * ファイルを先頭から検索
 *
 * @param string $file ファイルのパス
 * @param string $word キーワード
 * @return void
 */
function searchFile($file, $word){
	$result = '';
	$fp = fopen($file, 'r');
	while( ($buff=fgets($fp)) !== false ){
		list($id, $title, $format, $length) = explode("\t", $buff);
		if( strpos($title, $word) !== false ){
			$result .= $buff;
		}
	}
	fclose($fp);

	return($result);
}

/**
 * インデックスを利用して検索
 *
 * @param string $dataFile データファイルのパス
 * @param array $index インデックス
 * @param string $word キーワード
 */
function searchIndex($dataFile, $index, $word){
	$result = '';
	$position = $index[$word];
	$lenght = count($position);

	$fp = fopen($dataFile, 'r');
	for( $i=0; $i<$lenght; $i++ ){
		fseek($fp, (int)$position[$i]);
		$result .= fgets($fp);
	}
	fclose($fp);

	return($result);
}

