/**
 * First we will load all of this project's JavaScript dependencies which
 * includes React and other helpers. It's a great starting point while
 * building robust, powerful web applications using React + Laravel.
 */

import './bootstrap';
import ReactDOM from "react-dom/client";
import TodoList from "@/components/todo_list.jsx";
import React from "react";

/**
 * Next, we will create a fresh React component instance and attach it to
 * the page. Then, you may begin adding components to this application
 * or customize the JavaScript scaffolding to fit your unique needs.
 */


if (document.getElementById('todo-list')) {
    const Index = ReactDOM.createRoot(document.getElementById("todo-list"));

    Index.render(
        <React.StrictMode>
            <TodoList/>
        </React.StrictMode>
    )
}
