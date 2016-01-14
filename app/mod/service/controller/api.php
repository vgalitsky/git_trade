<?php
class service_controller_api extends core_controller{


    public function getActivitiesAction(){
        $activity_model = new manage_model_activity();
        /** @var manage_model_activity_collection $ac */
        $ac = $activity_model->getCollection()
                ->activityReferenceForMobile();
        $ac->load();

        $ftmp = fopen('php://output', 'w');
        $activities = $ac->toArray(  );
        foreach($activities as $id=>$activity){
            fputs($ftmp,"{:$id:}{$activity['name']}\n");
        }
        fclose($ftmp);
    }

    public function selectAction(){
        //$hlp = new service_helper_sql();
        $sql = urldecode($this->getRequest()->getParam('select',null));
        service_helper_sql::assertSqlReadonly($sql);
        $stmt = app::getConnection()->prepare($sql);
        $stmt->execute();
        $all = $stmt->fetchAll(pdo::FETCH_ASSOC);
        //echo '<pre>';
        //print_r($all);
        echo service_helper_csv::arraysToCsv($all);
    }

    /**
     * @param $entity
     * @param string $columns
     * @param string $where_field
     * @param null $joins
       e.g. array(
                    array(
                        'type'=>type
                        'table' => table
                        'on' => on conditions
                    )
             )
     */
    public function select( $sql ){

    }



    public function addEventAction(){

        try {
            $eventData = array(
                'imei' => $this->getRequest()->getParam('uID'),
                'activity_id' => $this->getRequest()->getParam('act'),
                'lat' => $this->getRequest()->getParam('lat'),
                'long' => $this->getRequest()->getParam('long'),
                'date' => $this->getRequest()->getParam('time'),
            );

            $img_64 = $this->getRequest()->getParam('img');
            $file_path = app::getConfig("dir/sd") . 'event' . DS . 'img' . DS . date('Y') . DS . date('m') . DS . date('d') . DS . uniqid($eventData['imei'] . '-') . '.jpg';
            core_fs::createDirIfNotExists(dirname($file_path));
            $this->_saveEventImage($file_path, $img_64);

            $eventData['image'] = $file_path;

            $event = new manage_model_event();
            $event->setData($eventData);
            $event->save();
            die('1');
        }catch(Exception $e){
            core_log::logException($e);
            $request = $_REQUEST;
            unset($request['img']);
            core_log::log($request,'events.request.log');
            die('err: see exception log');
        }


    }

    private function _saveEventImage( $filename_path, $base_64_str ){
        $decoded=base64_decode($base_64_str);
        file_put_contents($filename_path,$decoded);
        return $this;
    }

    public function md_AddTradePointAction(){
        core_debug::dump($_REQUEST);
    }



}