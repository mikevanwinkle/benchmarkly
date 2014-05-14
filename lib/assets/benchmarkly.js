jQuery(function($) {	
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
		}

		self.doRequest = function() {
			$.ajax( ajaxurl , { "type": self.request['method'], "data": { "action": self.request['action'] } }, function(resp) {
				console.log(resp);
			} );
		}
	}
	
	var benchmarkly = new Benchmarkly();
	benchmarkly.init();
});
