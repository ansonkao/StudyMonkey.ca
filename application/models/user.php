<?php

class User extends StudyMonkey_Model
{
    protected $TABLE_NAME;

    protected $db_fields = array
        ( 'privilege'
        , 'login_counter'
        , 'school_id'
        , 'first_name'
        , 'last_name'
        , 'email'
        , 'password_encrypted'
        , 'username'
        , 'gender'
        , 'year_started_school'
        , 'program_id'
        , 'other'
        , 'referral_method'
        , 'total_credits'
        , 'total_dollars'
        , 'total_bananabucks'
        , 'total_course_professor_reviews'
        , 'total_notes'
        , 'total_note_reviews'
        , 'average_rating'
        , 'total_ratings'
        );

    function __construct()
    {
        parent::__construct();
        $this->TABLE_NAME = 'user';
    }

    public static function find_by_email($email) {
        global $database;
        if (empty($email)) { return false; }
        $result_array = self::find_by_sql("SELECT * FROM ". get_class() ." WHERE email='". $email ."' LIMIT 1");
        return !empty($result_array) ? array_shift($result_array) : false;
    }

    public static function find_by_username($username) {
        global $database;
        if (empty($username)) { return false; }
        $result_array = self::find_by_sql("SELECT * FROM ". get_class() ." WHERE username='". $username ."' LIMIT 1");
        return !empty($result_array) ? array_shift($result_array) : false;
    }

    public static function total_school_dollars ($school_id){
        global $database;
        $total = 0;
        $result_set = $database->query("SELECT SUM(total_dollars) FROM ". get_class() ." WHERE school_id={$school_id}");
        $row = $database->fetch_array($result_set);
        $total += array_shift($row);
        return $total;

    }


    public function update_totals($which = NULL) {
        global $database;
        if (empty($which) || $which == "credits") {
            $this->total_credits = transaction::total_user_credits($this->id);
        }
        if (empty($which) || $which == "dollars") {
            $this->total_dollars = transaction::total_user_dollars($this->id);
        }
        if (empty($which) || $which == "bananabucks") {
            $this->total_bananabucks = transaction::total_user_bananabucks($this->id);
        }
        if (empty($which) || $which == "course_professor_reviews") {
            $this->total_course_professor_reviews = course_professor_review::count_by_user_id($this->id);
        }
        if (empty($which) || $which == "note_reviews") {
            $this->total_note_reviews = note_review::count_by_user_id($this->id);
        }
        if (empty($which) || $which == "notes") {
            $this->total_notes = note::count_by_user_id($this->id);
        }
        if (empty($which) || $which == "ratings") {
            $this->average_rating = 0;
            $this->total_ratings = 0;
            foreach(note::find_by_user_id($this->id) as $note) {
                $this->average_rating += ($note->average_rating)? $note->average_rating*$note->total_reviews : 0;
                $this->total_ratings += $note->total_reviews;
            }
            $this->average_rating /= (float)$this->total_ratings;
        }
    }

    /* Returns the $user object if login is successful, false if password is
     * incorrect or user email does not exist.
     */
    public static function validate_login($email, $password) {
        global $database;
        if (empty($email) || empty($password)) { return false; }
        $current_user = self::find_by_email($email);
        if ($current_user) {
            if ($current_user->password_encrypted == md5($password)) {
                return $current_user;
            }
        }
        return false;
    }

    /* FORM VALIDATION
     * Returns an array of error strings corresponding to each
     * invalid parameter, otherwise returns false.
     */
    public static function validate_new($params) {
        $errors;
        
        // School
        if ($params['school_id'] <= 0 || $params['school_id'] > school::count_all()) {
            $errors['school_id'] = "Please indicate your school.";
            return $errors;
        }

        // Email format, valid school
        if (filter_var($params['email'], FILTER_VALIDATE_EMAIL) == false) {
            $errors['email'] = "Must be a valid e-mail address - did you get the spelling right?";
            return $errors;
        } else if (self::find_by_email($params['email'])) { // Email already taken?
            $errors['email'] = "An account has already been created with that e-mail.";
            return $errors;
        }

        // Password format
        if (ctype_alnum($params['password_encrypted']) == false || strlen($params['password_encrypted']) < 6 || strlen($params['password_encrypted']) > 20 ) {
            $errors['password_encrypted'] = "Your password must be alphanumeric, 6-20 characters long.";
            return $errors;
        }

        return $errors;
    }

    /* FORM VALIDATION
     * Returns an array of error strings corresponding to each
     * invalid parameter, otherwise returns false.
     */
    public static function validate_registration($params, $school_id = NULL) {
        $errors;

        // Program of Study
        if (empty($params['program_id'])) {
            $errors['program_id'] = "Please indicate your program/major.";
            return $errors;
        }
        // Year Started School
        if (empty($params['year_started_school'])) {
            $errors['year_started_school'] = "Please indicate which year you started university!";
            return $errors;
        }
        // Campus (if U of T)
        if ($school_id == 3 && empty($params['other'])) {
            $errors['other'] = "Please indicate your Campus!";
            return $errors;
        }
        // Gender
        if (empty($params['gender'])) {
            $errors['gender'] = "Please indicate your sex! (hehehe)";
            return $errors;
        }
        // First name format
        if (strlen($params['first_name']) < 1) {
            $errors['first_name'] = "Please enter your first name!";
            return $errors;
        } else if (strlen($params['first_name']) > 20) {
            $errors['first_name'] = "We can only accept first names up to 25 characters long.";
            return $errors;
        } else if (preg_match("/^[a-zA-Z-.\s]+$/", $params['first_name']) == false) {
            $errors['first_name'] = "No weird characters in your first name please!";
            return $errors;
        }
        // Last name format
        if (strlen($params['last_name']) < 1) {
            $errors['last_name'] = "Please enter your last name!";
            return $errors;
        } else if (strlen($params['first_name']) > 20) {
            $errors['last_name'] = "We can only accept last names up to 25 characters long.";
            return $errors;
        } else if (preg_match("/^[a-zA-Z-.\s]+$/", $params['last_name']) == false) {
            $errors['last_name'] = "No weird characters in your last name please!";
            return $errors;
        }
        // Username format
        if (strlen($params['username']) < 2 || strlen($params['username']) > 20) {
            $errors['username'] = "Your username must be 2-20 characters long.";
            return $errors;
        } else if (preg_match("/^[a-zA-Z0-9_-]+$/", $params['username']) == false) {
            $errors['username'] = "No spaces, exclamation marks, or other weird stuff in your username please!  Underscores or dashes are okay.";
            return $errors;
        // Username taken?
        } else if (self::find_by_username($params['username'])) {
            $errors['username'] = "Sorry, that Anonymous Username is already taken.";
            return $errors;
        }

        return $errors;
    }

}

?>