const currencyData = [
  { code: "INR", name: "Indian Rupee", flag: "🇮🇳", inr: 1 },
  { code: "USD", name: "US Dollar", flag: "🇺🇸", inr: 83.3 },
  { code: "EUR", name: "Euro", flag: "🇪🇺", inr: 89.04 },
  { code: "GBP", name: "British Pound", flag: "🇬🇧", inr: 103.6 },
  { code: "JPY", name: "Japanese Yen", flag: "🇯🇵", inr: 0.54 },
  { code: "CNY", name: "Chinese Yuan", flag: "🇨🇳", inr: 11.5 },
  { code: "AED", name: "UAE Dirham", flag: "🇦🇪", inr: 22.68 },
  { code: "AUD", name: "Australian Dollar", flag: "🇦🇺", inr: 54.19 },
  { code: "CAD", name: "Canadian Dollar", flag: "🇨🇦", inr: 60.91 },
  { code: "SGD", name: "Singapore Dollar", flag: "🇸🇬", inr: 61.5 },
  { code: "ZAR", name: "South African Rand", flag: "🇿🇦", inr: 4.36 },
  { code: "MXN", name: "Mexican Peso", flag: "🇲🇽", inr: 4.91 },
  { code: "EGP", name: "Egyptian Pound", flag: "🇪🇬", inr: 1.73 },
];

const fromSelect = document.getElementById("fromCurrency");
const toSelect = document.getElementById("toCurrency");
const amountInput = document.getElementById("amount");
const resultDiv = document.getElementById("result");
const rateInfoDiv = document.getElementById("rateInfo");

function populateDropdowns() {
  currencyData.forEach((cur) => {
    const option1 = document.createElement("option");
    option1.value = cur.code;
    option1.innerHTML = `${cur.flag} ${cur.code} - ${cur.name}`;
    const option2 = option1.cloneNode(true);
    fromSelect.appendChild(option1);
    toSelect.appendChild(option2);
  });
  fromSelect.value = "USD";
  toSelect.value = "INR";
}

function getRate(from, to) {
  const fromObj = currencyData.find((c) => c.code === from);
  const toObj = currencyData.find((c) => c.code === to);
  return fromObj && toObj ? fromObj.inr / toObj.inr : 1;
}

function convertCurrency() {
  const amount = parseFloat(amountInput.value);
  const from = fromSelect.value;
  const to = toSelect.value;

  if (isNaN(amount) || amount < 0) {
    resultDiv.textContent = "Enter a valid amount";
    rateInfoDiv.textContent = "";
    return;
  }

  const rate = getRate(from, to);
  const converted = (amount * rate).toFixed(2);

  const fromFlag = currencyData.find((c) => c.code === from).flag;
  const toFlag = currencyData.find((c) => c.code === to).flag;

  resultDiv.innerHTML = `${fromFlag} ${amount} ${from} = <strong>${toFlag} ${converted} ${to}</strong>`;
  rateInfoDiv.textContent = `1 ${from} = ${rate.toFixed(
    4
  )} ${to} | 1 ${to} = ${(1 / rate).toFixed(4)} ${from}`;
}

function swapCurrencies() {
  const temp = fromSelect.value;
  fromSelect.value = toSelect.value;
  toSelect.value = temp;
  convertCurrency();
}

amountInput.addEventListener("input", convertCurrency);
fromSelect.addEventListener("change", convertCurrency);
toSelect.addEventListener("change", convertCurrency);

populateDropdowns();
convertCurrency();