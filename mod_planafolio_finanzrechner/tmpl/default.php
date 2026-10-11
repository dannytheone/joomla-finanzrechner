<?php

/**
 * @package     Planafolio Finanzrechner
 * @copyright   (C) 2026 Planafolio
 * @license     GNU General Public License version 2 or later; see LICENSE
 */

\defined('_JEXEC') or die;

use Planafolio\Module\Finanzrechner\Site\Helper\CalculatorHelper;

/** @var array|null $calculator */
if (!$calculator) {
    return;
}
CalculatorHelper::useAssets();
echo CalculatorHelper::render($calculator);
