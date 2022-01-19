# Development

## Running as docker container

### building the dev container

you might need to build the SDK and tag it first:

```bash
docker pull spryker/php-sdk:latest
#Create new image with enabled debug spryker/php-sdk-debug:latest image
docker build -f {path to SDK}/infrastructure/sdk.debug.Dockerfile -t spryker/php-sdk-debug:latest {path to SDK}/spryker-sdk
spryker-sdk --mode=dev
```

### Run SDK in development mode
Requires (mutagen)[https://mutagen.io/documentation/introduction/installation] to be installed.

`bin/spryker-sdk.sh --mode=dev`

### Debug SDK
This will start a xdebug session with the serverName "spryker-sdk" (needs to be configured in PHPStorm)

`bin/spryker-sdk.sh --mode=debug <task>`

## handy commands
debug
Helpful commands to use during development are

### Reset SDK
`rm db/data.db && bin/spryker-sdk.sh sdk:init:sdk`

### Reset project
`cd <project> && rm .ssdk && rm .ssdk.log && bin/spryker-sdk.sh sdk:init:project`
