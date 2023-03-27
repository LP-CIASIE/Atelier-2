<?php

namespace atelier\gateway\errors\exceptions;

use Exception;
use Slim\Exception\HttpException;

class BodyErrorValidationException extends HttpException
{
  protected $code = 400;
  protected $message = 'Certain attributs ne sont pas conforme.';
  protected string $title = '400 - Error Validation Attribut.';
}
