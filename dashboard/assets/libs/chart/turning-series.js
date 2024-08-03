function convertToLocalTime(timestamp) {
    var date = new Date(timestamp);
    // Konversi ke waktu lokal
    return date.getTime();
}

$(function() {
    console.log("Starting AJAX request to get data...");

    $.ajax({
        url: 'proses-sensor.php',
        method: 'GET',
        dataType: 'json',
        success: function(datasets) {
            console.log("Data received from server:", datasets);
            
            var i = 0;
            $.each(datasets, function(key, val) {
                val.color = i;
                ++i;
            });

            var choiceContainer = $("#choices");
            $.each(datasets, function(key, val) {
                choiceContainer.append('<input type="checkbox" name="' + key +
                    '" checked="checked" id="id' + key + '">' +
                    '<label for="id' + key + '">' +
                    val.label + '</label>');
            });
            choiceContainer.find("input").click(plotAccordingToChoices);

            function plotAccordingToChoices() {
                var data = [];
                console.log("Plotting data...");

                choiceContainer.find("input:checked").each(function() {
                    var key = $(this).attr("name");
                    if (key && datasets[key]) {
                        data.push(datasets[key]);
                    }
                });

                if (data.length > 0) {
                    console.log("Data to be plotted:", data);
                    $.plot($("#placeholder"), data, {
                        yaxis: { min: 0 },
                        xaxis: {
                            mode: "time",
                            timeformat: "%Y-%m-%d",
                            tickSize: [1, "day"]
                            // timeformat: "%H:%M",
                            // tickSize: [1, "hour"]
                            // timeformat: "%H:%M:%S",
                            // tickSize: [1, "minute"]
                        }
                    });
                } else {
                    console.log("No data selected for plotting.");
                }
            }

            plotAccordingToChoices();
        },
        error: function(xhr, status, error) {
            console.error("Error loading data: " + error);
        }
    });
});
