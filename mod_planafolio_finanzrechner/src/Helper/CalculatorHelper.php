<?php

/**
 * @package     Planafolio Finanzrechner
 * @copyright   (C) 2026 Planafolio
 * @license     GNU General Public License version 2 or later; see LICENSE
 */

namespace Planafolio\Module\Finanzrechner\Site\Helper;

use Joomla\CMS\Factory;
use Joomla\CMS\Language\Text;
use Joomla\Registry\Registry;

\defined('_JEXEC') or die;

/**
 * Builds and renders one calculator: iframe URL, name, two-click mode and the
 * optional source link. Shared by the module and the content plugin.
 */
final class CalculatorHelper
{
    private const SERVICE_ORIGIN = 'https://planafolio.com';

    /** Slug => full calculator page per language (English pages live under /en/). */
    public const TOOLS = [
        'sparplanrechner' => ['de' => '/sparplanrechner', 'en' => '/en/sparplanrechner'],
        'zinseszinsrechner' => ['de' => '/zinseszinsrechner', 'en' => '/en/zinseszinsrechner'],
        'kapitalertragsteuer-rechner' => ['de' => '/kapitalertragsteuer-rechner', 'en' => '/en/capital-gains-tax-calculator'],
        'vorabpauschale-rechner' => ['de' => '/vorabpauschale-rechner', 'en' => '/en/vorabpauschale-rechner'],
        'freistellungsauftrag-rechner' => ['de' => '/freistellungsauftrag-rechner', 'en' => '/en/freistellungsauftrag-rechner'],
        'dividenden-rechner' => ['de' => '/dividenden-rechner', 'en' => '/en/dividenden-rechner'],
        'etf-kosten-rechner' => ['de' => '/etf-kosten-rechner', 'en' => '/en/etf-kosten-rechner'],
        'entnahmerechner' => ['de' => '/entnahmerechner', 'en' => '/en/entnahmerechner'],
        'finanzielle-freiheit-rechner' => ['de' => '/finanzielle-freiheit-rechner', 'en' => '/en/financial-independence-calculator'],
        'rentenluecke-rechner' => ['de' => '/rentenluecke-rechner', 'en' => '/en/rentenluecke-rechner'],
        'altersvorsorgedepot' => ['de' => '/altersvorsorgedepot', 'en' => '/en/altersvorsorgedepot'],
        'fruehstart-rente-rechner' => ['de' => '/fruehstart-rente-rechner', 'en' => '/en/fruehstart-rente-rechner'],
        'inflationsrechner' => ['de' => '/inflationsrechner', 'en' => '/en/inflation-calculator'],
        'einmalanlage-oder-sparplan' => ['de' => '/einmalanlage-oder-sparplan', 'en' => '/en/einmalanlage-oder-sparplan'],
        'tilgen-oder-investieren' => ['de' => '/tilgen-oder-investieren', 'en' => '/en/tilgen-oder-investieren'],
        'rebalancing-rechner' => ['de' => '/rebalancing-rechner', 'en' => '/en/rebalancing-calculator'],
        'musterportfolios' => ['de' => '/musterportfolios', 'en' => '/en/musterportfolios'],
        'xirr-rechner' => ['de' => '/xirr-rechner', 'en' => '/en/xirr-rechner'],
        'ttwror-rechner' => ['de' => '/ttwror-rechner', 'en' => '/en/ttwror-rechner'],
    ];

    /** Language constant for a calculator name. */
    public static function nameKey(string $slug): string
    {
        return 'MOD_PLANAFOLIO_FINANZRECHNER_TOOL_' . strtoupper(str_replace('-', '_', $slug));
    }

