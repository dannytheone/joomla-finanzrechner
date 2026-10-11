<?php

/**
 * @package     Planafolio Finanzrechner
 * @copyright   (C) 2026 Planafolio
 * @license     GNU General Public License version 2 or later; see LICENSE
 */

\defined('_JEXEC') or die;

use Joomla\CMS\Factory;
use Joomla\Database\DatabaseInterface;

/**
 * Plugins are installed disabled; enable the content plugin on first install
 * so {planafolio …} works right away. Updates leave the state untouched.
 */
class Pkg_Planafolio_finanzrechnerInstallerScript
{
    public function postflight(string $type, $parent): bool
    {
        if ($type !== 'install') {
            return true;
        }
        $db = Factory::getContainer()->get(DatabaseInterface::class);
        $query = $db->getQuery(true)
            ->update($db->quoteName('#__extensions'))
            ->set($db->quoteName('enabled') . ' = 1')
            ->where($db->quoteName('type') . ' = ' . $db->quote('plugin'))
            ->where($db->quoteName('folder') . ' = ' . $db->quote('content'))
            ->where($db->quoteName('element') . ' = ' . $db->quote('planafolio_finanzrechner'));
        $db->setQuery($query)->execute();

        return true;
    }
}
