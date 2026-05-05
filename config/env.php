<?php
/**
 * Minimal .env loader. No external dependency.
 * Loads project-root /.env into $_ENV, $_SERVER, and putenv() so getenv() works.
 * Existing env vars (set by the OS / web server) are NOT overwritten.
 */

(function () {
    $envFile = dirname(__DIR__) . '/.env';
    if (!is_readable($envFile)) {
        return;
    }
    $lines = file($envFile, FILE_IGNORE_NEW_LINES | FILE_SKIP_EMPTY_LINES);
    foreach ($lines as $line) {
        $line = trim($line);
        if ($line === '' || $line[0] === '#') {
            continue;
        }
        if (strpos($line, '=') === false) {
            continue;
        }
        [$name, $value] = explode('=', $line, 2);
        $name = trim($name);
        $value = trim($value);
        // Strip surrounding single or double quotes
        if (strlen($value) >= 2) {
            $first = $value[0];
            $last = $value[strlen($value) - 1];
            if (($first === '"' && $last === '"') || ($first === "'" && $last === "'")) {
                $value = substr($value, 1, -1);
            }
        }
        if (getenv($name) === false) {
            putenv("$name=$value");
            $_ENV[$name] = $value;
            $_SERVER[$name] = $value;
        }
    }
})();

if (!function_exists('env')) {
    function env(string $key, $default = null) {
        $value = getenv($key);
        return $value === false ? $default : $value;
    }
}
