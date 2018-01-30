<?php

namespace App\Entity\Price;

use ApiPlatform\Core\Annotation\ApiResource;
use Doctrine\ORM\Mapping as ORM;
use Symfony\Component\Serializer\Annotation\Groups;

/**
 * @ORM\Table(name="ukr_tovgroup_tovar", schema="price")
 * @ApiResource(attributes={
 *     "force_eager"=false,
 *     "normalization_context"={"groups"={"price_commodity"}}
 *     })
 * @ORM\Entity
 */
class GroupCommodity
{
    /**
     * @ORM\Id
     * @ORM\ManyToOne(targetEntity="Group")
     * @ORM\JoinColumn(name="cod_gr", referencedColumnName="cod_gr", nullable=false)
     *
     * @var Group
     */
    private $group;

    /**
     * @ORM\Id
     * @ORM\ManyToOne(targetEntity="Commodity", inversedBy="groupCommodities")
     * @ORM\JoinColumn(name="cod_tov", referencedColumnName="cod_tov", nullable=false)
     *
     * @var Commodity
     */
    private $commodity;

    /**
     * @var integer
     * @ORM\Column(type="integer", name="sort_order")
     */
    private $position;

    /**
     * @return Group
     */
    public function getGroup(): ? Group
    {
        return $this->group;
    }

    /**
     * @param Group $group
     */
    public function setGroup(Group $group): void
    {
        $this->group = $group;
    }

    /**
     * @return Commodity
     */
    public function getCommodity(): ? Commodity
    {
        return $this->commodity;
    }

    /**
     * @param Commodity $commodity
     */
    public function setCommodity(Commodity $commodity): void
    {
        $this->commodity = $commodity;
    }

    /**
     * @return int
     */
    public function getPosition(): ? int
    {
        return $this->position;
    }

    /**
     * @param int|null $position
     */
    public function setPosition(?int $position): void
    {
        $this->position = $position;
    }

    /**
     * @Groups({"price_commodity"})
     */
    public function getGroupId()
    {
        return $this->group->getId();
    }

}
