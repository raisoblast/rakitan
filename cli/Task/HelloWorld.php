<?php
namespace Task;

use Symfony\Component\Console\Input\InputInterface;
use Symfony\Component\Console\Output\OutputInterface;

class HelloWorld extends BaseTask
{
    protected function configure()
    {
        $this->setName('hello:world')->setDescription('Hello world!');
    }

    protected function execute(InputInterface $input, OutputInterface $output)
    {
        $output->writeln('Hello world!');
    }
}