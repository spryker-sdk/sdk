<?php

namespace EvaluatorExtension;

use EvaluatorExtension\DependencyInjection\EvaluatorExtensionExtension;
use Symfony\Component\DependencyInjection\Extension\Extension;
use Symfony\Component\HttpKernel\Bundle\Bundle;

class EvaluatorExtensionBundle extends Bundle
{
    public function createContainerExtension(): Extension
    {
        return new EvaluatorExtensionExtension();
    }
}
