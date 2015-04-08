<?php

class ScalcTest extends PHPUnit_Framework_TestCase
{
  protected $logger;

  protected function setUp()
  {
    $this->logger = new Logger('test.log');
  }

  public function testPrintResult()
  {
    $mock = $this->getMockBuilder('Scalc')
    ->setMethods(array('printResult'))
    ->getMock();

    $mock->expects($this->once())
    ->method('printResult')
    ->with($this->equalTo("The result is 6\n"));

    $mock->calculate('1,2,3');
  }
}
