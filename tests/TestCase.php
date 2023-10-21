<?php

namespace MailZeet\Tests;


use Faker\Factory;
use MailZeet\Factories\HttpFactory;
use MailZeet\Factories\ValidatorFactory;
use MailZeet\Payment;
use MailZeet\Payout;

class TestCase extends \PHPUnit\Framework\TestCase
{
    protected  $faker;
    protected HttpFactory $httpFactory;
    protected ValidatorFactory $validatorFactory;

    protected array $mailzeetClasses;

  public function setUp(): void
  {
    parent::setUp();

    $this->faker = Factory::create();
    $this->httpFactory = new HttpFactory();
    $this->validatorFactory = new ValidatorFactory();

    $this->mailzeetClasses = [
        Payment::class,
        Payout::class,
    ];

    $this->publicKey = $this->faker->md5();
    $this->secretKey = $this->faker->md5();

  }
}
