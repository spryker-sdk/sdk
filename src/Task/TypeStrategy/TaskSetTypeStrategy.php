<?php

/**
 * Copyright © 2016-present Spryker Systems GmbH. All rights reserved.
 * Use of this software requires acceptance of the Evaluation License Agreement. See LICENSE file.
 */

namespace Sdk\Task\TypeStrategy;

use Sdk\Style\StyleInterface;
use Sdk\Task\Configuration\Loader\ConfigurationLoaderInterface;
use Sdk\Task\Exception\TaskExecutionFailed;

class TaskSetTypeStrategy  extends AbstractTypeStrategy
{
    /**
     * @var \Sdk\Task\Configuration\Loader\ConfigurationLoaderInterface
     */
    protected ConfigurationLoaderInterface $configurationLoader;

    /**
     * @param \Sdk\Task\Configuration\Loader\ConfigurationLoaderInterface $configurationLoader
     */
    public function __construct(ConfigurationLoaderInterface $configurationLoader)
    {
        $this->configurationLoader = $configurationLoader;
    }

    /**
     * @return string
     */
    public function getType(): string
    {
        return 'task_set';
    }

    /**
     * @return array
     */
    public function extract(): array
    {
        $this->definition['placeholders'] = [];
        if ($this->definition['tasks']) {
            foreach ($this->definition['tasks'] as $key => $task) {
                $definition = $this->configurationLoader->loadTask($task['id']);
                if ($definition['placeholders'] && is_array($definition['placeholders'])) {
                    $this->definition['placeholders'] += $definition['placeholders'];
                }
            }
        }

        return $this->definition;
    }

    /**
     * @param array $definition
     * @param \Sdk\Style\StyleInterface $style
     *
     * @return string
     */
    public function execute(array $definition, StyleInterface $style): string
    {
        $placeholders = $definition['placeholders'];
        $values = [];
        foreach ($placeholders as $placeholder) {
            $values['%' . $placeholder['name'] . '%'] = $placeholder['value'];
        }

        foreach ($definition['tasks'] as $task) {

            $command = str_replace(array_keys($values), array_values($values), $task['command']);

            $output=null;
            $result=null;



            if (!$result) {
                throw new TaskExecutionFailed($output);
            }

            return is_array($output) ? $output : [];
        }
    }
}
