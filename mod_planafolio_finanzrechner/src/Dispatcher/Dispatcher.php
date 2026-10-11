<?php

/**
 * @package     Planafolio Finanzrechner
 * @copyright   (C) 2026 Planafolio
 * @license     GNU General Public License version 2 or later; see LICENSE
 */

namespace Planafolio\Module\Finanzrechner\Site\Dispatcher;

use Joomla\CMS\Dispatcher\AbstractModuleDispatcher;
use Planafolio\Module\Finanzrechner\Site\Helper\CalculatorHelper;

\defined('_JEXEC') or die;

final class Dispatcher extends AbstractModuleDispatcher
{
    protected function getLayoutData(): array
    {
        $data = parent::getLayoutData();
        $data['calculator'] = CalculatorHelper::build($data['params']);
        return $data;
    }
}
