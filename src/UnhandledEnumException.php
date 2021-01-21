<?php

declare(strict_types=1);

namespace Zlikavac32\Enum;

use LogicException;

class UnhandledEnumException extends LogicException
{

    private string $enumFqn;

    public function __construct(public Enum $enum)
    {
        $fqn = get_parent_class($enum);

        parent::__construct(sprintf('Enum %s::%s() is left unhandled', $fqn, $enum->name()));

        $this->enumFqn = $fqn;
    }

    public function enum(): Enum
    {
        return $this->enum;
    }

    public function enumFqn(): string
    {
        return $this->enumFqn;
    }
}
