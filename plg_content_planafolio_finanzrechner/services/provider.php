<?php

/**
 * @package     Planafolio Finanzrechner
 * @copyright   (C) 2026 Planafolio
 * @license     GNU General Public License version 2 or later; see LICENSE
 */

\defined('_JEXEC') or die;

use Joomla\CMS\Extension\PluginInterface;
use Joomla\CMS\Factory;
use Joomla\CMS\Plugin\PluginHelper;
use Joomla\DI\Container;
use Joomla\DI\ServiceProviderInterface;
use Joomla\Event\DispatcherInterface;
use Planafolio\Plugin\Content\Finanzrechner\Extension\Finanzrechner;

return new class () implements ServiceProviderInterface {
    public function register(Container $container): void
    {
        $container->set(
            PluginInterface::class,
            function (Container $container) {
                $plugin = new Finanzrechner(
                    $container->get(DispatcherInterface::class),
                    (array) PluginHelper::getPlugin('content', 'planafolio_finanzrechner')
                );
                $plugin->setApplication(Factory::getApplication());

                return $plugin;
            }
        );
    }
};
