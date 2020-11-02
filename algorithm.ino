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

int transform(byte data_msb, byte data_lsb, byte sec[]) {
  int data = (data_msb << 8) | data_lsb;
  int result = ((data % sec[0]) * sec[2]) - ((data / sec[0]) * sec[1]);
  if (result < 0)
    result += (sec[0] * sec[2]) + sec[1];
  return result;
}

unsigned long compute_response(unsigned short pin, unsigned long chg) {
  byte sec_1[3] = {0xB2,0x3F,0xAA};
  byte sec_2[3] = {0xB1,0x02,0xAB};

  long res_msb = transform((pin >> 8), (pin & 0xFF), sec_1) | transform(((chg >> 24) & 0xFF), (chg & 0xFF), sec_2);
  long res_lsb = transform(((chg >> 16) & 0xFF), ((chg >> 8) & 0xFF), sec_1) | transform((res_msb >> 8), (res_msb & 0xFF), sec_2);
  return (res_msb << 16) | res_lsb;
}