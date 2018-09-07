<?php

/**
 * Class Home
 *
 * Please note:
 * Don't use the same name for class and method, as this might trigger an (unintended) __construct of the class.
 * This is really weird behaviour, but documented here: http://php.net/manual/en/language.oop5.decon.php
 *
 */

 require(APP . 'controller/OAuth2/Client.php');
 require(APP . 'controller/OAuth2/GrantType/IGrantType.php');
 require(APP . 'controller/OAuth2/GrantType/AuthorizationCode.php');

use Microsoft\Graph\Graph;
use Microsoft\Graph\Model;

class Home extends Controller
{

    /**
     * PAGE: index
     * This method handles what happens when you move to http://yourproject/home/index (which is the default page btw)
     */
    public function index()
    {
        $_SESSION['errors-login-live'] = true;
        $auth = $this->authModel->getAuth();
        $errors = $this->authModel->getErrors();

        if (isset($auth))
            header('location: ' . URL . 'students');

        // load views
        require APP . 'view/_templates/header.php';
        require APP . 'view/home/index.php';
        require APP . 'view/_templates/footer.php';
    }

    public function editPassword()
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
        require APP . 'view/home/editPassword.php';
        require APP . 'view/_templates/footer.php';
    }

    public function updatePassword()
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
        //die(var_dump($auth['id'], $_POST['newPassword'], $auth['HavePassword']));

        if (isset($_POST['oldPassword']) && $_POST['oldPassword'] !== "" && $_POST['newPassword'] !== "" && $_POST['newPasswordConfirm'] !== "" && $_POST['newPassword'] === $_POST['newPasswordConfirm'] && $auth['HavePassword'])
        {
            if (sha1($_POST['oldPassword']) === $auth['passwordHash'])
            {
                $this->authModel->updatePassword($auth['id'], sha1($_POST['newPassword']));
                $_SESSION['auth']['passwordHash'] = sha1($_POST['newPassword']);
                header('location: ' . URL . 'students?confirm-edit-password=1');
            }
            else {
                header('location: ' . URL . 'home/editPassword?errors=1');
            }
        }
        else if ($_POST['newPassword'] !== "" && $_POST['newPasswordConfirm'] !== "" && $_POST['newPassword'] === $_POST['newPasswordConfirm'] && !$auth['HavePassword'])
        {
            $this->authModel->updatePassword($auth['id'], sha1($_POST['newPassword']));
            $_SESSION['auth']['passwordHash'] = sha1($_POST['newPassword']);
            $_SESSION['auth']['HavePassword'] = 1;
            header('location: ' . URL . 'students?confirm-edit-password=1');
        }
        else
        {
            header('location: ' . URL . 'home/editPassword?errors=1');
        }
    }

    public function login()
    {
        if (isset($_POST['email']) && isset($_POST['password'])) {

            $user = $this->authModel->login($_POST['email'], $_POST['password']);

            if (isset($user))
                header('location: ' . URL . 'students');
            else {
                $this->authModel->setErrors();
                header('location: ' . URL);
            }

        }
        header('location: ' . URL);
    }

    public function live()
    {
        $CLIENT_ID     = '604dee1f-f1a6-463d-b6e2-dbbac42428a4';
        $CLIENT_SECRET = 'gatCRAPK4008}dlswXE1~;)';

        $REDIRECT_URI           = 'https://samsung.absences.wac.epitech.eu/home/live';
        $AUTHORIZATION_ENDPOINT = 'https://login.microsoftonline.com/common/oauth2/v2.0/authorize';
        $TOKEN_ENDPOINT         = 'https://login.microsoftonline.com/common/oauth2/v2.0/token';

        $client = new OAuth2\Client($CLIENT_ID, $CLIENT_SECRET);
        if (!isset($_GET['code']))
        {
            $auth_url = $client->getAuthenticationUrl($AUTHORIZATION_ENDPOINT, $REDIRECT_URI);
            header('Location: ' . $auth_url);
            die('Redirect');

            // Y6bt3nFSiNohk61tI6qGDvk6g9PXaepAEtYSyveaN/M= AzureAd secretkey
            // ALTER TABLE `students` ADD `hors_parcours` INT NOT NULL DEFAULT '0' AFTER `email`;
        }
        else
        {
            $params = array('code' => $_GET['code'], 'redirect_uri' => $REDIRECT_URI);
            $response = $client->getAccessToken($TOKEN_ENDPOINT, 'authorization_code', $params);
            $client->setAccessTokenType($response['result']['token_type']);
            $client->setAccessToken($response['result']['access_token']);
            $response = $client->fetch('https://graph.microsoft.com/v1.0/me');
            $mail = $response['result']['mail'];
            $user = $this->authModel->getByMail($mail);
            if (!isset($user))
            {
                $student = $this->studentModel->getByMail($mail);
                if (!isset($student)) {
                    header("location: " . URL . '?errors-login-live=' . 1);
                    die();
                }

                $this->authModel->createAuthForStudent($student);
                $auth = $this->authModel->getAuth();
                if ($auth['isAdmin'] === '1') {
                    header('location: ' . URL . 'students');
                    die();
                }
                else {
                    $firstname = explode(" ", $auth['name'])[0];
                    $lastname = explode(" ", $auth['name'])[1];
                    header('location: ' . URL . 'students/view/' . $firstname . '.' . $lastname);
                }
            }
            else
            {
                $token = $this->authModel->randomToken();
                $this->authModel->setAuth($user, $token);

                $auth = $this->authModel->getAuth();

                if ($auth['isAdmin'] == '1')
                {
                        header('location: ' . URL . 'students');
                        die();
                }
                else {
                    $firstname = strtolower(explode(" ", $auth['name'])[0]);
                    $lastname = strtolower(explode(" ", $auth['name'])[1]);
                    header('location: ' . URL . 'students/view/' . $firstname . '.' . $lastname);
                }
            }
        }
    }

    public function logout()
    {
        unset($_SESSION['auth']);
        unset($_COOKIE['samcAuthEmail']);
        unset($_COOKIE['samcAuthToken']);
        setcookie('samcAuthEmail', '', time() - 3600, '/');
        setcookie('samcAuthToken', '', time() - 3600, '/');

        header('location: ' . URL);
    }

}
