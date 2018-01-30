<?php

namespace AppBundle\Entity;

use Doctrine\ORM\Mapping as ORM;

use Knp\DoctrineBehaviors\Model as ORMBehaviors;

/**
 * @ORM\Table(name="bid_commodity")
 * @ORM\Entity
 *
 * @method string getName()
 */
class BidCommodity
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
     * @var bool
     * @ORM\Column(type="boolean", options={"default": true})
     */
    private $isActive;

    /**
     * BidCommodity constructor.
     */
    public function __construct()
    {
        $this->isActive = true;
    }

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

    /**
     * @return bool
     */
    public function isActive()
    {
        return $this->isActive;
    }

    /**
     * @param bool $isActive
     */
    public function setIsActive($isActive)
    {
        $this->isActive = $isActive;
    }
}
