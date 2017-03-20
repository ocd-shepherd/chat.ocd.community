<?php
namespace Community\Query;

use Community\Finder\CommunityFinder;

final class FeaturedCommunitiesHandler
{
    private $finder;

    public function __construct(CommunityFinder $finder)
    {
        $this->finder = $finder;
    }

    public function __invoke(FeaturedCommunities $query)
    {
        return $this->finder->findAllFeatured();
    }
}

