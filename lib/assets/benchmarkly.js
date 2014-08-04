jQuery(document).ready(function($) {	
	function Benchmarkly() {
		var self = this;
		self.data = [];
		self.request = [];
		self.current_benchmark = '';

		self.init = function() {
			$("input.action-btn-click").on('click', function() {
				self.request["method"] = $(this).attr('data-method');
				self.request["action"] = $(this).attr('name');
				self.doRequest();
			});
			$('.accordion-section').accordion();
			self.current_benchmark = $('input#current-benchmark').val();
			$(document).on('change','select[name="benchmark"]', function() { 
				$.when( $.Deferred().resolve(1) ).done( function() { 
					$('input#current-benchmark').val($('select[name="benchmark"]').val());
				}).done( function() { 
					self.refreshChart(); 
				});
			});
			self.loadChart();
	
		}
		
		self.loadChart = function() {
			if ( bmkly.chart_data == 1 ) {
				self.data = [];
				jsondata = $.parseJSON(bmkly.benchmarks);
					console.log(self.current_benchmark);
				$.each(jsondata[self.current_benchmark].data, function(index,object) {				
						self.data.push([ ( Date.parse(index) ),object]);
				});
				$.plot( "#mainChart", [{ 
						data: [], 
						lines: {"show":true},
						points: {"show":true}
				}], { xaxis: { mode:"time"} } );
				$.plot( "#mainChart", [{ 
						data: self.data, 
						lines: {"show":true},
						points: {"show":true},
						hoverable: true
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
			$.each( bmkly.benchmarks[self.current_benchmark], function(index, obj) {
				console.log( obj.name );
				//data[obj.name] = obj.datanum;
			});
			//console.log( data );
		}

		self.refreshChart = function() {
			$.when( $.Deferred().resolve(1) ).done( function() { 
				$.plot( "#mainChart", [{ 
						data: [], 
						lines: {"show":true},
						points: {"show":true}
				}], { xaxis: { mode:"time"} } );
				
			}).done( function () {
				self.setCurrentBenchmark();
			}).done(function() { self.loadChart(); } );			
		}

		self.setCurrentBenchmark = function () {
			self.current_benchmark = $('input#current-benchmark').val();
		}
	}
	
	var benchmarkly = new Benchmarkly();
	benchmarkly.init();
});
