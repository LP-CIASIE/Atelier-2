<?php

use atelier\tedyspo\actions;
use atelier\tedyspo\middlewares\AccessEventMiddleware;
use atelier\tedyspo\middlewares\OwnerEventMiddleware;

/**
 * API Basic Route
 */

$app->get('/', actions\HomeAction::class)->setName('home');
