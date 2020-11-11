<?php

namespace App\Controller;

use Core\AbsController;
use App\Model\Message as MessageModel;
use Intervention\Image\ImageManagerStatic as ImageManager;

class Message extends AbsController
{
    public function indexAction()
    {
        if (!$this->user) {
            $this->redirect('/user/register');
        }

        $messages = (new MessageModel)->getListOfMessages();

        return $this->view->render('Message/index', [
            'messages' => $messages,
            'isAdmin' => $this->user->isAdmin()
        ]);

    }

    public function sendAction()
    {
        $userMessage = htmlspecialchars(trim($_POST['text'] ?? ''));
        $userFile = $_FILES['userFile'];
        $typeJpg = 'image/jpeg';
        $typePng = 'image/png';
        $newFileName = null;
        $errors = [];
        $success = true;

        if (!$userMessage) {
            $success = false;
            $errors[] = 4;
        }

        if ($userFile['name']) {
            $errorsFile = [
                'UPLOAD_ERR_INI_SIZE' => 5,
                'UPLOAD_ERR_FORM_SIZE' => 6,
                'UPLOAD_ERR_PARTIAL' => 7,
                'UPLOAD_ERR_NO_FILE' => 8,
                'UPLOAD_ERR_NO_TMP_DIR' => 9,
                'UPLOAD_ERR_CANT_WRITE' => 10,
                'UPLOAD_ERR_EXTENSION' => 11
            ];

            if (isset($errorsFile[$userFile['error']])) {
                $success = false;
                $errors[] = $errorsFile[$userFile['error']];
            }

            if ($userFile['type'] != $typeJpg && $userFile['type'] != $typePng) {
                $errors[] = 12;
                $success = false;
            } else {
                $filePath = ROOT_DIR . DIRECTORY_SEPARATOR . 'uploads/message';
                $generateFileName = sha1(date('Y-m-d-H-i-s') . $userFile['name']);
                $originalFileName = $userFile['name'];

                if (!is_dir($filePath)) {
                    mkdir($filePath, 0755, true);
                }

                $extension = pathinfo($originalFileName, PATHINFO_EXTENSION);
                $newFileName = $generateFileName . '.' . $extension;

                if (move_uploaded_file($userFile['tmp_name'], $filePath . '/'
                    . $newFileName)) {
                    $img = ImageManager::make($filePath . '/' . $newFileName)
                        ->resize(200, null, function ($constraint) {
                            $constraint->aspectRatio();
                        });

                    $img->text('#MVC',
                        $img->width() / 2 - 15,
                        $img->height() - 5,
                        function ($font) {
                            $font->file(ROOT_DIR . DIRECTORY_SEPARATOR . 'assets/font/19844.otf');
                            $font->size(40);
                            $font->color(array(0, 0, 0, 0.7));
                            $font->align('bottom-right');
                        })
                        ->save();

                    $success = true;
                }
            }
        }

        if ($success) {
            $data = [
                'user_id' => $_SESSION['id'],
                'text' => $userMessage,
                'img' => $newFileName
            ];

            (new MessageModel)->addNewMessage($data);

            $this->redirect('/message');
        }

        if (isset($_POST['submit'])) {
            return $this->view->render('Blog/index', [
                'user' => $this->user,
                'errorDescription' => parent::getErrorDescription(),
                'errors' => $errors
            ]);
        }

        return $this->view->render('Blog/index', [
            'user' => $this->user
        ]);
    }

    public function deleteAction()
    {
        if (isset($_POST['id'])) {
            (new MessageModel)->deleteMessage($_POST['id']);
        }

        $this->redirect('/message');
    }
}