<?php

namespace Application\Controller;

use Application\Form\KsiazkaForm;
use Application\Form\UsunForm;
use Application\Model\Ksiazka;
use Interop\Container\ContainerInterface;
use Laminas\ServiceManager\Factory\FactoryInterface;

class KsiazkiControllerFactory implements FactoryInterface
{
    public function __invoke(ContainerInterface $container, $requestedName, array $options = null)
    {
        $ksiazka = $container->get(Ksiazka::class);
        $ksiazkaForm = $container->get(KsiazkaForm::class);
        $ksiazkaUsunForm = $container->get(UsunForm::class);

        return new KsiazkiController($ksiazka, $ksiazkaForm, $ksiazkaUsunForm);
    }
}