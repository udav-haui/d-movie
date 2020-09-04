<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class Contact extends AbstractModel
{
    protected $guarded = [];

    /** Constant field */
    const ID = 'id';
    const STATUS = 'status';
    const CINEMA = 'cinema_id';
    const CONTACT_NAME = 'contact_name';
    const CONTACT_EMAIL = 'contact_email';
    const CONTACT_PHONE = 'contact_phone';
    const CONTACT_CONTENT = 'contact_content';

    /** Constant status value */
    const PENDING = 0;
    const CONTACTED = 1;

    /** Constant permission */
    const VIEW = 'contact-view';
    const CREATE = 'contact-create';
    const EDIT = 'contact-edit';
    const DELETE = 'contact-delete';

    public $timestamps = false;

    /**
     * Get Id
     *
     * @return int
     */
    public function getId()
    {
        return $this->getAttribute(self::ID);
    }

    /**
     * Set Id
     *
     * @param int $id
     * @return void
     */
    public function setId(int $id)
    {
        return $this->setAttribute(self::ID, $id);
    }

    /**
     * Get status
     * @return int
     */
    public function getStatus()
    {
        return $this->getAttribute(self::STATUS);
    }

    /**
     * Set status
     *
     * @param int $status
     */
    public function setStatus(int $status)
    {
        $this->setAttribute(self::STATUS, $status);
    }

    /**
     * @inheritDoc
     */
    public function getStatusLabel()
    {
        return (int)$this->getStatus() === self::PENDING ? __('Pending') : __('Contacted');
    }

    /**
     * Get related cinema
     *
     * @return Cinema
     */
    public function getCinema()
    {
        return $this->cinema;
    }

    /**
     * Get contact name
     *
     * @return string
     */
    public function getContactName()
    {
        return $this->getAttribute(self::CONTACT_NAME);
    }

    /**
     * Get contact email
     *
     * @return string
     */
    public function getContactEmail()
    {
        return $this->getAttribute(self::CONTACT_EMAIL);
    }

    public function getHtmlContactEmail()
    {
        return '<a target="_blank" href="https://mail.google.com/mail/?view=cm&fs=1&to='.
            $this->getContactEmail() . '" title="' . __('Send contact mail') . '">'.
            $this->getContactEmail() . '</a>';
    }

    /**
     * @return string
     */
    public function getContactPhone()
    {
        return $this->getAttribute(self::CONTACT_PHONE);
    }

    /**
     * @return string
     */
    public function getContactContent()
    {
        return $this->getAttribute(self::CONTACT_CONTENT);
    }

    /**
     * Belong to cinema
     *
     * @return \Illuminate\Database\Eloquent\Relations\BelongsTo
     */
    public function cinema()
    {
        return $this->belongsTo(Cinema::class);
    }
}
