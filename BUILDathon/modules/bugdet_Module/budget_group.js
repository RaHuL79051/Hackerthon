let people = [];
let payments = [];
let editingIndex = null;

// DOM elements
const peopleCountInput = document.getElementById("peopleCount");
const nameFields = document.getElementById("nameFields");
const payerSelect = document.getElementById("payer");
const paymentList = document.getElementById("paymentList");
const resultsBody = document.getElementById("resultsBody");
const whoOwesDiv = document.getElementById("whoOwes");

// Fetch payments from DB on load
fetch("group_expense_api.php")
  .then((res) => res.json())
  .then((data) => {
    payments = data;

    // If payments have payer names, extract unique people
    const unique = new Set();
    payments.forEach((p) => unique.add(p.payer_name));
    people = Array.from(unique);

    // Fallback to 3 default names if no one exists
    if (people.length === 0) {
      people = ["Person 1", "Person 2", "Person 3"];
    }

    peopleCountInput.value = people.length;
    renderNameFields();
    updatePayerDropdown();
    updateUI();
  });

// Render input fields for people names
function renderNameFields() {
  nameFields.innerHTML = "";
  for (let i = 0; i < people.length; i++) {
    const input = document.createElement("input");
    input.className = "name-input";
    input.placeholder = `Enter name for Person ${i + 1}`;
    input.value = people[i] || "";
    input.dataset.index = i;
    input.addEventListener("input", updateNamesFromInputs);
    nameFields.appendChild(input);
  }
}

// Update people array from name inputs
function updateNamesFromInputs() {
  const inputs = nameFields.querySelectorAll(".name-input");
  people = Array.from(inputs).map(
    (input) => input.value.trim() || `Person ${parseInt(input.dataset.index) + 1}`
  );
  updatePayerDropdown();
  updateUI();
}

// Update dropdown with payer options
function updatePayerDropdown() {
  payerSelect.innerHTML = people
    .map((person) => `<option value="${person}">${person}</option>`)
    .join("");
}

// Add a new payment
function addPayment() {
  const payer = payerSelect.value;
  const amount = parseFloat(document.getElementById("amount").value);
  const description = document.getElementById("description").value;

  if (!payer || isNaN(amount) || amount <= 0) return;

  fetch("group_expense_api.php", {
    method: "POST",
    headers: { "Content-Type": "application/x-www-form-urlencoded" },
    body: new URLSearchParams({
      action: "add",
      payer,
      amount,
      description,
    }),
  })
    .then((res) => res.json())
    .then(() => location.reload());
}

// Delete a payment
function deletePayment(id) {
  if (!confirm("Delete this payment?")) return;
  fetch("group_expense_api.php", {
    method: "POST",
    headers: { "Content-Type": "application/x-www-form-urlencoded" },
    body: new URLSearchParams({
      action: "delete",
      id,
    }),
  })
    .then((res) => res.json())
    .then(() => location.reload());
}

// Edit UI entry
function editPayment(index) {
  editingIndex = index;
  updateUI();
}

// Save edited payment
function saveEdit(index) {
  const amount = parseFloat(document.getElementById(`editAmount${index}`).value);
  const description = document.getElementById(`editDesc${index}`).value;
  const payer = document.getElementById(`editPayer${index}`).value;

  if (isNaN(amount) || amount <= 0) return;

  const id = payments[index].id;

  fetch("group_expense_api.php", {
    method: "POST",
    headers: { "Content-Type": "application/x-www-form-urlencoded" },
    body: new URLSearchParams({
      action: "update",
      id,
      amount,
      payer,
      description,
    }),
  })
    .then((res) => res.json())
    .then(() => location.reload());
}

function cancelEdit() {
  editingIndex = null;
  updateUI();
}

// Reset all payments
function resetAll() {
  if (!confirm("⚠️ This will permanently delete ALL your group expenses.\nContinue?")) return;
  payments.forEach((p) => deletePayment(p.id));
}

