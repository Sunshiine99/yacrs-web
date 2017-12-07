<?php
$this->layout("template",
    [
        "config" => $config,
        "title" => $title,
        "description" => $description,
        "breadcrumbs" => $breadcrumbs,
        "user" => $user
    ]
);

$backgroundColours = [];
$backgroundColours[] = "rgba(255, 99, 132, 0.2)";
$backgroundColours[] = "rgba(54, 162, 235, 0.2)";
$backgroundColours[] = "rgba(255, 206, 86, 0.2)";
$backgroundColours[] = "rgba(75, 192, 192, 0.2)";
$backgroundColours[] = "rgba(153, 102, 255, 0.2)";
$backgroundColours[] = "rgba(255, 159, 64, 0.2)";

$borderColours = [];
$borderColours[] = "rgba(255,99,132,1)";
$borderColours[] = "rgba(54, 162, 235, 1)";
$borderColours[] = "rgba(255, 206, 86, 1)";
$borderColours[] = "rgba(75, 192, 192, 1)";
$borderColours[] = "rgba(153, 102, 255, 1)";
$borderColours[] = "rgba(255, 159, 64, 1)";

function getColour($colours, $i) {
    return $colours[$i%count($colours)];
}

?>

<?php $this->push("end"); ?>
    <script src="https://cdnjs.cloudflare.com/ajax/libs/Chart.js/2.7.1/Chart.bundle.js" crossorigin="anonymous"></script>
    <script src="<?=$config["baseUrl"]?>js/session/question/response.js" crossorigin="anonymous"></script>
    <script>
        initBarChartSection();
    </script>
<?php $this->stop(); ?>

<h1>Responses</h1>
<ul class="nav nav-tabs">
    <?php if(isset($responsesMcq)): ?>
        <li class="nav-item" id="nav-bar-chart">
            <a class="nav-link active" href="#">Bar Chart</a>
        </li>
        <li class="nav-item" id="nav-pie-chart">
            <a class="nav-link" href="#">Pie Chart</a>
        </li>
    <?php endif; ?>
    <?php if(isset($responsesText)): ?>
        <li class="nav-item" id="nav-word-cloud">
            <a class="nav-link" href="#">Word Cloud</a>
        </li>
    <?php endif; ?>
</ul>

<?php if(isset($responsesMcq)): ?>
    <div id="section-bar-chart" class="section">
        <canvas id="bar-chart" width="400" height="200"></canvas>
    </div>
    <div id="section-pie-chart" class="section">
        <canvas id="pie-chart" width="400" height="200"></canvas>
    </div>
<?php endif; ?>

<style>
    ul.nav-tabs li.nav-item a.nav-link {
        outline: 0;
    }
</style>

<script>
    <?php if(isset($responsesMcq)): ?>
        var labels = [
            <?php foreach($responsesMcq as $response): ?>
                "<?=$response["choice"]?>",
            <?php endforeach; ?>
        ];

        var data = [
            <?php foreach($responsesMcq as $response): ?>
                <?=$response["count"]?$response["count"]:0?>,
            <?php endforeach; ?>
        ];

        var backgroundColor = [
            <?php for($i = 0; $i < count($responsesMcq); $i++): ?>
                '<?=getColour($backgroundColours, $i)?>',
            <?php endfor; ?>
        ];
        var borderColor = [
            <?php for($i = 0; $i < count($responsesMcq); $i++): ?>
                '<?=getColour($borderColours, $i)?>',
            <?php endfor; ?>
        ];
    <?php endif; ?>
</script>