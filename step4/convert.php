<?php
/**
 * 【キーワード検索】
 *   データファイル固定長化スクリプト
 *
 *   [使い方]
 *   $ php convert.php
 */

//-------------------------------------------
// 定数
//-------------------------------------------
define('DATA_FILE', '../data/video.csv');
define('DATA_FILE_FIXED', '../data/video_fixed.csv');

//-------------------------------------------
// データファイルを固定長化
//-------------------------------------------
$fp = fopen(DATA_FILE, 'r');
$fp_fixed = fopen(DATA_FILE_FIXED, 'w');
while( ($buff = fgets($fp)) !== false ){
	$buff = rtrim($buff);
	list($id, $title, $format, $length) = explode("\t", $buff);
	$line =   str_pad($id,     10, ' ', STR_PAD_RIGHT)
					. str_pad($title, 128, ' ', STR_PAD_RIGHT)
					. str_pad($format,  5, ' ', STR_PAD_RIGHT)
					. str_pad($length,  5, ' ', STR_PAD_RIGHT)
					. "\n";
	fwrite($fp_fixed, $line);
}
fclose($fp_fixed);
fclose($fp);
