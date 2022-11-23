<?php
namespace App\Orm;

use Doctrine\ORM\Query\Lexer;
use Doctrine\ORM\Query\Parser;
use Doctrine\ORM\Query\QueryException;
use Doctrine\ORM\Query\SqlWalker;
use Doctrine\ORM\Query\AST\Functions\FunctionNode;

/**
 * Classe permettant d'ajouter une fonction numérique RANDOM() dans les requêtes doctrine
 * Cette classe est déclarée dans config/packages/doctrine.yaml
 * Méthode trouvée sur https://gist.github.com/Ocramius/919465
 */
class Random extends FunctionNode
{
	/**
	 * @throws QueryException
	 */
	public function parse(Parser $parser): void
	{
		$parser->match(Lexer::T_IDENTIFIER);
		$parser->match(Lexer::T_OPEN_PARENTHESIS);
		$parser->match(Lexer::T_CLOSE_PARENTHESIS);
	}

	/**
	 * Retourne la fonction numérique RANDOM()
	 * @param SqlWalker $sqlWalker
	 * @return string
	 */
	public function getSql(SqlWalker $sqlWalker): string
	{
		return 'RANDOM()';
	}
}