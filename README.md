# Spryker SDK

[![Build Status](https://github.com/spryker-sdk/spryker-sdk/workflows/CI/badge.svg?branch=master)](https://github.com/spryker-sdk/app-validator/actions?query=workflow%3ACI+branch%3Amaster)
[![Minimum PHP Version](https://img.shields.io/badge/php-%3E%3D%208.0-8892BF.svg)](https://php.net/)
[![PHPStan](https://img.shields.io/badge/PHPStan-level%208-brightgreen.svg?style=flat)](https://phpstan.org/)

The Spryker SDK aims to provide a single entry point to accelerate your productivity working with Spryker.
No matter if you want to validate existing code, implement new features with Spryker or go live with your project,
the Spryker SDK provides you the tools to fulfill your mission faster and cut out the boring parts of the development,
so you can focus developing exciting features for your business case.

## Installation

- ensure docker & docker-compose is installed
- @todo download release from github to /<any path>
- echo "add alias spryker-sdk='php /<any path>/bin/spryker-sdk.sh'" >> ~/.bashrc

## Update
- @todo auto update for sdk in spryker-sdk.sh

## Getting started

To get an overview on the available capabilities of the Spryker SDK please run
`spryker-sdk list`

Any task can be executed by running `spryker-sdk <task-id>` from project root folder.
Using `bin/consolespryker-sdk <task-id> -h` will give a description on what options can be passed into the task.

## Extending the SDK capabilities

Pleaser refer to the [development documentation](./docs/development.md) for further information on how
to extend the SDK.

## PhpStorm Command Line Tools

Please refer to the [phpstorm integration documentation](./docs/phpstorm_cli_integration.md) for further information on how to integrate the SDK to PhpStorm.
