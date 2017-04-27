<?php

/**
 * This file is part of the <name> project.
 *
 * (c) <yourname> <youremail>
 *
 * For the full copyright and license information, please view the LICENSE
 * file that was distributed with this source code.
 */

namespace Application\Sonata\PageBundle\Entity;

use AppBundle\Entity\CategoryCollection;
use Doctrine\Common\Collections\ArrayCollection;
use Sonata\PageBundle\Entity\BaseBlock as BaseBlock;
use Knp\DoctrineBehaviors\Model\Translatable\Translatable;

/**
 * Class Block
 * @package Application\Sonata\PageBundle\Entity
 */
class Block extends BaseBlock
{
    use Translatable;

    /**
     * @var int $id
     */
    protected $id;

    /**
     * @var
     */
    private $gallery;

    /**
     * @var
     */
    private $categoriesList;

    /**
     * Category constructor.
     */
    public function __construct()
    {
        parent::__construct();
        $this->categoriesList = new ArrayCollection();
    }
    /**
     * Get id
     *
     * @return int $id
     */
    public function getId()
    {
        return $this->id;
    }

    /**
     * @return mixed
     */
    public function getGallery()
    {
        return $this->gallery;
    }

    /**
     * @param mixed $gallery
     */
    public function setGallery($gallery)
    {
        $this->gallery = $gallery;
    }


    /**
     * @return mixed
     */
    public function getCategoriesList()
    {
        return $this->categoriesList;
    }

    /**
     * @param mixed $categoriesList
     */
    public function setCategoriesList($categoriesList)
    {
        $this->categoriesList = $categoriesList;
    }

    /**
     * @param CategoryCollection $categoriesCollection
     * @return $this
     */
    public function addCategoriesList(CategoryCollection $categoriesCollection)
    {
        $categoriesCollection->setBlock($this);
        $this->categoriesList[] = $categoriesCollection;

        return $this;
    }

    /**
     * @param CategoryCollection $categoryCollection
     */
    public function removeCategoriesList(CategoryCollection $categoryCollection)
    {
        $this->categoriesList->removeElement($categoryCollection);
    }
}
