<?php

use CodeIgniter\Router\RouteCollection;

/**
 * @var RouteCollection $routes
 */
$routes->get('/', 'Home::index');

$routes->group('api', function ($routes) {

    $routes->group('pokemon', function ($routes) {
        $routes->get('', [\App\Controllers\Api\Pokemon::class, 'getPokemons'], ['as' => 'api_pokemon_getpokemons']);
        $routes->get('(:num)', [\App\Controllers\Api\Pokemon::class, 'getPokemon'], ['as' => 'api_pokemon_getpokemon']);
        $routes->get('(:alpha)', [\App\Controllers\Api\Pokemon::class, 'findPokemon'], ['as' => 'api_pokemon_findPokemon']);
    });

    $routes->group('types', function ($routes) {
        $routes->get('', [\App\Controllers\Api\PokemonTypes::class, 'getTypes'], ['as' => 'api_pokemontypes_gettypes']);
    });

});