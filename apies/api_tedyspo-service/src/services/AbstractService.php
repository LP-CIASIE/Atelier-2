<?php

namespace atelier\tedyspo\services;

abstract class AbstractService
{
  protected $container;

  public function __construct($container)
  {
    $this->container = $container;
  }
}
