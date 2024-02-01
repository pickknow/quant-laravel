<div>
    <input type="text" wire:model="todo" placeholder="Todo..."> 
 
    <button wire:click="add">Add Todo</button>
 
    <ul>
        @foreach ($todos as $key=> $todo)
        <div wire:key="{{ $key }}">
            <h1>{{ $todo}}</h1>
            <button wire:click="delete({{$key}})">Delete</button> 
        </div>
    @endforeach
    </ul>


</div>
