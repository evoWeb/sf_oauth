<?php

$EM_CONF['sf_oauth'] = [
    'title' => 'OAuth Service',
    'description' => 'Offers a service that can be instantiated in frontend 
    plugins and backend modules to authenticate against twitter to post
    updates or get information and tweets.',
    'category' => 'Sebastian Fischer',
    'author' => 'Sebastian Fischer',
    'author_email' => 'typo3@evoweb.de',
    'author_company' => 'evoweb',
    'state' => 'beta',
    'clearcacheonload' => 1,
    'version' => '1.0.0',
    'constraints' => [
        'depends' => [
            'typo3' => '8.0.0-8.7.99',
        ],
        'conflicts' => [],
        'suggests' => [],
    ],
    "autoload-dev" => [
        "psr-4" => [
            "Evoweb\\SfOauth\\Tests\\" => "Tests/",
        ],
    ],
];
