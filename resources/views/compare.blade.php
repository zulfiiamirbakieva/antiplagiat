@extends('layouts.app')

@section('content')
    <div class="container-fluid">
        <div class="row">
            <div class="col-md-6">
                <textarea id="code" name="code"></textarea>
            </div>
            <div class="col-md-6">
                <textarea id="code2" name="code"></textarea>
            </div>
        </div>
    </div>
    <style>
        .CodeMirror {
            height: calc(100vh - 48px - 54px);
        }
    </style>
<script>
    var textarea = document.getElementById("code");
    editor = CodeMirror.fromTextArea(textarea, {
        lineNumbers: true,
        mode: "javascript",
        keyMap: "sublime",
        autoCloseBrackets: true,
        matchBrackets: true,
        showCursorWhenSelecting: true,
        theme: "monokai",
        tabSize: 2
    });

    var textarea2 = document.getElementById("code2");
    editor = CodeMirror.fromTextArea(textarea2, {
        lineNumbers: true,
        mode: "javascript",
        keyMap: "sublime",
        autoCloseBrackets: true,
        matchBrackets: true,
        showCursorWhenSelecting: true,
        theme: "monokai",
        tabSize: 2
    });
</script>
@endsection
