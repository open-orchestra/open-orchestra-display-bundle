<?php

namespace OpenOrchestra\DisplayBundle\Fake;

use DateTime;
use Doctrine\Common\Collections\ArrayCollection;
use OpenOrchestra\ModelInterface\Model\ContentAttributeInterface;
use OpenOrchestra\ModelInterface\Model\ContentInterface;
use OpenOrchestra\ModelInterface\Model\KeywordInterface;
use OpenOrchestra\ModelInterface\Model\StatusInterface;

/**
 * Class FakeContent
 */
class FakeContent implements ContentInterface
{
    /**
     * Returns createdBy.
     *
     * @return string
     */
    public function getCreatedBy()
    {
        return 'fakeCreatedBy';
    }

    /**
     * Returns updatedBy.
     *
     * @return string
     */
    public function getUpdatedBy()
    {
        return 'fakeUpdatedBy';
    }

    /**
     * @return ArrayCollection
     */
    public function getAttributes()
    {
        $fakeContent = new FakeContentAttribute('fakeContentAttribute');

        return new ArrayCollection(array($fakeContent));
    }

    /**
     * @param string $name
     *
     * @return ContentAttributeInterface|null
     */
    public function getAttributeByName($name)
    {
        return new FakeContentAttribute($name);
    }

    /**
     * @return string
     */
    public function getContentId()
    {
        return 'fakeContentId';
    }

    /**
     * @return string
     */
    public function getContentType()
    {
        return 'fakeContentType';
    }

    /**
     * @return int
     */
    public function getContentTypeVersion()
    {
        return 'fakeContentTypeVersion';
    }

    /**
     * @return boolean
     */
    public function getDeleted()
    {
        return false;
    }

    /**
     * @return string
     */
    public function getId()
    {
        return 'fakeId';
    }

    /**
     * @return string
     */
    public function getLanguage()
    {
        return 'fakeLanguage';
    }

    /**
     * @return string
     */
    public function getName()
    {
        return 'fakeName';
    }

    /**
     * @return int
     */
    public function getVersion()
    {
        return 1;
    }

    /**
     * @return ArrayCollection
     */
    public function getKeywords()
    {
        return new ArrayCollection();
    }

    /**
     * Get status
     *
     * @return StatusInterface
     */
    public function getStatus()
    {
        return null;
    }

    /**
     * Returns createdAt.
     *
     * @return DateTime
     */
    public function getCreatedAt()
    {
        return new DateTime();
    }

    /**
     * Returns updatedAt.
     *
     * @return Datetime
     */
    public function getUpdatedAt()
    {
        return new DateTime();
    }

    /**
     * Sets createdBy.
     *
     * @param string $createdBy
     */
    public function setCreatedBy($createdBy)
    {
    }

    /**
     * Sets updatedBy.
     *
     * @param string $updatedBy
     */
    public function setUpdatedBy($updatedBy)
    {
    }

    /**
     * @param string $contentId
     */
    public function setContentId($contentId)
    {
    }

    /**
     * @param string $contentType
     */
    public function setContentType($contentType)
    {
    }

    /**
     * @param int $contentTypeVersion
     */
    public function setContentTypeVersion($contentTypeVersion)
    {
    }

    /**
     * @param boolean $deleted
     */
    public function setDeleted($deleted)
    {
    }

    /**
     * @param string $language
     */
    public function setLanguage($language)
    {
    }

    /**
     * @param string $name
     */
    public function setName($name)
    {
    }

    /**
     * @param int $version
     */
    public function setVersion($version)
    {
    }

    /**
     * Set status
     *
     * @param StatusInterface|null $status
     */
    public function setStatus(StatusInterface $status = null)
    {
    }

    /**
     * Sets createdAt.
     *
     * @param Datetime $createdAt
     */
    public function setCreatedAt(\DateTime $createdAt)
    {
    }

    /**
     * Sets updatedAt.
     *
     * @param DateTime $updatedAt
     */
    public function setUpdatedAt(\DateTime $updatedAt)
    {
    }

    /**
     * @param ContentAttributeInterface $attribute
     */
    public function addAttribute(ContentAttributeInterface $attribute)
    {
    }

    /**
     * @param ContentAttributeInterface $attribute
     */
    public function removeAttribute(ContentAttributeInterface $attribute)
    {
    }

    /**
     * @param KeywordInterface $keyword
     */
    public function addKeyword(KeywordInterface $keyword)
    {
    }

    /**
     * @param KeywordInterface $keyword
     */
    public function removeKeyword(KeywordInterface $keyword)
    {
    }
}
