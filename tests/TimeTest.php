<?php

require_once 'Base.php';

class TimeTest extends Base {

  public function testCanParse() {
    $this->knwl->register(new Knwl\Plugins\Time);
    $this->knwl->parse($this->str);

    $this->assertContains('12:54PM', $this->knwl->get('time'));
//    $this->assertContains('11:42AM', $this->knwl->get('time'));
  }

}