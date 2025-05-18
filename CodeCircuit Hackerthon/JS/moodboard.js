let moodItems = JSON.parse(
  localStorage.getItem("moodboardItems") || "[]"
);
let draggingId = null,
  offsetX = 0,
  offsetY = 0,
  addNoteActive = false;
let resizingId = null,
  resizeStart = null;
const moodboard = document.getElementById("moodboard");
document.addEventListener("paste", async (e) => {
  const items = (e.clipboardData || window.clipboardData).items;
  for (const item of items) {
    if (item.type.indexOf("image") === 0) {
      const blob = item.getAsFile();
      const reader = new FileReader();
      reader.onload = function (evt) {
        moodItems.push({
          type: "img",
          src: evt.target.result,
          x: moodboard.scrollWidth / 2 - 100,
          y: moodboard.scrollHeight / 2 - 75,
          width: 200,
          height: 150,
        });
        saveAndRender();
      };
      reader.readAsDataURL(blob);
    }
  }
});

async function downloadMoodboard() {
  document
    .querySelectorAll(".remove-btn,.resize-handle")
    .forEach((btn) => (btn.style.display = "none"));
  const canvas = await html2canvas(moodboard, {
    useCORS: true,
    allowTaint: true,
    scrollX: -window.scrollX,
    scrollY: -window.scrollY,
    windowWidth: moodboard.scrollWidth,
    windowHeight: moodboard.scrollHeight,
  });
  const link = document.createElement("a");
  link.download = "moodboard.png";
  link.href = canvas.toDataURL("image/png");
  link.click();
  document
    .querySelectorAll(".remove-btn,.resize-handle")
    .forEach((btn) => (btn.style.display = ""));
}

function saveAndRender() {
  localStorage.setItem("moodboardItems", JSON.stringify(moodItems));
  renderMoodboard();
}

function renderMoodboard() {
  moodboard.innerHTML = "";
  moodItems.forEach((item, idx) => {
    const el = document.createElement("div");
    el.className = "mood-item";
    el.style.left = (item.x || 40) + "px";
    el.style.top = (item.y || 40) + "px";
    el.setAttribute("data-id", idx);

    let content = "";
    if (item.type === "img") {
      const width = item.width || 200;
      const height = item.height || 150;
      content = `
            <img src="${item.src}" class="mood-img" style="width:${width}px;height:${height}px;">
            <div class="resize-handle" onmousedown="startResize(event,${idx})">↘</div>
          `;
    } else if (item.type === "sticker") {
      content = `<span class="mood-sticker">${item.sticker}</span>`;
    } else if (item.type === "note") {
      content = `<textarea class="mood-note" oninput="updateNote(${idx}, this.value)">${item.text || ""
        }</textarea>`;
    }
    el.innerHTML = `
          <button class="remove-btn" onclick="removeMoodItem(${idx})">×</button>
          ${content}
        `;
    el.onmousedown = dragStart;
    moodboard.appendChild(el);
  });
}

function dragStart(e) {
  if (
    e.target.classList.contains("remove-btn") ||
    e.target.classList.contains("mood-note") ||
    e.target.classList.contains("resize-handle")
  )
    return;
  draggingId = parseInt(this.getAttribute("data-id"));
  const rect = this.getBoundingClientRect();
  offsetX = e.clientX - rect.left;
  offsetY = e.clientY - rect.top;
  document.onmousemove = dragMove;
  document.onmouseup = dragEnd;
}

function dragMove(e) {
  if (draggingId === null) return;
  const boardRect = moodboard.getBoundingClientRect();
  let x = e.clientX - boardRect.left - offsetX;
  let y = e.clientY - boardRect.top - offsetY;
  x = Math.max(0, Math.min(x, moodboard.offsetWidth - 40));
  y = Math.max(0, Math.min(y, moodboard.offsetHeight - 40));
  moodItems[draggingId].x = x;
  moodItems[draggingId].y = y;
  saveAndRender();
}

function dragEnd() {
  draggingId = null;
  document.onmousemove = null;
  document.onmouseup = null;
}

window.startResize = function (e, idx) {
  e.stopPropagation();
  resizingId = idx;
  const item = moodItems[idx];
  resizeStart = {
    mouseX: e.clientX,
    mouseY: e.clientY,
    width: item.width || 200,
    height: item.height || 150,
  };
  document.onmousemove = doResize;
  document.onmouseup = stopResize;
};
function doResize(e) {
  if (resizingId === null) return;
  const item = moodItems[resizingId];
  let newWidth = Math.max(
    40,
    resizeStart.width + (e.clientX - resizeStart.mouseX)
  );
  let newHeight = Math.max(
    30,
    resizeStart.height + (e.clientY - resizeStart.mouseY)
  );
  item.width = newWidth;
  item.height = newHeight;
  saveAndRender();
}
function stopResize() {
  resizingId = null;
  document.onmousemove = null;
  document.onmouseup = null;
}

document
  .getElementById("imgUpload")
  .addEventListener("change", function (e) {
    const file = e.target.files[0];
    if (!file) return;
    const reader = new FileReader();
    reader.onload = function (evt) {
      moodItems.push({
        type: "img",
        src: evt.target.result,
        x: moodboard.scrollWidth / 2 - 100,
        y: moodboard.scrollHeight / 2 - 75,
        width: 200,
        height: 150,
      });
      saveAndRender();
    };
    reader.readAsDataURL(file);
    this.value = "";
  });

document.querySelectorAll(".sticker").forEach((sticker) => {
  sticker.addEventListener("dragstart", function (e) {
    e.dataTransfer.setData("sticker", this.textContent);
  });
});

moodboard.addEventListener("dragover", (e) => e.preventDefault());
moodboard.addEventListener("drop", function (e) {
  const sticker = e.dataTransfer.getData("sticker");
  if (sticker) {
    const rect = moodboard.getBoundingClientRect();
    moodItems.push({
      type: "sticker",
      sticker,
      x: e.clientX - rect.left - 20,
      y: e.clientY - rect.top - 20,
    });
    saveAndRender();
  }
});

function addNoteMode() {
  addNoteActive = true;
  moodboard.style.cursor = "crosshair";
  moodboard.onclick = function (e) {
    if (!addNoteActive) return;
    const rect = moodboard.getBoundingClientRect();
    if (e.target.classList.contains("mood-note")) return;
    moodItems.push({
      type: "note",
      text: "",
      x: e.clientX - rect.left - 40,
      y: e.clientY - rect.top - 20,
    });
    saveAndRender();
    addNoteActive = false;
    moodboard.style.cursor = "default";
    moodboard.onclick = null;
  };
}

function preventFocusLoss() {
  document.querySelectorAll(".mood-note").forEach((textarea) => {
    textarea.addEventListener("mousedown", (e) => e.stopPropagation());
    textarea.addEventListener("click", (e) => e.stopPropagation());
  });
}
function saveAndRender() {
  localStorage.setItem("moodboardItems", JSON.stringify(moodItems));
  renderMoodboard();
  preventFocusLoss();
}

function removeMoodItem(idx) {
  moodItems.splice(idx, 1);
  saveAndRender();
}