<?php
declare(strict_types=1);

namespace App\Command;

use App\Entity\User;
use Doctrine\ORM\EntityManager;
use RuntimeException;
use Symfony\Component\Console\Attribute\AsCommand;
use Symfony\Component\Console\Command\Command;
use Symfony\Component\Console\Helper\QuestionHelper;
use Symfony\Component\Console\Input\InputInterface;
use Symfony\Component\Console\Input\InputOption;
use Symfony\Component\Console\Output\OutputInterface;
use Symfony\Component\Console\Question\Question;
use Symfony\Component\Console\Style\SymfonyStyle;
use Symfony\Component\PasswordHasher\Hasher\UserPasswordHasherInterface;

#[AsCommand(
    name: 'dashboard:create-user',
    description: 'Creates a user for the dashboard',
)]
final class DashboardCreateUserCommand extends Command
{
    public const DEFAULT_VALUE = 'admin';

    public function __construct(
        private EntityManager $entityManager,
        private UserPasswordHasherInterface $passwordHasher,
    ) {
        parent::__construct();
    }

    protected function configure(): void
    {
        $this
            ->addOption('show_creds', null, InputOption::VALUE_NONE, 'Show user creds after input.')
        ;
    }

    protected function execute(InputInterface $input, OutputInterface $output): int
    {
        $io = new SymfonyStyle($input, $output);
        $helper = new QuestionHelper();

        $validator = static function ($answer) {
            if (empty($answer)) {
                return self::DEFAULT_VALUE;
            }
            if (!preg_match('/^[a-zA-Z0-9.@]+$/', $answer)) {
                throw new RuntimeException('Invalid value.');
            }

            return $answer;
        };

        $questionText = '<fg=green> Please enter the %s (default: </>'
            . '<fg=yellow>%s</>'
            . '<fg=green>)</>:'
            . \PHP_EOL
            . ' > ';
        $usernameQuestionText = sprintf(
            $questionText,
            'username',
            self::DEFAULT_VALUE
        );
        $passwordQuestionText = sprintf(
            $questionText,
            'password',
            self::DEFAULT_VALUE
        );

        $usernameQuestion = new Question($usernameQuestionText);
        $usernameQuestion->setValidator($validator);
        /** @var string $username */
        $username = $helper->ask($input, $output, $usernameQuestion);

        $passwordQuestion = new Question($passwordQuestionText);
        $passwordQuestion->setValidator($validator);
        $passwordQuestion->setHidden(true);
        /** @var string $password */
        $password = $helper->ask($input, $output, $passwordQuestion);

        if ($input->getOption('show_creds')) {
            $io->note(sprintf('Your creds: %s, %s', $username, $password));
        }

        $user = new User();
        $user->setUsername($username);
        $user->setPassword(
            $this->passwordHasher->hashPassword($user, $password)
        );
        $user->setRoles(['ROLE_ADMIN']);
        $this->entityManager->persist($user);
        $this->entityManager->flush();

        if (!\is_int($user->getId())) {
            $io->error('User not created!');

            return Command::FAILURE;
        }

        $io->success('New user created!');

        return Command::SUCCESS;
    }
}
