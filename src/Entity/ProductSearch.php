<?php

namespace App\Entity;

class ProductSearch
{
    /**
     * @var TypeProduct
     */
    private $type;

    /**
     * @var boolean|null
     */
    private $stocked;

    /**
     * @return TypeProduct
     */
    public function getType()
    {
        return $this->type;
    }

    /**
     * @param TypeProduct $type
     */
    public function setType(TypeProduct $type)
    {
        $this->type = $type;
    }

    /**
     * @return bool
     */
    public function isStocked()
    {
        return $this->stocked;
    }

    /**
     * @param bool $stocked
     */
    public function setStocked(bool $stocked)
    {
        $this->stocked = $stocked;
    }

}