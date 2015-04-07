<?php

class StringCalculatorTest extends PHPUnit_Framework_TestCase
{

  protected $stringCalculator;

  protected function setUp()
  {
    $this->stringCalculator = new StringCalculator();
  }

  public function testAddEmpty()
  {
    $this->assertEquals(0, $this->stringCalculator->add(''));
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
}
