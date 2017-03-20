<?php
declare(strict_types=1);

namespace Auth\Model;

class PasswordHash
{
    private $hash;

    private function __construct() {}

    public static function fromPlainText($plainText)
    {
        $self = new self;
        $self->hash = password_hash($plainText, PASSWORD_BCRYPT, ['cost' => 12]);
        return $self;
    }

    public static function fromHash($passwordHash)
    {
        $self = new self;
        $self->hash = $passwordHash;
        return $self;
    }

    public function matches($plainText)
    {
        return password_verify($plainText, $this->hash);
    }

    public function toString()
    {
        return $this->hash;
    }
}
