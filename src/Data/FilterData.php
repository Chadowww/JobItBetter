<?php

namespace App\Data;

use App\Entity\Company;
use App\Entity\Contract;

class FilterData
{
    /**
     * @var string
     */
    public $q = '';

    /**
     * @var integer
     */
    public $minSalary;

    /**
     * @var integer
     */
    public $maxSalary;

    /**
     * @var Contract[]
     */
    public $contract;

    /**
     * @var array
     */
    public $city;

    /**
     * @var integer
     */
    public $company;
}
