<?php
namespace Community\Command;

use EvantSource\AggregateRepository;
use Community\Finder\CommunityFinder;
use Community\Model\Community;

final class CreateCommunityHandler
{
    private $communityFinder;

    private $repository;

    public function __construct(CommunityFinder $channelFinder, AggregateRepository $repository)
    {
        $this->communityFinder = $channelFinder;
        $this->repository = $repository;
    }

    public function __invoke(CreateCommunity $command)
    {
        if (!$this->communityFinder->pathIsAvailable($command->path)) {
            throw new \Exception('Community path is already registered.');
        }

        $channel = Community::create(
            $command->communityId,
            $command->userId,
            $command->name,
            $command->path
        );

        $this->repository->save($channel);
    }
}

