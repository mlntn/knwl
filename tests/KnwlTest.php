<?php

class KnwlTest extends Base {

  public function testReturnsEmptyArrayForBadWordType() {
    $this->knwl->parse($this->str);

    $this->assertEquals([], $this->knwl->getWords('foo'));
  }
  
}