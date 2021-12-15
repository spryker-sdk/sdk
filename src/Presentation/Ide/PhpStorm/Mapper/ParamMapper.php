<?php

/**
 * Copyright © 2019-present Spryker Systems GmbH. All rights reserved.
 * Use of this software requires acceptance of the Evaluation License Agreement. See LICENSE file.
 */

namespace SprykerSdk\Sdk\Presentation\Ide\PhpStorm\Mapper;

use SprykerSdk\Sdk\Presentation\Ide\PhpStorm\Dto\Param;
use SprykerSdk\Sdk\Presentation\Ide\PhpStorm\Dto\ParamInterface;
use Symfony\Component\Console\Input\InputArgument;

class ParamMapper implements ParamMapperInterface
{
    /**
     * @param \Symfony\Component\Console\Input\InputArgument $inputArgument
     *
     * @return \SprykerSdk\Sdk\Presentation\Ide\PhpStorm\Dto\ParamInterface
     */
    public function mapToIdeParam(InputArgument $inputArgument): ParamInterface
    {
        return new Param(
            $inputArgument->getName(),
            $inputArgument->getDefault(),
        );
    }
}
