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
            $definition['placeholders'] = $this->resolveValue($definition['placeholders'], $options, $style);
        }

        $output = $this->typeStrategy->execute($definition, $style);

        $style->writeLine($output);
        $style->writeLine(sprintf('Task is done'));
    }

    /**
     * @param array $placeholders
     * @param array $options
     * @param \Sdk\Style\StyleInterface $style
     *
     * @throws \Sdk\Exception\SettingNotFoundException
     * @throws \Sdk\Task\Exception\ValueResolverNotResolved
     * @return array
     */
    protected function resolveValue(array $placeholders, array $options,  StyleInterface $style): array
    {
        foreach ($placeholders as $key => $placeholder) {
            $value = null;
            $placeholder = $placeholders[$key] = $this->valueResolver->expand([$placeholder])[0];
            if (!empty($options[$placeholder['parameterName']])) {
                $placeholders[$key]['value'] = $options[$placeholder['parameterName']];

                continue;
            }
            if ($value === null) {
                $value = $this->setting->getSetting($placeholder['parameterName'], false);
            }
            if ($value === null) {
                $value = $this->valueResolver->expand([$placeholder], true)[0]['value'];
            }
            if (!$placeholder['optional']) {
                $value = $this->askValue($placeholder, $value, $style);
            }
            $placeholders[$key]['value'] = $value;
        }

        return $placeholders;
    }


    /**
     * @param array $placeholder
     * @param null|string $value
     * @param \Sdk\Style\StyleInterface $style
     *
     * @return int|string|null
     */
    protected function askValue(array $placeholder, ?string $value, StyleInterface $style)
    {
        $question = $this->createPlaceholderQuestion(
            $placeholder['type'],
            $value,
            sprintf('Enter %s for <fg=yellow>%s</> setting (%s)', $placeholder['type'], $placeholder['parameterName'], $placeholder['description'])
        );

        return $style->askQuestion($question);
    }

    /**
     * @param string $type
     * @param null|string $value
     * @param string $message
     *
     * @return \Symfony\Component\Console\Question\Question
     */
    protected function createPlaceholderQuestion(string $type, ?string $value, string $message): Question
    {
        $multiline = $type === 'array' ? true : false;

        switch ($type) {
            case 'bool':
                $question = new ConfirmationQuestion($message, $value);

                break;
            default:
                $question = new Question($message, $value);
                $question->setNormalizer(function ($value) {
                    return $value ?: '';
                });

                if ($multiline) {
                    $question->setMultiline(true);
                }
        }
        if (!$value) {
            $question->setValidator(function ($value) {
                if (!$value) {
                    throw new InvalidArgumentException('Value is invalid');
                }

                return $value;
            });
        }

        return $question;
    }
}
