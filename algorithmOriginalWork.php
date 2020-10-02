<?php

function getKey($seedTXT, $appKeyTXT = "0000") { // Some hideous code because PHP is not a strongly typed language, this is working on a 64bits machine
	$seed = str_split($seedTXT, 2);
	$appKey = str_split($appKeyTXT, 2);
	
	$appKeyComputed = 0;
	
	$x = hexdec($appKey[0].$appKey[1]);
	$a = hexdec($appKey[1]."00".$appKey[0].$appKey[1]) * 0xAA;
	if ($x > 0x7FFF) {
		$b = ((0x0B81702E1 * (0xFFFFFFFF0000 | $x)) >> 32);
		$b = ((0xFFFF0000 | ($b & 0xffff)) >> 7) + 0xFE000000;
	} else {
		$b = ((0x0B81702E1 * $x) >> 32) >> 7; 
	}
	$c = (($b + ($b >> 0x1F)) & 0xffff) * 0x7673;
	$d = $a - $c;
	if (($d & 0xffff) > 0x7FFF) { // Negative
		$d += 0x7673;
	}
	$appKeyComputed = ($d & 0xffff);

	$x = hexdec($seed[0].$seed[3]);
	$a = $x * 0xAB;
	if ($x > 0x7FFF) {
		$b = ((0x0B92143FB * (0xFFFFFFFF0000 | $x)) >> 32);
		$b = ((0xFFFF0000 | ($b & 0xffff)) >> 7) + 0xFE000000;
	} else {
		$b = ((0x0B92143FB * $x) >> 32) >> 7; 
	}
	$c = (($b + ($b >> 0x1F)) & 0xffff) * 0x763D;
	$d = $a - $c;
	if (($d & 0xffff) > 0x7FFF) { // Negative
		$d += 0x763D;
	}
	$d = ($d & 0xffff);
	$key = $d | $appKeyComputed;

	$x = hexdec($seed[1].$seed[2]);
	$a = $x * 0xAA;
	if ($x > 0x7FFF) {
		$b = ((0x0B81702E1 * (0xFFFFFFFF0000 | $x)) >> 32);
		$b = ((0xFFFF0000 | ($b & 0xffff)) >> 7) + 0xFE000000;
	} else {
		$b = ((0x0B81702E1 * $x) >> 32) >> 7; 
	}
	$c = (($b + ($b >> 0x1F)) & 0xffff) * 0x7673;
	$d = $a - $c;
	if (($d & 0xffff) > 0x7FFF) { // Negative
		$d += 0x7673;
	}
	$d = ($d & 0xffff);

	$val = $d;

	$x = ($key & 0xffff);
	$a = $x * 0xAB;
	if ($x > 0x7FFF) {
		$b = ((0x0B92143FB * (0xFFFFFFFF0000 | $x)) >> 32);
		$b = ((0xFFFF0000 | ($b & 0xffff)) >> 7) + 0xFE000000;
	} else {
		$b = ((0x0B92143FB * $x) >> 32) >> 7;
	}
	$c = (($b + ($b >> 0x1F)) & 0xffff) * 0x763D;
	$d = $a - $c;
	if (($d & 0xffff) > 0x7FFF) { // Negative
		$d += 0x763D;
	}
	$d = ($d & 0xffff);

	$key_ = ($val | $d);

	return strtoupper(str_pad(dechex($key), 4, '0', STR_PAD_LEFT).str_pad(dechex($key_), 4, '0', STR_PAD_LEFT));
}

?>