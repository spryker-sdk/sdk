# Spryker SDK

[![Build Status](https://github.com/spryker-sdk/sdk/workflows/CI/badge.svg?branch=master)](https://github.com/spryker-sdk/sdk/actions?query=workflow%3ACI+branch%3Amaster)
[![codecov](https://codecov.io/gh/spryker-sdk/sdk/branch/master/graph/badge.svg?token=Ff8EDd0kgG)](https://codecov.io/gh/spryker-sdk/sdk)
[![Latest Stable Version](https://poser.pugx.org/spryker-sdk/sdk/v/stable.svg)](https://packagist.org/packages/spryker-sdk/sdk)
[![Minimum PHP Version](https://img.shields.io/badge/php-%3E%3D%207.4-8892BF.svg)](https://php.net/)
[![PHPStan](https://img.shields.io/badge/PHPStan-level%208-brightgreen.svg?style=flat)](https://phpstan.org/)

The Spryker SDK aims to provide a single entry point to accelerate your productivity working with Spryker.
No matter if you want to validate existing code, implement new features with Spryker or go live with your project,
the Spryker SDK provides you the tools to fulfill your mission faster and cut out the boring parts of the development,
so you can focus developing exciting features for your business case.

## Installation

- ensure auth file is available for composer (https://getcomposer.org/doc/articles/authentication-for-private-packages.md)
- ensure docker & docker-compose is installed
- Download the `installer.sh` from the latest release at https://github.com/spryker-sdk/sdk/releases
- run `installer.sh </path/to/install/sdk/in>`
- follow the installer's instructions.
- alias `spryker-sdk` should be set and `SPRYKER_SDK_PATH` env variable should be exported. If not check our troubleshooting doc.

Installation into the current dir:
```shell
PATH_TO_SDK=$(pwd) \
&& curl -fL github.com/spryker-sdk/sdk/releases/latest/download/installer.sh -O \
&& chmod +x installer.sh \
&& ./installer.sh "${PATH_TO_SDK}" \
&& rm -f installer.sh \
&& if [ -e ~/.zshrc ]; then source ~/.zshrc; else source ~/.bashrc; fi; \
echo "Current SDK version: $(spryker-sdk --version)"
```

## Update
Can be executed from any directory. The path will be taken from the `SPRYKER_SDK_PATH` env variable

```shell
curl -fL github.com/spryker-sdk/sdk/releases/latest/download/installer.sh -O \
&& chmod +x installer.sh \
&& ./installer.sh --self-update \
&& rm -f installer.sh \
&& echo "Current SDK version: $(spryker-sdk --version)"
```

## Getting started

To get an overview on the available capabilities of the Spryker SDK please run
`spryker-sdk list`

Any task can be executed by running `spryker-sdk <task-id>` from project root folder.
Using `bin/consolespryker-sdk <task-id> -h` will give a description on what options can be passed into the task.

## Extending the SDK capabilities

Pleaser refer to the [extension documentation](./docs/extending_the_sdk.md) for further information on how
to extend the SDK.
Extensions to the SDK should follow the [SDK conventions](./docs/conventions.md).
For maintaining an extension of the SDK please refer to the [lifecycle management](./docs/lifecycle_management.md).

## PhpStorm Command Line Tools

Please refer to the [phpstorm integration documentation](./docs/phpstorm_cli_integration.md) for further information on how to integrate the SDK to PhpStorm.

## Running the SDK as a developer

Running the SDK in a development or debug mode is documented at [development documentation](./docs/development.md)
