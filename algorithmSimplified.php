<?php

/* Simplified version for PHP based on Wouter Bokslag & Jason F. - https://github.com/prototux - work */

function transform($data, $sec) { // Some hideous code because PHP is not a strongly typed language, this is working on a 64bits machine
	if ($data > 0x7FFF) {
		$data = 0xFFFFFFFFFFFF0000 | $data;
	}
	$result = (((($data % $sec[0]) * $sec[2]) & 0xFFFFFFFF) - (((($data / $sec[0]) & 0xFFFFFFFF) * $sec[1]) & 0xFFFFFFFF)) & 0xFFFFFFFF;
	if ($result > 0x7FFFFFFF)
		$result += ($sec[0] * $sec[2]) + $sec[1];
	return $result & 0xFFFF;
}

function getKey($seedTXT, $appKeyTXT = "0000") {
	$seed = str_split($seedTXT, 2);
	$appKey = str_split($appKeyTXT, 2);
	
	// Hardcoded secrets
	$sec_1 = array(0xB2, 0x3F, 0xAA);
	$sec_2 = array(0xB1, 0x02, 0xAB);
	
	$res_msb = transform(hexdec($appKey[0].$appKey[1]), $sec_1) | transform(hexdec($seed[0].$seed[3]), $sec_2);
	$res_lsb = transform(hexdec($seed[1].$seed[2]), $sec_1) | transform($res_msb, $sec_2);
	$res = ($res_msb << 16) | $res_lsb;
	return str_pad(strtoupper(dechex($res)), 8, '0', STR_PAD_LEFT);
}

echo getKey("11111111", "D91C");

?>