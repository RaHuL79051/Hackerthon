
document.getElementById("login").addEventListener("click", function () {
  window.location.href = "login.html";
});

document.querySelectorAll(".feature-card").forEach((card) => {
  card.addEventListener("click", function () {
    window.location.href = "login.html";
  });
});

const founder = document.querySelector(".founder");
founder.addEventListener("click", function () {
  window.location.href =
    "https://rahul79051.github.io/portfolio.github.io/?fbclid=PAZXh0bgNhZW0CMTEAAaevPC49KJ8YR4ZHxUBTQk7GjoQb62dM42m-BxKhwWpNMyQH_ZK25co96nBHIw_aem_IJusVBOH-FHyVdpA0rdUGg";
});

gsap.to(".overlay", {
  duration: 4,
  opacity: 0,
  display: "none",
  ease: "power2.inOut",
});