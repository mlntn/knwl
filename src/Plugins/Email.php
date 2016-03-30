<?php

namespace Knwl\Plugins;


use Knwl\Abstracts\Plugin;

class Email extends Plugin {

  protected $languages = [
    'english',
  ];

  protected $name = 'email';

  public function calls(...$args) {
    $test = '~\b[A-Z0-9._%+-]+@([A-Z0-9.-]+\.[A-Z]{2,4}|(([0-9]|[1-9][0-9]|1[0-9]{2}|2[0-4][0-9]|25[0-5])\.){3}([0-9]|[1-9][0-9]|1[0-9]{2}|2[0-4][0-9]|25[0-5]))\b~i';

    $results = [];
    $match = '';

    $words = $this->knwl->getWords('linkWordsCasesensitive');

    foreach ($words as $i => $w) {
      $word = preg_split('~[\,\|\(\)\?]~', $w);
      foreach ($word as $j=>$l) {
        $temp = preg_replace('~[()!,]~', '', $l);
        if (preg_match($test, $temp, $match)) {
          $results[] = [
            'address' => $match[0],
          ];
        }
      }
    }

    return $results;
  }
}