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