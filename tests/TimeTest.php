<?php

require_once 'Base.php';

class TimeTest extends Base {

  public function testCanParse() {
    $this->knwl->register(new Knwl\Plugins\Time);
    $this->knwl->parse($this->str);

    $this->assertContains('12:54AM', $this->knwl->get('time'));
  }

}