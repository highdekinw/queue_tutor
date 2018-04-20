var course;
function numWithComma(x) {
    return x.toString().replace(/\B(?=(\d{3})+(?!\d))/g, ",");
}
$.post("backend/ajax_dashboard.php",{type:1},function(res){
	var data = JSON.parse(res);
	var seatCount = [],income = [];
	data.forEach(function(o,i){
		var temp = [];
		temp['label'] = o['term']+"/"+o['year'];
		temp['y'] = Number(o['seatCount']);
		temp['indexLabel'] = o['seatCount']+" ที่นั่ง";
		seatCount.push(temp);

		var temp = [];
		temp['label'] = o['term']+"/"+o['year'];
		temp['y'] = Number(o['income'])-Number(o['existing'])*200;
		temp['indexLabel'] = numWithComma(Number(o['income'])-Number(o['existing'])*200)+" บาท";
		income.push(temp);
	});
	var seatCountChart = new CanvasJS.Chart("seat-count-box", {
			title:{
				text: "การลงทะเบียน"
			},
			animationEnabled: true,
			backgroundColor: "transparent",
			theme: "theme1",
			toolTip: {
				borderThickness: 0,
				cornerRadius: 0,
				content: "<span style='\"'color: {color};'\"'>{label}</span>: {y} ที่นั่ง"
			},
			axisX: {
				labelFontSize: 14,
			},
			axisY: {
				gridThickness: 0,
				labelFontSize: 14,
				lineThickness: 2
			},
			data: [
				{
					type: "line",
					lineThickness: 2,
					color: "#F08080",
					dataPoints: seatCount
				}
			]
		});
	seatCountChart.render();
	var incomeChart = new CanvasJS.Chart("income-box", {
			title:{
				text: "รายได้"
			},
			animationEnabled: true,
			backgroundColor: "transparent",
			theme: "theme1",
			toolTip: {
				borderThickness: 0,
				cornerRadius: 0,
				content: "<span style='\"'color: {color};'\"'>{label}</span>: {y} บาท"
			},
			axisX: {
				labelFontSize: 14,
			},
			axisY: {
				gridThickness: 0,
				labelFontSize: 14,
				lineThickness: 2
			},
			data: [
				{
					type: "line",
					lineThickness: 2,
					color: "#F08080",
					dataPoints: income
				}
			]
		});
	incomeChart.render();
});
$.post("backend/ajax_dashboard.php",{type:2},function(res){
	var data = JSON.parse(res);
	course = JSON.parse(res);
	var mostReserve = [], mostRevenue = [];
	dataReserve = data.sort(function(a,b){
		return Number(b['seatCount'])-Number(a['seatCount']);
	});
	dataReserve.forEach(function(o,i){
		if(i < 10){
			var temp = [];
			temp['y'] = Number(o['seatCount']);
			temp['label'] = o['name'];
			temp['name'] = o['name']+" "+o['term']+"/"+o['year'];
			temp['indexLabel'] = numWithComma(Number(o['seatCount']))+" ที่นั่ง";
			mostReserve.push(temp);
		}
	});
	dataRevenue = data.sort(function(a,b){
		return (Number(b['income'])-Number(b['existing'])*200)-(Number(a['income'])-Number(a['existing'])*200);
	});
	dataRevenue.forEach(function(o,i){
		if(i < 10){
			var temp = [];
			temp['y'] = Number(o['income'])-Number(o['existing'])*200;
			temp['label'] =o['name'];
			temp['name'] = o['name']+" "+o['term']+"/"+o['year'];
			temp['indexLabel'] = numWithComma(Number(o['income'])-Number(o['existing'])*200)+"";
			mostRevenue.push(temp);
		}
	});
	var mostRevenueChart = new CanvasJS.Chart("most-revenue-course", {
			animationEnabled: true,
			theme: "theme1",
			toolTip: {
				borderThickness: 0,
				content: "<span style='\"'color: {color};'\"'>{name}</span>: {y} บาท",
				cornerRadius: 0
			},
			axisX:{
				labelFontSize: 10,
				interval: 1
			},
			data: [
				{       
					indexLabelFontColor: "#676464",
					indexLabelFontSize: 8,
					type: "column",
					dataPoints: mostRevenue
				}
			]
		});
	mostRevenueChart.render();
	var mostReserveChart = new CanvasJS.Chart("most-reserve-course", {
			animationEnabled: true,
			theme: "theme1",
			toolTip: {
				borderThickness: 0,
				content: "<span style='\"'color: {color};'\"'>{name}</span>: {y} ที่นั่ง",
				cornerRadius: 0
			},
			axisX:{
				labelFontSize: 10,
				interval: 1
			},
			data: [
				{       
					indexLabelFontColor: "#676464",
					indexLabelFontSize: 8,
					type: "column",
					dataPoints: mostReserve
				}
			]
		});
	mostReserveChart.render();
	$("#term-select").change();
});
$(function(){
	$("#term-select").on("change",function(){
		var term = $(this).find("option:selected").attr('data-term');
		var year = $(this).find("option:selected").attr('data-year');
		var courseReserve = [], courseRevenue = [];
		course.forEach(function(o,i){
			if(o['term'] == term && o['year'] == year){
				var temp = [];
				temp['y'] = Number(o['seatCount']);
				temp['label'] = o['name'];
				temp['name'] = o['name']+" "+o['term']+"/"+o['year'];
				temp['indexLabel'] = numWithComma(Number(o['seatCount']))+"";
				courseReserve.push(temp);

				var temp = [];
				temp['y'] = Number(o['income'])-Number(o['existing'])*200;
				temp['label'] = o['name'];
				temp['name'] = o['name']+" "+o['term']+"/"+o['year'];
				temp['indexLabel'] = numWithComma(Number(o['income'])-Number(o['existing'])*200)+"";
				courseRevenue.push(temp);
			}
			
		});
		var courseReserveChart = new CanvasJS.Chart("most-reserve", {
			title:{
				text: "การลงทะเบียนในคอร์ส "+term+"/"+year
			},
			animationEnabled: true,
			theme: "theme1",
			toolTip: {
				borderThickness: 0,
				content: "<span style='\"'color: {color};'\"'>{name}</span>: {y} ที่นั่ง",
				cornerRadius: 0
			},
			axisX:{
				labelFontSize: 10,
				interval: 1
			},
			data: [
				{       
					indexLabelFontColor: "#676464",
					indexLabelFontSize: 8,
					type: "column",
					dataPoints: courseReserve
				}
			]
		});
		courseReserveChart.render();
		var courseRevenueChart = new CanvasJS.Chart("most-revenue", {
			title:{
				text: "รายได้ในคอร์ส "+term+"/"+year
			},
			animationEnabled: true,
			theme: "theme1",
			toolTip: {
				borderThickness: 0,
				content: "<span style='\"'color: {color};'\"'>{name}</span>: {y} บาท",
				cornerRadius: 0
			},
			axisX:{
				labelFontSize: 10,
				interval: 1
			},
			data: [
				{       
					indexLabelFontColor: "#676464",
					indexLabelFontSize: 8,
					type: "column",
					dataPoints: courseRevenue
				}
			]
		});
		courseRevenueChart.render();
	});
})