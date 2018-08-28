<?php

/**
 * Class Problem
 * Formerly named "Error", but as PHP 7 does not allow Error as class name anymore (as there's a Error class in the
 * PHP core itself) it's now called "Problem"
 *
 * Please note:
 * Don't use the same name for class and method, as this might trigger an (unintended) __construct of the class.
 * This is really weird behaviour, but documented here: http://php.net/manual/en/language.oop5.decon.php
 *
 */
class Admin extends Controller
{
    /**
     * PAGE: index
     * This method handles the error page that will be shown when a page is not found
     */
    public function index()
    {
        $auth = $this->authModel->getAuth();
        if (!isset($auth))
            header('location: ' . URL);

        if ($auth['isAdmin'] !== '1')
        {
            $name = explode(" ", $auth['name']);
            $firstname = $name[0];
            $lastname = $name[1];
            header('location: ' . URL . 'students/view/' . $firstname . '.' . $lastname);
        }

        $users = $this->authModel->getAdmins();

        // load views
        require APP . 'view/_templates/header.php';
        require APP . 'view/admin/index.php';
        require APP . 'view/_templates/footer.php';
    }

    public function add()
    {
        $auth = $this->authModel->getAuth();
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
        require APP . 'view/admin/add.php';
        require APP . 'view/_templates/footer.php';
    }

    public function addPost()
    {
        $auth = $this->authModel->getAuth();
        if (!isset($auth))
            header('location: ' . URL);

        if ($auth['isAdmin'] !== '1')
        {
            $name = explode(" ", $auth['name']);
            $firstname = $name[0];
            $lastname = $name[1];
            header('location: ' . URL . 'students/view/' . $firstname . '.' . $lastname);
        }

        if(isset($_POST['email']) && $_POST['email'] != "")
        {
            if (explode("@", explode(".", $_POST['email'])[1])[1] != 'epitech')
            {
                header('location: ' . URL .'admin/add?error-email=1');
            }
            else
            {
                $this->authModel->createAdmin($_POST['email']);
                header('location: ' . URL .'admin?confirm-create-admin=1');
            }
        }
        else
        {
            header('location: ' . URL .'admin/add?error-post-admin=1');
        }
    }

    public function resetBdd()
    {
        $auth = $this->authModel->getAuth();
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
        require APP . 'view/admin/resetBdd.php';
        require APP . 'view/_templates/footer.php';
    }

    public function postResetBdd()
    {
        if ($_POST['secret'] === 'samc227445')
        {
            $this->authModel->truncateBdd();
            header('location: ' . URL .'students?confirm-truncate=1');
        }
        else
        {
            header('location: ' . URL .'admin/resetBdd?error-secret=1');
        }
    }
}
