<?php

/**
 * Paths and names for the javascript libraries needed by higcharts/highstock charts
 */
$jsFiles = array(
    'jQuery' => array(
        'name' => '/jquery-1.8.1.min.js',
        'path' => site_url('assets/lib/jquery')),

    'mootools' => array(
        'name' => 'mootools-yui-compressed.js',
        'path' => 'https://ajax.googleapis.com/ajax/libs/mootools/1.4.5/'),

    'prototype' => array(
        'name' => 'prototype.js',
        'path' => 'https://ajax.googleapis.com/ajax/libs/prototype/1.7.0.0/'),

    'highcharts' => array(
        'name' => '/highcharts.js',
        'path' => site_url('assets/highcharts/js')),

    'highchartsMootoolsAdapter' => array(
        'name' => '/mootools-adapter.js',
        'path' => site_url('assets/highcharts/js/adapters')),

    'highchartsPrototypeAdapter' => array(
        'name' => '/prototype-adapter.js',
        'path' => site_url('assets/highcharts/js/adapters')),

    'highstock' => array(
        'name' => 'highstock.js',
        'path' => 'http://code.highcharts.com/stock/'),

    'highstockMootoolsAdapter' => array(
        'name' => 'mootools-adapter.js',
        'path' => 'http://code.highcharts.com/stock/adapters/'),

    'highstockPrototypeAdapter' => array(
        'name' => 'prototype-adapter.js',
        'path' => 'http://code.highcharts.com/stock/adapters/'),

);
