<?php

namespace App\Command;

use App\Entity\User;
use DateTimeImmutable;
use Doctrine\ORM\EntityManagerInterface;
use Symfony\Component\Console\Command\Command;
use Symfony\Component\Console\Input\InputArgument;
use Symfony\Component\Console\Input\InputInterface;
use Symfony\Component\Console\Output\OutputInterface;
use Symfony\Component\Console\Question\Question;
use Symfony\Component\Console\Style\SymfonyStyle;
use Symfony\Component\PasswordHasher\Hasher\UserPasswordHasherInterface;

class CreateAdministratorCommand extends Command
{
    public function __construct(
        private readonly UserPasswordHasherInterface $passwordHasher,
        private readonly EntityManagerInterface $entityManager) {
        parent::__construct('app:create-admin');
    }

    protected function configure(): void
    {
        $this->setDescription('Creates an administrator user.');
        $this->addArgument('email', InputArgument::REQUIRED, 'The email of the administrator.')
        ->addArgument('password', InputArgument::REQUIRED, 'The password of the administrator.');
    }

    protected function execute(InputInterface $input, OutputInterface $output): int
    {
        $helper = $this->getHelper('question');
        $email = $input->getArgument('email');
        if(!$email){
            $question = new Question('Please enter the email of the administrator: ');
            $email = $helper->ask($input, $output, $question);
        }
        $password = $input->getArgument('password');
        if(!$password){
            $question = new Question('Please enter the password of the administrator: ');
            $question->setHidden(true);
            $question->setHiddenFallback(false);
            $password = $helper->ask($input, $output, $question);
        }
        $admin = new User();
        $admin->setEmail($email)
            ->setRoles(['ROLE_ADMIN'])
            ->setCreatedAt(new DateTimeImmutable())
            ->setStep('B');
        $admin->setPassword($this->passwordHasher->hashPassword($admin, $password));

        $this->entityManager->persist($admin);
        $this->entityManager->flush();

        $output->writeln(sprintf('Administrator user "%s" created successfully.', $email));
        return Command::SUCCESS;
    }

}
