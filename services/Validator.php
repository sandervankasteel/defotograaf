<?php
/**
 * Remco Schipper
 * Date: 19/11/14
 * Time: 11:45
 */

namespace services;


class Validator extends Service{
    private $rules = array();
    private $data = null;
    private $validators = array(
        'empty' => 'checkEmpty',
        'length' => 'checkLength',
        'numeric' => 'checkNumeric',
        'int' => 'checkInt',
        'string' => 'checkString',
        'email' => 'checkEmail',
        'phone' => 'checkPhone',
        'equal' => 'checkEqual',
        'datetime' => 'checkDatetime'
    );

    /**
     * Checks if a field matches the rules
     * @param string $field
     * @param array $rules
     * @return bool
     */
    private function check($field, $rules) {
        if (isset($this->data[$field])) {
            $data = $this->data[$field];

            foreach ($rules as $rule => $value) {
                if (isset($this->validators[$rule])) {
                    $func = $this->validators[$rule];

                    if ($func === 'checkEmpty' && $value === true && empty($data)) {
                        break;
                    }
                    else {
                        if (!$this->$func($data, $value)) {
                            return false;
                        }
                    }
                }
            }

            return true;
        }
        else {
            return (isset($rules['empty']) && $rules['empty'] === true);
        }
    }

    /**
     * @param $data
     * @param $value
     * @return bool
     */
    private function checkInt($data, $value) {
        return (ctype_digit($data) === $value);
    }

    /**
     * @param $data
     * @param $value
     * @return bool
     */
    private function checkDateTime($data, $value) {
        $d = \DateTime::createFromFormat($value, $data);
        $format = $d->format($value);
        return !$d === false && $format === $data;
    }

    /**
     * Check if a field is empty
     * @param string $data
     * @param string $value
     * @return bool
     */
    private function checkEmpty($data, $value) {
        return ($value === true) ? true : !empty($data);
    }

    /**
     * Check if a field is between or equal to the specified length
     * @param string $data
     * @param string $value
     * @return bool
     */
    private function checkLength($data, $value) {
        if (!is_array($value)) {
            $value = array($value, $value);
        }

        return (strlen($data) >= $value[0] && strlen($data) <= $value[1]);
    }

    /**
     * Check if a field is numeric
     * @param string $data
     * @param string $value
     * @return bool
     */
    private function checkNumeric($data, $value) {
        return (is_numeric($data) === $value);
    }

    /**
     * Check if a field is a string
     * @param string $data
     * @param string $value
     * @return bool
     */
    private function checkString($data, $value) {
        return (is_string($data) === $value);
    }

    /**
     * Check if a field is e-mail address
     * @param string $data
     * @param string $value
     * @return bool
     */
    private function checkEmail($data, $value) {
        if (filter_var($data, FILTER_VALIDATE_EMAIL) === false && $value === true) {
            return false;
        }

        return true;
    }

    /**
     * Check if a field is equal to another field
     * @param string $data
     * @param string $value
     * @return bool
     */
    private function checkEqual($data, $value) {
        return (isset($this->data[$value]) && $data === $this->data[$value]);
    }

    /**
     * Check if a field is a valid phone number (Dutch)
     * @param string $data
     * @param string $value
     * @return bool
     */
    private function checkPhone($data, $value) {
        return (preg_match('/(^\+[0-9]{2}|^\+[0-9]{2}\(0\)|^\(\+[0-9]{2}\)\(0\)|^00[0-9]{2}|^0)([0-9]{9}$|[0-9\-\s]{10}$)/', $data) > 0 && $value === true);
    }

    /**
     * Set all rules (overwrites ALL existing rules)
     * @param array $rules
     */
    public function setRules($rules) {
        $this->rules = $rules;
    }

    /**
     * Set the rules for a specified field (overwrites existing rules for the field)
     * @param string $field
     * @param array $rules
     */
    public function setRulesForField($field, $rules) {
        $this->rules[$field] = $rules;
    }

    /**
     * Add a rule to the list of rules for a specified field
     * @param string $field
     * @param string $rule
     * @param string|int|array $value
     */
    public function addRuleForField($field, $rule, $value) {
        if (!isset($this->rules[$field])) {
            $this->rules[$field] = array();
        }

        $this->rules[$field][$rule] = $value;
    }

    /**
     * Set the data which must be validated
     * @param array $data
     */
    public function setData($data) {
        $this->data = $data;
    }

    /**
     * Run the validator
     * @param null|array $data
     * @return array|bool
     */
    public function validate($data = null) {
        if ($data !== null) {
            $this->setData($data);
        }

        $errors = array();
        foreach ($this->rules as $field => $rules) {
            if (!$this->check($field, $rules)) {
                $t = Text::get($rules['message']);

                if ($t === null) {
                    $t = $rules['message'];
                }

                $errors[$field] = $t;
            }
        }

        return (count($errors) === 0) ? true : $errors;
    }

    /**
     * @param array $data
     * @param array $rules
     * @return array
     */
    public function parsePost($data, $rules) {
        $values = array();

        foreach ($rules as $key => $value) {
            $this->setRulesForField($key, $value);

            if (isset($_POST[$key])) {
                $values[$key] = $_POST[$key];
            }
        }

        $result = $this->validate($values);

        return array('result' => $result, 'values' => $values);
    }

    /**
     * @return Validator
     */
    public static function getInstance() {
        return new self();
    }
}