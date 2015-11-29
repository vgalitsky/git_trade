<?php
class manage_model_user extends core_model_user
{

    public function getManagerChildrenCollection(){
        $collection = $this->getCollection();
        $collection->addParentUserFilter( $this->getId() );
        return $collection;
    }

    public function getEventsCount( $from, $to ){

        $event = new manage_model_event();
        $event_collection = $event->getCollection();
        $sql = $event_collection->getSql();
        if(!is_numeric($from)){
            $from = strtotime($from);
            $to = strtotime($to);

        }
        $sql .= ' AND event.user_id=:user_id AND date>=:date_from AND date<=:date_to';

        $event_collection->setSql( $sql );

        $event_collection->setSqlValue('date_from',$from);
        $event_collection->setSqlValue('date_to',$to);
        $event_collection->setSqlValue('user_id',$this->getId());
        $event_collection->load();
        return $event_collection->count();
    }

    public function getWoParentCollection(){
        $collection = $this->getCollection();
        $sql = $collection->getSql();
        $sql .= ' AND user.parent_id IS NULL';
        $collection->setSql($sql);
        return $collection;
    }
}