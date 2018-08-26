<?php

class Controller
{
    /**
     * @var null Database Connection
     */
    public $db = null;

    /**
     * @var null Model
     */
    public $authModel = null;

    /**
     * Whenever controller is created, open a database connection too and load "the model".
     */
    function __construct()
    {
        $this->openDatabaseConnection();
        $this->loadModel();
    }

    /**
     * Open the database connection with the credentials from application/config/config.php
     */
    private function openDatabaseConnection()
    {
        // set the (optional) options of the PDO connection. in this case, we set the fetch mode to
        // "objects", which means all results will be objects, like this: $result->user_name !
        // For example, fetch mode FETCH_ASSOC would return results like this: $result["user_name] !
        // @see http://www.php.net/manual/en/pdostatement.fetch.php
        $options = array(PDO::ATTR_DEFAULT_FETCH_MODE => PDO::FETCH_OBJ, PDO::ATTR_ERRMODE => PDO::ERRMODE_WARNING);

        // generate a database connection, using the PDO connector
        // @see http://net.tutsplus.com/tutorials/php/why-you-should-be-using-phps-pdo-for-database-access/
        $this->db = new PDO(DB_TYPE . ':host=' . DB_HOST . ';dbname=' . DB_NAME . ';charset=' . DB_CHARSET, DB_USER, DB_PASS, $options);
    }

    /**
     * Loads the "model".
     * @return object model
     */
    public function loadModel()
    {
        require APP . 'model/authModel.php';
        require APP . 'model/studentModel.php';
        require APP . 'model/dayModel.php';
        require APP . 'model/pangSettingsModel.php';
        require APP . 'model/editPangModel.php';
        require APP . 'model/logsModel.php';
        // create new "model" (and pass the database connection)
        $this->authModel = new AuthModel($this->db);
        $this->studentModel = new StudentModel($this->db);
        $this->dayModel = new DayModel($this->db);
        $this->pangSettingsModel = new PangSettingsModel($this->db);
        $this->editPangModel = new EditPangModel($this->db);
        $this->logsModel = new LogsModel($this->db);
    }
}
