<?php return [
  'blog' => [
      'name' => 'Blog',
      'controller' => \App\Http\Controllers\Modules\Blog\PostsController::class,
      'action' => 'index',
      'route_prefix' => '/blog',
      ],
  'aiform' => [
      'name' => 'Ai form',
      'controller' => \App\Http\Controllers\Modules\Task\TaskController::class,
      'action' => 'index',
      'route_prefix' => '/task1',
  ]
];
