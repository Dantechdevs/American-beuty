<?php

if (!function_exists('route_exists')) {
    function route_exists(string $name): bool
    {
        try {
            return app('router')->getRoutes()->getByName($name) !== null;
        } catch (\Exception $e) {
            return false;
        }
    }
}

if (!function_exists('format_currency')) {
    function format_currency(float $amount, string $symbol = 'KSh'): string
    {
        return $symbol . ' ' . number_format($amount, 2);
    }
}

if (!function_exists('short_currency')) {
    function short_currency(float $amount, string $symbol = 'KSh'): string
    {
        return $symbol . ' ' . number_format($amount, 0);
    }
}