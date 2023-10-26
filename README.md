<div align="center">
<a href="https://mailzeet.com" title="MailZeet - Payment stack for Africa">
    <img src="/art/cover.png" alt="MailZeet website">
</a>

# MailZeet PHP SDK

<!-- Nav header - Start -->
<a href="https://www.mailzeet.com/">Website</a>
·
<a href="https://www.mailzeet.com/contact">Contact</a>
·
<a href="https://docs.mailzeet.com/">Documentation</a>

<!-- Nav header - END -->

<!-- Badges - Start -->
[![PHP Version](https://img.shields.io/packagist/php-v/mailzeet/mailzeet-php.svg)](https://packagist.org/packages/mailzeet/mailzeet-php)
[![Build Status](https://github.com/mailzeetHQ/mailzeet-php/actions/workflows/run-tests.yml/badge.svg?branch=main)](https://github.com/mailzeet/mailzeet-php/actions?query=branch%3Amain)
[![Latest Stable Version](https://poser.pugx.org/mailzeet/mailzeet-php/v/stable.svg)](https://packagist.org/packages/mailzeet/mailzeet-php)
[![Total Downloads](https://poser.pugx.org/mailzeet/mailzeet-php/downloads.svg)](https://packagist.org/packages/mailzeet/mailzeet-php)
[![License](https://poser.pugx.org/mailzeet/mailzeet-php/license.svg)](https://packagist.org/packages/mailzeet/mailzeet-php)

<!-- Badges - END -->


</div>

The MailZeet PHP SDK provides convenient access to the MailZeet API from applications written with PHP.


## Requirements
PHP Requirements: PHP 7.4 and later.

## Installation

You can install the package via composer:

```shell
composer require mailzeet/mailzeet-php
```

## Documentation
See the php SDK [documentation](https://docs.mailzeet.com/sdk/php).

## Development

1- Perform the tests
```shell
 composer test
```
2- Format and analyze your code before commit and push.
```shell
 composer format # Format your code with the required code style
 composer unused # check if there is an unused dependency
composer analyze # Analyze your code with phpstan
```

### DEV Mode
You can set `devMode` to `true` when creating a new instance of the SDK to enable the dev mode.
After enabling the dev mode, you can pass `devBaseUrl` to customize the base URL of the MailZeet API you want to use.
In dev mode, the SDK will use the `devBaseUrl` instead of the default base URL `https://api.mailzeet.com`.

## Notes
- The project is based on the KISS principle.
- Each time you make a change, you must run the tests and format your code.
- Each time you make a change, you must update the documentation.
- Each time you make a change, you must update the changelog.
- Each time you make a change, you must add test cases.
- Each time you make a change, you must update the version number.
- Each time you make a change, you must update the API documentation.
- Each time you make a change, you must update the README.md file.

## Security Vulnerabilities
If you discover a security vulnerability within MailZeet PHP SDK, please send an e-mail to MailZeet Security via [hello@mailzeet.com](mailto:security@mailzeet.com). All security vulnerabilities will be promptly addressed.

## License
The MailZeet PHP SDK is open-sourced software licensed under the [MIT license](LICENSE.md).