    /**
     * Normalised calculator data, or null for an unknown calculator.
     *
     * @param  Registry  $params  tool, theme (light|dark), lang (auto|de|en),
     *                            consent (1|0), credit (1|0), height (px)
     */
    public static function build(Registry $params, string $medium = 'joomla'): ?array
    {
        $slug = (string) $params->get('tool', 'sparplanrechner');
        if (!isset(self::TOOLS[$slug])) {
            return null;
        }

        $theme = $params->get('theme') === 'dark' ? 'dark' : 'light';
        $lang = (string) $params->get('lang', 'auto');
        if ($lang !== 'de' && $lang !== 'en') {
            $tag = Factory::getApplication()->getLanguage()->getTag();
            $lang = str_starts_with(strtolower($tag), 'de') ? 'de' : 'en';
        }
        $height = (int) $params->get('height', 640);
        $height = ($height >= 200 && $height <= 3000) ? $height : 640;

        $src = self::SERVICE_ORIGIN . '/embed/' . $slug . '?' . http_build_query([
            'v' => 1,
            'theme' => $theme,
            'lang' => $lang,
            'utm_medium' => $medium,
        ]);
        $href = self::SERVICE_ORIGIN . self::TOOLS[$slug][$lang] . '?' . http_build_query([
            'utm_source' => 'embed',
            'utm_medium' => $medium,
            'utm_campaign' => $slug,
        ]);

        return [
            'slug' => $slug,
            'name' => Text::_(self::nameKey($slug)),
            'src' => $src,
            'href' => $href,
            'theme' => $theme,
            'height' => $height,
            'placeholderHeight' => min($height, 360),
            'consent' => (bool) (int) $params->get('consent', 1),
            'credit' => (bool) (int) $params->get('credit', 0),
            'privacyUrl' => self::SERVICE_ORIGIN . ($lang === 'en' ? '/en/datenschutz' : '/datenschutz'),
        ];
    }

    /** Loads the stylesheet and the two-click script once per page. */
    public static function useAssets(): void
    {
        $wa = Factory::getApplication()->getDocument()->getWebAssetManager();
        $wa->getRegistry()->addExtensionRegistryFile('mod_planafolio_finanzrechner');
        $wa->useStyle('mod_planafolio_finanzrechner.embed')->useScript('mod_planafolio_finanzrechner.embed');
    }

    /** HTML for one calculator; all values escaped. */
    public static function render(array $c): string
    {
        $e = static fn ($v): string => htmlspecialchars((string) $v, ENT_QUOTES, 'UTF-8');
        $html = '<div class="pft-embed pft-theme-' . $e($c['theme']) . '">';

        if ($c['consent']) {
            $html .= '<div class="pft-consent" style="min-height:' . (int) $c['placeholderHeight'] . 'px">'
                . '<svg class="pft-consent-icon" viewBox="0 0 64 64" aria-hidden="true" focusable="false"><rect width="64" height="64" rx="16" fill="#7c5cfc"/><rect x="16" y="12" width="32" height="40" rx="6" fill="#fff"/><path d="M21 28l6-5 5 3 8-8" stroke="#6a48f0" stroke-width="3" fill="none" stroke-linecap="round" stroke-linejoin="round"/><g fill="#c4b5fd"><circle cx="24" cy="38" r="2.6"/><circle cx="32" cy="38" r="2.6"/><circle cx="40" cy="38" r="2.6"/><circle cx="24" cy="46" r="2.6"/><circle cx="32" cy="46" r="2.6"/></g><circle cx="40" cy="46" r="2.6" fill="#6a48f0"/></svg>'
                . '<p class="pft-consent-title">' . $e($c['name']) . '</p>'
                . '<p class="pft-consent-text">' . $e(Text::_('MOD_PLANAFOLIO_FINANZRECHNER_CONSENT_TEXT')) . '</p>'
                . '<button type="button" class="pft-consent-button" data-pft-src="' . $e($c['src']) . '" data-pft-title="' . $e($c['name']) . '" data-pft-height="' . (int) $c['height'] . '">'
                . $e(Text::_('MOD_PLANAFOLIO_FINANZRECHNER_CONSENT_BUTTON')) . '</button>'
                . '<p class="pft-consent-note">' . $e(Text::_('MOD_PLANAFOLIO_FINANZRECHNER_CONSENT_NOTE'))
                . ' <a href="' . $e($c['privacyUrl']) . '" target="_blank" rel="noopener nofollow">' . $e(Text::_('MOD_PLANAFOLIO_FINANZRECHNER_CONSENT_PRIVACY')) . '</a></p>'
                . '</div>';
        } else {
            $html .= '<iframe data-planafolio-embed="" src="' . $e($c['src']) . '" title="' . $e($c['name']) . '" loading="lazy" style="width:100%;border:0;height:' . (int) $c['height'] . 'px"></iframe>';
        }

        if ($c['credit']) {
            $html .= '<p class="pft-credit">' . $e(Text::_('MOD_PLANAFOLIO_FINANZRECHNER_CREDIT_PREFIX')) . ' <a href="' . $e($c['href']) . '">Planafolio</a></p>';
        }

        return $html . '</div>';
    }
}
