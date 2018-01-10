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

        // Load the question from the database
        $question = DatabaseSessionQuestion::loadQuestion($sessionQuestionID, $mysqli);

        if(!$question || $sessionID!=$question->getSessionID()) {
            header("Location: ..");
            die();
        }

        $responsesMcq = null;
        $responsesText = null;

        if(in_array($question->getType(), array("text", "textlong"))) {
            $responsesWordCloud = DatabaseResponse::loadWordcloud($sessionQuestionID, $mysqli);
            $responsesText = DatabaseResponse::loadResponses($sessionQuestionID, $mysqli);
        }

        elseif($question->getType() == "mcq") {
            $responsesMcq = DatabaseResponseMcq::loadChoicesTotal($sessionQuestionID, $mysqli);
        }

        // Setup Page breadcrumbs
        $breadcrumbs = new Breadcrumb();
        $breadcrumbs->addItem($config["title"], $config["baseUrl"]);
        $breadcrumbs->addItem("Sessions", $config["baseUrl"]."session/");
        $breadcrumbs->addItem($sessionID, $config["baseUrl"]."session/$sessionID/");
        $breadcrumbs->addItem("Run", $config["baseUrl"]."session/$sessionID/run/");
        $breadcrumbs->addItem("Questions", $config["baseUrl"]."session/$sessionID/run/question/");
        $breadcrumbs->addItem("Question", $config["baseUrl"]."session/$sessionID/run/question/$sessionQuestionID/");
        $breadcrumbs->addItem("Responses");

        $data["breadcrumbs"] = $breadcrumbs;
        $data["user"] = $user;
        $data["responsesMcq"] = $responsesMcq;
        $data["responsesWordCloud"] = $responsesWordCloud;
        $data["responsesText"] = $responsesText;
        echo $templates->render("session/run/questions/response", $data);
    }
}