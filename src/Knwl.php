<?php

namespace Knwl;


use Knwl\Abstracts\Plugin;
use Knwl\Exceptions\PluginException;

class Knwl {

  protected $language = 'unknown';

  protected $words = [
    'words'                  => [ ],
    'linkWords'              => [ ],
    'linkWordsCasesensitive' => [ ],
  ];

  /**
   * @var Plugin[]
   */
  protected $plugins = [ ];

  public function parse($str) {
    $lowercase              = strtolower($str);
    $linkWords              = $lowercase = preg_split('~[ \n]+~', $lowercase);
    $linkWordsCaseSensitive = preg_split('~[ \n]+~', $str);

    $l = count($lowercase);

    for ($i = 0; $i < $l; $i++) {
      $lowercase[$i] = preg_replace([ '~[ ,?!]~', '["]' ], [ '', "'" ], $lowercase[$i]);
    }

    $words = [ ];
    for ($i = 0; $i < $l; $i++) {
      $words[$i] = preg_split('~[.,!?]+~', $lowercase[$i])[0];
    }

    $this->words['linkWordsCasesensitive'] = $linkWordsCaseSensitive;
    $this->words['linkWords']              = $linkWords;
    $this->words['words']                  = $words;
  }

  public function get($parser) {
    if (array_key_exists($parser, $this->plugins)) {
      try {
        return $this->plugins[$parser]->calls();
      }
      catch (\Exception $e) {
        throw new PluginException("Error running parser plugin '{$parser}'");
      }
    } else {
      throw new PluginException("Parser plugin '{$parser}' not found.");
    }
  }

  public function register(Plugin $plugin) {
    if (empty($plugin->languages) === false && $this->language !== 'unknown') {
      $languages = $plugin->getLanguages();
      if (array_key_exists($this->language, $languages) === false || $languages[$this->language] === false) {
        throw new PluginException('Parser plugin does not seem to support the specified language.');
      }
    }

    $plugin->setKnwl($this);
    $this->plugins[$plugin->getName()] = $plugin;

    return $this;
  }

  public function removeCharacters($charArray, $str) {
    return strtr($str, array_combine($charArray, array_fill(0, count($charArray), '')));
  }

  public function search($terms, $words) {
    $results = [ ];
    foreach ($words as $i => $w) {
      foreach ($terms as $t) {
        $pos = $i;
        if ($words[$pos] === $t[0]) {
          foreach (str_split($t) as $x => $j) {
            if ($words[$pos] === $j) {
              if (strlen($t) - 1 === $x) {
                $results[] = $t;
              }
            }
            $pos++;
          }
        }
      }
    }

    return $results;
  }

  /**
   * Used to get the entire sentence or a portion of it (depending on size), in a human-readable format, from a position
   *
   * @param integer $position
   * @return string
   */
  public function preview($position) {
    $words = $this->words['linkWordsCasesensitive'];

    $startPos = $endPos = $position;

    foreach (array_reverse(array_slice($words, 0, $position + 1, true), true) as $i => $w) {
      if (! preg_match('~[?!.]$~', $w, $match)) {
        if ($position - $startPos > 0) {
          $startPos = $i + 1;
          break;
        }
      }
      else if ($position - $startPos > 10) {
        break;
      }
    }

    foreach (array_slice($words, $position, null, true) as $i => $w) {
      $endPos = $i;
      if (preg_match('~[?!.]$~', $w, $match)) {
        break;
      }
      else if ($endPos - $position > 10) {
        break;
      }
    }

    return implode(' ', array_slice($words, $startPos, $endPos - $startPos + 1));
  }

  /**
   * Retrieve words from database
   *
   * @param string $type
   * @return string[]
   */
  public function getWords($type) {
    if (array_key_exists($type, $this->words)) {
      return $this->words[$type];
    }

    return [];
  }

  /**
   * Used to get the entire sentence a position occurs in, in a specific format
   *
   * @param integer $pos
   * @param string $type
   * @return string[]
   */
  public function getSentence($pos, $type) {
    $fullWords = $this->getWords('linkWordsCasesensitive');
    $typeWords = $this->getWords($type);

    $begin = 0;
    foreach (array_reverse(array_slice($fullWords, 0, $pos, true), true) as $i => $w) {
      if (preg_match('~[?!.]~', $w)) {
        $begin = $i + 1;
        break;
      }
    }

    $end = 0;
    foreach (array_slice($fullWords, $pos, 0, true) as $i => $w) {
      $end = $i;
      if (preg_match('~[?!.]~', $w)) {
        break;
      }
    }

    return array_slice($typeWords, $begin, $end);
  }

}