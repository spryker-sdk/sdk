<?php

/**
 * Copyright © 2019-present Spryker Systems GmbH. All rights reserved.
 * Use of this software requires acceptance of the Evaluation License Agreement. See LICENSE file.
 */

namespace SprykerSdk\Sdk\Infrastructure\Service\Setting\ProjectSettingsInitializer\Question;

use SprykerSdk\Sdk\Core\Application\Dto\ReceiverValue;
use SprykerSdk\Sdk\Core\Domain\Enum\ValueTypeEnum;
use SprykerSdk\Sdk\Infrastructure\Service\CliValueReceiver\CliValueReceiver;
use SprykerSdk\SdkContracts\Entity\SettingInterface;

class ChangeDefaultValueQuestion
{
    /**
     * @var \SprykerSdk\Sdk\Infrastructure\Service\CliValueReceiver\CliValueReceiver
     */
    protected CliValueReceiver $cliValueReceiver;

    /**
     * @param \SprykerSdk\Sdk\Infrastructure\Service\CliValueReceiver\CliValueReceiver $cliValueReceiver
     */
    public function __construct(CliValueReceiver $cliValueReceiver)
    {
        $this->cliValueReceiver = $cliValueReceiver;
    }

    /**
     * @param \SprykerSdk\SdkContracts\Entity\SettingInterface $setting
     *
     * @return mixed
     */
    public function ask(SettingInterface $setting)
    {
        return $this->cliValueReceiver->receiveValue(
            new ReceiverValue(
                sprintf('Would you like to change the default value for `%s` setting?', $setting->getPath()),
                false,
                ValueTypeEnum::TYPE_BOOLEAN,
            ),
        );
    }
}
