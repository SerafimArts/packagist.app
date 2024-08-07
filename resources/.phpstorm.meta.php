<?php

namespace PHPSTORM_META {

    registerArgumentsSet('package_reference_type',
        'fossil',
        'git',
        'hg',
        'perforce',
        'svn',
        'zip',
        'rar',
        'tar',
        'gzip',
        'xz',
        'phar',
        'file',
        'path'
    );

    expectedArguments(
        App\Package\Domain\Reference\Reference::__construct(),
        0,
        argumentsSet('package_reference_type')
    );

    expectedArguments(
        App\Package\Domain\Reference\Reference::__construct(),
        0,
        argumentsSet('package_reference_type')
    );

    expectedArguments(
        App\Package\Domain\Reference\Reference::__construct(),
        0,
        argumentsSet('package_reference_type')
    );

}
