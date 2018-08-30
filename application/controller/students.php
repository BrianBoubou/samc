
<?php

use Carbon\Carbon;

class Students extends Controller
{
    /**
     * PAGE: index
     * This method handles the error page that will be shown when a page is not found
     */
    public function index()
    {
        $auth = $this->authModel->getAuth();
        $students = $this->studentModel->getAll();
        $date = Carbon::now("Europe/Paris");
        //var_dump($date);

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
        require APP . 'view/students/index.php';
        require APP . 'view/_templates/footer.php';
    }

    public function view($login)
    {
        $auth = $this->authModel->getAuth();

        if (!isset($auth)) {
            header('location: ' . URL);
            die();
        }

        $name = explode(".", $login);
        $firstname = $name[0];
        $lastname = $name[1];
        $student = $this->studentModel->getByName($firstname, $lastname);
        if(!$student) {
            header('location: ' . URL);
            die();
        }
        if ($auth['isAdmin'] === '1' || $auth['email'] === $firstname . '.' . $lastname . '@epitech.eu') {
            $days = $this->dayModel->getById($student->id);
            $pangsHistory = [];
            $attendanceHistory = [[], []];
            $pangs = [];
            $settings = $this->pangSettingsModel->getSettings();
            $total = 1000;
            foreach ($days as $day) {
                $total += $day->difference;
                if ($total > 1000) {
                    $total = 1000;
                }

                if ($total < 0) {
                    $total = 0;
                }

                $attendanceHistory[0][$day->day] = $day->arrived_at;
                $attendanceHistory[1][$day->day] = $day->leaved_at;
                $pangsHistory[$day->day] = $total;
                $student->total = $total;
                $entry = [];
                if ($day->difference != 0) {
                    $entry[0] = $day->day;
                    if ($editPang = $this->editPangModel->getEditPangByDay($student->id, $day->day)) {
                        foreach ($editPang as $edit) {
                            $entry[0] = $day->day;
                            $entry[1] = $edit->quantity;
                            $entry[3] = $edit->reason;
                            $entry[4] = $edit->id;
                            array_push($pangs, $entry);
                            $entry = [];
                            // $day->difference -= ($edit->quantity > 0) ? $edit->quantity : abs($edit->quantity);
                        }
                    }
                    if ($day->difference > 0) {
                        $entry[0] = $day->day;
                        $entry[1] = $day->difference;
                        $entry[3] = "Temps de présence avant " . $settings->morning_start . " et / ou après " . $settings->afternoon_extra;
                        array_push($pangs, $entry);
                    } elseif ($day->difference < 0) {
                        $entry[0] = $day->day;
                        $entry[1] = $day->difference;
                        if ($day->difference > (-1 * $settings->absent_loss)) {
                            $entry[3] = "Retard";
                        } else {
                            $entry[3] = "Absence a une (demi-)journée";
                        }
                        array_push($pangs, $entry);
                    }
                }
            }
            $student->pangs = $pangs;
            $student->pangsHistory = $pangsHistory;
            $student->attendanceHistory = $attendanceHistory;
            if (!isset($student->total))
                $student->total = 1000;

            // load views
            require APP . 'view/_templates/header.php';
            require APP . 'view/students/view.php';
            require APP . 'view/_templates/footer.php';
        }
        else
        {
            header('location: ' . URL);
        }
    }

