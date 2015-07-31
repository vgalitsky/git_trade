<?php
class manage_model_event extends core_model{
    public function __construct(){
        parent::__construct('event','event_id');
    }

    public function _afterLoad(){
        $this->setData('title',$this->getData('user_fullname').' ('.$this->getData('city_name').') '.date('d.m.Y H:i', $this->getData('date')));
        $this->setData('img','<img src="'.app::getUrl($this->getData('image')).'" width="350px"/>');
        /** @var map_block_map_marker_content $content_block */
        $content_block = new map_block_map_marker_content();
        $content_block->setVar('event',$this);
        $this->setData('content', $content_block->getHtml() );
        return $this;
    }

    public function emailGrab(){
        $inbox = imap_open('{imap.gmail.com:993/imap/ssl}INBOX', 'f.estelkontrol@gmail.com', 'f.estelkontrol07');

        /* get all new emails. If set to 'ALL' instead
        * of 'NEW' retrieves all the emails, but can be
        * resource intensive, so the following variable,
        * $max_emails, puts the limit on the number of emails downloaded.
        *
        */
        $emails = imap_search($inbox, 'UNSEEN');

        /* useful only if the above search is set to 'ALL' */
        $max_emails = 16;


        /* if any emails found, iterate through each email */
        if ($emails) {

            $count = 1;

            /* put the newest emails on top */
            rsort($emails);

            /* for every email... */
            foreach ($emails as $email_number) {

                /* get information specific to this email */
                $overview = imap_fetch_overview($inbox, $email_number, 0);


                /* get mail structure */
                $structure = imap_fetchstructure($inbox, $email_number);


                $attachments = array();

                /* if any attachments found... */
                if (isset($structure->parts) && count($structure->parts)) {
                    for ($i = 0; $i < count($structure->parts); $i++) {

                        $bodyText = imap_fetchbody($inbox,$email_number,1.2);
                        if(!strlen($bodyText)>0){
                            $bodyText = imap_fetchbody($inbox,$email_number,1);
                        }
                        $hinfo = imap_headerinfo($inbox,$email_number);
                        $subject = $hinfo->subject;


                        $attachments[$i] = array(
                            'email_number'=>$email_number,
                            'is_attachment' => false,
                            'date' => $hinfo->date,
                            'filename' => '',
                            'name' => '',
                            'attachment' => '',
                            'subject' => imap_utf8($subject),
                            'csv' => imap_utf8($bodyText),
                        );

                        if ($structure->parts[$i]->ifdparameters) {
                            foreach ($structure->parts[$i]->dparameters as $object) {
                                if (strtolower($object->attribute) == 'filename') {
                                    $attachments[$i]['is_attachment'] = true;
                                    $attachments[$i]['filename'] = $object->value;
                                }
                            }
                        }

                        if ($structure->parts[$i]->ifparameters) {
                            foreach ($structure->parts[$i]->parameters as $object) {
                                if (strtolower($object->attribute) == 'name') {
                                    $attachments[$i]['is_attachment'] = true;
                                    $attachments[$i]['name'] = $object->value;
                                }
                            }
                        }

                        if ($attachments[$i]['is_attachment']) {
                            $attachments[$i]['attachment'] = imap_fetchbody($inbox, $email_number, $i + 1);

                            /* 3 = BASE64 encoding */
                            if ($structure->parts[$i]->encoding == 3) {
                                $attachments[$i]['attachment'] = base64_decode($attachments[$i]['attachment']);
                            } /* 4 = QUOTED-PRINTABLE encoding */
                            elseif ($structure->parts[$i]->encoding == 4) {
                                $attachments[$i]['attachment'] = quoted_printable_decode($attachments[$i]['attachment']);
                            }
                        }
                    }
                }

                /* iterate through each attachment and save it */
                foreach ($attachments as $attachment) {
                    if ($attachment['is_attachment'] == 1) {
                        core_log::log( "START: Processing email [{$attachment['email_number']}]: {$attachment['subject']} ({$attachment['date']})" ,'event.imap.log');
                        $filename = $attachment['name'];
                        if (empty($filename)) $filename = $attachment['filename'];

                        if (empty($filename)) $filename = time() . ".dat";

                        /* prefix the email number to the filename in case two emails
                         * have the attachment with the same file name.
                         */
                        try {
                            $path = app::getConfig("dir/sd") . "event" . DS . "img"
                                . DS . uniqid(time() . '-') . "-" . $filename;
                            core_fs::createDirIfNotExists(dirname($path));
                            $fp = fopen($path, "w+");
                            fwrite($fp, $attachment['attachment']);
                            fclose($fp);

                            $attachment['full_filename'] = $path;
                            $event = $this->_email2event($attachment);
                            core_log::log( "DONE: ID={$event->getId()} ({$path})" ,'event.imap.log');
                        }catch(Exception $e){
                            core_log::log( "ERROR: {$e->getMessage()}" ,'event.imap.log');
                        }

                    }

                }

                if ($count++ >= $max_emails) break;
            }

        }
        imap_close($inbox);
    }

    protected function _email2event( $email ){

        $bodyData = $this->_pareseEmailBodyCsv( $email['csv'] );

        $eventData = array_merge(
            array(
            'image' => app::getUrl($email['full_filename']),
            ),
            $bodyData
        );

        $event = new manage_model_event();
        $event->setData($eventData)
            ->save();
        return $event;
    }

    protected function _pareseEmailBodyCsv($csv){
        $csv = strip_tags(html_entity_decode(trim(str_replace("\n",'',str_replace("\r",'',$csv)))));
        $array = str_getcsv($csv,';');

        $lat = $array[1];
        $long = $array[2];

        $data = array(
            'imei'      => $array[0],
            'lat'       => $lat,
            'long'      => $long,
            'date'      => $array[3],
            'activity_id'  => $array[4],//$this->_getActivityIdByName( $array[4] ),
            'city_id'  => $this->_getCityIdByName( core_google_geo::findCityName($lat, $long) ),
        );

        $user = new manage_model_user();
        $user->load($data['imei'],'imei');
        $data['user_id']=$user->getId();

        return $data ;
    }

    protected function _beforeSave(){
        parent::_beforeSave();
        if(!$this->getData('activity_id')){
          throw new Exception('Activity is empty');
        }
        if(!$this->getData('city_id')){
          throw new Exception('City is empty');
        }
        if(!$this->getData('user_id')){
          throw new Exception('User is empty');
        }
        if(!$this->getData('date')){
          throw new Exception('Date is empty');
        }
        if(!$this->getData('lat')){
          throw new Exception('Lat is empty');
        }
        if(!$this->getData('long')){
          throw new Exception('Long is empty');
        }
        return $this;
    }



    protected function _getActivityIdByName( $activity_name ){
        $activity_model = new manage_model_activity();
        $activity_model->load(strtolower($activity_name),'name');
        if(!$activity_model->getId()){
            $activity_model->setData('name',strtolower($activity_name))
                ->save();
        }
        return $activity_model->getId();
    }

    protected function _getCityIdByName( $city_name ){
        $activity_model = new manage_model_city();
        $activity_model->load(strtolower($city_name),'name');
        if(!$activity_model->getId()){
            $activity_model->setData('name',$city_name)
                ->save();
        }
        return $activity_model->getId();
    }

}


