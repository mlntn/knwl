<?php

require_once 'Base.php';

class PhoneTest extends Base {

  public function testCanParse() {
    $this->knwl->register(new Knwl\Plugins\Phone);
    $this->knwl->parse($this->str);

    $this->assertContains('2123231239', $this->knwl->get('phone'));
    $this->assertContains('18005674321', $this->knwl->get('phone'));
  }

}