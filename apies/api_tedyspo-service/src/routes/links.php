<?php
// Collection Links
// GET
$app->get('/events/{id_event}/links[/]', atelier\tedyspo\actions\links\GetLinksAction::class)->setName('get_links');
$app->get('/events/{id_event}/links/{id_link}[/]', atelier\tedyspo\actions\links\GetLinkAction::class)->setName('get_link');

// POST
$app->post('/events/{id_event}/links[/]', atelier\tedyspo\actions\links\CreateLinkAction::class)->setName('create_link');

// PUT
$app->put('/events/{id_event}/links/{id_link}[/]', atelier\tedyspo\actions\links\UpdateLinkAction::class)->setName('update_link');

// DELETE
$app->delete('/events/{id_event}/links/{id_link}[/]', atelier\tedyspo\actions\links\DeleteLinkAction::class)->setName('delete_link');
