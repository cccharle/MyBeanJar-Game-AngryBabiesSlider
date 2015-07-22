<html>
<head>
<script>
alert("Thank you for your Purchase.Your poster	Will be	emailed	To you soon!");
window.onload = function(){
	 if(window.opener){
		window.close();
	} 
	 else{
		 if(top.dg.isOpen() == true){
              top.dg.closeFlow();
              return true;
          }
     }                              
};
</script>
</head>
<body>
</body>
</html>