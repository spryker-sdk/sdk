<?php

/**
 * Copyright © 2019-present Spryker Systems GmbH. All rights reserved.
 * Use of this software requires acceptance of the Evaluation License Agreement. See LICENSE file.
 */

namespace SprykerSdk\Sdk\Presentation\Ide\PhpStorm\Formatter;

use SprykerSdk\Sdk\Presentation\Ide\PhpStorm\Dto\CommandInterface;

class CommandXmlFormatter implements CommandXmlFormatterInterface
{
    /**
     * @param \SprykerSdk\Sdk\Presentation\Ide\PhpStorm\Dto\CommandInterface $ideCommand
     *
     * @return array
     */
    public function format(CommandInterface $ideCommand): array
    {
        return [
            'name' => $ideCommand->getName(),
            'help' => $ideCommand->getHelp(),
            'params' => $this->formatParams($ideCommand->getParams()),
            'optionsBefore' => $this->formatOptionsBefore($ideCommand->getOptionsBefore()),
        ];
    }

    /**
     * @param array<\SprykerSdk\Sdk\Presentation\Ide\PhpStorm\Dto\ParamInterface> $params
     *
     * @return array<array>
     */
    protected function formatParams(array $params): array
    {
        $xmlParams = [];

        foreach ($params as $param) {
            $xmlParams[] = [
                'name' => $param->getName(),
                'default' => $param->getDefault(),
            ];
        }

        return $xmlParams;
    }

    /**
     * @param array<\SprykerSdk\Sdk\Presentation\Ide\PhpStorm\Dto\OptionInterface> $optionsBefore
     *
     * @return array
     */
    protected function formatOptionsBefore(array $optionsBefore): array
    {
        $xmlOptionsBefore = [];

        foreach ($optionsBefore as $optionBefore) {
            $xmlOptionsBefore['option'][] = [
                '@name' => $optionBefore->getName(),
                '@shortcut' => (string)$optionBefore->getShortcut(),
                'help' => $optionBefore->getHelp(),
            ];
        }

        return $xmlOptionsBefore;
    }
}
