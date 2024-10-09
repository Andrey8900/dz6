<?php

namespace Geekbrains\Application1\Application;

use PDO;
use Exception;

class UserController {

    private PDO $db;

    public function __construct(PDO $db) {
        $this->db = $db;
    }

    public function updateUser(int $id, array $newData): bool {
        try {
            $query = $this->db->prepare("SELECT * FROM users WHERE id = :id");
            $query->execute(['id' => $id]);
            $user = $query->fetch();

            if (!$user) {
                throw new Exception("User with ID $id not found.");
            }

            $fieldsToUpdate = [];
            $params = ['id' => $id];
            
            foreach ($newData as $key => $value) {
                $fieldsToUpdate[] = "$key = :$key";
                $params[$key] = $value;
            }
            
            if (empty($fieldsToUpdate)) {
                throw new Exception("No data to update.");
            }

            $sql = "UPDATE users SET " . implode(', ', $fieldsToUpdate) . " WHERE id = :id";
            $stmt = $this->db->prepare($sql);
            return $stmt->execute($params);

        } catch (Exception $e) {
            echo "Error: " . $e->getMessage();
            return false;
        }
    }

    public function deleteUser(int $id): bool {
        try {
            // Check if user exists
            $query = $this->db->prepare("SELECT * FROM users WHERE id = :id");
            $query->execute(['id' => $id]);
            $user = $query->fetch();

            if (!$user) {
                throw new Exception("User with ID $id not found.");
            }

            // Delete user
            $stmt = $this->db->prepare("DELETE FROM users WHERE id = :id");
            return $stmt->execute(['id' => $id]);

        } catch (Exception $e) {
            echo "Error: " . $e->getMessage();
            return false;
        }
    }
}
