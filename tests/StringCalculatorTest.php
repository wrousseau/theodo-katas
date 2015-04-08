<?php

class StringCalculatorTest extends PHPUnit_Framework_TestCase
{

  protected $stringCalculator;

  protected function setUp()
  {
    $loggerStub = $this->getMockBuilder('Logger')
    ->disableOriginalConstructor()
    ->getMock();
    $exceptionServiceStub = $this->getMockBuilder('Logger')
    ->disableOriginalConstructor()
    ->getMock();
    $this->stringCalculator = $this->getMockBuilder('StringCalculator')
    ->setConstructorArgs(array($loggerStub, $exceptionServiceStub))
    ->setMethods(array('printResult'))
    ->getMock();
  }

  public function testAddEmpty()
  {
    $this->assertEquals(1, $this->stringCalculator->add(''));
  }

  public function testAddOneNumber()
  {
    $this->assertEquals(1, $this->stringCalculator->add('1'));
  }

  public function testAddTwoNumbers()
  {
    $this->assertEquals(3, $this->stringCalculator->add("1,2"));
  }

  public function testAddThreeNumbers()
  {
    $this->assertEquals(6, $this->stringCalculator->add("1,2,3"));
  }

  public function testAddManyNumbers()
  {
    $this->assertEquals(20, $this->stringCalculator->add("1,2,3,4,10"));
  }

  public function testAddWithNewline()
  {
    $this->assertEquals(6, $this->stringCalculator->add("1\n2,3"));
  }

  public function testAddCustomerDelimiter()
  {
    $str = "//;\n1;2";
    $this->assertEquals(3, $this->stringCalculator->add($str));
  }

  /**
  * @expectedException        InvalidArgumentException
  * @expectedExceptionMessage negatives not allowed
  */
  public function testAddNegativeNumber()
  {
    $str = "1,-2";
    $this->stringCalculator->add($str);
  }

  /**
  * @expectedException        InvalidArgumentException
  * @expectedExceptionMessage negatives not allowed : -2,-4
  */
  public function testAddNegativeNumbersInMessage()
  {
    $str = "1,-2,3,-4";
    $this->stringCalculator->add($str);
  }

  public function testAddLargeNumbers()
  {
    $str = "1,1001";
    $this->assertEquals(1, $this->stringCalculator->add($str));
  }

  public function testAddStringDelimiter()
  {
    $str = "//***\n1***2***3";
    $this->assertEquals(6, $this->stringCalculator->add($str));
  }

  public function testAddStringDelimiterWithSquareBraquets()
  {
    $str = "//[***]\n1***2***3";
    $this->assertEquals(6, $this->stringCalculator->add($str));
  }

  public function testAddMultipleCustomDelimiters()
  {
    $str = "//[*][%]\n1*2%3";
    $this->assertEquals(6, $this->stringCalculator->add($str));
  }

  public function testAddMultipleStringCustomDelimiters()
  {
    $str = "//[***][%]\n1***2%3";
    $this->assertEquals(6, $this->stringCalculator->add($str));
  }

  public function testWritingInLogger()
  {
    $logger = $this->getMockBuilder('Logger')
    ->setMethods(array('write'))
    ->setConstructorArgs(array('test.log'))
    ->getMock();

    $logger->expects($this->exactly(3))
    ->method('write')
    ->withConsecutive(array(0), array(1), array(3));

    $this->stringCalculator->setLogger($logger);

    $this->stringCalculator->add('');
    $this->stringCalculator->add('1');
    $this->stringCalculator->add('1,2');
  }

  public function testNotifyExceptionServiceWhenLoggerThrows()
  {
    $exceptionMessage = 'exceptionMessage';

    $loggerStub = $this->getMockBuilder('Logger')
    ->disableOriginalConstructor()
    ->getMock();
    $exceptionServiceMock = $this->getMockBuilder('ExceptionService')
    ->setMethods(array('notify'))
    ->getMock();

    $exceptionServiceMock->expects($this->once())
    ->method('notify')
    ->with($this->equalTo($exceptionMessage));

    $loggerStub->method('write')
    ->will($this->throwException(new Exception($exceptionMessage)));

    $this->stringCalculator->setLogger($loggerStub);
    $this->stringCalculator->setExceptionService($exceptionServiceMock);
    $this->stringCalculator->add('');
  }

  public function testPrintResult()
  {
    $loggerStub = $this->getMockBuilder('Logger')
    ->disableOriginalConstructor()
    ->getMock();
    $exceptionServiceStub = $this->getMockBuilder('Logger')
    ->disableOriginalConstructor()
    ->getMock();

    $this->stringCalculator->expects($this->once())
    ->method('printResult')
    ->with($this->equalTo("6\n"));

    $this->stringCalculator->add('1,2,3');
  }
}
