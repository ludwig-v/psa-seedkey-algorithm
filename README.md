# PSA Seed/Key Algorithm

PSA Seed / Key Algorithm can be found by various ways, here is one: analyzing assembly from NAND dumps of various ECUs and searching for functions matching

#### Partial copy of assembly code extracted from CIROCCO:
    .DNH_DNB_CLIENT_IMX6S_appli.elf.RO:423060C0 sub_423060C0                            ; CODE XREF: sub_42306030+1A↑p
    .DNH_DNB_CLIENT_IMX6S_appli.elf.RO:423060C0                                         ; sub_42306030+40↑p
    .DNH_DNB_CLIENT_IMX6S_appli.elf.RO:423060C0                 MOV             R1, #0xB92143FB
    .DNH_DNB_CLIENT_IMX6S_appli.elf.RO:423060C8                 SMMUL.W         R1, R1, R0
    .DNH_DNB_CLIENT_IMX6S_appli.elf.RO:423060CC                 ADDS            R1, R1, R0
    .DNH_DNB_CLIENT_IMX6S_appli.elf.RO:423060CE                 ASRS            R1, R1, #7
    .DNH_DNB_CLIENT_IMX6S_appli.elf.RO:423060D0                 SUB.W           R1, R1, R0,ASR#31
    .DNH_DNB_CLIENT_IMX6S_appli.elf.RO:423060D4                 MOVS            R2, #0xB1
    .DNH_DNB_CLIENT_IMX6S_appli.elf.RO:423060D6                 SMULBB.W        R2, R1, R2
    .DNH_DNB_CLIENT_IMX6S_appli.elf.RO:423060DA                 SUBS            R0, R0, R2
    .DNH_DNB_CLIENT_IMX6S_appli.elf.RO:423060DC                 MOVS            R2, #0xAB
    .DNH_DNB_CLIENT_IMX6S_appli.elf.RO:423060DE                 SMULBB.W        R0, R0, R2
    .DNH_DNB_CLIENT_IMX6S_appli.elf.RO:423060E2                 SUB.W           R0, R0, R1,LSL#1
    .DNH_DNB_CLIENT_IMX6S_appli.elf.RO:423060E6                 SXTH            R0, R0
    .DNH_DNB_CLIENT_IMX6S_appli.elf.RO:423060E8                 CMP             R0, #0
    .DNH_DNB_CLIENT_IMX6S_appli.elf.RO:423060EA                 ITTT LT
    .DNH_DNB_CLIENT_IMX6S_appli.elf.RO:423060EC                 MOVLTW          R1, #0x763D
    .DNH_DNB_CLIENT_IMX6S_appli.elf.RO:423060F0                 ADDLT           R0, R0, R1
    .DNH_DNB_CLIENT_IMX6S_appli.elf.RO:423060F2                 SXTHLT          R0, R0
    .DNH_DNB_CLIENT_IMX6S_appli.elf.RO:423060F4                 BX              LR

Each ECU has his own key, which can be found inside calibration files or using bruteforce to find matching key using a couple of seed/key pairs generated from official diagnostic tool - which is very fast to do because ECU key is only 2 bytes long -