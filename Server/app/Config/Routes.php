<?php

use CodeIgniter\Router\RouteCollection;

/**
 * @var RouteCollection $routes
 */
$routes->get('/', 'Home::index');

$routes->group('api', function ($routes) {

    $routes->get('pokemon', [\App\Controllers\Api\Pokemon::class, 'getPokemons'], ['as' => 'api_pokemon_getpokemons']);
    $routes->get('pokemon/(:num)', [\App\Controllers\Api\Pokemon::class, 'getPokemon'], ['as' => 'api_pokemon_getpokemon']);

});