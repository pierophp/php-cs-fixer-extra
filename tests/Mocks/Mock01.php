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
     * @var mixed
     */
    public $mock05;

    /**
     * @param Mock02 $myMock02
     * @param string|\Tests\Mocks\Mock02 $test
     * @return MockAlias|mixed
     * @throws Mock02
     */
    public function getMyMock(Mock02 $myMock02, $test)
    {
        /** @var Mock02 $items */
        $items = [];

        return new MockAlias();
    }

    /**
     * @return mixed|null 
     */
    public function getMyMock2()
    {
    }
}
