<?php

declare(strict_types=1);

namespace App\Packagist\Presentation\Response\DTO;

use JMS\Serializer\Annotation as Serializer;

final readonly class PackageReleaseResponseDTO
{
    public function __construct(
        /**
         * The name of the package. It consists of vendor name and
         * project name, separated by `/`.
         *
         * Examples:
         *
         * ```
         * 'monolog/monolog'
         * 'igorw/event-source'
         * ```
         *
         * The name must be lowercase and consist of words separated by `-`, `.`
         * or `_`. The complete name should match `^[a-z0-9]([_.-]?[a-z0-9]+)*
         * /[a-z0-9](([_.]|-{1,2})?[a-z0-9]+)*$`
         *
         * The `name` property is required for published packages(libraries).
         *
         * @since 2.0 - before Composer version 2.0, a `name` could contain
         *        any character, including white spaces.
         *
         * @var non-empty-string|null
         */
        #[Serializer\Exclude(if: 'object.name === null')]
        public ?string $name,
        /**
         * A short description of the package. Usually this is one line long.
         *
         * Required for published packages (libraries).
         */
        #[Serializer\Exclude(if: 'object.description === null')]
        public ?string $description,
        /**
         * An array of keywords that the package is related to. These can be
         * used for searching and filtering.
         *
         * Examples:
         *
         * ```
         * [
         *     'logging',
         *     'events',
         *     'database',
         *     'redis',
         *     'templating',
         * ]
         * ```
         *
         * Note: Some special keywords trigger `composer require` without the
         *       `--dev` option to prompt users if they would like to add these
         *       packages to `require-dev` instead of require. These are: `dev`,
         *       `testing`, `static analysis`.
         *
         * Note: The range of characters allowed inside the string is restricted
         *       to unicode letters or numbers, space `' '`, dot `.`, underscore
         *       `_` and dash `-`. (Regex: `'{^[\p{N}\p{L} ._-]+$}u'`) Using
         *       other characters will emit a warning when running
         *       `composer validate` and will cause the package to fail updating
         *        on Packagist.org.
         *
         * Optional.
         *
         * @var list<non-empty-string>|null
         */
        #[Serializer\Exclude(if: 'object.keywords === null')]
        public ?array $keywords,
        /**
         * A URL to the website of the project.
         *
         * Optional.
         */
        #[Serializer\Exclude(if: 'object.homepage === null')]
        public ?string $homepage,
        /**
         * A relative path to the readme document. Defaults to README.md.
         *
         * This is mainly useful for packages not on GitHub, as for GitHub
         * packages Packagist.org will use the readme API to fetch the one
         * detected by GitHub.
         *
         * Optional.
         */
        #[Serializer\Exclude(if: 'object.readme === null')]
        public ?string $readme,
        /**
         * The license of the package. This can be either a string or an array
         * of strings.
         *
         * The recommended notation for the most common licenses is
         * (alphabetical):
         *
         * ```
         * Apache-2.0
         * BSD-2-Clause
         * BSD-3-Clause
         * BSD-4-Clause
         * GPL-2.0-only / GPL-2.0-or-later
         * GPL-3.0-only / GPL-3.0-or-later
         * LGPL-2.1-only / LGPL-2.1-or-later
         * LGPL-3.0-only / LGPL-3.0-or-later
         * MIT
         * ```
         *
         * Optional, but it is highly recommended to supply this. More
         * identifiers are listed at the {@link https://spdx.org/licenses/
         * SPDX Open Source License Registry}.
         *
         * Note: For closed-source software, you may use "proprietary"
         *       as the license identifier.
         *
         * An Example:
         *
         * ```
         * {
         *     "license": "MIT"
         * }
         * ```
         *
         * For a package, when there is a choice between licenses
         * ("disjunctive license"), multiple can be specified as an array.
         *
         * An Example for disjunctive licenses:
         *
         * ```
         * {
         *     "license": [
         *         "LGPL-2.1-only",
         *         "GPL-3.0-or-later"
         *     ]
         * }
         * ```
         *
         * Alternatively they can be separated with "or" and enclosed in
         * parentheses;
         *
         * ```
         * {
         *     "license": "(LGPL-2.1-only or GPL-3.0-or-later)"
         * }
         * ```
         *
         * Similarly, when multiple licenses need to be applied
         * ("conjunctive license"), they should be separated with "and" and
         * enclosed in parentheses.
         *
         * @var list<non-empty-string>|null
         */
        #[Serializer\Exclude(if: 'object.license === null')]
        public ?array $license,
        /**
         * TODO
         *
         * @varlist<mixed>|null
         */
        #[Serializer\Exclude(if: 'object.authors === null')]
        public ?array $authors,
        /**
         * The type of the package. It defaults to library.
         *
         * Package types are used for custom installation logic. If you
         * have a package that needs some special logic, you can define a
         * custom type. This could be a `symfony-bundle`, a `wordpress-plugin`
         * or a `typo3-cms-extension`. These types will all be specific to
         * certain projects, and they will need to provide an installer
         * capable of installing packages of that type.
         *
         * Out of the box, Composer supports four types:
         *
         * - library: This is the default. It will copy the files to vendor.
         *
         * - project: This denotes a project rather than a library. For example
         *   application shells like the Symfony standard edition, CMSs like
         *   the Silverstripe installer or full fledged applications distributed
         *   as packages. This can for example be used by IDEs to provide
         *   listings of projects to initialize when creating a new workspace.
         *
         * - metapackage: An empty package that contains requirements and will
         *   trigger their installation, but contains no files and will not
         *   write anything to the filesystem. As such, it does not require
         *   a dist or source key to be installable.
         *
         * - composer-plugin: A package of type composer-plugin may provide
         *   an installer for other packages that have a custom type. Read more
         *   in the dedicated article.
         *
         * - php-ext and php-ext-zend: These names are reserved for PHP
         *   extension packages which are written in C. Do not use these types
         *   for packages written in PHP.
         *
         * Only use a custom type if you need custom logic during installation.
         * It is recommended to omit this field and have it default to library.
         *
         * @var non-empty-string|null
         */
        #[Serializer\Exclude(if: 'object.type === null')]
        public ?string $type,
        /**
         * TODO
         *
         * @var list<mixed>|null
         */
        #[Serializer\Exclude(if: 'object.funding === null')]
        public ?array $funding,
        #[Serializer\Exclude(if: 'object.source === null')]
        public ?SourceReferenceResponseDTO $source,
        #[Serializer\Exclude(if: 'object.dist === null')]
        public ?DistReferenceResponseDTO $dist,
        /**
         * The version of the package.
         *
         * This must follow the format of `X.Y.Z` or `vX.Y.Z` with an optional
         * suffix of `-dev`, `-patch` (`-p`), `-alpha` (`-a`), `-beta` (`-b`)
         * or `-RC`. The patch, alpha, beta and RC suffixes can also be
         * followed by a number.
         *
         * Examples:
         *
         * ```
         * 1.0.0
         * 1.0.2
         * 1.1.0
         * 0.2.5
         * 1.0.0-dev
         * 1.0.0-alpha3
         * 1.0.0-beta2
         * 1.0.0-RC5
         * v2.0.4-p1
         * ```
         *
         * Optional if the package repository can infer the version from
         * somewhere, such as the VCS tag name in the VCS repository. In that
         * case it is also recommended to omit it.
         *
         * Note: Packagist uses VCS repositories, so the statement above is
         * very much true for Packagist as well. Specifying the version yourself
         * will most likely end up creating problems at some point due to
         * human error.
         *
         * @var non-empty-string
         */
        public string $version,
        #[Serializer\SerializedName(name: 'version_normalized')]
        /**
         * @var non-empty-string
         */
        public string $versionNormalized,
        #[Serializer\SerializedName(name: 'time')]
        public \DateTimeInterface $updatedAt,
    ) {}
}
