<?php

namespace App\Models;

use JsonSerializable;

class User implements JsonSerializable
{
    const FILENAME =  __DIR__ . '../../users.json';
    public $displayName;
    public $email;
    public $createdAt;
    public $id;

    function __construct($displayName, $email, $createdAt, $id = null)
    {
        $this->displayName = $displayName;
        $this->email = $email;
        $this->createdAt = $createdAt;
        $this->id = $id;
    }

    function validate()
    {
        if (empty($this->displayName) || empty($this->email) || empty($this->createdAt) || empty($this->id) ||
            !is_string($this->displayName) || !is_string($this->email) || !is_int($this->createdAt)
        ) {
            return false;
        } else {
            return true;
        }
    }

    static function readOne($id)
    {
        try {
            $f = @fopen(self::FILENAME, 'r+');
            $data = @fread($f, filesize(self::FILENAME));
            $data = json_decode($data, true);
            @fclose($f);
            if (false === isset($data['list'][$id])) {
                return false;
            } else {
                return new User(...array_values(array_merge($data['list'][$id], ['id' => $id])));
            }
        } catch (\Exception $exception) {
            http_response_code(500);
            print_r(['error' => $exception->getMessage()]);
            exit();
        }
    }

    static function readAll()
    {
        try {
            $f = @fopen(self::FILENAME, 'r+');
            $data = @fread($f, filesize(self::FILENAME));
            $data = json_decode($data, true);
            @fclose($f);
            $all = [];
            foreach ($data['list'] as $k => $v) {
                $all[$k] = new User(...(array_values(array_merge($v, ['id' => $k]))));
            }
            return $all;
        } catch (\Exception $exception) {
            http_response_code(500);
            print_r(['error' => $exception->getMessage()]);
            exit();
        }
    }

    function write()
    {
        try {
            $f = @fopen(self::FILENAME, 'a+');
            $data = @fread($f, filesize(self::FILENAME));
            $data = json_decode($data, true);
            $data['list'][$this->id] = $this;
            @ftruncate($f, 0);
            @fwrite($f, json_encode($data));
            @fclose($f);
        } catch (\Exception $exception) {
            http_response_code(500);
            print_r(['error' => $exception->getMessage()]);
            exit();
        }
    }

    function remove()
    {
        try {
            $f = @fopen(self::FILENAME, 'a+');
            $data = @fread($f, filesize(self::FILENAME));
            $data = json_decode($data, true);
            unset($data['list'][$this->id]);
            @ftruncate($f, 0);
            @fwrite($f, json_encode($data));
            @fclose($f);
        } catch (\Exception $exception) {
            http_response_code(500);
            print_r(['error' => $exception->getMessage()]);
            exit();
        }
    }

    static function newIndex()
    {
        $f = @fopen(self::FILENAME, 'a+');
        $data = @fread($f, filesize(self::FILENAME));
        $data = json_decode($data, true);
        @fclose($f);
        if (isset($data['increment'])) {
            return $data['increment'] + 1;
        } else {
            return 1;
        }
    }

    function jsonSerialize()
    {
        return [
            'display_name' => $this->displayName,
            'email' => $this->email,
            'created_at' => $this->createdAt
        ];
    }
}