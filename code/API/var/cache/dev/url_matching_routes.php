<?php

/**
 * This file has been auto-generated
 * by the Symfony Routing Component.
 */

return [
    false, // $matchHost
    [ // $staticRoutes
        '/docs' => [[['_route' => 'app_docs', '_controller' => 'App\\Controller\\DocsController::index'], null, ['GET' => 0], null, false, false, null]],
        '/donnees-virus' => [
            [['_route' => 'app_get_donnees_virus', '_controller' => 'App\\Controller\\DonneesVirusController::index'], null, ['GET' => 0], null, false, false, null],
            [['_route' => 'app_create_donnees_virus', '_controller' => 'App\\Controller\\DonneesVirusController::create'], null, ['POST' => 0], null, false, false, null],
        ],
        '/pays' => [
            [['_route' => 'app_get_pays', '_controller' => 'App\\Controller\\PaysController::index'], null, ['GET' => 0], null, false, false, null],
            [['_route' => 'app_create_pays', '_controller' => 'App\\Controller\\PaysController::create'], null, ['POST' => 0], null, false, false, null],
        ],
        '/virus' => [
            [['_route' => 'app_get_virus', '_controller' => 'App\\Controller\\VirusController::index'], null, ['GET' => 0], null, false, false, null],
            [['_route' => 'app_create_virus', '_controller' => 'App\\Controller\\VirusController::create'], null, ['POST' => 0], null, false, false, null],
        ],
    ],
    [ // $regexpList
        0 => '{^(?'
                .'|/_error/(\\d+)(?:\\.([^/]++))?(*:35)'
                .'|/donnees\\-virus/([^/]++)(*:66)'
                .'|/pays/([^/]++)(*:87)'
                .'|/virus/([^/]++)(*:109)'
            .')/?$}sDu',
    ],
    [ // $dynamicRoutes
        35 => [[['_route' => '_preview_error', '_controller' => 'error_controller::preview', '_format' => 'html'], ['code', '_format'], null, null, false, true, null]],
        66 => [[['_route' => 'app_delete_donnees_virus', '_controller' => 'App\\Controller\\DonneesVirusController::delete'], ['id'], ['DELETE' => 0], null, false, true, null]],
        87 => [[['_route' => 'app_delete_pays', '_controller' => 'App\\Controller\\PaysController::delete'], ['id'], ['DELETE' => 0], null, false, true, null]],
        109 => [
            [['_route' => 'app_delete_virus', '_controller' => 'App\\Controller\\VirusController::delete'], ['id'], ['DELETE' => 0], null, false, true, null],
            [null, null, null, null, false, false, 0],
        ],
    ],
    null, // $checkCondition
];
