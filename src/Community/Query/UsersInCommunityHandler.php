<?php
namespace Community\Query;

use Community\Finder\CommunityFinder;

final class UsersInCommunityHandler
{
    private $finder;

    public function __construct(CommunityFinder $finder)
    {
        $this->finder = $finder;
    }

    public function __invoke(UsersInCommunity $query)
    {
        return $this->finder->findRandomMembers($query->communityId, 250);
    }
}
