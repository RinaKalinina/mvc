<?php
namespace Core\Interfaces;

interface StorageInterface
{
    /**
     * The default collection name.
     */
    const SESSION_INDEX_USER = 'user';

    /**
     * Returns the contents of storage.
     *
     * @return mixed
     */
    public function get();

    /**
     * Writes data to the storage.
     *
     * @param $data
     */
    public function set($data);
}