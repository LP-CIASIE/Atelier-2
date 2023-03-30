<?php

use atelier\tedyspo\actions\links;
use atelier\tedyspo\middlewares\AccessEventMiddleware;
use atelier\tedyspo\middlewares\OwnerEventMiddleware;

// Collection Links
// GET
$app->get('/events/{id_event}/links[/]', links\GetLinksAction::class)->setName('get_links')->add(new AccessEventMiddleware($container));
$app->get('/events/{id_event}/links/{id_link}[/]', links\GetLinkAction::class)->setName('get_link')->add(new AccessEventMiddleware($container));

// POST
$app->post('/events/{id_event}/links[/]', links\CreateLinkAction::class)->setName('create_link')->add(new OwnerEventMiddleware($container));

// PUT
$app->put('/events/{id_event}/links/{id_link}[/]', links\UpdateLinkAction::class)->setName('update_link')->add(new OwnerEventMiddleware($container));

// DELETE
$app->delete('/events/{id_event}/links/{id_link}[/]', links\DeleteLinkAction::class)->setName('delete_link')->add(new OwnerEventMiddleware($container));
