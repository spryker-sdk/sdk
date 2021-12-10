<?php

/**
 * Copyright © 2019-present Spryker Systems GmbH. All rights reserved.
 * Use of this software requires acceptance of the Evaluation License Agreement. See LICENSE file.
 */

namespace SprykerSdk\Sdk\Infrastructure\Repository;

use Doctrine\Bundle\DoctrineBundle\Repository\ServiceEntityRepository;
use SprykerSdk\Sdk\Core\Appplication\Dependency\ConverterRepositoryInterface;

/**
 * @extends \Doctrine\ORM\EntityRepository<\SprykerSdk\Sdk\Infrastructure\Entity\Converter>
 */
class ConverterRepository extends ServiceEntityRepository implements ConverterRepositoryInterface
{
}
