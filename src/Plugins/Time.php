<?php

namespace Knwl\Plugins;

use Knwl\Abstracts\Plugin;

class Time extends Plugin {

  protected $languages = [
    'english',
  ];

  protected $name = 'time';

  protected $times = [ ];

  public function calls() {
    $words = $this->knwl->getWords('words');

    foreach ($words as $i => $w) {
      $testTime = explode(':', $w);
      if (count($testTime) === 2) {
        $meridian = false;
        if (preg_match('~[ap]m~i', $testTime[1], $m)) {
          $testTime[1] = substr($testTime[1], 0, -2);
          $meridian    = strtoupper($m[0]);
        }

        if (is_numeric($testTime[0]) && is_numeric($testTime[1])) {
          if ($testTime[0] > 0 && $testTime[0] < 13) {
            if ($testTime[1] >= 0 && $testTime[1] < 61) {
              if (preg_match('~[ap]m~i', $words[$i + 1], $m)) {
                $this->add($testTime[0], $testTime[1], strtoupper($m[0]));
              }
              else if ($meridian !== false) {
                $this->add($testTime[0], $testTime[1], null);
              }
            }
          }
        }
      }

    }

    foreach ($words as $i => $w) {
      $testTime = explode(':', $w);
      if (count($testTime) === 1) {
        if (is_numeric($w)) {
          $temp = (int) $w;
          if ($temp > 0 && $temp < 13) {
            if (preg_match('~[ap]m~i', $words[$i + 1], $m)) {
              $this->add($temp, '00', strtoupper($m[0]));
            }
          }
        }
        else if (preg_match('~[ap]m~i', $w, $m)) {
          $w = (int) substr($w, 0, -2);
          if (is_numeric($w)) {
            if ($w > 0 && $w < 13) {
              $this->add($w, '00', strtoupper($m[0]));
            }
          }
        }
      }
    }

    return $this->times;
  }

  protected function add($hour, $minute, $meridian) {
    $this->times[] = "{$hour}:{$minute}{$meridian}";
  }

}