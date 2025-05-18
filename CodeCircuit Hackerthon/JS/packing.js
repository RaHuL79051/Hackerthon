let selectedEmoji = "🎒";
function toggleSectionForm(show) {
  const form = document.getElementById("sectionForm");
  form.style.display =
    typeof show === "boolean"
      ? show
        ? "block"
        : "none"
      : form.style.display === "block"
        ? "none"
        : "block";
}
function selectEmoji(element) {
  document
    .querySelectorAll(".emoji-option")
    .forEach((emoji) => emoji.classList.remove("selected"));
  element.classList.add("selected");
  selectedEmoji = element.textContent;
}
function createNewSection() {
  const sectionName = document.getElementById("sectionName").value.trim();
  if (!sectionName) return alert("Please enter a section name");
  const newSection = {
    id: `custom_${Date.now()}`,
    icon: selectedEmoji,
    name: sectionName,
    items: [],
  };
  categories.push(newSection);
  saveCategories(categories);
  toggleSectionForm(false);
  document.getElementById("sectionName").value = "";
  rerender();
}
const DEFAULT_CATEGORIES = [
  {
    id: "clothing",
    icon: "👕",
    name: "Clothing",
    items: [
      { id: "tshirts", label: "5 T-shirts" },
      { id: "pants", label: "3 Pairs of pants" },
    ],
  },
  {
    id: "toiletries",
    icon: "🧴",
    name: "Toiletries",
    items: [
      { id: "toothbrush", label: "Toothbrush & toothpaste" },
      { id: "shampoo", label: "Shampoo" },
    ],
  },
  {
    id: "necessities",
    icon: "🧢",
    name: "Daily Necessities",
    items: [
      { id: "charger", label: "Phone charger" },
      { id: "wallet", label: "Wallet" },
    ],
  },
  {
    id: "electronics",
    icon: "🔌",
    name: "Electronics",
    items: [
      { id: "laptop", label: "Laptop" },
      { id: "camera", label: "Camera" },
    ],
  },
];

function loadCategories() {
  const saved = localStorage.getItem("packingCategories");
  if (saved) return JSON.parse(saved);
  return JSON.parse(JSON.stringify(DEFAULT_CATEGORIES));
}
function saveCategories(categories) {
  localStorage.setItem("packingCategories", JSON.stringify(categories));
}
function loadChecked() {
  return JSON.parse(localStorage.getItem("packingChecked") || "{}");
}
function saveChecked(checked) {
  localStorage.setItem("packingChecked", JSON.stringify(checked));
}

function renderCategories(categories, checked, editing) {
  document
    .querySelectorAll(".category-card")
    .forEach((card) => card.remove());
  categories.forEach((cat) => {
    const card = document.createElement("div");
    card.className = "category-card";
    card.innerHTML = `
                    <div class="category-header">
                        <span style="font-size:1.5rem">${cat.icon}</span>
                        <h2 style="margin:0;font-size:1.2rem">${cat.name}</h2>
                    </div>
                    <div class="packing-items" id="items-${cat.id}"></div>
                    <form class="add-item-form" data-cat="${cat.id}">
                        <input type="text" placeholder="Add item..." maxlength="40" required />
                        <button type="submit">Add</button>
                    </form>
                `;
    document.getElementById("main-content").appendChild(card);
    const itemsDiv = card.querySelector(`#items-${cat.id}`);
    cat.items.forEach((item) => {
      const itemDiv = document.createElement("div");
      itemDiv.className = "packing-item";
      const checkboxId = `cat-${cat.id}-item-${item.id}`;
      const isEditing = editing[checkboxId];
      if (isEditing) {
        itemDiv.innerHTML = `
                            <input type="checkbox" id="${checkboxId}" data-cat="${cat.id
          }" data-item="${item.id}" style="pointer-events:none" ${checked[checkboxId] ? "checked" : ""
          }>
                            <input type="text" class="item-edit-input" value="${item.label.replace(
            /"/g,
            "&quot;"
          )}" maxlength="40">
                            <div class="item-actions">
                                <button class="item-btn save" title="Save" data-cat="${cat.id
          }" data-item="${item.id}">💾</button>
                                <button class="item-btn cancel" title="Cancel" data-cat="${cat.id
          }" data-item="${item.id}">✖️</button>
                            </div>
                        `;
      } else {
        itemDiv.innerHTML = `
                            <input type="checkbox" id="${checkboxId}" data-cat="${cat.id
          }" data-item="${item.id}" ${checked[checkboxId] ? "checked" : ""}>
                            <span class="item-label">${item.label}</span>
                            <div class="item-actions">
                                <button class="item-btn edit" title="Edit" data-cat="${cat.id
          }" data-item="${item.id}">✏️</button>
                                <button class="item-btn delete" title="Delete" data-cat="${cat.id
          }" data-item="${item.id}">🗑️</button>
                            </div>
                        `;
      }
      itemsDiv.appendChild(itemDiv);
    });
  });
}

