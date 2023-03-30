<?php

use atelier\tedyspo\actions\medias;
use atelier\tedyspo\middlewares\AccessEventMiddleware;
use atelier\tedyspo\middlewares\OwnerEventMiddleware;

/** ============================
 * Collection Medias
 * ========================== */
// GET

$app->get('/comments/{id_comment}/medias[/]', medias\GetCommentMediasAction::class)->setName('get_comment_medias');
$app->get('/comments/{id_comment}/medias/{id_media}[/]', medias\GetCommentMediaAction::class)->setName('get_comment_media');

// POST
$app->post('/comments/{id_comment}/medias[/]', medias\CreateCommentMediaAction::class)->setName('create_comment_media');

// PUT
$app->put('/comments/{id_comment}/medias/{id_media}[/]', medias\UpdateCommentMediaAction::class)->setName('update_comment_media');

// DELETE
$app->delete('/comments/{id_comment}/medias/{id_media}[/]', medias\DeleteCommentMediaAction::class)->setName('delete_comment_media');
