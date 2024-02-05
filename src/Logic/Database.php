<?php

namespace Rignchen\SlimExemple\Logic;
use PDO;

class Database
{
    private PDO $pdo;
    public function __construct(PDO $pdo){
        $this->pdo = $pdo;
    }
}
