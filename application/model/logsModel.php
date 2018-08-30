<?php

use Carbon\Carbon;

class LogsModel
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

        $this->authModel = new AuthModel($this->db);
    }

    public function getAll()
    {
        $sql = "SELECT * FROM logs";
        $query = $this->db->prepare($sql);
        $query->execute();

        $logs = $query->fetchAll();

        foreach ($logs as $log) {
            foreach ($log as $key => &$value) {
                if ($key === "user_id")
                {
                    $user = $this->authModel->getById($value);
                    $value = $user->name;
                }
            }
        }

        // fetchAll() is the PDO method that gets all result rows, here in object-style because we defined this in
        // core/controller.php! If you prefer to get an associative array as the result, then do
        // $query->fetchAll(PDO::FETCH_ASSOC); or change core/controller.php's PDO options to
        // $options = array(PDO::ATTR_DEFAULT_FETCH_MODE => PDO::FETCH_ASSOC ...
        return $logs;
    }

    public function create($user, $categorie, $action)
    {
        $sql = "INSERT INTO logs (user_id, category_id, action) VALUES (:user, :categorie, :action)";
        $query = $this->db->prepare($sql);
        $parameters = array(':user' => $user, ':categorie' => $categorie, ':action' => $action);

        // useful for debugging: you can see the SQL behind above construction by using:
        // echo '[ PDO DEBUG ]: ' . Helper::debugPDO($sql, $parameters);  exit();

        $query->execute($parameters);
    }

}
