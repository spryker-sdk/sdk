<?php

/**
 * Copyright © 2019-present Spryker Systems GmbH. All rights reserved.
 * Use of this software requires acceptance of the Evaluation License Agreement. See LICENSE file.
 */

namespace SprykerSdk\Sdk\Core\Application\Service;

use SprykerSdk\SdkContracts\Workflow\TransitionResolverInterface;

class WorkflowTransitionResolverRegistry
{
    /**
     * @var iterable<\SprykerSdk\SdkContracts\Workflow\TransitionResolverInterface>
     */
    protected iterable $workflowTransitionResolvers;

    /**
     * @var array<string, \SprykerSdk\SdkContracts\Workflow\TransitionResolverInterface>|null
     */
    protected ?array $transitionResolverMap = null;

    /**
     * @param iterable<\SprykerSdk\SdkContracts\Workflow\TransitionResolverInterface> $workflowTransitionResolvers
     */
    public function __construct(iterable $workflowTransitionResolvers)
    {
        $this->workflowTransitionResolvers = $workflowTransitionResolvers;
    }

    /**
     * @param string $name
     *
     * @return \SprykerSdk\SdkContracts\Workflow\TransitionResolverInterface|null
     */
    public function getTransitionResolverByName(string $name): ?TransitionResolverInterface
    {
        if ($this->transitionResolverMap === null) {
            $this->populateTransitionResolverMap();
        }

        return $this->transitionResolverMap[trim($name)] ?? null;
    }

    /**
     * @return void
     */
    protected function populateTransitionResolverMap(): void
    {
        $this->transitionResolverMap = [];

        foreach ($this->workflowTransitionResolvers as $workflowTransitionResolver) {
            $this->transitionResolverMap[$workflowTransitionResolver->getName()] = $workflowTransitionResolver;
        }
    }
}
