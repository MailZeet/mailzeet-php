<?php

namespace MailZeet\Tests;


use Faker\Factory;

class TestCase extends \PHPUnit\Framework\TestCase
{
    protected \Faker\Generator $faker;

  public function setUp(): void
  {
    parent::setUp();

    $this->faker = Factory::create();

  }
}
