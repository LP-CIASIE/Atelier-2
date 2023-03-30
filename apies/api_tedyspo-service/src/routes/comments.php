<?php

// Collection Commentaires
// GET
$app->get('/events/{id_event}/comments[/]', atelier\tedyspo\actions\comments\GetCommentsAction::class)->setName('get_comments')->add(new atelier\tedyspo\middlewares\AccessEventMiddleware($container));
$app->get('/events/{id_event}/comments/{id_comment}[/]', atelier\tedyspo\actions\comments\GetCommentAction::class)->setName('get_comment')->add(new atelier\tedyspo\middlewares\AccessEventMiddleware($container));

// POST
$app->post('/events/{id_event}/comments[/]', atelier\tedyspo\actions\comments\CreateCommentAction::class)->setName('create_comment')->add(new atelier\tedyspo\middlewares\AccessEventMiddleware($container));

// PUT
$app->put('/events/{id_event}/comments/{id_comment}[/]', atelier\tedyspo\actions\comments\UpdateCommentAction::class)->setName('update_comment')->add(new atelier\tedyspo\middlewares\AccessEventMiddleware($container));

// DELETE
$app->delete('/events/{id_event}/comments/{id_comment}[/]', atelier\tedyspo\actions\comments\DeleteCommentAction::class)->setName('delete_comment')->add(new atelier\tedyspo\middlewares\AccessEventMiddleware($container));
