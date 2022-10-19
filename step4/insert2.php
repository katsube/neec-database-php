<?php
/**
 * データを挿入する
 *
 */

//-------------------------------------------
// ライブラリ
//-------------------------------------------
require_once('common.php');

//-------------------------------------------
// データを挿入
//-------------------------------------------
// 挿入するデータを準備
$id     = 'my0001';
$title  = 'オレの動画';
$format = 'flv';
$length = '60';

// ファイルに書き込み
$fp = fopen(DATA_FILE, 'a');
$line =  '1'																					// 1:有効, 0:削除済み
				. str_pad($id,     10, ' ', STR_PAD_RIGHT)
				. str_pad($title, 128, ' ', STR_PAD_RIGHT)
				. str_pad($format,  5, ' ', STR_PAD_RIGHT)
				. str_pad($length,  5, ' ', STR_PAD_RIGHT)
				. "\n";
fwrite($fp, $line);
fclose($fp);