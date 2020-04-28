<?php
class Model_usr extends My_model
{
    protected $_table = 'users_api';
    protected $primary_key = 'id';
    protected $return_type = 'array';
    protected $after_get = array('remove_sensite_data');
    protected $before_create = array('prep_data');
    protected $before_update = array('update_timestamp');
    //al consultar la data no deberiamos mostrar la contraseña
    protected function remove_sensite_data($usr)
    {
        unset($usr['password']);
        if (isset($usr['pre'])) {
            unset($usr['respuesta1']);
            unset($usr['email']);
        }
        return ($usr);
    }
    protected function prep_data($usr)
    {
        if (isset($usr['password'])) {
            $usr['password'] = md5($usr['password']);
        }
        $usr['register_date'] = date('Y-m-d H:i:s');
        return $usr;
    }
    protected function update_timestamp($usr)
    {
        $usr['update_timestamp'] = date('Y-m-d H:i:s');
        if (isset($usr['password'])) {
            $usr['password'] = md5($usr['password']);
        }
        return $usr;
    }
    public function consulta_usuario($key)
    {
        $query  = $this->db->query('SELECT *  FROM keys_app k INNER JOIN users_api u ON k.user_id = u.id WHERE k.key_id = "' . $key . '" AND u.status = "active"');
        $result = $query->row_array();
        return $result;
    }
    public function llave_user($key)
    {
        if ($this->config->item('rest_enable_keys') == true) {
            $query  = $this->db->query('SELECT user_id, level FROM  keys_app WHERE key_id ="' . $key . '"  AND STATUS = "active" LIMIT 1');
            $result = $query->row_array();
        } else {
            $result = array(
                'level' => 0,
                'user_id' => 0
            );
        }
        return $result;
    }
}
?>
