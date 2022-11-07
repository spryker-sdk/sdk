<?php

/**
 * Copyright © 2019-present Spryker Systems GmbH. All rights reserved.
 * Use of this software requires acceptance of the Evaluation License Agreement. See LICENSE file.
 */

namespace SprykerSdk\Sdk\Infrastructure\Setting\ProjectSettingsInitializer\Question;

use SprykerSdk\Sdk\Core\Application\Dependency\InteractionProcessorInterface;
use SprykerSdk\Sdk\Core\Application\Dto\ReceiverValue;
use SprykerSdk\Sdk\Infrastructure\Setting\SettingChoicesProviderRegistry;
use SprykerSdk\SdkContracts\Entity\SettingInterface;

class SettingValueQuestion
{
    /**
     * @var \SprykerSdk\Sdk\Core\Application\Dependency\InteractionProcessorInterface
     */
    protected InteractionProcessorInterface $cliValueReceiver;

    /**
     * @var \SprykerSdk\Sdk\Infrastructure\Setting\SettingChoicesProviderRegistry
     */
    protected SettingChoicesProviderRegistry $settingChoicesProviderRegistry;

    /**
     * @param \SprykerSdk\Sdk\Core\Application\Dependency\InteractionProcessorInterface $cliValueReceiver
     * @param \SprykerSdk\Sdk\Infrastructure\Setting\SettingChoicesProviderRegistry $settingChoicesProviderRegistry
     */
    public function __construct(
        InteractionProcessorInterface $cliValueReceiver,
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

        if ($questionDescription === null) {
            $questionDescription = 'Initial value for ' . $setting->getPath();
        }

        $choicesProviderName = $setting->getInitializer();

        $choiceValues = [];

        if ($choicesProviderName !== null && $this->settingChoicesProviderRegistry->hasSettingChoicesProvider($choicesProviderName)) {
            $choiceValues = $this->settingChoicesProviderRegistry->getSettingChoicesProvider($choicesProviderName)->getChoices($setting);
        }

        return $this->cliValueReceiver->receiveValue(
            new ReceiverValue(
                $setting->getPath(),
                $questionDescription,
                is_array($availableValues) ? array_key_first($availableValues) : $availableValues,
                $setting->getType(),
                $choiceValues,
            ),
        );
    }
}
