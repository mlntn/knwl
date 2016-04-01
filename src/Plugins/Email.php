<?php

namespace Knwl\Plugins;

use Knwl\Abstracts\Plugin;

class Email extends Plugin {

  protected $languages = [
    'english',
  ];

  protected $name = 'email';

  public function calls() {
    $results = [];

    $words = $this->knwl->getWords('linkWordsCasesensitive');

    foreach ($words as $i => $w) {
      $word = preg_split('~[\,\|\(\)\?]~', $w);
      foreach ($word as $j=>$l) {
        $temp = preg_replace('~[()!,]~', '', $l);
        if ($match = filter_var($temp, FILTER_VALIDATE_EMAIL)) {
          $results[] = $match;
        }
      }
    }

    return $results;
  }
}