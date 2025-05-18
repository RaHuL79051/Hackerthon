
let people = ["Person 1"];
let payments = [];
let editingIndex = null;

const savedData = JSON.parse(localStorage.getItem("budgetData") || "{}");
if (savedData.people) people = savedData.people;
if (savedData.payments) payments = savedData.payments;

const peopleCountInput = document.getElementById("peopleCount");
const nameFields = document.getElementById("nameFields");
const payerSelect = document.getElementById("payer");
const paymentList = document.getElementById("paymentList");
const resultsBody = document.getElementById("resultsBody");
const whoOwesDiv = document.getElementById("whoOwes");

function renderNameFields() {
  const count = parseInt(peopleCountInput.value);
  nameFields.innerHTML = "";
  for (let i = 0; i < count; i++) {
    const input = document.createElement("input");
    input.className = "name-input";
    input.placeholder = `Enter name for Person ${i + 1}`;
    input.value = people[i] || "";
    input.dataset.index = i;
    input.addEventListener("input", updateNamesFromInputs);
    nameFields.appendChild(input);
  }
}

function updateNamesFromInputs() {
  const inputs = nameFields.querySelectorAll(".name-input");
  people = Array.from(inputs).map(
    (input) =>
      input.value.trim() || `Person ${parseInt(input.dataset.index) + 1}`
  );
  updatePayerDropdown();
  updateUI();
  saveData();
}

function updatePayerDropdown() {
  payerSelect.innerHTML = people
    .map((person) => `<option value="${person}">${person}</option>`)
    .join("");
}

function addPayment() {
  const payer = payerSelect.value;
  const amount = parseFloat(document.getElementById("amount").value);
  const description = document.getElementById("description").value;
  if (!payer || isNaN(amount) || amount <= 0) return;
  payments.push({
    payer,
    amount,
    description: description || "No description",
    timestamp: new Date().toISOString(),
  });
  document.getElementById("amount").value = "";
  document.getElementById("description").value = "";
  updateUI();
  saveData();
}

function editPayment(index) {
  editingIndex = index;
  updateUI();
}

function saveEdit(index) {
  const amount = parseFloat(
    document.getElementById(`editAmount${index}`).value
  );
  const description = document.getElementById(`editDesc${index}`).value;
  if (isNaN(amount) || amount <= 0) return;
  payments[index].amount = amount;
  payments[index].description = description;
  editingIndex = null;
  updateUI();
  saveData();
}

function cancelEdit() {
  editingIndex = null;
  updateUI();
}

function deletePayment(index) {
  if (confirm("Delete this payment?")) {
    payments.splice(index, 1);
    updateUI();
    saveData();
  }
}

function calculateSettlement() {
  const totalPerPerson = {};
  payments.forEach((payment) => {
    totalPerPerson[payment.payer] =
      (totalPerPerson[payment.payer] || 0) + payment.amount;
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

function updateUI() {
  paymentList.innerHTML =
    payments
      .map((payment, idx) =>
        editingIndex === idx
          ? `<div class="payment-entry">
                    <select id="editPayer${idx}">
                      ${people
            .map(
              (p) =>
                `<option value="${p}"${p === payment.payer ? " selected" : ""
                }>${p}</option>`
            )
            .join("")}
                    </select>
                    <input type="number" id="editAmount${idx}" value="${payment.amount
          }" style="width:80px;">
                    <input type="text" id="editDesc${idx}" value="${payment.description
          }" style="width:180px;">
                    <br>
                    <button class="save-btn" onclick="saveEdit(${idx})">Save</button>
                    <button class="cancel-btn" onclick="cancelEdit()">Cancel</button>
                  </div>`
          : `<div class="payment-entry">
                    ${payment.payer} paid ₹${payment.amount.toFixed(2)} - ${payment.description
          }
                    <button class="edit-btn" onclick="editPayment(${idx})">Edit</button>
                    <button class="delete-btn" onclick="deletePayment(${idx})">Delete</button>
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
            <td class="${net >= 0 ? "owed" : "due"}">
              ₹${Math.abs(net).toFixed(2)} ${netLabel}
            </td>
          </tr>`;
    })
    .join("");
  whoOwesDiv.innerHTML = getSettlementSentences(result);
}

function getSettlementSentences(balances) {
  const balanceArr = people.map((p) => ({
    name: p,
    value: parseFloat(balances[p].toFixed(2)),
  }));
  const debtors = balanceArr
    .filter((b) => b.value < 0)
    .sort((a, b) => a.value - b.value);
  const creditors = balanceArr
    .filter((b) => b.value > 0)
    .sort((a, b) => b.value - a.value);
  let sentences = [];
  let i = 0,
    j = 0;
  while (i < debtors.length && j < creditors.length) {
    const debtor = debtors[i];
    const creditor = creditors[j];
    const amount = Math.min(-debtor.value, creditor.value);
    if (amount > 0.01) {
      sentences.push(
        `<b>${debtor.name}</b> owes <b>${creditor.name
        }</b> ₹${amount.toFixed(2)}`
      );
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

function saveData() {
  localStorage.setItem(
    "budgetData",
    JSON.stringify({ people, payments })
  );
}

function resetAll() {
  if (confirm("Are you sure you want to reset everything?")) {
    people = ["Person 1"];
    payments = [];
    peopleCountInput.value = 1;
    renderNameFields();
    updatePayerDropdown();
    updateUI();
    saveData();
  }
}
peopleCountInput.value = people.length;
renderNameFields();
updatePayerDropdown();
updateUI();

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
  saveData();
});

window.editPayment = editPayment;
window.deletePayment = deletePayment;
window.saveEdit = saveEdit;
window.cancelEdit = cancelEdit;