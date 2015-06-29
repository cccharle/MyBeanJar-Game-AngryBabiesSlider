Ab folder contains front end code
webservice folder contains your webservice code
sql fodler contains database file 

changes you have to done
replace front end with ab folder
after that open index.php file in front end change paypal button if
(find <input type="hidden" name="hosted_button_id" value="P38PFY6CF8VF2"> in and replace your paypal hosted button id like <input type="hidden" name="hosted_button_id" value="your paypal button id">);

Databse change
upload one sql table to your database which is in sql folder.

webservice folde changes
replace both file with our file 
src/MyBeanJar/ApiBundle/ApiService/ApiServiceVersion2.php
src/MyBeanJar/CoreBundle/Model/UserModel.php

paypal instruction
create paypal buy now button.
in set sucess url , notification url and cancel url
Sucess url : yoursite.com/paypal/success.php
cancel url : yoursite.com/paypal/cancel.php
notification url : yoursite.com/paypal/ipn.php

Note : paypal folder is already is in our ab folders also please change database connection in paypal folder ipn.php file if needed.


