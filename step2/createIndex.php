<?php
/**
 * 【キーワード検索】
 *   インデックス作成スクリプト
 *
 *   [使い方]
 *   $ php createIndex.php
 */

//-------------------------------------------
// 定数
//-------------------------------------------
define('DATA_FILE', '../data/video.csv');
define('INDEX_FILE', '../data/index.txt');

// インデックスを作成する単語
$words = [
	'アイドルマスター',
	'陰陽師',
	'孤独のグルメ'
];

//-------------------------------------------
// インデックス作成
//-------------------------------------------
// 既存のインデックスファイルを削除
if( file_exists(INDEX_FILE) ){
	unlink(INDEX_FILE);
}

// インデックスファイルを作成
for($i=0; $i<count($words); $i++){
	$word = $words[$i];
	$index = createIndex(DATA_FILE, $word);
	saveIndex(INDEX_FILE, $word, $index);
}


/**
 * キーワードの位置を抽出する
 *
 * @param string $word キーワード
 * @param string $data データファイルのパス
 * @return array [123, 546, ...]
 */
function createIndex($file, $word){
	$position = [ ];
	$buff = '';
	$pos = 0;

	$fp = fopen($file, 'r');
	fseek($fp, 0);
	while( $buff !== false ){
		if( strpos($buff, $word) !== false ){
			$position[] = $pos;
		}
		$pos = ftell($fp);
		$buff = fgets($fp);
	}
	fclose($fp);

	return( $position );
}


/**
 * インデックスファイルを作成する
 *
 * @param string $file インデックスファイスのパス
 * @param string $word キーワード
 * @param array $position [123, 546, ...]
 */
function saveIndex($file, $word, $position){
	$line = $word . ':' . implode(',', $position) . PHP_EOL;

	$fp = fopen($file, 'a');
	fwrite($fp, $line);
	fclose($fp);
}