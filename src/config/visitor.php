<?php

    return [

        'table' => 'visitor_registry',

        'model' => \ThemisMin\LaravelVisitor\Models\VisitorRegistry::class,

        'ignored' => [
            '192.168.10.0/24',
            //  '*.example.com',
            'localhost',
        ],

        'maxmind_db_path' => storage_path().'/geo/GeoLite2-City.mmdb',

    ];
