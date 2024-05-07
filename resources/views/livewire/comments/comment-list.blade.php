<div>
    @foreach($comments as $comment)
        <livewire:Comments.comment-item :comment="$comment" key="{{ now() }} .'-'.{{ $comment->id }}"/>
    @endforeach
</div>
