<?php

use Carbon\Carbon;

class DayModel
{

    private $settings;
    private $students;
    private $date;
    private $morning_early;
    private $morning_start;
    private $morning_late;
    private $morning_end;
    private $afternoon_start;
    private $afternoon_leave;
    private $afternoon_extra;
    private $afternoon_end;
    private $pangSettingsModel;
    private $editPangModel;

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

        $this->pangSettingsModel = new PangSettingsModel($this->db);
        $this->editPangModel = new EditPangModel($this->db);
        $this->settings = $this->pangSettingsModel->getSettings();
        $day = date('Y-m-d');

        $this->date = ($day !== null) ? Carbon::createFromFormat("Y-m-d", $day) : Carbon::now("Europe/Paris")->addHour(2);

        $this->morning_early = Carbon::createFromFormat("Y-m-d H:i:s", $this->date->toDateString() . " " . $this->settings->morning_early);
        $this->morning_start = Carbon::createFromFormat("Y-m-d H:i:s", $this->date->toDateString() . " " . $this->settings->morning_start);
        $this->morning_late = Carbon::createFromFormat("Y-m-d H:i:s", $this->date->toDateString() . " " . $this->settings->morning_late);
        $this->morning_end = Carbon::createFromFormat("Y-m-d H:i:s", $this->date->toDateString() . " " . $this->settings->morning_end);
        $this->afternoon_start = Carbon::createFromFormat("Y-m-d H:i:s", $this->date->toDateString() . " " . $this->settings->afternoon_start);
        $this->afternoon_leave = Carbon::createFromFormat("Y-m-d H:i:s", $this->date->toDateString() . " " . $this->settings->afternoon_leave);
        $this->afternoon_extra = Carbon::createFromFormat("Y-m-d H:i:s", $this->date->toDateString() . " " . $this->settings->afternoon_extra);
        $this->afternoon_end = Carbon::createFromFormat("Y-m-d H:i:s", $this->date->toDateString() . " " . $this->settings->afternoon_end);

