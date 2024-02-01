<?php

namespace App\Livewire;

use Livewire\Component;
use Illuminate\Support\Facades\Log;
use Livewire\Attributes\On;

class Counter extends Component
{
    public $todos = ['aa', 'bb', 'cc', 'dd'];

    public $todo = '';

    public function add()
    {
        $this->todos[] = $this->todo;

        $this->todo = '';
    }

    public function delete($id)
    {

        array_splice($this->todos, $id, 1);
        $this->dispatch('todo-deleted', id: $id);
    }
    public function getPostCount()
    {
        return count($this->todos);
    }

    #[On('todo-deleted')]
    public function updatePostList($id)
    {
    }
}
