
window.ga = function () { ga.q.push(arguments) }; ga.q = []; ga.l = +new Date;
ga('create', '{{google-analytics-id}}', 'auto'); ga('send', 'pageview')


const currentYear = new Date().getFullYear();

const yearsPhotoshop = currentYear - 1995;
const yearsGit = currentYear - 2013;
const yearsFrontend = currentYear - 2009;


document.addEventListener('DOMContentLoaded', () => {
  document.getElementById('years-photoshop').innerText = `${yearsPhotoshop}+ years of Adobe Software experience including Photoshop, Illustrator, and more.`;
  document.getElementById('years-git').innerText = `${yearsGit}+ years Git version control experience.`;
  document.getElementById('years-frontend').innerText = `${yearsFrontend}+ years front-end web development experience, creating and refining beautiful, functional, and most importantly usable interfaces.`;
});


$(function(){
  $('.navbar-nav>li>a').on('click', function(){
    $('.navbar-collapse').collapse('hide');
  });
  
  $(window).scroll(function() {
    if(scrolled('below', '.bg-image-alex')){
      $('body').removeClass('bg-blue-sm');
      $('.navbar').addClass('bg-blue');
      $('.navbar').removeClass('bg-grey');
      $('.jumbotron').addClass('darken-jumbotron');
    }
    
    if(scrolled('above', '.bg-image-alex')){
      $('body').addClass('bg-blue-sm');
      $('.navbar').removeClass('bg-blue');
      $('.navbar').addClass('bg-grey');
      $('.jumbotron').removeClass('darken-jumbotron');
    }
  });
});

function scrolled(direction, selector){
  var scroll = $(window).scrollTop();
  var os = $(selector).offset().top;
  var ht = $(selector).height();
  return direction === "below" ? scroll > os + ht : scroll < os + ht;
}
