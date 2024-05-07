<div>
    <div class="card mb-3">
        <div class="card-body">
            <h5 class="card-title">{{ $comment->title }}</h5>
            <p class="card-text">{{ $comment->body }}</p>

            @if($editCommentId === $comment->id)

                <div class="flex-1 mb-3">
                    <textarea
                        class="form-control mb-3"
                        name="editCommentValue"
                        wire:model.lazy="editCommentValue"> {{$comment->body}} </textarea>
                    <button class="btn btn-success"
                            wire:click="updateComment({{ $comment->id }})">{{__('Save')}}</button>
                    <button class="btn btn-danger" wire:click="cancelEditedComment">{{__('Cancel')}}</button>
                    @error('editCommentValue')
                    <x-input-error class="mt-2 text-sm" :messages="$message"/>
                    @enderror
                </div>
            @endif

            @if($replyCommentId === $comment->id)
                <div class="flex-1 mb-3 ml-3">
                  <textarea
                      class="form-control mb-3"
                      name="comment"
                      wire:model.lazy="replyCommentBody"></textarea>
                    <button class="btn btn-success"
                            wire:click="replyComment({{ $comment->id }})">{{__('Reply')}}</button>
                    <button wire:click.prevent="cancelReply" class="btn btn-danger">{{__('Cancel')}}</button>
                    @error('replyCommentBody')
                    <x-input-error class="mt-2 text-sm" :messages="$message"/>
                    @enderror
                </div>
            @endif

            @if(!$editCommentId && Auth::check())
                <div class="btn-group">
                    @if(Auth::user()->id === $comment->creator_id)
                        <button type="button" class="btn btn-primary"
                                wire:click.prevent="startEditingComment({{ $comment->id }}, '{{$comment->body}}')">
                            {{__('Edit')}}
                        </button>
                        <button type="button" class="btn btn-danger"
                                wire:confirm="{{__('delete')}} ?"
                                wire:click.prevent="deleteComment({{$comment->id}})"> {{__('Delete')}}
                        </button>
                    @else
                        @if(!$replyCommentId)
                            <button type="button" class="btn btn-info"
                                    wire:click.prevent="startReply({{ $comment->id }})">
                                {{__('Reply')}}
                            </button>
                        @endif
                    @endif
                </div>
            @endif

        </div>
    </div>

    @if($comment->children)
        @foreach($comment->children as $children)
            <div style="margin-left: 35px">
                <livewire:Comments.comment-item :comment="$children" key="'child_'.{{ now() }} .'-'.{{ $children->id }}"/>
            </div>
        @endforeach
    @endif

</div>
