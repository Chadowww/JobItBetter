<?php

namespace App\Extensions\Doctrine;

use Doctrine\ORM\Query\Lexer;
use PhpParser\Node\Expr;

class DateDiff extends \Doctrine\ORM\Query\AST\Functions\FunctionNode
{
    public mixed $firstDateExpression = null;

    public mixed $secondDateExpression = null;

    public function parse(\Doctrine\ORM\Query\Parser $parser): void
    {
        $parser->match(Lexer::T_IDENTIFIER);
        $parser->match(Lexer::T_OPEN_PARENTHESIS);
        $this->firstDateExpression = $parser->ArithmeticPrimary();
        $parser->match(Lexer::T_COMMA);
        $this->secondDateExpression = $parser->ArithmeticPrimary();
        $parser->match(Lexer::T_CLOSE_PARENTHESIS);
    }

    public function getSql(\Doctrine\ORM\Query\SqlWalker $sqlWalker): string
    {
        return 'DATEDIFF(' .
            $sqlWalker->walkArithmeticTerm($this->firstDateExpression) . ', ' .
            $sqlWalker->walkArithmeticTerm($this->secondDateExpression) .
            ')';
    }
}
