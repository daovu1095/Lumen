<!DOCTYPE html>
<html lang="en-US">
<head>
    <meta charset="utf-8" content="application/json">
    <meta http-equiv="X-UA-Compatible" content="IE=edge">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <title>The Amazing PHP - AngularJS Single-page Application with Lumen CRUD Services</title>

    <!-- Load Bootstrap CSS -->
    <link href="https://maxcdn.bootstrapcdn.com/bootstrap/3.3.4/css/bootstrap.min.css" rel="stylesheet">
</head>
<body>

<div ng-app="myApp" ng-controller="usersCtrl">

    <!-- There will be a table, to dispay the data, here -->
    <table class="table">
        <thead>
        <tr>
            <th>ID</th>
            <th>Name</th>
            <th>Birdthday</th>
            <th>Description</th>
            <th><button id="btn-add" class="btn btn-primary btn-xs" ng-click="toggle('add',0)">Add New User</button></th>
        </tr>
        </thead>
        <tbody>
        <tr ng-repeat="user in users">
            <td>{{ user.id }}</td>
            <td>{{ user.name }}</td>
            <td>{{ user.birdthday }}</td>
            <td>{{ user.description }}</td>
            <td>
                <button class="btn btn-default btn-xs btn-detail" ng-click="toggle('edit',user.id)">Edit</button>
                <button class="btn btn-danger btn-xs btn-delete" ng-click="confirmDelete(user.id)">Delete</button>
            </td>
        </tr>
        </tbody>
    </table>
    <!-- There will be a modal to pop-up a Form (One form used as a create and edit form) -->
    <div class="modal fade" id="myModal" tabindex="-1" role="dialog" aria-labelledby="myModalLabel" aria-hidden="true">
        <div class="modal-dialog">
            <div class="modal-content">
                <div class="modal-header">
                    <button type="button" class="close" data-dismiss="modal" aria-label="Close"><span aria-hidden="true">&times;</span></button>
                    <h4 class="modal-title" id="myModalLabel">{{state}}</h4>
                </div>
                <div class="modal-body">
                    <form class="form-horizontal">

                        <div class="form-group">
                            <label for="inputEmail3" class="col-sm-3 control-label">Name</label>
                            <div class="col-sm-9">
                                <input type="text" class="form-control" id="inputEmail3" placeholder="Fullname" value="{{name}}" ng-model="formData.name">
                            </div>
                        </div>

                        <div class="form-group">
                            <label for="inputEmail3" class="col-sm-3 control-label">Birdthday</label>
                            <div class="col-sm-9">
                                <input type="text" class="form-control" id="inputEmail3" placeholder="birdthday" value="{{birdthday}}" ng-model="formData.birdthday">
                            </div>
                        </div>

                        <div class="form-group">
                            <label for="inputPassword3" class="col-sm-3 control-label">Description</label>
                            <div class="col-sm-9">
                                <input type="text" class="form-control" id="inputPassword3" placeholder="Description" value="{{description  }}" ng-model="formData.description">
                            </div>
                        </div>
                    </form>
                </div>
                <div class="modal-footer">
                    <button type="button" class="btn btn-default" data-dismiss="modal">Close</button>
                    <button type="button" class="btn btn-primary" id="btn-save" ng-click="save(modalstate,id)">Save changes</button>
                </div>
            </div>
        </div>
    </div>

</div>

<!-- Load Javascript Libraries (AngularJS, JQuery, Bootstrap) -->
<script src="https://ajax.googleapis.com/ajax/libs/angularjs/1.4.8/angular.min.js"></script>
<script src="https://cdnjs.cloudflare.com/ajax/libs/jquery/1.11.3/jquery.min.js"></script>
<script src="https://maxcdn.bootstrapcdn.com/bootstrap/3.3.4/js/bootstrap.min.js"></script>

<!-- There will be our javascript Application here -->
<script>
    var app = angular.module('myApp', []);
    app.controller('usersCtrl', function($scope, $http) {

        $http.get("http://localhost:80/user")
            .success(function (response) {
                $scope.users = response;
            });

        $scope.save = function(modalstate, id){
            switch(modalstate){
                case 'add': var url = "http://localhost:80/user"; break;
                case 'edit': var url = "http://localhost:80/user/"+ id; break;
                default: break;
            }
            $http({
                method  : 'POST',
                url     : url,
                data    : $.param($scope.formData),  // pass in data as strings
                headers : { 'Content-Type': 'application/x-www-form-urlencoded' }  // set the headers so angular passing info as form data (not request payload)
            }).
            success(function(response){
                location.reload();
            }).
            error(function(response){
                console.log(response);
                alert('Incomplete Form');
            });
        }

        $scope.toggle = function(modalstate,id) {
            $scope.modalstate = modalstate;
            switch(modalstate){
                case 'add':
                    $scope.state = "Add New User";
                    $scope.id = 0;
                    $scope.name = '';
                    $scope.birdthday = '';
                    $scope.description = '';
                    break;
                case 'edit':
                    $scope.state = "User Detail";
                    $scope.id = id;
                    $http.get("http://localhost:80/user/" + id)
                        .success(function(response) {
                            alert($scope.formData);
                            $scope.formData = response;
                        });
                    break;
                default: break;
            }

            //console.log(id);
            $('#myModal').modal('show');
        }

        $scope.confirmDelete = function(id) {
            var isConfirmDelete = confirm('Are you sure you want this record?');
            if (isConfirmDelete) {
                $http({
                    method: 'GET',
                    url: 'http://localhost:80/user/' + id
                }).
                success(function(data) {
                    console.log(data);
                    location.reload();
                }).
                error(function(data) {
                    console.log(data);
                    alert('Unable to delete');
                });
            } else {
                return false;
            }
        }

    });
</script>
</body>
</html>