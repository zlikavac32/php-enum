<?php

declare(strict_types=1);

namespace spec\Zlikavac32\Enum;

use PhpSpec\ObjectBehavior;
use Zlikavac32\Enum\Enum;
use Zlikavac32\Enum\UnhandledEnumException;

class UnhandledEnumExceptionSpec extends ObjectBehavior
{

    public function let()
    {
        $this->beConstructedWith(FooEnum::FOO());
    }

    function it_is_initializable()
    {
        $this->shouldHaveType(UnhandledEnumException::class);
    }

    public function it_should_have_correct_message(): void
    {
        $this->getMessage()
            ->shouldReturn('Enum spec\Zlikavac32\Enum\FooEnum::FOO() is left unhandled');
    }

    public function it_should_have_correct_enum(): void
    {
        $this->enum()
            ->shouldReturn(FooEnum::FOO());
    }

    public function it_should_have_correct_enum_fqn(): void
    {
        $this->enumFqn()
            ->shouldReturn('spec\Zlikavac32\Enum\FooEnum');
    }
}

/**
 * @internal
 *
 * @method static FooEnum FOO
 */
abstract class FooEnum extends Enum
{

}
