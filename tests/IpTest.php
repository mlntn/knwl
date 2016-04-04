<?php

require_once 'Base.php';

class IpTest extends Base {

  public function testCanParse() {
    $this->knwl->register(new Knwl\Plugins\Ip);
    $this->knwl->parse($this->str);

    $this->assertContains('192.168.0.10', $this->knwl->get('ip'));
    $this->assertNotContains('192.268.0.10', $this->knwl->get('ip'));
  }

}