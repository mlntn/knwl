<?php

namespace Knwl\Contracts;

interface Plugin {

  /**
   * @return string
   */
  public function getName();

  public function calls();

}