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
$result = searchFile(DATA_FILE, $keyword);

// 結果を表示
$len = count($result);
for( $i=0; $i<$len; $i++ ){
	echo $result[$i];
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