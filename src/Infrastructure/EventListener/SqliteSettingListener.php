<?php

/**
 * Copyright © 2019-present Spryker Systems GmbH. All rights reserved.
 * Use of this software requires acceptance of the Evaluation License Agreement. See LICENSE file.
 */

namespace SprykerSdk\Sdk\Infrastructure\EventListener;

use Doctrine\Bundle\DoctrineBundle\Registry;
use Symfony\Contracts\EventDispatcher\Event;

class SqliteSettingListener
{
    /**
     * @var \Doctrine\Bundle\DoctrineBundle\Registry
     */
    protected $doctrine;

    /**
     * @param \Doctrine\Bundle\DoctrineBundle\Registry $doctrine
     */
    public function __construct(Registry $doctrine)
    {
        $this->doctrine = $doctrine;
    }

    /**
     * @param \Symfony\Contracts\EventDispatcher\Event $event
     *
     * @return void
     */
    public function beforeConsoleCommand(Event $event): void
    {
        /** @var \Doctrine\DBAL\Driver\PDO\Connection $connection */
        $connection = $this->doctrine->getConnection();
        $connection->exec('PRAGMA foreign_keys = ON');
    }
}
