<?php

namespace Rignchen\SlimExemple\Logic\DataType;

use Rignchen\SlimExemple\Logic\Database;

class Post {
    private int $user_id;
    private string $title;
    private string $content;
    private string $created_at;

    public function __construct(array $data) {
        $this->user_id = $data['user_id'];
        $this->title = $data['title'];
        $this->content = $data['content'];
        $this->created_at = $data['created_at'];
    }

    function test_property(User|null $user): bool {
        if ($user === null) return false;
        return $this->user_id === $user->get_id();
    }

    public function get_title(): string {
        return $this->title;
    }
    public function get_content(): string {
        return $this->content;
    }
    public function get_creation_date(): string {
        return $this->created_at;
    }
    public function get_user_id(): int {
        return $this->user_id;
    }
}