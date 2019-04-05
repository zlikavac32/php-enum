<?php

declare(strict_types=1);

namespace Zlikavac32\Enum;

use LogicException;

class UnhandledEnumException extends LogicException
{

    /**
     * @var Enum
     */
    private $enum;
    /**
     * @var string
     */
    private $enumFqn;

    public function __construct(Enum $enum)
    {
        $fqn = get_parent_class($enum);

        parent::__construct(sprintf('Enum %s::%s() is left unhandled', $fqn, $enum->name()));

        $this->enum = $enum;
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
