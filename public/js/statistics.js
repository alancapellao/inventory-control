$(function () {

    // Função para receber dados dos produtos e gerar gráficos
    $.ajax({
        url: '/statistic',
        method: 'GET',
        success: function (data) {
            const sales = (parseInt(data.sale["Furniture"] || 0) + parseInt(data.sale["Decoration"] || 0) + parseInt(data.sale["Kitchen"] || 0) + parseInt(data.sale["Bathroom"] || 0));
            const stocks = (parseInt(data.stock["Furniture"] || 0) + parseInt(data.stock["Decoration"] || 0) + parseInt(data.stock["Kitchen"] || 0) + parseInt(data.stock["Bathroom"] || 0));

            $("#sale").text(sales);
            $("#stock").text(stocks);
            $("#active").text(data.active);
            $("#disabled").text(data.disabled);

            var ctx4 = $("#bar-chart").get(0).getContext("2d");
            var myChart4 = new Chart(ctx4, {
                type: "bar",
                data: {
                    labels: ["Furniture", "Decoration", "Kitchen", "Bathroom"],
                    datasets: [{
                        label: 'Sales',
                        backgroundColor: [
                            "#1F77B4",
                            "#1E90FF",
                            "#00BFFF",
                            "#2869ff"
                        ],
                        data: [
                            data.sale["Furniture"] || 0,
                            data.sale["Decoration"] || 0,
                            data.sale["Kitchen"] || 0,
                            data.sale["Bathroom"] || 0
                        ]
                    }]
                },
                options: {
                    responsive: true
                }
            });


            var ctx5 = $("#pie-chart").get(0).getContext("2d");
            var myChart5 = new Chart(ctx5, {
                type: "pie",
                data: {
                    labels: ["Furniture", "Decoration", "Kitchen", "Bathroom"],
                    datasets: [{
                        backgroundColor: [
                            "#1F77B4",
                            "#1E90FF",
                            "#00BFFF",
                            "#2869ff"
                        ],
                        data: [
                            data.stock["Furniture"] || 0,
                            data.stock["Decoration"] || 0,
                            data.stock["Kitchen"] || 0,
                            data.stock["Bathroom"] || 0
                        ]
                    }]
                },
                options: {
                    responsive: true
                }
            });
        }
    });
});
