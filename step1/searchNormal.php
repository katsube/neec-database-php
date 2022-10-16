<?php
/**
 * キーワード検索
 */

//-------------------------------------------
// 定数
//-------------------------------------------
define('DATA_FILE', '../data/video.csv');

//-------------------------------------------
// 検索する
//-------------------------------------------
$keyword = 'アイドルマスター';

// ファイルの先頭から検索
$starttime = microtime(true);
$result = searchFile(DATA_FILE, $keyword);
$endtime = microtime(true);

// 結果を表示
$len = count($result);
for( $i=0; $i<$len; $i++ ){
	echo $result[$i];
}

echo '検索時間: '.($endtime-$starttime).'秒';

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
		list($id, $title, $format, $length) = explode("\t", $buff);
		if( strpos($title, $word) !== false ){
			$result[] = $buff;
		}
	}
	fclose($fp);

	return($result);
}