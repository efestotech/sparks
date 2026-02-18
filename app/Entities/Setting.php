<?php

namespace App\Entities;

use CodeIgniter\Entity\Entity;

class Setting extends Entity
{
    protected $datamap = [];
    protected $dates   = [];
    protected $casts   = [];

    public function getValue()
    {
        $value = $this->attributes['value'];
        switch ($this->attributes['type']) {
            case 'int':
                return (int)$value;
            case 'bool':
                return (bool)$value;
            case 'json':
                return json_decode($value, true);
            default:
                return $value;
        }
    }
}
