<?php

namespace Tests\Fixer;

use PhpCsFixerExtra\Fixer\PhpdocFullNamespaceFixer;
use Symfony\CS\Tokenizer\Tokens;

class PhpdocFullNamespaceFixerTest extends \PHPUnit_Framework_TestCase
{
    public function testMock01()
    {
        $filePath = __DIR__ . '/../Mocks/Mock01.php';
        $content = file_get_contents($filePath);

        $fixer = new PhpdocFullNamespaceFixer();
        $response = $fixer->fix(new \SplFileInfo($filePath), $content);

        $tokens = Tokens::fromCode($response);
        $comments = $tokens->findGivenKind(T_DOC_COMMENT);

        $this->assertEquals('/**
     * @var \Tests\Mocks\Mock02
     */', $comments[37]->getContent());

        $this->assertEquals('/**
     * @type \Tests\Mocks\Mock03
     */', $comments[44]->getContent());

        $this->assertEquals('/**
     * @var \Tests\Mocks\Mock02
     */', $comments[51]->getContent());

        $this->assertEquals('/**
     * @param \Tests\Mocks\Mock02 $myMock02
     * @param string|\Tests\Mocks\Mock02 $test
     * @return \Tests\Mocks\Mock02|mixed
     * @throws \Tests\Mocks\Mock02
     */', $comments[65]->getContent());

        $this->assertEquals('/**
     * @var mixed
     */', $comments[58]->getContent());

        $this->assertEquals('/** @var \Tests\Mocks\Mock02 $items */', $comments[83]->getContent());

        $this->assertEquals('/**
     * @return mixed|null 
     */', $comments[104]->getContent());

    }

}