        if ($day !== null) {
            $this->date->hour = $this->afternoon_end->hour;
            $this->date->minute = $this->afternoon_end->minute;
            $this->date->second = $this->afternoon_end->second;
        }
    }

    public function getAll()
    {
        $sql = "SELECT * FROM days";
        $query = $this->db->prepare($sql);
        $query->execute();


        return $query->fetchAll();
    }

    public function getById($id)
    {
        $sql = "SELECT * FROM days WHERE student_id = :id";
        $query = $this->db->prepare($sql);
        $query->execute([":id" => $id]);


        return $query->fetchAll();
    }

    public function getByDayId($id)
    {
        $sql = "SELECT * FROM days WHERE id = :id";
        $query = $this->db->prepare($sql);
        $query->execute([":id" => $id]);


        return $query->fetchAll()[0];
    }

    public function getByIdAndDay($id, $day)
    {
        $sql = "SELECT * FROM days WHERE student_id = :id AND day = :day";
        $query = $this->db->prepare($sql);
        $query->execute([":id" => $id, ":day" => $day]);


        return $query->fetchAll();
    }

    public function create($id, $day, $diff = 0, $justify = 0, $reason = null)
    {
        $sql = "INSERT INTO days (student_id, day, difference) VALUES (:id, :day, :diff)";
        $query = $this->db->prepare($sql);
        $parameters = array(':id' => $id, ':day' => $day, ':diff' => $diff);

        $newId = $query->execute($parameters);
        $newId = $this->db->lastInsertId();

        $sql = "SELECT * FROM days WHERE id = :id";
        $query = $this->db->prepare($sql);
        $query->execute([":id" => $newId]);


        return $query->fetchAll()[0];
    }

    public function update($id, $diff = null, $justify = 0, $reason = null)
    {
        if($justify)
        {
            $sql = "UPDATE days SET excused = :excused, reason = :reason WHERE id = :id";
            $query = $this->db->prepare($sql);
            $parameters = array(':excused' => 1, ':reason' => $reason, ":id" => $id);
            $query->execute($parameters);
        }
        else if(isset($diff))
        {

        }
    }

    public function updateCheckIn($dayString, $id, $arrived)
    {
        if(!$this->date->isWeekend()) {
            $this->date = Carbon::now("Europe/Paris");
            $day = $this->getByIdAndDay($id, $dayString);
            if (empty($day)) {
                $day = $this->create($id, $dayString, 0);
            }
            else
                $day = $day[0];

                // die(var_dump($day, $arrived, $id));
            $sql = "UPDATE days SET arrived_at = :arrived WHERE student_id = :student_id AND id = :id";
            $query = $this->db->prepare($sql);
            $parameters = array(':arrived' => $arrived, ':student_id' => $id, ":id" => $day->id);
            $query->execute($parameters);

            $day = $this->getByIdAndDay($id, $dayString)[0];

            $this->proccessPangs($id, $day);
        }
    }

    public function updateCheckOut($dayString, $id, $leaved)
    {
        if(!$this->date->isWeekend()) {
            $this->date = Carbon::now("Europe/Paris");
            $day = $this->getByIdAndDay($id, $dayString)[0];
            if (empty($day)) {
                $day = $this->create($id, $dayString, 0);
            }

            $sql = "UPDATE days SET leaved_at = :leaved_at WHERE student_id = :student_id AND id = :id";
            $query = $this->db->prepare($sql);
            $parameters = array(':leaved_at' => $leaved, ':student_id' => $id, ":id" => $day->id);
            $query->execute($parameters);

            $day = $this->getByIdAndDay($id, $dayString)[0];

            $this->proccessPangs($id, $day);
        }
    }

    public function deleteJustify($id)
    {
        $sql = "UPDATE days SET excused = :excused WHERE id = :id";
        $query = $this->db->prepare($sql);
        $parameters = array(':excused' => 0, ":id" => $id);
        $query->execute($parameters);
    }

    public function proccessPangs($id, $day)
    {
        $arrive = ($day->arrived_at !== null) ? Carbon::createFromFormat("Y-m-d H:i:s", $this->date->toDateString() . " " . $day->arrived_at) : null;
        $leave = ($day->leaved_at !== null) ? Carbon::createFromFormat("Y-m-d H:i:s", $this->date->toDateString() . " " . $day->leaved_at) : null;
        $morning_loss = 0;
        $morning_gain = 0;
        $afternoon_loss = 0;
        $afternoon_gain = 0;
        $morning_absent = false;
        $afternoon_absent = false;
        $this->date = Carbon::now("Europe/Paris");

        if (!$this->date->isWeekend()) {

            if ($arrive !== null) {
                // Check pangs loss
                // Morning
                if($arrive >= $this->morning_end) {
                    $morning_loss = $this->settings->absent_loss;
                } elseif($arrive > $this->morning_late) {
                    $morning_loss += $arrive->diffInMinutes($this->morning_late) * $this->settings->losing_pang;
                }
                if($leave !== null && $leave < $this->morning_end) {
                    $morning_loss += $leave->diffInMinutes($this->morning_end) * $this->settings->losing_pang;
                }
                $morning_loss = ($morning_loss >= $this->settings->absent_loss) ? $this->settings->absent_loss : $morning_loss;
                $morning_absent = ($morning_loss >= $this->settings->absent_loss) ? true : false;

                // Afternoon
                if ($leave === null && $this->date >= $this->afternoon_end) {
                    $afternoon_loss += $this->settings->absent_loss;
                } else {
                    if( $leave != null) {
                        if ( $leave <= $this->afternoon_start) {
                            $afternoon_loss += $this->settings->absent_loss;
                        } elseif ($leave < $this->afternoon_leave) {
                            $afternoon_loss += $leave->diffInMinutes($this->afternoon_leave) * $this->settings->losing_pang;
                        }
                    }
                }
                if ($arrive > $this->afternoon_start) {
                    $afternoon_loss += $arrive->diffInMinutes($this->afternoon_start) * $this->settings->losing_pang;
                }
                $afternoon_loss = ($afternoon_loss >= $this->settings->absent_loss) ? $this->settings->absent_loss : $afternoon_loss;
                $afternoon_absent = ($afternoon_loss >= $this->settings->absent_loss) ? true : false;

                // Check pangs gain
                // Morning
                if($arrive < $this->morning_start) {
                    if ($day->excused || !$morning_absent) {
                        $arrive = ($arrive < $this->morning_early) ? $this->morning_early : $arrive;
                        $morning_gain = $arrive->diffInMinutes($this->morning_start) * $this->settings->earning_pang;
                    }
                }

                // Afternoon
                if ($leave > $this->afternoon_extra) {
                    if ($day->excused || !$afternoon_absent) {
                        $leave = ($leave > $this->afternoon_end) ? $this->afternoon_end : $leave;
                        $afternoon_gain = $leave->diffInMinutes($this->afternoon_extra) * $this->settings->earning_pang;
                    }
                }

                if($day->excused) {
                    $morning_loss = 0;
                    $afternoon_loss = 0;
                }

                $tManual = $this->editPangModel->getEditPangByDay($id, $this->date->toDateString());
                $editQuantity = 0;
                foreach ($tManual as $manual){
                    $editQuantity += $manual->quantity;
                }

                $difference = $morning_gain - $morning_loss + $afternoon_gain - $afternoon_loss + $editQuantity;

                // Update difference in days table
                $sql = "UPDATE days SET difference = :diff WHERE student_id = :id AND id = :i";
                $query = $this->db->prepare($sql);
                $parameters = array(':diff' => $difference, ':id' => $id, ":i" => $day->id);
                $query->execute($parameters);
            }
            else {
                // Cheking Absent loss
                // Morning
                if($this->date > $this->morning_late) {
                    $morning_loss += $this->date->diffInMinutes($this->morning_late) * $this->settings->losing_pang;
                    $morning_loss = ($morning_loss >= $this->settings->absent_loss) ? $this->settings->absent_loss : $morning_loss;
                }

                // Afternoon
                if($this->date > $this->afternoon_start) {
                    $afternoon_loss += $this->date->diffInMinutes($this->afternoon_start) * $this->settings->losing_pang;
                    $afternoon_loss = ($afternoon_loss >= $this->settings->absent_loss) ? $this->settings->absent_loss : $afternoon_loss;
                }

                if ($this->date >= $this->afternoon_end) {
                    $morning_loss = $this->settings->absent_loss;
                    $afternoon_loss = $this->settings->absent_loss;
                    $morning_absent = true;
                    $afternoon_absent = true;
                }

                if($day->excused) {
                    $morning_loss = 0;
                    $afternoon_loss = 0;
                }

                $tManual = $this->editPangModel->getEditPangByDay($id, $this->date->toDateString());
                $editQuantity = 0;
                foreach ($tManual as $manual){
                    $editQuantity += $manual->quantity;
                }

                $difference = $morning_gain - $morning_loss + $afternoon_gain - $afternoon_loss + $editQuantity;



                $sql = "UPDATE days SET difference = :diff WHERE student_id = :id AND id = :i";
                $query = $this->db->prepare($sql);
                $parameters = array(':diff' => $difference, ':id' => $id, ":i" => $day->id);
                $query->execute($parameters);
            }
        }
    }

}
