<?php
namespace Console\App\Commands;

use Symfony\Component\Console\Command\Command;
use Symfony\Component\Console\Input\InputInterface;
use Symfony\Component\Console\Output\OutputInterface;
use Symfony\Component\Console\Input\InputArgument;
use Symfony\Component\Console\Style\SymfonyStyle;

require_once(__DIR__ . '/Components/phpQuery-onefile.php');

class ThingExplainerCommand extends Command
{
    protected static $defaultName = 'explain';

    protected function configure()
    {
	$this->setDescription('Etymologie Helper');
	$this->setHelp('Met dit commando kun je de betekenis van een woord opzoeken.');

	$this->addArgument('woord', InputArgument::REQUIRED, 'Het woord waarvan je de betekenis wilt weten.'); 
    }

    protected function execute(InputInterface $input, OutputInterface $output)
    {
	$io = new SymfonyStyle($input, $output);
	$output->writeln([
        	'Etymologie van: "' . $input->getArgument('woord') .'"',
        	'============',
        	'',
    	]);
	$output->writeln('De betekenis van het woord "'.$input->getArgument('woord') .'" volgens... ');

	$output->writeln(' ');

	$section1 = $output->section();
	$section1->writeln('Searching the web...');

        $io->progressStart(100);
        for ($i = 0; $i<4; $i++) {
		sleep(1);
		$io->progressAdvance(25);
	}
	sleep(1);

	$section1->clear(2);

	$io->newLine(1);

	//$section2 = $output->section();
	$output->writeln($this->getHtmlFromWoordenOrg($input->getArgument('woord')));

	$output->writeln('<fg=red>Antoniem:</>');
	$output->writeln('- unknown...');

	$io->newLine();

	$output->writeln('<fg=red>Synoniemen:</>');

	$io->horizontalTable(
	    ['Contemporain', 'Nog een woord'],
	    [
	        ['Eigentijds', 'Cell 1-2'],
	        ['Heden', 'Cell 2-2'],
	        ['Hedendaags', ''],
	    ]
	);

	$io->newLine();

        return Command::SUCCESS;
    }

    protected function getHtmlFromWoordenOrg($word)
    {
        $url = "https://www.woorden.org/woord/$word";
        $html = file_get_contents($url);
        if ($html == false) return;

	$str = "\e[0;31;31m... woorden.org:\e[0m\n"; 

	$defHtml = $this->getTextBetweenTags($html, 'font', 0);

	$result =  strip_tags($this->getTextBetweenTags($defHtml, 'b', 0));
	if (!$result) return '';

	$str .= ' - ' . $result . "\n";

        //$str .= ' *' . strip_tags(pq('h2.inline')) . " [$url]\n";
        //$str .= '' . strip_tags(pq('font:first')) . "\n\n";

        return $str;
    }

    function getTextBetweenTags($string, $tagname, $spec = null) {
	$spec = ($spec === null)? 1: $spec;
    	$pattern = "/<$tagname ?.*>(.*)<\/$tagname>/";
    	preg_match($pattern, $string, $matches);

	if ($matches === null || !isset($matches[$spec])) return '';

        return $matches[$spec];
    }

}
