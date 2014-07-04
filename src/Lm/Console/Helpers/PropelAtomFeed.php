<?php 

namespace Lm\Console\Helpers;

use Lm\CommitBundle\Model\Author;
use Lm\CommitBundle\Model\AuthorQuery;
use Lm\CommitBundle\Model\File;
use Lm\CommitBundle\Model\FileQuery;
use Lm\CommitBundle\Model\Propelcommit;
use Lm\CommitBundle\Model\PropelcommitQuery;

/** 
/* PropelAtomFeed class - represents the information on the xml master.atom file containig the commits list of the Propel project
/*
**/
class PropelAtomFeed
{
	/**
     * Array of Propelcommit objects
     *
     * @var		array Propelcommit[]
     */
	private $_propelCommits;
	
	/**
     * Array of File objects
     *
     * @var		array File[]
     */
	private $_commitFiles;
	 
	/**
     * Array of Author objects
     *
     * @var		array Author[]
     */
	private $_authors;
	
	/**
     * The content of master.atom as a SimpleXmlElement object
     * The file content is expected to be an xml string
	 *
     * @var		SimpleXmlElement
     */
	private $_xmlfeed;	
	
	/**
     * Update date of the master.atom
     *
     * @var		datetime
     */
	private $_feed_update_date;
	
	/**
     * Latest update date in the database table Propelcommit
     *
     * @var		datetime
     */
	private $_latest_db_update_date;
	 
	/**
     * Constructor
     *
     * @param string $feedfile - the name of the master.atom feed file
     */
	public function __construct($feedfile = 'master.atom.txt')
	{
		//open the file and extract the content
		$file = fopen( $feedfile, 'r');
		if( $file){
			$this->_xmlfeed = simplexml_load_string(fread($file, filesize($feedfile)));
		} else {
			throw new \Exception('Failed to open file master.atom');
		}
		fclose($file);
		//the update date of the master.atom feed
		$this->_feed_update_date = $this->_xmlfeed->updated;

		//the latest update date in database
		$this->setLatestEntryUpdateDb();
		
		//process the entries and populate the class arrays
		foreach($this->_xmlfeed->entry as $entry){
			//add Author to array
			$author = new Author();
			$author->setName($entry->author->name);
			$author->setUri($entry->author->uri);
			$this->_authors[] = $author;
			
			//retrieve the author from db
			$author_db = AuthorQuery::create()
				  ->filterByName($author->getName())
				  ->filterByUri($author->getUri())
				  ->findOne();
			//add it to db if not found
			if(!$author_db)
				$autor->save();
				
		
			//add entry Propelcommit to array
			$commit = new Propelcommit();
			$commit->setTitle($entry->title);
			$commit->setLink($entry->link);
			$commit->setContent($entry->content);
			$commit->setUpdateDate($entry->updated);
			$commit->setAuthorId($author->getId());
			$this->_propelCommits[] = $commit;
			
		}
		
	}
	
	/**
     * Add objects to array of Author-s
     *
     * 
     */
	private function toArrayAuthors()
	{
		foreach($this->_xmlfeed->entry as $entry){
			//add Author to array
			$author = new Author();
			$author->setName($entry->author->name);
			$author->setUri($entry->author->uri);
			$this->_authors[] = $author;
			
			//retrieve the author from db
			$author_db = AuthorQuery::create()
				  ->filterByName($author->getName())
				  ->filterByUri($author->getUri())
				  ->findOne();
			//add it to db if not found
			if(!$author_db)
				$autor->save();				
		}		
	}
	
	/**
     * Add objects to array of Propelcommits-s
     *
     * 
     */
	private function toArrayCommits()
	{
		foreach($this->_xmlfeed->entry as $entry){
			//add entry Propelcommit to array
			$commit = new Propelcommit();
			$commit->setTitle($entry->title);
			$commit->setLink($entry->link);
			$commit->setContent($entry->content);
			$commit->setUpdateDate($entry->updated);			
			
			//find the author in the authors array
			foreach($this->_authors as $author){
				if($author->getName() == $entry->author->name && $author->getUri == $entry->author->uri)
					$commit->setAuthorId($author->getId());					
			}
			$this->_propelCommits[] = $commit;
			
		}	
	}
	
	/**
     * Sets the correct author id to Propelcommits-s
     * This function is just a control point to make sure the author id is set
     * The author id should be already set either during the constructor call or toArrayCommits call 
	 *
     */
	private function checkCommitEntryAuthor(){
		foreach($this->_propelComits as $commit){
			$author_id = $commit->getAuthorId();
			if( empty($author_id)){
				//find the entry for this commit in the xmlfeed
				$commit_id = $commit->getId();
				$entry = $this->_xmlfeed->xpath("/feed/entry[id={$commit_id}]");
				//find the author in the authors array
				foreach($this->_authors as $author){
					if($author->getName() == $entry->author->name && $author->getUri == $entry->author->uri)
						$commit->setAuthorId($author->getId());					
				}
			}
		}
	}
	
	/**
     * Add objects to array of File-s
     *
     * 
     */
	private function toArrayFiles()
	{
	
	}
	
	/**
     * Checks for new commits
     *
     * @return 
     */
	public function checkNewCommits()
	{
	
	}
	
	/**
     * Retrieve the latest update date from database
     *
     * @return string _latest_db_update_date
     */
	public function getLatestEntryUpdateDb()
	{
		if(empty($this->_latest_db_update_date))
			$this->setLatestEntryUpdateDb();
		
		return $this->_latest_db_update_date;
	}
	
	/**
     * Retrieve the latest update date from database and sets it to 
	 * member var _latest_db_update_date
     *
     * 
     */
	public function setLatestEntryUpdateDb()
	{	
		$commit = PropelcommitQuery::create()
				->orderByUpdateDate('desc')
				->findOne();
		if(!empty($commit)){
			$this->_latest_db_update_date = $commit->getUpdateDate();
		} else {
			throw new \Exception('Failed to retrieve a PropelCommit entry from database.');
		}
	}	

	/**
     * Check for new commits and insert them in database
     * All new authors in the current feed should have been saved previously 
	 * and their Id updated in the class authors array
	 *
     * This method could be improved by checking only the entries added after the latest date 
	 * stored in the database
     */
	public function saveNewCommits()
	{
		$query = new PropelcommitQuery();
		foreach($this->_propelCommits as $commit){
			$dt1 = new DateTime($commit->getUpdateDate());
			$dt2 = new DateTime($this->_latest_db_update_date);
			
			//check only the entries added after the latest date in database
			if( $dt1 > $dt2){
				//check if the commit already exists
				$c = $query->findPK($commit->getId());			
				if(!$c)
					$commit->save();
			}			
		}
	}
}

?>