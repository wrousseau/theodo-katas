<?php

class StringCalculator
{

  private $logger;
  private $exceptionService;

  public function __construct($logger, $exceptionService)
  {
    $this->logger = $logger;
    $this->exceptionService = $exceptionService;
  }

  public function getLogger()
  {
    return $this->logger;
  }

  public function setLogger($logger)
  {
    $this->logger = $logger;
  }

  public function getExceptionService()
  {
    return $this->exceptionService;
  }

  public function setExceptionService($exceptionService)
  {
    $this->exceptionService = $exceptionService;
  }

  public function add($str, $print = true)
  {
    // Is there a first line giving delimiters ?
    preg_match("#^//(.+)?\n(.+)#", $str, $matches);
    // If so, are there one or several delimiters in square brackets ?
    if (count($matches) && preg_match_all("#\[(.*?)\]#", $matches[1], $delimiterMatches))
    {
      $delimiters = array_slice($delimiterMatches, 1)[0];
    }
    else if (count($matches)) // Only one delimiter
    {
      $delimiters = $matches[1];
    }
    else // Using the default newline extra delimiter
    {
      $delimiters = "\n";
    }
    // Ignoring the first line (giving delimiters if present)
    $numbers = count($matches) ? $matches[2] : $str;
    // Getting the array of numbers
    $numbersArray = explode(",", str_replace($delimiters, ",", $numbers));

    $sum = 0;
    $negativeNumbers = array();
    foreach ($numbersArray as $number)
    {
      // Negative number to store in order to display in exception
      if ($number < 0)
      {
        $negativeNumbers[] = $number;
        continue;
      }
      else if ($number <= 1000) // Ignoring too large numbers
      {
        $sum += intval($number);
      }
    }
    // Throwing an exception if there were negative numbers
    if (count($negativeNumbers))
    {
      throw new InvalidArgumentException('negatives not allowed : '.implode(',', $negativeNumbers));
    }

    try {
      $this->logger->write($sum);
    } catch (Exception $e) {
      $this->exceptionService->notify($e->getMessage());
    }
    if ($print)
    {
      $this->printResult(strval($sum)."\n");
    }

    return $sum;
  }

  public function printResult($result)
  {
    echo $result;
  }
}
