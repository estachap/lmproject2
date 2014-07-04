<?php
namespace Lm\Console\Helpers;

use Lm\CommitBundle\Model\Lmlog;
use Lm\CommitBundle\Model\LmlogQuery;

class PropelLmlog
{
	/**
	 * 
	 *
	 * @var		Lmlog
	 */
	private $_lmlog;
	private $_lmlogQuery;
	
	const ERROR 	= 1000;
	const WARNING 	= 1001;
	const NOTE 		= 1002;
	
	function __construct()
	{
		
		$this->_lmlogQuery = new LmlogQuery();
		
		
	}
	
	public function logMessage($message, $logType = self::NOTE)
	{
		$this->_lmlog = new Lmlog();
		$this->_lmlog->setLogmessage($message);
		$this->_lmlog->setLogtype($logType);
		$dt = new \DateTime();
		$this->_lmlog->setLogdate($dt->format('Y-m-d h:i:s'));
		
		$this->_lmlog->save();
	}
}

?>