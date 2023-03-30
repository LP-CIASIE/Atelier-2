<?php

/** ============================
 * Collection Medias
 * ========================== */
// GET

$app->get('/comments/{id_comment}/medias[/]', atelier\tedyspo\actions\medias\GetCommentMediasAction::class)->setName('get_comment_medias');
$app->get('/comments/{id_comment}/medias/{id_media}[/]', atelier\tedyspo\actions\medias\GetCommentMediaAction::class)->setName('get_comment_media');

// POST
$app->post('/comments/{id_comment}/medias[/]', atelier\tedyspo\actions\medias\CreateCommentMediaAction::class)->setName('create_comment_media');

// PUT
$app->put('/comments/{id_comment}/medias/{id_media}[/]', atelier\tedyspo\actions\medias\UpdateCommentMediaAction::class)->setName('update_comment_media');

// DELETE
$app->delete('/comments/{id_comment}/medias/{id_media}[/]', atelier\tedyspo\actions\medias\DeleteCommentMediaAction::class)->setName('delete_comment_media');
