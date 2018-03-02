<?php
/**
 * @var $config array
 * @var $title string
 * @var $description string
 * @var $user User
 * @var $alert Alert
 */
$this->layout("template",
    [
        "config" => $config,
        "description" => $description,
        "user" => $user,
        "alert" => $alert
    ]
);
?>
<button id="screenshot">Screenshot</button>
<img id="display" src="" />

<?php $this->push("end"); ?>
    <script>
        $("#screenshot").click(function() {
            takeScreenshot(function(base64data) {
                $("#display").attr("src", base64data);
            });
        });
    </script>
<?php $this->stop(); ?>