    public function jsonStudentsData()
    {
        $auth = $this->authModel->getAuth();
        if (!isset($auth)) {
            echo json_encode(["error" => "disconnected"]);
            die();
        }

        if ($auth['isAdmin'] !== '1')
        {
            $name = explode(" ", $auth['name']);
            $firstname = $name[0];
            $lastname = $name[1];
            echo json_encode(['error' => 'not admin']);
            die();
        }

        $data = [];
        $students = $this->studentModel->getAll();
        $date = Carbon::now("Europe/Paris");
        foreach ($students as $student) {
            $days = $this->dayModel->getById($student->id);
            if(!$date->isWeekend())
            {
                $day = $this->dayModel->getByIdAndDay($student->id, $date->toDateString());
                if (empty($day)) {
                    $day = $this->dayModel->create($student->id, $date->toDateString(), 0);
                    $this->dayModel->proccessPangs($student->id, $day);
                }
                else {
                    $this->dayModel->proccessPangs($student->id, $day[0]);
                }
            }
            $student->checkIn = ($this->dayModel->getByIdAndDay($student->id, $date->toDateString()) !== []) ? $this->dayModel->getByIdAndDay($student->id, $date->toDateString())[0] : null ;
            $total = 1000;
            foreach ($days as $day) {
                $total += $day->difference;
                if ($total > 1000) {
                    $total = 1000;
                }

                if ($total < 0) {
                    $total = 0;
                }

            }

            if ($total <= 0) {
                $student->pangs = '<h4><span class="badge badge-danger">' . $total . '</span></h4>';
            } else if ($total <= 300) {
                $student->pangs = '<h4><span class="badge badge-warning">' . $total . '</span></h4>';
            } else if ($total <= 700) {
                $student->pangs = '<h4><span class="badge badge-primary">' . $total . '</span></h4>';
            } else {
                $student->pangs = '<h4><span class="badge badge-success">' . $total . '</span></h4>';
            }

            $tooltip = 'class="image-tooltip" data-tooltip-content="#image-'.$student->id.'"';
            $student->first_name_data = '<a '. $tooltip .' href="' . URL . 'students/view/' . $student->first_name . '.' . $student->last_name . '">' . ucfirst($student->first_name) . '</a>';
            $student->last_name_data = '<a '. $tooltip .' href="' . URL . 'students/view/' . $student->first_name . '.' . $student->last_name . '">' . ucfirst($student->last_name) . '</a>';

            if ($student->hors_parcours != '1')
            {
                if (is_object($student->checkIn) && $student->checkIn->day === \Carbon\Carbon::now()->toDateString() && $student->checkIn->arrived_at !== null) {
                    $checkIn = $student->checkIn->arrived_at;
                } else {
                    $checkIn = '<form method="post" class="checkIn" action="' . URL . "students/checkIn" . '">
                    ' . '
                    <input type="hidden" name="id" value="' . $student->id . '">
                    <button type="submit" class="btn btn-success btn-sm">Check-In</button>
                    </form>';
                }

                if (is_object($student->checkIn) && $student->checkIn->day === \Carbon\Carbon::now()->toDateString() && $student->checkIn->leaved_at !== null) {
                    $checkOut = $student->checkIn->leaved_at;
                } else {
                    if (is_object($student->checkIn) && $student->checkIn->day === \Carbon\Carbon::now()->toDateString() && $student->checkIn->arrived_at !== null) {
                        $checkOut = '<form method="post" class="checkOut" action="' . URL . "students/checkOut" . '">
                        ' . '
                        <input type="hidden" name="id" value="' . $student->id . '">
                        <button type="submit" class="btn btn-warning btn-sm">Check-Out</button>
                        </form>';
                    } else {
                        $checkOut = '';
                    }
                }
            }
            else{
                $checkIn = "hors parcours";
                $checkOut = "hors parcours";
            }


            array_push($data, ['id' => $student->id, 'first_name' => $student->first_name_data, 'last_name' => $student->last_name_data, 'pangs' => $student->pangs, 'checkin' => $checkIn, 'checkout' => $checkOut]);
        }
        $json = ['data' => $data];
        echo json_encode($json);
    }

    public function ajaxHorsParcours($student_id)
    {
        $auth = $this->authModel->getAuth();
        if (!isset($auth)) {
            echo json_encode(["error" => "disconnected"]);
            die();
        }

        if ($auth['isAdmin'] !== '1')
        {
            $name = explode(" ", $auth['name']);
            $firstname = $name[0];
            $lastname = $name[1];
            echo json_encode(['error' => 'not admin']);
            die();
        }

        $this->studentModel->horsParcours($student_id);
        echo json_encode(['success' => 'true']);
    }

    public function checkIn()
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

        $student = $this->studentModel->getById($_POST['id']);
        $date = Carbon::now("Europe/Paris");

        $this->dayModel->updateCheckIn($date->toDateString(), $_POST['id'], $date->toTimeString());
        $this->logsModel->create($auth['id'], 1, $date->toDateTimeString() . " : " . $auth['name'] . " a pointé " . ucfirst($student->first_name)  . " " .ucfirst($student->last_name));

