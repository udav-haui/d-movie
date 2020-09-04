<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

/**
 * Class StaticPage
 *
 * @package App
 */
class StaticPage extends AbstractModel
{
    protected $guarded = [];

    /** Constant */
    const ID = 'id';
    const STATUS = 'status';
    const NAME = 'name';
    const SLUG = 'slug';
    const LANGUAGE = 'language';
    const CONTENT = 'content';

    /** Constant permission */
    const VIEW = 'staticpage-view';
    const CREATE = 'staticpage-create';
    const EDIT = 'staticpage-edit';
    const DELETE = 'staticpage-delete';

    /** Constant status of page */
    const ENABLE = 1;
    const VIETNAM ='vi';
    const ENGLISH = 'en';

    /**
     * Get language
     *
     * @return string
     */
    public function getLanguageLabel()
    {
        return $this->getLanguage() === self::VIETNAM ?
            __('Vietnamese') :
            __('English');
    }

    /**
     * @return int
     */
    public function getId()
    {
        return $this->getAttribute(self::ID);
    }

    /**
     * @param int $id
     */
    public function setId(int $id)
    {
        $this->setAttribute(self::ID, $id);
    }

    /**
     * @return int
     */
    public function getStatus()
    {
        return $this->getAttribute(self::STATUS);
    }

    /**
     * @param int $status
     */
    public function setStatus(int $status)
    {
        $this->setAttribute(self::STATUS, $status);
    }

    /**
     * @return string
     */
    public function getStatusLabel()
    {
        return (int)$this->getStatus() === self::ENABLE ? __('Enable') : __('Disable');
    }

    /**
     * Get page name
     *
     * @return string
     */
    public function getName()
    {
        return $this->getAttribute(self::NAME);
    }

    /**
     * Set page name
     *
     * @param string $name
     * @return void
     */
    public function setName(string $name)
    {
        $this->setAttribute(self::NAME, $name);
    }

    /**
     * Get page slug
     *
     * @return string
     */
    public function getSlug()
    {
        return $this->getAttribute(self::SLUG);
    }

    /**
     * Set page Slug
     *
     * @param string $slug
     * @return void
     */
    public function setSlug(string $slug)
    {
        return $this->setAttribute(self::SLUG, $slug);
    }

    /**
     * Get page language
     *
     * @return string
     */
    public function getLanguage()
    {
        return $this->getAttribute(self::LANGUAGE);
    }

    /**
     * Set page Language
     *
     * @param string $lang
     * @return void
     */
    public function setLanguage(string $lang)
    {
        return $this->setAttribute(self::LANGUAGE, $lang);
    }

    /**
     * Get content
     *
     * @return string
     */
    public function getContent()
    {
        return $this->getAttribute(self::CONTENT);
    }

    /**
     * Set page content
     *
     * @param string $content
     * @return void
     */
    public function setContent(string $content)
    {
        return $this->getAttribute(self::CONTENT, $content);
    }
}
