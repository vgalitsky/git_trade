<?php
class service_controller_api extends core_controller{


    public function getActivitiesAction(){

    }

    public function selectAction(){
        //$hlp = new service_helper_sql();
        $sql = urldecode($this->getRequest()->getParam('select',null));
        service_helper_sql::assertSqlReadonly($sql);
        $stmt = app::getConnection()->prepare($sql);
        $stmt->execute();
        $all = $stmt->fetchAll();
        echo '<pre>';
        print_r($all);
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