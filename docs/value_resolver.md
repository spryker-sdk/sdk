# Spryker SDK value resolvers

| Value Resolver Name | Description                                                                                          |
|---------------------|------------------------------------------------------------------------------------------------------|
| APP_PHP_VERSION | Uses for resolving php version (7.4, 8.0).                                                           |
| APP_TYPE | Uses for resolving repository for creating project.                                                  |
| ARRAY_OPTION | Uses for multi-options.                                                                              |
| B2BC_TYPE  | Uses for resolving repository for public b2b,b2c.                                                    |
| CONFIG_PATH  | Uses to resolve relative path by priority: `project` path then by default `sdk` path if it's exists. |
| FLAG  | Uses for flag options, has boolean type.                                                             |
| NAMESPACE  | Uses for spryk tool to resolve namespcases for tool. Based on settings.                              |
| PC_SYSTEM  | Uses for resolveing OS. Linux, Mac, Mac (ARM).                                                       |
| PRIORITY_PATH  | Uses to resolve relative path for tool entrypoint: `project` path then by if exist `sdk` path.       |
| REPORT_DIR  | Uses for resolving report file path.                                                                 |
| SDK_DIR  | Uses for resolve path to sdk folder.                                                                 |
| CORE  | Uses for resolving core level. Based on resolved values from `NAMESPACE` resolver.                   |
| STATIC  | Uses as universal value resolver with additional settings.                                           |

#### Placeholder with `STATIC` value resolver with configuration. See [conventions](conventions.md#Placeholder):
```yaml
  - name: "%shortcode_name%" # For replacing in command.
    value_resolver: STATIC # Value resolver name.
    optional: true # Can be optional or required.
    configuration: # Configuration for value resolver instance.
      option: "report" # The name for tool option if it's needed Example: --report=. Optional setting.
      alias: 'report' # The name for sdk option command to provide the value to the tool. Can be closed for coming. Optional setting.
      description: "Report Type format"
      type: string # Type of value. Support `string`, `int`, `boolean`, `array`.
      defaultValue: 'json' # The default value. Optional setting.
```
