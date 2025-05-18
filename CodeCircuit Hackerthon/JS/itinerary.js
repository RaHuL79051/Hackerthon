let itinerary = JSON.parse(localStorage.getItem("itinerary") || "[]");
if (itinerary.length === 0) {
  itinerary = [
    { day: 1, activities: [] },
    { day: 2, activities: [] },
    { day: 3, activities: [] },
  ];
}
let editActivityRef = null;
const board = document.getElementById("itineraryBoard");
function renderBoard() {
  board.innerHTML = "";
  itinerary.forEach((day, dIdx) => {
    const column = document.createElement("div");
    column.className = "day-column";
    column.innerHTML = `
                    <div class="day-header">
                        <span>Day ${day.day}</span>
                        <button class="remove-day-btn" onclick="removeDay(${dIdx})" title="Remove Day">×</button>
                    </div>
                    <div class="activity-list" 
                         ondrop="drop(event, ${dIdx})" 
                         ondragover="allowDrop(event)">
                        ${day.activities
        .map(
          (activity, aIdx) => `
                            <div class="activity-card" 
                                 draggable="true"
                                 ondragstart="dragStart(event, ${dIdx}, '${activity.id
            }')"
                                 id="activity-${activity.id}">
                                <div class="activity-actions">
                                    <button class="activity-btn update" onclick="openEditActivity(${dIdx}, ${aIdx})" title="Edit">✏️</button>
                                    <button class="activity-btn delete" onclick="deleteActivity(${dIdx}, ${aIdx})" title="Delete">🗑️</button>
                                </div>
                                <h4>${activity.time} - ${activity.title}</h4>
                                <p>${activity.description || ""}</p>
                            </div>
                        `
        )
        .join("")}
                    </div>
                `;
    board.appendChild(column);
  });
  updateDaySelect();
  localStorage.setItem("itinerary", JSON.stringify(itinerary));
}

let draggedItem = null;
function allowDrop(ev) {
  ev.preventDefault();
}
function dragStart(ev, dayIdx, activityId) {
  draggedItem = { dayIdx, activityId };
  ev.target.classList.add("dragging");
}
function drop(ev, targetDayIdx) {
  ev.preventDefault();
  if (!draggedItem) return;
  const sourceDay = itinerary[draggedItem.dayIdx];
  const activityIndex = sourceDay.activities.findIndex(
    (a) => a.id === draggedItem.activityId
  );
  const [activity] = sourceDay.activities.splice(activityIndex, 1);
  itinerary[targetDayIdx].activities.push(activity);
  renderBoard();
  draggedItem = null;
}
document
  .getElementById("activityForm")
  .addEventListener("submit", function (e) {
    e.preventDefault();
    const formData = new FormData(this);
    const newActivity = {
      id: Date.now().toString(),
      title: formData.get("title"),
      time: formData.get("time"),
      description: formData.get("description"),
    };
    const selectedDay = parseInt(
      document.getElementById("daySelect").value
    );
    const dayIndex = itinerary.findIndex((d) => d.day === selectedDay);
    itinerary[dayIndex].activities.push(newActivity);
    renderBoard();
    this.reset();
  });

function updateDaySelect() {
  const select = document.getElementById("daySelect");
  const editDay = document.getElementById("editDay");
  select.innerHTML = itinerary
    .map((day) => `<option value="${day.day}">Day ${day.day}</option>`)
    .join("");
  if (editDay) {
    editDay.innerHTML = select.innerHTML;
  }
}
function addDay() {
  const maxDay = itinerary.length
    ? Math.max(...itinerary.map((d) => d.day))
    : 0;
  itinerary.push({ day: maxDay + 1, activities: [] });
  renderBoard();
}
function removeDay(idx) {
  if (confirm("Delete this day and all its activities?")) {
    itinerary.splice(idx, 1);
    renderBoard();
  }
}
function resetItinerary() {
  if (confirm("Reset the entire itinerary board?")) {
    itinerary = [
      { day: 1, activities: [] },
      { day: 2, activities: [] },
      { day: 3, activities: [] },
    ];
    renderBoard();
  }
}
function deleteActivity(dayIdx, actIdx) {
  if (confirm("Delete this activity?")) {
    itinerary[dayIdx].activities.splice(actIdx, 1);
    renderBoard();
  }
}
function openEditActivity(dayIdx, actIdx) {
  editActivityRef = { dayIdx, actIdx };
  const activity = itinerary[dayIdx].activities[actIdx];
  document.getElementById("editTitle").value = activity.title;
  document.getElementById("editTime").value = activity.time;
  document.getElementById("editDesc").value = activity.description;
  document.getElementById("editDay").value = itinerary[dayIdx].day;
  document.getElementById("modalOverlay").style.display = "flex";
}
function closeModal() {
  document.getElementById("modalOverlay").style.display = "none";
}
function saveEditActivity() {
  if (!editActivityRef) return;
  const { dayIdx, actIdx } = editActivityRef;
  const activity = itinerary[dayIdx].activities[actIdx];
  const newTitle = document.getElementById("editTitle").value;
  const newTime = document.getElementById("editTime").value;
  const newDesc = document.getElementById("editDesc").value;
  const newDay = parseInt(document.getElementById("editDay").value);
  if (itinerary[dayIdx].day !== newDay) {
    itinerary[dayIdx].activities.splice(actIdx, 1);
    const targetDayIdx = itinerary.findIndex((d) => d.day === newDay);
    itinerary[targetDayIdx].activities.push({
      ...activity,
      title: newTitle,
      time: newTime,
      description: newDesc,
    });
  } else {
    activity.title = newTitle;
    activity.time = newTime;
    activity.description = newDesc;
  }
  closeModal();
  renderBoard();
}

renderBoard();
window.removeDay = removeDay;
window.deleteActivity = deleteActivity;
window.openEditActivity = openEditActivity;
window.closeModal = closeModal;
window.saveEditActivity = saveEditActivity;
window.addDay = addDay;
window.resetItinerary = resetItinerary;
window.allowDrop = allowDrop;
window.dragStart = dragStart;
window.drop = drop;