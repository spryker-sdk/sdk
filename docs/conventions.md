# Conventions

The Spryker SDK defines the following conventions.

## Task

- MUST have an id following the schema `<group>:<language>:<subgroup>`, where `<group>` is one of validation, generation, `<language>` is php for now  and `<subgroup>` should be a descriptive name. E.g.: `validation:php:architecture`

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
