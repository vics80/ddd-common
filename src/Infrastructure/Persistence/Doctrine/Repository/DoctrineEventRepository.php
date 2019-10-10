<?php

namespace Torvic\Common\Infrastructure\Persistence\Doctrine\Repository;

use Doctrine\ORM\EntityManager;
use Torvic\Common\Domain\Event\PublishableDomainEvent;
use Torvic\Common\Domain\Model\Event\Event;
use Torvic\Common\Domain\Model\Event\EventRepository;
use Torvic\Common\Infrastructure\Service\PersistEventDataTransformer;

class DoctrineEventRepository extends DoctrineRepository implements EventRepository
{
    /**
     * @var PersistEventDataTransformer
     */
    private $dataTransformer;

    public function __construct(
        EntityManager $entityManager,
        PersistEventDataTransformer $dataTransformer
    )
    {
        parent::__construct($entityManager);

        $this->dataTransformer = $dataTransformer;
    }

    public function persist(PublishableDomainEvent $event)
    {
        if (!$this->entityManager->isOpen()) {
            $this->entityManager = $this->entityManager->create(
                $this->entityManager->getConnection(),
                $this->entityManager->getConfiguration()
            );
        }
        $event = $this->dataTransformer->transform($event);

        $query = $this->getQueryBuilder();

        if (!$event->id()) {
            $query->insert("event")
                ->setValue('user_id', ':user_id')
                ->setValue('type', ':type')
                ->setValue('event', ':event')
                ->setValue('occurred_on', ':occurred_on')
                ->setValue('name', ':name');
        } else {
            throw new \Exception("event must be immutable");
        }

        $query->setParameter(":user_id", $event->userId() ? $event->userId()->id() : 0);
        $query->setParameter(":type", $event->type());
        $query->setParameter(":event", $event->eventContent());
        $query->setParameter(":occurred_on", $event->occurredOn()->format("Y-m-d H:i:s"));
        $query->setParameter(":name", $event->name());

        $query->execute();

        if ($event->createdAt() == null) {
            $event->setCreatedAt(new \DateTime('now'));
        }
        return $event;
    }

    protected function createQuery()
    {
        return $this->entityManager->createQueryBuilder();
    }

    protected function getEntityClassName()
    {
        return Event::class;
    }

    protected  function getCollectionClassName()
    {
        // TODO: Implement getCollectionClassName() method.
    }
}