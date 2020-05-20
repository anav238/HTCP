/*
    Hamburger navigation for small screens (mobile devices),
    available to all pages
*/
let hamburger = document.querySelector("header .hamburger");
let nav = document.querySelector("nav");
let hamburgerTriggered = false;

hamburger.addEventListener("click", () => {
    if(nav.style.display === "block") {
        nav.style.display = "none";
        hamburgerTriggered = false;
        hamburger.classList.remove("hamburger-active");
    }
    else {
        nav.style.display = "block";
        hamburgerTriggered = true;
        hamburger.classList.add("hamburger-active");
    }
});

window.addEventListener("resize", () => {
    if(window.innerWidth > 767) {
        if(!nav.classList.contains('nav-profile'))
            nav.style.display = "block";
        hamburgerTriggered = 0;
        hamburger.classList.remove("hamburger-active");
    }
    else
    {
        if(!hamburgerTriggered) {
            nav.style.display = "none";
            hamburger.classList.remove("hamburger-active");
        }
    }
});

/*
    Running when on root
*/
if(window.location.pathname === "/") {
    fetch('/api/exercises/html/current')
        .then(response => response.json())
        .then(data => {
            window.location.href = "/html/" + data.level;
        });
}

/*
    Running when on a HTML level (the URI contains "html")
*/
if(window.location.pathname.includes("html")) {
    let loaded = 0;

    let editor = document.querySelector(".codeArea code");
    let title = document.querySelector(".instructions span");
    let instructions = document.querySelector(".instructions p");
    
    let editorInputs = editor.getElementsByTagName("input");
    let result = document.querySelector("iframe");
    let button = document.getElementById("submit");

    // Shows page elements after fetch loads
    function showElements() {
        if(!loaded) {
            document.querySelector("main").classList.remove("loading");
            loaded = 1;
        }
    }

    // Loads the level list and add events when clicking a level button
    let levelList = document.querySelector("nav h1 + ul");
    fetch('/api/exercises/html/current')
        .then(response => response.json())
        .then(data => {
            for(let i = 1; i <= data.level; i++) {
                levelList.insertAdjacentHTML("beforeend", "<li><a href=\"/html/" + i + "\" class=\"button button-green\">Level " + i + "</a></li>")
            }
            let a = document.querySelectorAll("nav h1 + ul a[href*=html]");
            for(let i = 0; i < a.length; i++) {
                let levelNumber = a[i].getAttribute("href").substring(6);
                a[i].addEventListener("click", (e) => {
                    fetch('/api/exercises?type=html&level=' + levelNumber)
                        .then(response => response.json())
                        .then(data => {
                            let newPageTitle = "HTML Level " + data.level + " - HTML & CSS Adventure";
                            window.history.pushState({
                                type: "HTML",
                                level: data.level
                            }, newPageTitle, "/html/" + data.level);
                            document.title = newPageTitle;
                            document.querySelector("nav h1 + ul a.button-active").classList.remove("button-active");
                            a[i].classList.add("button-active");
                            title.innerHTML = data.level;
                            instructions.innerHTML = data.description;
                            editor.innerHTML = data.problem;
                            refreshResult();
                        });
                    e.preventDefault();
                });
            }
        });
    
    // Refresh the result area
    function refreshResult() {
        let content = "data:text/html;charset=utf-8," + editor.innerHTML;
        content = content.replace(/<br[^>]*>/g, "");
        for (let i = 0; i < editorInputs.length; i++)
            content = content.replace(/<input[^>]*>/, editorInputs[i].value);
        result.contentWindow.location.replace(encodeURI(content));
    }
    editor.addEventListener("keyup", refreshResult);

    // Getting level number from URI
    let levelNumber;
    if(window.location.pathname.match(/\d+/g))
        levelNumber = window.location.pathname.match(/\d+/g)[0];
    else
        levelNumber = null;

    // Loads the level from URI, if it's not specified, loads user's current level
    if(levelNumber) {
        fetch('/api/exercises?type=html&level=' + levelNumber)
            .then(response => response.json())
            .then(data => {
                let newPageTitle = "HTML Level " + data.level + " - HTML & CSS Adventure";
                window.history.replaceState({
                    type: "HTML",
                    level: data.level
                }, newPageTitle, "/html/" + data.level);
                document.title = newPageTitle;
                document.querySelector("nav h1 + ul a[href*=html\\/" + data.level + "]").classList.add("button-active");
                title.innerHTML = data.level;
                instructions.innerHTML = data.description;
                editor.innerHTML = data.problem;
                refreshResult();
                showElements();
            });
    }
    else {
        fetch('/api/exercises/html/current')
            .then(response => response.json())
            .then(data => {
                let newPageTitle = "HTML Level " + data.level + " - HTML & CSS Adventure";
                window.history.replaceState({
                    type: "HTML",
                    level: data.level
                }, newPageTitle, "/html/" + data.level);
                document.title = newPageTitle;
                document.querySelector("nav h1 + ul a[href*=html\\/" + data.level + "]").classList.add("button-active");
                title.innerHTML = data.level;
                instructions.innerHTML = data.description;
                editor.innerHTML = data.problem;
                refreshResult();
                showElements();
            });
    }
        
    // Loads a level after pressing back/forward buttons in browser
    window.addEventListener('popstate', ()  => {
        if(history.state.type === "HTML") {
            fetch('/api/exercises?type=html&level=' + history.state.level)
                .then(response => response.json())
                .then(data => {
                    document.querySelector("nav h1 + ul a.button-active").classList.remove("button-active");
                    document.querySelector("nav h1 + ul a[href*=html\\/" + data.level + "]").classList.add("button-active");
                    title.innerHTML = data.level;
                    instructions.innerHTML = data.description;
                    editor.innerHTML = data.problem;
                    refreshResult();
                });
        }
    }, false);
}