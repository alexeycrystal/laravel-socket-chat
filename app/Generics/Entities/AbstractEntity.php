<?php


namespace App\Generics\Entities;


use phpDocumentor\Reflection\Types\Mixed_;

/**
 * Class AbstractEntity
 * @package App\Generics\Entities
 */
class AbstractEntity
{
    /**
     * AbstractEntity constructor.
     * @param array $data generated from eloquent->toArray()
     */
    public function __construct($data = null)
    {
        if($data) {

            foreach ($data as $nameProperty => $valueProperty){

                if (property_exists($this, $nameProperty))
                    $this->$nameProperty = $valueProperty;
            }
        }
    }
}
