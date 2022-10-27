# REST SDK API

## Installation

To start using rest-api server firstly you need to install the project locally

```shell
spryker-sdk --mode=docker sdk i
```

After you can start the web server

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

To start server with xdebug enabled need to pass `--xdebug` or `-x` option into the command
```shell
spryker-sdk rest-api-start -x 8000
```
