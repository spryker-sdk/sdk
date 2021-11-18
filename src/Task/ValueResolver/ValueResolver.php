<?php

namespace Sdk\Task\ValueResolver;

use Sdk\Setting\SettingInterface;
use Sdk\Task\Exception\ValueResolverNotResolved;
use Sdk\Task\ValueResolver\Value\ConfigurationValueResolverInterface;
use \Sdk\Task\ValueResolver\Value\ValueResolverInterface as ValueValueResolverInterface;

class ValueResolver implements ValueResolverInterface
{
    /**
     * @var array<string, \Sdk\Task\ValueResolver\Value\ValueResolverInterface>
     */
    protected array $valueResolvers = [];

    /**
     * @var \Sdk\Setting\SettingInterface
     */
    protected $setting;

    /**
     * @param \Sdk\Task\ValueResolver\Value\ValueResolverInterface[] $options
     */
    public function __construct(array $valueResolvers, SettingInterface $setting)
    {
        foreach ($valueResolvers as $valueResolver) {
            $this->valueResolvers[$valueResolver->getId()] = $valueResolver;
        }

        $this->setting = $setting;
    }

    /**
     * @param array $placeholders
     * @param bool $resolveValue
     *
     * @throws \Sdk\Task\Exception\ValueResolverNotResolved
     *
     * @return array
     */
    public function expand(array $placeholders, bool $resolveValue = false): array
    {
        foreach ($placeholders as $key => $placeholder) {
            if (!isset($this->valueResolvers[$placeholder['valueResolver']])) {
                throw new ValueResolverNotResolved(sprintf('`%s` value resolver is missing', $placeholder['valueResolver']));
            }
            $valueResolver = $this->valueResolvers[$placeholder['valueResolver']];
            if ($valueResolver instanceof ConfigurationValueResolverInterface) {
                $configuration = $placeholder['configuration'];
                $parameterName = $valueResolver->getParameterName($configuration);
                $placeholders[$key]['parameterName'] = $parameterName ?? strtolower($valueResolver->getId($configuration));
                $placeholders[$key]['type'] = $valueResolver->getType($configuration);
                $placeholders[$key]['description'] = $valueResolver->getDescription($configuration);
                if ($resolveValue) {
                    $placeholders[$key]['value'] = $this->getValue($valueResolver, $configuration);
                }

                continue;
            }
            $parameterName = $valueResolver->getParameterName();
            $placeholders[$key]['parameterName'] = $parameterName ?? strtolower($valueResolver->getId());
            $placeholders[$key]['type'] = $valueResolver->getType();
            $placeholders[$key]['description'] = $valueResolver->getDescription();
            if ($resolveValue) {
                $placeholders[$key]['value'] = $this->getValue($valueResolver);
            }
        }

        return $placeholders;
    }

    /**
     * @param \Sdk\Task\ValueResolver\Value\ValueResolverInterface $valueResolver
     * @param array $configuration
     *
     * @return mixed
     */
    protected function getValue(ValueValueResolverInterface $valueResolver, array $configuration = [])
    {
        $settingPaths = (!$configuration) ? $valueResolver->getSettingPaths() : $valueResolver->getSettingPaths($configuration);
        $settings = [];
        foreach ($settingPaths as $settingPath) {
            $settings[$settingPath] = $this->setting->getSetting($settingPath, false);
        }

        return (!$configuration) ? $valueResolver->getValue($settings, $configuration) : $valueResolver->getValue($settings, $configuration);
    }
}
