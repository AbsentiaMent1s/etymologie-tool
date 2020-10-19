<?php
namespace Console\App\Commands;
 
use Symfony\Component\Console\Command\Command;
use Symfony\Component\Console\Input\InputInterface;
use Symfony\Component\Console\Output\OutputInterface;
use Symfony\Component\Console\Input\InputArgument;
use Symfony\Component\Console\Style\SymfonyStyle;
 
class HelloWorldCommand extends Command
{
    protected static $defaultName = 'hello-style';

    protected function configure()
    {
	$this->setDescription('Test command');
	$this->setHelp('This command allows you to.....');
    }

    protected function execute(InputInterface $input, OutputInterface $output)
    {
	$io = new SymfonyStyle($input, $output);
	$io->title('test title?');

	$output->writeln('<info>INFO</info> || <comment>COMMENT</comment> || <question>QUESTION</question> || <error>ERROR</error>');

	$output->writeln('');
	$output->writeln(' (*-==========-*) ');
	$io->newline();

	$output->writeln('<fg=green>foo</> || <fg=black;bg=cyan>foo</> || <bg=yellow;options=bold>foo</> || <options=bold,underscore>foo</>');

	$io->newLine(2);

	$io->table(
	    ['Header 1', 'Header 2'],
	    [
	        ['Cell 1-1', 'Cell 1-2'],
	        ['Cell 2-1', 'Cell 2-2'],
	        ['Cell 3-1', 'Cell 3-2'],
	    ]
	);

	$io->progressStart(100);
	for ($i = 0; $i<5; $i++) {
		sleep(1);
		$io->progressAdvance(20);
	}

	$io->newLine(2);

        return Command::SUCCESS;
    }
}
