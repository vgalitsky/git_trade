<?php
class manage_controller_Tradepoint extends manage_controller_manage
{

    public function getAllTradePointsJsonAction()
    {
        try {
            $filter = $this->getRequest()->getParam('filter');
            /** @var manage_model_event_collection $events */
            $tradepoints = app::getModel('manage_model_tradepoint')->getCollection();


            $tradepoints->load();
            $tradepoints_array = $tradepoints->toArray();
            $json = self::okJson($tradepoints_array);
        } catch (Exception $e) {
            $json = self::errJson($e->getMessage());
        }
        echo $json;
    }

}