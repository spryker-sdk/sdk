### Integration to PhpStorm Command Line Tools

Within your project run:
```bash
spryker-sdk sdk:php:create-phpstorm-config
```

to generate the custom XML file for PhpStorm Command Line Tools that will make all Spryker SDK task available to PHPStorm.

The xml file that provides the configuration to PHPStorm will be stored in `.idea/commandlinetools/Custom_Spryker_Sdk.xml`.
After the file was generated a restart of PHPStorm will enable the Spryker SDK integration.

All SDK tasks will be available within PHPStorm from the command line tool `(Shift+Cmd+X)` or (press `Ctrl` twice) with a detailed help.
