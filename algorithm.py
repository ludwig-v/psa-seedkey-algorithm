"""
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
"""


def _to_int16(value: int) -> int:
    value &= 0xFFFF
    if value & 0x8000:
        return value - 0x10000
    return value


def _trunc_div(a: int, b: int) -> int:
    # Truncate toward zero to match C/JS integer division behavior.
    return int(a / b)


def _c_mod(a: int, b: int) -> int:
    return a - _trunc_div(a, b) * b


def transform(data: int, sec: tuple[int, int, int]) -> int:
    data = _to_int16(data)
    sec0, sec1, sec2 = sec
    result = (_c_mod(data, sec0) * sec2) - (_trunc_div(data, sec0) * sec1)
    if result < 0:
        result += (sec0 * sec2) + sec1
    return result & 0xFFFF


def get_key(seed_hex: str, app_key_hex: str = "0000") -> str:
    seed_hex = seed_hex.strip()
    app_key_hex = app_key_hex.strip()

    seed = [seed_hex[0:2], seed_hex[2:4], seed_hex[4:6], seed_hex[6:8]]
    app_key = [app_key_hex[0:2], app_key_hex[2:4]]

    # Hardcoded secrets
    sec_1 = (0xB2, 0x3F, 0xAA)
    sec_2 = (0xB1, 0x02, 0xAB)

    res_msb = transform(int(app_key[0] + app_key[1], 16), sec_1) | transform(
        int(seed[0] + seed[3], 16), sec_2
    )
    res_lsb = transform(int(seed[1] + seed[2], 16), sec_1) | transform(
        res_msb, sec_2
    )
    res = (res_msb << 16) | res_lsb
    return f"{res:08X}"


if __name__ == "__main__":
    import argparse

    parser = argparse.ArgumentParser(description="PSA seed/key algorithm.")
    parser.add_argument("seed", nargs="?", default="11111111")
    parser.add_argument("app_key", nargs="?", default="D91C")
    args = parser.parse_args()

    print(get_key(args.seed, args.app_key))