// Update all UI components
function updateUI() {
  paymentList.innerHTML =
    payments
      .map((payment, idx) =>
        editingIndex === idx
          ? `<div class="payment-entry">
              <select id="editPayer${idx}">
                ${people.map(
                  (p) => `<option value="${p}"${p === payment.payer_name ? " selected" : ""}>${p}</option>`
                ).join("")}
              </select>
              <input type="number" id="editAmount${idx}" value="${payment.amount}" style="width:80px;">
              <input type="text" id="editDesc${idx}" value="${payment.description}" style="width:180px;">
              <br>
              <button class="save-btn" onclick="saveEdit(${idx})">Save</button>
              <button class="cancel-btn" onclick="cancelEdit()">Cancel</button>
            </div>`
          : `<div class="payment-entry">
              ${payment.payer_name} paid ₹${parseFloat(payment.amount).toFixed(2)} - ${payment.description}
              <button class="edit-btn" onclick="editPayment(${idx})">Edit</button>
              <button class="delete-btn" onclick="deletePayment(${payment.id})">Delete</button>
            </div>`
      )
      .join("") || "<div>No payments recorded yet</div>";

  const { total, share, result } = calculateSettlement();

  resultsBody.innerHTML = people
    .map((person) => {
      const paid = result[person] + share;
      const net = result[person];
      const netLabel = net >= 0 ? "owed" : "due";
      return `
        <tr>
          <td>${person}</td>
          <td>₹${paid.toFixed(2)}</td>
          <td>₹${share.toFixed(2)}</td>
          <td class="${net >= 0 ? "owed" : "due"}">₹${Math.abs(net).toFixed(2)} ${netLabel}</td>
        </tr>`;
    })
    .join("");

  whoOwesDiv.innerHTML = getSettlementSentences(result);
}

// Calculate who owes what
function calculateSettlement() {
  const totalPerPerson = {};
  payments.forEach((payment) => {
    const payer = payment.payer_name;
    totalPerPerson[payer] = (totalPerPerson[payer] || 0) + parseFloat(payment.amount);
  });

  const total = Object.values(totalPerPerson).reduce((a, b) => a + b, 0);
  const share = total / people.length;

  const result = {};
  people.forEach((person) => {
    const paid = totalPerPerson[person] || 0;
    result[person] = paid - share;
  });

  return { total, share, result };
}

// Generate readable text
function getSettlementSentences(balances) {
  const balanceArr = people.map((p) => ({
    name: p,
    value: parseFloat(balances[p].toFixed(2)),
  }));
  const debtors = balanceArr.filter((b) => b.value < 0).sort((a, b) => a.value - b.value);
  const creditors = balanceArr.filter((b) => b.value > 0).sort((a, b) => b.value - a.value);
  let sentences = [];
  let i = 0, j = 0;
  while (i < debtors.length && j < creditors.length) {
    const debtor = debtors[i];
    const creditor = creditors[j];
    const amount = Math.min(-debtor.value, creditor.value);
    if (amount > 0.01) {
      sentences.push(`<b>${debtor.name}</b> owes <b>${creditor.name}</b> ₹${amount.toFixed(2)}`);
      debtor.value += amount;
      creditor.value -= amount;
    }
    if (Math.abs(debtor.value) < 0.01) i++;
    if (creditor.value < 0.01) j++;
  }
  return sentences.length
    ? sentences.map((s) => `<div>${s}</div>`).join("")
    : "<i>All settled up!</i>";
}

// When user changes number of people
peopleCountInput.addEventListener("change", () => {
  const newCount = parseInt(peopleCountInput.value);
  if (newCount > people.length) {
    for (let i = people.length; i < newCount; i++) {
      people.push(`Person ${i + 1}`);
    }
  } else {
    people = people.slice(0, newCount);
  }
  renderNameFields();
  updatePayerDropdown();
  updateUI();
});

// Make functions globally accessible
window.editPayment = editPayment;
window.deletePayment = deletePayment;
window.saveEdit = saveEdit;
window.cancelEdit = cancelEdit;
window.resetAll = resetAll;
window.addPayment = addPayment;
