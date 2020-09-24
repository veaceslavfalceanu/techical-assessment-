# Connect the dots GAME

#HTTP API 

###INSTRUCTIONS

1. Start Symfony local server by running <br>
symfony serve --no-tls

2. Open client make make request to <br>
http://localhost:8000/

Architecture decisions

The dot game has two entities <br>
1. Line
2. Matrix

Point qualifies for Value object that is used in Line Entity.
Every specific job is created as service.
1. NodeValidator (Takes care of all the Point Logic)
2. ResponseGenerator that keeps response content.
3. SessionManager keeps data.