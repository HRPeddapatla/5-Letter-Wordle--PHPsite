Five-Letter Wordle Game By Hemanth Peddapatla

This PHP based Website is the adaptation of the classic New York Times backed 5 letter Wordle game. The game prompts the user to make a guess
starting from the landing page and will cap off the input field at 5 letter characters only. Prompting an error if the user types in integers
or special characters. Once the user presses submit on their guess, the game has started and the program will display to the user what his 
current guess is, how many attempts the user has to guess the right word, how many letters the user has in the correct spot, how many letters
the user has in an incorrect spot, the list of previous guesses made by the user, and finally an alphabetical representation of what letters
the user has gotten correct and what letters the user has gotten wrong.

Logic.php
This file contains the logic and backend functionality of the Five-Letter Wordle Game. 

This file is responsible for the functionality of the game, with user convenience as the highest priority. The file initiates the game by 
choosing a random word from the word array provided and initializes the arrays for the correct letters, incorrect letters, and the number of
attempts left for the user to guess. This code also provides the user feedback from the game as per their guess, based upon the guess they make
the game will provide the number of letters in the target word and if the letter is in the correct position or not. Once you are on this page,
the user is provided an input field with a five letter maximum length and buttons to provide navigation to the user. The buttons being the 
submit button and the restart game button that runs the code from the beginning and redirects the user to the landing page to start the game 
over. At the bottom of the page the user is also provided with the english alphabet as a representation of the letters that the user has 
guessed and is yet to guess. Once the number of attempts are exhausted or the target word has been guessed, the application will display the
target word for the user to see.

index.html
This file serves as the landing page for the Five-Letter Wordle Game. It includes a simple HTML form for submitting guesses to the Logic.php
file, which then starts the game.

This file is responsible for prompting the user to start the game and serves as the landing page for the user to start on. When the user arrives
onto the page, they are greeted with the title of the game and an input field that allows them to type in a five character long word that they 
can then submit using a button below the input field. Once the user clicks enter, the code will redirect the application from this index.html
page to the Logic.php file for the functionality of the game.


1. To play the game open your web browser and type in https://hpeddap.people.clemson.edu this url will take you to the landing page of the game.
2. Once you find yourself on the landing page, type a five letter word to guess the target word and press submit, the game will start.
3. Once the game has started, you can use the clues that the game provides you to guess the target word if it is not correct and keep guessing
until either the number of attempts alloted to the user has run out or the target word has been guessed.
4. The game may be restarted at anytime with a click of the restart game button.