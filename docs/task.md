# Task

A *task* is the smallest unit for running commands in the SDK.
You can use the task as a single runnable console command, or you can use it in more complex structures such as [workflows](workflow.md) or [task_sets](task_set.md).
In other words, a task is a commands wrapper that makes the commands extensible, configurable, versionable, and provides a CLI interface for them.

## How to run a task

Use the following commands for tasks:

- To run a task: `spryker-sdk <task-id>`
- To run task commands filtered by stages: `spryker-sdk <task-id> --stages=stage_a --stages=stage_b` 
- To run task commands filtered by tags: `spryker-sdk <task-id> --tags=tag_a --tags=tag_b` 
- To get task help info: `spryker-sdk <task-id> --help` or `spryker-sdk <task-id> -h`.

## How to create a task

Tasks can be created in a declarative way by specifying the task YAML configuration file or by implementing `\SprykerSdk\SdkContracts\Entity\TaskInterface` as a PHP class.
Whereas the declarative is the preferred way unless a more complex logic is needed.

### Task YAML configuration file

The task configuration file should be placed to the `extension/*/config/task/` or `src/Extension/Resources/config/task/` directory. 

Example of the YAML configuration file: 

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
The table below describes the configuration file's properties.

| Property            | Required | Description                                                                                                                   |
|---------------------|----------|-------------------------------------------------------------------------------------------------------------------------------|
| `id`                | yes      | __Unique__ task id. It should consist only of `[\w\:]+` symbols.                                                                  |
| `short_description` | yes      | Task description that is displayed in the `Description` section in command help.                                                    |
| `version`           | yes      | Task version. The version format must comply with the semver specification.                                                                            |
| `type`              | yes      | Tasks type. `local_cli` or `local_cli_interactive` must be used for YAML task declaration and `php` type for a PHP task class |
| `command`           | yes      | An executable command. string                                                                                                  |
| `help`              | no       | Help description that is displayed in the *Help* section of the command help.                                                           |
| `stage`             | no       | Task and command stage.                                                                                                        |
| `deprecated`        | no       | Defines the task deprecation status.                                                                                           |
| `successor`         | no       | Task ID that should be used if the current one is deprecated.                                                          |
| `tags`              | no       | Task command tags.                                                                                                             |
| `error_message`     | no       | Default command error message that is used in case of non-zero command code return.                                             |
| `placeholders`      | no       | Command [placeholders](#placeholders)   list.                                                                  |
| `lifecycle`         | no       | Lifecycle commands list. See [Task lifecycle management](lifecycle_management.md) for details about the lifecycle.                                                                  |

#### Placeholders

The *placeholders* attribute of the task configuration file has the following properties:

| Property         | Required | Description                                                                                                                      |
|------------------|----------|----------------------------------------------------------------------------------------------------------------------------------|
| `name`           | yes      | The placeholder name. The same name should be placed in the command string for substitution. See [conventions](conventions.md#Placeholder) |
| `value_resolver` | yes      | Value resolver class name or name. It is used for fetching and processing command values.                                             |
| `optional`       | no       | Defines if the placeholder is optional or not.                                                                                          |
| `configuration`  | no       | Value resolver configuration. Depends on the particular value resolver.                                                               |

Check out the [conventions](conventions.md#Task) for additional information.

## How to update tasks

To update all of the existing tasks, run the following command: 

```bash
sdk:update:all
```
After you run this command, the [lifecycle events](lifecycle_management.md) are triggered.

## How to use tasks in the workflows

You can use tasks in workflow transitions.
Such tasks must be defined in workflow configuration at `transitions.<transition>.metadata.task`.
For more information on the workflows, see [Workflow](workflow.md).

```yaml
  transitions:
    CreateAppSkeleton:
        from: start
        to: app-skeleton
        metadata:
          task: generate:php:app
```
