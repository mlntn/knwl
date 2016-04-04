<?php

abstract class Base extends PHPUnit_Framework_TestCase {

  protected $str = 'This is a Knwl.js demo using a few of parser plugins. Click the \'parse\' button below to see the
project in action.

When you get a minute, check out Romans 12, 2 Tim, 1 Col. 3:23, Jn. 11:35-14:6 and Ps. 119:12-14.

My IP is 192.168.0.10, though someone tried to set it to 192.268.0.10.

I\'m sorry, I\'m extremely busy right now. I just looked at the clock, and it\'s 12:54 PM - 11:54AM for you - I\'ve
still got a lot of work to do.  Don\'t worry about 2016 event, it\'s been moved ahead a week, to the 28th of December.
Remember though, you\'ve got to call to get a ticket soon. Their # is 212-323-1239, or call the toll free number
1 800 567-4321. They\'re based in Chicago, and their website says it costs $23 per person.

800 888-888-8888

If you\'ve got enough time, they have some more information on their website, http://theevent.com.

Regards,
David (david32@gmail.com)';

  /**
   * @var \Knwl\Knwl
   */
  protected $knwl;

  public function setUp() {
    $this->knwl = new \Knwl\Knwl;
  }

}