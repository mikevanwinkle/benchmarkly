jQuery(document).ready(function($) {	
	function Benchmarkly() {
		var self = this;
		self.data = [];
		self.request = [];

		self.init = function() {
			$("input.action-btn-click").on('click', function() {
				self.request["method"] = $(this).attr('data-method');
				self.request["action"] = $(this).attr('name');
				self.doRequest();
			});
			$('.accordion-section').accordion();
			if ( bmkly.chart_data == 1 ) {
				jsondata = $.parseJSON(bmkly.benchmarks);
				$.each(jsondata[0].data, function(index,object) { 
					self.data.push([ ( Date.parse(index) ),object]);
				});
				$.plot( "#mainChart", [{ 
						data: self.data, 
						lines: {"show":true},
						points: {"show":true}
				}], { xaxis: { mode:"time"} } );
			}
	
		}

		self.doRequest = function() {
			$.ajax( ajaxurl , { "type": self.request['method'], "data": { "action": self.request['action'] } }, function(resp) {
				console.log(resp);
			} );
		}

		self.chartifyData = function() {
			data = {};
			$.each( bmkly.benchmarks, function(index, obj) {
				console.log( obj.name );
				//data[obj.name] = obj.datanum;
			});
			//console.log( data );
		}
	}
	
	var benchmarkly = new Benchmarkly();
	benchmarkly.init();
});
