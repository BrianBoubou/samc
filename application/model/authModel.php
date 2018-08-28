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
        if (!isset($user->password) || $user->password == "")
            $_SESSION['auth']['HavePassword'] = 0;
        else
        {
            $_SESSION['auth']['HavePassword'] = 1;
            $_SESSION['auth']['passwordHash'] = $user->password;
        }
    }

    public function updatePassword($id, $password)
    {
        $sql = "UPDATE users SET password = :pwd WHERE id = :id";
        $query = $this->db->prepare($sql);
        $parameters = array(':pwd' => sha1($password), ':id' => $user->id);
        $query->execute($parameters);
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

    public function createAdmin($email)
    {
        $sql = "INSERT INTO users (name, email, admin) VALUES (:name, :email, 1)";
        $query = $this->db->prepare($sql);
        $parameters = array(':name' => ucfirst(explode('.', $email)[0]) . ' ' . ucfirst(explode('@', explode('.', $email)[1])[0]), ':email' => $email);
        $query->execute($parameters);
    }

    public function getById($id)
    {
        $sql = "SELECT * FROM users WHERE id = :id";
        $query = $this->db->prepare($sql);
        $query->execute([":id" => $id]);
        return $query->fetchAll()[0];
    }

    public function getAdmins()
    {
        $sql = "SELECT * FROM users WHERE admin = :admin";
        $query = $this->db->prepare($sql);
        $query->execute([":admin" => 1]);
        return $query->fetchAll();
    }

    public function truncateBdd()
    {
        $sql = "SET FOREIGN_KEY_CHECKS=0; DELETE FROM `logs` WHERE id != 0; DELETE FROM `days` WHERE id != 0; DELETE FROM `edit_pangs` WHERE id != 0; DELETE FROM `students` WHERE id != 0; DELETE FROM `users` WHERE admin != 1; SET FOREIGN_KEY_CHECKS=1;";
        $query = $this->db->prepare($sql);
        $query->execute();
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
