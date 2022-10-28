<?php

/**
 * Copyright © 2019-present Spryker Systems GmbH. All rights reserved.
 * Use of this software requires acceptance of the Evaluation License Agreement. See LICENSE file.
 */

namespace SprykerSdk\Sdk\Infrastructure\Mapper;

use SprykerSdk\Sdk\Infrastructure\Entity\Workflow as DomainWorkflow;
use SprykerSdk\SdkContracts\Entity\WorkflowInterface;

interface WorkflowMapperInterface
{
    /**
     * @param \SprykerSdk\SdkContracts\Entity\WorkflowInterface $workflow
     *
     * @return \SprykerSdk\Sdk\Infrastructure\Entity\Workflow
     */
    public function mapDomainWorkflowToInfrastructureWorkflow(WorkflowInterface $workflow): DomainWorkflow;
}
