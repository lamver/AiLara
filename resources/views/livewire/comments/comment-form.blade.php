<div>
    <form wire:submit.prevent="submitComment">
        <div class="mb-3">
            <label class="form-label" style="font-size: 18px">{{__('comment.Title')}}</label>
            <input wire:model.defer="title" class="form-control"/>
        </div>
        <div class="mb-3">
            <label for="content" class="form-label">{{__('comment.Add your comment')}}</label>
            <textarea wire:model.defer="content" class="form-control" id="content" rows="3"
                      placeholder="{{__('comment.Add your comment')}}"></textarea>
        </div>
        <button type="submit" class="btn btn-primary">{{__('Submit')}}</button>
    </form>
    @if($errors->any())
        @foreach ($errors->all() as $error)
            <x-input-error class="mt-2 text-sm" :messages="$error"/>
        @endforeach
    @endif
</div>
