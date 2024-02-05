<?php

namespace Rignchen\SlimExemple\Logic;
use PDO;

class Database
{
    private PDO $pdo;
    public function __construct(PDO $pdo){
        $this->pdo = $pdo;
    }

    public function get_user(String $username): array {
        $user_id = $this->pdo->prepare('SELECT * FROM users WHERE username = :username');
        $user_id->execute(['username' => $username]);
        return $user_id->fetch();
    }
    public function get_post(int $user_id, String $postName) {
        $stmt = $this->pdo->prepare('SELECT * FROM posts WHERE user_id = :username AND title = :postName');
        $stmt->execute(['username' => $user_id, 'postName' => $postName]);
        return $stmt->fetch();
    }
}
