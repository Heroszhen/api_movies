<?php

namespace App\EventListener;

use App\Entity\Movie;
use Doctrine\ORM\Event\PreRemoveEventArgs;
use Doctrine\ORM\Events;
use Doctrine\Bundle\DoctrineBundle\Attribute\AsEntityListener;
use Doctrine\ORM\EntityManagerInterface;

#[AsEntityListener(event: Events::preRemove, method: 'preRemove', entity: Movie::class)]
final class MovieRemoveListener
{
    public function __construct(private EntityManagerInterface $entityManager)
    {}

    public function preRemove(Movie $movie, PreRemoveEventArgs $event): void
    {
        foreach($movie->getActors() as $actor) {
            $movie->removeActor($actor);
        }
        $this->entityManager->flush();
    }
}
