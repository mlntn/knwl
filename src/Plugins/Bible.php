<?php

namespace Knwl\Plugins;

use Knwl\Abstracts\Plugin;

class Bible extends Plugin {

  protected $languages = [
    'english',
  ];

  protected $name = 'bible';

  protected $books = [
    'Genesis'         => [ 'Gen' ],
    'Exodus'          => [ 'Ex' ],
    'Leviticus'       => [ 'Lev' ],
    'Numbers'         => [ 'Num' ],
    'Deuteronomy'     => [ 'Deut' ],
    'Joshua'          => [ 'Josh' ],
    'Judges'          => [ 'Judg' ],
    'Ruth'            => [ ],
    'Samuel'          => [ 'Sam' ],
    'Kings'           => [ ],
    'Chronicles'      => [ 'Chr' ],
    'Ezra'            => [ ],
    'Nehemiah'        => [ 'Neh' ],
    'Esther'          => [ 'Esth' ],
    'Job'             => [ ],
    'Psalm'           => [ 'Ps', 'Psalms' ],
    'Proverbs'        => [ 'Prov' ],
    'Ecclesiastes'    => [ 'Eccl', 'Ecc' ],
    'Song of Solomon' => [ 'Song', 'Song of Songs' ],
    'Isaiah'          => [ 'Isa', 'Is' ],
    'Jeremiah'        => [ 'Jer' ],
    'Lamentations'    => [ 'Lam' ],
    'Ezekiel'         => [ 'Ezek' ],
    'Daniel'          => [ 'Dan' ],
    'Hosea'           => [ 'Hos' ],
    'Joel'            => [ ],
    'Amos'            => [ 'Am' ],
    'Obadiah'         => [ 'Obad', 'Ob' ],
    'Jonah'           => [ 'Jon' ],
    'Micah'           => [ 'Mic' ],
    'Nahum'           => [ 'Nah' ],
    'Habakkuk'        => [ 'Hab' ],
    'Zephaniah'       => [ 'Zeph' ],
    'Haggai'          => [ 'Hag' ],
    'Zechariah'       => [ 'Zech' ],
    'Malachi'         => [ 'Mal' ],
    'Matthew'         => [ 'Matt', 'Mt' ],
    'Mark'            => [ 'Mk' ],
    'Luke'            => [ 'Luk', 'Lk' ],
    'John'            => [ 'Jn' ],
    'Acts'            => [ ],
    'Romans'          => [ 'Rom' ],
    'Corinthians'     => [ 'Cor' ],
    'Galatians'       => [ 'Gal' ],
    'Ephesians'       => [ 'Eph' ],
    'Philippians'     => [ 'Phil' ],
    'Colossians'      => [ 'Col' ],
    'Thessalonians'   => [ 'Thess', 'Th' ],
    'Timothy'         => [ 'Tim' ],
    'Titus'           => [ 'Tit' ],
    'Philemon'        => [ ],
    'Hebrews'         => [ 'Heb' ],
    'James'           => [ 'Jas' ],
    'Peter'           => [ 'Pet' ],
    'Jude'            => [ ],
    'Revelation'      => [ 'Rev' ],
  ];

  protected $sequential = [
    'Samuel',
    'Kings',
    'Chronicles',
    'Corinthians',
    'Thessalonians',
    'Timothy',
    'Peter',
  ];

  protected $both = [
    'John',
  ];

  public function __construct() {
    $books = [];
    foreach ($this->books as $b => $a) {
      $books[$b] = array_key_exists($b, $books) ? $books[$b] : [];
      if (in_array($b, $this->sequential) || in_array($b, $this->both)) {
        $books[$b][] = new Sequential($b, $a);
      }
      if (in_array($b, $this->sequential) === false || in_array($b, $this->both)) {
        $books[$b][] = new Nonsequential($b, $a);
      }
    }
    $this->books = $books;
  }

  public function calls(...$args) {
    $matches = [];
    $words   = $this->knwl->getWords('linkWordsCasesensitive');
    foreach ($words as $i => $w) {
      $w = preg_replace('~(^\D|\D$)~', '', $w);
      if (preg_match('~^\d+([,:\-;]\d+)*$~', $w)) {
        $r = preg_replace('~[^A-z]~', '', $words[$i - 1]);

        foreach ($this->books as $books) {
          foreach ($books as $b) {
            if ($b->check($r, $w)) {
              $matches[] = [
                'passage' => $b->get() . ' ' . $w,
              ];
            }
          }
        }
      }
    }

    return $matches;
  }

}

class Nonsequential {

  protected $name;
  protected $abbr;

  public function __construct($name, $abbr) {
    $this->name = $name;
    $this->abbr = $abbr;
  }

  public function check($word, $previous) {
    return $word === $this->name || in_array($word, $this->abbr);
  }

  public function get() {
    return $this->name;
  }

}

class Sequential extends Nonsequential {

  protected $number;

  public function __construct($name, $abbr) {
    parent::__construct($name, $abbr);
  }

  public function check($word, $previous) {
    return ($word === $this->name || in_array($word, $this->abbr)) && $this->number = $previous;
  }

  public function get() {
    return trim($this->number . ' ' . $this->name);
  }

}