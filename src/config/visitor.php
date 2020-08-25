<?php

    return [

        'table' => 'log_visitors',

        'model' => \ThemisMin\LaravelVisitor\Models\LogVisitor::class,

        'ignored' => [
            '192.168.10.0/24',
            //  '*.example.com',
            'localhost',
        ],

        'maxmind_db_path' => storage_path().'/geo/GeoLite2-City.mmdb',

    ];
