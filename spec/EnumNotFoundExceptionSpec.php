<?php

declare(strict_types=1);

namespace spec\Zlikavac32\ZEnum;

use Exception;
use PhpSpec\ObjectBehavior;
use Zlikavac32\ZEnum\EnumNotFoundException;

class EnumNotFoundExceptionSpec extends ObjectBehavior
{
    public function let(Exception $previous)
    {
        $this->beConstructedWith('NAME', 'SomeClass', 32, $previous);
    }

    function it_is_initializable()
    {
        $this->shouldHaveType(EnumNotFoundException::class);
    }

    public function it_should_have_correct_message(): void
    {
        $this->getMessage()
            ->shouldReturn('Enum element NAME missing in SomeClass');
    }

    public function it_should_have_correct_missing_element(): void
    {
        $this->missingEnumName()
            ->shouldReturn('NAME');
    }

    public function it_should_have_correct_enum_class(): void
    {
        $this->enumClass()
            ->shouldReturn('SomeClass');
    }

    public function it_should_have_correct_code(): void
    {
        $this->getCode()
            ->shouldReturn(32);
    }

    public function it_should_have_correct_previous(Exception $previous): void
    {
        $this->getPrevious()
            ->shouldReturn($previous);
    }

    public function it_should_have_correct_default_arguments(): void
    {
        $this->beConstructedWith('NAME', 'SomeClass');

        $this->getCode()
            ->shouldReturn(0);
        $this->getPrevious()
            ->shouldReturn(null);
    }
}
