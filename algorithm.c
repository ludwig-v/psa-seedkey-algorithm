#include <inttypes.h>

/* Full credit to Wouter Bokslag & Jason F. - https://github.com/prototux - */

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