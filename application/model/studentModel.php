<?php

class StudentModel
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

    public function getAll()
    {
        $sql = "SELECT * FROM students";
        $query = $this->db->prepare($sql);
        $query->execute();

        // fetchAll() is the PDO method that gets all result rows, here in object-style because we defined this in
        // core/controller.php! If you prefer to get an associative array as the result, then do
        // $query->fetchAll(PDO::FETCH_ASSOC); or change core/controller.php's PDO options to
        // $options = array(PDO::ATTR_DEFAULT_FETCH_MODE => PDO::FETCH_ASSOC ...
        return $query->fetchAll();
    }

    public function getById($id)
    {
        $sql = "SELECT * FROM students WHERE id = :id";
        $query = $this->db->prepare($sql);
        $query->execute([":id" => $id]);

        // fetchAll() is the PDO method that gets all result rows, here in object-style because we defined this in
        // core/controller.php! If you prefer to get an associative array as the result, then do
        // $query->fetchAll(PDO::FETCH_ASSOC); or change core/controller.php's PDO options to
        // $options = array(PDO::ATTR_DEFAULT_FETCH_MODE => PDO::FETCH_ASSOC ...
        return $query->fetchAll()[0];
    }

    public function getByName($firstname, $lastname)
    {
        $sql = "SELECT * FROM students WHERE first_name = :firstname AND last_name = :lastname";
        $query = $this->db->prepare($sql);
        $query->execute([":firstname" => $firstname, ":lastname" => $lastname]);

        // fetchAll() is the PDO method that gets all result rows, here in object-style because we defined this in
        // core/controller.php! If you prefer to get an associative array as the result, then do
        // $query->fetchAll(PDO::FETCH_ASSOC); or change core/controller.php's PDO options to
        // $options = array(PDO::ATTR_DEFAULT_FETCH_MODE => PDO::FETCH_ASSOC ...
        return $query->fetchAll()[0];
    }

    public function getByMail($mail)
    {
        $name = explode(" ", preg_replace("#^([_A-Za-z-1-]{1,})\.([_A-Za-z-1-]{1,})@(.)+$#", "$1 $2", $mail));
        $sql = "SELECT * FROM students WHERE first_name = :firstname AND last_name = :lastname";
        $query = $this->db->prepare($sql);
        $query->execute([":firstname" => $name[0], ":lastname" => $name[1]]);

        if ($query->rowCount() > 0)
            return $query->fetchAll()[0];
        else
            return null;
    }

    public function create($firstname, $lastname)
    {
        $sql = "INSERT INTO students (first_name, last_name, promo_id, email) VALUES (:firstname, :lastname, :promo, :email)";
        $query = $this->db->prepare($sql);
        $parameters = array(':firstname' => $firstname, ':lastname' => $lastname, ':promo' => 1, ":email" => $firstname . '.' . $lastname . '@epitech.eu');

        $query->execute($parameters);
    }

    public function createByMail($mail)
    {
        $firstname = explode(".", $mail)[0];
        $lastname = explode("@", explode(".", $mail)[1])[0];

        $sql = "INSERT INTO students (first_name, last_name, promo_id, email) VALUES (:firstname, :lastname, :promo, :email)";
        $query = $this->db->prepare($sql);
        $parameters = array(':firstname' => $firstname, ':lastname' => $lastname, ':promo' => 1, ":email" => $firstname . '.' . $lastname . '@epitech.eu');

        $query->execute($parameters);
    }



}
