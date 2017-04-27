<?php

namespace AppBundle\Entity;

use Doctrine\ORM\Mapping as ORM;
/**
 * Class CategoryCollection
 * @package AppBundle\Entity
 * @ORM\Table(name="category_collection")
 * @ORM\Entity
 */
class CategoryCollection
{
    /**
     * @var int
     *
     * @ORM\Column(name="id", type="integer")
     * @ORM\Id
     * @ORM\GeneratedValue(strategy="AUTO")
     */
    private $id;

    /**
     * @ORM\ManyToOne(targetEntity="Category", inversedBy="categoriesCollection")
     * @ORM\JoinColumn(name="category_id", referencedColumnName="id", onDelete="SET NULL")
     */
    private $category;

    /**
     * @ORM\ManyToOne(targetEntity="Application\Sonata\PageBundle\Entity\Block", inversedBy="categoriesList")
     * @ORM\JoinColumn(name="block_id", referencedColumnName="id", onDelete="SET NULL")
    */
    private $block;

    /**
     * @var integer
     * @ORM\Column(type="integer", nullable=true)
     */

    private $position;

    /**
     * @return mixed
     */
    public function getId()
    {
        return $this->id;
    }

    /**
     * @param mixed $id
     */
    public function setId($id)
    {
        $this->id = $id;
    }

    /**
     * @return mixed
     */
    public function getCategory()
    {
        return $this->category;
    }

    /**
     * @param mixed $category
     */
    public function setCategory($category)
    {
        $this->category = $category;
    }

    /**
     * @return mixed
     */
    public function getBlock()
    {
        return $this->block;
    }

    /**
     * @param mixed $block
     */
    public function setBlock($block)
    {
        $this->block = $block;
    }

    /**
     * @return int
     */
    public function getPosition()
    {
        return $this->position;
    }

    /**
     * @param int $position
     */
    public function setPosition($position)
    {
        $this->position = $position;
    }
}