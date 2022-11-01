# Conventions

The Spryker SDK defines the following conventions.

## Task
`id`, `short_description`, `version`, `type`, `command` are  __REQUIRED__ properties.

- __MUST__ have an `id` following the schema `<group>:<language>:<subgroup>`, where `<group>` is one of validation, generation, `<language>` is php for now  and `<subgroup>` should be a descriptive name. E.g.: `validation:php:architecture`

- __MUST__ have a `version` according to the [semver](https://semver.org/) specification

- __MUST__ have a `short_description` with a short task description

- __MUST__ have a `type` one of a `local_cli`, `local_cli_interactive`, `task_set`, `php`

- __MUST__ have a `command` with executable command string or null

## Task Set

- __MUST__ have a `type` with `task_set` value

- __MUST__ have a `tasks` with the list of the required sub-tasks

- __MUST__ have a sub-task `id` in the `tasks` list

- __MUST__ have null value in `command` property (e.g. `command: ~`)

## Workflow

No conventions yet

## ValueResolver

- __MUST__ be suffixed with _ValueResolver_

- __MUST__ implement the [ValueResolverInterface](https://github.com/spryker-sdk/sdk-contracts/blob/master/src/ValueResolver/ValueResolverInterface.php)

[Configurable](https://github.com/spryker-sdk/sdk-contracts/blob/master/src/ValueResolver/ConfigurableValueResolverInterface.php) one __SHOULD__ be preferred over the concrete implementation

## Setting

- __MUST__ define the path with an underscore as separator (e.g.: `some_setting`)

- __MUST__ define the scope `setting_type: sdk/local/shared` to distinguish if the setting is per project or globally

- __MUST__  define a type to be either array, integer, string, boolean or float

- __MUST__ define the strategy to be used when setting the value (allowed values: `merge`, `overwrite`)

- __SHOULD__ define `init` as boolean. True means this value will be initially requested from the user

## Placeholder

- name __MUST__ start and end with `%` (e.g.: `%some_placeholder%`)
- __MUST__ define `optional: true/false` to indicate if the placeholders needs to be resolved to run the task
- __MUST__ use the `id` or full qualified class name of an existing `ValueResolver` for the field `valueResolver`

## Naming

### Folder naming

- Folder's name __MUST__ be singular. `Event` instead of `Events`.

### Class naming

- The name of the class __MUST__ be singular. `AcmeTask` instead of `AcmeTasks`.

### Method naming

- [Core convention](https://spryker.atlassian.net/wiki/spaces/CORE/pages/497156313/Common+Conventions#CommonConventions-Namingofmethods) `MUST` be followed.

### Variable naming

- [Core convention](https://spryker.atlassian.net/wiki/spaces/CORE/pages/497156313/Common+Conventions#CommonConventions-Namingofvariables) `MUST` be followed.


## Console command

- Console command __SHOULD__ have no business logic.
- Only basic input validation and output formatting __SHOULD__ present in the console command.
- `protected static $defaultName` __SHOULD NOT__ be used because of performance reasons and future deprecation in Symfony 6.1 version.
  Instead `protected const NAME` __SHOULD__ be provided and passed to the parent constructor as a parameter.

## Contracts

#### This section describes how to develop and document sdk contracts and their implementation.

- Contract is an interface that allows users to customize existing business logic.
- Contract __MUST__ exist only in case existing logic provides for an extension by the user.
- Contract is a public API and __MUST__ follow [Spryker plugin interfaces specification](https://spryker.atlassian.net/wiki/spaces/RFC/pages/1038092073/INTEGRATED+RFC+Plugin+interface+specification).

## REST API
- Controller __SHOULD__ have no business logic.
- Controller __SHOULD__ be named by template of task's id.
  For task `validation:php:codestyle` - controller name is `ValidationController` and controller's action name is `validationPhpCodestyle` (task's full id in camelCase).
- Controller __MUST__ be placed in namespace `SprykerSdk\Sdk\Presentation\RestApi\Controller\v1`. The `v1` is current version of SDK API.
- Route __MUST__ be placed in `src/Presentation/RestApi/Resources/routing.yaml`
- Route __SHOULD__ be named by template `api_${version}_${controller_name}_${action_name}`.
  Example: For action `ValidationController::validationPhpCodestyle` route is `api_v1_validation_validation_php_codestyle`
- Route's path __SHOULD__ be named in hyphen-case. For task `validation:php:codestyle` path is `/api/v1/validation-php-codestyle`

## TODO

- .env usage.
- Enums.
