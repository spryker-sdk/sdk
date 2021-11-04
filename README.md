# Spryker SDK console application

## Run command
```{task} || --tags``` - is required parameter if it's not ```-h```

```php bin/spryker-sdk help {task}``` - shows arguments with default values

```php bin/spryker-sdk list``` - shows tasks list

```php bin/spryker-sdk run {task}``` = runs task

```php bin/spryker-sdk run --tags=sniff,architecture ...``` - runs tasks with tags

```php bin/spryker-sdk run {task} --tags=sniff,architecture ...``` - runs task and run other with tags
