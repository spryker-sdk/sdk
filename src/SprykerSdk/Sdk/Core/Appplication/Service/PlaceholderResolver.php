<?php

/**
 * Copyright © 2016-present Spryker Systems GmbH. All rights reserved.
 * Use of this software requires acceptance of the Evaluation License Agreement. See LICENSE file.
 */

namespace SprykerSdk\Sdk\Core\Appplication\Service;

use SprykerSdk\Sdk\Core\Domain\Entity\Placeholder;
use SprykerSdk\Sdk\Core\Domain\Repository\SettingRepositoryInterface;

class PlaceholderResolver
{
    /**
     * @param \SprykerSdk\Sdk\Core\Domain\Repository\SettingRepositoryInterface $settingRepository
     * @param array<\SprykerSdk\Sdk\Core\Appplication\Port\ValueResolverInterface> $valueResolver
     */
    public function __construct(
        protected SettingRepositoryInterface $settingRepository,
        protected array $valueResolver
    ) {
    }

    /**
     * @param \SprykerSdk\Sdk\Core\Domain\Entity\Placeholder $placeholder
     *
     * @return mixed
     */
    public function resolve(Placeholder $placeholder): mixed
    {
        foreach ($this->valueResolver as $valueResolver) {
            if ($valueResolver->getId() === $placeholder->valueResolver || $valueResolver::class === $placeholder->valueResolver) {
                $settingValues = [];

                foreach ($valueResolver->getSettingPaths() as $settingPath) {
                    $settingValues[$settingPath] = $this->settingRepository->findByPath($settingPath);
                }

                return $valueResolver->getValue($settingValues);
            }
        }

        //@todo throw exception -> Placeholder not resolvable
    }
}