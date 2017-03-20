<?php
namespace Common\ViewHelper;

use Auth\AuthService;

use Zend\View\Helper\AbstractHelper;
use Aura\Session\Session as AuraSession;

final class CsrfToken extends AbstractHelper
{
    private $session;

    public function __construct(AuraSession $session)
    {
        $this->session = $session;
    }

    public function __invoke()
    {
        return htmlspecialchars($this->session->getCsrfToken()->getValue(), ENT_QUOTES, 'UTF-8');
    }
}
