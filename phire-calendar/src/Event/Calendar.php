<?php

namespace Phire\Calendar\Event;

use Phire\Calendar\Model;
use Pop\Application;
use Phire\Controller\AbstractController;

class Calendar
{

    /**
     * Parse calendar
     *
     * @param  AbstractController $controller
     * @param  Application        $application
     * @return void
     */
    public static function parse(AbstractController $controller, Application $application)
    {
        if ((!$_POST) && ($controller->hasView())) {
            if (($application->isRegistered('phire-templates')) && ($controller->view()->isStream()) &&
                (strpos($controller->view()->getTemplate()->getTemplate(), '[{calendar_') !== false)) {
                $template = $controller->view()->getTemplate()->getTemplate();
                $id       = substr($template, (strpos($template, '[{calendar_') + 11));
                $calendar = new Model\Calendar(['date' => $controller->request()->getQuery('date')]);
                $template = str_replace(
                    '[{calendar_' . $id . '}]',
                    $calendar->getById($id),
                    $template
                );
                $controller->view()->getTemplate()->setTemplate($template);
            } else if (($controller instanceof \Phire\Content\Controller\IndexController) && ($controller->view()->isFile())) {
                $controller->view()->phire->calendar = new Model\Calendar(['date' => $controller->request()->getQuery('date')]);
            }
        }
    }

}
