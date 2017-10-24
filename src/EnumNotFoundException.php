<?php

declare(strict_types=1);

namespace Zlikavac32\Enum;

use LogicException;
use Throwable;

class EnumNotFoundException extends LogicException
{
    /**
     * @var string
     */
    private $missingEnumName;
    /**
     * @var string
     */
    private $enumClass;

    public function __construct(string $missingEnumName, string $enumClass, int $code = 0, Throwable $previous = null)
    {
        parent::__construct(sprintf('Enum object %s missing in %s', $missingEnumName, $enumClass), $code, $previous);
        $this->missingEnumName = $missingEnumName;
        $this->enumClass = $enumClass;
    }

    /**
     * @return string
     */
    public function missingEnumName(): string
    {
        return $this->missingEnumName;
    }

    /**
     * @return string
     */
    public function enumClass(): string
    {
        return $this->enumClass;
    }
}
