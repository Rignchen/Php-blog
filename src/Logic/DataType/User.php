<?php

namespace Rignchen\SlimExemple\Logic\DataType;

class User {

    private int $id;
    private string $username;
    private string $email;
    private string $password;
    private string $created_at;

    public function __construct(array $data) {
        $this->id = $data['id'];
        $this->username = $data['username'];
        $this->email = $data['email'];
        $this->password = $data['password'];
        $this->created_at = $data['created_at'];
    }

    public function compare_user(User $user): bool {
        return $this->id === $user->get_id();
    }

    public function get_id(): int {
        return $this->id;
    }
    public function get_username(): string {
        return $this->username;
    }
    public function get_email(): string {
        return $this->email;
    }
    public function get_creation_date(): string {
        return $this->created_at;
    }
}