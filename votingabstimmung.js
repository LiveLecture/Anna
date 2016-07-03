var diagramm = document.getElementById('myChart');

datas = [0, 0, 0, 0];

var data = {
    labels: ["a)", "b)", "c)", "d)"],
    datasets: [
        {
            label: "Stimmen",
            backgroundColor: "#991177",
            borderColor: "rgba(255,99,132,1)",
            borderWidth: 1,
            hoverBorderColor: "rgba(255,99,132,1)",
            data: datas
        }
    ]
};

var myBarChart = new Chart(diagramm, {
    type: 'bar',
    data: data,
    options: {
        scales: {
            yAxes: [{
                display: true,
                ticks: {
                    suggestedMax: 5,
                    beginAtZero: true
                }
            }]
        }
    }
});

function update(key) {

    $.ajax({
        url: "aktuellestimmen.php?key=" + key
    }).done(function(datas) {
        datas.trim().split(",").forEach(function(element, index) {
            myBarChart.data.datasets[0].data[index] = element;
        });
        myBarChart.update();
        setTimeout(function(){
            update(key);
        }, 3000); // frage Stand alle 3 Sekunden ab
    });
}
