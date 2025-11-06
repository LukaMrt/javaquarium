<?php

declare(strict_types=1);

namespace App\Tests;

use PHPat\Test\Builder\BuildStep;
use App\Domain\Shared\ValueObject\ValueObjectInterface;
use App\Domain\Shared\Entity\EntityInterface;
use PHPat\Selector\Selector;
use PHPat\Test\PHPat;

final class CleanArchitectureTest
{
    public function testDomainLayerIndependence(): BuildStep
    {
        return PHPat::rule()
            ->classes(Selector::inNamespace('App\Domain'))
            ->shouldNotDependOn()
            ->classes(
                Selector::inNamespace('App\Application'),
                Selector::inNamespace('App\Infrastructure'),
                Selector::inNamespace('App\Presentation'),
                Selector::inNamespace('Doctrine'),
                Selector::inNamespace('Symfony\Bundle'),
                Selector::inNamespace('Symfony\Component\HttpFoundation'),
                Selector::inNamespace('Symfony\Component\HttpKernel'),
                Selector::inNamespace('Symfony\Component\Validator'),
            )
            ->excluding(Selector::inNamespace('Symfony\Component\Uid'))
            ->because('The Domain layer must be independent of other layers and frameworks.');
    }

    public function testApplicationLayerDependsOnlyOnDomain(): BuildStep
    {
        return PHPat::rule()
            ->classes(Selector::inNamespace('App\Application'))
            ->shouldNotDependOn()
            ->classes(
                Selector::inNamespace('App\Infrastructure'),
                Selector::inNamespace('App\Presentation'),
                Selector::inNamespace('Doctrine'),
                Selector::inNamespace('Symfony\Bundle'),
                Selector::inNamespace('Symfony\Component\HttpFoundation'),
                Selector::inNamespace('Symfony\Component\HttpKernel'),
                Selector::inNamespace('Symfony\Component\Validator'),
            )
            ->because('The Application layer should only depend on the Domain layer.');
    }

    public function testPresentationDependsOnApplicationOnly(): BuildStep
    {
        return PHPat::rule()
            ->classes(Selector::inNamespace('App\Presentation'))
            ->shouldNotDependOn()
            ->classes(
                Selector::inNamespace('App\Domain'),
                Selector::inNamespace('App\Infrastructure'),
                Selector::inNamespace('Doctrine'),
            )
            ->because('The Presentation layer should only depend on the Application layer.');
    }

    public function testValueObjectsMustBeFinal(): BuildStep
    {
        return PHPat::rule()
            ->classes(Selector::implements(ValueObjectInterface::class))
            ->shouldBeFinal()
            ->because('Value Objects must be final and immutable.');
    }

    public function testValueObjectsMustBeReadonly(): BuildStep
    {
        return PHPat::rule()
            ->classes(Selector::implements(ValueObjectInterface::class))
            ->shouldBeReadonly()
            ->because('Value Objects must be readonly and immutable.');
    }

    public function testEntitiesMustBeFinal(): BuildStep
    {
        return PHPat::rule()
            ->classes(Selector::implements(EntityInterface::class))
            ->shouldBeFinal()
            ->because('Entities should be final to prevent unexpected inheritance.');
    }

    public function testDomainServicesMustBeFinal(): BuildStep
    {
        return PHPat::rule()
            ->classes(Selector::inNamespace('App\Domain\Service'))
            ->shouldBeFinal()
            ->because('Domain Services must be stateless (final and readonly).');
    }

    public function testDomainServicesMustBeReadonly(): BuildStep
    {
        return PHPat::rule()
            ->classes(Selector::inNamespace('App\Domain\Service'))
            ->shouldBeReadonly()
            ->because('Domain Services must be stateless (final and readonly).');
    }

    public function testHandlersInUseCaseMustBeFinal(): BuildStep
    {
        return PHPat::rule()
            ->classes(
                Selector::inNamespace('App\Application\UseCase'),
                Selector::classname('.*Handler$', true)
            )
            ->shouldBeFinal()
            ->because('Handlers must be stateless (final and readonly).');
    }

    public function testHandlersInUseCaseMustBeReadonly(): BuildStep
    {
        return PHPat::rule()
            ->classes(
                Selector::inNamespace('App\Application\UseCase'),
                Selector::classname('.*Handler$', true)
            )
            ->shouldBeReadonly()
            ->because('Handlers must be stateless (final and readonly).');
    }

    public function testCommandsMustBeFinal(): BuildStep
    {
        return PHPat::rule()
            ->classes(
                Selector::inNamespace('App\Application\UseCase'),
                Selector::classname('.*Command$', true)
            )
            ->shouldBeFinal()
            ->because('Commands are DTOs and must be immutable.');
    }

    public function testCommandsMustBeReadonly(): BuildStep
    {
        return PHPat::rule()
            ->classes(
                Selector::inNamespace('App\Application\UseCase'),
                Selector::classname('.*Command$', true)
            )
            ->shouldBeReadonly()
            ->because('Commands are DTOs and must be immutable.');
    }

    public function testQueriesMustBeFinal(): BuildStep
    {
        return PHPat::rule()
            ->classes(
                Selector::inNamespace('App\Application\UseCase'),
                Selector::classname('.*Query$', true)
            )
            ->shouldBeFinal()
            ->because('Queries are DTOs and must be immutable.');
    }

    public function testQueriesMustBeReadonly(): BuildStep
    {
        return PHPat::rule()
            ->classes(
                Selector::inNamespace('App\Application\UseCase'),
                Selector::classname('.*Query$', true)
            )
            ->shouldBeReadonly()
            ->because('Queries are DTOs and must be immutable.');
    }

    public function testResponsesMustBeFinal(): BuildStep
    {
        return PHPat::rule()
            ->classes(
                Selector::inNamespace('App\Application\UseCase'),
                Selector::classname('.*Response$', true)
            )
            ->shouldBeFinal()
            ->because('Responses are DTOs and must be immutable.');
    }

    public function testResponsesMustBeReadonly(): BuildStep
    {
        return PHPat::rule()
            ->classes(
                Selector::inNamespace('App\Application\UseCase'),
                Selector::classname('.*Response$', true)
            )
            ->shouldBeReadonly()
            ->because('Responses are DTOs and must be immutable.');
    }

    public function testDTOsMustBeFinal(): BuildStep
    {
        return PHPat::rule()
            ->classes(Selector::inNamespace('App\Application\DTO'))
            ->shouldBeFinal()
            ->because('DTOs must be immutable (final and readonly).');
    }

    public function testDTOsMustBeReadonly(): BuildStep
    {
        return PHPat::rule()
            ->classes(Selector::inNamespace('App\Application\DTO'))
            ->shouldBeReadonly()
            ->because('DTOs must be immutable (final and readonly).');
    }

    public function testControllersMustBeFinal(): BuildStep
    {
        return PHPat::rule()
            ->classes(Selector::inNamespace('App\Presentation\Api\Controller'))
            ->shouldBeFinal()
            ->because('Controllers should be final to prevent unexpected inheritance.');
    }
}
