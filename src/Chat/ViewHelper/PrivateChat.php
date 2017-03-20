<?php
namespace Chat\ViewHelper;

use Zend\View\Helper\AbstractHelper;
use Auth\AuthService;
use Auth\Finder\UserFinder;

final class PrivateChat extends AbstractHelper
{
    private $authService;
    private $userFinder;

    public function __construct(AuthService $service, UserFinder $userFinder)
    {
        $this->authService = $service;
        $this->userFinder  = $userFinder;
    }

    public function __invoke($username)
    {
        if (!$this->authService->hasIdentity()) {
            return false;
        }

        $otherUser = $this->userFinder->findByUsername($username);

        if (!$otherUser) {
            return false;
        }

        $userId1 = $otherUser->id;
        $userId2 = $this->authService->getIdentity()->id;

        if ($userId1 > $userId2) {
            return "{$userId1}:{$userId2}";
        }

        return "{$userId2}:{$userId1}";
    }
}
