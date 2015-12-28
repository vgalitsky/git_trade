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

    public function saveEventsAction(){
        $events = $this->getRequest()->getParam('events',null);
        core_debug::dump($events);
    }

    public function saveEventsExampleAction(){
        $event = new manage_model_event();
        $events = $event->getCollection();
        $i=0;
        $evnts = array();
        foreach($events as $event){
            $i++;
            if($i>10) break;
            $event->setData(array(
                'lat' => $event->getData('lat'),
                'long' => $event->getData('long'),
                'imei' => $event->getData('imei'),
                'date' => $event->getData('date'),
                'img' => 'image content here',
            ));
            $evnts[]=$event->getData();
        }
        core_debug::dump($evnts);
    }


}