<?php

namespace OpenOrchestra\DisplayBundle\Fake;

use Doctrine\Common\Collections\ArrayCollection;
use OpenOrchestra\ModelInterface\Model\ReadContentAttributeInterface;
use OpenOrchestra\ModelInterface\Model\ReadContentInterface;
use OpenOrchestra\ModelInterface\Model\KeywordInterface;

/**
 * Class FakeContent
 */
class FakeContent implements ReadContentInterface
{
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
     * @return ReadContentAttributeInterface|null
     */
    public function getAttributeByName($name)
    {
        return new FakeContentAttribute($name);
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
     * @return string
     */
    public function getSiteId()
    {
        return 'fakeSiteId';
    }

    /**
     * @return ArrayCollection
     */
    public function getKeywords()
    {
        return new ArrayCollection();
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

    public function initializeKeywords()
    {
    }

    /**
     * @return string
     */
    public function getContentType()
    {
        return 'fakeContentType';
    }
}
