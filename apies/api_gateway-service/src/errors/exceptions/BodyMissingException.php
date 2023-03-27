<?php

namespace atelier\gateway\errors\exceptions;

use Exception;
use Slim\Exception\HttpException;
use Slim\Exception\HttpInternalServerErrorException;

class BodyMissingException extends HttpException
{
  protected $code = 400;
  protected $message = 'Le body de la requête n\'a pas été trouvé, veuillez recommencer.';
  protected string $title = '400 - Body introuvable.';
}
