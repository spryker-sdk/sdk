<?php

/**
 * Copyright © 2019-present Spryker Systems GmbH. All rights reserved.
 * Use of this software requires acceptance of the Evaluation License Agreement. See LICENSE file.
 */

namespace SprykerSdk\Sdk\Extension\Tasks\Commands;

use SprykerSdk\Sdk\Core\Domain\Entity\Violation\ViolationReport;
use SprykerSdk\Sdk\Core\Domain\Entity\Violation\ViolationReportConverter;
use SprykerSdk\SdkContracts\Entity\CommandInterface;
use SprykerSdk\SdkContracts\Entity\ConverterInterface;
use SprykerSdk\SdkContracts\Entity\ErrorCommandInterface;
use SprykerSdk\SdkContracts\Violation\ViolationReportableInterface;
use SprykerSdk\SdkContracts\Violation\ViolationReportInterface;

class CheckGitCommand implements CommandInterface, ErrorCommandInterface, ViolationReportableInterface
{
    /**
     * @return string
     */
    public function getCommand(): string
    {
        return 'git --version';
    }

    /**
     * @return string
     */
    public function getErrorMessage(): string
    {
        return 'For using this task you should to have GIT. More details you can find https://git-scm.com/book/en/v2/Getting-Started-Installing-Git';
    }

    /**
     * @return string
     */
    public function getType(): string
    {
        return 'local_cli';
    }

    /**
     * @return bool
     */
    public function hasStopOnError(): bool
    {
        return true;
    }

    /**
     * @return array<string>
     */
    public function getTags(): array
    {
        return [];
    }

    /**
     * @return \SprykerSdk\SdkContracts\Entity\ConverterInterface|null
     */
    public function getViolationConverter(): ?ConverterInterface
    {
        return null;
    }

    /**
     * @return \SprykerSdk\SdkContracts\Violation\ViolationReportInterface|null
     */
    public function getViolationReport(): ?ViolationReportInterface
    {
        $vol = ['asdasd', 'asdasd', 'asdasdasd', 'asdasdasd', 'asdasdasdas'];
        $t = [];
        foreach ($vol as $v) {
            $t[] = new ViolationReportConverter($v, $v);
        }

        $v = new ViolationReport('asdsa','asdasd', $t, []);
        return $v;
    }
}
