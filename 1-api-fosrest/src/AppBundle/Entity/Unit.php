<?php

namespace AppBundle\Entity;

use Doctrine\ORM\Mapping as ORM;

use Knp\DoctrineBehaviors\Model as ORMBehaviors;

/**
 * @ORM\Table(name="unit")
 * @ORM\Entity
 *
 * @method string getName()
 */
class Unit
{
    use ORMBehaviors\Translatable\Translatable;

    /**
     * @var integer
     * @ORM\Column(type="integer")
     * @ORM\Id
     * @ORM\GeneratedValue(strategy="AUTO")
     */
    private $id;

    /**
     * @param $method
     * @param $arguments
     * @return mixed
     */
    public function __call($method, $arguments)
    {
        if (!in_array(substr($method, 0, 3), ['get', 'set'])) {
            $method = 'get'.ucfirst($method);
        }

        return $this->proxyCurrentLocaleTranslation($method, $arguments);
    }

    /**
     * @return int
     */
    public function getId()
    {
        return $this->id;
    }
}
