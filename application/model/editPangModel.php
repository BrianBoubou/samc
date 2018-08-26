<?php

use Carbon\Carbon;

class EditPangModel
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

    public function getById($id)
    {
        $sql = "SELECT * FROM edit_pangs WHERE id = :id";
        $query = $this->db->prepare($sql);
        $query->execute([":id" => $id]);

        return $query->fetchAll()[0];
    }

    public function getEditPangByDay($id, $day)
    {
        $sql = "SELECT * FROM edit_pangs WHERE student_id = :id AND day = :day";
        $query = $this->db->prepare($sql);
        $query->execute([":id" => $id, ":day" => $day]);

        return $query->fetchAll();
    }

    public function create($id, $day, $quantity, $reason)
    {
        $sql = "INSERT INTO edit_pangs (student_id, day, quantity, reason) VALUES (:id, :day, :quantity, :reason)";
        $query = $this->db->prepare($sql);
        $parameters = array(':id' => $id, ':day' => $day, ':quantity' => $quantity, ':reason' => $reason);

        $query->execute($parameters);
    }

    public function delete($id)
    {
        $sql = "DELETE FROM edit_pangs WHERE id = :id";
        $query = $this->db->prepare($sql);
        $query->execute([":id" => $id]);
    }

}
