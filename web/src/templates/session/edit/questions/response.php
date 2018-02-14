<?php
/**
 * @var $config array
 * @var $title string
 * @var $description string
 * @var $breadcrumbs Breadcrumb
 * @var $user User
 * @var $alert Alert
 * @var $responsesMrq array
 * @var $responsesMcq array
 * @var $userMcqResponses array
 * @var $userMrqResponses array
 * @var $responsesWordCloud array
 * @var $responsesText Response[]
 */
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

<?php $this->push("head"); ?>
    <link rel="stylesheet" type="text/css" href="<?=$this->e($config["baseUrl"])?>css/session/edit/question/response.css" />
<?php $this->end(); ?>

<?php $this->push("end"); ?>
    <script src="https://cdnjs.cloudflare.com/ajax/libs/Chart.js/2.7.1/Chart.bundle.js" crossorigin="anonymous"></script>
    <script src="<?=$this->e($config["baseUrl"])?>js/session/edit/question/response.js" crossorigin="anonymous"></script>
    <script src="<?=$this->e($config["baseUrl"])?>js/d3/d3.js" charset="utf-8"></script>
    <script src="<?=$this->e($config["baseUrl"])?>js/d3/d3.layout.cloud.js"></script>
    <script src="<?=$this->e($config["baseUrl"])?>js/d3/d3.wordcloud.js"></script>

    <script>
        <?php if(isset($responsesMcq) || isset($responsesMrq)): ?>
            initBarChartSection();
        <?php elseif(isset($responsesWordCloud)): ?>
            initWordCloudSection('<?=str_replace("'","\'",json_encode($responsesWordCloud))?>');
        <?php endif; ?>
    </script>
<?php $this->stop(); ?>

<div class="page-header">
    <h1 class="row">
        <div class="col-sm-9">
            <h1>Responses</h1>
        </div>
        <div class="col-sm-3">
            <a href="<?=$config["baseUrl"]?>session/<?=$this->e($session->getSessionIdentifier())?>/edit/" class="btn btn-light btn-light-border pull-right width-xs-full">Edit Session</a>
        </div>
    </h1>
</div>

<ul class="nav nav-tabs">
    <?php if(isset($responsesMcq) || isset($responsesMrq)): ?>
        <li class="nav-item" id="nav-bar-chart">
            <a class="nav-link active" href="#">Bar Chart</a>
        </li>
        <li class="nav-item" id="nav-pie-chart">
            <a class="nav-link" href="#">Pie Chart</a>
        </li>
        <li class="nav-item" id="nav-responses">
            <a class="nav-link" href="#">Responses</a>
        </li>
    <?php endif; ?>
    <?php if(isset($responsesWordCloud)): ?>
        <li class="nav-item" id="nav-word-cloud">
            <a class="nav-link" href="#">Word Cloud</a>
        </li>
        <li class="nav-item" id="nav-responses">
            <a class="nav-link" href="#">Responses</a>
        </li>
    <?php endif; ?>
</ul>

<?php if(isset($responsesMcq) || isset($responsesMrq)): ?>
    <div id="section-bar-chart" class="section">
        <canvas id="bar-chart" width="400" height="200"></canvas>
    </div>
    <div id="section-pie-chart" class="section">
        <canvas id="pie-chart" width="400" height="200"></canvas>
    </div>
<?php endif; ?>

<?php // The word cloud bit ?>
<?php if(isset($responsesWordCloud)): ?>
    <div id="section-word-cloud" class="section">
        <div id="wordcloud"></div>
    </div>
<?php endif; ?>
<?php if(isset($responsesText) || isset($userMcqResponses) || isset($userMrqResponses)): ?>
    <div id="section-responses" class="section">
        <button id="display-personal" class="btn btn-primary width-xs-full">Display Personal Information</button>
        <button id="hide-personal" class="btn btn-primary width-xs-full">Hide Personal Information</button>
        <table class="table table-bordered">
            <thead>
            <tr>
                <th class="username" scope="col">Username</th>
                <th class="fullname" scope="col">Full Name</th>
                <th scope="col">Time</th>
                <th scope="col">Response</th>
            </tr>
            </thead>
            <tbody>
            <?php if(isset($responsesText)): ?>
                <?php foreach($responsesText as $response): ?>
                    <tr>
                        <td class="username">
                            <?=$response->getUser()->isGuest() ? "Guest" : $this->e($response->getUser()->getUsername())?>
                        </td>
                        <td class="fullname">
                            <?=$this->e($response->getUser()->getFullName())?>
                        </td>
                        <td><?=date($config["datetime"]["datetime"]["long"], $response->getTime())?></td>
                        <td><?=$this->e($response->getResponse())?></td>
                    </tr>
                <?php endforeach; ?>
            <?php elseif(isset($userMcqResponses)): ?>
                <?php foreach($userMcqResponses as $response): ?>
                    <tr>
                        <td class="username">
                            <?=$response->getUser()->isGuest() ? "Guest" : $this->e($response->getUser()->getUsername())?>
                        </td>
                        <td class="fullname">
                            <?=$this->e($response->getUser()->getFullName())?>
                        </td>
                        <td><?=date($config["datetime"]["datetime"]["long"], $response->getTime())?></td>
                        <td><?=$this->e($response->getResponse())?></td>
                    </tr>
                <?php endforeach; ?>
            <?php elseif(isset($userMrqResponses)): ?>
                <?php foreach($userMrqResponses as $response): ?>
                    <tr>
                        <td class="username">
                            <?=$response->getUser()->isGuest() ? "Guest" : $this->e($response->getUser()->getUsername())?>
                        </td>
                        <td class="fullname">
                            <?=$this->e($response->getUser()->getFullName())?>
                        </td>
                        <td><?=date($config["datetime"]["datetime"]["long"], $response->getTime())?></td>
                        <td><?=$this->e($response->getResponse())?></td>
                    </tr>
                <?php endforeach; ?>
            <?php endif; ?>
            </tbody>
        </table>
    </div>
<?php endif; ?>

<script>
    <?php if(isset($responsesMcq)): ?>
        var labels = [
            <?php foreach($responsesMcq as $response): ?>
                "<?=$this->e($response["choice"])?>",
            <?php endforeach; ?>
        ];

        var data = [
            <?php foreach($responsesMcq as $response): ?>
                <?=$response["count"]?$this->e($response["count"]):0?>,
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
    <?php if(isset($responsesMrq)): ?>
    var labels = [
        <?php foreach($responsesMrq as $response): ?>
        "<?=$this->e($response["choice"])?>",
        <?php endforeach; ?>
    ];

    var data = [
        <?php foreach($responsesMrq as $response): ?>
        <?=$response["count"]?$this->e($response["count"]):0?>,
        <?php endforeach; ?>
    ];

    var backgroundColor = [
        <?php for($i = 0; $i < count($responsesMrq); $i++): ?>
        '<?=getColour($backgroundColours, $i)?>',
        <?php endfor; ?>
    ];
    var borderColor = [
        <?php for($i = 0; $i < count($responsesMrq); $i++): ?>
        '<?=getColour($borderColours, $i)?>',
        <?php endfor; ?>
    ];
    <?php endif; ?>
</script>