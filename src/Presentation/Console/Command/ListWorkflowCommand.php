<?php

/**
 * Copyright © 2019-present Spryker Systems GmbH. All rights reserved.
 * Use of this software requires acceptance of the Evaluation License Agreement. See LICENSE file.
 */

namespace SprykerSdk\Sdk\Presentation\Console\Command;

use SprykerSdk\Sdk\Infrastructure\Workflow\ProjectWorkflow;
use Symfony\Component\Console\Command\Command;
use Symfony\Component\Console\Input\InputInterface;
use Symfony\Component\Console\Input\InputOption;
use Symfony\Component\Console\Output\OutputInterface;
use Symfony\Component\Console\Style\SymfonyStyle;

class ListWorkflowCommand extends Command
{
    /**
     * @var string
     */
    protected const NAME = 'sdk:workflow:list';

    /**
     * @var string
     */
    protected const OPTION_PROJECT = 'project';

    /**
     * @var string|null The default command description
     */
    protected static $defaultDescription = 'List available workflows.';

    /**
     * @var \SprykerSdk\Sdk\Infrastructure\Workflow\ProjectWorkflow
     */
    protected $projectWorkflow;

    /**
     * @param \SprykerSdk\Sdk\Infrastructure\Workflow\ProjectWorkflow $projectWorkflow
     */
    public function __construct(ProjectWorkflow $projectWorkflow)
    {
        $this->projectWorkflow = $projectWorkflow;
        parent::__construct(static::NAME);
    }

    /**
     * @return void
     */
    protected function configure()
    {
        parent::configure();
        $this->addOption(static::OPTION_PROJECT, 'p', InputOption::VALUE_NONE, 'Show project workflows');
    }

    /**
     * @param \Symfony\Component\Console\Input\InputInterface $input
     * @param \Symfony\Component\Console\Output\OutputInterface $output
     *
     * @return int
     */
    public function execute(InputInterface $input, OutputInterface $output): int
    {
        $io = new SymfonyStyle($input, $output);

        if ($input->getOption(static::OPTION_PROJECT)) {
            $io->listing($this->projectWorkflow->findInitializedWorkflows());

            return static::SUCCESS;
        }

        $io->listing($this->projectWorkflow->getAll());

        return static::SUCCESS;
    }
}
