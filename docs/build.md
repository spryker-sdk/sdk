# Building flavored Spryker SDKs

For some cases the simple extendability and core SDK capabilities might not be enough.
This is especially the case when an extension to the SDK requires additional dependencies or
a deep integration of the SDK.
For those the SDK can be extended by adding additional Symfony bundles to the SDK and
building an own flavored Spryker SDK image.

## Add additional dependencies

Beside extending the Spryker SDK through (Yaml definitions)[./development.md#via-yaml-definition] more complex
extensions can be provided via (PHP implementations)[./development.md#via-php-implementation].
This extension through a PHP implementation need to be added as a composer dependency and registered as Symfony bundle.

#### Download the SDK source code

First you need to download the source to be able to build your own flavored Spryker SDK.

`git clone --depth 1 --branch <tag_name> git@github.com:spryker-sdk/sdk.git`

For testing and development purposes you can run the Spryker SDK in (development mode)[./development.md#run-sdk-in-development-mode].

#### Composer

Additional dependencies can be added via composer.
`composer require <your company>/<your package>`

#### Symfony Bundle registration

Modify `config/bundles.php`:

```diff
$bundles = [
    ...,
    Doctrine\Bundle\MigrationsBundle\DoctrineMigrationsBundle::class => ['all' => true],
+   YourNamespace\YourBundle\YourBundle::class => ['all' => true],
];
```

#### Build your own image

`docker-compose -f docker-compose.yml build --no-cache`

Once the flavored Spryker SDK is build you can execute the same way a non-flavored one will be executed.

#### Private repositories

Adding private repositories requires to add an (auth.json)[https://getcomposer.org/doc/articles/authentication-for-private-packages.md] before building the container.

