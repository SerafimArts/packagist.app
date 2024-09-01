<?php

declare(strict_types=1);

namespace App\Account\Presentation\Console;

use App\Account\Domain\Account;
use App\Account\Domain\AccountRepositoryInterface;
use App\Account\Domain\Integration\Integration;
use App\Account\Domain\Role;
use Doctrine\Common\Collections\ArrayCollection;
use Symfony\Component\Console\Attribute\AsCommand;
use Symfony\Component\Console\Command\Command;
use Symfony\Component\Console\Input\InputArgument;
use Symfony\Component\Console\Input\InputInterface;
use Symfony\Component\Console\Output\OutputInterface;
use Symfony\Component\Console\Terminal;

/**
 * @api
 *
 * @internal this is an internal library class, please do not use it in your code.
 * @psalm-internal App\Account\Presentation\Console
 */
#[AsCommand(name: 'account:info', description: 'Show account info')]
final class AccountInfoCommand extends Command
{
    private const string TPL_NONE = '<fg=gray>--none--</>';
    private const string FMT_DATE = \DateTimeInterface::RFC2822;

    public function __construct(
        private readonly AccountRepositoryInterface $accounts,
    ) {
        parent::__construct();
    }

    protected function configure(): void
    {
        $this->addArgument(
            name: 'login',
            mode: InputArgument::REQUIRED,
            description: 'Account login to show',
        );
    }

    public function run(InputInterface $input, OutputInterface $output): int
    {
        $account = $this->accounts->findByLogin($login = $input->getArgument('login'));

        if ($account === null) {
            throw new \InvalidArgumentException(\sprintf(
                'Account "%s" not found',
                $login,
            ));
        }

        $terminal = new Terminal();

        $output->writeln($this->title($terminal, 'Account Info'));

        foreach ($this->getAccountInfoRows($account) as $title => $value) {
            $output->writeln($this->row($terminal, $title, $value));
        }

        /** @var Integration $integration */
        foreach ($this->getAccountIntegrationRows($account->integrations) as $integration => $rows) {
            $output->writeln('');
            $output->writeln($this->subtitle($terminal, $integration->dsn->scheme));
            $output->writeln('');

            foreach ($rows as $title => $value) {
                $output->writeln($this->row($terminal, $title, $value));
            }
        }

        $output->writeln('');
        $output->writeln($this->line($terminal));
        $output->writeln('');

        return self::SUCCESS;
    }

    private function title(Terminal $terminal, string $title): string
    {
        $dots = $terminal->getWidth() - 4
            - \mb_strlen(\strip_tags($title));

        return \vsprintf("\n <fg=yellow;options=bold>%s %s %s</>\n", [
            \str_repeat('━', 2),
            $title,
            \str_repeat('━', \max(0, $dots - 2)),
        ]);
    }

    private function subtitle(Terminal $terminal, string $title): string
    {
        $dots = $terminal->getWidth() - 4
            - \mb_strlen(\strip_tags($title));

        return \vsprintf(' <fg=gray>%s <fg=gray;options=bold>%s</> %s</>', [
            \str_repeat('─', 2),
            $title,
            \str_repeat('─', \max(0, $dots - 2)),
        ]);
    }

    private function line(Terminal $terminal): string
    {
        return \vsprintf(' <fg=gray>%s</>', [
            \str_repeat('─', \max(0, $terminal->getWidth() - 2)),
        ]);
    }

    private function row(Terminal $terminal, string $title, string $suffix): string
    {
        $dots = $terminal->getWidth() - 6
            - \mb_strlen(\strip_tags($title))
            - \mb_strlen(\strip_tags($suffix));

        return \vsprintf('  %s <fg=gray>%s</> %s', [
            '<options=bold>' . $title . '</>',
            \str_repeat('.', \max(0, $dots)),
            $suffix,
        ]);
    }

    /**
     * @param iterable<array-key, Integration> $integrations
     */
    private function getAccountIntegrationRows(iterable $integrations): iterable
    {
        foreach ($integrations as $integration) {
            yield $integration => [
                'ID' => $integration->id->toString(),
                'DSN' => (string) $integration->dsn,
                'Login' => $integration->login ?? self::TPL_NONE,
                'Email' => $integration->email ?? self::TPL_NONE,
                'Avatar URL' => $integration->avatar ?? self::TPL_NONE,
                'Created At' => $integration->createdAt->format(self::FMT_DATE),
                'Updated At' => $integration->updatedAt?->format(self::FMT_DATE) ?? self::TPL_NONE,
            ];
        }
    }

    private function getAccountInfoRows(Account $account): iterable
    {
        return [
            'ID' => $account->id->toString(),
            'Login' => $account->login,
            'Password' => $account->password->isPasswordProtected()
                ? '<fg=green>✓</>'
                : '<fg=red>✕</>',
            'Roles' => (string) $account->roles ?: self::TPL_NONE,
            'Created At' => $account->createdAt->format(self::FMT_DATE),
            'Updated At' => $account->updatedAt?->format(self::FMT_DATE) ?? self::TPL_NONE,
        ];
    }
}
