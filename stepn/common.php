<?php
/**
 * サーバ・クライアント間の共通データ
 */

//-------------------------------------------
// 定数
//-------------------------------------------
define('SERVE_ADDRESS', '127.0.0.1');		// サーバーのIPアドレス
define('SERVE_PORT', 3000);							// サーバーのポート番号
define('SERVE_BACKLOG', 5);							// サーバーの最大待受数

define('DATA_FILE', '../data/video.csv');		// データファイルのパス
define('INDEX_FILE', '../data/index.txt');	// インデックスファイルのパス
