<?php

namespace Connection;

use Doctrine\ORM\EntityManager;

interface ConnectionInterface
{

    public function getConnection(): EntityManager;

}
