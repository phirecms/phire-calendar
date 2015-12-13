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
                'name'     => 'app.send.pre',
                'action'   => 'Phire\Calendar\Event\Calendar::init',
                'priority' => 1000
            ],
            [
                'name'     => 'app.send.post',
                'action'   => 'Phire\Calendar\Event\Calendar::parse',
                'priority' => 1000
            ]
        ],
        'weekdays' => [
            'Sun', 'Mon', 'Tue', 'Wed', 'Thu', 'Fri', 'Sat'
        ],
        'range'             => '6', // examples: 12, 12-12, EOY, SOY-EOY
        'range_format'      => 'M Y',
        'day_format'        => 'D, M j',
        'force_list'        => false,
        'force_list_mobile' => true,
        'show_all'          => true
    ]
];
