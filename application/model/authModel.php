<?php

class AuthModel
{
    /**
     * @param object $db A PDO database connection
     */
    function __construct($db)
    {
        try {
            $this->db = $db;
        } catch (PDOException $e) {
            exit('Database connection could not be established.');
        }
    }

    public function getAuth()
    {
        if (isset($_SESSION['auth'])) {
            $sql = "SELECT * FROM users WHERE email = :email AND remember_token = :token";
            $query = $this->db->prepare($sql);
            $query->execute([":email" => $_SESSION['auth']['email'], ":token" => $_SESSION['auth']['token']]);

        if ($query->rowCount() > 0) {
            $_SESSION['auth']['check'] = true;
            return $_SESSION['auth'];
        }
        else
            return null;
        }
        else
            return null;
    }

    public function setAuth($user, $token)
    {
        $sql = "UPDATE users SET remember_token = :token WHERE id = :id";
        $query = $this->db->prepare($sql);
        $parameters = array(':token' => $token, ':id' => $user->id);
        $query->execute($parameters);

        $_SESSION['auth'] = ['name' => $user->name, 'isAdmin' => $user->admin, 'token' => $token, 'email' => $user->email, "id" => $user->id];
    }

    public function createAuthForStudent($student)
    {
        $sql = "INSERT INTO users (name, email) VALUES (:name, :email)";
        $query = $this->db->prepare($sql);
        $parameters = array(':name' => $student->first_name . ' ' . $student->last_name, ':email' => $student->first_name . '.' . $student->last_name . '@epitech.eu');
        $query->execute($parameters);
        $newId = $this->db->lastInsertId();
        $token = $this->randomToken();
        $user = $this->getById($newId);
        $this->setAuth($user, $token);
    }

    public function getById($id)
    {
        $sql = "SELECT * FROM users WHERE id = :id";
        $query = $this->db->prepare($sql);
        $query->execute([":id" => $id]);
        return $query->fetchAll()[0];
    }

    public function getErrors()
    {
        if (isset($_SESSION['errors']))
            return $_SESSION['errors'];
        else
            return null;
    }

    public function setErrors()
    {
        $_SESSION['errors'] = ["loooool"];
    }

    public function login($email, $password)
    {
        $sql = "SELECT * FROM users WHERE email = :email AND password = :pwd";
        $query = $this->db->prepare($sql);
        $query->execute([":email" => $email, ":pwd" => sha1($password)]);

        if ($query->rowCount() > 0) {
            $token = $this->randomToken();
            $user = $query->fetchAll()[0];
            $this->setAuth($user, $token);
            return $user;
        }
        else
            return null;
    }

    public function getByMail($email)
    {
        $sql = "SELECT * FROM users WHERE email = :email";
        $query = $this->db->prepare($sql);
        $query->execute([":email" => $email]);

        if ($query->rowCount() > 0) {
            $user = $query->fetchAll()[0];
            return $user;
        }
        else
            return null;
    }

    public function randomToken()
    {
        $string = "";
        $chaine = "a0b1c2d3e4f5g6h7i8j9klmnpqrstuvwxy123456789";
        srand((double)microtime()*1000000);
        for($i=0; $i<50; $i++){
            $string .= $chaine[rand()%strlen($chaine)];
        }
        return $string;
    }
}
