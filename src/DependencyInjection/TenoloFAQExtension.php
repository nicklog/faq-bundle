<?php

namespace Tenolo\Bundle\FAQBundle\DependencyInjection;

use Symfony\Component\Config\FileLocator;
use Symfony\Component\DependencyInjection\ContainerBuilder;
use Symfony\Component\DependencyInjection\Extension\PrependExtensionInterface;
use Symfony\Component\DependencyInjection\Loader;
use Symfony\Component\HttpKernel\DependencyInjection\ConfigurableExtension;
use Tenolo\Bundle\FAQBundle\Entity as BundleEntity;
use Tenolo\Bundle\FAQBundle\Model\Interfaces as BundleInterfaces;

/**
 * Class TenoloFAQExtension
 *
 * @package Tenolo\Bundle\FAQBundle\DependencyInjection
 * @author  Nikita Loges
 * @company tenolo GbR
 */
class TenoloFAQExtension extends ConfigurableExtension implements PrependExtensionInterface
{

    /**
     * @inheritdoc
     */
    public function loadInternal(array $configs, ContainerBuilder $container)
    {
        $loader = new Loader\YamlFileLoader($container, new FileLocator(__DIR__ . '/../Resources/config'));
        $loader->load('services.yml');

        $container->setParameter('tenolo_faq.templates.faq.index', $configs['templates']['faq']['index']);
        $container->setParameter('tenolo_faq.templates.category.show', $configs['templates']['category']['show']);
        $container->setParameter('tenolo_faq.templates.question.most_recent', $configs['templates']['question']['most_recent']);
        $container->setParameter('tenolo_faq.templates.question.show', $configs['templates']['question']['show']);
    }

    /**
     * @inheritDoc
     */
    public function prepend(ContainerBuilder $container)
    {
        $container->prependExtensionConfig('doctrine', $this->getDoctrineConfig());
    }

    /**
     * @return array
     */
    protected function getDoctrineConfig()
    {
        return [
            'orm' => [
                'resolve_target_entities' => [
                    BundleInterfaces\CategoryInterface::class => BundleEntity\Category::class,
                    BundleInterfaces\QuestionInterface::class => BundleEntity\Question::class,
                ]
            ]
        ];
    }
}
