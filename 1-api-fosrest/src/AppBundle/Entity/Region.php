<?php

namespace AppBundle\Entity;

use Doctrine\ORM\Mapping as ORM;

use Knp\DoctrineBehaviors\Model as ORMBehaviors;

/**
 * @ORM\Table(name="region")
 * @ORM\Entity
 *
 * @method string getName()
 */
class Region
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
     * @var string
     * @ORM\Column(type="string", length=2)
     */
    private $country;

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
     * @return string
     */
    public function getCountry()
    {
        return $this->country;
    }

    /**
     * @param string $country
     */
    public function setCountry($country)
    {
        $this->country = $country;
    }
}
