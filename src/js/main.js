
window.ga = function () { ga.q.push(arguments) }; ga.q = []; ga.l = +new Date;
ga('create', '{{google-analytics-id}}', 'auto'); ga('send', 'pageview')


const currentYear = new Date().getFullYear();

const yearsPhotoshop = currentYear - 1996;
const yearsGit = currentYear - 2014;
const yearsFrontend = currentYear - 2009;


document.addEventListener('DOMContentLoaded', () => {
  document.getElementById('years-photoshop').innerText = `${yearsPhotoshop}+ years of Adobe Software experience including Photoshop, Illustrator, and more.`;
  document.getElementById('years-git').innerText = `${yearsGit}+ years Git version control experience.`;
  document.getElementById('years-frontend').innerText = `${yearsFrontend}+ years front-end web development experience, creating and refining beautiful, functional, and most importantly usable interfaces.`;
});




function scrolled(direction, selector){
  var scroll = $(window).scrollTop();
  var os = $(selector).offset().top;
  var ht = $(selector).height();
  return direction === "below" ? scroll > os + ht : scroll < os + ht;
}



const card = document.querySelector('.float-card');
const container = document.querySelector('.card-container');

document.addEventListener('mousemove', (e) => {
  const { innerWidth: w, innerHeight: h } = window;
  const x = (e.clientX / w - 0.5) * 2; // from -1 to 1
  const y = (e.clientY / h - 0.5) * 2;

  const rotateX = y * 10; // max tilt angle
  const rotateY = -x * 10;

  card.style.transform = `rotateX(${rotateX}deg) rotateY(${rotateY}deg)`;
});


function shuffleStack(stack) {
  const images = Array.from(stack.querySelectorAll('.polaroid'));
  if (images.length <= 1) return;

  // Move the top image to the back of the stack
  const top = images.pop();
  stack.removeChild(top);
  stack.insertBefore(top, images[0]);

  // Reassign classes to update z-index + rotation
  const updated = Array.from(stack.querySelectorAll('.polaroid'));
  updated.forEach((img, i) => {
    img.style.setProperty('--angle', `${[-6, 3, -2, 5, -4][i % 5]}deg`);
    img.style.setProperty('--depth', `${i * 10}px`);
    img.style.zIndex = i + 1;
  });
}

