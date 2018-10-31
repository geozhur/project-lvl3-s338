<?php

$host = env('DB_HOST', '127.0.0.1');
$database = env('DB_DATABASE', '');
$port = env('DB_PORT', '');
$username = env('DB_USERNAME', 'tututu');
$password = env('DB_PASSWORD', 'tututu');


if($databaseUrl = getenv('DATABASE_URL')) {
    $url = parse_url($databaseUrl);
    $host = $url['host'];
    $port = $url['port'];
    $username = $url['user'];
    $password = $url['pass'];
    $database = ltrim($url["path"],'/');
}

return [

    'fetch' => PDO::FETCH_CLASS,
    'default' =>env('DB_CONNECTION', 'sqlite'),
    'migrations' => 'migrations',
    'connections' => [

        'sqlite' => [
            'driver'   => 'sqlite',
            'database' => env('DB_DATABASE', base_path('database/database.sqlite')),
            'prefix'   => env('DB_PREFIX', ''),
        ],

        'pgsql' => [
            'driver' => 'pgsql',
            'host' => $host,
            'port' => $port,
            'database' => $database,
            'username' => $username,
            'password' => $password,
            'charset' => 'utf8',
            'prefix' => '',
            'schema' => 'public',
            'sslmode' => 'prefer',
        ],
    ],

];