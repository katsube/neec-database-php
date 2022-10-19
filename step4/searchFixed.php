<?php
/**
 * キーワード検索
 *   固定長ファイル版
 */

//-------------------------------------------
// 定数
//-------------------------------------------
define('DATA_FILE', '../data/video_fixed.csv');

//-------------------------------------------
// 検索する
//-------------------------------------------
$keyword = 'アイドルマスター';

// ファイルの先頭から検索
$starttime = microtime(true);
$result = searchFileFixed(DATA_FILE, $keyword);
$endtime = microtime(true);

// 結果を表示
$len = count($result);
for( $i=0; $i<$len; $i++ ){
	echo implode("\t", $result[$i]);
	echo "\n";
}

echo '検索時間: '.($endtime-$starttime).'秒';

/**
 * ファイルを先頭から検索
 *
 * @param string $file ファイルのパス
 * @param string $word キーワード
 * @return void
 */
function searchFileFixed($file, $word){
	$result = [ ];

	$fp = fopen($file, 'r');
	while( $buff = fscanf($fp, '%10s%128s%5s%5s') ){
		list($id, $title, $format, $length) = $buff;
		if( strpos($title, $word) !== false ){
			$result[] = $buff;
		}
	}
	fclose($fp);

	return($result);
}