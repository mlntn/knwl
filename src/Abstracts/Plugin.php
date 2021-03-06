<?php

namespace Knwl\Abstracts;


use Knwl\Contracts\Plugin as PluginContract;
use Knwl\Knwl;

abstract class Plugin implements PluginContract {

  /**
   * @var Knwl
   */
  protected $knwl;

  protected $name;

  public function setKnwl(Knwl $knwl) {
    $this->knwl = $knwl;
  }

  public function getName() {
    return $this->name;
  }

}