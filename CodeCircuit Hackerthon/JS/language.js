const phrases = {
  spanish: {
    general: [
      ["Hello", "Hola"],
      ["Goodbye", "Adiós"],
      ["Please", "Por favor"],
      ["Thank you", "Gracias"],
      ["Yes / No", "Sí / No"],
      ["Do you speak English?", "¿Hablas inglés?"],
    ],
    food: [
      ["A table for two, please.", "Una mesa para dos, por favor."],
      ["Can I see the menu?", "¿Puedo ver el menú?"],
      ["Water", "Agua"],
      ["The bill, please.", "La cuenta, por favor."],
    ],
    directions: [
      ["Where is the bus stop?", "¿Dónde está la parada de autobús?"],
      ["How do I get to the airport?", "¿Cómo llego al aeropuerto?"],
      ["Left / Right / Straight", "Izquierda / Derecha / Recto"],
    ],
    shopping: [
      ["How much is this?", "¿Cuánto cuesta esto?"],
      ["Do you accept credit cards?", "¿Aceptan tarjetas de crédito?"],
      ["Cheaper, please.", "Más barato, por favor."],
    ],
    emergency: [
      ["Help!", "¡Ayuda!"],
      ["Call the police!", "¡Llame a la policía!"],
      ["I need a doctor.", "Necesito un médico."],
    ],
  },
  french: {
    general: [
      ["Hello", "Bonjour"],
      ["Goodbye", "Au revoir"],
      ["Please", "S'il vous plaît"],
      ["Thank you", "Merci"],
      ["Yes / No", "Oui / Non"],
      ["Do you speak English?", "Parlez-vous anglais ?"],
    ],
    food: [
      [
        "A table for two, please.",
        "Une table pour deux, s'il vous plaît.",
      ],
      ["Can I see the menu?", "Puis-je voir le menu ?"],
      ["Water", "Eau"],
      ["The bill, please.", "L'addition, s'il vous plaît."],
    ],
    directions: [
      ["Where is the bus stop?", "Où est l'arrêt de bus ?"],
      ["How do I get to the airport?", "Comment aller à l'aéroport ?"],
      ["Left / Right / Straight", "Gauche / Droite / Tout droit"],
    ],
    shopping: [
      ["How much is this?", "Combien ça coûte ?"],
      [
        "Do you accept credit cards?",
        "Acceptez-vous les cartes de crédit ?",
      ],
      ["Cheaper, please.", "Moins cher, s'il vous plaît."],
    ],
    emergency: [
      ["Help!", "À l'aide !"],
      ["Call the police!", "Appelez la police !"],
      ["I need a doctor.", "J'ai besoin d'un médecin."],
    ],
  },
  german: {
    general: [
      ["Hello", "Hallo"],
      ["Goodbye", "Auf Wiedersehen"],
      ["Please", "Bitte"],
      ["Thank you", "Danke"],
      ["Yes / No", "Ja / Nein"],
      ["Do you speak English?", "Sprechen Sie Englisch?"],
    ],
    food: [
      ["A table for two, please.", "Ein Tisch für zwei, bitte."],
      ["Can I see the menu?", "Kann ich die Speisekarte sehen?"],
      ["Water", "Wasser"],
      ["The bill, please.", "Die Rechnung, bitte."],
    ],
    directions: [
      ["Where is the bus stop?", "Wo ist die Bushaltestelle?"],
      ["How do I get to the airport?", "Wie komme ich zum Flughafen?"],
      ["Left / Right / Straight", "Links / Rechts / Geradeaus"],
    ],
    shopping: [
      ["How much is this?", "Wie viel kostet das?"],
      ["Do you accept credit cards?", "Akzeptieren Sie Kreditkarten?"],
      ["Cheaper, please.", "Billiger, bitte."],
    ],
    emergency: [
      ["Help!", "Hilfe!"],
      ["Call the police!", "Rufen Sie die Polizei!"],
      ["I need a doctor.", "Ich brauche einen Arzt."],
    ],
  },
  hindi: {
    general: [
      ["Hello", "नमस्ते (Namaste)"],
      ["Goodbye", "अलविदा (Alvida)"],
      ["Please", "कृपया (Kripya)"],
      ["Thank you", "धन्यवाद (Dhanyavaad)"],
      ["Yes / No", "हाँ / नहीं (Haan / Nahin)"],
      [
        "Do you speak English?",
        "क्या आप अंग्रेज़ी बोलते हैं? (Kya aap angrezi bolte hain?)",
      ],
    ],
    food: [
      ["A table for two, please.", "दो लोगों के लिए मेज़, कृपया।"],
      ["Can I see the menu?", "क्या मैं मेनू देख सकता हूँ?"],
      ["Water", "पानी (Pani)"],
      ["The bill, please.", "बिल दीजिए।"],
    ],
    directions: [
      ["Where is the bus stop?", "बस स्टॉप कहाँ है?"],
      ["How do I get to the airport?", "हवाई अड्डे कैसे जाऊँ?"],
      ["Left / Right / Straight", "बाएँ / दाएँ / सीधा"],
    ],
    shopping: [
      ["How much is this?", "यह कितने का है?"],
      [
        "Do you accept credit cards?",
        "क्या आप क्रेडिट कार्ड स्वीकार करते हैं?",
      ],
      ["Cheaper, please.", "सस्ता कीजिए।"],
    ],
    emergency: [
      ["Help!", "मदद!"],
      ["Call the police!", "पुलिस को बुलाओ!"],
      ["I need a doctor.", "मुझे डॉक्टर चाहिए।"],
    ],
  },
  chinese: {
    general: [
      ["Hello", "你好 (Nǐ hǎo)"],
      ["Goodbye", "再见 (Zàijiàn)"],
      ["Please", "请 (Qǐng)"],
      ["Thank you", "谢谢 (Xièxiè)"],
      ["Yes / No", "是 / 不是 (Shì / Bù shì)"],
      ["Do you speak English?", "你会说英语吗？(Nǐ huì shuō Yīngyǔ ma?)"],
    ],
    food: [
      ["A table for two, please.", "请给我们两个人的桌子。"],
      ["Can I see the menu?", "可以看一下菜单吗？"],
      ["Water", "水 (Shuǐ)"],
      ["The bill, please.", "请结账。"],
    ],
    directions: [
      ["Where is the bus stop?", "公交车站在哪里？"],
      ["How do I get to the airport?", "怎么去机场？"],
      ["Left / Right / Straight", "左 / 右 / 直走"],
    ],
    shopping: [
      ["How much is this?", "这个多少钱？"],
      ["Do you accept credit cards?", "可以刷信用卡吗？"],
      ["Cheaper, please.", "便宜一点。"],
    ],
    emergency: [
      ["Help!", "救命! (Jiùmìng!)"],
      ["Call the police!", "叫警察! (Jiào jǐngchá!)"],
      ["I need a doctor.", "我需要医生。"],
    ],
  },
  japanese: {
    general: [
      ["Hello", "こんにちは (Konnichiwa)"],
      ["Goodbye", "さようなら (Sayōnara)"],
      ["Please", "お願いします (Onegaishimasu)"],
      ["Thank you", "ありがとう (Arigatou)"],
      ["Yes / No", "はい / いいえ (Hai / Iie)"],
      [
        "Do you speak English?",
        "英語を話せますか？(Eigo o hanasemasu ka?)",
      ],
    ],
    food: [
      ["A table for two, please.", "二人用の席をお願いします。"],
      ["Can I see the menu?", "メニューを見せてください。"],
      ["Water", "水 (Mizu)"],
      ["The bill, please.", "お会計をお願いします。"],
    ],
    directions: [
      ["Where is the bus stop?", "バス停はどこですか？"],
      ["How do I get to the airport?", "空港へはどう行きますか？"],
      ["Left / Right / Straight", "左 / 右 / まっすぐ"],
    ],
    shopping: [
      ["How much is this?", "これはいくらですか？"],
      ["Do you accept credit cards?", "クレジットカードは使えますか？"],
      ["Cheaper, please.", "もっと安くしてください。"],
    ],
    emergency: [
      ["Help!", "助けて! (Tasukete!)"],
      ["Call the police!", "警察を呼んで! (Keisatsu o yonde!)"],
      ["I need a doctor.", "医者が必要です。"],
    ],
  },
};

