<?php

use atelier\tedyspo\actions\events;
use atelier\tedyspo\middlewares\AccessEventMiddleware;
use atelier\tedyspo\middlewares\OwnerEventMiddleware;

/** =========================
 * Collection Evenements
 * ======================= */

// GET
$app->get('/events[/]', events\GetEventsAction::class)->setName('get_events');
$app->get('/events/{id_event}[/]', events\GetEventAction::class)->setName('get_event');

// POST
$app->post('/events[/]', events\CreateEventAction::class)->setName('create_event');

// PUT
$app->put('/events/{id_event}[/]', events\UpdateEventAction::class)->setName('update_event')->add(new OwnerEventMiddleware($container));

// DELETE
$app->delete('/events/{id_event}[/]', events\DeleteEventAction::class)->setName('delete_event')->add(new OwnerEventMiddleware($container));
