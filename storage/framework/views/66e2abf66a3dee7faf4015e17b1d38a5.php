<?php $__env->startSection('content'); ?>
    <div id="app">
        <div class="row mb-4">
            <div class="col-12 justify-content-end d-flex">
                <a class="btn btn-primary" href="<?php echo e(route('todo_list.create')); ?>">Create new Todo List</a>
            </div>
        </div>
        <?php if(!$todoLists->count()): ?>
            <div class="row">
                <div class="col-12">
                    <div class="alert alert-info text-center">
                        There are no lists.
                    </div>
                </div>
            </div>
        <?php endif; ?>
        <div class="row">
            <?php $__currentLoopData = $todoLists; $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $todolist): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); ?>
                <div class="col-4 mb-4">
                    <div class="card">
                        <div class="card-header">
                            <h3 class="d-flex justify-content-between">
                                <a href="<?php echo e(route('todo_list.show', ['todoList'=>$todolist])); ?>">
                                    <?php echo e($todolist->title); ?>

                                </a>
                                <span>
                                    <?php echo e($todolist->state==\App\Models\TodoList::STATE_COMPLETED ? 'Completed' : ''); ?>

                                </span>
                            </h3>
                        </div>
                        <div class="card-body">
                            <div>
                                Tasks:
                                <span>
                                    <?php echo e($todolist->tasks->count()); ?>

                                </span>
                            </div>
                            <div>
                                Created at (UTC):
                                <span>
                                    <?php echo e($todolist->created_at); ?>

                                </span>
                            </div>
                        </div>
                    </div>
                </div>
            <?php endforeach; $__env->popLoop(); $loop = $__env->getLastLoop(); ?>
        </div>
    </div>
<?php $__env->stopSection(); ?>

<?php echo $__env->make('layouts.user', \Illuminate\Support\Arr::except(get_defined_vars(), ['__data', '__path']))->render(); ?><?php /**PATH F:\PROJECTS\task-laravel10-todo-lists\resources\views/todo_list/index.blade.php ENDPATH**/ ?>