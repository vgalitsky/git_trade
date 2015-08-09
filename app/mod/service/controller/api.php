<?php
class service_controller_api extends core_controller{


    public function getActivitiesAction(){
        $activity_model = new manage_model_activity();
        /** @var manage_model_activity_collection $ac */
        $ac = $activity_model->getCollection()
                ->activityReferenceForMobile();
        $ac->load();

        $ftmp = fopen('php://output', 'w');
        $activities = $ac->toArray( false );
        foreach($activities as $activity){
            fputcsv($ftmp,$activity);
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


}