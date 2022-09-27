# Task

Task is the smallest unit for running commands in SDK.
It can be used as a single runnable console command or used in more complex structures such as the [workflows](workflow.md) or [task-sets](task_set.md).
In another word task is a commands wrapper that makes them extensible, configurable, versionable and provides cli interface for them.

## How to run task

- `spryker-sdk <task-id>` to run task.
- `spryker-sdk <task-id> --stages=stage_a --stages=stage_b` to run task commands filtered by stages.
- `spryker-sdk <task-id> --tags=tag_a --tags=tag_b` to run task commands filtered by tags.
- `spryker-sdk <task-id> --help` or `spryker-sdk <task-id> -h` to get task help info.

## How to update tasks

`sdk:update:all` - executes the updates of all existing tasks. The [lifecycle events](lifecycle_management.md) will be triggered after.

## How to create task

Task can be created in a declarative way by specifying the task yaml configuration file or by implementing `\SprykerSdk\SdkContracts\Entity\TaskInterface` as a php class.
The declarative is preferred way unless more complex logic is needed.

### Task yaml configuration reference

Configuration file should be placed in `extension/*/config/task/` or `src/Extension/Resources/config/task/` directory.

| Property            | Required | Description                                                                                                                   |
|---------------------|----------|-------------------------------------------------------------------------------------------------------------------------------|
| `id`                | yes      | __Unique__ task id. Should consist only of `[\w\:]+` symbols                                                                  |
| `short_description` | yes      | Task description that's displayed in `Description` section in command help                                                    |
| `version`           | yes      | Task version. Must follow the semver specification                                                                            |
| `type`              | yes      | Tasks type. `local_cli` or `local_cli_interactive` should be used for yaml task declaration and `php` type for php task class |
| `command`           | yes      | An executable command string                                                                                                  |
| `help`              | no       | Help description that's displayed in `Help` section in command help                                                           |
| `stage`             | no       | Task and command stage                                                                                                        |
| `deprecated`        | no       | Defines the task deprecation status                                                                                           |
| `successor`         | no       | Task id that should be used instead if the current one is deprecated                                                          |
| `tags`              | no       | Task command tags                                                                                                             |
| `error_message`     | no       | Default command error message that's used in case of non-zero command code return                                             |
| `placeholders`      | no       | Command placeholders list. [See definition](#placeholders)                                                                    |
| `lifecycle`         | no       | Lifecycle commands list. [See docs](lifecycle_management.md)                                                                  |

#### Placeholders

| Property         | Required | Description                                                                                                                      |
|------------------|----------|----------------------------------------------------------------------------------------------------------------------------------|
| `name`           | yes      | Placeholder name. The same name should be placed in command string for substitution. See [conventions](conventions.md#Placeholder) |
| `value_resolver` | yes      | Value resolver classname or name. Is used for fetching and processing command values                                             |
| `optional`       | no       | Defines the placeholder optional or not                                                                                          |
| `configuration`  | no       | Value resolver configuration. Depends on particular value resolver                                                               |

Checkout the [conventions](conventions.md#Task) for additional info.

```yaml
id: 'hello:world'
short_description: 'Sends greetings'
help: 'Will greet the one using it'
stage: hello
version: 1.0.0
deprecated: false
successor: 'hello:php'
command: '/bin/echo "hello %world% %somebody%"'
type: local_cli
tags: ['hello', 'world']
placeholders:
  - name: '%world%'
    value_resolver: SprykerSdk\Sdk\Extension\ValueResolver\StaticValueResolver
    optional: false
    configuration:
      name: 'world'
      description: 'what is the world?'
      defaultValue: 'World'
  - name: '%somebody%'
    value_resolver: STATIC
    optional: false
    configuration:
      name: 'somebody'
      description: 'Who is somebody'
lifecycle:
  INITIALIZED:
      commands:
        - command: echo "hello world"
          type: local_cli
      files: ~
      placeholders: ~
  UPDATED:
      commands:
        - command: echo "hello world"
          type: local_cli
      files: ~
      placeholders: ~
  REMOVED:
    commands:
      - command: echo "hello world"
        type: local_cli
    files: ~
    placeholders: ~
```

### How to use task in the workflows

Tasks can be used in workflow transitions.
Such tasks must be defined in workflow configuration in `transitions.<transition>.metadata.task` path.
For more information read the [docs](workflow.md).

```yaml
  transitions:
    CreateAppSkeleton:
        from: start
        to: app-skeleton
        metadata:
          task: generate:php:app
```
