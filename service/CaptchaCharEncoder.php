<?php

namespace service;

class CaptchaCharEncoder
{
    public function encode(string $char): int
    {
        $map = array_flip(['6', '2', 'A', 'B', 'C', 'D', 'E', 'F', 'G', 'H', 'I', 'J', 'K', 'L', 'M', 'N', 'O', 'P', 'Q', 'R', 'S', 'T', 'U', 'W', 'V', 'X', 'Y', 'Z']);
        return $map[$char];
    }
}
