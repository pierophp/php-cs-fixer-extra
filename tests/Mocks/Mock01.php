<?php

namespace Tests\Mocks;

use Tests\Mocks\Mock02;
use Tests\Mocks\Mock02 as MockAlias;

class Mock01
{
    /**
     * @var Mock02
     */
    public $mock02;

    /**
     * @type Mock03
     */
    public $mock03;

    /**
     * @var MockAlias
     */
    public $mock04;

    /**
     * @param Mock02 $mock
     * @return MockAlias
     * @throws Mock02
     */
    public function getMyMock(Mock02 $mock)
    {
        return new MockAlias();
    }


}