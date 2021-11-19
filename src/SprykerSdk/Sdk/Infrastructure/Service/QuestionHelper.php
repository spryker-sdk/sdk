<?php

namespace SprykerSdk\Sdk\Infrastructure\Service;

use SprykerSdk\Sdk\Core\Appplication\Dependency\QuestionHelperInterface;
use SprykerSdk\Sdk\Core\Appplication\Exception\MissingValueException;
use Symfony\Component\Console\Question\ConfirmationQuestion;
use Symfony\Component\Console\Question\Question;

class QuestionHelper implements QuestionHelperInterface
{
    /**
     * @param \Symfony\Component\Console\Helper\QuestionHelper $questionHelper
     */
    public function __construct(
        protected \Symfony\Component\Console\Helper\QuestionHelper $questionHelper
    ) {}

    /**
     * @param string $description
     * @param mixed $defaultValue
     * @param string $type
     *
     * @return mixed
     */
    public function askValue(string $description, mixed $defaultValue, string $type): mixed
    {
        switch ($type) {
            case 'bool':
                $question = new ConfirmationQuestion($description, $defaultValue);

                break;
            default:
                $question = new Question($description, $defaultValue);
                $question->setNormalizer(function ($value) {
                    return $value ?: '';
                });

                if ($type === 'array') {
                    $question->setMultiline(true);
                }
        }

        if (!$defaultValue) {
            $question->setValidator(function ($value) {
                if (!$value && $value !== false) {
                    throw new MissingValueException('Value is invalid');
                }

                return $value;
            });
        }

        return $this->questionHelper->ask(
            $this->input,
            $this->output,
            $question
        );
    }
}
