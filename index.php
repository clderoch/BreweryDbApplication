<!DOCTYPE html>
<html>
  <head>
    <title>Distilled Brewery</title>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
	<link rel="shortcut icon" href="images/logo.png">
	
	
	<!-- Latest compiled and minified bootstrap CSS -->
	<link rel="stylesheet" href="http://maxcdn.bootstrapcdn.com/bootswatch/3.3.2/cyborg/bootstrap.min.css">

	<!-- bootstrap jQuery library -->
	<script src="https://ajax.googleapis.com/ajax/libs/jquery/1.11.1/jquery.min.js"></script>

	<!-- Latest compiled bootstrap JavaScript -->
	<script src="http://maxcdn.bootstrapcdn.com/bootstrap/3.2.0/js/bootstrap.min.js"></script>
	
	<!-- AJAX function to retrieve random beer and get list of beers from particular brewery using the breweryDb api-->
	<script type = "text/javascript">
		var breweryId = "";
		
		function randomBeer() {
			var nameTag = document.getElementById("name");
			var descTag = document.getElementById("description");
			var image = document.getElementById("beerIcon");
			$.ajax( { 
				type : 'POST',
				data : {},
				url  : 'php/random.php',             
				success: function (response) {
					console.log(response);
					var beer = JSON.parse(response);
					breweryId = beer.data.breweries[0].id;
					var beerName = beer.data.name;	
					var beerDescription = beer.data.description;
					
					var label = beer.data.labels.medium;
					
					if(typeof beerDescription == 'undefined' || !beerDescription){
						randomBeer();
					}else{
						nameTag.innerHTML = beerName.toString();
						descTag.innerHTML = beerDescription.toString();
						console.log(label);
						image.src = label;
						
					}
				},
				
				error: function (xhr) {
					alert("error retrieving data from the API");
				}
			});
		}

		function brewerySearch(){
			var container = document.getElementById("searchResults");
			container.innerHTML = "";
				$.ajax({ 
					type : 'POST',
					data : {
							breweryId: breweryId
						   },
					url  : 'php/breweries.php',             
					success: function (response) {
						var search = JSON.parse(response);
						for(i=0;i<search.data.length;i++){
							if (typeof search.data[i].description != 'undefined'&&typeof search.data[i].labels != 'undefined'){ 
								var name = search.data[i].name;
								var desc = search.data[i].description;
								var label = search.data[i].labels.medium;
								container.innerHTML += '<div class="jumbotron" style="min-height: 200px;padding: 15px; text-align:center;"> <div class="col-md-2"><img src="'+label+'" alt="" style="height:120px;width:120px;" class="img-rounded"></div><div class="col-md-8"><p id="name" style="font-size:20px;">'+name+'</p></br><p id="description" style="font-size:13px;">'+desc+'</p></div></div>'; 
							}								
						}
					},
					
					error: function (xhr) {
						alert("error retrieving data from the API");
					}
				});
		}
						
    </script>
	
	<!-- AJAX function to search for beer and breweries using the breweryDb api-->
	<script type = "text/javascript">
		function searchBeer() {
			var container = document.getElementById("searchResults");
			container.innerHTML = "";
			var searchCat = document.querySelector('input[name="type"]:checked').value.toString();
			var userInput = $("#searchTerm").val();
			var searchVal = userInput;
			if(searchVal != "" && /^[a-zA-Z0-9-' ]*$/.test(searchVal) == true){
				$.ajax({ 
					type : 'POST',
					data : {
							name: searchVal,
							type: searchCat
						   },
					url  : 'php/search.php',             
					success: function (response) {
						console.log(response);
						var search = JSON.parse(response);
						
						if(typeof search.data == 'undefined'){ 
							container.innerHTML += '<div class="jumbotron" style="min-height: 150px;padding: 15px; text-align:center;"><h3 style="text-align:center;">No search results for item: '+searchVal+'</h3></div>'; 
						}else if(typeof search.data[0].labels != 'undefined'){
							for(i=0;i<search.data.length;i++){
								if (typeof search.data[i].description != 'undefined'){ 
									var name = search.data[i].name;
									var desc = search.data[i].description;
									var label = search.data[i].labels.medium;
									container.innerHTML += '<div class="jumbotron" style="min-height: 200px;padding: 15px; text-align:center;"> <div class="col-md-2"><img src="'+label+'" alt="" style="height:120px;width:120px;" class="img-rounded"></div><div class="col-md-8"><p id="name" style="font-size:20px;">'+name+'</p></br><p id="description" style="font-size:13px;">'+desc+'</p></div></div>'; 
								}
							}
						}else if(typeof search.data[0].images != 'undefined'){
							for(i=0;i<search.data.length;i++){
								if (typeof search.data[i].description != 'undefined'){ 
									var name = search.data[i].name;
									var desc = search.data[i].description;
									var image = search.data[i].images.medium;
									container.innerHTML += '<div class="jumbotron" style="min-height: 150px;padding: 15px; text-align:center;"> <div class="col-md-2"><img src="'+image+'" alt="" style="height:120px;width:120px;" class="img-rounded"></div><div class="col-md-8"><p id="name" style="font-size:20px;">'+name+'</p></br><p id="description" style="font-size:13px;">'+desc+'</p></div></div>'; 
								}
							}
						}
					},
					
					error: function (xhr) {
						alert("error retrieving data from the API");
					}
				});
			}else{ 
				alert("Invalid search. Please try again!");
			}	
		}				
	</script>
	
	<body onload = "randomBeer()">	
		<!-- nav bar layout -->
		<nav class="navbar navbar-inverse">
			<div class="container-fluid">
				<div class="navbar-header">
					<a class="navbar-brand" href="index">Distilled Brewery</a>
				</div>
			<div class="collapse navbar-collapse" id="myNavbar">
				<ul class="nav navbar-nav navbar-right">
					<li>
						<form class="navbar-form" method="post" onsubmit="return false" role="search">
							<div class="input-group">
								<input type="radio" name="type" value="beers" id="beerType" checked>Beer<br>
								<input type="radio" name="type" value="breweries" id="breweryType">Brewery<br>
							</div>
							<div class="input-group">
								<input type="text" class="form-control" placeholder="Search" name="searchTerm" id="searchTerm">
								<div class="input-group-btn">
									<button class="btn btn-default" onclick="return searchBeer()"name ="submit" type="button"><i class="glyphicon glyphicon-search"></i></button>
								</div>
							</div>
						</form>
					</li>
				</ul>
			</div>
		</nav>
		
		<!-- Random beer section -->
		<div class="container">
			<h2>Distilled SCH Beer Application</h2>			
			<div class="jumbotron" style="min-height: 200px;padding: 15px;"> 
				<div class="col-md-2">
					<p id="name" style="font-size:12px;"></p>
					<img id="beerIcon" src="" alt="" style="height:120px;width:120px;" class="img-rounded"/>
					<p id="brewId" style="font-size:8px;"></p>
				</div>
				
				<div class="col-md-8">
					<p id="description" style="font-size:13px;"></p>
				</div>
				
				<div class="col-md-2">
					<button type="button" onclick="randomBeer()" class="btn btn-primary btn-sm" data-toggle="modal" data-target="#myModal" style="font-size:20px;width:170px;height:70px;"value="Another Beer">Another Beer</button>
					</br></br>
					<button type="button" onclick="brewerySearch()" class="btn btn-primary btn-sm" data-toggle="modal" data-target="#myModal" style="font-size:20px;width:170px;height:70px;">More from this <br/>brewery</button>
				</div>
			</div>
		</div>
		
		</br>
		
		<!-- Search result box -->
		<div class="container" id="searchContainer">	
			<h3>Search Results:</h3>
			<div class="container" id="searchResults"></div>		
		</div>
		
		<!-- Footer -->
		<div class="clearfix visible-lg"></div>
			<center>
				<footer class ="footer">
					<div class = "container">
						<p class=" text-muted">Distilled Brewery &copy; 2017</p>
					</div>
				</footer>
			</center>
		</div>
    
	<script src="https://ajax.googleapis.com/ajax/libs/jquery/1.11.1/jquery.min.js"></script>
    <script src="http://maxcdn.bootstrapcdn.com/bootstrap/3.2.0/js/bootstrap.min.js"></script>
  </body>
</html>