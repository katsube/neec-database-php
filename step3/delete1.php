<?php
/**
 * データを削除する
 *   パターン1:
 *     削除したら上に詰める
 */

//-------------------------------------------
// ライブラリ
//-------------------------------------------
require_once('common.php');

//-------------------------------------------
// データを削除
//-------------------------------------------
// 削除対象のID
$target_id = 'my0001';

// ファイルからIDを探し出して書き込む
$fp_r = fopen(DATA_FILE, 'r');
$fp_w = fopen(DATA_FILE.'.tmp', 'w');
while( ($buff = fgets($fp_r)) !== false ){
	list($id, $title, $format, $length) = explode("\t", $buff);
	if( $id !== $target_id ){
		fwrite($fp_w, $buff);
	}
}
fclose($fp_r);
fclose($fp_w);

// ファイルを置き換える
rename(DATA_FILE.'.tmp', DATA_FILE);