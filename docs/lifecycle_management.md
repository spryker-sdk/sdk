# Task lifecycle management

A task of the SDK may change over time. It may need to update the tool it wraps or get replaced by a successor task.
Therefore, each task can subscribe to certain lifecycle events to react when ever the SDK is updated, the task is initialized
or removed.

To be able to emit those lifecycle events to the specific task the task needs to subscribe to them and needs to be versioned.

## Lifecycle events

### Subscribing to lifecycle events

A task can define a list of `commands` and `files` for each of the lifecycle events that will be executed and created
when the event is emitted.
`commands` follow the same structure as the `command` of a task itself and can have `placeholders` for the dynamic parts of the
`command`.
`files` only define a path and the `content` that should be put into the defined file. Using `placeholders` for dynamic
parts of the `files.path` or the `files.content` is possible.

### Event types

#### INITIALIZED

Will be emitted when the Task is initialized inside a project for the first time.
This is the right place to create task specific configuration and initialize the tool.

#### UPDATED

Will be emitted when the SDK was updated and the task version has changed, so the Task can update configurations and tools it needs to run.

#### REMOVED

Will be emitted after the task was removed from the SDK, can be used to perform cleanups of the task, like removing configuration files.

## via YAML

```yaml
---
id: string #e.g.: validation:php:codestile-fix
version: 1.0.0
deprecated: false #if false it can be omitted
successor: string|null # if null it can be omitted, e.g.: validation:php:codestyle-fix
# ... other task properties
lifecycle:
  INITIALIZED:
    commands:
      - '%vendor_dir%/bin/composer require --dev "spryker/code-sniffer: dev-master"'
    files:
      - path: path # e.g.: '%project_dir%/.cs_config' # does not really exist, only for the example
        content: string # e.g.: "serverity: 3"
    placeholders:
      - name: string # e.g.: '%project_dir%'
        valueResolver: string #e.g.: PROJECT_DIR mapping to a ValueResolver
  UPDATED: ~ # if event does not define anything it can be omitted
  REMOVED: #same format as INITIALIZED
```

## via PHP

A task implemented in PHP only needs to implement the [TaskLifecycleInterface](https://github.com/spryker-sdk/sdk-contracts/blob/master/src/Entity/Lifecycle/TaskLifecycleInterface.php) to subscribe to the lifecycle events.
