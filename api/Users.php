<?php

namespace Api;

class Users
{
    /**
     * Список пользователей который нельзя изменить
     *
     * @var array|array[]
     */
    private static array $users = [
        ["id" => 1, "name" => "Артём Мартынов"],
        ["id" => 2, "name" => "Алексей Прохоров"],
        ["id" => 3, "name" => "Александра Савина"],
        ["id" => 4, "name" => "Маргарита Соколова"],
        ["id" => 5, "name" => "Ариана Михайлова"],
        ["id" => 6, "name" => "Алиса Баранова"]
    ];

    /**
     * Метод удаление пользователя, request DELETE
     *
     * @return void
     */
    private static function deleteUsers(): void
    {
        if (!isset($requestUri[1])) {
            http_response_code(404);
            echo json_encode(["message" => "Пользователь не найден"]);
            return;
        }

        $userId = intval($requestUri[1]);
        foreach (self::$users as $key => $user) {
            if ($user['id'] === $userId) {
                unset(self::$users[$key]);
                echo json_encode(["message" => "Пользователь удален"]);
            }
        }
    }

    /**
     * Метод обновления списка пользователя(сам список не изменить, реализованно лишь ответ если бы был добавлен в
     * список), request PUT
     *
     * @return void
     */
    private static function putUsers(): void
    {
        if (!isset($requestUri[1])) {
            http_response_code(404);
            echo json_encode(["message" => "Пользователь не найден"]);
            return;
        }

        $userId = intval($requestUri[1]);
        $input = json_decode(file_get_contents('php://input'), true);
        foreach (self::$users as &$user) {
            if ($user['id'] === $userId) {
                $user['name'] = $input['name'];
                echo json_encode($user);
                return;
            }
        }
    }

    /**
     * Метод создания пользователя (реализованна симуляция, список не должен измениться), request POST
     *
     * @return void
     */
    private static function postUsers(): void
    {
        if (!isset($_POST['name'])) {
            http_response_code(404);
            echo json_encode(["message" => "Имя пользователь не содержит пустое поле"]);
            return;
        }

        $input = $_POST;
        $newUser = [
            "id" => count(self::$users) + 1,
            "name" => $input['name']
        ];
        self::$users[] = $newUser;
        echo json_encode($newUser);
    }

    /**
     * Метод получения списка пользователя $users, request GET
     *
     * @return void
     */
    private static function getUsers(): void
    {
        if (isset($requestUri[1])) {
            $userId = intval($requestUri[1]);
            $user = array_filter(self::$users, fn($u) => $u['id'] === $userId);

            if (!$user) {
                http_response_code(404);
                echo json_encode(["message" => "Пользователь не найден"]);
                return;
            }

            echo json_encode(array_values($user)[0]);
            return;
        }

        // Получение всех пользователей
        echo json_encode(self::$users);
    }

    /**
     * Метод вывода/изменения информации об пользователе исходя из вида запроса
     *
     * @param $requestMethod
     * @param $requestUri
     *
     * @return void
     */
    public static function requestUsers($requestMethod, $requestUri): void
    {
        switch ($requestMethod) {
            case 'GET':
                self::getUsers();
                break;

            case 'POST':
                self::postUsers();
                break;

            case 'PUT':
                self::putUsers();
                break;

            case 'DELETE':
                self::deleteUsers();
                break;

            default:
                http_response_code(405);
                echo json_encode(["message" => "Метод не найден"]);
                break;
        }
    }
}