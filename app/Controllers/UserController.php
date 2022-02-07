<?php

namespace App\Controllers;

use App\Models\User;

class UserController
{
    // получение пользователей
    public function index()
    {
        http_response_code(200);
        print_r(json_encode(['users' => User::readAll()]));
    }

    // получение пользователя
    public function getOne($id)
    {
        $user = User::readOne($id);
        if (!$user) {
            http_response_code(404);
            print_r(json_encode(['error' => 'user not found']));
            exit();
        }
        http_response_code(200);
        print_r(json_encode(['user' => $user]));
    }

    //создание пользователя
    public function createUser()
    {
        $input = json_decode(file_get_contents("php://input"), true);
        $user = new User($input['display_name'], $input['email'], time(), User::newIndex());
        if (!$user->validate()) {
            http_response_code(400);
            print_r(json_encode(['error' => 'invalid data']));
            exit();
        }
        $user->write();
        http_response_code(200);
        print_r(json_encode(['id' => $user->id]));
    }

    //изменение пользователя
    public function updateUser ($id) {
        $user = User::readOne($id);
        if (!$user) {
            http_response_code(404);
            print_r(json_encode(['error' => 'user not found']));
            exit();
        }
        $input = json_decode(file_get_contents("php://input"), true);
        $user->displayName = $input['display_name'];
        $user->email = $input['email'];
        if (!$user->validate()) {
            http_response_code(400);
            print_r(json_encode(['error' => 'invalid data']));
            exit();
        }
        $user->write();
        http_response_code(200);
        print_r(json_encode(['user' => $user]));
    }

    //удаление пользователя
    public function deleteUser ($id) {
        $user = User::readOne($id);
        if (!$user) {
            http_response_code(404);
            print_r(json_encode(['error' => 'user not found']));
            exit();
        }
        $user->remove();
        http_response_code(200);
    }


}