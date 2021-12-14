<?php

/**
 * Copyright © 2019-present Spryker Systems GmbH. All rights reserved.
 * Use of this software requires acceptance of the Evaluation License Agreement. See LICENSE file.
 */

namespace SprykerSdk\Sdk\Contracts\Violation;

use SprykerSdk\Sdk\Contracts\Repository\SettingRepositoryInterface;

abstract class AbstractViolationConverter implements ViolationConverterInterface
{
    /**
     * @var \SprykerSdk\Sdk\Contracts\Repository\SettingRepositoryInterface
     */
    protected SettingRepositoryInterface $settingRepository;

    /**
     * @param \SprykerSdk\Sdk\Contracts\Repository\SettingRepositoryInterface $settingRepository
     */
    public function __construct(SettingRepositoryInterface $settingRepository)
    {
        $this->settingRepository = $settingRepository;
    }

    /**
     * @param array $configuration
     *
     * @return void
     */
    abstract public function configure(array $configuration): void;

    /**
     * @return \SprykerSdk\Sdk\Contracts\Violation\ViolationReportInterface|null
     */
    abstract public function convert(): ?ViolationReportInterface;
}
