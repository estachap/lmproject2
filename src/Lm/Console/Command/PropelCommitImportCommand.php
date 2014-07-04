<?php
namespace Lm\Console\Command;

use Symfony\Component\Console\Command\Command;
use Symfony\Component\Console\Input\InputArgument;
use Symfony\Component\Console\Input\InputInterface;
use Symfony\Component\Console\Input\InputOption;
use Symfony\Component\Console\Output\OutputInterface;
use Lm\Console\Helpers\CurlHelper;
use Lm\Console\Helpers\PropelAtomFeed;

class PropelCommitImportCommand extends Command
{
	protected function configure()
	{
		$this
            ->setName('propelcommit:import_to_db')
            ->setDescription('Import the master.atom commit entries to database')
            ->addArgument(
                'date',
                InputArgument::OPTIONAL,
                'Which entries you wnat to import?'
            )            
        ;
	
	}
	
	protected function execute(InputInterface $input, OutputInterface $output)
	{
		$date = $input->getArgument('date');
		//we don't anything right now with this parameter
		
		//check if master.atom.txt' exists
		$filename = __DIR__ .'\\master.atom.txt';
		$file = fopen( $filename, 'r');
		
		if($file){
			fclose($file);
			
			//process the atom feed
			$this->processAtomCommit($filename);
			
			$output->writeln('Done!\n');
		}else {
			$output->writeln('File ' . __DIR__ .'\\master.atom.txt was not found' );
		}
	
	}
	
	/**
	* Process the xml master.atom fetched from github repo
	*
	* @param string fileaname
	**/
	private function processAtomCommit($filename)
	{
		$propelcommit = new PropelAtomFeed($filename);
		
		$propelcommit->saveNewCommits();
		
	}
}

?>