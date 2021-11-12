<?php

namespace Sdk\Task\ValueResolver;

use Sdk\Setting\SettingInterface;
use Sdk\Task\Exception\ValueResolverNotResolved;
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
                throw new ValueResolverNotResolved(sprintf('Value resolver for placeholder `%s` is missing', $placeholder['name']));
            }
            $valueResolver = $this->valueResolvers[$placeholder['valueResolver']];
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
     *
     * @return mixed
     */
    protected function getValue(ValueValueResolverInterface $valueResolver)
    {
        $settingPaths = $valueResolver->getSettingPaths();
        $settings = [];
        foreach ($settingPaths as $settingPath) {
            $settings[$settingPath] = $this->setting->getSetting($settingPath, false);
        }

        return $valueResolver->getValue($settings);
    }
}
