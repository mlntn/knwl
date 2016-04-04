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
        return $this->plugins[$parser]->calls();
    }
    else {
      throw new PluginException("Parser plugin '{$parser}' not found.");
    }
  }

  public function register(Plugin $plugin) {
    $plugin->setKnwl($this);
    $this->plugins[$plugin->getName()] = $plugin;

    return $this;
  }

  public function removeCharacters($charArray, $str) {
    return strtr($str, array_combine($charArray, array_fill(0, count($charArray), '')));
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

}