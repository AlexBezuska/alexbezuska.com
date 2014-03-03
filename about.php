<html>
<head>
  <title>Alex Bezuska | About</title>
  <link rel="stylesheet" href="css/main.css"/>
</head>

<body class="pageAbout">
  <div class="page">
  <?php include('nav.php'); ?>

    <div class="grid">
      <div class="col1-2">
        <section class="logo module">
          <img src="img/glasses-grey.png" alt="Alex Bezuska Web Designer Louisville Kentucky"/><br/>
          <h1>Alex Bezuska</h1>
        </section>
      </div>
      <div class="col1-2">
        <section class="social module">
          <?php include('social.php'); ?>
        </section>
      </div>
    </div>

    <div class="grid">
      <div class="col2-3">
        <section class="module">
          <p>
            I am a web designer, developer, and indie game illustrator with a passion for when art and technology work together for a purpose. A transplant to Louisville from Orange County, CA, I have discovered the extraordinary beauty of all four seasons and life without a great deal of traffic.
          </p>
           <p>
I am completely self taught and I strongly believe that with dedication and time I can learn just about anything. I believe that web design is much more than a coat of paint.  Design and user experience must create a seamless flow from the creator to the user in order to convey the website's message. Please take some time to check out <a href="http://portfolio.alexbezuska.com" target="_blank">my work</a>, learn about <a href="http://blog.alexbezuska.com/" target="_blank">my passions</a>, and feel free to contact me with any additional questions.
          </p>
        </section>
      </div>
       <div class="col1-3">
        <section class="photo module">
          <img src="img/alex-bezuska-photo.png" alt="Alex Bezuska Web Designer Louisville Kentucky"/>
        </section>
      </div>
    </div>

    <!-- <div class="grid interests">
      <div class="col1-3">
        <section class="code module">
           <h3>Code</h3>
        </section>
      </div>
      <div class="col1-3">
        <section class="games module">
           <h3>Game Design</h3>
        </section>
      </div>
      <div class="col1-3">
        <section class="art module">
          <h3>Art</h3>
        </section>
      </div>
    </div>-->

    <div class="grid">
         <div class="col1-2">
        <section class="module">
          <h3>Work Experience</h3>
            <p>
            I am currently employed at Mortenson Dental Partners, and previous to that I worked as an independant web designer for five years. My current position has taught me to work well on a team, and my freelance work taught me to how work one-on-one with the client.
            </p>
            <p>
           I work directly with three asp.net C# developers. In order to efficiently combine my front end work with their server-side code I have to not only be comfortable with, but I have also learned C# as well. I have experience  SQL server, mySQL and I am currently experimenting with MongoDB.
            </p>
            <p>Working directly with clients you acquire people skills, learn how to draw up proposals and contracts, and how to quickly resolve conflicts. Understanding the user's need for a project and how that intersects with your client's requests is a delicate balance I have had to refine throughout my work experience. I fight to make things simple and intuitive for the user, even if it creates more work for me, but I believe that in the end it pays off.
            </p>
        </section>
      </div>
  

      <div class="col1-2">
        <section class="module">
          <h3>Personal Experience</h3>
          <p>
          In my time as an independant web designer the most important skill I have learned is understanding the client's needs. This is why I am passionate about UX.</p>
          <p>
          I am a constant learner and have kept myself motivated by starting and sustaining <a href="http://jslou.org/" target="_blank">JSLou</a>, a local meetup group focused on JavaScript. Through JSLou I have met many experienced developers and I have enjoyed the opportunity to learn from them as I gain a better understanding of JavaScript.
          </p>
          <p>
          To keep my skills current and to sustain my passion about design and programming, I like to keep a few personal projects going at all times.  My current project is developing HTML5/JavaScript games.  I am able to execute ideas by mixing art and technology to create games that entertain and excite players. 
          </p>
        </section>
      </div>
     </div>

    <div class="chart grid">
      <div class="col2-3">
        <section class="module">
          <h3>Skills</h3>
          <img src="img/skillsChart.png" alt="Alex Bezuska Web Designer Louisville Kentucky"/>
<!--<canvas class="dounutChart" id="skillsChart-html" width="500" height="500"></canvas>
 <canvas class="dounutChart" id="skillsChart-csssass" width="100" height="100"></canvas>
<canvas class="dounutChart" id="skillsChart-javascript" width="100" height="100"></canvas>
<canvas class="dounutChart" id="skillsChart-csharp" width="100" height="100"></canvas>
<canvas class="dounutChart" id="skillsChart-webDesign" width="100" height="100"></canvas>
<canvas class="dounutChart" id="skillsChart-userExperience" width="100" height="100"></canvas>
 -->
        </section>
      </div>
      <div class="col1-3">
        <section class="module">
          <h3>Software</h3>
<!--   <canvas class="softwareChart" id="softwareChart" width="450" height="200"></canvas>-->
<img src="img/softwareChart.png" alt="Alex Bezuska Web Designer Louisville Kentucky"/>
        </section>
      </div>
    </div>

     <div class="grid">
      <div class="col1-3">
        <section class="module">
          <br/>
        </section>
      </div>
      <div class="col2-3">
        <section class="module">
          <br/>
        </section>
      </div>
    </div>

    <div class="connect grid">
      <h3>Connect</h3>
      <div class="col1-3">
        <section class="module">
         <h2>twitter: <em>@alexbezuska</em></h2>
        </section>
      </div>
      <div class="col1-3">
        <section class="module">
         <h2>email: <em>abezuska@gmail.com</em></h2>
        </section>
      </div> 
       <div class="col1-3">
        <section class="module">
         <h2>mobile: <em>714 423 7411</em></h2>
        </section>
      </div> 
    </div>
      
    <?php include('footer.php'); ?>
</div> 
<script src="http://ajax.googleapis.com/ajax/libs/jquery/1.9.1/jquery.min.js"></script>
<script>window.jQuery || document.write('<script src="js/jquery.min.js"><\/script>')</script>
<script src="js/Chart.min.js"></script>
<script src="js/softwareChart.js"></script>
<script src="js/skillsChart.js"></script>
</html>


