/**
 * Tournament Countdown Timer
 * Target Date: October 31, 2026 00:00:00
 */
document.addEventListener('DOMContentLoaded', function() {
  const targetDate = new Date('October 31, 2026 00:00:00').getTime();

  const daysElement = document.getElementById('days');
  const hoursElement = document.getElementById('hours');
  const minutesElement = document.getElementById('minutes');
  const secondsElement = document.getElementById('seconds');

  if (!daysElement || !hoursElement || !minutesElement || !secondsElement) {
    return; // Element not found on this page
  }

  function updateTimer() {
    const now = new Date().getTime();
    const difference = targetDate - now;

    if (difference <= 0) {
      document.querySelector('.countdown-box').innerHTML = '<h3>THE TOURNAMENT HAS STARTED!</h3>';
      return;
    }

    // Time calculations
    const days = Math.floor(difference / (1000 * 60 * 60 * 24));
    const hours = Math.floor((difference % (1000 * 60 * 60 * 24)) / (1000 * 60 * 60));
    const minutes = Math.floor((difference % (1000 * 60 * 60)) / (1000 * 60));
    const seconds = Math.floor((difference % (1000 * 60)) / 1000);

    // Output results with leading zeroes if needed
    daysElement.innerText = days;
    hoursElement.innerText = hours.toString().padStart(2, '0');
    minutesElement.innerText = minutes.toString().padStart(2, '0');
    secondsElement.innerText = seconds.toString().padStart(2, '0');
  }

  // Initial call and run every second
  updateTimer();
  setInterval(updateTimer, 1000);
});
