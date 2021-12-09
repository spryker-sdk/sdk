<?php

/**
 * Copyright © 2019-present Spryker Systems GmbH. All rights reserved.
 * Use of this software requires acceptance of the Evaluation License Agreement. See LICENSE file.
 */

namespace SprykerSdk\Sdk\Infrastructure\Repository;

use Doctrine\Bundle\DoctrineBundle\Repository\ServiceEntityRepository;
use SprykerSdk\Sdk\Contracts\Repository\LifecycleRepositoryInterface;

/**
 * @extends \Doctrine\Bundle\DoctrineBundle\Repository\ServiceEntityRepository<\SprykerSdk\Sdk\Infrastructure\Entity\Lifecycle>
 */
class LifecycleRepository extends ServiceEntityRepository implements LifecycleRepositoryInterface
{
}
