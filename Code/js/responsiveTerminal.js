let terminals = [];

//
const startTerminal = (id) => {
  terminals[id] = {};
  load_from_file("code", id);
  loadScenario(id);
};

// fetch JSON scenario file and assign global variables
const loadScenario = (id) => {
  const fileName = "./resources/scenario" + id + ".json";
  fetch(fileName)
    .then((response) => response.json())
    .then((json) => {
      terminals[id].title = json.title;
      terminals[id].simulationState = json.simulationState;
      terminals[id].cols = json.cols;
      terminals[id].rows = json.rows;
      terminals[id].bgClassHeader = json.bgClassHeader;
      terminals[id].bgClassBody = json.bgClassBody;
      terminals[id].fontScale = json.fontScale;
      terminals[id].curState = "start";
      terminals[id].cmdHistory = [];
      terminals[id].cmdHistPtr = undefined;
      assignFinish(id);
    });
};

const load_from_file = (file_type, termId) => {
  $.get("resources/" + file_type + termId, function (data) {
    $.when(assign_from_File(data, file_type, termId)).then(assignFinish(termId));
  });
};

const assign_from_File = (data, file_type, termId) => {
  const fileContent = data;
  if (file_type == "code") {
    terminals[termId].code = fileContent.split(/\r\n|\n|\r/);
  }
};

const assignFinish = (termId) => {
  /*We only proceed with a terminal setup when code AND scenario files have been loaded */
  if (typeof terminals[termId].code != "undefined" && typeof terminals[termId].title != "undefined") {
    initTerminal(termId);
  }
  return true;
};

const initTerminal = (termId) => {
  setTermHtml(termId);
  // init termTitle from json
  document.getElementById("termTitleText" + termId).textContent = terminals[termId].title;

  setState("start", termId);

  /*init termBody with empty lines*/
  for (j = 0; j < terminals[termId].cols; j++) {
    document.getElementById("termBody" + termId).innerHTML += "&nbsp;<br>";
  }
  scroll_term_down(termId);

  document.getElementById("termTextArea" + termId).focus();

  // Set event listeners
  document.getElementById("termTextArea" + termId).addEventListener("keydown", (event) => {
    processCommand(event);
  });
  document.getElementById("termFullScreen" + termId).addEventListener("click", (event) => {
    toggleFullScreen(event);
  });
  document.getElementById("termBody" + termId).addEventListener("click", (event) => {
    const termId =
      event.target.localName === "pre"
        ? event.target.parentNode.id.replace("termBody", "")
        : event.target.id.replace("termBody", "");
    scroll_term_down(termId);
    document.getElementById("termTextArea" + termId).focus();
  });
};

const toggleFullScreen = (e) => {
  const termId = e.target.id.replace("termFullScreen", "");
  const elem = document.getElementById("terminal" + termId);
  if (!document.fullscreenElement) {
    elem.requestFullscreen().catch((err) => {
      console.log(`Error attempting to enable full-screen mode: ${err.message} (${err.name})`);
    });
  } else {
    document.exitFullscreen();
  }
};

const scroll_term_down = (id) => {
  const div = document.getElementById("termBody" + id);
  div.scrollTop = div.scrollHeight - div.clientHeight;
};

const processCommand = (event) => {
  const textAreaId = event.target.id;
  const id = textAreaId.replace("termTextArea", "");
  let ptr = 0;

  let keycode = event.keyCode ? event.keyCode : event.which;
  switch (keycode) {
    case 13: {
      /*only processing commands after pressing the Enter key*/
      let input = document.getElementById(textAreaId);

      /*capture user input and reset input field*/
      let command = input.value.replace(/^\s+/g, "");
      input.value = "";

      if (command != "") {
        appendCmdHistory(command, id);

        // Adding the command to the terminal Body
        appendOutput(terminals[id].curState.prompt + command, id);

        let matchFound = validCommand(command, id);

        if (!matchFound) {
          // Need to deal with feedback  message
          appendOutput(terminals[id].curState.errorMsg, id);
        }
      } else {
        if (terminals[id].curState.stateName !== "end") {
          appendOutput(terminals[id].curState.prompt + command, id);
        }
      }
      // in any case, reset the textarea
      break;
    }

    case 38:
      // arrow up
      if (terminals[id].cmdHistory.length > 0) {
        ptr = terminals[id].cmdHistoryPtr;
        const inputField = document.getElementById("termTextArea" + id);
        terminals[id].cmdHistoryPtr = ptr !== 0 ? ptr - 1 : 0;
        document.getElementById("termTextArea" + id).value = terminals[id].cmdHistory[ptr];
      }
      break;

    case 40:
      // arrow down
      if (terminals[id].cmdHistory.length > 0) {
        ptr = terminals[id].cmdHistoryPtr;
        terminals[id].cmdHistoryPtr = ptr < terminals[id].cmdHistory.length - 1 ? ptr + 1 : ptr;
        document.getElementById("termTextArea" + id).value = terminals[id].cmdHistory[ptr];
      }
      break;

    default:
      break;
  }
};

