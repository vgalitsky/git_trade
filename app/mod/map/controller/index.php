<?php
class map_controller_index extends map_controller_requirelogin{


    public function activityAction(){
        $this->initLayout();
        /** @var core_block_page $page */
        $page = $this->getLayout()->getPageBlock();
        $page->addChild('head',new map_block_head());
        $map = new map_block_map();
        $map->addChild('controls', new map_block_map_controls());
        $map->addChild('toolbar', new map_block_map_toolbar());
        $this->getLayout()->getPageBlock()->addChild('root', $map);
        //core_debug::dump($this->getLayout());

        $this->renderLayout();

    }

    public function rndAction(){
        for($i = 0; $i<100; $i++){
            $lt = rand(1,10);
            $lng = rand(1,10);
            $a = rand(1,3);
            $u = rand(24,27);
            $c = rand(1,4);
            $e = new manage_model_event();
            $data = array(
                'lat' => 49.26+$lt/10,
                'long' => 27.69+$lng/10,
                'user_id' => $u,
                'activity_id'=> $a,
                'city_id'=> $c,
                'date' => strtotime(rand(1,29).'.'.rand(1,7).'.'.'2015'),
                'marker_color' => '#efefef',

            );
            $e->setData($data);
            core_debug::dump($e);
            $e->save();
        }
    }
}