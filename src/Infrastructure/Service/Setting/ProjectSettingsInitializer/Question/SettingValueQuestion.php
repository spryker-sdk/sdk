<?php

/**
 * Copyright © 2019-present Spryker Systems GmbH. All rights reserved.
 * Use of this software requires acceptance of the Evaluation License Agreement. See LICENSE file.
 */

namespace SprykerSdk\Sdk\Infrastructure\Service\Setting\ProjectSettingsInitializer\Question;

use SprykerSdk\Sdk\Core\Application\Dto\ReceiverValue;
use SprykerSdk\Sdk\Infrastructure\Service\CliValueReceiver\CliValueReceiver;
use SprykerSdk\Sdk\Infrastructure\Service\Setting\SettingChoicesProviderRegistry;
use SprykerSdk\SdkContracts\Entity\SettingInterface;

class SettingValueQuestion
{
    /**
     * @var \SprykerSdk\Sdk\Infrastructure\Service\CliValueReceiver\CliValueReceiver
     */
    protected CliValueReceiver $cliValueReceiver;

    /**
     * @var \SprykerSdk\Sdk\Infrastructure\Service\Setting\SettingChoicesProviderRegistry
     */
    protected SettingChoicesProviderRegistry $settingChoicesProviderRegistry;

    /**
     * @param \SprykerSdk\Sdk\Infrastructure\Service\CliValueReceiver\CliValueReceiver $cliValueReceiver
     * @param \SprykerSdk\Sdk\Infrastructure\Service\Setting\SettingChoicesProviderRegistry $settingChoicesProviderRegistry
     */
    public function __construct(
        CliValueReceiver $cliValueReceiver,
        SettingChoicesProviderRegistry $settingChoicesProviderRegistry
    ) {
        $this->cliValueReceiver = $cliValueReceiver;
        $this->settingChoicesProviderRegistry = $settingChoicesProviderRegistry;
    }

    /**
     * @param \SprykerSdk\SdkContracts\Entity\SettingInterface $setting
     * @param mixed $availableValues
     *
     * @return mixed
     */
    public function ask(SettingInterface $setting, $availableValues)
    {
        $questionDescription = $setting->getInitializationDescription();

        if ($questionDescription === null || $questionDescription === '') {
            $questionDescription = 'Initial value for ' . $setting->getPath();
        }

        $choiceProviderName = $setting->getInitializer() ?? '';

        $choiceValues = $this->settingChoicesProviderRegistry->hasSettingChoicesProvider($choiceProviderName)
            ? $this->settingChoicesProviderRegistry->getSettingChoicesProvider($choiceProviderName)->getChoices($setting)
            : [];

        return $this->cliValueReceiver->receiveValue(
            new ReceiverValue(
                $questionDescription,
                is_array($availableValues) ? array_key_first($availableValues) : $availableValues,
                $setting->getType(),
                $choiceValues,
            ),
        );
    }
}
