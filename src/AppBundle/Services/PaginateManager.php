<?php
namespace AppBundle\Services;
class PaginateManager
{
    public $limit;
    public function __construct($limit)
    {
        $this->limit = $limit;
    }
    public function paginate($thisPage, $objectArray)
    {
        if ($thisPage === null) {
            $thisPage = 1;
        }
        $limit = $this->limit;
        $maxPages = ceil($objectArray->count() / $limit);
        $result = array($maxPages, $thisPage, $limit);
        return $result;
    }
}