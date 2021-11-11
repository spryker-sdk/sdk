<?php

namespace Sdk\Setting;

use Sdk\Style\StyleInterface;

interface SettingInterface
{
    /**
     * @param string $name
     *
     * @throws \Sdk\Exception\SettingNotFoundException
     * @return mixed
     */
    public function getSetting(string $name);

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
