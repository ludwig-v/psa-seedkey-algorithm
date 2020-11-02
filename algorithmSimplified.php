<?php

/*
Copyright 2020, Ludwig V. <https://github.com/ludwig-v>
Original algorithm by Wouter Bokslag & Jason F. <https://github.com/prototux>

This program is free software: you can redistribute it and/or modify
it under the terms of the GNU General Public License as published by
the Free Software Foundation, either version 3 of the License, or
(at your option) any later version.

This program is distributed in the hope that it will be useful,
but WITHOUT ANY WARRANTY; without even the implied warranty of
MERCHANTABILITY or FITNESS FOR A PARTICULAR PURPOSE.  See the
GNU General Public License at <http://www.gnu.org/licenses/> for
more details.

The above copyright notice and this permission notice shall be included in
all copies or substantial portions of the Software.
*/

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