<?php

require_once 'Base.php';

class EmailTest extends Base {

  public function testCanParse() {
    $this->knwl->register(new Knwl\Plugins\Email);
    $this->knwl->parse($this->str);

    $this->assertContains('david32@gmail.com', $this->knwl->get('email'));
  }

}