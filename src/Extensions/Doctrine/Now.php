<?php

namespace App\Extensions\Doctrine;

use Doctrine\ORM\Query\Lexer;

class Now extends \Doctrine\ORM\Query\AST\Functions\FunctionNode
{
    public function getSql(\Doctrine\ORM\Query\SqlWalker $sqlWalker): string
    {
        return 'NOW()';
    }

    public function parse(\Doctrine\ORM\Query\Parser $parser): void
    {
        $parser->match(Lexer::T_IDENTIFIER);
        $parser->match(Lexer::T_OPEN_PARENTHESIS);
        $parser->match(Lexer::T_CLOSE_PARENTHESIS);
    }
}
