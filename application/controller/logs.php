<?php

use Carbon\Carbon;

class Logs extends Controller
{
    /**
     * PAGE: index
     * This method handles the error page that will be shown when a page is not found
     */
    public function index()
    {
        $auth = $this->authModel->getAuth();
        $logs = $this->logsModel->getAll();

        if (!isset($auth))
            header('location: ' . URL);

        if ($auth['isAdmin'] !== '1')
        {
            $name = explode(" ", $auth['name']);
            $firstname = $name[0];
            $lastname = $name[1];
            header('location: ' . URL . 'students/view/' . $firstname . '.' . $lastname);
        }

        // load views
        require APP . 'view/_templates/header.php';
        require APP . 'view/logs/index.php';
        require APP . 'view/_templates/footer.php';
    }
}
