<?php
namespace Lm\Console\Command;

use Symfony\Component\Console\Command\Command;
use Symfony\Component\Console\Input\InputArgument;
use Symfony\Component\Console\Input\InputInterface;
use Symfony\Component\Console\Input\InputOption;
use Symfony\Component\Console\Output\OutputInterface;
use Lm\Console\Helpers\CurlHelper;

class PropelCommitAtomFeedCommand extends Command
{
	protected function configure()
	{
		$this
            ->setName('propelcommit:get_atom_feed')
            ->setDescription('Import the maste.atom commit xml from Propel github repo')
            ->addArgument(
                'date',
                InputArgument::OPTIONAL,
                'What is the first date from which to commit'
            )            
        ;
	
	}
	
	protected function execute(InputInterface $input, OutputInterface $output)
	{
		$date = $input->getArgument('date');
		//we don't anything right now with this parameter
		
		$curl = new CurlHelper();
		$url = 'https://github.com/propelorm/Propel/commits/master.atom';
		$response = $curl->curl_get($url);
		
		if($response) {
			//Save the result into an text file
			$filename = __DIR__ .'\\master.atom.txt';
			$file = fopen( $filename, 'w');
			fwrite($file, $response);
			fclose($file);
			
			$output->writeln('Done!\n');
		}else {
			$output->writeln('The response is empty');
		}
	
	}


}

?>