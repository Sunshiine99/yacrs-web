$("#nav-bar-chart").click(function() {
    if(!$(this).find("a").hasClass("active")) {
        initBarChartSection();
    }
});

$("#nav-pie-chart").click(function() {
    if(!$(this).find("a").hasClass("active")) {
        initPieChartSection();
    }
});

function initBarChartSection() {
    $("ul.nav-tabs li.nav-item a.nav-link.active").removeClass("active");
    $("ul.nav-tabs li.nav-item#nav-bar-chart a.nav-link").addClass("active");

    $(".section").css("display", "none");
    $("#section-bar-chart").css("display", "block");
    initBarChart("bar-chart", labels, data, backgroundColor, borderColor);
}

function initPieChartSection() {
    $("ul.nav-tabs li.nav-item a.nav-link.active").removeClass("active");
    $("ul.nav-tabs li.nav-item#nav-pie-chart a.nav-link").addClass("active");

    $(".section").css("display", "none");
    $("#section-pie-chart").css("display", "block");
    initPieChart("pie-chart", labels, data, backgroundColor, borderColor);
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