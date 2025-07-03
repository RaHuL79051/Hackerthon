<!-- Tripify Enhanced Chatbot UI -->
<div id="chatbot">
  <div id="chat-header">
    Tripify Assistant 🤖
    <button id="close-chat">×</button>
  </div>
  <div id="chat-body"></div>
</div>

<button id="chat-icon">💬</button>

<style>
  #chatbot {
    position: fixed;
    bottom: 90px;
    right: 20px;
    width: 320px;
    background: #fff;
    border-radius: 16px;
    box-shadow: 0 8px 24px rgba(0, 0, 0, 0.2);
    display: none;
    flex-direction: column;
    z-index: 9999;
    font-family: 'Poppins', sans-serif;
    overflow: hidden;
    animation: slideUp 0.3s ease-out;
  }

  #chat-header {
    background: linear-gradient(135deg, #007bff, #6f42c1);
    color: white;
    padding: 12px 16px;
    font-weight: bold;
    font-size: 16px;
    display: flex;
    justify-content: space-between;
    align-items: center;
  }

  #close-chat {
    background: transparent;
    border: none;
    font-size: 20px;
    color: white;
    cursor: pointer;
  }

  #chat-body {
    padding: 16px;
    max-height: 350px;
    overflow-y: auto;
    background-color: #f9f9f9;
  }

  .chat-option {
    display: block;
    margin: 6px 0;
    background: #fff;
    border: 1px solid #ddd;
    padding: 10px 14px;
    border-radius: 10px;
    cursor: pointer;
    width: 100%;
    text-align: left;
    transition: 0.2s ease;
    font-size: 14px;
  }

  .chat-option:hover {
    background: #007bff;
    color: white;
    border-color: #007bff;
  }

  #chat-icon {
    position: fixed;
    bottom: 20px;
    right: 20px;
    background: #007bff;
    color: white;
    border: none;
    border-radius: 50%;
    font-size: 24px;
    width: 55px;
    height: 55px;
    z-index: 9998;
    cursor: pointer;
    box-shadow: 0 5px 12px rgba(0, 0, 0, 0.25);
    transition: transform 0.2s ease;
  }

  #chat-icon:hover {
    transform: scale(1.1);
  }

  .user-msg, .bot-msg {
    margin-bottom: 12px;
    font-size: 14px;
  }

  .user-msg {
    color: #198754;
    font-weight: 500;
  }

  .bot-msg {
    font-weight: bold;
  }

  @keyframes slideUp {
    from {
      transform: translateY(100%);
      opacity: 0;
    }
    to {
      transform: translateY(0%);
      opacity: 1;
    }
  }
</style>

<script>
  const chatbot = document.getElementById("chatbot");
const chatIcon = document.getElementById("chat-icon");
const closeChat = document.getElementById("close-chat");
const chatBody = document.getElementById("chat-body");

// Start with root node (no parent)
chatIcon.onclick = () => {
  chatbot.style.display = "flex";
  chatIcon.style.display = "none";
  chatBody.innerHTML = "";
  loadNode(null);
};
closeChat.onclick = () => {
  chatbot.style.display = "none";
  chatIcon.style.display = "block";
  chatBody.innerHTML = "";
};

function loadNode(node_id) {
  // Fetch current node (question or answer)
  fetch("modules/chatbot_module/fetch_node.php" + (node_id ? "?id=" + node_id : ""))
    .then(res => res.json())
    .then(node => {
      chatBody.innerHTML = "";
      if (node) {
        if (node.node_type === "question") {
          // Show question text
          const msg = document.createElement("div");
          msg.classList.add("bot-msg");
          msg.innerHTML = `<p>${node.question_text}</p>`;
          chatBody.appendChild(msg);

          // Fetch options (children)
          fetch("modules/chatbot_module/fetch_nodes.php?parent_id=" + node.id)
            .then(res => res.json())
            .then(options => {
              if (Array.isArray(options) && options.length > 0) {
                options.forEach(optNode => {
                  if (optNode.option_text) {
                    const btn = document.createElement("button");
                    btn.className = "chat-option";
                    btn.innerText = optNode.option_text;
                    btn.onclick = () => {
                      // Show user selection
                      const userMsg = document.createElement("div");
                      userMsg.classList.add("user-msg");
                      userMsg.innerHTML = `<p>You: ${optNode.option_text}</p>`;
                      chatBody.appendChild(userMsg);
                      // Load next node
                      loadNode(optNode.id);
                    };
                    chatBody.appendChild(btn);
                  }
                });
              } else {
                // No options available
                const msg = document.createElement("div");
                msg.classList.add("bot-msg");
                msg.innerHTML = `<p>No further options.</p>`;
                chatBody.appendChild(msg);
              }
              chatBody.scrollTop = chatBody.scrollHeight;
            });
        } else if (node.node_type === "answer") {
          // Show answer text
          const msg = document.createElement("div");
          msg.classList.add("bot-msg");
          msg.innerHTML = `<p>${node.answer_text}</p>`;
          chatBody.appendChild(msg);
          chatBody.scrollTop = chatBody.scrollHeight;
        }
      }
    })
    .catch(err => {
      console.error("Error loading node:", err);
      chatBody.innerHTML = `<p class="bot-msg">Sorry, something went wrong.</p>`;
    });
}



</script>