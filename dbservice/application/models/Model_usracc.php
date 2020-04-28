<?php
class Model_usrAcc extends My_model
{
    protected $_table = 'access';
    protected $primary_key = 'id';
    protected $return_type = 'array';
    protected $after_get = array('remove_sensite_data');
    protected $before_create = array('prep_data');
    protected $before_update = array('update_timestamp');
    //al consultar la data no deberiamos mostrar la contraseña
    protected function remove_sensite_data($usracc)
    {
        //    unset($metodo['password']);
        unset($usracc['key']);
        unset($usracc['status']);
        unset($usracc['register_date']);
        unset($usracc['update_timestamp']);
        unset($usracc['date_created']);
        unset($usracc['date_modified']);
        return ($usracc);
    }
    protected function prep_data($usracc)
    {
        if (isset($usracc['password'])) {
            $usracc['password'] = md5($usracc['password']);
        }
        $usracc['controller']    = str_replace("/", "", $usracc['controller']);
        $usracc['controller']    = "/" . $usracc['controller'];
        $usracc['register_date'] = date('Y-m-d H:i:s');
        return $usracc;
    }
    protected function update_timestamp($usracc)
    {
        $usracc['update_timestamp'] = date('Y-m-d H:i:s');
        if (isset($usracc['controller'])) {
            $usracc['controller'] = str_replace("/", "", $usracc['controller']);
            $usracc['controller'] = "/" . $usracc['controller'];
        }
        if (isset($usracc['password'])) {
            $usracc['password'] = md5($usracc['password']);
        }
        return $usracc;
    }
    function verifica_metodo_update_exist($control, $funcion, $metodo, $id, $user_id)
    {
        $query = $this->db->query("SELECT count(*) as count FROM access
                WHERE controller =  '/" . $control . "'  and FUNCTION ='" . $funcion . "' and metodo = '" . $metodo . "' and id <>  '" . $id . "'  and status= 'active'  and user_id = " . $user_id);
        $cant  = $query->row('count');
        return $cant;
    }
    function update_key($id, $data)
    {
        $dato = array(
            "key" => $data
        );
        //$this->db->trans_start();
        $this->db->where('user_id', $id);
        $this->db->update('access', $dato);
        //$this->db->trans_complete();
        return $this->db->affected_rows();
    }
}
?>
