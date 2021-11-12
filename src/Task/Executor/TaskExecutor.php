<?php

namespace Sdk\Task\Executor;

use Sdk\Setting\SettingInterface;
use Sdk\Style\StyleInterface;
use Sdk\Task\TypeStrategy\TypeStrategyInterface;
use Sdk\Task\ValueResolver\ValueResolverInterface;
use Symfony\Component\Console\Exception\InvalidArgumentException;
use Symfony\Component\Console\Question\ConfirmationQuestion;
use Symfony\Component\Console\Question\Question;

class TaskExecutor implements TaskExecutorInterface
{
    /**
     * @var \Sdk\Task\TypeStrategy\TypeStrategyInterface
     */
    protected $typeStrategy;

    /**
     * @var \Sdk\Setting\SettingInterface
     */
    protected $setting;

    /**
     * @var \Sdk\Task\ValueResolver\ValueResolverInterface
     */
    protected $valueResolver;

    /**
     * @param \Sdk\Task\TypeStrategy\TypeStrategyInterface $typeStrategy
     * @param \Sdk\Setting\SettingInterface $setting
     * @param \Sdk\Task\ValueResolver\ValueResolverInterface $valueResolver
     */
    public function __construct(
        TypeStrategyInterface $typeStrategy,
        SettingInterface $setting,
        ValueResolverInterface $valueResolver
    ) {
        $this->typeStrategy = $typeStrategy;
        $this->setting = $setting;
        $this->valueResolver = $valueResolver;
    }

    /**
     * @param array $options
     * @param \Sdk\Style\StyleInterface $style
     *
     * @return void
     */
    public function execute(array $options, StyleInterface $style): void
    {
        $definition = $this->typeStrategy->extract();

        if ($definition['placeholders']) {
            $definition['placeholders'] = $this->resolveValue($definition['placeholders'], $style);
        }
        $output = $this->typeStrategy->execute($definition, $style);

        $style->writeLine($output);
        $style->writeLine(sprintf('Task is done'));
    }

    /**
     * @param array $placeholders
     * @param \Sdk\Style\StyleInterface $style
     *
     * @return array
     */
    protected function resolveValue(array $placeholders, StyleInterface $style): array
    {
        foreach ($placeholders as $key => $placeholder) {
            $value = null;
            if (!empty($options[$placeholder['name']])) {
                $value = $options[$placeholder['name']];
            }
            if ($value === null) {
                $value = $this->setting->getSetting($placeholder['name'], false);
            }
            if ($value === null) {
                $placeholder = $this->valueResolver->expand([$placeholder], true)[0];
                $value = $placeholder['value'];
            }
            if (!$value && $placeholder['optional']) {
                $value = $this->askValue($placeholder, $style);
            }
            $placeholders[$key]['value'] = $value;
        }

        return $placeholders;
    }


    /**
     * @param array $placeholder
     * @param \Sdk\Style\StyleInterface $style
     *
     * @return int|string|null
     */
    protected function askValue(array $placeholder, StyleInterface $style)
    {
        $multiline = $placeholder['type'] == 'array' ? true : false;
        $question = $this->createPlaceholderQuestion(
            $placeholder['type'],
            sprintf('Enter %s for <fg=yellow>%s</> setting (%s)', $placeholder['type'], $placeholder['name'], $placeholder['description']),
            $multiline
        );

        return $style->askQuestion($question);
    }

    /**
     * @param string $type
     * @param string $message
     * @param bool $multiline
     *
     * @return mixed
     */
    protected function createPlaceholderQuestion(string $type, string $message, bool $multiline = false): Question
    {
        switch ($type) {
            case 'bool':
                $question = new ConfirmationQuestion($message);

                break;
            default:
                $question = new Question($message);
                $question->setNormalizer(function ($value) {
                    return $value ?: '';
                });
        }
        $question->setValidator(function ($value) {
            if (!$value) {
                throw new InvalidArgumentException('Value is invalid');
            }

            return $value;
        });

        if ($multiline) {
            $question->setMultiline(true);
        }

        return $question;
    }
}
