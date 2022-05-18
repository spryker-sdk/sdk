# Development

## Running as docker container

### Building the dev container

You might need to build the SDK and tag it first:

```bash
docker pull spryker/php-sdk:latest
#Create new image with enabled debug spryker/php-sdk-debug:latest image
docker build -f {path to SDK}/infrastructure/sdk.debug.Dockerfile -t spryker/php-sdk-debug:latest {path to SDK}
spryker-sdk --mode=dev
```

### Run SDK in development mode
Requires (mutagen)[https://mutagen.io/documentation/introduction/installation] to be installed.

`spryker-sdk --mode=dev`

### Debug SDK
This will start a xdebug session with the serverName "spryker-sdk" (needs to be configured in PHPStorm)

`spryker-sdk --mode=debug <task>`

## Handy commands
debug
Helpful commands to use during development are

### Reset SDK
`rm db/data.db && spryker-sdk sdk:init:sdk`

### Reset project
`cd <project> && rm -f .ssdk && rm -f .ssdk.log && spryker-sdk sdk:init:project`

## Troubleshooting

### Problems with docker container
If you have problems with:
- pulling container from docker registry
- file permissions and ownership on files created by the SDK

You can build you own container from SDK sources. Please refer to [**Building flavored Spryker SDKs**](build.md)
