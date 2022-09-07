# Task set

It might be suitable to group related tasks up and run them by one command.
To achieve that you must create the task set yaml configuration or implement `\SprykerSdk\SdkContracts\Entity\TaskSetInterface`.

### Overriding

In tasks set you can redeclare sub-tasks `stop_on_error`, `tags` and override placeholders behavior.

### Configuring sub-task placeholders

After the grouping tasks into the task set you can face that placeholder from one task is used in another task or duplicated several times.
Such behaviour caused by placeholder's naming collision in task set. To resolve it you can redefine the placeholder(fully or partially) in task `placeholder_overrides` section.
To share the same placeholder between tasks you must declare it in `shared_placeholders` section also you can amend the description there.
This mapping also available for php tasks sets in `TaskSetInterface`.

```yaml
id: "sdk:task:set"
short_description: "Create ACP AsyncAPI file with message"
help: ~
stage: build
version: 1.0.0
command: ~
type: task_set
tasks:
  - id: "task:one"
    stop_on_error: true
    tags: ['tag_one', 'tag_two']
    placeholder_overrides:
        '%config%':
            name: "%config_one%"
            value_resolver: STATIC_TEXT
            optional: true
            configuration:
                name: "report_dir"
                description: "Reports directory"
                type: string
                settingPaths: ["report_dir"]


  - id: "task:two"
    stop_on_error: false
placeholders: []
shared_placeholders:
    '%sdk_dir%': ~
    '%config%':
      description: 'Some description'
```
