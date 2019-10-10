<?php

namespace Torvic\Common\Infrastructure\Persistence\Doctrine\Repository;

use Doctrine\DBAL\Query\QueryBuilder;
use Doctrine\ORM\EntityManager;
use Doctrine\ORM\EntityNotFoundException;
use Doctrine\ORM\EntityRepository;
use Torvic\Common\Domain\DomainRepository;
use Torvic\Common\Domain\Model\Entity;

abstract class DoctrineRepository implements DomainRepository
{
    /**
     * @var EntityRepository
     */
    protected $repository;

    protected $entityManager;

    protected $table;

    protected $exists;

    protected $transactionMode;


    /**
     * DoctrineRepository constructor.
     * @param EntityManager $entityManager
     */
    public function __construct(
        EntityManager $entityManager
    )
    {
        $this->entityManager = $entityManager;
        $this->repository = $this->entityManager->getRepository($this->getEntityClassName());
        $this->table = $this->entityManager->getClassMetadata($this->getEntityClassName())->getTableName();
    }

    public function beginTransaction()
    {
        $this->entityManager->beginTransaction();
        $this->transactionMode = true;
    }

    /**
     * @throws \Doctrine\DBAL\ConnectionException
     */
    public function rollback()
    {
        $this->transactionMode = false;
        $this->entityManager->rollback();

        $connection = $this->entityManager->getConnection();
        if (!$connection->isTransactionActive() || $connection->isRollbackOnly()) {
            $this->entityManager->close();
        }
    }

    /**
     * @param Entity $entity
     * @return Entity
     * @throws \Doctrine\ORM\ORMException
     * @throws \Doctrine\ORM\OptimisticLockException
     */
    public function update(Entity $entity)
    {
        if (!$this->entityManager->isOpen()) {
            $this->entityManager = $this->entityManager->create(
                $this->entityManager->getConnection(),
                $this->entityManager->getConfiguration()
            );
        }

        $this->entityManager->merge($entity);
        $this->entityManager->flush();

        return $entity;
    }

    public function insert(Entity $entity)
    {
        $this->entityManager->persist($entity);
        $this->entityManager->flush();

        return $entity;
    }

    abstract protected function getEntityClassName();

    abstract protected function getCollectionClassName();

    abstract protected function createQuery();

    /**
     * @param Entity $entity
     * @throws \Doctrine\ORM\ORMException
     */
    protected function insertOrUpdate(Entity $entity)
    {
        /** TODO: Always try to insert, updates pending */
        $this->entityManager->persist($entity);
        $this->entityManager->flush();
    }

    /**
     * @param $entityClassName
     * @return string
     */
    protected function getEntityTable($entityClassName)
    {
        return $this->entityManager->getClassMetadata($entityClassName)->getTableName();
    }

    protected function getQueryBuilder()
    {
        return new QueryBuilder($this->entityManager->getConnection());
    }

    /**
     * @param $result
     */
    protected function checkEmptySingleResult($result)
    {
        if (empty($result)) {
            throw new EntityNotFoundException($this->getEntityClassName() . ' not found');
        }
    }

    /**
     * @param $result
     */
    protected function checkEmptyColectionResult($result)
    {
        if (empty($result)) {
            throw new EntityNotFoundException('No ' . $this->getEntityClassName() . ' found with this criteria');
        }
    }

    protected function buildCollection($result)
    {
        $collectionClassName = $this->getCollectionClassName();
        $collection = new $collectionClassName;

        foreach($result as $row)
        {
            $collection->add($row);
        }

        return $collection;
    }

    /**
     * @param $filterCriteria
     */
    public function findBy($filterCriteria)
    {
        $result = $this->repository->findBy($filterCriteria);

        return $this->buildCollection($result);
    }

    /**
     * @param Entity $entity
     * @throws \Doctrine\ORM\ORMException
     * @throws \Doctrine\ORM\OptimisticLockException
     */
    public function remove(Entity $entity)
    {
        $this->entityManager->remove($entity);
        $this->entityManager->flush();
    }

}