<?php

namespace Rignchen\SlimExemple\router;

use Twig\Extension\ExtensionInterface;

class AddTwigExtensions implements ExtensionInterface {
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
        return [];
    }
    public function getOperators(): array {
        return [];
    }
}