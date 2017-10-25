<?php

declare(strict_types=1);

namespace Zlikavac32\Rick\Examples;

use Generator;
use Zlikavac32\Enum\Enum;

require_once __DIR__ . '/../vendor/autoload.php';

/**
 * Example illustrating one practical usage of this enum library. We will define our Token enum that will consist of
 * the valid tokens with appropriate patterns. Then we'll offer two convenient methods to use with our token. One that
 * will escape a delimiter in the pattern and a second that will create a named group pattern from the current token
 */

/**
 * @method static Token T_PLUS
 * @method static Token T_MINUS
 * @method static Token T_TIMES
 * @method static Token T_DIV
 * @method static Token T_SPACE
 * @method static Token T_NUMBER
 * @method static Token T_INVALID
 */
abstract class Token extends Enum
{
    /**
     * @var string
     */
    private $pattern;

    public function __construct(string $pattern)
    {
        $this->pattern = $pattern;
    }

    protected static function enumerate(): array
    {
        return [
            'T_PLUS'    => new class('\\+') extends Token {},
            'T_MINUS'   => new class('-') extends Token {},
            'T_TIMES'   => new class('\\*') extends Token {},
            'T_DIV'     => new class('/') extends Token {},
            'T_SPACE'   => new class('\\s+') extends Token {},
            'T_NUMBER'  => new class('\\d+') extends Token {},
            'T_INVALID' => new class('.') extends Token {},
        ];
    }

    public function pattern(): string
    {
        return $this->pattern;
    }

    public function patternWithEscapedDelimiter(string $delimiter): string
    {
        return addcslashes($this->pattern, $delimiter);
    }

    public function patternAsNamedGroup(string $delimiter): string
    {
        return sprintf('(?P<%s>%s)', $this->name(), $this->patternWithEscapedDelimiter($delimiter));
    }
}

foreach (Token::values() as $token) {
    var_dump(
        sprintf(
            '%s - pattern: %s; escaped: %s; named: %s)',
            (string) $token,
            $token->pattern(),
            $token->patternWithEscapedDelimiter('/'),
            $token->patternAsNamedGroup('/')
        )
    );
}

function lex(string $input): Generator
{
    $pattern = sprintf(
        '/^%s/',
        implode(
            '|',
            array_map(
                function (Token $token): string {
                    return $token->patternAsNamedGroup('/');
                },
                Token::values()
            )
        )
    );

    $position = 0;

    while ('' !== $input) {
        preg_match($pattern, $input, $matches);
        foreach ($matches as $key => $match) {
            if (
                !is_string($key)
                ||
                '' === $match
            ) {
                continue;
            }

            yield [Token::valueOf($key), $match, $position];
            $input = substr($input, strlen($match));
            $position += strlen($match);

            break;
        }
    }
}

/* @var Token $token */
foreach (lex('12 + 89 * 19 - 119') as [$token, $match, $position]) {
    var_dump(sprintf('%s("%s", %d)', $token->name(), $match, $position));
}
