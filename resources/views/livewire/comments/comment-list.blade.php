<div>
    @foreach($comments as $comment)
        <livewire:Comments.comment-item :comment="$comment" key="{{ now() }} .'-'.{{ $comment->id }}"/>
    @endforeach
</div>

<script>
    let commentBox = document.querySelector(location.hash);
    if(commentBox) {
        commentBox.style.backgroundColor = '#0dcaf014'
    }
</script>
