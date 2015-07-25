(function(){
	var ram = angular.module('ram', ['ui.bootstrap']);

	/**
	 * We can't use angular-cookie here since it does not allow persistent cookies yet.
	 * As an alternative we use the cookieProvider found here:
	 * https://github.com/angular/angular.js/pull/2459#issuecomment-24657403
	 */
	
	ram.provider('$cookieStore', [function(){
		var self = this;
		self.defaultOptions = {};

		self.setDefaultOptions = function(options){
			self.defaultOptions = options;
		};

		self.$get = function(){
			return {
				get: function(name){
					var jsonCookie = $.cookie(name);
					if(jsonCookie){
						return angular.fromJson(jsonCookie);
					}
				},
				put: function(name, value, options){
					options = $.extend({}, self.defaultOptions, options);
					$.cookie(name, angular.toJson(value), options);
				},
				remove: function(name, options){
					options = $.extend({}, self.defaultOptions, options);
					$.removeCookie(name, options);
				}
			};
		};
	}]);

	ram.config(['$cookieStoreProvider', function($cookieStoreProvider){
		$cookieStoreProvider.setDefaultOptions({
			path: '/', // Cookies should be available on all pages
			expires: 365 * 3 // Store cookies for three years
		});
	}]);

	/**
	 * End of vendor code
	 */
	
	ram.controller('saveModalCtrl', function ($scope, $modalInstance, programs) {

		$scope.programs = programs;

		$scope.savename = undefined;

		$scope.select = function (name){
			$scope.savename = name;
		}

		$scope.ok = function () {
			if($scope.savename !== undefined && $scope.savename !== 'programs' && $scope.savename !== ''){
				$modalInstance.close($scope.savename);
			}else{
				alert('Please enter a valid name.');
			}
		};

		$scope.cancel = function () {
			$modalInstance.dismiss('cancel');
		};
	});

	ram.controller('editorCtrl', ['$cookieStore', '$scope', '$modal', '$http', function ($cookieStore, $scope, $modal, $http){

		// we use this to store the saved programs
		$scope.saved = [];

		// the program which is currently selected to be loaded by the user
		$scope.selected = undefined;

		// hash of the editor field to track changes
		$scope.starthash = undefined;


		$scope.running = false;
		$scope.result = undefined;

		$scope.lockEditor = function (){
			editor.setReadOnly(true);
			$scope.running = true;
			$('#loading').fadeIn();
		};

		$scope.unlockEditor = function (){
			editor.setReadOnly(false);
			$scope.running = false;
			$('#loading').fadeOut();
		};

		//list of examples
		$scope.examples = [];
		$scope.selected_example = undefined;

		// init function
		$scope.init = function (){
			$scope.starthash = md5(editor.getValue());

			var progs = $cookieStore.get('programs');
			
			var stillWorking = [];
			if(progs !== undefined){
				for(var i = 0; i < progs.length; i++){
					if($cookieStore.get(progs[i]) === undefined){
						alert('Your saved program '+progs[i]+' has been deleted');
					}else{

						// the cookie is still there, update its expire date
						var val = $cookieStore.get(progs[i]);
						$cookieStore.remove(progs[i]);
						$cookieStore.put(progs[i], val, {expires: 365 * 3});
						stillWorking.push(progs[i]);

						// load the program into our saved array
						$scope.saved.push({title: progs[i], program: val});

						if(progs.length > 0){
							$scope.selected = progs[0];
						}
					}
				}
			}

			$cookieStore.put('programs', stillWorking);

			// load examples from server
			var listPromise = $http.get('examples/');

			listPromise.success(function(data, status, headers, config) {
				$scope.examples = data.data;
				if($scope.examples.length > 0){
					$scope.selected_example = $scope.examples[0].value;
				}
			});

			listPromise.error(function(data, status, headers, config) {
				alert('Couldn\'t load the examples from the server!');
			});

		}

		$scope.save = function(){

			var saveModal = $modal.open({
				templateUrl: 'saveModal.html',
				controller: 'saveModalCtrl',
				size: 'lg',
				resolve: {
					programs: function () {
						return $scope.saved;
					}
				}
			});

			saveModal.result.then(function (saveName) {
				if($cookieStore.get(saveName) !== undefined && confirm('A program with the name '+saveName+' already exists. Continue?')){
					$cookieStore.remove(saveName);
				}else{
					var list = $cookieStore.get('programs');
					list.push(saveName);
					$cookieStore.put('programs', list);
				}
				$cookieStore.put(saveName, editor.getValue());
				$scope.saved.push({title: saveName, program: editor.getValue()});
			
				$scope.selected = saveName;
				$scope.starthash = md5(editor.getValue());

			}, function () {
			});
		};

		$scope.run = function(){
			
			$scope.lockEditor();
			editor.getSession().clearAnnotations();

			var progPromise = $http({
				method: 'post',
				url: "ram/",
				data: {
					program: editor.getValue()
				}
			});
 
			progPromise.success(function(data, status, headers, config) {

				if(data.error !== undefined){

					var error =  [{row: data.line-1, text: data.message, type: 'error'}];
					editor.getSession().setAnnotations(error);

				}else{
					$scope.result = data;
				}
				$scope.unlockEditor();
			});

			progPromise.error(function(data, status, headers, config) {
				alert('Huh,something went wrong: Request to the RAM simulator failed!');
			});
		}

		$scope.loadSave = function(){
			if($scope.starthash === md5(editor.getValue()) || confirm('The program has been changed. Do you really want to discard your changes?')){
				for(var i=0; i < $scope.saved.length; i++){
					if($scope.saved[i].title === $scope.selected){
						editor.setValue($scope.saved[i].program);
						$scope.starthash = md5(editor.getValue());
						break;
					}
				}
			}
		}

		$scope.loadExample = function(){

			var exPromise = $http.get('examples/?get='+$scope.selected_example);

			exPromise.success(function(data, status, headers, config) {
				if($scope.starthash === md5(editor.getValue()) || confirm('The program has been changed. Do you really want to discard your changes?')){
					editor.setValue(data.data);
					$scope.starthash = md5(editor.getValue());
				}
			});
			exPromise.error(function(data, status, headers, config) {
				alert('Couldn\'t load the example '+$scope.selected_example+' from the server!');
			});
		}

	}]);

})();