<?php

use atelier\tedyspo\actions\locations;
use atelier\tedyspo\middlewares\AccessEventMiddleware;
use atelier\tedyspo\middlewares\OwnerEventMiddleware;

// Collection locations
// GET
$app->get('/events/{id_event}/locations[/]', locations\GetLocationsAction::class)->setName('get_locations')->add(new AccessEventMiddleware($container));
$app->get('/events/{id_event}/locations/{id_location}[/]', locations\GetLocationAction::class)->setName('get_location')->add(new AccessEventMiddleware($container));

// POST
$app->post('/events/{id_event}/locations[/]', locations\CreateLocationAction::class)->setName('create_location')->add(new OwnerEventMiddleware($container));

// PUT
$app->put('/events/{id_event}/locations/{id_location}[/]', locations\UpdateLocationAction::class)->setName('update_location')->add(new OwnerEventMiddleware($container));

// DELETE
$app->delete('/events/{id_event}/locations/{id_location}[/]', locations\DeleteLocationAction::class)->setName('delete_location')->add(new OwnerEventMiddleware($container));
