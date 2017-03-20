<?php
namespace Auth\Model;

use Ramsey\Uuid\Uuid;

final class UserId
{
    /**
     * @var Uuid
     */
    private $uuid;

    /**
     * @return UserId
     */
    public static function generate()
    {
        return new self(Uuid::uuid4()->toString());
    }

    /**
     * @param $userId
     * @return UserId
     */
    public static function fromString($userId)
    {
        return new self(Uuid::fromString($userId)->toString());
    }

    /**
     * @param Uuid $uuid
     */
    private function __construct($uuid)
    {
        $this->uuid = $uuid;
    }

    /**
     * @return string
     */
    public function toString()
    {
        return $this->uuid;
    }

    /**
     * @param UserId $other
     * @return bool
     */
    public function sameValueAs(UserId $other)
    {
        return $this->toString() === $other->toString();
    }
}
