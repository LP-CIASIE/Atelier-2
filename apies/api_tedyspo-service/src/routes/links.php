<?php
// Collection Links
// GET
$app->get('/events/{id_event}/links[/]', atelier\tedyspo\actions\links\GetLinksAction::class)->setName('get_links')->add(new atelier\tedyspo\middlewares\AccessEventMiddleware($container));
$app->get('/events/{id_event}/links/{id_link}[/]', atelier\tedyspo\actions\links\GetLinkAction::class)->setName('get_link')->add(new atelier\tedyspo\middlewares\AccessEventMiddleware($container));

// POST
$app->post('/events/{id_event}/links[/]', atelier\tedyspo\actions\links\CreateLinkAction::class)->setName('create_link')->add(new atelier\tedyspo\middlewares\OwnerEventMiddleware($container));

// PUT
$app->put('/events/{id_event}/links/{id_link}[/]', atelier\tedyspo\actions\links\UpdateLinkAction::class)->setName('update_link')->add(new atelier\tedyspo\middlewares\OwnerEventMiddleware($container));

// DELETE
$app->delete('/events/{id_event}/links/{id_link}[/]', atelier\tedyspo\actions\links\DeleteLinkAction::class)->setName('delete_link')->add(new atelier\tedyspo\middlewares\OwnerEventMiddleware($container));
