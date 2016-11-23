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
        $comments = array_values($tokens->findGivenKind(T_DOC_COMMENT));

        $this->assertEquals('/**
     * @var \Tests\Mocks\Mock02
     */', $comments[0]->getContent());

        $this->assertEquals('/**
     * @type \Tests\Mocks\Mock03
     */', $comments[1]->getContent());

        $this->assertEquals('/**
     * @var \Tests\Mocks\Mock02
     */', $comments[2]->getContent());

        $this->assertEquals('/**
     * @param \Tests\Mocks\Mock02 $mock
     * @return \Tests\Mocks\Mock02
     * @throws \Tests\Mocks\Mock02
     */', $comments[3]->getContent());

    }

}