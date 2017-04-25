<?php

namespace Application\Sonata\PageBundle\Entity;

use Knp\DoctrineBehaviors\Model\Translatable\Translation;

class BlockTranslation
{
    use Translation;

    /**
     * @var array
     */
    protected $translatableFields;

    /**
     * @var int $id
     */
    protected $id;

    public function __construct()
    {
        $this->setTranslatableFields([]);
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
     * @return array
     */
    public function getTranslatableFields()
    {
        return $this->translatableFields;
    }

    /**
     * @param array $translatableFields
     */
    public function setTranslatableFields($translatableFields)
    {
        $this->translatableFields = $translatableFields;
    }
}