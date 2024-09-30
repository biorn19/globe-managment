<?php 

require "models/Wage.php";

class AdminController
{
    public function showHomepage()
    {
        $userModel = new User();
        $users = $userModel->getUsers();
        require 'views\admin\homepage.php';
    }

    public function createWage()
    {
        $wageModel = new Wage();
        $userId = $_POST['id'];
        $userModel = new User();
        $user = $userModel->getUserById($userId);
        $lastWageDate = $wageModel->getLastWageDate($userId);
        $wage = $this->calculateWage($userId, $lastWageDate);
        
        if ($wage) {;  
            $wageModel->create($wage['wage'], $wage['start_date'], $wage['end_date'], $wage['sundays'], $wage['festive_days'], $userId);
            $success = ['user' => $user['name'] . ' ' . $user['surname'], 'wage' => $wage['wage']];
            $_SESSION['new_wage'] = $success;
        } else {
            $success = ['user' => $user['name'] . ' ' . $user['surname'], 'wage' => 'Has no days to be paid!'];
            $_SESSION['no_wage'] = $success;
        }
        header("Location: /globe-managment/admin/homepage");
    }

    private function calculateWage($id, $lastWage)
    {
        $activityModel = new Activity();
        $activities = [];

        if ($lastWage) {
            $lastDate = $lastWage['end_date'];
            $dateTime = new DateTime($lastDate);
            $dateTime->modify('+1 day');
            $startDate = $dateTime->format('Y-m-d');
            $activities = $activityModel->getActivitiesById($id, $startDate);
        } else {
            $activities = $activityModel->getAllActivitiesById($id);
            if (!empty($activities)) {
                $startDate = $activities[0]['date'];
            }
        }
        if (!empty($activities)) {
            $lastActivity = end($activities);
            if (is_array($lastActivity) && isset($lastActivity['date'])) {
                $endDate = $lastActivity['date']; 
            } else {
                error_log("Unexpected value from end($activities): " . print_r($lastActivity, true));
                return false;
            }

            $sundays = 0;
            $festiveDays = 0;
            $normalDays = 0;    
            foreach ($activities as $activity) {
                var_dump($activity);
                
                if (is_array($activity)) {
                    $isSunday = (isset($activity['is_sunday']) && $activity['is_sunday'] == 1);
                    $isFestive = (isset($activity['is_festive']) && $activity['is_festive'] == 1);
                    if ($isSunday) {
                        $sundays++;
                    } else if ($isFestive) {
                        $festiveDays++;
                    } else {
                        $normalDays++;
                    }
                } else {
                    error_log("Activity is not an array: " . print_r($activity, true));
                }
            }

            $vacationDates = [
                '01-01', // New Year's Day
                '01-02', // New Year's Day
                '03-14', // Summer Day
                '04-07', // Health Day
                '05-01', // Labor Day
                '05-24', // Eid al-Fitr (example date)
                '06-28', // Eid al-Adha (example date)
                '11-28', // Independence Day
                '11-29', // Liberation Day
                '12-25'  // Christmas Day
            ];
    
            $startDateTime = new DateTime($startDate);
            $endDateTime = new DateTime($endDate);
    
            foreach ($vacationDates as $vacationDate) {
                $year = $startDateTime->format('Y');
                $festiveDateTime = new DateTime("$year-$vacationDate");
                
                if ($festiveDateTime >= $startDateTime && $festiveDateTime <= $endDateTime) {
                    $normalDays++;
                }
            }
            
            $dayWage = 19.23;
            $festiveDayWage = $dayWage + (($dayWage * 10)/100);
            $sundayDayWage = $dayWage + (($dayWage * 15)/100);
            $normalDaysWage = $dayWage * $normalDays;
            $festiveDaysWage = $festiveDayWage * $festiveDays;
            $sundayDaysWage = $sundayDayWage * $sundays;

            $wage = $normalDaysWage + $festiveDaysWage + $sundayDaysWage;

            return [
                'wage' => $wage,
                'start_date' => $startDate,
                'end_date' => $endDate,
                'sundays' => $sundays,
                'festive_days' => $festiveDays,
            ];
        } else {
            return false;
        }
    }
}
