Pre-requisites: PHP version 7.1, Chrome Browser

Overview:

The solution has a REST API developed using an MVC framework and a javascript client that consumes this REST API. The server and client are located in the same solution folder.

The relational database used is sqlite and is located in this folder: /storage/databases/todo.sqlite. This database has two tables called Tasks and Status to store the todo items. Status is a master table with two keys - 'Pending' and 'Available'. This allows room for additional states going forward. The Tasks table has a foreign key to Status table. We can create additional tables to host other aspects of the task such as Location, Due date, Reminder settings, etc in their own tables and add the foreign key to the Tasks table.

Code Structure:

Server:

1. The REST end points for the api is defined in routes/api.php to point to methods in the TaskController.php class.
2. The TaskController (app/Http/Controllers/TaskController.php) has methods to handle all the CRUD operations on a todo task
3. The Eloquent based ORM models for Tasks and Status tables are available in 'app/Http/Models'. 
4. The DTO's that are exported from the API are stored in the app/Http/DTO folder. There is only class here called Task.php and this is serialized to a json form by the # API call.'

Client:

Disclaimer: I am not an expert in javascript. I have copied the solution from todomvc.com and made changes to work wth the REST API instead of localStorage. The user interface has been tested on a Chrome browser.

1. The client application is present inside public/todoapp folder. This client application is set as the home page through the routes/web.php class.




Steps to execute this solution:

1. Clone the repository to a local folder
2. Run the following command to start the embedded server: "php artisan serve"
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