const appendCmdHistory = (text, id) => {
  terminals[id].cmdHistory.push(text);
  terminals[id].cmdHistoryPtr = terminals[id].cmdHistory.length - 1;
};

const appendOutput = (text, id) => {
  const termBody = document.getElementById("termBody" + id);
  termBody.innerHTML += "<pre>" + text + "</pre>";
  termBody.lastChild.style.margin = 0;
  termBody.lastChild.style.fontFamily = "Courier New";
  scroll_term_down(id);
};

const validCommand = (command, id) => {
  matchFound = false;
  terminals[id].curState.availableCommands.map((cmd) => {
    if (terminals[id].code[parseInt(cmd.commandLineId) - 1].replace(terminals[id].curState.prompt, "") === command) {
      // we have found a matching command and need to do something

      // Adding command output to termBody if needed
      let startEnum = parseInt(cmd.outputLineRange.split("-")[0]);
      let endEnum = parseInt(cmd.outputLineRange.split("-")[1]);
      let outputText = "";
      if (isNaN(endEnum)) {
        endEnum = startEnum;
      }
      for (i = startEnum; i <= endEnum; i++) {
        outputText = terminals[id].code[i - 1];
        appendOutput(outputText, id);
      }

      if (typeof cmd.nextState !== "undefined" && cmd.nextState) {
        // change state required
        setState(cmd.nextState, id);
      }

      matchFound = true;
    }
  });
  return matchFound;
};

const setState = (stateName, id) => {
  terminals[id].curState = getState(stateName, id);
  setPromptUI(id, terminals[id].curState.prompt);
  setTooltipUI(id, terminals[id].curState.tooltip);
  if (stateName === "end") {
    document.getElementById("termTextArea" + id).readOnly = true;
  }
};

const getState = (name, id) => {
  let simState = null;
  terminals[id].simulationState.map((state) => {
    if (state.stateName === name) {
      simState = state;
    }
  });
  return simState;
};

const setTooltipUI = (id, tooltip) => {
  document.getElementById("termToolTip" + id).innerHTML = tooltip;
};

const setPromptUI = (termId, prompt) => {
  document.getElementById("termPrompt" + termId).textContent = prompt;
  /* then adjust prompt + textarea css*/
  let prompt_len = prompt.length;
  let cmd_len = terminals[termId].cols - prompt_len;
  document.getElementById("termPrompt" + termId).style.maxWidth = prompt_len + "ch";
  document.getElementById("termPrompt" + termId).style.width = prompt_len + "ch";
  document.getElementById("termPrompt" + termId).style.minWidth = prompt_len + "ch";
  document.getElementById("termTextArea" + termId).style.width = cmd_len + "ch";
};

