<div class="fullEditor" data-key="{{ $key }}">
    @if ($mode == 1)
        <span class="badge badge-pill badge-warning" style=" float: right; position: relative;">use editor</span>
    @else
        <span class="badge badge-pill badge-danger" style=" float: right; position: relative;">only text</span>
    @endif

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
