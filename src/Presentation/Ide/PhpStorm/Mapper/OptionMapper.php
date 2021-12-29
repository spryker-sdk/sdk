<?php

/**
 * Copyright © 2019-present Spryker Systems GmbH. All rights reserved.
 * Use of this software requires acceptance of the Evaluation License Agreement. See LICENSE file.
 */

namespace SprykerSdk\Sdk\Presentation\Ide\PhpStorm\Mapper;

use SprykerSdk\Sdk\Presentation\Ide\PhpStorm\Dto\Option;
use SprykerSdk\Sdk\Presentation\Ide\PhpStorm\Dto\OptionInterface;
use Symfony\Component\Console\Input\InputOption;

class OptionMapper implements OptionMapperInterface
{
    /**
     * @param \Symfony\Component\Console\Input\InputOption $inputOption
     *
     * @return \SprykerSdk\Sdk\Presentation\Ide\PhpStorm\Dto\OptionInterface
     */
    public function mapToIdeOption(InputOption $inputOption): OptionInterface
    {
        return new Option(
            $inputOption->getName(),
            $inputOption->getShortcut(),
            $inputOption->getDescription(),
        );
    }
}
