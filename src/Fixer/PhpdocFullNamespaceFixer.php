<?php

namespace PhpCsFixerExtra\Fixer;

use Symfony\CS\AbstractFixer;
use Symfony\CS\DocBlock\DocBlock;
use Symfony\CS\DocBlock\Line;
use Symfony\CS\Tokenizer\Tokens;

class PhpdocFullNamespaceFixer extends AbstractFixer
{
    /**
     * @param \SplFileInfo $file
     * @param string $content
     * @return string
     */
    public function fix(\SplFileInfo $file, $content)
    {
        $tokens = Tokens::fromCode($content);

        foreach ($tokens->findGivenKind(T_DOC_COMMENT) as $token) {
            $doc = new DocBlock($token->getContent());
            $annotations = $doc->getAnnotationsOfType(['var', 'type', 'return', 'throws', 'param']);

            if (empty($annotations)) {
                continue;
            }

            foreach ($annotations as $annotation) {

                $line = $doc->getLine($annotation->getStart());

                $classPartsRaw = explode(' ', trim($line->getContent()));
                $classParts = [];

                foreach ($classPartsRaw as $classPart) {
                    if (!$classPart) {
                        continue;
                    }

                    if ($classPart == '*/') {
                        continue;
                    }

                    $classParts[] = $classPart;
                }

                $class = end($classParts);

                if (substr($class, 0, 1) == '$') {
                    $classIndex = count($classParts) - 2;
                    if (isset($classParts[$classIndex])) {
                        $class = $classParts[$classIndex];
                    }
                }

                if (!$class) {
                    continue;
                }

                $classList = explode('|', $class);
                foreach ($classList as $class) {

                    if (substr($class, 0, 1) == '\\') {
                        continue;
                    }

                    $class = str_replace(['[', ']'], '', $class);

                    $wasParsed = $this->parseLineWithUseAndAs($line, $class, $content);
                    if ($wasParsed) {
                        continue;
                    }

                    $wasParsed = $this->parseLineWithUse($line, $class, $content);
                    if ($wasParsed) {
                        continue;
                    }

                    $this->parseLineWithoutUse($line, $class, $content, $file);
                }
            }

            $token->setContent($doc->getContent());
        }

        return $tokens->generateCode();
    }

    /**
     * @param Line $line
     * @param $className
     * @param $content
     * @return bool
     */
    private function parseLineWithUseAndAs(Line $line, $className, $content)
    {
        preg_match_all('/use (.*) as ' . preg_quote($className) . ';/', $content, $matches, PREG_OFFSET_CAPTURE);
        if (!isset($matches[0][0][0])) {
            return false;
        }

        $fullClassName = $matches[0][0][0];
        $fullClassName = str_replace('use ', '', $fullClassName);
        $fullClassName = str_replace(';', '', $fullClassName);

        $fullClassName = explode(' ', $fullClassName);
        $fullClassName = current($fullClassName);

        $line->setContent(str_replace($className, '\\' . $fullClassName, $line->getContent()));

        return true;
    }

    /**
     * @param Line $line
     * @param $className
     * @param $content
     * @return bool
     */
    private function parseLineWithUse(Line $line, $className, $content)
    {
        preg_match_all('/use (.*)' . preg_quote('\\' . $className) . ';/', $content, $matches, PREG_OFFSET_CAPTURE);
        if (!isset($matches[0][0][0])) {
            return false;
        }

        $fullClassName = $matches[0][0][0];
        $fullClassName = str_replace('use ', '', $fullClassName);
        $fullClassName = str_replace(';', '', $fullClassName);

        $fullClassName = explode(' ', $fullClassName);
        $fullClassName = current($fullClassName);

        $line->setContent(str_replace(' ' . $className, ' \\' . $fullClassName, $line->getContent()));

        return true;
    }

    /**
     * @param Line $line
     * @param $className
     * @param $content
     * @param $file
     * @return bool
     */
    private function parseLineWithoutUse(Line $line, $className, $content, $file)
    {
        $fileParts = explode(DIRECTORY_SEPARATOR, $file->getRealPath());
        $directory = str_replace(end($fileParts), '', $file->getRealPath());
        if (!file_exists($directory . $className . '.php')) {
            return false;
        }

        preg_match_all('/namespace (.*);/', $content, $matches, PREG_OFFSET_CAPTURE);
        if (!isset($matches[1][0][0])) {
            return false;
        }

        $fullClassName = $matches[1][0][0] . '\\' . $className;


        $line->setContent(str_replace($className, '\\' . $fullClassName, $line->getContent()));

        return true;
    }

    /**
     * @return int
     */
    public function getLevel()
    {
        return 0;
    }

    /**
     * @return string
     */
    public function getDescription()
    {
        return 'Namespace full';
    }
}
