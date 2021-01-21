<?php

declare(strict_types=1);

namespace Zlikavac32\Enum;

use LogicException;
use Throwable;
use function sprintf;

class EnumNotFoundException extends LogicException
{

    public function __construct(private string $missingEnumName, private string $enumClass, int $code = 0, Throwable $previous = null)
    {
        parent::__construct(sprintf('Enum element %s missing in %s', $missingEnumName, $enumClass), $code, $previous);
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
