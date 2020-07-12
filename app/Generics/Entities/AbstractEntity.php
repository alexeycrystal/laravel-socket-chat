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
     * @throws \Exception
     */
    public function __construct(?array $data = null)
    {
        if($data) {

            foreach ($data as $nameProperty => $valueProperty){

                if (!property_exists($this, $nameProperty))
                    throw new \Exception('Mapper FROM array to Entities (Called in Repository): Property  << '.$nameProperty. ' >> does not exist in '.get_class($this));

                $this->$nameProperty = $valueProperty;
            }
        }
    }
}