function renderCart(categories, checked) {
  const packedList = document.getElementById("packed-items");
  const unpackedList = document.getElementById("unpacked-items");
  let packed = [],
    unpacked = [],
    total = 0;
  categories.forEach((cat) => {
    cat.items.forEach((item) => {
      total++;
      const checkboxId = `cat-${cat.id}-item-${item.id}`;
      const entry = { label: item.label, cat: cat.name };
      if (checked[checkboxId]) {
        packed.push(entry);
      } else {
        unpacked.push(entry);
      }
    });
  });
  packedList.innerHTML = packed
    .map(
      (item) =>
        `<li class="cart-item"><span>${item.label}</span><div class="status-dot packed"></div></li>`
    )
    .join("");
  unpackedList.innerHTML = unpacked
    .map(
      (item) =>
        `<li class="cart-item"><span>${item.label}</span><div class="status-dot"></div></li>`
    )
    .join("");
  document.getElementById("packed-count").textContent = packed.length;
  document.getElementById("unpacked-count").textContent = unpacked.length;
  const percent = total === 0 ? 0 : (packed.length / total) * 100;
  document.getElementById("progress").style.width = percent + "%";
  if (total > 0 && packed.length === total && !window.celebrationShown) {
    window.celebrationShown = true;
    document.getElementById("celebration").style.display = "grid";
  }
  if (packed.length !== total) {
    window.celebrationShown = false;
    document.getElementById("celebration").style.display = "none";
  }
}

let categories = loadCategories();
let checked = loadChecked();
let editing = {};

function rerender() {
  renderCategories(categories, checked, editing);
  renderCart(categories, checked);
  document.getElementById("addSectionBtn").onclick = function () {
    toggleSectionForm(true);
  };
  document.getElementById("cancelSectionBtn").onclick = function () {
    toggleSectionForm(false);
  };
  document.getElementById("createSectionBtn").onclick = createNewSection;
  document.querySelectorAll(".emoji-option").forEach((emoji) => {
    emoji.onclick = function () {
      selectEmoji(this);
    };
  });

  document
    .querySelectorAll('.packing-item input[type="checkbox"]')
    .forEach((checkbox) => {
      checkbox.addEventListener("change", function () {
        checked[this.id] = this.checked;
        saveChecked(checked);
        renderCart(categories, checked);
      });
    });
  document.querySelectorAll(".add-item-form").forEach((form) => {
    form.addEventListener("submit", function (e) {
      e.preventDefault();
      const catId = this.getAttribute("data-cat");
      const input = this.querySelector('input[type="text"]');
      const label = input.value.trim();
      if (!label) return;
      const itemId =
        "user_" + Date.now() + "_" + Math.floor(Math.random() * 10000);
      const cat = categories.find((c) => c.id === catId);
      cat.items.push({ id: itemId, label });
      saveCategories(categories);
      input.value = "";
      rerender();
    });
  });

  document.querySelectorAll(".item-btn.edit").forEach((btn) => {
    btn.addEventListener("click", function () {
      const catId = this.getAttribute("data-cat");
      const itemId = this.getAttribute("data-item");
      const checkboxId = `cat-${catId}-item-${itemId}`;
      editing[checkboxId] = true;
      rerender();
    });
  });

  document.querySelectorAll(".item-btn.delete").forEach((btn) => {
    btn.addEventListener("click", function () {
      const catId = this.getAttribute("data-cat");
      const itemId = this.getAttribute("data-item");
      const cat = categories.find((c) => c.id === catId);
      cat.items = cat.items.filter((item) => item.id !== itemId);
      saveCategories(categories);
      const checkboxId = `cat-${catId}-item-${itemId}`;
      delete checked[checkboxId];
      saveChecked(checked);
      rerender();
    });
  });
  document.querySelectorAll(".item-btn.save").forEach((btn) => {
    btn.addEventListener("click", function () {
      const catId = this.getAttribute("data-cat");
      const itemId = this.getAttribute("data-item");
      const cat = categories.find((c) => c.id === catId);
      const item = cat.items.find((it) => it.id === itemId);
      const itemDiv = btn.closest(".packing-item");
      const input = itemDiv.querySelector(".item-edit-input");
      const newLabel = input.value.trim();
      if (newLabel) {
        item.label = newLabel;
        saveCategories(categories);
      }
      const checkboxId = `cat-${catId}-item-${itemId}`;
      delete editing[checkboxId];
      rerender();
    });
  });
  document.querySelectorAll(".item-btn.cancel").forEach((btn) => {
    btn.addEventListener("click", function () {
      const catId = this.getAttribute("data-cat");
      const itemId = this.getAttribute("data-item");
      const checkboxId = `cat-${catId}-item-${itemId}`;
      delete editing[checkboxId];
      rerender();
    });
  });
  document.getElementById("closeCelebration").onclick = function () {
    document.getElementById("celebration").style.display = "none";
  };
}
rerender();