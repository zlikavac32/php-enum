<?php

declare(strict_types=1);

namespace Zlikavac32\Enum\Examples;

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
        parent::__construct();
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

// string(57) "T_PLUS - pattern: \\+; escaped: \\+; named: (?P<T_PLUS>\\+))"
// string(56) "T_MINUS - pattern: -; escaped: -; named: (?P<T_MINUS>-))"
// string(59) "T_TIMES - pattern: \\*; escaped: \\*; named: (?P<T_TIMES>\\*))"
// string(54) "T_DIV - pattern: /; escaped: \\/; named: (?P<T_DIV>\\/))"
// string(62) "T_SPACE - pattern: \\s+; escaped: \\s+; named: (?P<T_SPACE>\\s+))"
// string(64) "T_NUMBER - pattern: \\d+; escaped: \\d+; named: (?P<T_NUMBER>\\d+))"
// string(60) "T_INVALID - pattern: .; escaped: .; named: (?P<T_INVALID>.))"
// string(17) "T_NUMBER("12", 0)"
// string(15) "T_SPACE(" ", 2)"
// string(14) "T_PLUS("+", 3)"
// string(15) "T_SPACE(" ", 4)"
// string(17) "T_NUMBER("89", 5)"
// string(15) "T_SPACE(" ", 7)"
// string(15) "T_TIMES("*", 8)"
// string(15) "T_SPACE(" ", 9)"
// string(18) "T_NUMBER("19", 10)"
// string(16) "T_SPACE(" ", 12)"
// string(16) "T_MINUS("-", 13)"
// string(16) "T_SPACE(" ", 14)"
// string(19) "T_NUMBER("119", 15)"
