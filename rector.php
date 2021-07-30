<?php

declare(strict_types=1);

use PhpCsFixer\Fixer\Import\NoUnusedImportsFixer;
use Rector\Core\Configuration\Option;
use Rector\PHPUnit\Set\PHPUnitSetList;
use Rector\Php74\Rector\Property\TypedPropertyRector;
use Rector\Set\ValueObject\SetList;
use Rector\Symfony\Set\SymfonySetList;
use Symfony\Component\DependencyInjection\Loader\Configurator\ContainerConfigurator;

return static function (ContainerConfigurator $containerConfigurator): void {
    // get parameters
    $parameters = $containerConfigurator->parameters();

    $parameters->set(Option::PATHS, [__DIR__ . '/src', __DIR__ . '/tests/unit']);

    $parameters->set(Option::AUTO_IMPORT_NAMES, true);
    $parameters->set(Option::IMPORT_DOC_BLOCKS, false);
    $parameters->set(Option::IMPORT_SHORT_CLASSES, false);

    // Define what rule sets will be applied
    $containerConfigurator->import(SymfonySetList::SYMFONY_40);
    $containerConfigurator->import(SymfonySetList::SYMFONY_41);
    $containerConfigurator->import(SymfonySetList::SYMFONY_42);
    $containerConfigurator->import(SymfonySetList::SYMFONY_43);
    $containerConfigurator->import(SymfonySetList::SYMFONY_44);

    // get services (needed for register a single rule)
    $services = $containerConfigurator->services();

    // register a single rule
    $services->set(NoUnusedImportsFixer::class);
};
