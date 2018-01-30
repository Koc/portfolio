<?php

namespace App\Api\DataPersister;

use ApiPlatform\Core\DataPersister\DataPersisterInterface;
use ApiPlatform\Core\Util\ClassInfoTrait;
use App\Api\CompositeModel;
use Doctrine\Common\Persistence\ManagerRegistry;
use Doctrine\Common\Persistence\ObjectManager as DoctrineObjectManager;

class CompositeModelDataPersister implements DataPersisterInterface
{
    use ClassInfoTrait;

    private $managerRegistry;

    public function __construct(ManagerRegistry $managerRegistry)
    {
        $this->managerRegistry = $managerRegistry;
    }

    public function supports($data): bool
    {
        return $data instanceof CompositeModel or is_iterable($data);
    }

    public function persist($data)
    {
        /** @var CompositeModel $data */
        $managers = new \SplObjectStorage();
        $persistentObjects = $data instanceof CompositeModel ? $data->getPersistentObjects() : $data;
        foreach ($persistentObjects as $persistentObject) {
            if (!$manager = $this->getManager($persistentObject)) {
                continue;
            }

            $manager->persist($persistentObject);
            $managers->attach($manager);
        }

        /** @var DoctrineObjectManager $manager */
        foreach ($managers as $manager) {
            $manager->flush();
        }
    }

    /**
     * {@inheritdoc}
     */
    public function remove($data)
    {
        /** @var CompositeModel $data */
        $managers = new \SplObjectStorage();
        $persistentObjects = $data instanceof CompositeModel ? $data->getPersistentObjects() : $data;
        foreach ($persistentObjects as $persistentObject) {
            if (!$manager = $this->getManager($persistentObject)) {
                continue;
            }

            $manager->remove($persistentObject);
            $managers->attach($manager);
        }

        /** @var DoctrineObjectManager $manager */
        foreach ($managers as $manager) {
            $manager->flush();
        }
    }

    /**
     * Gets the Doctrine object manager associated with given data.
     *
     * @param mixed $data
     *
     * @return DoctrineObjectManager|null
     */
    private function getManager($data)
    {
        return is_object($data) ? $this->managerRegistry->getManagerForClass($this->getObjectClass($data)) : null;
    }
}
