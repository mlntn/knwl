<?php

namespace Knwl\Plugins;

use Knwl\Abstracts\Plugin;

class Link extends Plugin {

  protected $languages = [
    'english',
  ];

  protected $name = 'link';

  public function calls(...$args) {
    $results = [ ];
    $words   = $this->knwl->getWords('linkWords');

    foreach ($words as $i => $w) {
      $word = preg_replace('~[ \(\)!]~', '', $w);
      if (preg_match('~^(https?|ftp):\/\/(-\.)?([^\s\/?\.#-]+\.?)+(\/[^\s]*)?$~i', $word)) {
        $link = $word;
        if (preg_match('~[?.!,]$~', $link)) {
          $link = substr($link, 0, -1);
        }
        $results[] = [
          'address' => $link,
        ];
      }
    }

    return $results;
  }

}