# Development

## Running as docker container

### building the dev container

you might need to build the SDK and tag it first:

```bash
docker image rm spryker/php-sdk:dev
bin/spryker-sdk.sh
docker images spryker/php-sdk #find latest tag
docker image tag spryker/php-sdk:<latest tag> spryker/php-sdk:latest
bin/spryker-sdk.sh --mode=dev
```

### Run SDK in development mode
Requires (mutagen)[https://mutagen.io/documentation/introduction/installation] to be installed.

`bin/spryker-sdk.sh --mode=dev`

### Debug SDK
This will start a xdebug session with the serverName "spryker-sdk" (needs to be configured in PHPStorm)

`bin/spryker-sdk.sh --mode=debug <task>`

## handy commands

Helpful commands to use during development are

### Reset SDK
`rm db/data.db && bin/spryker-sdk.sh sdk:init:sdk`

### Reset project
`cd <project> && rm .ssdk && rm .ssdk.log && bin/spryker-sdk.sh sdk:init:project`
