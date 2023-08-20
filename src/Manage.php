<?php
/**
 * @brief integrityCheck, a plugin for Dotclear 2
 *
 * @package Dotclear
 * @subpackage Plugins
 *
 * @author Franck Paul and contributors
 *
 * @copyright Franck Paul carnet.franck.paul@gmail.com
 * @copyright GPL-2.0 https://www.gnu.org/licenses/gpl-2.0.html
 */
declare(strict_types=1);

namespace Dotclear\Plugin\integrityCheck;

use dcCore;
use dcUpdate;
use Dotclear\Core\Backend\Notices;
use Dotclear\Core\Backend\Page;
use Dotclear\Core\Process;
use Exception;

class Manage extends Process
{
    /**
     * Initializes the page.
     */
    public static function init(): bool
    {
        // Manageable only by super-admin
        return self::status(My::checkContext(My::MANAGE));
    }

    /**
     * Processes the request(s).
     */
    public static function process(): bool
    {
        if (!self::status()) {
            return false;
        }

        // Init stuff
        dcCore::app()->admin->has_bad_files = false;
        dcCore::app()->admin->updater       = new dcUpdate(DC_UPDATE_URL, 'dotclear', DC_UPDATE_VERSION, DC_TPL_CACHE . '/versions');

        // Run check
        try {
            dcCore::app()->admin->updater->checkIntegrity(DC_ROOT . '/inc/digests', DC_ROOT);
        } catch (Exception $e) {
            $msg       = $e->getMessage();
            $bad_files = dcCore::app()->admin->updater->getBadFiles();
            if (count($bad_files)) {
                dcCore::app()->admin->has_bad_files = true;

                $msg = __('The following files differ from your initial dotclear installation :') .
                    '<ul><li><strong>' .
                    implode('</strong></li><li><strong>', $bad_files) .
                    '</strong></li></ul>';
            } else {
                $msg = __('An unexpected error occured : ') . $e->getMessage();
            }

            dcCore::app()->error->add($msg);
        }

        return true;
    }

    /**
     * Renders the page.
     */
    public static function render(): void
    {
        if (!self::status()) {
            return;
        }

        Page::openModule(__('Integrity Check'));

        echo Page::breadcrumb(
            [
                __('System')          => '',
                __('Integrity Check') => '',
            ]
        );
        echo Notices::getNotices();

        // Content
        if (!dcCore::app()->admin->has_bad_files) {
            echo
            '<h2>' . __('Diagnostics') . '</h2>' .
            '<p class="message">' . __('All your installation files are correct.') . '</p>';
        }

        Page::closeModule();
    }
}
