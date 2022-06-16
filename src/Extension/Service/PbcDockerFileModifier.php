<?php

/**
 * Copyright © 2019-present Spryker Systems GmbH. All rights reserved.
 * Use of this software requires acceptance of the Evaluation License Agreement. See LICENSE file.
 */

namespace SprykerSdk\SdkTasksBundle\Service;

use Symfony\Component\Yaml\Yaml;

class PbcDockerFileModifier extends AbstractPbcFileModifier
{
    /**
     * @var \Symfony\Component\Yaml\Yaml
     */
    protected Yaml $yaml;

    /**
     * @param \Symfony\Component\Yaml\Yaml $yaml
     */
    public function __construct(Yaml $yaml)
    {
        $this->yaml = $yaml;
    }

    /**
     * @param string $content
     *
     * @return array
     */
    protected function parseContent(string $content): array
    {
        return $this->yaml->parse($content);
    }

    /**
     * @param array $content
     *
     * @return string
     */
    protected function dumpContent(array $content): string
    {
        return $this->yaml->dump($content, 10, 4, Yaml::DUMP_NULL_AS_TILDE);
    }

    /**
     * @return string
     */
    protected function getFileName(): string
    {
        return 'deploy.dev.yml';
    }
}
