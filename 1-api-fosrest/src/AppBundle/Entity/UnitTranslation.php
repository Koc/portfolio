<?php

namespace AppBundle\Entity;

use Doctrine\ORM\Mapping as ORM;
use Knp\DoctrineBehaviors\Model as ORMBehaviors;

/**
 * @ORM\Entity
 * @ORM\Table()
 */
class UnitTranslation
{
    use ORMBehaviors\Translatable\TranslationMethods;

    /**
     * @var integer
     *
     * @ORM\Id()
     * @ORM\Column(name="id", type="integer")
     */
    protected $id;

    /**
     * @var string
     *
     * @ORM\Id()
     * @ORM\Column(name="lang", type="string", length=2)
     */
    protected $locale;

    /**
     * @var mixed
     */
    protected $translatable;

    /**
     * @var string
     * @ORM\Column(type="string", length=200, nullable=false)
     */
    protected $name;

    /**
     * Set id
     *
     * @param integer $id
     */
    public function setId($id)
    {
        $this->id = $id;
    }

    /**
     * @return string
     */
    public function getName()
    {
        return $this->name;
    }

    /**
     * @param string $name
     */
    public function setName($name)
    {
        $this->name = (string)$name;
    }

}
