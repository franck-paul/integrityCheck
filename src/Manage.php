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

use Dotclear\App;
use Dotclear\Core\Backend\Notices;
use Dotclear\Core\Backend\Page;
use Dotclear\Core\Backend\Update;
use Dotclear\Core\Process;
use Exception;

/**
 * @todo switch Helper/Html/Form/...
 */
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
        App::backend()->has_bad_files = false;
        App::backend()->updater       = new Update(App::config()->coreUpdateUrl(), 'dotclear', App::config()->coreUpdateCanal(), App::config()->cacheRoot() . '/versions');

        // Run check
        try {
            App::backend()->updater->checkIntegrity(App::config()->dotclearRoot() . '/inc/digests', App::config()->dotclearRoot());
        } catch (Exception $exception) {
            $msg       = $exception->getMessage();
            $bad_files = App::backend()->updater->getBadFiles();
            if (count($bad_files) > 0) {
                App::backend()->has_bad_files = true;

                $msg = __('The following files differ from your initial dotclear installation :') .
                    '<ul><li><strong>' .
                    implode('</strong></li><li><strong>', $bad_files) .
                    '</strong></li></ul>';
            } else {
                $msg = __('An unexpected error occured : ') . $exception->getMessage();
            }

            App::error()->add($msg);
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

        Page::openModule(My::name());

        echo Page::breadcrumb(
            [
                __('System')          => '',
                __('Integrity Check') => '',
            ]
        );
        echo Notices::getNotices();

        // Content
        if (!App::backend()->has_bad_files) {
            echo
            '<h2>' . __('Diagnostics') . '</h2>' .
            '<p class="message">' . __('All your installation files are correct.') . '</p>';
        }

        Page::closeModule();
    }
}
