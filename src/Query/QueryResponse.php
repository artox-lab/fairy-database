<?php namespace Fairy\Query;

class QueryResponse
{
    protected $select;
    /** @var ResultsProcessor */
    protected $resultsProcessor;
    protected $results;

    public function __construct($select, $resultsProcessor, $results)
    {
        $this->select = $select;
        $this->resultsProcessor = $resultsProcessor;
        $this->result = $results;
    }

    public function plain()
    {
        return $this->results;
    }

    public function formatted()
    {
        return $this->resultsProcessor->processResult($this->select, $this->results);
    }
}