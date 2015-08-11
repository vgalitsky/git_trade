<?php
class manage_controller_event extends manage_controller_manage
{

    public function getAllEventsJsonAction()
    {
        try {
            $filter = $this->getRequest()->getParam('filter');
            /** @var manage_model_event_collection $events */
            $events = app::getModel('manage_model_event')->getCollection();

            $events->prepareFilter($filter);
            $events->load();
            $events_array = $events->toArray();
            $json = self::okJson($events_array);
        } catch (Exception $e) {
            $json = self::errJson($e->getMessage());
        }
        echo $json;
    }

    public function grabEventsFromEmailAction(){
        //echo phpinfo();
        $m = new  manage_model_event();
        $m->emailGrab();
        core_debug::dump($m);
        core_debug::dump(imap_errors());
        die('at ctrl');
    }

    public function deleteAction(){
        $event_id = $this->getRequest()->getParam('event_id');
        $event = new manage_model_event();
        $event->load($event_id);
        $event->delete();
    }

}