<?php

/**
 * Copyright © 2019-present Spryker Systems GmbH. All rights reserved.
 * Use of this software requires acceptance of the Evaluation License Agreement. See LICENSE file.
 */

namespace SprykerSdk\Sdk\Infrastructure\Manifest\Normalizer;

use InvalidArgumentException;
use SprykerSdk\Sdk\Core\Application\Dependency\SettingFetcherInterface;
use SprykerSdk\SdkContracts\Enum\Setting;
use Symfony\Component\Process\Process;

class PhpManifestNormalizer implements ManifestNormalizerInterface
{
    /**
     * @var int
     */
    protected const PHPCBF_SUCCESS_FIX_CODE = 1;

    /**
     * @var \SprykerSdk\Sdk\Core\Application\Dependency\SettingFetcherInterface
     */
    protected SettingFetcherInterface $settingFetcher;

    /**
     * @param \SprykerSdk\Sdk\Core\Application\Dependency\SettingFetcherInterface $settingFetcher
     */
    public function __construct(SettingFetcherInterface $settingFetcher)
    {
        $this->settingFetcher = $settingFetcher;
    }

    /**
     * @param string $filePath
     *
     * @throws \InvalidArgumentException
     *
     * @return void
     */
    public function normalize(string $filePath): void
    {
        $sdkDir = $this->settingFetcher->getOneByPath(Setting::PATH_SDK_DIR)->getValues();

        $process = Process::fromShellCommandline('php "${:sdk_path}"/vendor/bin/phpcbf "${:filepath}"', $sdkDir);
        $process->run(null, [
            'sdk_path' => $sdkDir,
            'filepath' => $filePath,
        ]);

        if (!$process->isSuccessful() && $process->getExitCode() !== static::PHPCBF_SUCCESS_FIX_CODE) {
            throw new InvalidArgumentException(
                sprintf(
                    'Out: %s Error: %s Code: %s',
                    $process->getOutput(),
                    $process->getErrorOutput(),
                    $process->getExitCode(),
                ),
            );
        }
    }
}
