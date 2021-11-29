<?php

/**
 * Copyright © 2016-present Spryker Systems GmbH. All rights reserved.
 * Use of this software requires acceptance of the Evaluation License Agreement. See LICENSE file.
 */

namespace SprykerSdk\Sdk\Infrastructure\Entity;

class File extends \SprykerSdk\Sdk\Core\Domain\Entity\File
{
    protected int $id;

    protected RemovedEvent $removedEvent;

    /**
     * @return RemovedEvent
     */
    public function getRemovedEvent(): RemovedEvent
    {
        return $this->removedEvent;
    }

    /**
     * @param RemovedEvent $removedEvent
     *
     * @return File
     */
    public function setRemovedEvent(RemovedEvent $removedEvent): File
    {
        $this->removedEvent = $removedEvent;

        return $this;
    }

    /**
     * @return int
     */
    public function getId(): int
    {
        return $this->id;
    }

    /**
     * @param int $id
     * @return File
     */
    public function setId(int $id): File
    {
        $this->id = $id;
        return $this;
    }

    /**
     * @param string $path
     * @return File
     */
    public function setPath(string $path): File
    {
        $this->path = $path;
        return $this;
    }

    /**
     * @param string $content
     * @return File
     */
    public function setContent(string $content): File
    {
        $this->content = $content;
        return $this;
    }
}
