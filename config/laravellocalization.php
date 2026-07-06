<?php

return [
    'supportedLocales' => [
        'id' => ['name' => 'Indonesian', 'script' => 'Latn', 'native' => 'ID', 'regional' => 'id_ID'],
        'en' => ['name' => 'English', 'script' => 'Latn', 'native' => 'EN', 'regional' => 'en_GB'],
    ],

    'useAcceptLanguageHeader' => true,

    'hideDefaultLocaleInURL' => false,

    'localesOrder' => [],

    'localesMapping' => [],

    'utf8suffix' => env('LARAVELLOCALIZATION_UTF8SUFFIX', '.UTF-8'),

    'urlsIgnored' => ['/skipped'],

    'httpMethodsIgnored' => ['POST', 'PUT', 'PATCH', 'DELETE'],
];
