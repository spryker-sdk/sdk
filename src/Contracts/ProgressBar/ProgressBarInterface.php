<?php

/**
 * Copyright © 2019-present Spryker Systems GmbH. All rights reserved.
 * Use of this software requires acceptance of the Evaluation License Agreement. See LICENSE file.
 */

namespace SprykerSdk\Sdk\Contracts\ProgressBar;

interface ProgressBarInterface
{
    /**
     * @param int|null $max
     *
     * @return void
     */
    public function start(?int $max = null): void;

    /**
     * @param int $max
     *
     * @return void
     */
    public function setMaxSteps(int $max): void;

    /**
     * Advances the progress output X steps.
     *
     * @param int $step Number of steps to advance
     *
     * @return void
     */
    public function advance(int $step = 1): void;

    /**
     * @return void
     */
    public function finish(): void;

    /**
     * @param string $message The text to associate with the placeholder
     * @param string $name The name of the placeholder
     *
     * @return void
     */
    public function setMessage(string $message, string $name = 'message');
}
