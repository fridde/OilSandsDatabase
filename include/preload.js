function SelectDeselect() {
	if ($('#chkSelectDeselectAll').checked) {
		$("INPUT[type='checkbox']").attr('checked', true);
	} else {
		$("INPUT[type='checkbox']:not(#chkSelectDeselectAll)").attr('checked', false);
	}
}

function sendToPage(page, formId) {
	form = document.getElementById(formId);
	form.target = '_blank';
	form.action = page;
	form.submit();
}


$(document).ready(function() {

	$(".showbutton").on("change", function() {
		$(".mainComp").show();
	});

	$(':radio:not(".showbutton"):not(".mainComp")').on("change", function() {
		$(".mainComp").hide();
	});

	$("#sortable").dataTable({
		"bPaginate" : false
	});

	var compilationIdArray = [];
	$("#compilation li").each(function() {
		compilationIdArray.push($(this).text());
	});

	var compilationIdList = "compilationId[]=" + compilationIdArray.join("&compilationId[]=");

	var shortNameIdArray = [];
	$("#shortName li").each(function() {
		shortNameIdArray.push($(this).text());
	});
	var plotType = $("#plotType").text();

	var shortNameIdList = "shortNameId[]=" + shortNameIdArray.join("&shortNameId[]=");
	var postdata = compilationIdList + "&" + shortNameIdList + "&plotType=" + plotType;

	$.ajax({
		url : 'get_graphs.php',
		type : "POST",
		data : postdata,
		//dataType : 'text',
		success : function(bigArray) {

			var data = [];

			$.each(bigArray, function(index, value) {
				if (index == "plotType") {
					plotType = value;
				} else {
					data.push({
						label : index,
						data : value
					});
				}
			});

			if (plotType == "stacked") {
				var stacked = true;
				var filled = true;
				var shadowed = false;
			} else {
				var stacked = false;
				var filled = false;
				var shadowed = true;
			}

			var graphoptions = {

				series : {
					stack : stacked,
					lines : {
						show : true,
						fill : filled,
						shadow : shadowed
					}
				},
				xaxis : {
					ticks : 5,
					mode : "time",
					timeformat : "%b %Y",
					autoscaleMargin : 0.05
				},
				yaxis : {
					tickDecimals : 1,
					ticks : 4,
					tickFormatter : function suffixFormatter(val, axis) {
						if (val >= 1000000)
							return (val / 1000000).toFixed(axis.tickDecimals) + " million  -";
						else
							return val.toFixed(axis.tickDecimals) + "    -";
					}
				},
				selection : {
					mode : "x"
				},
				legend : {
					container : $("#labeler")
				},
				imageClassName : "canvas-image",
				imageFormat : "png"

			};

			var overviewoptions = {
				series : {
					lines : {
						show : true,
						lineWidth : 1
					},
					shadowSize : 0
				},
				xaxis : {
					ticks : [],
					mode : "time"
				},
				yaxis : {
					ticks : [],
					min : 0,
					autoscaleMargin : 0.1
				},
				selection : {
					mode : "x"
				},
				legend : {
					show : false,
					position : "nw",
				}
			};

			// hard-code color indices to prevent them from shifting as
			// series are turned on/off
			var i = 0;
			$.each(data, function(key, val) {
				val.color = i; ++i;

			});

			// insert checkboxes
			var choiceContainer = $("#choices");
			$.each(data, function(key, val) {
				choiceContainer.append('<br/><input type="checkbox" name="' + key + '" checked="checked" id="id' + key + '">' + '<label for="id' + key + '">' + val.label + '</label>');
			});
			choiceContainer.find("input").click(plotAccordingToChoices);

			function plotAccordingToChoices() {
				var newdata = [];

				choiceContainer.find("input:checked").each(function() {
					var key = $(this).attr("name");
					if (key && data[key])
						newdata.push(data[key]);
				});

				if (newdata.length > 0)
					$.plot($("#graph"), newdata, graphoptions);
				//$("#graph").UseTooltip();
			}

			// $("#graph").text(data);
			plotAccordingToChoices();

			//var plot = $.plot($("#graph"), data, graphoptions);
			var overview = $.plot("#overview", data, overviewoptions);

			$("#graph").bind("plotselected", function(event, ranges) {

				// do the zooming

				plot = $.plot("#graph", data, $.extend(true, {}, graphoptions, {
					xaxis : {
						min : ranges.xaxis.from,
						max : ranges.xaxis.to
					}
				}));

				// don't fire event on the overview to prevent eternal loop

				overview.setSelection(ranges, true);
			});

			$("#overview").bind("plotselected", function(event, ranges) {
				plot.setSelection(ranges);
			});

		}
	});

	$("#graph").click(function() {
		var content = "content=" + $("graph").html();
		$("body").css("background-color: black;");
		$.ajax({
			url : 'draw_graphs.php',
			type : "POST",
			data : content
			//dataType : 'text',
			//success : function() {

		});
	});

//  end flot graph functions 


	$(".buttonWithDescription").mouseenter(function() {
		$("#buttonExplanation").show();
		var id = $(this).attr("value");
		if ($("#" + id).text() != "") {
			$("#buttonExplanation").html("<h3>" + id + "</h3>" + $("#" + id).text());
		}
	});

	// $( ".accordion" ).accordion();
	$("#tabs").tabs();

	$(".tCheck").on("change", function() {
		var tableId = '.table_' + $(this).val();

		$(tableId).toggle();
	});

//  start map functions

var postdata;

$.ajax({
		url : 'get_map_data.php',
		type : "POST",
		data : postdata,
		//dataType : 'text',
		success : function(bigArray) {

			var projects = [];

			$.each(bigArray, function(index, row) {
					projects[index] = {
						center: new google.maps.LatLng(row["lon"], row["lat"]),
						production : row["Production"]
					};
			});
		}
});

	function initialize() {
		var mapOptions = {
			center : new google.maps.LatLng(57.010599, -111.570282),
			zoom : 7,
			mapTypeId: google.maps.MapTypeId.TERRAIN
		};
		
		var map = new google.maps.Map(document.getElementById("map"), mapOptions);
	};

  // Construct the circle for each value in citymap.
  // Note: We scale the population by a factor of 20.
  for (var project in projects) {
    var projectOptions = {
      strokeColor: '#FF0000',
      strokeOpacity: 0.8,
      strokeWeight: 2,
      fillColor: '#FF0000',
      fillOpacity: 0.35,
      map: map,
      center: projects[project].center,
      radius: projects[project].production / 20
    };
    // Add the circle for this city to the map.
    cityCircle = new google.maps.Circle(populationOptions);
  };
// end circle drawing

	google.maps.event.addDomListener(window, 'load', initialize);

// end map functions

//end document ready
});

