<?php

namespace PHPSTORM_META {

    registerArgumentsSet(
        'signer_algo',
        \Local\Token\Algo::ES256,
        \Local\Token\Algo::ES384,
        \Local\Token\Algo::ES512,
        \Local\Token\Algo::HS256,
        \Local\Token\Algo::HS364,
        \Local\Token\Algo::HS512,
        \Local\Token\Algo::RS256,
        \Local\Token\Algo::RS364,
        \Local\Token\Algo::RS512,
    );

    expectedArguments(
        \Local\Token\Driver\Lcobucci\SignerFactory::create(),
        0,
        argumentsSet('signer_algo'),
    );

    expectedArguments(
        \Local\Token\Driver\LcobucciTokenBuilder::__construct(),
        1,
        argumentsSet('signer_algo'),
    );

    expectedArguments(
        \Local\Token\Driver\LcobucciTokenParser::__construct(),
        1,
        argumentsSet('signer_algo'),
    );

    expectedArguments(
        \Local\Token\Driver\FirebaseTokenBuilder::__construct(),
        1,
        argumentsSet('signer_algo'),
    );

    expectedArguments(
        \Local\Token\FirebaseTokenParser::__construct(),
        1,
        argumentsSet('signer_algo'),
    );

    expectedArguments(
        \Local\Token\TokenParserFactory::create(),
        1,
        argumentsSet('signer_algo'),
    );

    expectedArguments(
        \Local\Token\TokenParserFactoryInterface::create(),
        1,
        argumentsSet('signer_algo'),
    );

    expectedArguments(
        \Local\Token\TokenParserFactory::createFromFile(),
        1,
        argumentsSet('signer_algo'),
    );

    expectedArguments(
        \Local\Token\TokenParserFactoryInterface::createFromFile(),
        1,
        argumentsSet('signer_algo'),
    );

    expectedArguments(
        \Local\Token\TokenBuilderFactory::create(),
        1,
        argumentsSet('signer_algo'),
    );

    expectedArguments(
        \Local\Token\TokenBuilderFactoryInterface::create(),
        1,
        argumentsSet('signer_algo'),
    );

    expectedArguments(
        \Local\Token\TokenBuilderFactory::createFromFile(),
        1,
        argumentsSet('signer_algo'),
    );

    expectedArguments(
        \Local\Token\TokenBuilderFactoryInterface::createFromFile(),
        1,
        argumentsSet('signer_algo'),
    );

}
