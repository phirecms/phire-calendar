<?php
/**
 * Module Name: phire-calendar
 * Author: Nick Sagona
 * Description: This is the calendar module for Phire CMS 2, to be used in conjunction with the Content module
 * Version: 1.0
 */
return [
    'phire-calendar' => [
        'prefix'     => 'Phire\Calendar\\',
        'src'        => __DIR__ . '/../src',
        'events' => [
            [
                'name'     => 'app.send',
                'action'   => 'Phire\Calendar\Event\Calendar::parse',
                'priority' => 1000
            ]
        ]
    ]
];
