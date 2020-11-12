<?php

namespace App\Model;

use Core\AbsModel;

class Message extends AbsModel
{
    protected $fillable = ['user_id', 'text', 'img'];

    public function user()
    {
        return $this->belongsTo('App\Model\User');
    }

    /**
     * @return mixed
     */
    public function getImg(): ?string
    {
        return $this->img ? '/uploads/message/' . $this->img : null;
    }

    /**
     * @param array $data
     * @return int|null
     */
    public function addNewMessage(array $data = [])
    {
        if (empty($data)) {
            return null;
        }

        return Message::create($data);
    }

    public function deleteMessage(int $id)
    {
        Message::where('id', $id)->delete();
    }

    public function getListOfMessages($limit = 20)
    {
        return Message::with('user')->limit($limit)->get();
    }

    public function getJsonOfMessages(int $id, $limit = 20)
    {
        $messages = Message::where('user_id', $id)
            ->limit($limit)
            ->get();

        if (!$messages) {
            return null;
        }

        return json_encode($messages);
    }
}