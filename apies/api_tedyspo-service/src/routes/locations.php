<?php
// Collection locations
// GET
$app->get('/events/{id_event}/locations[/]', atelier\tedyspo\actions\locations\GetLocationsAction::class)->setName('get_locations');
$app->get('/events/{id_event}/locations/{id_location}[/]', atelier\tedyspo\actions\locations\GetLocationAction::class)->setName('get_location');

// POST
$app->post('/events/{id_event}/locations[/]', atelier\tedyspo\actions\locations\CreateLocationAction::class)->setName('create_location');

// PUT
$app->put('/events/{id_event}/locations/{id_location}[/]', atelier\tedyspo\actions\locations\UpdateLocationAction::class)->setName('update_location');

// DELETE
$app->delete('/events/{id_event}/locations/{id_location}[/]', atelier\tedyspo\actions\locations\DeleteLocationAction::class)->setName('delete_location');
