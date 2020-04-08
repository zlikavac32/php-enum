<?php

declare(strict_types=1);

namespace Zlikavac32\Enum;

use LogicException;
use Throwable;
use function sprintf;

class EnumNotFoundException extends LogicException
{

    private string $missingEnumName;
    private string $enumClass;

    public function __construct(string $missingEnumName, string $enumClass, int $code = 0, Throwable $previous = null)
    {
        parent::__construct(sprintf('Enum element %s missing in %s', $missingEnumName, $enumClass), $code, $previous);
        $this->missingEnumName = $missingEnumName;
        $this->enumClass = $enumClass;
    }

    public function missingEnumName(): string
    {
        return $this->missingEnumName;
    }

    public function enumClass(): string
    {
        return $this->enumClass;
    }
}
