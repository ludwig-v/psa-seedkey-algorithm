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

#include <inttypes.h>

// Transformation function with PSA not-so-secret sauce
int16_t transform(uint8_t data_msb, uint8_t data_lsb, uint8_t sec[])
{
	int16_t data = (data_msb << 8) | data_lsb;
	int32_t result = ((data % sec[0]) * sec[2]) - ((data / sec[0]) * sec[1]);
	if (result < 0)
		result += (sec[0] * sec[2]) + sec[1];
	return result;
}

// Challenge reponse calculation for a given pin and challenge
// Challenge (seed) is 4 bytes and pin (key) is 2 bytes
uint32_t compute_response(uint8_t pin[], uint8_t chg[])
{
	// Still hardcoded secrets
	int8_t sec_1[3] = {0xB2, 0x3F, 0xAA};
	int8_t sec_2[3] = {0xB1, 0x02, 0xAB};

	// Compute each 16b part of the response, with the twist, and return it
	int16_t res_msb = transform(pin[0], pin[1], sec_1) | transform(chg[0], chg[3], sec_2);
	int16_t res_lsb = transform(chg[1], chg[2], sec_1) | transform(res_msb >> 8, res_msb & 0xFF, sec_2);
	return (res_msb << 16) | res_lsb;
}