        echo $date->toTimeString();
    }

    public function checkOut()
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

        $student = $this->studentModel->getById($_POST['id']);
        $date = Carbon::now("Europe/Paris");

        $this->dayModel->updateCheckOut($date->toDateString(), $_POST['id'], $date->toTimeString());
        $this->logsModel->create($auth['id'], 1, $date->toDateTimeString() . " : " . $auth['name'] . " a dépointé " . ucfirst($student->first_name)  . " " .ucfirst($student->last_name));

        echo $date->toTimeString();
    }

    public function editChecks()
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

        $students = $this->studentModel->getAll();

        // load views
        require APP . 'view/_templates/header.php';
        require APP . 'view/students/editCheck.php';
        require APP . 'view/_templates/footer.php';
    }

    public function updateChecks()
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

        if (!isset($_POST['students']) || !isset($_POST['day']))
            header('location: ' . URL);


        $date = Carbon::now("Europe/Paris");

        foreach ($_POST['students'] as $student_id) {
            $student = $this->studentModel->getById($student_id);
            $day = $this->dayModel->getByIdAndDay($student_id, $_POST['day']);

            if (empty($day))
                $day = $this->dayModel->create($student_id, $date->toDateString(), 0);
            else
                $day = $day[0];

            if ($_POST["arrived_at"] !== null && $_POST['arrived_at'] !== '') {

                $this->dayModel->updateCheckIn(
                    $_POST['day'],
                    $student_id,
                    $_POST["arrived_at"]
                );

                $this->logsModel->create(
                    $auth['id'],
                    2,
                    $date->toDateTimeString() . " : " . $auth['name'] . " a modifié l'heure de pointage de " . ucfirst($student->first_name)  . " " .ucfirst($student->last_name) . " de $day->arrived_at à " . $_POST["arrived_at"] . ":00 le " . $_POST['day']
                );
            }

            if ($_POST["leaved_at"] !== null && $_POST['leaved_at'] !== '') {
                $this->dayModel->updateCheckOut(
                    $_POST['day'],
                    $student_id,
                    $_POST["leaved_at"]
                );

                $this->logsModel->create(
                    $auth['id'],
                    2,
                    $date->toDateTimeString() . " : " . $auth['name'] . " a modifié l'heure de dépointage de " . ucfirst($student->first_name)  . " " .ucfirst($student->last_name) . " de $day->leaved_at à " . $_POST["leaved_at"] . ":00 le " . $_POST['day']
                );
            }
        }

        header('location: ' . URL);
    }

    public function editPangs()
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

        $students = $this->studentModel->getAll();

        // load views
        require APP . 'view/_templates/header.php';
        require APP . 'view/students/editPangs.php';
        require APP . 'view/_templates/footer.php';
    }

    public function updatePangs()
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

        if (!isset($_POST['students']) || !isset($_POST['day']) || !isset($_POST['quantity']) || !isset($_POST['reason']))
            return $this->editPangs();


        $date = Carbon::now("Europe/Paris");

        foreach ($_POST['students'] as $student_id) {

            $student = $this->studentModel->getById($student_id);
            $sign = ($_POST['quantity']) > 0 ? "ajouté" : "retiré";

            $this->editPangModel->create(
                $student_id,
                $_POST['day'],
                $_POST['quantity'],
                $_POST['reason']
            );

            $this->logsModel->create(
                $auth['id'],
                5,
                $date->toDateTimeString() . " : " . $auth['name'] . " a $sign " . abs($_POST['quantity']) . " pangs à " . ucfirst($student->first_name)  . " " .ucfirst($student->last_name) . " : " . $_POST['reason']
            );

            $day = $this->dayModel->getByIdAndDay($student_id, $_POST['day']);

            if (empty($day))
                $day = $this->dayModel->create($student_id, $date->toDateString(), 0);
            else
                $day = $day[0];

            $this->dayModel->proccessPangs($student_id, $day);
        }

        header('location: ' . URL . 'students?confirm-edit-pangs');
    }

    public function justify()
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

        $students = $this->studentModel->getAll();

        // load views
        require APP . 'view/_templates/header.php';
        require APP . 'view/students/justify.php';
        require APP . 'view/_templates/footer.php';
    }

    public function storeJustify()
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

        if (!isset($_POST['students']) || !isset($_POST['days']) || !isset($_POST['reason']))
            return $this->justify();


        $_POST['days'] = explode(" - ", $_POST['days'][0]);
        $date = Carbon::now("Europe/Paris");

        foreach($_POST['students'] as $student_id){

            foreach ($_POST['days'] as $key => $postDay) {

                $student = $this->studentModel->getById($student_id);
                $day = $this->dayModel->getByIdAndDay($student_id, Carbon::createFromFormat('m/d/Y', $postDay)->format("Y-m-d"));
                if (empty($day))
                    $day = $this->dayModel->create($student_id, Carbon::createFromFormat('m/d/Y', $postDay)->format("Y-m-d"), 0, 1, $_POST['reason']);
                else
                {
                    $day = $day[0];
                    $this->dayModel->update($day->id, null, 1, $_POST['reason']);
                }


                $this->logsModel->create(
                    $auth['id'],
                    3,
                    $date->toDateTimeString() . " : " . $auth['name'] . " a ajouté une excuse à " . ucfirst($student->first_name) . " " . ucfirst($student->last_name) . " le " . $postDay . " : " . $_POST['reason']
                );

                $day = $this->dayModel->getByIdAndDay($student_id, Carbon::createFromFormat('m/d/Y', $postDay)->format("Y-m-d"))[0];

                $this->dayModel->proccessPangs($student_id, $day);

            }
        }

        header('location: ' . URL . 'students?confirm-store-justify=1');
    }

    public function deleteJustify($id)
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

        $day = $this->dayModel->getByDayId($id);
        $student = $this->studentModel->getById($day->student_id);
        $date = Carbon::now("Europe/Paris");

        $this->logsModel->create(
            $auth['id'],
            4,
            $date->toDateTimeString() . " : " . $auth['name'] . " a supprimé l'excuse \"" . $day->reason . "\" à " . ucfirst($student->first_name) . " " . ucfirst($student->last_name) . " du " . $day->day . "."
        );

        $this->dayModel->deleteJustify($id);
    }

    public function deletePangs($edit_pang_id)
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

        $edit = $this->editPangModel->getById($edit_pang_id);
        $student = $this->studentModel->getById($edit->student_id);
        $sign = ($edit->quantity > 0) ? "l'ajout" : "le retrait";
        $date = Carbon::now("Europe/Paris");

        $this->logsModel->create(
            $auth['id'],
            6,
            $date->toDateTimeString() . " : " . $auth['name'] . " a supprimé $sign de " . abs($edit->quantity) . " pangs à " . ucfirst($student->first_name)  . " " .ucfirst($student->last_name) . "."
        );

        $this->editPangModel->delete($edit_pang_id);
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
        require APP . 'view/students/add.php';
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

        if (!isset($_POST['firstname']) || !isset($_POST['lastname']))
            header('location: ' . URL . 'students/add');


        $this->studentModel->create($_POST['firstname'], $_POST['lastname']);
        header('location: ' . URL . 'students?confirm-add-students=1');
    }

    public function addBulk()
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
        require APP . 'view/students/addBulk.php';
        require APP . 'view/_templates/footer.php';
    }

    public function storeBulk()
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

        if (isset($_FILES['file']) && $_FILES['file']['error'] === 0) {

            // UPLOAD DU FICHIER CSV, vérification et insertion en BASE
            if($_FILES["file"]["type"] != "application/vnd.ms-excel") {
                header('location: ' . URL . 'students/addBulk?error-file-type=1');
            }
            elseif(is_uploaded_file($_FILES['file']['tmp_name'])) {

            	//Process the CSV file
            	$handle = fopen($_FILES['file']['tmp_name'], "r");
            	$data = fgetcsv($handle, 1000, ";"); //Remove if CSV file does not have column headings
                if ($data[0] == 'login' || $data[0] == 'Login' || $data[0] == 'email' || $data[0] == 'mail')
                {
                    while (($data = fgetcsv($handle, 1000, ";")) !== FALSE) {
                        $mail = $data[0];
                        if (isset($mail) && $mail != '')
                            $this->studentModel->createByMail($mail);
                    }
                    header('location: ' . URL . 'students?confirm-add-students=1');
                }
                else
                    header('location: ' . URL . 'students/addBulk?error-header-csv=1');

            }
            else
                header('location: ' . URL . 'students/addBulk?error-upload-file=1');

        }
        else {

            // die(var_dump($_POST['names']));
            if (!isset($_POST['names']) || $_POST['names'] == ''){
                header('location: ' . URL . 'students/addBulk?error-empty-input=1');
                die();
            }

            $names = explode("\n", $_POST['names']);
            foreach ($names as $name) {
                $parts = explode(" ", $name);
                $this->studentModel->create($parts[0], $parts[1]);
            }
            header('location: ' . URL . 'students?confirm-add-students=1');
        }
    }

    public function ajaxUpdateExcuse()
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

        if (isset($_GET['id']) && isset($_GET['reason']) && $_GET['reason'] !== "")
        {
            $this->dayModel->updateReason($_GET['id'], $_GET['reason']);
            $date = Carbon::now("Europe/Paris");
            $firstname = explode(".", $_GET['student'])[0];
            $lastname = explode(".", $_GET['student'])[1];

            $this->logsModel->create(
                $auth['id'],
                7,
                $date->toDateTimeString() . " : " . $auth['name'] . " a modifié l'excuse \"" . $_GET['reason'] . "\" à " . ucfirst($firstname) . " " . ucfirst($lastname) . " du " . $_GET['day'] . "."
            );

            echo true;
        }
        else
            echo false;
    }

    public function ajaxUpdateEditPang()
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

        if (isset($_GET['id']) && isset($_GET['reason']) && $_GET['reason'] !== "" && isset($_GET['diff']) && $_GET['diff'] !== "")
        {
            $this->editPangModel->updatePangsEdit($_GET['id'], $_GET['reason'], $_GET['diff']);
            $date = Carbon::now("Europe/Paris");
            $sign = ($_GET['diff'] > 0) ? "l'ajout" : 'le retrait';
            $firstname = explode(".", $_GET['student'])[0];
            $lastname = explode(".", $_GET['student'])[1];
            $this->logsModel->create(
                $auth['id'],
                8,
                $date->toDateTimeString() . " : " . $auth['name'] . " a modifié $sign de " . abs($_GET['diff']) . " pangs à " . ucfirst($firstname)  . " " .ucfirst($lastname) . "."
            );
            echo true;
        }
        else
            echo false;
    }

    public function ajaxUpdateChecks()
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

        if (isset($_GET['id']) && isset($_GET['checkIn']) && $_GET['checkIn'] !== "")
        {
            $student_id = $_GET['studentId'];
            $this->dayModel->updateCheckInByDayId($_GET['id'], $_GET['checkIn'], $student_id);
            $date = Carbon::now("Europe/Paris");
            $firstname = explode(".", $_GET['student'])[0];
            $lastname = explode(".", $_GET['student'])[1];
            $day = $this->dayModel->getByDayId($_GET['id']);

            $this->logsModel->create(
                $auth['id'],
                2,
                $date->toDateTimeString() . " : " . $auth['name'] . " a modifié l'heure de pointage de " . ucfirst($firstname)  . " " .ucfirst($lastname) . " de $day->arrived_at à " . $_GET["checkIn"] . ":00 le " . $_GET['day']
            );
        }
        if (isset($_GET['id']) && isset($_GET['checkOut']) && $_GET['checkOut'] !== "")
        {
            $student_id = $_GET['studentId'];
            $this->dayModel->updateCheckOutByDayId($_GET['id'], $_GET['checkOut'], $student_id);
            $date = Carbon::now("Europe/Paris");
            $firstname = explode(".", $_GET['student'])[0];
            $lastname = explode(".", $_GET['student'])[1];
            $day = $this->dayModel->getByDayId($_GET['id']);

            $this->logsModel->create(
                $auth['id'],
                2,
                $date->toDateTimeString() . " : " . $auth['name'] . " a modifié l'heure de dépointage de " . ucfirst($firstname)  . " " .ucfirst($lastname) . " de $day->leaved_at à " . $_GET["checkOut"] . ":00 le " . $_GET['day']
            );
        }

        echo true;

    }
}