const translations = {
  english: {
    spanish: {
      hello: "hola",
      goodbye: "adiós",
      "thank you": "gracias",
      please: "por favor",
    },
    french: {
      hello: "bonjour",
      goodbye: "au revoir",
      "thank you": "merci",
      please: "s'il vous plaît",
    },
    german: {
      hello: "hallo",
      goodbye: "auf wiedersehen",
      "thank you": "danke",
      please: "bitte",
    },
    hindi: {
      hello: "नमस्ते (Namaste)",
      goodbye: "अलविदा (Alvida)",
      "thank you": "धन्यवाद (Dhanyavaad)",
      please: "कृपया (Kripya)",
    },
  },
  spanish: {
    english: {
      hola: "hello",
      adiós: "goodbye",
      gracias: "thank you",
      "por favor": "please",
    },
  },
};

function translateText() {
  const sourceLang = document.getElementById("sourceLang").value;
  const targetLang = document.getElementById("targetLang").value;
  const text = document
    .getElementById("inputText")
    .value.trim()
    .toLowerCase();
  const resultDiv = document.getElementById("translationResult");
  const speakBtn = document.getElementById("speakBtn");

  if (sourceLang === targetLang) {
    resultDiv.textContent =
      "Please select different source and target languages.";
    speakBtn.style.display = "none";
    return;
  }

  let translation = translations[sourceLang]?.[targetLang]?.[text];
  if (translation) {
    resultDiv.innerHTML = `<strong>Translation:</strong> ${translation}`;
    speakBtn.style.display = "inline-block";
    speakBtn.dataset.text = translation;
    speakBtn.dataset.lang = targetLang;
  } else {
    resultDiv.textContent =
      "Translation not available for this phrase (demo supports only a few phrases).";
    speakBtn.style.display = "none";
  }
}

