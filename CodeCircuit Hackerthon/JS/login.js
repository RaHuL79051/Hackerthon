document.addEventListener('DOMContentLoaded', function () {
  const loginForm = document.getElementById('loginForm');
  if (loginForm) {
    loginForm.addEventListener('submit', function (e) {
      e.preventDefault();
      const username = document.getElementById('username').value.toLowerCase();
      const password = document.getElementById('password').value;

      if (username === 'admin' && password === '1234') {
        localStorage.setItem('loggedIn', 'true');
        window.location.href = 'dashboard.html';
      } else {
        document.getElementById('loginError').style.display = 'block';
      }
    });
  }

  const logoutBtn = document.querySelector('.logout-btn');
  if (logoutBtn) {
    logoutBtn.addEventListener('click', function () {
      localStorage.removeItem('loggedIn');
    });
  }
});

const flipCard = document.getElementById("flipCard");
let flipped = false;

flipCard.addEventListener("click", () => {
  flipped = !flipped;
  gsap.to(flipCard, {
    duration: 0.8,
    rotationY: flipped ? 180 : 0,
    ease: "power2.inOut"
  });
});
