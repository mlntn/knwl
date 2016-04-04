<?php

require_once 'Base.php';

class BibleTest extends Base {

  public function testCanParse() {
    $this->knwl->register(new Knwl\Plugins\Bible);
    $this->knwl->parse($this->str);

//    $this->assertContains('2 Timothy', $this->knwl->get('bible'));
    $this->assertContains('Romans 12', $this->knwl->get('bible'));
    $this->assertContains('Colossians 3:23', $this->knwl->get('bible'));
    $this->assertContains('Psalm 119:12-14', $this->knwl->get('bible'));
    $this->assertContains('John 11:35-14:6', $this->knwl->get('bible'));
  }

}