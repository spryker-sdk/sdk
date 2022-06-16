<?php

/**
 * Copyright © 2019-present Spryker Systems GmbH. All rights reserved.
 * Use of this software requires acceptance of the Evaluation License Agreement. See LICENSE file.
 */

namespace SprykerSdk\SdkTasksBundle\Service;

class PbcComposerFileModifier extends AbstractPbcFileModifier
{
    /**
     * @return string
     */
    protected function getFileName(): string
    {
        return 'composer.json';
    }

    /**
     * @param string $content
     *
     * @return array
     */
    protected function parseContent(string $content): array
    {
        return json_decode($content, true);
    }

    /**
     * @param array $content
     *
     * @return string
     */
    protected function dumpContent(array $content): string
    {
        return json_encode($content, JSON_PRETTY_PRINT | JSON_UNESCAPED_SLASHES | JSON_THROW_ON_ERROR);
    }
}
