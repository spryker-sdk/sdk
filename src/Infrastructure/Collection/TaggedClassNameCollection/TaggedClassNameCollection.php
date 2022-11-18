<?php

/**
 * Copyright © 2019-present Spryker Systems GmbH. All rights reserved.
 * Use of this software requires acceptance of the Evaluation License Agreement. See LICENSE file.
 */

namespace SprykerSdk\Sdk\Infrastructure\Collection\TaggedClassNameCollection;

use ArrayIterator;
use Iterator;

class TaggedClassNameCollection implements TaggedClassNameCollectionInterface
{
    /**
     * @var \ArrayIterator
     */
    protected ArrayIterator $iterator;

    /**
     * Is populated by a compiler pass
     *
     * @param array<class-string> $classNames
     */
    public function __construct(array $classNames)
    {
        $this->iterator = new ArrayIterator($classNames);
    }

    /**
     * @return \Iterator<class-string>
     */
    public function getIterator(): Iterator
    {
        return $this->iterator;
    }
}
