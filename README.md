# Spryker SDK

The Spryker SDK aims to provide a single entry point to accelerate your productivity working with Spryker.
No matter if you want to validate existing code, implement new features with Spryker or go live with your project,
the Spryker SDK provides you the tools to fulfill your mission faster and cut out the boring parts of the development,
so you can focus developing exciting features for your business case.

## Installation

- composer require `spryker-sdk/spryker-sdk`
- nano ~/.bashrc
- add alias spryker-sdk='php {path to sdk}/bin/console'
- source ~/.bashrc

## Update
composer update `spryker-sdk/spryker-sdk`

## Getting started

To get an overview on the available capabilities of the Spryker SDK please run
`spryker-sdk list`

Any task can be executed by running `spryker-sdk <task-id>` from project root folder.
Using `bin/consolespryker-sdk <task-id> -h` will give a description on what options can be passed into the task.

## Extending the SDK capabilities

Pleaser refer to the [development documentation](./docs/development.md) for further information on how
to extend the SDK.
