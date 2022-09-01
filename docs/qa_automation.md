## QA Automation

Qa automation is a feature that gives an ability to run QA tools such as,
code style checks, static analysis and unit tests required by the Spryker.

### How to enable

It enables on the project initialization step as an additional setting. Just run `spryker-sdk sdk:init:project`
and pick steps that you want to be enabled from the list. If none picked the [default list](https://github.com/spryker-sdk/sdk/blob/d6cac0ec997ea3ef067f8af07b8b375f96632a4f/src/Extension/Resources/config/setting/settings.yaml) (check `settings.qa_tasks.values`) will be taken.
After project initialization qa automation tasks will be saved into `.ssdk/setting` file.

Result example
```yaml
qa_tasks:
    - 'validation:php:rector'
    - 'validation:php:codestyle-check'
    - 'validation:php:static'
```

### How to use

Simply run `spryker-sdk sdk:qa:run` to execute all configured qa automation tasks.

If you need to customize a list of executable qa automation tasks change `qa_tasks` block into the `.ssdk/setting` file.
If you want to add custom qa task, your task id name should have the following pattern `validation:*`.

### Tests execution

Qa automation can execute unit tests, but only in case it doesn't require project environment configuration. Otherwise,
the result might be unpredictable.
