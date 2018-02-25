<?php
/**
 * @var $config array
 * @var $user User
 * @var $mysqli mysqli
 */
?>
<!DOCTYPE html>
<html lang="en">
    <head>
        <meta charset="utf-8">
        <meta name="viewport" content="width=device-width, initial-scale=1, shrink-to-fit=no">
        <title>Live View</title>
        <meta name="theme-color" content="#003865">

        <link rel="stylesheet" href="<?= $this->e($config["baseUrl"]) ?>css/bootstrap-4.0.0-beta.2.min.css">
        <link href="https://maxcdn.bootstrapcdn.com/font-awesome/4.7.0/css/font-awesome.min.css" rel="stylesheet">
        <style>
            body {
                background-color: <?=isDesktopApp() ? "#003865" : "blue" ?>;
                -webkit-app-region: drag;
                color: white;
                position: absolute;
                left: 0;
                top: 0;
                height: 100%;
                width: 100%;
                user-select: none;
                overflow:hidden;
            }

            button, a:hover{
                color: #ffb949;
            }

            .display-none {
                display: none;
            }

            .not-active {
                pointer-events: none;
                cursor: default;
                color: grey;
                -webkit-app-region: drag;
            }

            button, a, select {
                -webkit-app-region: no-drag;
            }

            a {
                outline: 0;
                color: white;
            }

            a:hover {
                color: #ffb949;
            }

            .view {
                position: absolute;
                left: 0;
                top: 0;
                padding: 10px;
                background-color: #003865;
                min-height: 80px;
                /*min-width: 940px;*/
                min-width: 815px;
            }

            .view.expanded {
                min-width: 925px;
            }

            .view.compact {
                min-width: 425px;
            }

            .view.compact.expanded {
                min-width: 535px;
            }

                .view .logo-container {
                    float: left;
                    margin-right: 20px;
                }

                .view.compact .logo-container {
                    display: none;
                }

                    .view .logo-container img {
                        height: 80px;
                    }

                .view .question-container {
                    width: 320px;
                    height: 80px;
                    position: relative;
                    float: left;
                }

                .view.compact .question-container {
                    display: none;
                }

                    .view .question-container .question {
                        position: absolute;
                        left: 0;
                        top: 0;
                        width: 100%;
                        height: 100%;
                        display: table;
                    }

                        .view .question-container .question p {
                            display: table-cell;
                            vertical-align: middle;
                            text-align: center;
                        }

                .view .button-container {
                    float: left;
                    height: 80px;
                    line-height: 80px;
                    margin-left: 10px;
                    margin-right: 10px;
                    text-align: center;
                }

                .view .button-container.icon {
                    font-size: 35px;
                }

                .view .button-container.nav {
                    font-size: 40px;
                }

                .view .button-container.activate {
                    font-size: 18px;
                    width: 90px;
                }

                .view .button-container.new-question {
                    margin-left: 20px;
                }

                .view .button-container.question-type {}

                    .view .button-container.question-type select {
                        width: 150px;
                    }

                .view .button-container.users {
                    font-size: 22px;
                }

                .view .button-container.power {
                    float: right;
                    font-size: 30px;
                }

            #debug {
                position: absolute;
                top: 200px;
            }

        </style>
    </head>
    <body>
        <div class="view">
            <div class="logo-container">
                <img src="<?=$this->e($config["baseUrl"])?>img/uofg/logo-gu-icon.png"/>
            </div>
            <div class="button-container nav">
                <a id="prev-question" href="#" class="not-active">
                    <i class="fa fa-angle-double-left"></i>
                </a>
            </div>
            <div class="question-container">
                <div class="question">
                    <p id="question-text"></p>
                </div>
            </div>
            <div class="button-container nav">
                <a id="next-question" href="#" class="not-active">
                    <i class="fa fa-angle-double-right"></i>
                </a>
            </div>
            <div class="button-container activate">
                <a id="activate" href="#" class="not-active">
                    <b>Activate</b>
                </a>
                <a id="deactivate" href="#" class="display-none not-active">
                    <b>Deactivate</b>
                </a>
            </div>
            <div class="button-container icon">
                <a id="responses" href="#" class="not-active">
                    <i class="fa fa-bar-chart" aria-hidden="true"></i>
                </a>
            </div>
            <div class="button-container users display-none">
                <span id="users"></span>
            </div>
            <div id="new-question-container" class="button-container icon new-question">
                <a id="new-question" href="#" class="not-active">
                    <i class="fa fa-plus"></i>
                </a>
            </div>
            <div id="question-type-container" class="button-container question-type display-none">
                <select id="question-type">
                    <option value="mcq_d">MCQ A-D</option>
                    <option value="mcq_e">MCQ A-E</option>
                    <option value="mcq_f">MCQ A-F</option>
                    <option value="mcq_g">MCQ A-G</option>
                    <option value="mcq_h">MCQ A-H</option>
                    <option value="mrq_d">MRQ A-D</option>
                    <option value="mrq_e">MRQ A-E</option>
                    <option value="mrq_f">MRQ A-F</option>
                    <option value="mrq_g">MRQ A-G</option>
                    <option value="mrq_h">MRQ A-H</option>
                    <option value="text">Text</option>
                    <option value="textlong">Long Text</option>
                    <option value="truefalse">True/False</option>
                    <option value="truefalsedk">True/False/Don't Know</option>
                </select>
                <button id="new-question-submit" class="btn btn-primary">+</button>
            </div>
            <div class="button-container power">
                <a id="power" href="#">
                    <i class="fa fa-power-off"></i>
                </a>
            </div>
        </div>

        <?php if(!isDesktopApp()): ?>
        <div id="debug">
            <input id="debug-session-identifier" type="text" name="firstname" value="1"><br>
            <input id="debug-session-join" type="submit" value="Join Session">
        </div>
        <?php endif; ?>

        <!-- Bootstrap core JavaScript
        ================================================== -->
        <!-- Placed at the end of the document so the pages load faster -->
        <script src="<?= $this->e($config["baseUrl"]) ?>js/jquery-3.2.1.min.js"></script>
        <script>
            var baseUrl = "<?=$this->e($config["baseUrl"])?>";
            try {
                window.$ = window.jQuery = module.exports;
            }
            catch(e) {}
        </script>
        <script src = "<?=$this->e($config["baseUrl"])?>js/popper.min.js" ></script>
        <script src="<?= $this->e($config["baseUrl"]) ?>js/bootstrap-4.0.0-beta.2.min.js"></script>
        <script src="<?=$this->e($config["baseUrl"])?>js/session/generic-questions.js" crossorigin="anonymous"></script>
        <script src="<?= $this->e($config["baseUrl"]) ?>js/session/live.js"></script>
    </body>
</html>