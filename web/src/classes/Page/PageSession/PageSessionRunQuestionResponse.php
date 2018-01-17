<?php

class PageSessionRunQuestionResponse
{

    public static function response($sessionIdentifier, $sessionQuestionID) {
        $templates = Flight::get("templates");
        $data = Flight::get("data");
        $config = Flight::get("config");

        // Ensure the user is logged in
        $user = Page::ensureUserLoggedIn($config);

        // Connect to database
        $databaseConnect = Flight::get("databaseConnect");
        $mysqli = $databaseConnect();

        $sessionID = DatabaseSessionIdentifier::loadSessionID($sessionIdentifier, $mysqli);

        // If invalid session identifier, display 404
        if(!$sessionID) {
            PageError::error404();
            die();
        }

        // Load the question from the database
        $question = DatabaseSessionQuestion::loadQuestion($sessionQuestionID, $mysqli);

        if(!$question || $sessionID!=$question->getSessionID()) {
            header("Location: ..");
            die();
        }

        $responsesMcq = null;
        $responsesText = null;
        $responseMrq = null;

        if(in_array($question->getType(), array("text", "textlong"))) {
            $responsesWordCloud = DatabaseResponse::loadWordcloud($sessionQuestionID, $mysqli);
            $responsesText = DatabaseResponse::loadResponses($sessionQuestionID, $mysqli);
        }

        elseif($question->getType() == "mcq") {
            $responsesMcq = DatabaseResponseMcq::loadChoicesTotal($sessionQuestionID, $mysqli);
            $userMcqResponses = DatabaseResponse::loadMcqResponses($sessionQuestionID, $mysqli);
        }

        elseif($question->getType() == "mrq") {
            $responsesMrq = DatabaseResponseMcq::loadChoicesTotal($sessionQuestionID, $mysqli);
        }

        // Setup Page breadcrumbs
        $breadcrumbs = new Breadcrumb();
        $breadcrumbs->addItem($config["title"], $config["baseUrl"]);
        $breadcrumbs->addItem("Sessions", $config["baseUrl"]."session/");
        $breadcrumbs->addItem($sessionIdentifier, $config["baseUrl"]."session/$sessionIdentifier/");
        $breadcrumbs->addItem("Run", $config["baseUrl"]."session/$sessionIdentifier/run/");
        $breadcrumbs->addItem("Questions", $config["baseUrl"]."session/$sessionIdentifier/run/question/");
        $breadcrumbs->addItem("Question", $config["baseUrl"]."session/$sessionIdentifier/run/question/$sessionQuestionID/");
        $breadcrumbs->addItem("Responses");

        $data["userMcqResponses"] = $userMcqResponses;
        $data["responsesMrq"] = $responsesMrq;
        $data["breadcrumbs"] = $breadcrumbs;
        $data["user"] = $user;
        $data["responsesMcq"] = $responsesMcq;
        $data["responsesWordCloud"] = $responsesWordCloud;
        $data["responsesText"] = $responsesText;
        echo $templates->render("session/run/questions/response", $data);
    }
}