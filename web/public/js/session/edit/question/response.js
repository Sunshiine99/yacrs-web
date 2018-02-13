var wordCloudData;

/**
 * When the button to display usernames is clicked
 */
$("#display-personal").click(function() {
    $(".username").css("display", "table-cell");
    $(".fullname").css("display", "table-cell");
    $(this).css("display", "none");
    $("#hide-personal").css("display", "inline")
});

/**
 * When the button to hide usernames is clicked
 */
$("#hide-personal").click(function() {
    $(".username").css("display", "none");
    $(".fullname").css("display", "none");
    $(this).css("display", "none");
    $("#display-personal").css("display", "inline")
});

/**
 * Runs when a nav item is clicked.
 * @param event
 */
function navClick(event) {
    if(!$(event.data.that).find("a").hasClass("active")) {
        event.data.callback();
    }
}

$("#nav-bar-chart").click({"that": this, "callback": initBarChartSection}, navClick);
$("#nav-pie-chart").click({"that": this, "callback": initPieChartSection}, navClick);
$("#nav-word-cloud").click({"that": this, "callback": initWordCloudSection}, navClick);
$("#nav-responses").click({"that": this, "callback": initResponsesSection}, navClick);

function initSection(sectionId) {
    $("ul.nav-tabs li.nav-item a.nav-link.active").removeClass("active");
    $("ul.nav-tabs li.nav-item#nav-"+sectionId+" a.nav-link").addClass("active");

    $(".section").css("display", "none");
    $("#section-"+sectionId).css("display", "block");
}

function initBarChartSection() {
    initSection("bar-chart");
    initBarChart("bar-chart", labels, data, backgroundColor, borderColor);
}

function initPieChartSection() {
    initSection("pie-chart");
    initPieChart("pie-chart", labels, data, backgroundColor, borderColor);
}

function initWordCloudSection(json) {
    initSection("word-cloud");
    wordCloudData = JSON.parse(json);
    initWordCloud();
}

function initResponsesSection() {
    initSection("responses");
}

function initBarChart(id, labels, data, backgroundColor, borderColor) {
    var ctx = document.getElementById(id).getContext('2d');
    var myChart = new Chart(ctx, {
        type: 'bar',
        data: {
            labels: labels,
            datasets: [{
                label: 'Number of Votes',
                data: data,
                backgroundColor: backgroundColor,
                borderColor: borderColor,
                borderWidth: 1
            }]
        },
        options: {
            scales: {
                yAxes: [{
                    ticks: {
                        beginAtZero:true
                    }
                }]
            }
        }
    });
}

function initPieChart(id, labels, data, backgroundColor, borderColor) {
    var ctx = document.getElementById(id).getContext('2d');
    var myChart = new Chart(ctx, {
        type: 'pie',
        data: {
            labels: labels,
            datasets: [{
                label: 'Number of Votes',
                data: data,
                backgroundColor: backgroundColor,
                borderColor: borderColor,
                borderWidth: 1
            }]
        }
    });
}

function initWordCloud() {
    var data = wordCloudData;
    var id = "wordcloud";

    d3.wordcloud()
        .size([$("main .container").width(), 600])
        .fill(d3.scale.ordinal().range(["#884400", "#448800", "#888800", "#444400"]))
        .words(data)
        .onwordclick(function (d, i) {
            //if (d.href) { window.location = d.href; }
            if (d.alert) {
                //alert(d.alert);
                //initWordCloud(id, data);
            }
        })
        .start();
}