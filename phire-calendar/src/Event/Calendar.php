<?php

namespace Phire\Calendar\Event;

use Phire\Calendar\Model;
use Pop\Application;
use Phire\Controller\AbstractController;

class Calendar
{

    /**
     * Init calendar object
     *
     * @param  AbstractController $controller
     * @param  Application        $application
     * @return void
     */
    public static function init(AbstractController $controller, Application $application)
    {
        if ((!$_POST) && ($controller->hasView()) &&
            ($controller instanceof \Phire\Content\Controller\IndexController) && ($controller->view()->isFile())) {
            $sess   = $application->services()->get('session');
            $roleId = (isset($sess->user)) ? $sess->user->role_id : null;

            $controller->view()->phire->calendar = new Model\Calendar([
                'user_role_id' => $roleId,
                'weekdays'     => $application->module('phire-calendar')['weekdays'],
                'range'        => $application->module('phire-calendar')['range'],
                'range_format' => $application->module('phire-calendar')['range_format'],
                'date'         => $controller->request()->getQuery('date')
            ]);
        }
    }

    /**
     * Parse calendar
     *
     * @param  AbstractController $controller
     * @param  Application        $application
     * @return void
     */
    public static function parse(AbstractController $controller, Application $application)
    {
        if ((!$_POST) && ($controller->hasView()) &&
            ($controller instanceof \Phire\Content\Controller\IndexController)) {
            $body = $controller->response()->getBody();

            // Parse any calendar placeholders
            $calendars = [];
            $calendarIds = [];

            preg_match_all('/\[\{calendar.*\}\]/', $body, $calendars);
            if (isset($calendars[0]) && isset($calendars[0][0])) {
                foreach ($calendars[0] as $calendar) {
                    $id = substr($calendar, (strpos($calendar, '[{calendar_') + 11));

                    if ((strpos($id, '_') !== false)) {
                        $id = substr($id, 0, strpos($id, '_'));
                        $replace = '[{calendar_' . $id . '_time}]';
                        $time = true;
                    } else {
                        $id = substr($id, 0, strpos($id, '}]'));
                        $replace = '[{calendar_' . $id . '}]';
                        $time = false;
                    }

                    $calendarIds[] = [
                        'id' => $id,
                        'replace' => $replace,
                        'time' => $time
                    ];
                }
            }

            if (count($calendarIds) > 0) {
                $sess = $application->services()->get('session');
                $roleId = (isset($sess->user)) ? $sess->user->role_id : null;

                foreach ($calendarIds as $cal) {
                    $calendar = new Model\Calendar([
                        'user_role_id' => $roleId,
                        'weekdays'     => $application->module('phire-calendar')['weekdays'],
                        'range'        => $application->module('phire-calendar')['range'],
                        'range_format' => $application->module('phire-calendar')['range_format'],
                        'date'         => $controller->request()->getQuery('date')
                    ]);
                    $rendered = $calendar->getById($cal['id'], $cal['time']);
                    $body = str_replace(
                        $cal['replace'],
                        $rendered,
                        $body
                    );
                }

                $controller->response()->setBody($body);
            }
        }
    }

}
