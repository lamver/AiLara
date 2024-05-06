<div>
    @foreach($comments as $comment)
        <livewire:Comments.comment-item :comment="$comment" key="{{ now() }}"/>
    @endforeach
</div>
