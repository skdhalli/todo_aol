/*jshint unused:false */

(function (exports) {

	'use strict';
	
	var STORAGE_KEY = 'todos-vuejs';
    var base_url = '/api/tasks';
	exports.todoStorage = {
		fetch: function () {
			var task_todos = [];
			axios.get(base_url).then(
				response=>{
					response.data.tasks.forEach(function (task){
						var todo = {};
						todo.title = task.description;
						todo.id = task.id;
						if(task.status == "Pending")
						{
							todo.completed = false;
						}
						else
						{
							todo.completed = true;
						}
						task_todos.push(todo)
					})
				});
			return task_todos;
		}
	};

})(window);
