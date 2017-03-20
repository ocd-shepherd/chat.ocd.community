<?php
namespace Community\Query;

use Community\Finder\CommunityFinder;

final class CommunityPathAvailableHandler
{
    private $finder;

    public function __construct(CommunityFinder $finder)
    {
        $this->finder = $finder;
    }

    public function __invoke(CommunityPathAvailable $query)
    {
        return $this->finder->pathIsAvailable($query->path);
    }
}
