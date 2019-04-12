<?php
class Issue2158Test extends PHPUnit_Framework_TestCase
{
    /**
     * Set constant in main process
     */
    public function testSomething()
    {
        include(str_replace("www/html", "docker-composer-lamp/www", __DIR__) . '/constant.inc');
        $this->assertTrue(true);
    }

    /**
     * Constant defined previously in main process constant should be available and
     * no errors should be yielded by reload of included files
     *
     * @runInSeparateProcess
     */
    public function testSomethingElse()
    {
        $this->assertTrue(defined('TEST_CONSTANT'));
    }
}
