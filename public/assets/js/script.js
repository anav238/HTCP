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
    Function to show page when content is loaded (called in some fetch() functions)
*/
let loaded = false;

function showElements() {
    if(!loaded) {
        document.querySelector("main").classList.remove("loading");
        document.querySelector("main").classList.remove("partial-loading");
        loaded = true;
    }
}

function hideElements() {
    if(loaded) {
        document.querySelector("main").classList.add("partial-loading");
        loaded = false;

        if(hamburgerTriggered && window.innerWidth < 768) {
            nav.style.display = "none";
            hamburgerTriggered = false;
            hamburger.classList.remove("hamburger-active");
        }
    }
}

function popup(title, text, exercise = null) {
    document.body.insertAdjacentHTML("afterbegin", "<div class=\"popup\"><div class=\"popup-container\"><div class=\"popup-container-header\">" + title + "</div><div class=\"popup-container-text\">" + text + "</div><div class=\"popup-container-footer\"><a class=\"button button-blue\" id=\"close\">Close</a></div></div></div>");
    let popup = document.querySelector(".popup");
    let buttons = popup.querySelector(".popup .popup-container .popup-container-footer");
    let closeButton = buttons.querySelector("#close");

    closeButton.addEventListener("click", () => {
        popup.style.display = "none";
        popup.remove();
    });

    if(exercise) {
        buttons.insertAdjacentHTML("afterbegin", "<div class=\"button button-green\" id=\"next\">Next level</a>");
        let nextButton = buttons.querySelector("#next");
        nextButton.addEventListener("click", () => {
            loadExercise(exercise, true, "pushState");
            popup.style.display = "none";
            popup.remove();
        });
    }

    popup.style.display = "flex";
}

/*
    Running when on root
*/
if(window.location.pathname === "/") {
    window.location.href = "/html";
}

