<?php

/**
 * Copyright © 2016-present Spryker Systems GmbH. All rights reserved.
 * Use of this software requires acceptance of the Evaluation License Agreement. See LICENSE file.
 */

namespace SprykerSdk\SprykerSdk;

use SprykerSdk\Spryk\Model\Spryk\ArgumentList\Builder\ArgumentListBuilder;
use SprykerSdk\Spryk\Model\Spryk\ArgumentList\Builder\ArgumentListBuilderInterface;
use SprykerSdk\Spryk\Model\Spryk\ArgumentList\Generator\ArgumentListGenerator;
use SprykerSdk\Spryk\Model\Spryk\ArgumentList\Generator\ArgumentListGeneratorInterface;
use SprykerSdk\Spryk\Model\Spryk\ArgumentList\Reader\ArgumentListReader;
use SprykerSdk\Spryk\Model\Spryk\ArgumentList\Reader\ArgumentListReaderInterface;
use SprykerSdk\Spryk\Model\Spryk\Builder\Collection\SprykBuilderCollection;
use SprykerSdk\Spryk\Model\Spryk\Builder\Collection\SprykBuilderCollectionInterface;
use SprykerSdk\Spryk\Model\Spryk\Builder\SprykBuilderFactory;
use SprykerSdk\Spryk\Model\Spryk\Command\ComposerDumpAutoloadSprykCommand;
use SprykerSdk\Spryk\Model\Spryk\Command\ComposerReplaceGenerateSprykCommand;
use SprykerSdk\Spryk\Model\Spryk\Command\SprykCommandInterface;
use SprykerSdk\Spryk\Model\Spryk\Configuration\ConfigurationFactory;
use SprykerSdk\Spryk\Model\Spryk\Definition\Argument\Callback\CallbackFactory;
use SprykerSdk\Spryk\Model\Spryk\Definition\Argument\Collection\ArgumentCollection;
use SprykerSdk\Spryk\Model\Spryk\Definition\Argument\Collection\ArgumentCollectionInterface;
use SprykerSdk\Spryk\Model\Spryk\Definition\Argument\Resolver\ArgumentResolver;
use SprykerSdk\Spryk\Model\Spryk\Definition\Argument\Resolver\ArgumentResolverInterface;
use SprykerSdk\Spryk\Model\Spryk\Definition\Argument\Superseder\Superseder;
use SprykerSdk\Spryk\Model\Spryk\Definition\Argument\Superseder\SupersederInterface;
use SprykerSdk\Spryk\Model\Spryk\Definition\Builder\SprykDefinitionBuilder;
use SprykerSdk\Spryk\Model\Spryk\Definition\Builder\SprykDefinitionBuilderInterface;
use SprykerSdk\Spryk\Model\Spryk\Dumper\Finder\SprykDefinitionFinder;
use SprykerSdk\Spryk\Model\Spryk\Dumper\Finder\SprykDefinitionFinderInterface;
use SprykerSdk\Spryk\Model\Spryk\Dumper\SprykDefinitionDumper;
use SprykerSdk\Spryk\Model\Spryk\Dumper\SprykDefinitionDumperInterface;
use SprykerSdk\Spryk\Model\Spryk\Executor\Configuration\SprykExecutorConfiguration;
use SprykerSdk\Spryk\Model\Spryk\Executor\Configuration\SprykExecutorConfigurationInterface;
use SprykerSdk\Spryk\Model\Spryk\Executor\SprykExecutor;
use SprykerSdk\Spryk\Model\Spryk\Executor\SprykExecutorInterface;
use SprykerSdk\Spryk\Model\Spryk\Filter\FilterFactory;
use SprykerSdk\Spryk\Style\SprykStyle;
use SprykerSdk\Spryk\Style\SprykStyleInterface;
use Symfony\Component\Console\Input\InputInterface;
use Symfony\Component\Console\Output\OutputInterface;

class SdkFactory
{
    /**
     * @var \SprykerSdk\SprykerSdk\SdkConfig|null
     */
    protected $config;

    /**
     * @return \SprykerSdk\SprykerSdk\SdkConfig
     */
    public function getConfig(): SdkConfig
    {
        if ($this->config === null) {
            $this->config = new SdkConfig();
        }

        return $this->config;
    }
}
