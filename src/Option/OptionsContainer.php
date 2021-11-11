<?php

namespace Sdk\Option;

class OptionsContainer
{
    /**
     * @var array|null
     */
    protected static $options;

    /**
     * @param array $options
     *
     * @return void
     */
    public static function setOptions(array $options): void
    {
        static::$options = $options;
    }

    /**
     * @param string $name
     *
     * @return bool
     */
    public static function hasOption(string $name): bool
    {
        return static::$options && isset(static::$options[$name]);
    }

    /**
     * @param string $name
     *
     * @throws \RuntimeException
     *
     * @return mixed
     */
    public static function getOption(string $name)
    {
        if (static::$options === null) {
            throw new RuntimeException('Options not loaded');
        }

        return static::$options[$name];
    }

    /**
     * @return void
     */
    public static function clearOptions(): void
    {
        static::$options = null;
    }
}
