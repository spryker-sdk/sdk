<?php

namespace SprykerSdk\Sdk\Extension\Service;

use SprykerSdk\Sdk\Extension\Exception\FileNotFoundException;
use SprykerSdk\SdkContracts\Entity\ContextInterface;
use Symfony\Component\Yaml\Yaml;

class PbcDockerFileModifier extends AbstractPbcFileModifier
{
    private Yaml $yaml;

    /**
     * @param Yaml $yaml
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
