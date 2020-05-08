let editor = document.querySelector(".codeArea code");
let level = document.querySelector(".instructions span");
let instructions = document.querySelector(".instructions p");

fetch('/api/exercises/html/current')
    .then(response => response.json())
    .then(data => {
        console.log(data);
        level.innerHTML = data.level;
        instructions.innerHTML = data.description;
        editor.innerHTML = data.problem;
        refreshResult();
    });

let editorInputs = editor.getElementsByTagName("input");
let result = document.querySelector("iframe");
let button = document.getElementById("submit");

//window.onload = refreshResult;
editor.addEventListener("keyup", refreshResult);

function refreshResult() {
    let content = "data:text/html;charset=utf-8," + editor.innerHTML;
    content = content.replace(/<br[^>]*>/g, "");
    for (let i = 0; i < editorInputs.length; i++)
        content = content.replace(/<input[^>]*>/, editorInputs[i].value);
    result.src = encodeURI(content);
}

let hamburger = document.querySelector("header .hamburger");
let nav = document.querySelector("nav");
let hamburgerTriggered = false;

hamburger.addEventListener("click", () => {
    if(nav.style.display === "block") {
        nav.style.display = "none";
        hamburgerTriggered = false;
    }
    else {
        nav.style.display = "block";
        hamburgerTriggered = true;
    }
});

window.addEventListener("resize", () => {
    if(window.innerWidth > 767) {
        nav.style.display = "block";
        hamburgerTriggered = 0;
    }
    else
    {
        if(!hamburgerTriggered) {
            nav.style.display = "none";
        }
    }
});