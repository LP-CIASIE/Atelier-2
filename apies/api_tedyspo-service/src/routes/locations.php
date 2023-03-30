<?php
// Collection locations
// GET
$app->get('/events/{id_event}/locations[/]', atelier\tedyspo\actions\locations\GetLocationsAction::class)->setName('get_locations')->add(new atelier\tedyspo\middlewares\AccessEventMiddleware($container));
$app->get('/events/{id_event}/locations/{id_location}[/]', atelier\tedyspo\actions\locations\GetLocationAction::class)->setName('get_location')->add(new atelier\tedyspo\middlewares\AccessEventMiddleware($container));

// POST
$app->post('/events/{id_event}/locations[/]', atelier\tedyspo\actions\locations\CreateLocationAction::class)->setName('create_location')->add(new atelier\tedyspo\middlewares\OwnerEventMiddleware($container));

// PUT
$app->put('/events/{id_event}/locations/{id_location}[/]', atelier\tedyspo\actions\locations\UpdateLocationAction::class)->setName('update_location')->add(new atelier\tedyspo\middlewares\OwnerEventMiddleware($container));

// DELETE
$app->delete('/events/{id_event}/locations/{id_location}[/]', atelier\tedyspo\actions\locations\DeleteLocationAction::class)->setName('delete_location')->add(new atelier\tedyspo\middlewares\OwnerEventMiddleware($container));
