let destinations = JSON.parse(localStorage.getItem("destinations")) || [
  { name: "Paris", region: "Europe", flag: "🇫🇷" },
  { name: "London", region: "Europe", flag: "🇬🇧" },
  { name: "Berlin", region: "Europe", flag: "🇩🇪" },
  { name: "Rome", region: "Europe", flag: "🇮🇹" },
  { name: "Barcelona", region: "Europe", flag: "🇪🇸" },
  { name: "Amsterdam", region: "Europe", flag: "🇳🇱" },
  { name: "Athens", region: "Europe", flag: "🇬🇷" },
  { name: "Lisbon", region: "Europe", flag: "🇵🇹" },
  { name: "Tokyo", region: "Asia", flag: "🇯🇵" },
  { name: "Beijing", region: "Asia", flag: "🇨🇳" },
  { name: "Seoul", region: "Asia", flag: "🇰🇷" },
  { name: "Bangkok", region: "Asia", flag: "🇹🇭" },
  { name: "Singapore", region: "Asia", flag: "🇸🇬" },
  { name: "Dubai", region: "Asia", flag: "🇦🇪" },
  { name: "Delhi", region: "Asia", flag: "🇮🇳" },
  { name: "Bali", region: "Asia", flag: "🇮🇩" },
  { name: "Mumbai", region: "Asia", flag: "🇮🇳" },
  { name: "Bengaluru", region: "Asia", flag: "🇮🇳" },
  { name: "Chennai", region: "Asia", flag: "🇮🇳" },
  { name: "Kolkata", region: "Asia", flag: "🇮🇳" },
  { name: "Hyderabad", region: "Asia", flag: "🇮🇳" },
  { name: "Ahmedabad", region: "Asia", flag: "🇮🇳" },
  { name: "Pune", region: "Asia", flag: "🇮🇳" },
  { name: "Jaipur", region: "Asia", flag: "🇮🇳" },
  { name: "Goa", region: "Asia", flag: "🇮🇳" },
  { name: "Kerala", region: "Asia", flag: "🇮🇳" },
  { name: "Varanasi", region: "Asia", flag: "🇮🇳" },
  { name: "Agra", region: "Asia", flag: "🇮🇳" },
  { name: "Amritsar", region: "Asia", flag: "🇮🇳" },
  { name: "Shimla", region: "Asia", flag: "🇮🇳" },
  { name: "Manali", region: "Asia", flag: "🇮🇳" },
  { name: "Udaipur", region: "Asia", flag: "🇮🇳" },
  { name: "Rishikesh", region: "Asia", flag: "🇮🇳" },
  { name: "Darjeeling", region: "Asia", flag: "🇮🇳" },
  { name: "Mysuru", region: "Asia", flag: "🇮🇳" },
  { name: "Puducherry", region: "Asia", flag: "🇮🇳" },
  { name: "Leh", region: "Asia", flag: "🇮🇳" },
  { name: "Sikkim", region: "Asia", flag: "🇮🇳" },
  { name: "Andaman & Nicobar", region: "Asia", flag: "🇮🇳" },
  { name: "Lakshadweep", region: "Asia", flag: "🇮🇳" },

  { name: "New York", region: "Americas", flag: "🇺🇸" },
  { name: "Los Angeles", region: "Americas", flag: "🇺🇸" },
  { name: "Toronto", region: "Americas", flag: "🇨🇦" },
  { name: "Vancouver", region: "Americas", flag: "🇨🇦" },
  { name: "Rio de Janeiro", region: "Americas", flag: "🇧🇷" },
  { name: "Buenos Aires", region: "Americas", flag: "🇦🇷" },
  { name: "Mexico City", region: "Americas", flag: "🇲🇽" },
  { name: "Havana", region: "Americas", flag: "🇨🇺" },

  { name: "Cairo", region: "Africa", flag: "🇪🇬" },
  { name: "Cape Town", region: "Africa", flag: "🇿🇦" },
  { name: "Nairobi", region: "Africa", flag: "🇰🇪" },
  { name: "Marrakesh", region: "Africa", flag: "🇲🇦" },
  { name: "Lagos", region: "Africa", flag: "🇳🇬" },
  { name: "Accra", region: "Africa", flag: "🇬🇭" },
  { name: "Addis Ababa", region: "Africa", flag: "🇪🇹" },
  { name: "Tunis", region: "Africa", flag: "🇹🇳" },

  { name: "Sydney", region: "Oceania", flag: "🇦🇺" },
  { name: "Melbourne", region: "Oceania", flag: "🇦🇺" },
  { name: "Brisbane", region: "Oceania", flag: "🇦🇺" },
  { name: "Auckland", region: "Oceania", flag: "🇳🇿" },
  { name: "Wellington", region: "Oceania", flag: "🇳🇿" },
  { name: "Fiji Islands", region: "Oceania", flag: "🇫🇯" },
  { name: "Port Moresby", region: "Oceania", flag: "🇵🇬" }
];


let currentRegion = "All";
let searchQuery = "";

function saveData() {
  localStorage.setItem("destinations", JSON.stringify(destinations));
}

function renderDestinations() {
  const grid = document.getElementById("destGrid");
  const filtered = destinations.filter(
    (d) =>
      (currentRegion === "All" || d.region === currentRegion) &&
      d.name.toLowerCase().includes(searchQuery.toLowerCase())
  );

  if (filtered.length === 0) {
    grid.innerHTML =
      "<div style='grid-column:1/-1;text-align:center;color:#777'>No destinations found.</div>";
    return;
  }

  grid.innerHTML = filtered
    .map(
      (d, index) => `
    <div class="dest-card">
      <div class="dest-flag">${d.flag}</div>
      <div class="dest-title">${d.name}</div>
      <div class="dest-region">${d.region}</div>
      <div class="card-actions">
        <button onclick="editDestination(${index})">✏️ Edit</button>
        <button onclick="deleteDestination(${index})">🗑️ Delete</button>
      </div>
    </div>`
    )
    .join("");
}

function editDestination(index) {
  const newName = prompt("Edit Destination Name:", destinations[index].name);
  if (newName) {
    destinations[index].name = newName;
    saveData();
    renderDestinations();
  }
}

function deleteDestination(index) {
  if (confirm("Are you sure you want to delete this destination?")) {
    destinations.splice(index, 1);
    saveData();
    renderDestinations();
  }
}

document.querySelectorAll(".filter-btn").forEach((btn) => {
  btn.onclick = function () {
    document
      .querySelectorAll(".filter-btn")
      .forEach((b) => b.classList.remove("active"));
    this.classList.add("active");
    currentRegion = this.dataset.region;
    renderDestinations();
  };
});

document.getElementById("searchInput").addEventListener("input", function () {
  searchQuery = this.value;
  renderDestinations();
});

renderDestinations();
