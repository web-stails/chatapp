<?php

return [
    // Создаваемые компоненты.

    'created_templates_components' => [
        'default' => [
            'builder',
            'collection',
            'controller',
//            'permission',
//            'permissionGroup',
            'policy',
            'request',
            'resource',
            'route',
            'service',
            'testPestController',
        ],
        'in_public' => [
            'builder',
            'collection',
            'controller',
            'request',
            'resource',
            'route',
            'service',
            'testPestController',
        ],
        'lite' => [
            'builder',
            'collection',
            'controller',
            'policy',
            'request',
            'resource',
            'route',
            'service',
            'testPestController',
        ]
    ]



];