<?php

/**
 * Created by PhpStorm.
 * User: jaredmellentine
 * Date: 4/4/16
 * Time: 8:27 AM
 */
class PluginTest extends Base {

  public function testThrowsExceptionOnBadPlugin() {
    $this->knwl->parse($this->str);

    $this->expectException(\Knwl\Exceptions\PluginException::class);
    
    $this->knwl->get('foo');
  }


}