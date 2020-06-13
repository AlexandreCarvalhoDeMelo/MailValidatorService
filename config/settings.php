<?php

declare(strict_types=1);

return static function (string $appEnv) {
    return [
        'app_env' => $appEnv,
        'di_compilation_path' => __DIR__ . '/../var/cache',
        'display_error_details' => false
    ];
};
