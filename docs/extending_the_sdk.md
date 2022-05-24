# SDK development

The SDK offers different extension points to enable 3rd parties to contribute to the SDK without needing to modify it.

From simple to complex the SDK can be extended by:

1. providing additional tasks or settings via YAML definition placed inside `<path/to/spryker/sdk>/extension/<YourBundleName>/Tasks/<taskname>.yaml`. Those tasks can't introduce additional dependencies and therefore are best suited to integrate existing tools that come with a standalone executable.
2. providing additional tasks, value resolvers or settings via PHP implementation, placed inside `<path/to/spryker/sdk>/extension/<YourBundleName>/Tasks/<taskname>.php`. Those tasks need to implement the [TaskInterface](https://github.com/spryker-sdk/sdk-contracts/blob/master/src/Entity/TaskInterface.php) and need to be exposed by providing a Symfony bundle to the Spryker SDK (e.g.: `<path/to/spryker/sdk>/extension/<YourBundleName>/<YourBundleName>Bundle.php`), following the conventions of a [Symfony bundle](https://symfony.com/doc/current/bundles.html#creating-a-bundle). This possibility is best suited for more complex tasks that do not require additional dependencies (e.g.: validating the content of a yaml file by using Symphony validators)
3. Providing additional tasks, value resolver or settings that come with additional dependencies follows the same guideline as 2. but requires to build your own [SDK docker image](./build.md) that includes those dependencies.

## Implementing a new task

A task is essentially the execution of a very specific function.
This could be for example executing an external tool through a CLI call.

There are two possibilities to define a new task. Based on YAML for simple task definitions and
implementation via PHP and Symfony services for specialized purposes.

### via YAML definition

YAML based tasks need to fulfill a defined structure to be able to execute them from the SDK.
The command defined in the YAML definition can have placeholders, that need to be defined in the placeholder
section. Each placeholder need to map to one value resolver.

#### Definition

Add a definition for your task `<path>/Tasks/<name>.yaml`.

````yaml
---
id: string #e.g.: validation:code
short_description: string #e.g.: Fix code style violations
help: string|null #e.g: Fix codestyle violations, lorem ipsum, etc.
stage: string #e.g.: build
command: string #e.g.: php %project_dir%/vendor/bin/phpcs -f --standard=%project_dir%/vendor/spryker/code-sniffer/Spryker/ruleset.xml %module_dir%
type: string #e.g.: local_cli
placeholders:
- name: string #e.g.: %project_dir%
  valueResolver: string #e.g.: PROJECT_DIR, mapping to a ValueResolver with id PROJECT_DIR or  a FQCN
  optional: bool
````

#### Adding a tasks to the SDK

Tasks that located in `extension/<your extension name>/Tasks` can be added to the SDK by executing `spryker-sdk sdk:update:all`

### via PHP implementation

For cases when a __Task__ is more than just a call to an existing tool a __Task__ can be implemented as PHP class
and registered using the Symfony service tagging feature.
This requires to provide the __Task__ as part of a symfony bundle.

#### Implement the bundle
Please refer to https://symfony.com/doc/current/bundles.html for information on creating a Symfony bundle.
Your bundle needs to use the https://github.com/spryker-sdk/sdk-contracts via composer.

#### Implement __Task__

```php
namespace <YourNamespace>\Tasks;

use SprykerSdk\Sdk\Contracts\Entity\TaskInterface;
use <YourNamespace>\Tasks\Commands\YourCommand;

class YourTask implements TaskInterface
{
    /**
     * @return string
     */
    public function getShortDescription(): string {}

    /**
     * @return array<\SprykerSdk\Sdk\Contracts\Entity\PlaceholderInterface>
     */
    public function getPlaceholders(): array {}

    /**
     * @return string|null
     */
    public function getHelp(): ?string {}


    public function getId(): string {}

    /**
     * @return array<\SprykerSdk\Sdk\Contracts\Entity\CommandInterface>
     */
    public function getCommands(): array
    {
        return [
            new YourCommand(),
        ];
    }

}
```

#### Implement __Command__

While a __Task__ definition serves as general description of the __Task__ and maps __Placeholders__ to __ValueResolvers__,
a __Command__ serves as function that is executed along with the resolved __Placeholders__.

```php
namespace <YourNamespace>\Tasks\Commands;

use SprykerSdk\Sdk\Contracts\Entity\ExecutableCommandInterface;

class YourCommand implements ExecutableCommandInterface
{
    /**
     * @return string
     */
    public function getCommand(): string
    {
        return static::class;
    }

    /**
     * @return string
     */
    public function getType(): string
    {
        //use 'php' to execute command inside the SDK
        return 'php';
    }

    /**
     * @return bool
     */
    public function hasStopOnError(): bool
    {
        return true;
    }

    /**
     * @param array<string, mixed> $resolvedValues
     *
     * @return int
     */
    public function execute(array $resolvedValues): int
    {
        //your implementation of the command
        //$resolvedValues will be array<placeholder.name, value>
        //return 0 for success and any non 0 integer up to 255 for failed
        return 0;
    }
}
```

#### Implement Placeholders

Placeholders will be resolved at runtime by using a specified __ValueResolver__.
A __Placeholder__ need to have a specific name that is not used anywhere in the Commands the __Placeholder__ is used for.
For example `%` can be appended and suffixed for this purpose and will make the __Placeholder__ easier to recognize in a __Command__.
The used __ValueResolver__ can be referenced by his id or by his full qualified class name (FQCN), where the FQCN should be preferred.

```php
namespace <YourNamespace>\Tasks;

use SprykerSdk\Sdk\Core\Domain\Entity\Placeholder;
use SprykerSdk\Sdk\Contracts\Entity\TaskInterface;
use <YourNamespace>\ValueResolvers\YourValueResolver;

class YourTask implements TaskInterface
{
    /**
     * @return array<\SprykerSdk\Sdk\Contracts\Entity\PlaceholderInterface>
     */
    public function getPlaceholders(): array
    {
        return [
            new Placeholder('%some_placeholder%', YourValueResolver::class, [], false),
        ];
    }
}
```

#### Implement Symfony service

Once the __Task__ is implemented it needs to be registered as a Symfony service.
You can find a more extensive documentation on registering a Symfony service at https://symfony.com/doc/current/service_container.html#creating-configuring-services-in-the-container.

```yaml
services:
  your_task:
    class: <YourNamespace>\Tasks\YourTask
    tags: ['sdk.task']
```

#### Register your bundle

If your bundle does not have different dependencies than the Spryker SDK itself you don't need to register the bundle at
but just need to place it into the `extension` directory that is part of your SDK installation.

For more complex bundles, that for example require additional dependencies you need to follow the guide to [build a flavored Spryker SDK](build.md).

## Add a value resolver

Most __Placeholders__ will need an implementation to resolve their value during runtime.
This can be reading some settings and assembling a value based on their content or any implementation
that turns a placeholder into a resolved value.
The purpose is to unify __ValueResolvers__ and always use the same name for a value.

```php
namespace <YourNamespace>\ValueResolvers;

use SprykerSdk\Sdk\Contracts\ValueResolver\ValueResolverInterface;

class YourValueResolver implements ValueResolverInterface
{
    /**
     * @return string
     */
    public function getId(): string
    {
        //ValueResolver can be referenced by YOUR_ID instead of the FQCN
        return 'YOUR_ID';
    }

    public function getDescription(): string
    {
        //will be shown when `spryker-sdk <task> -h` is called for each parameter
        return 'description';
    }

    public function getSettingPaths(): array
    {
        //ensures some_setting_path is read from settings and the respective value is passed
        //into getValue(['some_setting_path' => <value>])
        return [
            'some_setting_path',
        ];
    }

    public function getType(): string
    {
        //any php type
        return 'string';
    }

    public function getAlias(): ?string
    {
        //used to give an alias for overwriting the value via CLI `spryker-sdk <task> --some-alias=<value>`
        return 'some-alias';
    }

    /**
     * @param array<string, mixed> $settingValues
     * @return mixed
     */
    public function getValue(array $settingValues): mixed
    {
        //implementation to resolve the according value
        //when null is returned the user of the SDK is asked to give his input
        return '<resolved value>'
    }

    /**
     * @return mixed
     */
    public function getDefaultValue(): mixed
    {
        //Used to set the default value for the CLI option
        return null;
    }
}
```

A __ValueResolver__ can be defined as a Symfony service, for example to be able to inject services into it.
When the __ValueResolver__ is not defined as a service it will be instantiated by his FQCN.

```yaml
services:
  your_value_resolver:
    class: <YourNamespace>\ValueResolvers\YourValueResolver
    tags: ['sdk.value_resolver']
```

## Add a setting

A bundle might add additional __Settings__ that can be used by __ValueResolvers__ to create a persistent behavior.
Settings are defined in yaml file called `settings.yaml` and will be added to the SDK by calling
`spryker-sdk setting:set setting_dirs <path to your settings>`

```yaml
settings:
  - path: string #e.g.: some_setting
    initialization_description: string #Will be used when a user is asked to provide the setting value
    strategy: string #merge or overwrite, where merge will add the value to the list and overwrite will replace it
    init: bool #if the user should be asked for the setting value when `spryker-sdk sdk:init:sdk` or `spryker-sdk sdk:init:project` is called
    type: string #Use array for lists of values or any scalar type (string|integer|float|boolean)
    is_project: boolean #defines if the setting is persisted across projects and initialized during `spryker-sdk sdk:init:sdk` or per project and initialized with `spryker-sdk sdk:init:project`
    values: array|string|integer|float|boolean #serve as default values for initialization
```

## Adding a new command runner

__Commands__ are executed using command runners. Each command has a `type` that will be used to determine what command runner
is capable of executing the given __Command__.
To implement new __Task__ types a new command runner is required and needs to be registered as Symfony service.

```php
namespace <YourNamespace>\CommandRunners;

use SprykerSdk\Sdk\Contracts\CommandRunner\CommandRunnerInterface;

class YourTypeCommandRunner implements CommandRunnerInterface
{
    /**
     * @param CommandInterface $command
     *
     * @return bool
     */
    public function canHandle(CommandInterface $command): bool
    {
        return $command->getType() === 'your_command_type';
    }

    /**
     * @param CommandInterface $command
     * @param array<string, mixed> $resolvedValues
     *
     * @return int
     */
    public function execute(CommandInterface $command, array $resolvedValues): int
    {
        //own implementation on how to execute a command

        //MUST return 0 for success and 1-255 for failure
        return 0;
    }
}
```

```yaml
  your_type_command_runner:
    class: <YourNamespace>\CommandRunners\YourTypeCommandRunner
    tags: ['command.runner']
```

In addition, existing command runners can be overwritten
with better suitable implementation.

```yaml
  local_cli_command_runner:
    class: <YourNamespace>\CommandRunners\BetterLocalCliRunner
```
