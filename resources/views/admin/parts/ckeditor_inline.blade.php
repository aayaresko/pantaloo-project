<div class="fullEditor" data-key="{{ $key }}">
    <div class="ckEditor" data-group="{{ $group }}" data-item="{{ $item }}">
        @if (is_null($html))
            <br>
        @else
            {!! $html !!}
        @endif
    </div>


    <div class="panelEditor" style="display: none">
        <hr>
        <button type="button" class="btn btn-success saveTrans">Save</button>
        <input class="statusEditor" readonly>
    </div>
</div>
