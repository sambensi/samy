function changeCol(){
	var couleur = document.getElementById('img').getAttribute("style").background;
	
	alert(couleur);
	
	if(couleur == "white")
	{
		document.getElementById('img').style.backgroundColor = "green";
	}
	elseif(couleur == "green")
	{
		document.getElementById('img').style.backgroundColor = "red";
	}
	elseif(couleur == "red")
	{
		document.getElementById('img').style.backgroundColor = "white";
	}
		

};