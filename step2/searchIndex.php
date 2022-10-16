<?php
/**
 * キーワード検索
 */

//-------------------------------------------
// 定数
//-------------------------------------------
define('DATA_FILE', '../data/video.csv');
define('INDEX_FILE', '../data/index.txt');

//-------------------------------------------
// 検索する
//-------------------------------------------
// 処理開始時間
$starttime = microtime(true);

// コマンドラインから引数を受け取る
$word = ($argc === 2)?  $argv[1] : 'アイドルマスター';

// インデックスをメモリ上にロード
$indexloadstart = microtime(true);
$index = loadIndex(INDEX_FILE);
$indexloadend = microtime(true);
echo 'インデックス読み込み時間: '.($indexloadend-$indexloadstart).'秒'.PHP_EOL;

// 検索
if( array_key_exists($word, $index) ){
	$result = searchIndex(DATA_FILE, $index, $word);
}
else{
	$result = searchFile(DATA_FILE, $word);
}

// 検索終了時間
$endtime = microtime(true);

// 結果を表示
$len = count($result);
for( $i=0; $i<$len; $i++ ){
	echo $result[$i];
}

echo '検索時間: '.($endtime-$starttime).'秒';

/**
 * インデックスをロード
 *
 * @param string $file インデックスファイルのパス
 * @return array インデックス
 */
function loadIndex($file){
	$index = [ ];
	$fp = fopen($file, 'r');
	while( ($buff = fgets($fp)) !== false){
		$buff = trim($buff);											// 改行コードを削除
		list($word, $pos) = explode(':', $buff);
		$index[$word] = explode(',', $pos);
	}
	return($index);
}

/**
 * インデックスを利用して検索
 *
 * @param string $dataFile データファイルのパス
 * @param array $index インデックス
 * @param string $word キーワード
 */
function searchIndex($dataFile, $index, $word){
	$result = [ ];
	$position = $index[$word];
	$length = count($position);

	$fp = fopen($dataFile, 'r');
	for( $i=0; $i<$length; $i++ ){
		fseek($fp, (int)$position[$i]);
		$result[] = fgets($fp);
	}
	fclose($fp);

	return($result);
}

/**
 * ファイルを先頭から検索
 *
 * @param string $file ファイルのパス
 * @param string $word キーワード
 * @return void
 */
function searchFile($file, $word){
	$result = [ ];

	$fp = fopen($file, 'r');
	while( ($buff=fgets($fp)) !== false ){
		if( strpos($buff, $word) !== false ){
			$result[] = $buff;
		}
	}
	fclose($fp);

	return($result);
}