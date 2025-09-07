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
use Dotclear\Core\Upgrade\Update;
use Dotclear\Helper\Html\Form\Li;
use Dotclear\Helper\Html\Form\Note;
use Dotclear\Helper\Html\Form\Set;
use Dotclear\Helper\Html\Form\Text;
use Dotclear\Helper\Html\Form\Ul;
use Dotclear\Helper\Process\TraitProcess;
use Exception;

class Manage
{
    use TraitProcess;

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
            $bad_files = App::backend()->updater->getBadFiles();
            if (count($bad_files) > 0) {
                App::backend()->has_bad_files = true;

                $msg = (new Set())
                    ->items([
                        (new Text(null, __('The following files differ from your initial dotclear installation :'))),
                        (new Ul())
                            ->items(array_map(fn ($bad) => (new Li())->text($bad), $bad_files)),
                    ]);
            } else {
                $msg = (new Text(null, __('An unexpected error occured : ') . $exception->getMessage()));
            }

            App::error()->add($msg->render());
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
        if (!App::error()->flag() && !App::backend()->has_bad_files) {
            echo (new Set())
                ->items([
                    (new Note())
                        ->class('message')
                        ->text(__('All your installation files are correct.')),
                ])
            ->render();
        }

        Page::closeModule();
    }
}
