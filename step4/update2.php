<?php
/**
 * データを更新する
 *   パターン2:
 *     レコードを直接上書きする
 */

//-------------------------------------------
// ライブラリ
//-------------------------------------------
require_once('common.php');

//-------------------------------------------
// データを更新
//-------------------------------------------
// 更新対象のID
$target_id = 'sm1018';

// 更新するデータを準備
$title  = 'オレの動画';
$format = 'mp4';
$length = '60';

$line = str_pad($title, 128, ' ', STR_PAD_RIGHT)
			. str_pad($format,  5, ' ', STR_PAD_RIGHT)
			. str_pad($length,  5, ' ', STR_PAD_RIGHT);


// ファイルからIDを探し出して更新する
$fp = fopen(DATA_FILE, 'r+');
while( $buff = fscanf($fp, '%1d%10s%128s%5s%5s') ){
	list($flag, $id, $title, $format, $length) = $buff;

	// 書き換える
	if( $flag === 1 && $id === $target_id ){
		fseek($fp, (128 + 5 + 5 + 1) * -1, SEEK_CUR);
		fwrite($fp, $line);
		break;
	}
}
fclose($fp);
