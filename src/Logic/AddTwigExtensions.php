<?php

namespace Rignchen\SlimExemple\Logic;

use Rignchen\SlimExemple\Logic\DataType\Post;
use Twig\Extension\ExtensionInterface;
use Twig\TwigFunction;

class AddTwigExtensions implements ExtensionInterface {
    public function __construct(private readonly Database $db) {}
    public function getTokenParsers(): array {
        return [];
    }
    public function getNodeVisitors(): array {
        return [];
    }
    public function getFilters(): array {
        return [];
    }
    public function getTests(): array {
        return [];
    }
    public function getFunctions(): array {
        return [
            new TwigFunction('dump', 'dump'),
            new TwigFunction('get_creator', fn (Post $post) => $this->db->get_user_by_id($post->get_user_id())),
        ];
    }
    public function getOperators(): array {
        return [];
    }
}