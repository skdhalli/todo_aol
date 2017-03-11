/*global Vue, todoStorage */

(function (exports) {

	'use strict';
	var base_url = '/api/tasks';
	var filters = {
		all: function (todos) {
			return todos;
		},
		active: function (todos) {
			return todos.filter(function (todo) {
				return !todo.completed;
			});
		},
		completed: function (todos) {
			return todos.filter(function (todo) {
				return todo.completed;
			});
		}
	};

	exports.app = new Vue({

		// the root element that will be compiled
		el: '.todoapp',

		// app initial state
		data: {
			todos: todoStorage.fetch(),
			newTodo: '',
			editedTodo: null,
			visibility: 'all'
		},

		// computed properties
		// http://vuejs.org/guide/computed.html
		computed: {
			filteredTodos: function () {
				return filters[this.visibility](this.todos);
			},
			remaining: function () {
				return filters.active(this.todos).length;
			},
			allDone: {
				get: function () {
					return this.remaining === 0;
				},
				set: function (value) {
					this.todos.forEach(function (todo) {
						todo.completed = value;
					});
				}
			}
		},

		// methods that implement data logic.
		// note there's no DOM manipulation here at all.
		methods: {

			pluralize: function (word, count) {
				return word + (count === 1 ? '' : 's');
			},

			addTodo: function () {
				var value = this.newTodo && this.newTodo.trim();
				if (!value) {
					return;
				}
				
				var task = {};
				task.description = value;
				task.status = 'Pending';
				var self = this;
				axios.post(base_url, task)
					  .then(function(response){
					    self.todos.push({ title: value, completed: false, id: response.data.last_insert_id });
					  }); 

				this.newTodo = '';
			},

			removeTodo: function (todo) {
				var index = this.todos.indexOf(todo);
				this.todos.splice(index, 1);
				//delete from local db
				var task = {};
				task.id = todo.id;
				axios({method: 'delete', url: base_url, data: {id: todo.id}}); 
			},
			updateTodoStatus: function (todo) {
				var task = {};
					task.description = todo.title;
					if(todo.completed)
					{
						task.status = "Pending";
					}
					else
					{
						task.status = "Complete";
					}
					task.id = todo.id;
					axios.put(base_url, task)
						.then(function(response){
							
						});
			},

			editTodo: function (todo) {
				this.beforeEditCache = todo.title;
				this.editedTodo = todo;
				
			},

			doneEdit: function (todo) {
				if (!this.editedTodo) {
					return;
				}
				this.editedTodo = null;
				todo.title = todo.title.trim();
				if (!todo.title) {
					this.removeTodo(todo);
				}
				else
				{
					var task = {};
					task.description = todo.title;
					if(todo.completed)
					{
						task.status = "Complete";
					}
					else
					{
						task.status = "Pending";
					}
					task.id = todo.id;
					axios.put(base_url, task)
						.then(function(response){
							
						});  
				}
			},

			cancelEdit: function (todo) {
				this.editedTodo = null;
				todo.title = this.beforeEditCache;
				
			},

			removeCompleted: function () {
				this.todos.forEach(function(todo) {
					if(todo.completed)
					{
						axios({method: 'delete', url: base_url, data: {id: todo.id}});
					}
				});
				this.todos = filters.active(this.todos);
                
			}
		},

		// a custom directive to wait for the DOM to be updated
		// before focusing on the input field.
		// http://vuejs.org/guide/custom-directive.html
		directives: {
			'todo-focus': function (el, binding) {
				if (binding.value) {
					el.focus();
				}
			}
		}
	});

})(window);
