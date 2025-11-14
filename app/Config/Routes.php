<?php

use CodeIgniter\Router\RouteCollection;

/**
 * @var RouteCollection $routes
 */



 
$routes->setDefaultNamespace('App\Controllers');
$routes->setDefaultController('Home');
$routes->setDefaultMethod('index');
$routes->setTranslateURIDashes(false);
$routes->set404Override();
$routes->setAutoRoute(true);
 
 
 
 
 
 
 
$routes->add('/', 'Home::index');
$routes->add('class/(:num)', 'Xclass::index/$1');

// Development Login (bypass OAuth for local development)
if (ENVIRONMENT === 'development') {
    $routes->add('dev-login', 'DevLogin::index');
    $routes->add('auto-login', 'AutoLogin::index');
}


$routes->add('member','Member\Home::index');
$routes->add('member/(:any)', 'Member\Home::index/$1');

$routes->add('xAdmin/edit_curriculum', 'XAdmin\Edit_curriculum::index');
$routes->add('xAdmin/edit_curriculum/(:num)', 'XAdmin\Edit_curriculum::index/$1');

$routes->add('xAdmin/permission', 'XAdmin\Permission::index');
$routes->add('xAdmin/permission/(:num)', 'XAdmin\Permission::spuser/$1');


$routes->add('xAdmin/edit_prayer_items/(:any)', 'XAdmin\Edit_prayer_items::index/$1');

 

$routes->add('xAdmin/prayer_items', 'XAdmin\Prayer_items::index'); 
$routes->add('xAdmin/prayer_items/notification/(:num)/(:num)', 'XAdmin\Prayer_items::notification/$1/$2');
$routes->add('xAdmin/prayer_items/(:num)', 'XAdmin\Prayer_items::index/$1');

$routes->add('xAdmin/export/(:num)/(:any)', 'XAdmin\Export::index/$1/$2');

$routes->add('xAdmin/curriculum', 'XAdmin\Curriculum::index');
$routes->add('xAdmin/curriculum/(:any)', 'XAdmin\Curriculum::index/$1');

$routes->add('xAdmin/group/new', 'XAdmin\Group::index/0');
$routes->add('xAdmin/group/(:num)', 'XAdmin\Group::index/$1');


$routes->add('xAdmin/baptist', 'XAdmin\Baptist::index');
$routes->add('xAdmin/baptist/(:num)', 'XAdmin\Baptist::index/$1');
$routes->add('xAdmin/baptist/user_pto_update', 'XAdmin\Baptist::user_pto_update');
$routes->add('xAdmin/baptist/syncMailchimp', 'XAdmin\Baptist::syncMailchimp');
$routes->add('xAdmin/baptist/getMailchimpStatus', 'XAdmin\Baptist::getMailchimpStatus');


$routes->add('xAdmin/search', 'XAdmin\Search::index');
$routes->add('xAdmin/search/(:num)/(:any)', 'XAdmin\Search::index/$1/$2');


$routes->add('xAdmin', 'XAdmin\Home::index');




$routes->add('pto', 'Pto::index');
$routes->add('pto/(:num)', 'Pto::index/$1');


