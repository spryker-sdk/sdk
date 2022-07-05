<?php

/**
 * Copyright © 2019-present Spryker Systems GmbH. All rights reserved.
 * Use of this software requires acceptance of the Evaluation License Agreement. See LICENSE file.
 */

namespace SprykerSdk\Sdk\Extension\Workflow;

use SprykerSdk\SdkContracts\Entity\ContextInterface;
use SprykerSdk\SdkContracts\Workflow\TransitionResolverInterface;

class TransitionBooleanResolver implements TransitionResolverInterface
{
    /**
     * @var string
     */
    public const SUCCESSFUL = 'successful';

    /**
     * @var string
     */
    public const FAILED = 'failed';

    /**
     * @param \SprykerSdk\SdkContracts\Entity\ContextInterface $context
     * @param array $settings
     *
     * @return string|null
     */
    public function resolveTransition(ContextInterface $context, array $settings): ?string
    {
        return $settings[$context->getExitCode() !== ContextInterface::SUCCESS_EXIT_CODE ? static::FAILED : static::SUCCESSFUL] ?? null;
    }
}
