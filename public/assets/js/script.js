let editor = document.querySelector(".codeArea code");
let editorInputs = editor.getElementsByTagName("input");
let result = document.querySelector("iframe");
let button = document.getElementById("submit");

window.onload = refreshResult;
editor.addEventListener("keyup", refreshResult);

function refreshResult() {
    let content = "data:text/html;charset=utf-8," + editor.innerHTML;
    content = content.replace(/<br[^>]*>/g, "");
    for (let i = 0; i < editorInputs.length; i++)
        content = content.replace(/<input[^>]*>/, editorInputs[i].value);
    result.src = encodeURI(content);
}