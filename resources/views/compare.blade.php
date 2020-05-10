@extends('layouts.app')

@section('content')
    <div class="container-fluid">
        <div class="row">
            <div class="col-md-5">
                <textarea id="code" name="code"></textarea>
            </div>
            <div class="col-md-2 d-flex justify-content-center m-0 p-0">
                <button class="btn btn-success" id="compare">Сравнить</button>
            </div>
            <div class="col-md-5">
                <textarea id="code2" name="code"></textarea>
            </div>
        </div>
    </div>
    <div class="modal" tabindex="-1" role="dialog" id="modal">
        <div class="modal-dialog" role="document">
            <div class="modal-content">
                <div class="modal-header">
                    <h5 class="modal-title">Результат сравнения</h5>
                    <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                        <span aria-hidden="true">&times;</span>
                    </button>
                </div>
                <div class="modal-body">
                    <p>Сравнение методом W-Shingling: <strong><span id="shingle-result"></span> %</strong></p>
                </div>
            </div>
        </div>
    </div>
    <style>
        .CodeMirror {
            height: calc(100vh - 48px - 54px);
        }
    </style>
<script>
    let textarea = document.getElementById("code");
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
    let textarea2 = document.getElementById("code2");
    editor2 = CodeMirror.fromTextArea(textarea2, {
        lineNumbers: true,
        mode: "javascript",
        keyMap: "sublime",
        autoCloseBrackets: true,
        matchBrackets: true,
        showCursorWhenSelecting: true,
        theme: "monokai",
        tabSize: 2
    });
    $('#compare').on('click', function() {
        let firstText = editor.getValue();
        let secondText = editor2.getValue();

        if(firstText && secondText) {
            let firstHash = normalize(firstText);
            let secondHash = normalize(secondText);
            let result = compareShingles(firstHash, secondHash);
            console.log(result);
            $('#shingle-result').text(result)
            $('#modal').modal()
        }
    })
    function normalize(text) {
        text = text.toLowerCase();
        text = removeNewlines(text);
        let shingles = shingle(text.split(' '), 3);
        return shingles.map((arr) => {
            return arr.map((str) => {
                return hashString(str);
            });
        });
    }

    function removeNewlines(str) {
        str = str.replace(/([\/\,\!\\\^\$\{\}\[\]\(\)\.\*\+\?\|\<\>\-\&\;\:\@])/g,"");
        str = str.replace(/\s{2,}/g, ' ');
        str = str.replace(/\t/g, ' ');
        str = str.toString().trim().replace(/(\r\n|\n|\r)/g,"");
        return str;
    }

    function shingle(collection, size) {
        let shingles = [];
        for (let i=0; i<collection.length-size+1; i++) {
            shingles.push(collection.slice(i, i+size));
        }
        return shingles;
    }

    function hashString(str){
        let hash = 0;
        for (let i = 0; i < str.length; i++) {
            hash += Math.pow(str.charCodeAt(i) * 31, str.length - i);
            hash = hash & hash; // Convert to 32bit integer
        }
        return hash;
    }

    function union(array_first, array_second) {
        let concat_array = [...array_first, ...array_second];
        return [...new Set(concat_array)];
    }
    function intersect(a, b) {
        return [...new Set(a)].filter(x => new Set(b).has(x));
    }

    function compareShingles(arr1, arr2) {
        let unionArr = arr1.map((val, key) => {
            if(arr2[key]) {
                return union(val, arr2[key])
            }
        })
        unionArr = unionArr.filter((i) => i);
        let intersectArr = arr1.map((val, key) => {
            if(arr2[key]) {
                return intersect(val, arr2[key])
            }
        })
        intersectArr = intersectArr.filter((i) => i);
        return parseFloat((intersectArr.flat()).length / (unionArr.flat()).length * 100).toFixed(2)
    }

</script>
@endsection
