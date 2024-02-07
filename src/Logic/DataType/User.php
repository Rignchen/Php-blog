<?php

namespace Rignchen\SlimExemple\Logic\DataType;

class User {

    private int $id;
    private int $gender;
    private string $username;
    private string|null $avatar;
    private string $email;
    private string $password;
    private string $created_at;

    public function __construct(array $data) {
        $this->id = $data['id'];
        $this->gender = $data['gender'];
        $this->username = $data['username'];
        $this->avatar = $data['avatar'];
        $this->email = $data['email'];
        $this->password = $data['password'];
        $this->created_at = $data['created_at'];
    }

    public function compare_user(User|null $user): bool {
        if ($user === null) return false;
        return $this->id === $user->get_id();
    }

    public function get_id(): int {
        return $this->id;
    }
    public function get_gender(): int {
        return $this->gender;
    }
    public function get_username(): string {
        return $this->username;
    }
    public function get_profile_picture(): string|null {
        return $this->avatar;
    }
    public function get_email(): string {
        return $this->email;
    }
    public function get_creation_date(): string {
        return $this->created_at;
    }
}