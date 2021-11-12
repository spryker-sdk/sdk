<?php

namespace Sdk\Setting;

use Sdk\Style\StyleInterface;

interface SettingInterface
{
    /**
     * @param string $name
     * @param bool $require
     *
     * @throws \Sdk\Exception\SettingNotFoundException
     * @return mixed
     */
    public function getSetting(string $name, bool $require = true);

    /**
     * @return array
     */
    public function getRequiredSettings(): array;

    /**
     * @param array $settings
     * @param StyleInterface $style
     *
     * @return void
     */
    public function setSettings(array $settings, StyleInterface $style): void;
}
