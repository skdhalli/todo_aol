Pre-requisites: PHP version 7.1

The relational database used is sqlite and is located in this folder: /storage/databases/todo.sqlite. This database has two tables called Tasks and Status to store the todo items. Status is a master table with two keys - 'Pending' and 'Available'. The Tasks table has a foreign key to Status table.

Code Structure:

Server:
1. The REST end points for the api is defined in routes/api.php to point to methods in the Task controller
2. The TaskController (app/Http/Controllers/TaskController.php) has methods to handle all the CRUD operations on a todo task
3. The ORM models for Tasks and Status tables are available in 'app/Http/Models'. These models are based on the eloquent framework.
4. The DTO's that are exported from the API are stored in the app/Http/DTO folder. There is only class here called Task.php and this is serialized to a json form by the # API call.'

Client:
1. The client application is present inside public/todoapp folder. This client application is set as the home page through the routes/web.php class.
2. This client application has been downloaded from todomvc.com.
3. It has been modified to work with the REST api instead of the local storage.




Steps to execute this solution:
1. Clone the repository to a local folder
2. Run the following command to start the embedded server: php artisan serve
3. The user interface can be accessed from the following url --> http://localhost:8000/. This user interface is fully functional to perform all CRUD operations on the todo list.

API end points:

1. To get all the todo items

curl -X GET -H "Content-Type: application/json" -H "Cache-Control: no-cache"  "http://localhost:8000/api/tasks"


2. To create a todo item:

curl -X POST -H "Content-Type: application/json" -H "Cache-Control: no-cache"  -d '{"description":"My first todo item"}' "http://localhost:8000/api/tasks"

Expected response:
{
"success": true,
"last_insert_id": 80
}


3. To change the title of the todo item:

curl -X PUT -H "Content-Type: application/json" -H "Cache-Control: no-cache"  -d '{"id":80, "description":"My very first todo item", "status":"Pending"}' "http://localhost:8000/api/tasks"

Expected response:
{
"success": true,
"last_updated_id": 80
}

4. To change the status of a todo item:

curl -X PUT -H "Content-Type: application/json" -H "Cache-Control: no-cache"  -d '{"id":80, "description":"My very first todo item", "status":"Complete"}' "http://localhost:8000/api/tasks"

Expected response:
{
"success": true,
"last_updated_id": 80
}

5. To delete an existing todo item

curl -X DELETE -H "Content-Type: application/json" -H "Cache-Control: no-cache" -d '{"id":80}' "http://localhost:8000/api/tasks"

Expected response:
{
"success": true,
"last_deleted_id": 80
}
