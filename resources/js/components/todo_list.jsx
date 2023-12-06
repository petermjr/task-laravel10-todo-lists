import React from 'react';
import $ from 'jquery';
import moment from 'moment-timezone'
import { ToastContainer, toast } from 'react-toastify';
import 'react-toastify/dist/ReactToastify.css';

class TodoList extends React.Component {

    constructor(props) {
        super(props);
        this.state = {
            todoList: [],
            taskContent: '',
            taskDeadline: null,
            tzOffsetMinutes: new Date().getTimezoneOffset(),
            timeZone: Intl.DateTimeFormat().resolvedOptions().timeZone,
            userLang: navigator.language || 'en-US'
        }
        this.saveTask = this.saveTask.bind(this)
        this.fetchTodoList = this.fetchTodoList.bind(this)
        this.updateTask = this.updateTask.bind(this)
        this.updateTodoList = this.updateTodoList.bind(this)
        this.formatDtToUserTz = this.formatDtToUserTz.bind(this)
        this.deleteTodoList = this.deleteTodoList.bind(this)
    }

    updateTask(e) {
        const id = $(e.target).data('id')
        let content = document.querySelector(`input[name='content'][data-id='${id}']`).value;
        let deadline = document.querySelector(`input[name='deadline'][data-id='${id}']`).value;
        let state = document.querySelector(`select[name='state'][data-id='${id}']`).value;
        $.ajax({
            url: '/task/' + id,
            method: 'PATCH',
            headers: {
                'Content-Type': 'application/json',
                'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content'),
            },
            data: JSON.stringify({
                todo_list_id: this.state.todoList.id,
                content: content,
                deadline: deadline,
                state: state,
                tz_offset_minutes: this.state.tzOffsetMinutes
            }),
            success: (data) => {
                this.setState({todoList: {...data.data}});
                $("#task-flash-message-" + id).addClass("alert alert-success mt-3").text("The task was updated!");
                setTimeout(function () {
                    $("#task-flash-message-" + id).removeClass("alert alert-success mt-3").text("");
                }, 2000);
            },
            error: (jqXHR, textStatus, errorThrown) => {
                console.error('There was an unexpected problem!', errorThrown);
                let errors = ''
                if (jqXHR.responseJSON.errors != undefined) {
                    for (let prop in jqXHR.responseJSON.errors) {
                        errors += jqXHR.responseJSON.errors[prop] + '\n'
                    }
                    alert(errors)
                }
            }
        })
    }

    saveTask() {
        $.ajax({
            url: '/task',
            method: 'POST',
            headers: {
                'Content-Type': 'application/json',
                'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content')
            },
            data: JSON.stringify({
                todo_list_id: this.state.todoList.id,
                content: this.state.taskContent,
                deadline: this.state.taskDeadline,
                tz_offset_minutes: this.state.tzOffsetMinutes
            }),
            success: (data) => {
                this.setState({todoList: {...data.data}});
            },
            error: (jqXHR, textStatus, errorThrown) => {
                console.error('There was an unexpected problem!', errorThrown);
                let errors = ''
                if (jqXHR.responseJSON.errors != undefined) {
                    for (let prop in jqXHR.responseJSON.errors) {
                        errors += jqXHR.responseJSON.errors[prop] + '\n'
                    }
                    alert(errors)
                }
            }
        });
    }

    fetchTodoList(id) {
        fetch(`/api/todo-list/${id}`, {
            method: 'get', headers: {
                'Content-Type': 'application/json',
            },
        })
            .then(response => {
                if (!response.ok) {
                    throw new Error('There was an unexpected problem!');
                }
                return response.json();
            })
            .then(data => {
                this.setState({todoList: {...data.data}});
            })
            .catch(error => {
                console.error(error);
            });
    }

    deleteTodoList() {
        if (!confirm('Are you sure you want to delete this Todo List?')) {
            return false
        }
        $.ajax({
            url: '/todo-list/' + this.state.todoList.id,
            method: 'POST',
            headers: {
                'Content-Type': 'application/json',
                'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content'),
            }, data: JSON.stringify({
                '_method': 'DELETE'
            }),
            success: (data) => {
                window.location.href = '/'
            },
            error: (jqXHR, textStatus, errorThrown) => {
                console.error('There was an unexpected problem!', errorThrown);
                let errors = ''
                if (jqXHR.responseJSON.errors != undefined) {
                    for (let prop in jqXHR.responseJSON.errors) {
                        errors += jqXHR.responseJSON.errors[prop] + '\n'
                    }
                    alert(errors)
                }
            }
        });
    }

    updateTodoList(e) {
        $.ajax({
            url: '/todo-list/' + this.state.todoList.id,
            method: 'PATCH',
            headers: {
                'Content-Type': 'application/json',
                'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content'),
            }, data: JSON.stringify({
                title: this.state.todoList.title
            }),
            success: (data) => {
                toast('Todo list was updated!')
            },
            error: (jqXHR, textStatus, errorThrown) => {
                console.error('There was an unexpected problem!', errorThrown);
                let errors = ''
                if (jqXHR.responseJSON.errors != undefined) {
                    for (let prop in jqXHR.responseJSON.errors) {
                        errors += jqXHR.responseJSON.errors[prop] + '\n'
                    }
                    alert(errors)
                }
            }
        });
    }

    formatDtToUserTz(dateTime) {
        let dt = moment.utc(dateTime).tz(this.state.timeZone);
        return dt.format('YYYY-MM-DDTHH:mm');
    }

    componentDidMount() {
        const path = window.location.pathname;
        const id = path.split("/").pop();
        this.fetchTodoList(id)
    }

    render() {
        const {todoList} = this.state

        if (!todoList) {
            return (<div className={'col-7'}>
                <div className={'text-center'}>
                    Loading data... <br/>
                    <a href={'/'}>Go back</a>
                </div>
            </div>)
        }
        return (<div>
            <div className="row justify-content-center">
                <div className="col-8 ms-auto me-auto">
                    <div className={'mb-2'}>
                        {this.state.todoList.state=='completed' ? (
                            <h4 className={'text-success'}>Completed</h4>
                        ): (
                            ''
                        )}
                    </div>
                    <div className={'d-flex justify-content-between mb-5'}>
                        <div className="col-6">
                            <div className="input-group">
                                <input
                                    style={{cursor: 'pointer', fontSize: '1.3em', fontWeight: 'bold'}}
                                    type="text"
                                    className="form-control"
                                    defaultValue={todoList.title}
                                    onChange={(e)=>this.setState({todoList: {...this.state.todoList, title: e.target.value}})}
                                />
                                <button
                                    type="button"
                                    className="btn btn-outline-success"
                                    onClick={this.updateTodoList}
                                >✔️
                                </button>
                            </div>
                        </div>
                        <button className={'btn btn-sm btn-outline-danger'} onClick={this.deleteTodoList}>Delete List
                        </button>
                    </div>
                    <form action="" className={'mb-5'}>
                        <div className={'row'}>
                            <div className={'col-lg-6'}>
                                <input
                                    onChange={(e) => {
                                        this.setState({taskContent: e.target.value})
                                    }}
                                    placeholder={'Create a task ...'} type="text" className={'form-control'}
                                    name={'content'}
                                    id={'task-content'}/>
                            </div>
                            <div className="col-lg-4">
                                <input
                                    onChange={(e) => {
                                        this.setState({taskDeadline: e.target.value})
                                    }}
                                    className={'form-control'}
                                    type="datetime-local" name="deadline" id="task-deadline"/>
                            </div>
                            <div className={'col-lg-2'}>
                                <button onClick={this.saveTask} type={'button'}
                                        className={'btn w-100 btn-primary'}>Save
                                </button>
                            </div>
                        </div>
                    </form>
                    <div className="row">

                        <div className="col-12">
                            {todoList.tasks && todoList.tasks.length ? (
                                <h3>Tasks</h3>
                            ): ''}
                            {todoList.tasks && todoList.tasks.length? (
                                todoList.tasks.map((task) => {
                                    return (
                                        <div key={'task-' + task.id} className="card" style={{margin: '1em 0'}}>
                                            <div className="card-body">
                                                <div className="form-group">
                                                    <input type={'text'} name={'content'} data-id={task.id}
                                                           onChange={this.updateTask}
                                                           defaultValue={task.content}
                                                           className="form-control"/>
                                                </div>
                                                <div className={'row'}>
                                                    <div className={'col-lg-6'}>
                                                        <label htmlFor={'task-deadline-' + task.id}
                                                               className={'form-label'}>Deadline:</label>
                                                        <input
                                                            data-id={task.id}
                                                            onChange={this.updateTask}
                                                            id={'task-deadline-' + task.id}
                                                            name={'deadline'}
                                                            className={'form-control'}
                                                            type="datetime-local"
                                                            data-datetime={task.deadline}
                                                            value={this.formatDtToUserTz(task.deadline)}/>
                                                    </div>
                                                    <div className={'col-lg-6'}>
                                                        <label htmlFor={'task-state-' + task.id}
                                                               className="form-label">State:</label>
                                                        <select id={'task-state-' + task.id}
                                                                data-id={task.id}
                                                                onChange={this.updateTask}
                                                                name={'state'}
                                                                className="form-select"
                                                                aria-label="Task state" value={task.state}>
                                                            <option value="uncompleted"
                                                                    defaultValue={task.state}>Uncompleted
                                                            </option>
                                                            <option value="completed"
                                                                    defaultValue={task.state}>Completed
                                                            </option>
                                                            <option value="disabled"
                                                                    defaultValue={task.state}>Disabled
                                                            </option>
                                                        </select>
                                                    </div>
                                                </div>
                                                <div className={'row'}>
                                                    <div className={'col-12'}>
                                                        <div id={'task-flash-message-' + task.id}></div>
                                                    </div>
                                                </div>
                                            </div>
                                        </div>
                                    )
                                })
                            ) : (
                                <div>There are no tasks yet</div>
                            )}
                        </div>
                    </div>

                </div>
            </div>
            <ToastContainer />
        </div>)
    }
}

export default TodoList;