const setTermHtml = (id) => {
  const rows = terminals[id].rows;
  const cols = terminals[id].cols;
  const bgClassHeader = terminals[id].bgClassHeader;
  const bgClassBody = terminals[id].bgClassBody;
  const scale = terminals[id].fontScale;
  let fontScale = 1.5;
  switch (scale) {
    case "xxsmall":
      fontScale = 0.7;
      break;
    case "xsmall":
      fontScale = 0.9;
      break;
    case "small":
      fontScale = 1;
      break;
    case "medium":
      fontScale = 1.3;
      break;
    case "large":
      fontScale = 1.5;
      break;
    case "xlarge":
      fontScale = 1.7;
      break;
    case "xxlarge":
      fontScale = 2;
      break;
    default:
      fontScale = 1.3;
      break;
  }

  const promptLen = typeof prompt[id] != "undefined" && prompt[id] != null ? prompt[id].length : 10;
  // Html structure
  const termParent = document.getElementById("terminal" + id);

  const termTitle = document.createElement("div");
  termTitle.setAttribute("id", "termTitle" + id);

  const termTitleImg = document.createElement("img");
  termTitleImg.setAttribute("id", "termTitleImg" + id);
  termTitleImg.setAttribute("src", "../images/try-it.svg");
  termTitleImg.setAttribute("alt", "Terminal Simulation");

  const termTitleText = document.createElement("div");
  termTitleText.setAttribute("id", "termTitleText" + id);

  const termFullScreen = document.createElement("img");
  termFullScreen.setAttribute("id", "termFullScreen" + id);
  termFullScreen.setAttribute("src", "../images/fullScreen.svg");
  termFullScreen.setAttribute("alt", "Full Screen Toggle");

  const termBody = document.createElement("div");
  termBody.setAttribute("id", "termBody" + id);

  const termInput = document.createElement("div");
  termInput.setAttribute("id", "termInput" + id);

  const termPrompt = document.createElement("div");
  termPrompt.setAttribute("id", "termPrompt" + id);

  const termTextArea = document.createElement("input");
  termTextArea.setAttribute("id", "termTextArea" + id);
  termTextArea.setAttribute("autocomplete", "off");

  const termToolTip = document.createElement("div");
  termToolTip.setAttribute("id", "termToolTip" + id);

  termInput.appendChild(termPrompt);
  termInput.appendChild(termTextArea);
  termTitle.appendChild(termTitleImg);
  termTitle.appendChild(termTitleText);
  termTitle.appendChild(termFullScreen);
  termParent.appendChild(termTitle);
  termParent.appendChild(termBody);
  termParent.appendChild(termInput);
  termParent.appendChild(termToolTip);

  termParent.style.display = "flex";
  termParent.style.flexDirection = "column";
  termParent.style.width = cols + "ch";
  termParent.style.background = "#FFF";
  termParent.style.overflow = "hidden";
  termParent.style.font = "normal " + fontScale + 'vw "Courier New"';
  termParent.style.padding = 0;
  termParent.style.margin = 0;
  termParent.style.borderTop = "2px solid #555";

  termTitle.style.display = "flex";
  termTitle.style.flexFlow = "row nowrap";
  termTitle.setAttribute("class", bgClassHeader);
  termTitle.style.justifyContent = "space-between";

  termTitleImg.style.display = "inline-block";
  termTitleImg.style.height = "1.5em";

  termFullScreen.style.display = "inline-block";
  termFullScreen.style.height = "1.5em";
  termFullScreen.style.cursor = "pointer";

  termTitleText.style.font = "normal " + fontScale + 'vw "Courier New"';
  termTitleText.style.lineHeight = "1.5em";

  termBody.setAttribute("class", bgClassBody);
  termBody.style.height = rows + "em";
  termBody.style.lineHeight = "1em";
  termBody.style.overflowY = "auto";
  termBody.style.font = "normal " + fontScale + 'vw "Courier New"';
  termBody.style.margin = 0;
  termBody.style.overflowX = "hidden";

  termInput.style.display = "flex";
  termInput.style.flexFlow = "row nowrap";
  termInput.setAttribute("class", bgClassBody);
  termInput.style.margin = 0;
  termInput.style.lineHeight = "1em";
  termInput.style.maxHeight = "1em";
  termInput.style.font = "normal " + fontScale + 'vw "Courier New"';
  termInput.style.borderBottom = "2px solid #555";
  termInput.spellcheck = false;

  termPrompt.style.maxWidth = promptLen + "ch";
  termPrompt.style.minWidth = promptLen + "ch";
  termPrompt.style.width = promptLen + "ch";
  termPrompt.style.lineHeight = "1em";
  termPrompt.style.font = "normal " + fontScale + 'vw "Courier New"';

  termTextArea.style.padding = 0;
  termTextArea.style.border = 0;
  termTextArea.setAttribute("class", bgClassBody);
  termTextArea.style.overflowY = "hidden";
  termTextArea.style.font = "normal " + fontScale + 'vw "Courier New"';
  termTextArea.style.outline = "none";
  termTextArea.style.resize = "none";
  termTextArea.style.lineHeight = "1em";
  termTextArea.style.width = cols - promptLen + "ch";

  termToolTip.setAttribute("class", bgClassHeader);
  termToolTip.style.marginTop = "2em";
  termToolTip.style.minHeight = "5em";
  termToolTip.style.font = "normal 1em MetricHPE-Web-Regular";

  termToolTip.innerHTML =
    "Welcome to this terminal simulation. <br>Click in the terminal area and press Enter to get started.";
};
