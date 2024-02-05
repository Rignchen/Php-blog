<?php

namespace Rignchen\SlimExemple\Logic;

use Carbon\Carbon;
use Twig\Extension\ExtensionInterface;
use Twig\TwigFilter;
use Twig\TwigFunction;

class AddTwigExtensions implements ExtensionInterface {
    public function getTokenParsers(): array {
        return [];
    }
    public function getNodeVisitors(): array {
        return [];
    }
    public function getFilters(): array {
        return [
            new TwigFilter('carbon', function ($int) {return Carbon::createFromTimestamp($int)->toDateString();}),
        ];
    }
    public function getTests(): array {
        return [];
    }
    public function getFunctions(): array {
        return [
            new TwigFunction('dd', function ($var) {echo '<pre style="background-color: black; color: white; padding: 1rem">' . json_encode($var, JSON_PRETTY_PRINT) . '</pre>';}),
        ];
    }
    public function getOperators(): array {
        return [];
    }
}