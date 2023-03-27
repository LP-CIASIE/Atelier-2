<?php

namespace atelier\gateway\errors\exceptions;

use Exception;
use Slim\Exception\HttpException;

class BodyMissingAttributesException extends HttpException
{
  protected $code = 400;
  protected $message = 'Certain attributs du body de la requête sont manquants.';
  protected string $title = '400 - Missing Attribut.';
}
