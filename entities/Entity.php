<?php
/**
 * Remco Schipper
 * Date: 14/11/14
 * Time: 10:44
 */

namespace entities;

use exceptions\database\Prepare;
use services\Database;

class Entity {
    public static $id_field = null;

    public function __construct($data = null) {
        if ($data !== null) {
            $this->populate($data);
        }
    }

    /**
     * Populates the entity values from an array
     * @param array $data
     */
    public function populate($data) {
        /** @var static $class */
        $class = get_called_class();

        foreach ($data as $key => $value) {
            if (property_exists($class, $key)) {
                $this->$key = $value;
            }
        }
    }

    /**
     * Executes an prepared statement
     * @param string $query
     * @param array $values
     * @param array $types
     * @return \mysqli_stmt
     * @throws Prepare
     */
    private function stmtQuery($query, $values, $types) {
        $database = Database::get();

        if (!($stmt = $database->prepare($query))) {
            throw new Prepare($database->error, $database->errno);
        }

        $referenced = array();
        array_unshift($values, $types);

        for ($i = 0; $i < count($values); $i++) {
            $referenced[$i] = &$values[$i];
        }

        call_user_func_array(array($stmt, 'bind_param'), $referenced);

        if (!$stmt->execute()) {
            throw new Prepare($stmt->error, $stmt->errno);
        }

        return $stmt;
    }

    /**
     * Prepares the fields and values for the statement
     * @return array
     */
    private function stmtPrepare() {
        $reflect = new \ReflectionClass(get_called_class());
        $fields = $reflect->getProperties(\ReflectionProperty::IS_PROTECTED);
        $names = array();
        $values = array();
        $types = '';

        foreach ($fields as $field) {
            $name = $field->getName();

            if ($this->$name !== null) {
                $names[] = $name;
                $values[] = $this->$name;

                if (is_int($this->$name)) {
                    $types .= 'i';
                }
                else {
                    $types .= 's';
                }
            }
        }

        return array('names' => $names, 'values' => $values, 'types' => $types);
    }

    /**
     * Saves an entity to the database
     * @throws Prepare
     * @return int
     */
    public function save() {
        $data = $this->stmtPrepare();

        $query = sprintf('INSERT INTO %s (%s) VALUES (%s)',
            $this::$table,
            implode(',', $data['names']),
            substr(str_repeat('?,', count($data['values'])), 0, -1));

        $stmt = $this->stmtQuery($query, $data['values'], $data['types']);
        $this->setId($stmt->insert_id);
    }

    /**
     * Updates an entity by the id
     * @param null|array $null
     * @throws Prepare
     */
    public function update($null = array()) {
        $data = $this->stmtPrepare();
        $fields = array();

        foreach($data['names'] as $name) {
            $fields[] = $name . '=?';
        }
        foreach($null as $name) {
            $fields[] = $name . '=?';
            $data['values'][] = null;
            $data['types'] .= 's';
        }

        $field = $this::getIdField();
        $id = $this->getId();

        if (is_string($id)) {
            $data['types'] .= 's';
        }
        else {
            $data['types'] .= 'i';
        }

        $data['values'][] = $id;

        $fields = implode(', ', $fields);

        $query = sprintf('UPDATE %s SET %s WHERE %s=?', $this::$table, $fields, $field);

        $this->stmtQuery($query, $data['values'], $data['types']);
    }

    /**
     * @throws Prepare
     */
    public function delete() {
        $query = sprintf('DELETE FROM %s WHERE %s=?', $this::$table, $this->getIdField());

        $this->stmtQuery($query, array($this->getId()), 'i');
    }

    /**
     * Returns the id based on the class name
     * @return int
     */
    public function getId() {
        $field = $this::getIdField();

        return $this->$field;
    }

    /**
     * Set the id based on the database value
     * @param int $id
     */
    private function setId($id) {
        $field = $this->getIdField();

        $this->$field = $id;
    }

    /**
     * @return string
     */
    public static function getIdField() {
        $class = get_called_class();

        if ($class::$id_field === null) {
            return strtolower(end(explode('\\', $class))) . '_id';;
        }

        return $class::$id_field;
    }

    /**
     * Find a record by it's id
     * @param string|int $id
     * @return static
     */
    public static function getById($id) {
        /** @var Entity $class */
        $class = get_called_class();
        $field = $class::getIdField();

        return $class::getBy($field, $id, true);
    }

    /**
     * @return static[]|null
     * @throws Prepare
     */
    public static function getAll() {
        $class = get_called_class();
        $query = sprintf('SELECT * FROM %s', $class::$table);

        return self::getByQuery($query);
    }

    /**
     * @param $query
     * @return static[]|null
     * @throws Prepare
     * @return static|static[]
     */
    public static function getByQuery($query, $values = array(), $types = '') {
        $class = get_called_class();
        $database = Database::get();

        if (!($stmt = $database->prepare($query))) {
            throw new Prepare($database->error, $database->errno);
        }

        if (count($values) > 0) {
            $referenced = array();
            array_unshift($values, $types);

            for ($i = 0; $i < count($values); $i++) {
                $referenced[$i] = &$values[$i];
            }

            call_user_func_array(array($stmt, 'bind_param'), $referenced);
        }

        if (!$stmt->execute()) {
            throw new Prepare($stmt->error, $stmt->errno);
        }

        $return = null;

        $result = $stmt->get_result();
        while ($row = $result->fetch_array(MYSQLI_ASSOC)) {
            if ($return === null) {
                $return = array();
            }
            $return[] = new $class($row);
        }

        return $return;
    }

    /**
     * Find a record or multiple records by the specified field
     * @param string $field
     * @param string|int $value
     * @param bool $one
     * @throws Prepare
     * @return static|static[]
     */
    public static function getBy($field, $value, $one = false) {
        /** @var static $class */
        $class = get_called_class();
        $database = Database::get();
        $query = sprintf('SELECT * FROM %s WHERE %s=?', $class::$table, $field);

        if (!($stmt = $database->prepare($query))) {
            throw new Prepare($database->error, $database->errno);
        }

        if (!$stmt->bind_param(((is_string($value)) ? 's' : 'i'), $value)) {
            throw new Prepare($stmt->error, $stmt->errno);
        }

        if (!$stmt->execute()) {
            throw new Prepare($stmt->error, $stmt->errno);
        }

        $return = null;

        $result = $stmt->get_result();
        while ($row = $result->fetch_array(MYSQLI_ASSOC)) {
            if ($one === true) {
                return new $class($row);
            }
            else {
                if ($return === null) {
                    $return = array();
                }
                $return[] = new $class($row);
            }
        }

        return $return;
    }
} 