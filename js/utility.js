"use strict";
function genCloseButton() {
    var button = document.createElement("button");
    button.classList.add("button-close");
    var span = document.createElement("span");
    span.textContent = "x";
    span.style.position = "relative";
    span.style.top = "-2px";
    button.append(span);
    $(button).on("click", function (e) {
        var _a;
        (_a = this.parentElement) === null || _a === void 0 ? void 0 : _a.remove();
    });
    return button;
}
function askForConfirmation(question, onConfirm, param) {
    var window = document.createElement("div");
    window.classList.add("confirmation-window");
    $(window).on("mousewheel", function (e) {
        e.preventDefault();
    });
    var wrapper = document.createElement("div");
    wrapper.style.backgroundColor = "#fff";
    var span = document.createElement("span");
    span.textContent = question;
    wrapper.append(span);
    var buttonsWrapper = document.createElement("div");
    var buttonConfirm = document.createElement("button");
    var buttonCancel = document.createElement("button");
    buttonConfirm.textContent = "Yes";
    $(buttonConfirm).addClass("btn btn-success btn-sm");
    buttonCancel.textContent = "No";
    $(buttonCancel).addClass("btn btn-danger btn-sm");
    $(buttonCancel).on("click", function (e) {
        window.remove();
    });
    $(buttonConfirm).on("click", function (e) {
        onConfirm(param);
        window.remove();
    });
    buttonsWrapper.append(buttonConfirm);
    buttonsWrapper.append(buttonCancel);
    wrapper.append(buttonsWrapper);
    window.append(wrapper);
    document.body.append(window);
}
function calcTextWidth(s, element) {
    var div = document.createElement("div");
    div.style.fontSize = element.style.fontSize;
    div.style.fontStyle = element.style.fontStyle;
    div.style.fontWeight = element.style.fontWeight;
    div.textContent = s;
    div.style.margin = "0";
    div.style.padding = "0";
    div.style.width = "fit-content";
    div.style.position = "absolute";
    div.style.backgroundColor = "red";
    div.style.left = "-1000px";
    div.style.top = "-1000px";
    $(div).insertAfter(element);
    var width = div.clientWidth;
    div.remove();
    return width;
}
function animateStar(e, dir, callback) {
    var tick = 10;
    var originX = parseInt(e.style.left);
    var originY = parseInt(e.style.top);
    var distance = Math.floor(Math.random() * 20) + 10;
    var height = Math.random() * 2 + 1;
    var direction = (dir == undefined ? Math.floor(Math.random() * 2) : dir % 2);
    var x = 0.0;
    var y = 0.0;
    var sinusCurve = setInterval(function () {
        x++;
        y = Math.sin(x / distance) * distance * height;
        y = Math.floor(y);
        e.style.top = originY - y + "px";
        if (direction == 0)
            e.style.left = originX + x + "px";
        else
            e.style.left = originX - x + "px";
        if ((x / distance) > Math.PI) {
            clearInterval(sinusCurve);
            if (direction == 0)
                $(e).animate({ top: "+=80", left: "+=35", opacity: 0 }, 350, function () { this.remove(); });
            else
                $(e).animate({ top: "+=80", left: "-=35", opacity: 0 }, 350, function () { this.remove(); });
        }
    }, tick);
}
function shootStars(x, y, position) {
    if (position === void 0) { position = "absolute"; }
    if (position != "absolute" && position != "fixed") {
        throw "False position input parameter.";
    }
    var stars = Array();
    for (var i = 0; i < 6; i++) {
        stars.push(document.createElement("i"));
        var s = stars[i];
        s.classList.add("bi");
        s.classList.add("bi-star-fill");
        s.style.color = "#fcc111";
        s.style.position = position;
        s.style.top = y + "px";
        s.style.left = x + "px";
        s.style.zIndex = 100 + "";
        document.body.append(s);
        animateStar(s, i);
    }
}
