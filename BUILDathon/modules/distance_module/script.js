document.addEventListener('DOMContentLoaded', () => {
  const form = document.querySelector('.distance-form');

  form.addEventListener('submit', () => {
    const button = form.querySelector('button[type="submit"]');
    button.disabled = true;
    button.textContent = 'Calculating...';
    button.style.backgroundColor = '#0096c7';
  });

  // Optional: Highlight nearby cards when hovered
  document.querySelectorAll('.place-card').forEach(card => {
    card.addEventListener('mouseenter', () => {
      card.style.backgroundColor = '#d0f0ff';
    });
    card.addEventListener('mouseleave', () => {
      card.style.backgroundColor = '#f1fcff';
    });
  });
});
