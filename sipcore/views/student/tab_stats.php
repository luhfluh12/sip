
<div id="placeholder" style="width: 900px; height: 400px; position: relative;"></div>
<div id="legend" style="float:right;width:200px;border:1px solid #999;padding:1px;"></div>
<div id="choices"></div>
<div class="clearfix"></div>

<script type="text/javascript">
$(function () {
    var datasets = <?php echo $json; ?>;

    // hard-code color indices to prevent them from shifting as
    // countries are turned on/off
    var i = 0;
    $.each(datasets, function(key, val) {
        val.color = i;
        ++i;
    });
    
    // insert checkboxes 
    var choiceContainer = $("#choices");
    $.each(datasets, function(key, val) {
        choiceContainer.append('<br/><input type="checkbox" name="' + key +
                               '" checked="checked" id="id' + key + '">' +
                               '<label for="id' + key + '">'
                                + val.label + '</label>');
    });
    choiceContainer.find("input").click(plotAccordingToChoices);

    
    function plotAccordingToChoices() {
        var data = [];

        choiceContainer.find("input:checked").each(function () {
            var key = $(this).attr("name");
            if (key && datasets[key])
                data.push(datasets[key]);
        });

        if (data.length > 0)
            $.plot($("#placeholder"), data, {
                yaxis: {},
                xaxis: { mode: "time", timeformat: "%d/%m/%y" },
                legend:{container:$("#legend")}
            });
    }

    plotAccordingToChoices();
});
</script>