function speakTranslation() {
  const synth = window.speechSynthesis;
  const text = document.getElementById("speakBtn").dataset.text;
  const lang = document.getElementById("speakBtn").dataset.lang;
  const voices = synth.getVoices();
  const code = langToCode(lang);
  const voice =
    voices.find((v) => v.lang.startsWith(code.split("-")[0])) ||
    voices[0];
  const utterance = new SpeechSynthesisUtterance(text);
  utterance.voice = voice;
  utterance.lang = code;
  synth.speak(utterance);
}

function langToCode(lang) {
  const codes = {
    english: "en-US",
    spanish: "es-ES",
    french: "fr-FR",
    german: "de-DE",
    hindi: "hi-IN",
    chinese: "zh-CN",
    japanese: "ja-JP",
  };
  return codes[lang] || "en-US";
}

speechSynthesis.onvoiceschanged = () => { };

const langSelect = document.getElementById("langSelect");
const contextSelect = document.getElementById("contextSelect");
const cheatTable = document.getElementById("cheatTable");
const categoryLabel = document.getElementById("categoryLabel");

function renderTable() {
  const lang = langSelect.value;
  const context = contextSelect.value;
  const tableRows = phrases[lang][context]
    .map(([en, target]) => `<tr><td>${en}</td><td>${target}</td></tr>`)
    .join("");
  cheatTable.innerHTML = `
        <tr><th>English</th><th>${langSelect.options[langSelect.selectedIndex].text.split(" ")[0]
    }</th></tr>
        ${tableRows}
      `;
  categoryLabel.textContent =
    contextSelect.options[contextSelect.selectedIndex].text;
}

langSelect.addEventListener("change", renderTable);
contextSelect.addEventListener("change", renderTable);

renderTable();