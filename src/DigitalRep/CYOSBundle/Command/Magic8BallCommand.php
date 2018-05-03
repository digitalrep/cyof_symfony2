<?php
namespace DigitalRep\CYOSBundle\Command;

use Symfony\Component\Console\Command\Command;
use Symfony\Component\Console\Input\InputArgument;
use Symfony\Component\Console\Input\InputInterface;
use Symfony\Component\Console\Input\InputOption;
use Symfony\Component\Console\Output\OutputInterface;

class Magic8BallCommand extends Command
{
    protected function configure()
    {
        $this
        ->setName('magic:8ball')
        ->setDescription('Magic 8 Ball predicts the future')
        ->addArgument('question', InputArgument::OPTIONAL,'What question do you ask the Magic 8 Ball?');
    }

    protected function execute(InputInterface $input, OutputInterface $output)
    {
        $answers = array('yes', 'no', 'maybe', 'it is a secret');
        $question = $input->getArgument('question');
        if ($question) {
            $response = $answers[rand(0, 3)];
        } else {
            $response = 'Hello';
        }
        $output->writeln($response);
    }
}
?>