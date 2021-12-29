<?php

/**
 * Copyright © 2019-present Spryker Systems GmbH. All rights reserved.
 * Use of this software requires acceptance of the Evaluation License Agreement. See LICENSE file.
 */

namespace SprykerSdk\Sdk\Infrastructure\Event;

use Doctrine\Bundle\DoctrineBundle\Registry;
use Symfony\Component\Console\Event\ConsoleCommandEvent;

class CliSqliteListener
{
    /**
     * @var \Doctrine\Bundle\DoctrineBundle\Registry
     */
    private $doctrine;

    /**
     * @param \Doctrine\Bundle\DoctrineBundle\Registry $doctrine
     */
    public function __construct(Registry $doctrine)
    {
        $this->doctrine = $doctrine;
    }

    /**
     * @param \Symfony\Component\Console\Event\ConsoleCommandEvent $event
     *
     * @return void
     */
    public function beforeConsoleCommand(ConsoleCommandEvent $event): void
    {
        /** @var \Doctrine\DBAL\Driver\PDO\Connection $connection */
        $connection = $this->doctrine->getConnection();
        $connection->exec('PRAGMA foreign_keys = ON');
    }
}
