// app/helpers.php
if (!function_exists('route_exists')) {
    function route_exists(string $name): bool {
        try {
            route($name);
            return true;
        } catch (\Exception $e) {
            return false;
        }
    }
}