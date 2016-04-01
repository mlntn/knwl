<?php

namespace Knwl\Plugins;

use Knwl\Abstracts\Plugin;

class Phone extends Plugin {

  protected $languages = [
    'english',
  ];

  protected $name = 'phone';

  protected $area_code_length = 3;

  public function calls() {
    $results = [];

    $words = $this->knwl->getWords('words');
    $currWord = null;

    foreach ($words as $i=>$w) {
      $currWord = $this->knwl->removeCharacters(['-', '(', ')'], $w);

      if (preg_match('~^\d{7,13}$~', $currWord)) {
        /* At this point the word is thought to be a phone number.
           If the current word is only of length 7 it's required that the previous word
           is the area code, assuming there is a previous word. */
        if ($i > 0 && strlen($currWord) === 7) {
          $areaCode = $this->knwl->removeCharacters(['(', ')'], $words[$i - 1]);
          if (preg_match('~^\d{3}$~', $areaCode)) {
            $currWord = $areaCode . $currWord;

            /* At this point the current word and previous word make up the area code
               and phone number.
               Check whether the 2 words back represents the country code */
            if ($i > 1) {
              $countryCode = $this->knwl->removeCharacters(['+'], $words[$i - 2]);
              if (preg_match('~^\d{1,3}$~', $countryCode)) {
                $currWord = $countryCode . $currWord;
              }
            }
          }
          /* If the current word is not length 7, it's possible that the current word contains
         both the phone number and area code and the previous word is the country code.
         Once again, this is assuming that the areaCode length is 3 */
        }
        else if ($i > 0 && strlen($currWord) === $this->area_code_length + 7) {
          $countryCode = $this->knwl->removeCharacters(['+'], $words[$i - 1]);
          if (preg_match('~^\d{1,3}$~', $countryCode)) {
            $currWord = $countryCode . $currWord;
          }
        }

        /* We needed the phoneRegex to accept a minimum of 7 digits in case the preceding words
           made up the area code and possibly the country code, but if at this point there is
           not at least 7 digits plus the areaCodeLength in the currWord then it is not likely
           a phone number */
        if (strlen($currWord) >= 7 + $this->area_code_length) {
          $results[] = $currWord;
        }
      }
    }

    return $results;
  }

}