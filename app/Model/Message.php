<?php

namespace App\Model;

use Core\AbsModel;
use Core\DB;
use Core\Mail;

class Message extends AbsModel
{
    private $id;
    private $userId;
    private $userName;
    private $text;
    private $img;
    private $createdAt;

    public function __construct($data = [])
    {
        if ($data) {
            if (empty($data['text'])) {
                throw new \InvalidArgumentException();
            }

            if (isset($data['id'])) {
                $this->setId($data['id']);
            }

            if (isset($data['name'])) {
                $this->setUserName($data['name']);
            }

            if (isset($data['created_at'])) {
                $this->setCreatedAt($data['created_at']);
            }

            if (isset($data['img']))
            {
                $this->setImg($data['img']);
            }

            $this->setUserId($data['user_id']);
            $this->setText($data['text']);

        }
    }

    /**
     * @param mixed $id
     */
    public function setId($id): self
    {
        $this->id = $id;
        return $this;
    }

    /**
     * @param string $userName
     */
    public function setUserName(string $userName): self
    {
        $this->userName = $userName;
        return $this;
    }

    /**
     * @param mixed $createdAt
     */
    public function setCreatedAt(string $createdAt): self
    {
        $this->createdAt = $createdAt;
        return $this;
    }


    /**
     * @param mixed $userId
     */
    public function setUserId(int $userId): self
    {
        $this->userId = $userId;
        return $this;
    }

    /**
     * @param mixed $text
     */
    public function setText(string $text): self
    {
        $this->text = $text;
        return $this;
    }

    /**
     * @param mixed $img
     */
    public function setImg(?string $img): self
    {
        $this->img = $img;
        return $this;
    }

    /**
     * @return mixed
     */
    public function getId(): int
    {
        return $this->id;
    }

    /**
     * @return mixed
     */
    public function getUserId(): int
    {
        return $this->userId;
    }

    /**
     * @return mixed
     */
    public function getText(): string
    {
        return $this->text;
    }

    /**
     * @return mixed
     */
    public function getUserName(): string
    {
        return $this->userName;
    }

    /**
     * @return mixed
     */
    public function getCreatedAt(): string
    {
        return $this->createdAt;
    }

    /**
     * @return mixed
     */
    public function getImg(): ?string
    {
        return $this->img ? 'uploads/message/' . $this->img : null;
    }

    public function save()
    {
        $insert = "INSERT INTO `messages`(`user_id`, `text`, `img`) VALUES (
            :user_id, :text, :img
        )";

        $this->getDb()->exec($insert, __METHOD__, [
            ':user_id' => $this->userId,
            ':text' => $this->text,
            ':img' => $this->img
        ]);

        $lastInsertId = $this->getDb()->lastInsertId();
        $this->id = $lastInsertId;

        return $lastInsertId;
    }

    public function deleteMessage(int $id)
    {
        $delete = "DELETE FROM `messages` WHERE `id` = $id";
        $this->getDb()->exec($delete, __METHOD__);
    }

    public function getListOfMessages($limit = 20): ?array
    {
        $select = "SELECT m.*, u.name FROM `messages` m
                   INNER JOIN `users` u
                   ON m.user_id = u.id
                   LIMIT $limit";
        $messages = $this->getDb()->fetchAll($select, __METHOD__);

        if ($messages) {
            $data = [];
            foreach ($messages as $message) {
                $data[] = new self($message);
            }

            return $data;
        }
    }

    public function getJsonOfMessages(int $id, $limit = 20)
    {
        $select = "SELECT * FROM `messages` 
                   WHERE `user_id` = $id
                   LIMIT $limit";
        $messages = $this->getDb()->fetchAll($select, __METHOD__);

        if (!$messages) {
            return null;
        }

        return json_encode($messages);
    }

    public function send()
    {
        (new Mail)->createAndSend($this->text);
    }
}