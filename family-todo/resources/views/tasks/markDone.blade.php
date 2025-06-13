<form action="{{ route('tasks.markDone', $task) }}" method="POST" class="inline">
    @csrf
    @method('PATCH')
    <button class="text-green-600 hover:underline">✔️ Done</button>
</form>