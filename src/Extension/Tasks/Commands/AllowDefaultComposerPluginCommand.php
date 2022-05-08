<?php

namespace SprykerSdk\Sdk\Extension\Tasks\Commands;

use SprykerSdk\Sdk\Core\Domain\Entity\Message;
use SprykerSdk\Sdk\Extension\Exception\FileNotFoundException;
use SprykerSdk\Sdk\Extension\Service\PbcFileModifierInterface;
use SprykerSdk\SdkContracts\Entity\ContextInterface;
use SprykerSdk\SdkContracts\Entity\ConverterInterface;
use SprykerSdk\SdkContracts\Entity\ExecutableCommandInterface;
use SprykerSdk\SdkContracts\Entity\MessageInterface;

class AllowDefaultComposerPluginCommand implements ExecutableCommandInterface
{
    private PbcFileModifierInterface $composerFileModifier;

    /**
     * @param PbcFileModifierInterface $composerFileModifier
     */
    public function __construct(PbcFileModifierInterface $composerFileModifier)
    {
        $this->composerFileModifier = $composerFileModifier;
    }

    /**
     * @return string
     */
    public function getCommand(): string
    {
        return '';
    }

    /**
     * @return string
     */
    public function getType(): string
    {
        return 'php';
    }

    /**
     * @return array<string>
     */
    public function getTags(): array
    {
        return [];
    }

    /**
     * @return bool
     */
    public function hasStopOnError(): bool
    {
        return false;
    }

    /**
     * @return ConverterInterface|null
     */
    public function getViolationConverter(): ?ConverterInterface
    {
        return null;
    }

    /**
     * @param ContextInterface $context
     *
     * @return ContextInterface
     */
    public function execute(ContextInterface $context): ContextInterface
    {
        try {
            $composerContent = $this->composerFileModifier->read($context, 'Could not enable default composer plugins');
            $composerContent['config']['allow-plugins']['dealerdirect/phpcodesniffer-composer-installer'] = true;
            $composerContent['config']['allow-plugins']['sllh/composer-versions-check'] = true;
            $this->composerFileModifier->write($composerContent, $context);
        } catch (FileNotFoundException $exception) {
            $context->addMessage(static::class, new Message($exception->getMessage(), MessageInterface::DEBUG));
        }

        return $context;
    }
}
