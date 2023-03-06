# Conventions

The Spryker SDK defines the following conventions.

## Task
In a task, `id`, `short_description`, `version`, `type`, and `command` are required properties. The task properties must meet the following conditions:

- `id` must follow the schema `<group>:<language>:<subgroup>`, where `<group>` stands for validation or generation, `<language>` is PHP, and `<subgroup>` should be a descriptive name.  For example, there can be a task with the following ID: `validation:php:architecture`.

- `version` must follow the [semver](https://semver.org/) specification.

- Every task must have a `short_description`.

- `type` can have one of the following values: `local_cli`, `local_cli_interactive`, `task_set`, `php`.

- `command` must be an executable command string or null

## Task set

A task set must have the following properties:

- `type` with the `task_set` value.

- `tasks` with the list of the required sub-tasks.

- Sub-task `id` in the `tasks` list.

- Null value in the `command` property. For example, `command: ~`.

## Workflow

There are no conventions for the workflow yet.

## ValueResolver

A ValueResolver must meet the following conditions:

- It must be suffixed with _ValueResolver_.

- It must implement the [ValueResolverInterface](https://github.com/spryker-sdk/sdk-contracts/blob/master/src/ValueResolver/ValueResolverInterface.php).

**Note:** The [configurable](https://github.com/spryker-sdk/sdk-contracts/blob/master/src/ValueResolver/ConfigurableValueResolverInterface.php) ValueResolver interface should be preferred over the concrete implementation.

## Setting

A setting must meet the following conditions:

- Define the path with an underscore as a separator. For example, `some_setting`).

- Define the scope `setting_type: sdk/local/shared` to distinguish if the setting is per project or global.

- Define a type to be either array, integer, string, boolean or float.

- Define the strategy to be used when setting the value. The allowed values are: `merge`, `overwrite`.

- Define `init` as boolean. `true` means that this value is initially requested from the user.

## Placeholder

A placeholder must meet the following conditions:

- The placeholder's name must start and end with `%`. For example, `%some_placeholder%`.
- Define `optional: true/false` to indicate if the placeholders needs to be resolved to run the task.
- Use the `id` or a full qualified class name of an existing `ValueResolver` for the `valueResolver` field.

## Console command
A console command must meet the following conditions:

- Only basic input validation and output formatting __SHOULD__ present in the console command.
- `protected static $defaultName` __SHOULD NOT__ be used because of performance reasons and future deprecation in Symfony 6.1 version.
  Instead `protected const NAME` __SHOULD__ be provided and passed to the parent constructor as a parameter.

## Naming conventions

There are the following conventions for naming entities:

- Folder name: Must be in a singular form. For example, `Event` and not `Events`.

- Class name: Must be in a singular form. For example, `AcmeTask` and not `AcmeTasks`.

- Method name: [Core convention](https://spryker.atlassian.net/wiki/spaces/CORE/pages/497156313/Common+Conventions#CommonConventions-Namingofmethods) must be followed.

- Variable name:[Core convention](https://spryker.atlassian.net/wiki/spaces/CORE/pages/497156313/Common+Conventions#CommonConventions-Namingofvariables) must be followed.

## Contracts

A contract is an interface that allows users to customize the existing business logic.
Contracts must meet the following conditions:

- A contract must exist only in case if the existing logic provides for an extension by the user.
- Contract is a public API and must follow [Spryker plugin interfaces specification](https://spryker.atlassian.net/wiki/spaces/RFC/pages/1038092073/INTEGRATED+RFC+Plugin+interface+specification).

## REST API
- Controller __SHOULD__ have no business logic.
- Controller __MUST__ have only one action method `__invoke`.
- Controller __MUST__ be placed in namespace `SprykerSdk\Sdk\Presentation\RestApi\Controller\v1`. The `v1` is current version of SDK API.
- Route __MUST__ be placed in `src/Presentation/RestApi/Resources/routing.yaml`
- Route __SHOULD__ be named by template `api_${version}_${controller_name}`.
  Example: For action `ValidatePhpCodestyleController::__invoke` route is `api_v1_validation_php_codestyle`
- Route's path __SHOULD__ be named in hyphen-case. For task `validation:php:codestyle` path is `/api/v1/validation-php-codestyle`

## TODO

- .env usage.
- Enums.
