<?php

/**
 * Copyright © 2019-present Spryker Systems GmbH. All rights reserved.
 * Use of this software requires acceptance of the Evaluation License Agreement. See LICENSE file.
 */

namespace SprykerSdk\Sdk\Infrastructure\Workflow;

use Symfony\Component\Workflow\Marking;

class TimestampedMarking extends Marking
{
    /**
     * @var array
     */
    protected array $places = [];

    /**
     * @param array<string, int> $representation
     */
    public function __construct(array $representation = [])
    {
        foreach ($representation as $place => $timestamp) {
            $this->mark($place, $timestamp);
        }
    }

    /**
     * @param string $place
     * @param int|null $timestamp
     *
     * @return void
     */
    public function mark(string $place, ?int $timestamp = null): void
    {
        $this->places[$place] = $timestamp ?? time();
    }

    /**
     * @param string $place
     *
     * @return void
     */
    public function unmark(string $place): void
    {
        unset($this->places[$place]);
    }

    /**
     * @param string $place
     *
     * @return bool
     */
    public function has(string $place): bool
    {
        return isset($this->places[$place]);
    }

    /**
     * @return array
     */
    public function getPlaces(): array
    {
        return $this->places;
    }
}
