<?php
$dbopts = parse_url(env('DATABASE_URL'));

return [

   

    'fetch' => PDO::FETCH_CLASS,
    'default' =>env('DB_CONNECTION', 'sqlite'),
 
    'connections' => [

        'sqlite' => [
            'driver'   => 'sqlite',
            'database' => env('DB_DATABASE', base_path('database/database.sqlite')),
            'prefix'   => env('DB_PREFIX', ''),
        ],

        'pgsql' => [
            'driver' => 'pgsql',
            'host' => $dbopts['host'],
            'port' => $dbopts['port'],
            'database' => ltrim($dbopts["path"],'/'),
            'username' => $dbopts['user'],
            'password' => $dbopts['pass'],
            'charset' => 'utf8',
            'prefix' => '',
            'schema' => 'public',
            'sslmode' => 'prefer',
        ],
    ],

];