/*
    Running when on a HTML level (the URI contains "html")
*/
if(window.location.pathname.includes("html")) {
    let editor = document.querySelector(".codeArea code");
    let title = document.querySelector(".instructions span");
    let instructions = document.querySelector(".instructions p");
    
    let editorInputs = editor.getElementsByTagName("input");
    let result = document.querySelector("iframe").contentWindow.document;

    let highestLevel = 0;
    let currentLevel = 0;
    let levelId;

    // Refresh the result area
    function refreshResult() {
        let content = editor.innerHTML;
        for (let i = 0; i < editorInputs.length; i++)
            content = content.replace(/<input[^>]*>/, editorInputs[i].value);
        content = content.replace(/<br[^>]*>/g, "")
            .replace(/&lt;/g, "<")
            .replace(/&gt;/g, ">")
            .replace(/<form/g, "<form onsubmit=\"return false;\"")
            .replace(/img src="/g, "img src=\"" + window.location.protocol + "//" + window.location.host + "/public/assets/img/levels/")
            .replace(/url\('/g, "url('" + window.location.protocol + "//" + window.location.host + "/public/assets/img/levels/");
        result.open();
        result.writeln(content);
        result.close();
    }
    editor.addEventListener("keyup", refreshResult);

    // Showing level info
    function loadExercise(exercise, changeActiveButton = false, state = "unset") {
        levelId = exercise.id;
        currentLevel = exercise.level;
        if(changeActiveButton)
            document.querySelector("nav h1 + ul a.button-active").classList.remove("button-active");
        document.querySelector("nav h1 + ul a[href*=html\\/" + exercise.level + "]").classList.add("button-active");
        let newPageTitle = "HTML Level " + exercise.level + " - HTML & CSS Adventure";
        if(state === "replaceState") {
            window.history.replaceState({
                type: "HTML",
                level: exercise.level
            }, newPageTitle, "/html/" + exercise.level);
            document.title = newPageTitle;
        }
        else if(state === "pushState") {
            window.history.pushState({
                type: "HTML",
                level: exercise.level
            }, newPageTitle, "/html/" + exercise.level);
            document.title = newPageTitle;
        }
        title.innerHTML = exercise.level;
        instructions.innerHTML = exercise.description;
        editor.innerHTML = exercise.problem;
        refreshResult();
    }

    // Loading levels
    fetch('/api/exercises?type=html')
        .then(response => response.json())
        .then(data => {
            // Getting highest unlocked number and storing indexes of levels
            let exercises = [], exercise = {};
            for(let i in data) {
                exercises[data[i].level] = i;
                if(highestLevel < parseInt(data[i].level))
                    highestLevel = data[i].level;
            }

            // Placing links for all the unlocked levels
            let levelList = document.querySelector("nav h1 + ul");
            for(let i = 1; i <= highestLevel; i++)
                levelList.insertAdjacentHTML("beforeend", "<li><a href=\"/html/" + i + "\" class=\"button button-green\">Level " + i + "</a></li>");
            
            // Getting level number from URI
            let requestedLevel;
            if(window.location.pathname.match(/\d+/g))
                requestedLevel = window.location.pathname.match(/\d+/g)[0];
            else
                requestedLevel = highestLevel;
            
            // Copying object to make it easier for us to do any other things
            exercise = data[exercises[requestedLevel]];

            // Showing the requested level page
            loadExercise(exercise, false, "replaceState");

            // Adding events when clicking a level link
            let a = document.querySelectorAll("nav h1 + ul a[href*=html]");
            for(let i = 0; i < a.length; i++) {
                let levelNumber = a[i].getAttribute("href").substring(6);
                a[i].addEventListener("click", (e) => {
                    hideElements();
                    fetch('/api/exercises?type=html&level=' + levelNumber)
                        .then(response => response.json())
                        .then(data => {
                            loadExercise(data, true, "pushState");
                            showElements();
                        });
                    e.preventDefault();
                });
            }

            // Unhide loaded content
            showElements();
        });
        
    // Loads a level after pressing back/forward buttons in browser
    window.addEventListener('popstate', ()  => {
        if(history.state.type === "HTML") {
            hideElements();
            fetch('/api/exercises?type=html&level=' + history.state.level)
                .then(response => response.json())
                .then(data => {
                    loadExercise(data, true);
                    showElements();
                });
        }
    }, false);

    // Submitting a solution
    let button = document.getElementById("submit");
    button.addEventListener('click', submitSolution);
    function submitSolution() {
        button.removeEventListener('click', submitSolution);
        submit.innerHTML = "Loading...";
        submit.classList.add("button-loading");
        let lastLevelLink = document.querySelector("nav h1 + ul li:last-child a[href*=html]");
        let lastLevelNumber = lastLevelLink.getAttribute("href").substring(6);
        let levelList = document.querySelector("nav h1 + ul");

        let answer = { solution: [] };
        for(let i = 0; i < editorInputs.length; i++)
            answer.solution.push(editorInputs[i].value);
        fetch('/api/exercises?id=' + levelId, {
            method: "POST",
            body: JSON.stringify(answer)
        })
        .then(response => response.json())
        .then(data => {
            if(data.reason === "Success!") {
                if(currentLevel != highestLevel) {
                    submit.innerHTML = "Submit";
                    submit.classList.remove("button-loading");
                    button.addEventListener('click', submitSolution);
                    popup("Hooray!", "The submitted solution is correct!");
                }
                else {
                    fetch('/api/exercises/html/current')
                        .then(response => response.json())
                        .then(data => {
                            if(lastLevelNumber != data.level) {
                                highestLevel++;
                                levelList.insertAdjacentHTML("beforeend", "<li><a href=\"/html/" + data.level + "\" class=\"button button-green\">Level " + data.level + "</a></li>");
                                lastLevelLink = document.querySelector("nav h1 + ul li:last-child a[href*=html]");
                                lastLevelLink.addEventListener("click", (e) => {
                                    hideElements();
                                    fetch('/api/exercises?type=html&level=' + data.level)
                                        .then(response => response.json())
                                        .then(data => {
                                            loadExercise(data, true, "pushState");
                                            showElements();
                                        });
                                    e.preventDefault();
                                });
                                submit.innerHTML = "Submit";
                                submit.classList.remove("button-loading");
                                button.addEventListener('click', submitSolution);
                                popup("Hooray!", "The submitted solution is correct!<br /><i>Next level is now unlocked.</i>", data);
                            }
                            else {
                                submit.innerHTML = "Submit";
                                submit.classList.remove("button-loading");
                                button.addEventListener('click', submitSolution);
                                popup("Hooray!", "The submitted solution is correct!<br /><i>You finished all the levels.</i>");
                            }
                        });
                }
            }
            else if(data.reason === "Wrong solution!") {
                submit.innerHTML = "Submit";
                submit.classList.remove("button-loading");
                button.addEventListener('click', submitSolution);
                popup("Oh no!", "The submitted solution is wrong!");
            }
        });
    }
}

/*
    Running when on a CSS level (the URI contains "css")
*/
if(window.location.pathname.includes("css")) {
    let editor = document.querySelector(".codeArea code");
    let title = document.querySelector(".instructions span");
    let instructions = document.querySelector(".instructions p");
    
    let editorInputs = editor.getElementsByTagName("input");
    let result = document.querySelector("iframe").contentWindow.document;

    let highestLevel = 0;
    let currentLevel = 0;
    let levelId;

    let resultHTML = ""; 

    // Refresh the result area
    function refreshResult() {
        let content = "<style>" + editor.innerHTML + "</style>" + resultHTML;
        for (let i = 0; i < editorInputs.length; i++)
            content = content.replace(/<input[^>]*>/, editorInputs[i].value);
        content = content.replace(/<br[^>]*>/g, "")
            .replace(/&lt;/g, "<")
            .replace(/&gt;/g, ">")
            .replace(/img src="/g, "img src=\"" + window.location.protocol + "//" + window.location.host + "/public/assets/img/levels/")
            .replace(/url\('/g, "url('" + window.location.protocol + "//" + window.location.host + "/public/assets/img/levels/");
        result.open();

        result.writeln(content);
        
        let extraCSS = result.createElement("link");
        extraCSS.href = window.location.protocol + "//" + window.location.host + "/public/assets/css/levels.css";
        extraCSS.rel = "stylesheet";
        extraCSS.type = "text/css";
        result.head.insertBefore(extraCSS, result.head.childNodes[0]);

        result.close();
    }
    editor.addEventListener("keyup", refreshResult);

    // Showing level info
    function loadExercise(exercise, changeActiveButton = false, state = "unset") {
        levelId = exercise.id;
        currentLevel = exercise.level;
        if(changeActiveButton)
            document.querySelector("nav h1 + ul a.button-active").classList.remove("button-active");
        document.querySelector("nav h1 + ul a[href*=css\\/" + exercise.level + "]").classList.add("button-active");
        let newPageTitle = "CSS Level " + exercise.level + " - HTML & CSS Adventure";
        if(state === "replaceState") {
            window.history.replaceState({
                type: "CSS",
                level: exercise.level
            }, newPageTitle, "/css/" + exercise.level);
            document.title = newPageTitle;
        }
        else if(state === "pushState") {
            window.history.pushState({
                type: "CSS",
                level: exercise.level
            }, newPageTitle, "/css/" + exercise.level);
            document.title = newPageTitle;
        }
        resultHTML = exercise.extraHTML;
        title.innerHTML = exercise.level;
        instructions.innerHTML = exercise.description;
        editor.innerHTML = exercise.problem;
        refreshResult();
    }

    // Loading levels
    fetch('/api/exercises?type=css')
        .then(response => response.json())
        .then(data => {
            // Getting highest unlocked number and storing indexes of levels
            let exercises = [], exercise = {};
            for(let i in data) {
                exercises[data[i].level] = i;
                if(highestLevel < parseInt(data[i].level))
                    highestLevel = data[i].level;
            }

            // Placing links for all the unlocked levels
            let levelList = document.querySelector("nav h1 + ul");
            for(let i = 1; i <= highestLevel; i++)
                levelList.insertAdjacentHTML("beforeend", "<li><a href=\"/css/" + i + "\" class=\"button button-red\">Level " + i + "</a></li>");
            
            // Getting level number from URI
            let requestedLevel;
            if(window.location.pathname.match(/\d+/g))
                requestedLevel = window.location.pathname.match(/\d+/g)[0];
            else
                requestedLevel = highestLevel;
            
            // Copying object to make it easier for us to do any other things
            exercise = data[exercises[requestedLevel]];

            // Showing the requested level page
            loadExercise(exercise, false, "replaceState");

            // Adding events when clicking a level link
            let a = document.querySelectorAll("nav h1 + ul a[href*=css]");
            for(let i = 0; i < a.length; i++) {
                let levelNumber = a[i].getAttribute("href").substring(5);
                a[i].addEventListener("click", (e) => {
                    hideElements();
                    fetch('/api/exercises?type=css&level=' + levelNumber)
                        .then(response => response.json())
                        .then(data => {
                            loadExercise(data, true, "pushState");
                            showElements();
                        });
                    e.preventDefault();
                });
            }

            // Unhide loaded content
            showElements();
        });
        
    // Loads a level after pressing back/forward buttons in browser
    window.addEventListener('popstate', ()  => {
        if(history.state.type === "CSS") {
            hideElements();
            fetch('/api/exercises?type=css&level=' + history.state.level)
                .then(response => response.json())
                .then(data => {
                    loadExercise(data, true);
                    showElements();
                });
        }
    }, false);

    // Submitting a solution
    let button = document.getElementById("submit");
    button.addEventListener('click', submitSolution);
    function submitSolution() {
        button.removeEventListener('click', submitSolution);
        submit.innerHTML = "Loading...";
        submit.classList.add("button-loading");
        let lastLevelLink = document.querySelector("nav h1 + ul li:last-child a[href*=css]");
        let lastLevelNumber = lastLevelLink.getAttribute("href").substring(5);
        let levelList = document.querySelector("nav h1 + ul");

        let answer = { solution: [] };
        for(let i = 0; i < editorInputs.length; i++)
            answer.solution.push(editorInputs[i].value);
        fetch('/api/exercises?id=' + levelId, {
            method: "POST",
            body: JSON.stringify(answer)
        })
        .then(response => response.json())
        .then(data => {
            if(data.reason === "Success!") {
                if(currentLevel != highestLevel) {
                    submit.innerHTML = "Submit";
                    submit.classList.remove("button-loading");
                    button.addEventListener('click', submitSolution);
                    popup("Hooray!", "The submitted solution is correct!");
                }
                else {
                    fetch('/api/exercises/css/current')
                        .then(response => response.json())
                        .then(data => {
                            if(lastLevelNumber != data.level) {
                                highestLevel++;
                                levelList.insertAdjacentHTML("beforeend", "<li><a href=\"/css/" + data.level + "\" class=\"button button-red\">Level " + data.level + "</a></li>");
                                lastLevelLink = document.querySelector("nav h1 + ul li:last-child a[href*=css]");
                                lastLevelLink.addEventListener("click", (e) => {
                                    hideElements();
                                    fetch('/api/exercises?type=css&level=' + data.level)
                                        .then(response => response.json())
                                        .then(data => {
                                            loadExercise(data, true, "pushState");
                                            showElements();
                                        });
                                    e.preventDefault();
                                });
                                submit.innerHTML = "Submit";
                                submit.classList.remove("button-loading");
                                button.addEventListener('click', submitSolution);
                                popup("Hooray!", "The submitted solution is correct!<br /><i>Next level is now unlocked.</i>", data);
                            }
                            else {
                                submit.innerHTML = "Submit";
                                submit.classList.remove("button-loading");
                                button.addEventListener('click', submitSolution);
                                popup("Hooray!", "The submitted solution is correct!<br /><i>You finished all the levels.</i>");
                            }
                        });
                }
            }
            else if(data.reason === "Wrong solution!") {
                submit.innerHTML = "Submit";
                submit.classList.remove("button-loading");
                button.addEventListener('click', submitSolution);
                popup("Oh no!", "The submitted solution is wrong!");
            }
        });
    }
}

/*
    Running when on leaderboard page (the URI contains "leaderboard")
*/
if(window.location.pathname.includes("leaderboard")) {
    Promise.all([
        fetch('/api/users/correctnessLeaderboard'),
        fetch('/api/users/speedLeaderboard')
    ])
        .then(responses => Promise.all(responses.map(response => response.json())))
        .then(data => {
            // Correctness leaderboard
            let correctnessTable = document.querySelector(".right .leaderboard div:nth-child(1) table tbody");
            for(let i in data[0]) {
                let rank = parseInt(i) + 1;
                correctnessTable.insertAdjacentHTML("beforeend", "<tr>\
                <td>" + rank + "</td>\
                <td><img src=\"" + data[0][i].avatar + "\" alt=\"" + data[0][i].username + "'s avatar\" />" + data[0][i].username + "</td>\
                <td>" + data[0][i].correctness_score + "</td>\
                </tr>");
            }
            // Speed leaderboard
            let speedTable = document.querySelector(".right .leaderboard div:nth-child(2) table tbody");
            for(let i in data[1]) {
                let rank = parseInt(i) + 1;
                speedTable.insertAdjacentHTML("beforeend", "<tr>\
                <td>" + rank + "</td>\
                <td><img src=\"" + data[1][i].avatar + "\" alt=\"" + data[1][i].username + "'s avatar\" />" + data[1][i].username + "</td>\
                <td>" + data[1][i].speed_score + "</td>\
                </tr>");
            }
            showElements();
        });
}

/*
    Running when on profile page (the URI contains "profile")
*/
if(window.location.pathname.includes("profile")) {
    fetch('/api/users/ping')
        .then(response => response.json())
        .then(data => {
            document.title = data.username + " - HTML & CSS Adventure";
            document.querySelector(".right .profileData img").src = data.avatar;
            document.querySelector(".right .profileData h1").innerHTML = data.username;
            document.querySelector(".right .profileData .stats div:nth-child(1) span").innerHTML = data.html_level;
            document.querySelector(".right .profileData .stats div:nth-child(2) span").innerHTML = data.css_level;
            document.querySelector(".right .profileData .stats div:nth-child(3) span").innerHTML = data.speed_score;
            document.querySelector(".right .profileData .stats div:nth-child(4) span").innerHTML = data.correctness_score;
            showElements();
        });
}