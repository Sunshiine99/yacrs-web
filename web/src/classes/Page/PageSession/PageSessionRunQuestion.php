<?php

class PageSessionRunQuestion extends PageSessionRun
{

    /**
     * Page to add a new question to a session
     * @param int $sessionIdentifier
     */
    public static function add($sessionIdentifier) {
        /**
         * Setup basic session variables (Type hinting below to avoid IDE error messages)
         * @var $templates League\Plates\Engine
         * @var $data array
         * @var $config array
         * @var $user User
         * @var $mysqli mysqli
         * @var $session Session
         */
        extract(self::setup($sessionIdentifier));

        // Setup Page breadcrumbs
        $breadcrumbs = new Breadcrumb();
        $breadcrumbs->addItem($config["title"], $config["baseUrl"]);
        $breadcrumbs->addItem("Sessions", $config["baseUrl"]."session/");
        $breadcrumbs->addItem($sessionIdentifier, $config["baseUrl"]."session/$sessionIdentifier/");
        $breadcrumbs->addItem("Run", $config["baseUrl"]."session/$sessionIdentifier/run/");
        $breadcrumbs->addItem("Questions", $config["baseUrl"]."session/$sessionIdentifier/run/questions/");
        $breadcrumbs->addItem("New");

        $data["session"] = $session;
        $data["breadcrumbs"] = $breadcrumbs;
        $data["user"] = $user;
        echo $templates->render("session/run/questions/edit", $data);
    }

    /**
     * Submits a new session
     * @param int $sessionID
     */
    public static function addSubmit($sessionIdentifier) {
        /**
         * Setup basic session variables (Type hinting below to avoid IDE error messages)
         * @var $templates League\Plates\Engine
         * @var $data array
         * @var $config array
         * @var $user User
         * @var $mysqli mysqli
         * @var $session Session
         */
        extract(self::setup($sessionIdentifier));

        // Attempt to create a new question for this question type
        try {
            $question = QuestionFactory::create($_POST["questionType"], $_POST);
        }

        // If error creating question, log the error and display an error page
        catch(Exception $e) {
            Error::exception($e, __LINE__, __FILE__);
            die();
        }

        // If MCQ or MRQ question
        if(in_array(get_class($question), ["QuestionMcq", "QuestionMrq"])) {

            // Loop for every posted value
            foreach($_POST as $key => $value) {

                // If this is one of the MCQ choices, add it as a choice
                if(substr($key, 0, 11) == "mcq-choice-") {
                    $question->addChoice($value);
                }
            }
        }

        // Insert question into the database
        $questionID = DatabaseQuestion::insert($question, $mysqli);

        // Load the session ID
        $sessionID = DatabaseSessionIdentifier::loadSessionID($sessionIdentifier, $mysqli);

        // Insert question session combo into DatabaseSession
        DatabaseSessionQuestion::insert($sessionID, $questionID, $mysqli);

        header("Location: " . $config["baseUrl"] . "session/$sessionIdentifier/run/");
        die();
    }

    public static function edit($sessionIdentifier, $sessionQuestionID) {
        /**
         * Setup basic session variables (Type hinting below to avoid IDE error messages)
         * @var $templates League\Plates\Engine
         * @var $data array
         * @var $config array
         * @var $user User
         * @var $mysqli mysqli
         * @var $session Session
         */
        extract(self::setup($sessionIdentifier));

        $sessionID = DatabaseSessionIdentifier::loadSessionID($sessionIdentifier, $mysqli);

        // Get question whilst ensuring permissions are kept
        $question = self::setupQuestion($sessionID, $sessionQuestionID, $mysqli);

        // Setup Page breadcrumbs
        $breadcrumbs = new Breadcrumb();
        $breadcrumbs->addItem($config["title"], $config["baseUrl"]);
        $breadcrumbs->addItem("Sessions", $config["baseUrl"]."session/");
        $breadcrumbs->addItem($sessionIdentifier, $config["baseUrl"]."session/$sessionIdentifier/");
        $breadcrumbs->addItem("Run", $config["baseUrl"]."session/$sessionIdentifier/run/");
        $breadcrumbs->addItem("Questions", $config["baseUrl"]."session/$sessionIdentifier/run/questions/");
        $breadcrumbs->addItem("Edit");

        $data["question"] = $question;
        $data["session"] = $session;
        $data["breadcrumbs"] = $breadcrumbs;
        $data["user"] = $user;
        echo $templates->render("session/run/questions/edit", $data);
    }

    public static function editSubmit($sessionIdentifier, $sessionQuestionID) {
        /**
         * Setup basic session variables (Type hinting below to avoid IDE error messages)
         * @var $templates League\Plates\Engine
         * @var $data array
         * @var $config array
         * @var $user User
         * @var $mysqli mysqli
         * @var $session Session
         */
        extract(self::setup($sessionIdentifier));

        $sessionID = DatabaseSessionIdentifier::loadSessionID($sessionIdentifier, $mysqli);

        // Get question whilst ensuring permissions are kept
        $question = self::setupQuestion($sessionID, $sessionQuestionID, $mysqli);

        // If MCQ question
        if(in_array(get_class($question), ["QuestionMcq", "QuestionMrq"])) {

            // Remove existing choices
            $question->setChoices([]);

            // Load new choices
            foreach ($_POST as $key => $value) {
                if (substr($key, 0, 11) == "mcq-choice-") {
                    $question->addChoice($value);
                }
            }
        }

        // Update question text
        $question->setQuestion($_POST["question"]);

        DatabaseQuestion::update($question, $mysqli);

        header("Location: ..");
        die();
    }

    /**
     * Setup questions whilst ensuring permissions are kept
     * @param int $sessionID
     * @param int $sessionQuestionID
     * @param mysqli $mysqli
     * @return Question|QuestionMcq
     */
    private static function setupQuestion($sessionID, $sessionQuestionID, $mysqli) {

        // If no session question ID, go up a page
        if(!$sessionQuestionID)
            header("Location: ..");

        // Load the question
        $question = DatabaseSessionQuestion::loadQuestion($sessionQuestionID, $mysqli);

        // Display a 404 if the question wasn't loaded or this question doesn't belong to this session
        if(!$question || $sessionID != $question->getSessionID()) {
            PageError::error404();
            die();
        }

        return $question;
    }
}