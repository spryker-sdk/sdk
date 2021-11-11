<?php

namespace Sdk\Task\ValueResolver;

use Sdk\Task\Exception\ValueResolverNotResolved;

class ValueResolver implements ValueResolverInterface
{
    /**
     * @var array<string, \Sdk\Task\ValueResolver\Value\ValueResolverInterface>
     */
    protected array $valueResolvers = [];

    /**
     * @var array
     */
    protected array $parameters = [];

    /**
     * @param \Sdk\Task\ValueResolver\Value\ValueResolverInterface[] $valueResolvers
     */
    public function __construct(array $valueResolvers, array $parameters)
    {
        foreach ($valueResolvers as $valueResolver) {
            $this->valueResolvers[$valueResolver->getId()] = $valueResolver;
        }

        $this->parameters = $parameters;
    }

    /**
     * @param array $definition
     *
     * @throws \Sdk\Task\Exception\ValueResolverNotResolved
     *
     * @return array
     */
    public function expand(array $definition): array
    {
        foreach ($definition['placeholders'] as &$placeholder) {
            if (isset($this->valueResolvers[$placeholder['valueResolver']])) {
                $valueResolver = $this->valueResolvers[$placeholder['valueResolver']];
                $placeholder['type'] = $valueResolver->getType();
                $placeholder['description'] = $valueResolver->getDescription();
                $placeholder['value'] = $valueResolver->getValue($this->parameters);

                continue;
            }

            throw new ValueResolverNotResolved(sprintf('Value resolver for placeholder `%s` is not supported', $placeholder['id']));
        }

        return $definition;
    }
}
