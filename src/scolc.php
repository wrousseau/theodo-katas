<?php

class Scalc
{
  protected $stringCalculator;

  public function __construct()
  {
    $logger = new Logger('StringCalculator.log');
    $exceptionService = new ExceptionService();
    $this->stringCalculator = new StringCalculator($logger, $exceptionService);
  }

  public function calculate($str)
  {
    $result = $this->stringCalculator->add($str, false);
    $this->printResult("The result is ".$result."\n");
  }

  public function printResult($result)
  {
    echo $result;
  }
}

if (isset($argv))
{
  $scalc = new Scalc();
  $scalc->calculate($argvs[1]);
}


?>
