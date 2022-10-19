<?php
/**
 * データを更新する
 *   パターン1:
 *     更新対象の位置を維持する
 */

//-------------------------------------------
// ライブラリ
//-------------------------------------------
require_once('common.php');

//-------------------------------------------
// データを更新
//-------------------------------------------
// 更新対象のID
$target_id = 'my0001';

// 更新するデータを準備
$data = [
	'my0001',				// id
	'アタイの動画',	// title
	'mp4',					// format
	'80'						// length
];

// ファイルからIDを探し出して書き込む
$fp_r = fopen(DATA_FILE, 'r');
$fp_w = fopen(DATA_FILE.'.tmp', 'w');
while( ($buff = fgets($fp_r)) !== false ){
	list($id, $title, $format, $length) = explode("\t", $buff);
	if( $id === $target_id ){
		fwrite($fp_w, implode("\t", $data)."\n");
	}
	else{
		fwrite($fp_w, $buff);
	}
}
fclose($fp_r);
fclose($fp_w);

// ファイルを置き換える
rename(DATA_FILE.'.tmp', DATA_FILE);