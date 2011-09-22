<?php  if ( ! defined('BASEPATH')) exit('No direct script access allowed');
/**
 * CodeIgniter
 *
 * An open source application development framework for PHP 5.1.6 or newer
 *
 * @package		CodeIgniter
 * @author		ExpressionEngine Dev Team
 * @copyright	Copyright (c) 2008 - 2011, EllisLab, Inc.
 * @license		http://codeigniter.com/user_guide/license.html
 * @link		http://codeigniter.com
 * @since		Version 1.0
 * @filesource
 */

// ------------------------------------------------------------------------

/**
 * CodeIgniter Model Class
 *
 * @package		CodeIgniter
 * @subpackage	Libraries
 * @category	Libraries
 * @author		ExpressionEngine Dev Team
 * @link		http://codeigniter.com/user_guide/libraries/config.html
 */
class StudyMonkey_Model extends CI_Model
{
    protected $TABLE_NAME;

    protected $general_db_fields = array ('id', 'date_created', 'date_modified');

    function __construct()
    {
        parent::__construct();
    }

	function __isset($key)
	{
		$CI =& get_instance();
		return isset($CI->$key);
	}

    public function find_all()
    {
        $query = $this->db->get($this->TABLE_NAME);
        return $query->result();
    }

    public function find_by_id($id)
    {
        $query = $this->db->get_where($this->TABLE_NAME, array('id' => $id));
        return $query->row_array();
    }

    public function compare($array_1, $array_2)
    {
        foreach($this->general_db_fields as $field) {
            if($array_1[$field] != $array_2[$field])
                return false;
        }
        return true;
    }

    protected function create_field_array($model_array)
    {
        $db_array = array();
        foreach($this->db_fields as $field) {
            if (isset($model_array[$field]))
                $db_array[$field] = $model_array[$field];
        }

        return $db_array;
    }

    public function save(&$model_array)
    {
        $db_array = self::create_field_array($model_array);

       if (isset($model_array['id']))
       {
            return $this->db->update($this->TABLE_NAME, $db_array, array('id' => $model_array['id']));
       }
       else
       {
            $db_array['date_created'] = date('Y-m-d H:i:s');
            $this->db->insert($this->TABLE_NAME, $db_array);
            $model_array['id'] = $this->db->insert_id();
            return $model_array;
        }
    }

    public function delete(&$model_array)
    {
        $this->db->where('id', $model_array['id']);
        $this->db->delete($this->TABLE_NAME);
    }
}


/* End of file StudyMonkey_Model.php */
/* Location: ./application/core/StudyMonkey_Model.php */