<?php

declare(strict_types=1);

enum Bit: int
{
    case D_1 = 1 << 0;
    case D_2 = 1 << 1;
    case D_4 = 1 << 2;
    case D_8 = 1 << 3;
    case D_16 = 1 << 4;
}
