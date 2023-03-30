<?php

/**
 * API Basic Route
 */

$app->get('/', atelier\tedyspo\actions\HomeAction::class)->setName('home');
