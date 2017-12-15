<!DOCTYPE html>
<html>
    <head>
        <style>

            .simplechart.bar {
                font-family: sans-serif;
            }
                .simplechart.bar table tbody tr td.bar-container {
                    background-color: yellow;
                    width: 100px;
                    height: 300px;
                    border: none;
                    position: relative;
                }

                .simplechart.bar table tbody tr td.y-axis {
                    width: 20px;
                }

                    .simplechart.bar table tbody tr td.y-axis table {
                        height: 100%;
                    }

                        .simplechart.bar table tbody tr td.y-axis table td {
                            vertical-align: top;
                        }

                    .simplechart.bar table tbody tr td div.bar {
                        position: absolute;
                        background-color: blue;
                        bottom: 0;
                        left: 0;
                        width: 100%;
                        color: #fff;
                        text-align: center;
                    }
        </style>
    </head>

    <?php
    $data = [];
    $data[] = 12;
    $data[] = 21;
    $data[] = 13;
    $data[] = 6;

    $label = [];
    $label[] = "A";
    $label[] = "B";
    $label[] = "C";
    $label[] = "D";

    $sum = 0;
    foreach($data as $d) {
        $sum += $d;
    }

    $ydivisor = 6;
    while($sum%$ydivisor != 0 && $ydivisor > 0) {
        $ydivisor--;
    }

    ?>

    <body>
        <div id="chart" class="simplechart bar">
            <table cellspacing="0">
                <tbody>
                    <tr>
                        <td class="y-axis">
                            <table cellspacing="0">
                                <tbody>
                                    <?php for($i=0; $i<$ydivisor; $i++): ?>
                                    <tr>
                                        <td>
                                            <?=$sum - ($i * $sum / $ydivisor)?>
                                        </td>
                                    </tr>
                                    <?php endfor; ?>
                                </tbody>
                            </table>
                        </td>
                        <?php foreach($data as $d): ?>
                            <?php $percent = round(($d/$sum)*100,4); ?>
                            <td class="bar-container">
                                <div style="height: <?=$percent?>%" class="bar">
                                    <?=$d?>
                                </div>
                            </td>
                        <?php endforeach; ?>
                    </tr>
                    <tr>
                        <td></td>
                        <?php foreach($label as $l): ?>
                            <td>
                                <?=$l?>
                            </td>
                        <?php endforeach; ?>
                    </tr>
                </tbody>
            </table>
        </div>
    </body>
</html>