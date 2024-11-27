<?php

use CodeIgniter\Router\RouteCollection;

/**
 * @var RouteCollection $routes
 */
$routes->get('/', 'Home::index');
$routes->get('/sales', 'Sales::index');
$routes->post('/sales/save', 'Sales::save');
$routes->post('/sales/getSales', 'Sales::getSales');
$routes->post('/sales/summary', 'Sales::getSalesSummary');


