<?php

// Should be set to 0 in production
error_reporting(E_ALL);

// Should be set to '0' in production
ini_set('display_errors', '1');

$settings = [
  'displayErrorDetails' => true,

  'logger' => [
    'name' => 'app',
    'path' => __DIR__ . '/logs/app.log',
  ],

  'db' => [
    'path' => __DIR__ . '/storage.db',
  ],
];

return $settings;
