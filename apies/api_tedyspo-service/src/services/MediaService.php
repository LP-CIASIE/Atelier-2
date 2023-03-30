<?php

namespace atelier\tedyspo\services;

use atelier\tedyspo\models\Comment;
use atelier\tedyspo\models\Media;

class MediaService extends AbstractService
{
  public function getMedias($id_comment)
  {
    try {
      $comment = Comment::findOrFail($id_comment);
    } catch (\Exception $e) {
      throw new \Exception('Ce commentaire n\'existe pas ou plus', 404);
    }

    try {
      $medias = $comment->medias()->get();
    } catch (\Exception $e) {
      throw new \Exception('Erreur lors de la récupération des médias', 500);
    }
    return $medias;
  }

  public function getMedia($id_media)
  {
    try {
      $media = Media::findOrFail($id_media);
    } catch (\Exception $e) {
      throw new \Exception('Ce média n\'existe pas ou plus', 404);
    }
    return $media;
  }

  public function createMedia($id_comment, $file_media)
  {
    try {
      $comment = Comment::findOrFail($id_comment);
    } catch (\Exception $e) {
      throw new \Exception('Ce commentaire n\'existe pas ou plus', 404);
    }

    $media = new Media();
    $media->id_media = \Ramsey\Uuid\Uuid::uuid4();
    $media->file_media = $file_media;
    $media->id_comment = $id_comment;

    try {
      $media->save();
    } catch (\Exception $e) {
      throw new \Exception('Erreur lors de la création du média', 500);
    }

    return $media;
  }

  public function deleteMedia($id_media)
  {
    try {
      $media = Media::findOrFail($id_media);
    } catch (\Exception $e) {
      throw new \Exception('Ce média n\'existe pas ou plus', 404);
    }

    try {
      $media->delete();
    } catch (\Exception $e) {
      throw new \Exception('Erreur lors de la suppression du média', 500);
    }
  }
}
