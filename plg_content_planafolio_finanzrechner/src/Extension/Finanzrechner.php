<?php

/**
 * @package     Planafolio Finanzrechner
 * @copyright   (C) 2026 Planafolio
 * @license     GNU General Public License version 2 or later; see LICENSE
 */

namespace Planafolio\Plugin\Content\Finanzrechner\Extension;

use Joomla\CMS\Event\Content\ContentPrepareEvent;
use Joomla\CMS\Plugin\CMSPlugin;
use Joomla\Event\SubscriberInterface;
use Joomla\Registry\Registry;
use Planafolio\Module\Finanzrechner\Site\Helper\CalculatorHelper;

\defined('_JEXEC') or die;

/**
 * Replaces {planafolio sparplanrechner} (optionally with theme="dark",
 * lang="en", height="800") in articles with a calculator. Defaults come from
 * the plugin settings; the rendering is shared with the module.
 */
final class Finanzrechner extends CMSPlugin implements SubscriberInterface
{
    private const PATTERN = '/\{planafolio\s+([a-z0-9-]+)((?:\s+[a-z]+="[^"]*")*)\s*\}/i';

    public static function getSubscribedEvents(): array
    {
        return ['onContentPrepare' => 'onContentPrepare'];
    }

    public function onContentPrepare(ContentPrepareEvent $event): void
    {
        $item = $event->getItem();
        if ($event->getContext() === 'com_finder.indexer' || !\is_object($item) || !isset($item->text)
            || !\is_string($item->text) || stripos($item->text, '{planafolio') === false) {
            return;
        }
        // Shared helper and strings live in the module of the same package.
        if (!class_exists(CalculatorHelper::class)) {
            return;
        }
        $this->getApplication()->getLanguage()->load('mod_planafolio_finanzrechner', JPATH_SITE);

        $used = false;
        $item->text = preg_replace_callback(self::PATTERN, function (array $m) use (&$used): string {
            $params = new Registry([
                'tool' => strtolower($m[1]),
                'theme' => $this->params->get('theme', 'light'),
                'lang' => $this->params->get('lang', 'auto'),
                'height' => $this->params->get('height', 640),
                'consent' => $this->params->get('consent', 1),
                'credit' => $this->params->get('credit', 0),
            ]);
            preg_match_all('/([a-z]+)="([^"]*)"/i', $m[2], $attrs, PREG_SET_ORDER);
            foreach ($attrs as [, $name, $value]) {
                $name = strtolower($name);
                if (\in_array($name, ['theme', 'lang', 'height'], true)) {
                    $params->set($name, $value);
                }
            }
            $calculator = CalculatorHelper::build($params);
            if ($calculator === null) {
                return $m[0];
            }
            $used = true;

            return CalculatorHelper::render($calculator);
        }, $item->text);

        if ($used) {
            CalculatorHelper::useAssets();
        }
    }
}
