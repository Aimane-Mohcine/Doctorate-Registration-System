<xsl:stylesheet version="1.0" xmlns:xsl="http://www.w3.org/1999/XSL/Transform">
  <xsl:output method="html" indent="yes"/>



  <!-- Template principal pour démarrer la transformation -->
  <xsl:template match="/">
    <html>
      <head>
				<link href="https://cdn.jsdelivr.net/npm/bootstrap@5.2.3/dist/css/bootstrap.min.css" rel="stylesheet" integrity="sha384-rbsA2VBKQhggwzxH7pPCaAqO46MgnOM80zW1RWuH61DGLwZJEdK2Kadq2F9CUG65" crossorigin="anonymous"/>
        <title> calendrier</title>
  				<style>
				
    				.carousel-control-prev, .carousel-control-next {
      					opacity: 25%;
    				}
    				.salle-footer, .themeTable,.gg{
      					display: none;
    				}
    				.salle-footer.active, .themeTable.active,.gg.active{
      					display: block;
    				}
           .themeBtn {
    display: block;
    margin-bottom: 5px;
    cursor: pointer; /* Add cursor style */
}
  				</style>
      </head>
      <body style="background-color: #bacade;">
        <nav class="navbar p-3 navbar-expand-sm bg-light navbar-dark  shadow">
  					<div class="container-fluid">
    					<img src="r.jpeg" class="float-start"/>
    					<ul class="nav flex-column container-fluid">
    						<li class="nav-item text-center">
      							<h3 class="text-capitalize"> Calendrier des examens oraux de doctorat  </h3>
    						</li>
    						<li class="nav-item p-2">
                  <div class="container">
                      <div class="row row-cols-auto justify-content-start align-items-center">
                    <xsl:for-each select="//submission[not(theme = preceding-sibling::submission/theme)]">
                    <xsl:variable name="currentTheme" select="normalize-space(theme)"/>
                    <xsl:variable name="cr" select="position()"/>
                    <button type="button" class="themeBtn btn btn-outline-success m-1 " data-theme="{$currentTheme}" onclick="showTable('{$cr}'),ggg({$cr}),showSalle({$cr}) ,toggleActive({$cr})">
                      <xsl:value-of select="$currentTheme"/>
                    </button>
                  </xsl:for-each>
                    </div>
                  </div>
    						</li>
  						</ul>
    					<img src="r3.jpeg" class="float-end"/>
  					</div>				
				</nav>
        <!-- Group submissions by theme -->
        <div class="container-md d-flex justify-content-center align-items-center p-3" style="background-color: #bacade; min-height: 100vh;">
  					<div  class="card container " >
    					<div id="car" class="carousel slide carousel-fade m-3" >
                
                <div class="card-body">
                  <xsl:for-each select="//submission[not(disciplines = preceding-sibling::submission/disciplines)]">
                  <xsl:variable name="currentD" select="disciplines"/>
                  <xsl:for-each select="//submission[not(theme = preceding-sibling::submission/theme)][disciplines = $currentD]">
                  <xsl:variable name="currentTheme" select="theme"/>
                  <div class="themeTable">
                  <p class="text-center fs-3 m-3 border-bottom text-muted">Theme: <xsl:value-of select="$currentTheme"/></p>
                  <xsl:call-template name="generateTables">
                  <xsl:with-param name="theme" select="$currentTheme"/>
                  <xsl:with-param name="submissions" select="//submission[theme=current()/theme]"/>
                  <xsl:with-param name="dayName" select="'Monday'"/>
                  </xsl:call-template>
                  </div>
                  </xsl:for-each>
                  </xsl:for-each>
                </div>
                <div class="card-footer">
    							<xsl:for-each select="//submission[not(theme = preceding-sibling::submission/theme)]">
                  <div class="salle-footer ">
    								<h3 class="container text-danger d-flex justify-content-center align-items-center">Salle <xsl:value-of select="position()"/></h3>
  								</div>
                  </xsl:for-each>
  							</div>
              </div>
            </div>
				</div>
        <script>
        	function showSalle(index) {
    					const salleFooters = document.querySelectorAll('.salle-footer');
						
    					salleFooters.forEach((footer, i) => {
      					if (i === index - 1) {
        					footer.classList.add('active');
      					} else {
        					footer.classList.remove('active');
      					}
    					});
  					}
          function ggg(index){
						const ggs = document.querySelectorAll('.gg');
						ggs.forEach((gg, i) => {
      					if (i === index - 1) {
        					gg.classList.add('active');
      					} else {
        					gg.classList.remove('active');
      					}
    					});
					}
          function showTable(index){
						const themeTables = document.querySelectorAll('.themeTable');
						themeTables.forEach((themeTable, i) => {
      					if (i === index - 1) {
        					themeTable.classList.add('active');
      					} else {
        					themeTable.classList.remove('active');
      					}
    					});
					}
           function toggleActive(btnIndex) {
    					// Get all buttons
    					var buttons = document.querySelectorAll('.btn-group button');

    					// Loop through each button
    					buttons.forEach(function(button, index) {
      					if (index + 1 === btnIndex) {
        					// Activate the clicked button
        					button.classList.add('active');
      					} else {
        					// Deactivate other buttons
        					button.classList.remove('active');
      					}
    					});
					  }
        </script>
        <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.2.3/dist/js/bootstrap.bundle.min.js" integrity="sha384-kenU1KFOnpD lijXkOYC+UnjQ7+T9yRoy4IWgjA9y5zY1NLojk4+t4+/pSlHdYvD6" crossorigin="anonymous"></script>
				<script src="https://cdn.jsdelivr.net/npm/bootstrap@5.0.2/dist/js/bootstrap.bundle.min.js" integrity="sha384-MrcW6ZMFYlzcLA8Nl+NtUVF0sA7MsXsP1UyJoMp4YLEuNSfAP+JcXn/tWtIaxVXM" crossorigin="anonymous"></script>
  			<script src="https://cdn.jsdelivr.net/npm/@popperjs/core@2.9.2/dist/umd/popper.min.js" integrity="sha384-IQsoLXl5PILFhosVNubq5LC7Qb9DXgDA9i+tQ8Zj3iwWAwPtgFTxbJ8NT4GN1R8p" crossorigin="anonymous"></script>
  			<script src="https://cdn.jsdelivr.net/npm/bootstrap@5.0.2/dist/js/bootstrap.min.js" integrity="sha384-cVKIPhGWiC2Al4u+LWgxfKTRIcfu0JTxR+EQDz/bgldoEyl4H0zUF0QKbrJ0EcQF" crossorigin="anonymous"></script>

      </body>
    </html>
  </xsl:template>

  <!-- Template pour générer les tableaux -->
  <xsl:template name="generateTables">
    <xsl:param name="theme"/>
    <xsl:param name="submissions"/>
    <xsl:param name="dayName"/>
    <xsl:param name="startIndex" select="1"/>
    <xsl:variable name="numSubmissions" select="count($submissions)"/>
    <xsl:variable name="end" select="($startIndex + 5)"/>
    <xsl:variable name="endIndex" select="($startIndex + 11)"/>

    <xsl:if test="$startIndex &lt;= $numSubmissions">
     
     <p class="text-center fs-3 m-3 border-bottom"><xsl:value-of select="$dayName"/></p>
      <table class="table ">
        <thead>
        <tr>
            <th class = "p-1">9:00h to 9:20h</th>
            <th class = "p-1">9:20h to 9:40h</th>
						<th class = "p-1">9:40h to 10:00h</th>
            <th class = "p-1">10:30h to 10:50h</th>
            <th class = "p-1">11:10h to 11:30h</th>
            <th class = "p-1">11:30h to 11:50h</th>
        </tr>
        </thead>
        <tbody>
        <tr>
        <!-- Loop over submissions for the current "day" -->
        <xsl:for-each select="$submissions[position() &gt;= $startIndex and position() &lt;= $end]">
          <!-- Display first name and last name -->
          
              <td><xsl:value-of select="concat(firstauthor/firstname,' ',firstauthor/lastname)"/></td>
          
          
        </xsl:for-each>
        </tr>
        </tbody>
      </table>
      <table class="table ">
					<thead>
            <tr>
              <th class = "p-1">14:00h to 14:20h</th>
              <th class = "p-1">14:20h to 14:40h</th>
							<th class = "p-1">14:40h to 15:00h</th>
              <th class = "p-1">15:30h to 15:50h</th>
              <th class = "p-1">16:10h to 16:30h</th>
              <th class = "p-1">16:50h to 17:10h</th>
            </tr>
					</thead>
					<tbody>
            <tr>
              <!-- Loop over submissions for the current "day" -->
              <xsl:for-each select="$submissions[position() &gt;= $end+1 and position() &lt;= $endIndex]">
              <!-- Display first name and last name -->
          
              <td><xsl:value-of select="concat(firstauthor/firstname,' ',firstauthor/lastname)"/></td>
              </xsl:for-each>
            </tr>
          </tbody>
      </table>
      <!-- Recursively call the template for the next set of submissions -->
      <xsl:variable name="nextDay">
          <xsl:choose>
            <xsl:when test="$dayName = 'Monday'">Tuesday</xsl:when>
            <xsl:when test="$dayName = 'Tuesday'">Wednesday</xsl:when>
            <xsl:when test="$dayName = 'Wednesday'">Thursday</xsl:when>
            <xsl:when test="$dayName = 'Thursday'">Friday</xsl:when>
            <xsl:when test="$dayName = 'Friday'">Saturday</xsl:when>
            <xsl:when test="$dayName = 'Saturday'">Sunday</xsl:when>
            <xsl:otherwise>Monday</xsl:otherwise>
          </xsl:choose>
      </xsl:variable>
      <xsl:call-template name="generateTables">
        <xsl:with-param name="theme" select="$theme"/>
        <xsl:with-param name="submissions" select="$submissions"/>
        <xsl:with-param name="startIndex" select="$endIndex + 1"/>
        <xsl:with-param name="dayName" select="$nextDay"/>
      </xsl:call-template>
    </xsl:if>
  </xsl:template>
</xsl:stylesheet>
