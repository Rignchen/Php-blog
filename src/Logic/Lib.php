<?php

namespace Rignchen\SlimExemple\Logic;
use PDO;
use Rignchen\SlimExemple\Logic\DataType\Post;
use Rignchen\SlimExemple\Logic\DataType\User;

class Lib {
    public static function len($content): int {
        if (is_array($content)) {
            return count($content);
        }
        return strlen($content);
    }
}
