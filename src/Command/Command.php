<?php

namespace App\Command;

use Symfony\Component\Console\Command\Command as SymfonyCommand;
use Doctrine\ORM\EntityManagerInterface;
use App\Entity\Command as CommandEntity;
use App\Entity\CommandItem as CommandItemEntity;

abstract class Command extends SymfonyCommand
{

    protected $em;
    private   $command;

    public function __construct(EntityManagerInterface $em)
    {
        parent::__construct();
        $this->em = $em;
    }

}