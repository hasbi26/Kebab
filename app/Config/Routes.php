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
$routes->post('/sales/point', 'Sales::calculatePoints');
$routes->post('/sales/acumulatepoint', 'Sales::calculatePointsByMonth');
$routes->post('/sales/getSalesByPaymentType', 'Sales::getSalesByPaymentType');
$routes->get('/dash', 'Dashboard::index');
$routes->post('/dash/salesMonth', 'Dashboard::GetSalesMonthly');


