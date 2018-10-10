function drawCurveTypes(jsonData1, jsonData2) {
	var options = {
			series: {
				0: {pointShape: "circle"},
				1: {pointShape: "triangle"},
				2: {pointShape: "square"},
				3: {pointShape: "diamond"},
				4: {pointShape: "star"},
				5: {pointShape: "polygon"}
			},
			legend: {
				alignment: 'start',
				position: 'bottom'
			},
			pointSize: 7,
			chartArea: {
				left: 30,
				width: 415,
				top: 30
			},
			vAxis: {
				gridlines: {
					count: -1
				},
				viewWindow: {
					min: 0
				}
			},
			hAxis: {
				viewWindow: {
					min: 0
				},
				textStyle: {
					color: '#2d2d2d'
				}
			}
	};
	
	function getMeta(jsonData) {
		var i, iCnt, j, jCnt, v, step,
			min = null, 
			max = null,
			ticks = [0],
			maxGgridlines = 4,
			data = JSON.parse(jsonData);
		for (i = 0, iCnt = data.rows.length; i < iCnt; i += 1) {
			for (j = 1, jCnt = data.rows[i].c.length; j < jCnt; j += 1) {
				v = parseInt(data.rows[i].c[j].v, 10);
				if (min === null || v < min) {
					min = v;
				}
				if (max === null || v > max) {
					max = v;
				}
			}
		}
		
		if (min === null) {
			min = 0;
		}
		if (max === null) {
			max = 0;
		}
		
		step = Math.floor(max / maxGgridlines);
		
		iCnt = max;
		if (step <= 0) {
			step = 1;
		}
		for (i = 0; i < iCnt; i += step) {
			ticks.push( i + step );
		}
		
		if (ticks.length <= maxGgridlines) {
			for (i = ticks.length; i <= maxGgridlines; i += 1) {
				ticks.push(i);
			}
		}
		
		return {
			"min": min, 
			"max": max,
			"gridlinesCount": ticks.length,
			"ticks": ticks
		};
	}
	
	var handler = function(e, data) {
		var m = e.targetID.match(/hAxis#0#label#(\d+)/);
		if (m !== null) {
			window.location.href = 'index.php?controller=pjAdminBookings&action=pjActionCalendar&format=Y-m-d&selected_date=' + data.getValue(parseInt(m[1], 10), 0);
		}
	};
	
	var o, data1, data2, chart1, chart2;
	
	// Chart 1
	o = getMeta(jsonData1);
	options.vAxis.gridlines.count = o.gridlinesCount;
	options.vAxis.ticks = o.ticks;

	data1 = new google.visualization.DataTable(jsonData1);
	
	chart1 = new google.visualization.LineChart(document.getElementById('chart-1'));
	chart1.draw(data1, options);
	
	google.visualization.events.addListener(chart1, 'click', function (e) {
		handler.call(null, e, data1);
	});
	
	// Chart 2
	o = getMeta(jsonData2);
	options.vAxis.gridlines.count = o.gridlinesCount;
	options.vAxis.ticks = o.ticks;

	data2 = new google.visualization.DataTable(jsonData2);
	
	chart2 = new google.visualization.LineChart(document.getElementById('chart-2'));
	chart2.draw(data2, options);
	
	google.visualization.events.addListener(chart2, 'click', function (e) {
		handler.call(null, e, data2);
	});
	
	jQuery("#chart-2").appendTo("#charts-2");
}

google.load('visualization', '1', {packages: ['corechart', 'line']});

(function ($, undefined) {
	$(function () {
		"use strict";
	
		var tabs = ($.fn.tabs !== undefined),
			$charts = $("#charts");
		
		if ($charts.length > 0 && tabs) {
			$charts.tabs({
				disabled: true
			});
		}
		if(myLabel.room_avail == 'true')
		{
			$.when($.get('index.php?controller=pjAdmin&action=pjActionChartGet&type=1'), $.get('index.php?controller=pjAdmin&action=pjActionChartGet&type=2')).done(function (a1, a2) {
				google.setOnLoadCallback(drawCurveTypes(a1[2].responseText, a2[2].responseText));
				
				$charts.tabs("enable");
			});
			
			window.setTimeout(function () {
				window.location.reload();
			}, 1000 * 60 * 2);
		}else{
			$charts.tabs("enable");
		}
	});
})(jQuery);