<?php
/**
 * Created by PhpStorm.
 * User: Joey
 * Date: 27-11-2014
 * Time: 13:11
 */

namespace entities;


class StartingCost extends Entity
{
    protected $starting_costs_id = null;
    protected $price = null;

    public static $table = 'starting_costs';
    public static $id_field = 'starting_costs_id';

    /**
     * @return int
     */
    public function getPrice()
    {
        return $this->price;
    }

    /**
     * @return static
     * @throws \exceptions\database\Prepare
     */
    public static function getActive() {
        /** @var static $class */
        $class = get_called_class();
        $query = sprintf('SELECT * FROM %s ORDER BY starting_costs_id DESC LIMIT 1', $class::$table);

        $result = $class::getByQuery($query);

        return $result[0];
    }
}