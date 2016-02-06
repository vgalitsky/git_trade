<?php
class service_controller_api extends core_controller{


    /**
     * 
     */
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
    public function getCompetitorsAction(){
        $competitor_model = new manage_model_competitor();
        /** @var manage_model_competitor_collection $cc */
        $cc = $competitor_model->getCollection()
                ->competitorReferenceForMobile();
        $cc->load();

        $ftmp = fopen('php://output', 'w');
        $competitors = $cc->toArray(  );
        foreach($competitors as $id=>$competitor){
            fputs($ftmp,"{:$id:}{$competitor['name']}\n");
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
                'date' => $this->_applyDate( $this->getRequest()->getParam('time') ),
            );

            $img_64 = $this->getRequest()->getParam('img');
            $uniqid = uniqid($eventData['imei'] . '-');
            $file_path = app::getConfig("dir/sd") . 'event' . DS . 'img' . DS . date('Y') . DS . date('m') . DS . date('d') . DS . $uniqid . '.jpg';
            $file_url = 'sd' .DS. 'event' . DS . 'img' . DS . date('Y') . DS . date('m') . DS . date('d') . DS . $uniqid . '.jpg';
            core_fs::createDirIfNotExists(dirname($file_path));
            $this->_saveRequestImage($file_path, $img_64);

            $eventData['image'] = $file_url;

            $event = new manage_model_event();
            $event->setData($eventData);
            $event->save();
            core_log::log($event->getData(),'event/'.date('d-m-Y').'events.added.log');
            $this->_xhrOk();

        }catch(Exception $e){

            $request = $_REQUEST; unset($request['img']); $request['img'] = $file_path;

            core_log::logException($e);
            core_log::log($request,'event/'.date('d-m-Y').'events.request.log');
            core_log::log($e->getMessage(),'event/'.date('d-m-Y').'events.error.log');

            $this->_xhrErr('err: see exception log');
        }


    }

    protected function _xhrOk(){
        die('{{:ok:}}');
    }
    protected function _xhrErr( $err ){
        die($err);
    }


    protected function _applyDate( $time ){
        if(strlen($time) > 10){
            return substr($time,0,10);
        }
        return $time;
    }

    protected function _saveRequestImage( $filename_path, $base_64_str ){
        //core_log::log($base_64_str,$filename_path.'.log');

        $base_64_str = str_replace("\n",'',$base_64_str);
        $base_64_str = str_replace("\r",'',$base_64_str);
        $base_64_str = str_replace(" ",'+',$base_64_str);

        //core_log::log($base_64_str,$filename_path.'.stripped.log');

        $decoded=base64_decode($base_64_str);
        file_put_contents($filename_path,$decoded);
        return $this;
    }

    public function addTradePointAction(){
        try {
            $tpData = array(
                'name' => $this->getRequest()->getParam('spn'),
                'address' => $this->getRequest()->getParam('adr'),
                'long' => $this->getRequest()->getParam('long'),
                'lat' => $this->getRequest()->getParam('lat'),
                'contact_person ' => $this->getRequest()->getParam('con'),
                'phone' => $this->getRequest()->getParam('tel'),
                'personal_count' => $this->getRequest()->getParam('per'),
                'rate' => $this->getRequest()->getParam('ppr'),
            );

            $img_64 = $this->getRequest()->getParam('img');
            $uniqid = uniqid('tp');
            $file_path = app::getConfig("dir/sd") . 'tp' . DS . 'img' . DS . date('Y') . DS . date('m') . DS . date('d') . DS . $uniqid . '.jpg';
            $file_url = 'sd' .DS. 'tp' . DS . 'img' . DS . date('Y') . DS . date('m') . DS . date('d') . DS . $uniqid . '.jpg';
            core_fs::createDirIfNotExists(dirname($file_path));
            $this->_saveRequestImage($file_path, $img_64);

            $tpData['image'] = $file_url;

            $trade_point = new manage_model_tradepoint();
            $trade_point->setData($tpData);
            $trade_point->save();

            core_log::log($trade_point->getData(),'tp/'.date('d-m-Y').'trade_points.added.log');

            $request = $_REQUEST; unset($request['img']); $request['img'] = $file_path;
            core_log::log($request,'tp/'.date('d-m-Y').'trade_point.request.log');

            $this->_xhrOk();


        }catch(Exception $e){

            $request = $_REQUEST; unset($request['img']); $request['img'] = $file_path;

            core_log::logException($e);
            core_log::log($request,'tp/'.date('d-m-Y').'trade_point.request.log');
            core_log::log($e->getMessage(),'tp/'.date('d-m-Y').'trade_point.error.log');

            $this->_xhrErr('err: see exception log');
        }

    }



}