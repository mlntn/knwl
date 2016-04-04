<?php

namespace Knwl\Plugins;

use Knwl\Abstracts\Plugin;

class Ip extends Plugin {

  protected $languages = [
    'english',
  ];

  protected $name = 'ip';

  public function calls() {
    $results = [ ];
    
    $words   = $this->knwl->getWords('linkWords');

    foreach ($words as $i => $w) {
      $w = preg_replace('~(^\D|\D$)~', '', $w);
      if ($match = filter_var($w, FILTER_VALIDATE_IP)) {
        $results[] = $match;
      }
    }

    return $results;
  }

}