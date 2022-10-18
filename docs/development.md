# Development

## Installation for development

Clone project and export `SPRYKER_SDK_ENV` with dev environment and create an alias
```shell
git clone git@github.com:spryker-sdk/sdk.git && \
cd sdk; \
PATH_TO_SDK=$(pwd) && \
if [ -e ~/.zshrc ]; then \
  echo 'export SPRYKER_SDK_ENV=dev' >> ~/.zshrc && \
  echo 'alias spryker-sdk="$PATH_TO_SDK/bin/spryker-sdk.sh"' >> ~/.zshrc && \
  source ~/.zshrc; \
else \
  echo 'export SPRYKER_SDK_ENV=dev' >> ~/.bashrc && \
  echo 'alias spryker-sdk="'$PATH_TO_SDK'/bin/spryker-sdk.sh"' >> ~/.bashrc && \
  source ~/.bashrc; \
fi;
```

Build containers
```shell
docker-compose -f docker-compose.yaml -f docker-compose.dev.yml build
```

## Usage

### How to run a task or a command
```shell
spryker-sdk <task|command>
```

### How to debug a task or a command
The server name in IDE should be `spryker-sdk`
```shell
spryker-sdk --mode=debug <task|command>
```

### How to run in the production environment
```shell
SPRYKER_SDK_ENV=prod spryker-sdk <task|command>
```

### How to run some command inside the docker container
```shell
spryker-sdk --mode=docker "<command>"

spryker-sdk --mode=docker "cd /data && composer cs-check"
```

### How to jump into the docker container
```shell
spryker-sdk --mode=docker /bin/bash
```

## SDK helper

Inside the container you can find the sdk helper with useful commands shortcuts and aliases

### How to refresh state after the switching to a new branch
```shell
spryker-sdk --mode=docker sdk --refresh
# or
spryker-sdk --mode=docker sdk r
```

The full command list
```shell
spryker-sdk --mode=docker sdk --help

    --refresh, -r           refreshes cache vendor and db
    --composer, -c          runs sdk composer
                            accepts composer arguments like 'sdk --composer install' 'sdk -c cs-check'
    --cache-clear, -cl      alias for 'rm -rf var/cache && bin/console cache:clear'
    --cs-fix, -cf           alias for 'composer cs-fix'
    --cs-check, -cc         alias for 'composer cs-check'
    --stan, -s              alias for 'composer stan'
    --unit, -u              runs codeception unit tests
                            accepts arguments like 'sdk -u someUnitTest.php'
    --acceptance, -a        runs codeception acceptance tests
                            accepts arguments like 'sdk -u someAcceptanceTest.php'
```

## Manage the configuration
See [https://symfony.com/doc/current/configuration.html](https://symfony.com/doc/current/configuration.html)

## Issues with docker container
If you have problems with:
- pulling container from docker registry
- file permissions and ownership on files created by the SDK

You can build you own container from SDK sources. Please refer to [**Building flavored Spryker SDKs**](build.md)

## Useful links
- [How to use the profiler](profiler.md)
