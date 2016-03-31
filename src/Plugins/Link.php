<?php

namespace Knwl\Plugins;

use Knwl\Abstracts\Plugin;

class Link extends Plugin {

  protected $languages = [
    'english',
  ];

  protected $name = 'link';

  public function calls() {
    $results = [ ];
    
    $words   = $this->knwl->getWords('linkWords');

    foreach ($words as $i => $w) {
      $word = preg_replace('~[ \(\)!]~', '', $w);
      if ($match = filter_var($word, FILTER_VALIDATE_URL)) {
        $match = preg_replace('~[?.!,]$~', '', $match);
        $results[] = [
          'address' => $match,
        ];
      }
    }

    return $results;
  }

}