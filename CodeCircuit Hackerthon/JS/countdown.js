function formatDate(date) {
  return date.toLocaleDateString(undefined, {
    year: "numeric",
    month: "short",
    day: "numeric",
  });
}

function showNotification(title, message) {
  document.getElementById("notifTitle").textContent = title;
  document.getElementById("notifMsg").textContent = message;
  document.getElementById("notificationOverlay").style.display = "grid";
}
function closeNotification() {
  document.getElementById("notificationOverlay").style.display = "none";
}

function saveTripData() {
  localStorage.setItem(
    "tripDates",
    JSON.stringify({
      start: startDate.value,
      startTime: startTime.value,
      end: endDate.value,
    })
  );
}
function savePlans() {
  localStorage.setItem("tripPlans", JSON.stringify(plans));
}
function loadTripData() {
  return JSON.parse(localStorage.getItem("tripDates") || "{}");
}
function loadPlans() {
  return JSON.parse(localStorage.getItem("tripPlans") || "{}");
}

const startDate = document.getElementById("startDate");
const startTime = document.getElementById("startTime");
const endDate = document.getElementById("endDate");
const countdownDiv = document.getElementById("countdown");
const plannerSection = document.getElementById("plannerSection");
let plans = loadPlans();

const savedDates = loadTripData();
if (savedDates.start) startDate.value = savedDates.start;
if (savedDates.startTime) startTime.value = savedDates.startTime;
if (savedDates.end) endDate.value = savedDates.end;

let tripStartedNotified =
  localStorage.getItem("tripStartedNotified") === "true";
let tripEndedNotified =
  localStorage.getItem("tripEndedNotified") === "true";

function getStartDateTime() {
  if (!startDate.value) return null;
  let time = startTime.value || "00:00";
  return new Date(startDate.value + "T" + time);
}

function updateCountdownAndPlanner() {
  countdownDiv.textContent = "";
  plannerSection.innerHTML = "";
  if (!startDate.value) {
    countdownDiv.textContent =
      "Set your trip start date and time to begin the countdown!";
    return;
  }
  const now = new Date();
  const start = getStartDateTime();
  const end = endDate.value ? new Date(endDate.value) : null;

  if (now < start) {
    const msDiff = start - now;
    const days = Math.floor(msDiff / (1000 * 60 * 60 * 24));
    const hours = Math.floor(
      (msDiff % (1000 * 60 * 60 * 24)) / (1000 * 60 * 60)
    );
    const mins = Math.floor((msDiff % (1000 * 60 * 60)) / (1000 * 60));
    const secs = Math.floor((msDiff % (1000 * 60)) / 1000);
    countdownDiv.textContent = `Trip starts in ${days}d ${hours}h ${mins}m ${secs}s (${formatDate(
      start
    )} ${startTime.value})`;
    tripStartedNotified = false;
    tripEndedNotified = false;
    localStorage.setItem("tripStartedNotified", "false");
    localStorage.setItem("tripEndedNotified", "false");
  } else if (end && now > end) {
    countdownDiv.textContent = `Your trip ended on ${formatDate(end)}.`;
    if (!tripEndedNotified) {
      showNotification("Trip Ended!", "Hope you had a great journey! 🧳");
      tripEndedNotified = true;
      localStorage.setItem("tripEndedNotified", "true");
    }
  } else {
    countdownDiv.textContent = "Your trip has started!";
    if (!tripStartedNotified) {
      showNotification(
        "Trip Started!",
        "Bon voyage! Your trip is now underway! ✈️"
      );
      tripStartedNotified = true;
      localStorage.setItem("tripStartedNotified", "true");
    }
  }

  if (endDate.value && new Date(endDate.value) >= start) {
    const end = new Date(endDate.value);
    let dayNum = 1;
    for (
      let d = new Date(start);
      d <= end;
      d.setDate(d.getDate() + 1), dayNum++
    ) {
      const dayKey = `${startDate.value}_${dayNum}`;
      const note = plans[dayKey] || "";
      const dayDiv = document.createElement("div");
      dayDiv.className = "day-plan";
      dayDiv.innerHTML = `
            <div class="day-title">Day ${dayNum} - ${formatDate(
        new Date(d)
      )}</div>
            <textarea class="note-input" id="note_${dayKey}" placeholder="Add notes or plans...">${note}</textarea>
            <button class="save-note-btn" onclick="saveNote('${dayKey}')">Save</button>
          `;
      plannerSection.appendChild(dayDiv);
    }
  }
}

window.saveNote = function (dayKey) {
  const val = document.getElementById("note_" + dayKey).value;
  plans[dayKey] = val;
  savePlans();
};

startDate.addEventListener("change", () => {
  saveTripData();
  updateCountdownAndPlanner();
});
startTime.addEventListener("change", () => {
  saveTripData();
  updateCountdownAndPlanner();
});
endDate.addEventListener("change", () => {
  saveTripData();
  updateCountdownAndPlanner();
});

setInterval(updateCountdownAndPlanner, 1000);
updateCountdownAndPlanner();