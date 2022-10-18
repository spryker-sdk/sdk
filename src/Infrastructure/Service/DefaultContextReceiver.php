<?php

/**
 * Copyright © 2019-present Spryker Systems GmbH. All rights reserved.
 * Use of this software requires acceptance of the Evaluation License Agreement. See LICENSE file.
 */

namespace SprykerSdk\Sdk\Infrastructure\Service;

use SprykerSdk\Sdk\Core\Application\Dependency\DefaultContextReceiverInterface;
use SprykerSdk\Sdk\Core\Application\Dependency\SettingFetcherInterface;

class DefaultContextReceiver implements DefaultContextReceiverInterface
{
    /**
     * @var \SprykerSdk\Sdk\Core\Application\Dependency\SettingFetcherInterface
     */
    protected SettingFetcherInterface $settingFetcher;

    /**
     * @param \SprykerSdk\Sdk\Core\Application\Dependency\SettingFetcherInterface $settingFetcher
     */
    public function __construct(SettingFetcherInterface $settingFetcher)
    {
        $this->settingFetcher = $settingFetcher;
    }

    /**
     * @return string
     */
    public function getFormat(): string
    {
        return $this->settingFetcher->getOneByPath('default_violation_output_format')->getValues();
    }
}
