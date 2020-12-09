def transform(data, sec):
    if data > 32767:
        data = -65536 | data

    if data > 0:
        q = int(data % int(sec[0]))
    else:
        q = int(data % int(sec[0])) - sec[0]

    a = (q * int(sec[2])) & 0xFFFFFFFF
    b = ((int(round(data / int(sec[0]), 12)) & 0xFFFFFFFF) * sec[1]) & 0xFFFFFFFF

    subresult = (a - b) & 0xFFFFFFFF

    if subresult > 0x7FFFFFFF:
        subresult = subresult + (sec[0] * sec[2]) + sec[1]

    subresult = subresult & 0xFFFF
    return subresult


def get_key(seed_txt, app_key_txt):
    seed = [seed_txt[0:2], seed_txt[2:4], seed_txt[4:6], seed_txt[6:8]]
    app_key = [app_key_txt[0:2], app_key_txt[2:4]]

    sec_1 = [0xB2, 0x3F, 0xAA]
    sec_2 = [0xB1, 0x02, 0xAB]

    res_msb_1 = transform(int(app_key[0] + app_key[1], 16), sec_1)
    res_msb_2 = transform(int(seed[0] + seed[3], 16), sec_2)
    res_msb = res_msb_1 | res_msb_2

    res_lsb = transform(int(seed[1] + seed[2], 16), sec_1) | transform(res_msb, sec_2)

    subresult = (res_msb << 16) | res_lsb
    subresult = hex(subresult)
    subresult = subresult.rjust(8, '0')

    return [hex(int(subresult[2:4], 16)), hex(int(subresult[4:6], 16)), hex(int(subresult[6:8], 16)),
            hex(int(subresult[8:10], 16))]
