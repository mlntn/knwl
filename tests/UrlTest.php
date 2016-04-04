<?php

require_once 'Base.php';

class UrlTest extends Base {

  public function testCanParse() {
    $this->knwl->register(new Knwl\Plugins\Url);
    $this->knwl->parse($this->str);

    $this->assertContains('http://theevent.com', $this->knwl->get('url'));
  }

}