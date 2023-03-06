# REST SDK API

SDK Rest API enables an easy way to communicate with it via HTTP.
So far, it is an experimental feature and is available only for local development.

## Installation

To start using rest-api server firstly you need to install the project locally.

```shell
spryker-sdk --mode=docker sdk i
```

After you can start the web server.

```shell
spryker-sdk rest-api-start # start server
spryker-sdk rest-api-start 8080 # start server on 8080 port
```

## Commands

```shell
spryker-sdk rest-api-start # start server
spryker-sdk rest-api-start 8080 # start server on 8080 port
spryker-sdk rest-api-stop # stop server
spryker-sdk rest-api-status # docker containers status
spryker-sdk rest-api-rm # remove docker containers
```

## Xdebug

To start server with xdebug enabled need to pass `--xdebug` or `-x` option into the command.
```shell
spryker-sdk rest-api-start -x 8000
```

## REST API doc
The rest api follows the [OpenApi specification](https://swagger.io/specification/).

The api doc can be found by these urls:
- `/api/doc` - swagger ui
- `/api/doc.json` - json format
- `/api/doc.yaml` - yaml formal
