<?php

namespace App\Service;


class CodeGenerator
{
    /**
     * Function to generate random code
     * @param int $length
     * @return bool|string
     */
    public function getCode(int $length)
    {
        $stringAlph = 'ABCDEFGHIJKLMNOPQRSTUVWXYZ';
        $stringNum = '0123456789';

        return substr(str_shuffle($stringAlph), 0, 2) . substr(str_shuffle($stringNum), 0, $length - 2);
    }
}