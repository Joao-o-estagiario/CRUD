var app = angular.module('angularjs-starter', []);
app.controller('MainCtrl', function($scope) {
  $scope.name = 'World';

  $scope.save = function(myForm){
    console.log(myForm);
    myForm.$setPristine();
  }
});