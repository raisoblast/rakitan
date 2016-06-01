<?php
namespace Model;

use Illuminate\Database\Eloquent\Model;
use Valitron\Validator;

class BaseModel extends Model
{
    protected $rules = array();
    protected $labels = array();
    protected $errors;

    /**
     * validasi
     * @param array $data POST data
     * @return boolean
     */
    public function validate($data)
    {
        $v = new Validator($data, [], 'id');
        foreach ($this->rules as $rule => $columns) {
            $v->rule($rule, $columns);
        }
        $v->labels($this->labels);
        if ($v->validate()) {
            return true;
        } else {
            $this->errors = $v->errors();
            return false;
        }
    }

    public function errors()
    {
        return $this->errors;
    }

    public function getErrors()
    {
        $result = '';
        if (!is_array($this->errors)) {
            return $result;
        }
        foreach ($this->errors as $errs) {
            foreach ($errs as $err) {
                $result .= $err . '<br/>';
            }
        }
        return $result;
    }
}
