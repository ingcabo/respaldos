<?php

class Model_crpvolumen extends My_model
{

   protected $_table = 'volumen';
   protected $primary_key = 'id';
   protected $return_type = 'array';

   protected $after_get = array('remove_sensite_data');
   protected $before_create = array('prep_data');
   protected $before_update = array('update_timestamp');

   public function get_manyby()
   {
       $criterio['active'] = 1;
       $data               = $this->get_many_by($criterio);
       return $data;
   }

   protected function remove_sensite_data($arg)
   {
       unset($arg['active']);
       return $arg;
   }

}
